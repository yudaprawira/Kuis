<?php
namespace App\Http\Controllers\BE;

use App\Http\Requests,
    App\Models\System\Level,
    App\Models\System\Menu,
    App\Models\System\User,
    App\Models\System\Access,
    Yajra\Datatables\Datatables,
    Illuminate\Support\Facades\Schema,
    Illuminate\Database\Schema\Blueprint;

use Input, Hash, Lang, Redirect, Request, Response, Session;

class SystemController extends BaseController
{
    function __construct() {
        parent::__construct();        
    }
    
    /*
    |--------------------------------------------------------------------------
    | Management Menu
    |--------------------------------------------------------------------------
    */
    function menu()
    {
        $this->dataView['list'] = $this->buildListMenu();
        $this->dataView['form'] = $this->buildFormMenu();
        
        return view($this->tmpl . 'system.menu.index', $this->dataView);
    }
    private function buildListMenu($parent=0)
    {
        if ($menu = Menu::where('parent_id', $parent)->orderBy('order_num')->get() )
        {
            if(!empty($menu))
            {
                $level = getRowArray(Level::all(), 'id', 'name');
                
                $list = '';
                foreach( $menu as $m )
                {
                    $m = $m->toArray();
                    $m['sub'] = $this->buildListMenu($m['id']);
                    $m['icon']= $m['icon'] ? $m['icon'] : 'navicon'; 
                    $m['level'] = $level; 
                    $m['access'] = getRowArray(Access::where('menu_id', $m['id'])->get(), 'level_id', 'menu_id'); 
                    $list .= view($this->tmpl . 'system.menu.item', $m);
                }
                
                return $list ? '<ol class="dd-list">'.$list.'</ol>' : '';
            }
        }
    }
    
    /*
    |--------------------------------------------------------------------------
    | Build Form Menu
    |--------------------------------------------------------------------------
    */
    function buildFormMenu($id=0)
    {
        $data = Menu::find($id);
        
        $dataForm = $data ? $data->toArray() : []; 
        
        $dataForm['title'] = $data ? trans('global.form_edit') : trans('global.form_add');

        return view($this->tmpl . 'system.menu.form', $dataForm);
    }
    
    /*
    |--------------------------------------------------------------------------
    | Delete Menu
    |--------------------------------------------------------------------------
    */
    function deleteMenu($id)
    {
        return Response()->json([ 
            'status' => $this->_deleteData(new Menu(), $id), 
            'message'=> $this->_buildNotification(true),
            'list'   => base64_encode($this->buildListMenu())
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Save Data Menu Order
    |--------------------------------------------------------------------------
    */
    function saveOrder()
    {
        if( Input::get('data') )
        {
            $this->saveOrderRecursive(json_decode(Input::get('data'),true), 0);
        }
    }
    
    private function saveOrderRecursive($row, $parent=0)
    {
        if ( !empty($row) )
        {
            foreach( $row as $orderNum=>$r )
            {
                Menu::where('id', $r['id'])->update(['order_num'=>($parent+$orderNum), 'parent_id'=>$parent]);

                if (isset($r['children']))
                {
                    $this->saveOrderRecursive($r['children'], $r['id']);    
                } 
            }
        } 
    }
    
    /*
    |--------------------------------------------------------------------------
    | Save Access | Level Access to menu
    |--------------------------------------------------------------------------
    */
    function saveAccess($menuID)
    {
        Access::where('menu_id', $menuID)->delete();
        
        if ( Input::has('access') )
        {
            $access = Input::get('access');
            
            if ( !empty( $access ) )
            {
                foreach( $access as $k=>$v )
                {
                    Access::insert(['level_id'=>$k, 'menu_id'=>$menuID]);
                }
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Save Data | Insert Or Update
    |--------------------------------------------------------------------------
    */
    function saveMenu()
    {
        $input  = Input::except('_token');
        $module = isset($input['module']) ? $input['module'] : null;unset($input['module']);
        
        $status = $this->_saveData( new Menu(), [   
            //VALIDATOR
            "name" => "required|unique:menu". ($input['id'] ? ",name,".$input['id'] : '')
        ], $input);

        //Update Module
        if ( $input['id'] )
        {
            if ($menu = Menu::where('id', $input['id'])->first())
            {
                if ( $menu->module_name )
                {
                    $this->updateModule($menu->module_name, [
                        'active'=> $menu->status,
                        'name' => $input['name'],
                        'alias' => $input['url'],
                        'description' => $input['description'],
                    ]);
                }
            }
        }
        else
        {
            //create Module
            if ( $status && isset($module['check']) )
            {
                $sts = $this->createModule($input['url'],[
                    'name' => $input['name'],
                    'alias' => $input['url'],
                    'field_name' => str_slug($module['field'], '_'),
                    'field_alias' => $module['value'],
                    'description' => $input['description'],
                ], $module['type_module']);

                //updateMenu
                if (Menu::where('id', $status)->update(['module_name'=>$sts['modulePath']]))
                {
                    $rdr = BeUrl($input['url']);
                    $this->setNotif(trans('system/module.set_module', ['name'=>$input['name'], 'act'=>strtolower(trans('system/module.activate'))]), 'success');
                }
            }
        }

        //set Menu
        if (!$input['id'] && $status)
        {
            //set Access
            Access::insertGetId([
                'level_id' => Session::get('ses_level_id'),
                'menu_id' => $status,
            ]);

            //relogin
            $this->_setSessionLogin(User::where('id', Session::get('ses_userid'))->first());
        }
                
        return Response()->json([ 
            'rdr'    => isset($rdr) ? $rdr : null,
            'status' => $status, 
            'message'=> $this->_buildNotification(true),
            'form'   => $status ? base64_encode($this->buildFormMenu()) : null,
            'list'   => $status ? base64_encode($this->buildListMenu()) : null
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Management Module
    |--------------------------------------------------------------------------
    */
    function module()
    {
        if ( Request::isMethod('get') )
        {
            //get module
            $modules = glob( config('modules.paths.modules') . '/*/module.json');
            $rowMods = array();
            if ( !empty($modules) )
            {
                foreach( $modules as $r)
                {
                    $name = str_replace('/module.json', '', $r);
                    $name = explode('/', $name);
                    $name = end($name);
                    $json = file_get_contents($r);
                    $rowMods[$name] = json_decode($json, true);
                }
            }
            $this->dataView['rowMods'] = $rowMods;

            return view($this->tmpl . 'system.module.index', $this->dataView);
        }
        else
        {
            if ( in_array($_POST['act'], ["true","false"]) ) 
            {    
                if ( !empty($_POST['modules']) )
                {
                    $act = $_POST['act']=="true" ? trans('system/module.activate') : trans('system/module.inactivate'); 

                    foreach($_POST['modules'] as $m)
                    {
                        if ( $rowJson = $this->updateModule($m, ['active'=>($_POST['act']=="true"?1:0)]) )
                        {
                            //set menu
                            //check menu
                            if ( $_POST['act']=='true' && Menu::where('url', $rowJson['alias'])->count()==0)
                            {
                                //insert
                                $menuID = Menu::insertGetId([
                                    'parent_id' => 0,
                                    'order_num' => 0,
                                    'name' => $rowJson['name'],
                                    'url' => $rowJson['alias'],
                                    'icon' => 'file-o',
                                    'description' => $rowJson['description'],
                                    'status' => 1,
                                    'module_name' => $m,
                                    'created_by' =>  Session::get('ses_userid'),
                                    'created_at' => dateSQL(),
                                ]);

                                //set Access
                                Access::insertGetId([
                                    'level_id' => Session::get('ses_level_id'),
                                    'menu_id' => $menuID,
                                ]);
                            }
                            else
                            {
                                if ( $menu = Menu::where('url', $rowJson['alias'])->first() )
                                {
                                    //delete
                                    Menu::where("id", $menu->id)->update(['status'=>($_POST['act']=="true"?1:0)]);
                                }
                            }

                            //relogin
                            $this->_setSessionLogin(User::where('id', Session::get('ses_userid'))->first());
                            
                            
                            $this->setNotif(trans('system/module.set_module', ['name'=>$m, 'act'=>strtolower($act)]), 'success');
                        }
                        else
                        {
                            $this->setNotif(trans('system/module.notset_module', ['name'=>$m, 'act'=>strtolower($act)]), 'danger');
                        }
                    }
                }
                else
                {
                    $this->setNotif(trans('system/module.empty_module'), 'warning');
                }
            }
            else
            {
                $this->setNotif(trans('system/module.empty_action'), 'warning');
            }
            return Redirect( BeUrl('system-modules') );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Management Documentation
    |--------------------------------------------------------------------------
    */
    function documentation()
    {
        return view($this->tmpl . 'system.documentation.index', $this->dataView);
    }

    /*
    |--------------------------------------------------------------------------
    | Management User Level
    |--------------------------------------------------------------------------
    */
    function level($isTrash=false)
    {
        if ( Request::isMethod('get') )
        {
            $this->dataView['countAll'] = Level::where('status', '<>', '-1')->count();
            $this->dataView['countTrash'] = Level::where('status', '-1')->count();

            $this->dataView['isTrash'] = $isTrash;

            $this->dataView['form'] = $this->buildFormLevel();
            
            return view($this->tmpl . 'system.level.index', $this->dataView);
        }
        else
        {
            $rows = $isTrash ? Level::where('status', '-1') : Level::where('status', '<>', '-1');

            return Datatables::of($rows)->addColumn('action', function ($r) use ($isTrash) { return $this->_buildAction($r->id, $r->name, 'default', $isTrash); })
                                        ->editColumn('status', function ($r) { return $r->status=='1' ? trans('global.active') : trans('global.inactive'); })->make(true);
        }
    }

    public function levelTrash()
    {
        return $this->level(true);
    }
    
    /*
    |--------------------------------------------------------------------------
    | Build Form Level
    |--------------------------------------------------------------------------
    */
    function buildFormLevel($id=0)
    {
        $data = Level::find($id);
        
        $dataForm = $data ? $data->toArray() : []; 
        
        $dataForm['title'] = $data ? trans('global.form_edit') : trans('global.form_add');

        return view($this->tmpl . 'system.level.form', $dataForm);
    }
    
    /*
    |--------------------------------------------------------------------------
    | Delete Level
    |--------------------------------------------------------------------------
    */
    function deleteLevel($id)
    {
        return Response()->json([ 
            'status' => $this->_deleteData(new Level(), $id, (val($_GET, 'permanent')=='1' ? null : ['status'=>'-1'])), 
            'message'=> $this->_buildNotification(true)
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Restore Level
    |--------------------------------------------------------------------------
    */
    function restoreLevel($id)
    {
        return Response()->json([ 
            'status' => $this->_deleteData(new Level(), $id, ['status'=>'1']), 
            'message'=> $this->_buildNotification(true)
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Save Data | Insert Or Update
    |--------------------------------------------------------------------------
    */
    function saveLevel()
    {
        $input  = Input::except('_token');
        $input['status'] = val($input, 'status') ? '1' :'0';

        $status = $this->_saveData( new Level(), [   
            //VALIDATOR
            "name" => "required|unique:level". ($input['id'] ? ",name,".$input['id'] : '')
        ], $input);
                
        return Response()->json([ 
            'status' => $status, 
            'message'=> $this->_buildNotification(true),
            'form'   => $status ? base64_encode($this->buildFormLevel()) : null
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Management User Account
    |--------------------------------------------------------------------------
    */
    function user($isTrash=false)
    {
        if ( Request::isMethod('get') )
        {
            $this->dataView['countAll'] = User::where('status', '<>', '-1')->count();
            $this->dataView['countTrash'] = User::where('status', '-1')->count();

            $this->dataView['isTrash'] = $isTrash;

            $this->dataView['form'] = $this->buildFormUser();
            
            return view($this->tmpl . 'system.user.index', $this->dataView);
        }
        else
        {
            $rows = $isTrash ? User::where('status', '-1') : User::where('status', '<>', '-1');

            return Datatables::of($rows)
                             ->addColumn('action', function ($r) use ($isTrash) { return $this->_buildAction($r->id, $r->username, 'default', $isTrash); })
                             ->addColumn('last_login', function ($r) { return $r->last_login=='0000-00-00 00:00:00' ? '-' : date('j M Y H:i:s', strtotime($r->last_login)); })
                             ->editColumn('status', function ($r) { return $r->status=='1' ? trans('global.active') : trans('global.inactive'); })
                             ->removeColumn('password', 'hash')
                             ->make(true);
        }
    }

    public function userTrash()
    {
        return $this->user(true);
    }
    
    /*
    |--------------------------------------------------------------------------
    | Build Form User
    |--------------------------------------------------------------------------
    */
    function buildFormUser($id=0)
    {
        $data = User::find($id);
        
        $dataForm = $data ? $data->toArray() : [];
        
        $dataForm['level'] = getRowArray(Level::all(), 'id', 'name'); 
        
        $dataForm['title'] = $data ? trans('global.form_edit') : trans('global.form_add');

        return view($this->tmpl . 'system.user.form', $dataForm);
    }
    
    /*
    |--------------------------------------------------------------------------
    | Delete User
    |--------------------------------------------------------------------------
    */
    function deleteUser($id)
    {
        return Response()->json([ 
            'status' => $this->_deleteData(new User(), $id, (val($_GET, 'permanent')=='1' ? null : ['status'=>'-1'])), 
            'message'=> $this->_buildNotification(true)
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Restore User
    |--------------------------------------------------------------------------
    */
    function restoreUser($id)
    {
        return Response()->json([ 
            'status' => $this->_deleteData(new User(), $id, ['status'=>'1']), 
            'message'=> $this->_buildNotification(true)
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Save Data | Insert Or Update
    |--------------------------------------------------------------------------
    */
    function saveUser()
    {
        $input  = Input::except('_token');
        $input['status'] = val($input, 'status') ? '1' :'0';
        
        $exclude = ['password_confirmation'];
        $hash = ['password'];
        
        if ( !$input['password'] ) $exclude[] = 'password';
        
        $status = $this->_saveData( new User(), [   
            //VALIDATOR
            "username" => "required|unique:user". ($input['id'] ? ",id,.".$input['id'] : ''),
            "email" => "required|email|unique:user". ($input['id'] ? ",id,.".$input['id'] : ''),
            "password_confirmation"  => "same:password",
        ], $input, 'username', $exclude, $hash);
                
        return Response()->json([ 
            'status' => $status, 
            'message'=> $this->_buildNotification(true),
            'form'   => $status ? base64_encode($this->buildFormUser()) : null
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Update Module
    |--------------------------------------------------------------------------
    |@params $moduleName String, Ex : Page
    |@params $value Array
    */
    private function updateModule($moduleName, $value)
    {
        if ( file_exists( config('modules.paths.modules').'/'.$moduleName.'/module.json' ))
        {
            $file = config('modules.paths.modules').'/'.$moduleName.'/module.json';
            $json = file_get_contents($file);
            $rowJson = json_decode($json, true);
            
            $rowJson = array_merge($rowJson, $value);

            $json_string = json_encode($rowJson, JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK);

            //save Json
            if ( file_put_contents($file, $json_string) )
            {
                return $rowJson;
            }
        }
        else
        {
            $this->setNotif(trans('system/module.notfound_module', ['name'=>$moduleName]), 'danger');
        }
        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | create Module
    |--------------------------------------------------------------------------
    |@params $menuUrl String, Ex : Page
    |@params $value Array
    */
    private function createModule($menuUrl, $value, $type='full_page')
    {
        $moduleFrom = storage_path('module_creator/'.$type);
        $modulePath = str_replace(' ', '', ucwords(str_replace('-', ' ', $menuUrl)));
        $value['pt']= $modulePath;
        $value['sc']= strtolower($modulePath);
        $value['date'] = dateSQL();

        $return = ['dirs'=>[], 'files'=>[], 'modulePath'=>$modulePath];

        if ( !file_exists( config('modules.paths.modules').'/'.$modulePath ) )
        {
            //get template
            if( file_exists( $moduleFrom ) )
            {
                //create Module Directory
                if ( mkdir( config('modules.paths.modules').'/'.$modulePath, 0777, true ) )
                {
                    $dirsTop = glob($moduleFrom.'/*');
                    $dirsSub = glob($moduleFrom.'/**/*');
                    $dirsSb_ = glob($moduleFrom.'/**/**/*');
                    $dirsSb1 = glob($moduleFrom.'/**/**/**/*');
                    $dirsSb2 = glob($moduleFrom.'/**/**/**/**/*');
                    $dirsSb3 = glob($moduleFrom.'/**/**/**/**/**/*');
                    $dirsSb4 = glob($moduleFrom.'/**/**/**/**/**/**/*');
                    $dirsSb5 = glob($moduleFrom.'/**/**/**/**/**/**/**/*');
                    $dirsSb6 = glob($moduleFrom.'/**/**/**/**/**/**/**/**/*');
                    $dirsSb7 = glob($moduleFrom.'/**/**/**/**/**/**/**/**/**/*');
                    $dirsSb8 = glob($moduleFrom.'/**/**/**/**/**/**/**/**/**/**/*');
                    $dirsSb9 = glob($moduleFrom.'/**/**/**/**/**/**/**/**/**/**/**/*');
                    $dirsSb10 = glob($moduleFrom.'/**/**/**/**/**/**/**/**/**/**/**/**/*');
                    $dirsMrg = array_merge($dirsTop, $dirsSub, $dirsSb_, $dirsSb1, $dirsSb2, $dirsSb3, $dirsSb4, $dirsSb5,
                                                                         $dirsSb6, $dirsSb7, $dirsSb8, $dirsSb9, $dirsSb10);

                    //ordering Directories
                    $fileSource = array();
                    foreach( $dirsMrg as $dir)
                        if ( is_dir($dir) ) $fileSource[]= $dir;
                    
                    foreach( $dirsMrg as $dir)
                        if ( !is_dir($dir) ) $fileSource[]= $dir;
                    
                    foreach( $fileSource as $dir)
                    {
                        $targetName = str_replace($moduleFrom, '', $dir);
                        $targetName = str_replace('Models/Page.php', 'Models/'.$modulePath.'.php', $targetName);

                        if ( is_dir($dir) )
                        {
                            //create Module Directory
                            if ( !file_exists( config('modules.paths.modules').'/'.$modulePath.$targetName ) )
                            {
                                if ( mkdir(config('modules.paths.modules').'/'.$modulePath.$targetName, 0777, true) )
                                {
                                    $return['dirs'][] = config('modules.paths.modules').'/'.$modulePath.$targetName;
                                }
                            }
                        }
                        else
                        {
                            //open file
                            if ($fileContent = file_get_contents($dir))
                            {
                                foreach( $value as $k=>$v )
                                {
                                    $fileContent = str_replace('___'.strtoupper($k).'___', $v, $fileContent);
                                }
                                //save as file
                                if (file_put_contents(config('modules.paths.modules').'/'.$modulePath.$targetName, $fileContent))
                                {
                                    $return['files'][] = config('modules.paths.modules').'/'.$modulePath.$targetName;
                                }
                            }

                        }
                    }
                    //Create Database
                    if (!Schema::hasTable('mod_'.$value['sc']))
                    {
                        Schema::create('mod_'.$value['sc'], function (Blueprint $table) use($value, $type) {
                            $table->increments('id');
                            $table->string($value['field_name'], 75)->index($value['field_name']);
                            $table->string('url', 75)->index('url');

                            if ( strpos($type, 'image') )
                            {
                                $table->string('image', 255);
                            }

                            $table->enum('status', ['1', '0', '-1'])->default('1')->index('status');
                            $table->tinyInteger('created_by')->index('created_by');
                            $table->tinyInteger('updated_by')->index('updated_by');
                            $table->timestamps();
                        });
                    }
                }
            }
            return $return;
        }
        else
        {
            $this->setNotif(trans('system/module.exists_module', ['name'=>$modulePath]), 'danger');
        }
        return false;
    }
}