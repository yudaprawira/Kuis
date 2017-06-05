<?php
namespace App\Http\Controllers\BE;

use App\Http\Controllers\Controller,
    App\Http\Requests,
    App\Models\System\User,
    App\Models\System\Menu,
    App\Models\System\Level,
    App\Models\System\Access;

use Input, Lang, Route, Session, Request, Cookie, Redirect, Hash;

class BaseController extends Controller
{
    /**
     * Variable IsLogin
    **/
    public $isLogin = false;

    /**
     * Variable Menus
    **/
    public $rowMenu;

    /**
     * Variable Access Menu ID
    **/
    public $accessmenuID;

    public $activeState;

    /*
    |--------------------------------------------------------------------------
    | Parent Construct
    |-------------------------------------------------------------------------
    | build main page 
    | box global, header, menu, footer
    */
    function __construct() 
    {
        parent::__construct();
        
        
        /**
         * Template 
        **/
        $this->tmpl = config('app.be_template');
        
        /**
         * Current Path
        **/
        $this->currentPath = $this->_getCurrentPage();

        /**
         * Template Public URL 
        **/
        $this->dataView = [
            'title'  => 'Dashboard',
            'notif'  => $this->_buildNotification(),
            'path'   => $this->currentPath,
            'pub_url'=> url(str_replace('.', '/', config('app.be_template')))
        ];
        

        /**
         * Check Login 
        **/
        $this->_checkLogin();

        /**
         * Box to build main Page 
        **/
        if ( Session::has('ses_userid') )
        {
            $this->isLogin = true;
            $this->_setMenu();            
            /**
             * Access Menu ID
            **/
            $this->accessmenuID = json_decode(Session::get('ses_access_id'),true);

            $this->activeState  = val(session::get('ses_switch_to'), session::get('ses_switch_active'));
            
            $this->dataView['header'] = $this->_buildHeader();
            $this->dataView['Lmenu'] = $this->_buildLMenu();
            $this->dataView['footer'] = $this->_buildFooter();
            $this->dataView['control'] = $this->_buildControl();
            $this->dataView['breadcrumb'] = $this->rowBreadcrumbs ? array_reverse($this->rowBreadcrumbs) : [];
            $this->dataView['active_state'] = val(session::get('ses_switch_to'), session::get('ses_switch_active'));
        }
    }
    
    /*
    |--------------------------------------------------------------------------
    | User Set Session
    |--------------------------------------------------------------------------
    */
    public function _setSessionLogin($obj)
    {
        if ( $obj )
        {
            session::put('ses_userid', $obj->id);
            session::put('ses_username', $obj->username);
            session::put('ses_useremail', $obj->email);
            session::put('ses_level_id', $obj->level_id);
            session::put('ses_photo', ($obj->image ? imgUrl($obj->image) : asset('/global/images/no-image.png') ));
            
            //get levelName
            $level = Level::where('id', $obj->level_id)->first();

            session::put('ses_level_name', $level->name);
            session::put('ses_level_url', str_slug($level->name));
            session::put('ses_is_superadmin', (str_slug($level->name)=='super-admin' ? true : false));

            //get level menu access
            $access = getRowArray(Access::where('level_id', $obj->level_id)->get(), 'menu_id', 'menu_id');
            
            session::put('ses_access_id', json_encode($access));
        }
    }

    /*
    |--------------------------------------------------------------------------
    | User clear Session
    |--------------------------------------------------------------------------
    */
    public function _clearSessionLogin()
    {
        Session::flush();
        if (Cookie::has('u_hash')) Cookie::queue(Cookie::forget('u_hash'));
        if (Cookie::has('e_hash')) Cookie::queue(Cookie::forget('e_hash'));
    }
    
    /*
    |--------------------------------------------------------------------------
    | User check Session Login
    |--------------------------------------------------------------------------
    */
    public function _checkLogin()
    {
        $excludePage = array('reset-password', 'create-password', 'login');
        
        if (!Session::has('ses_userid') && !in_array($this->currentPath, $excludePage))
    	{
    	   if ( Request::cookie('u_hash') && Request::cookie('e_hash'))
           {
                $user = User::where('hash', Request::cookie('u_hash'))->with('pegawai')->first();
                
                if ( count($user)>0 && sha1($user->user_email) == Request::cookie('e_hash') )
                {
                    return $this->_setSessionLogin($user);
                }
           }
           
           $this->setNotif( Lang::get('login.must_login')  );

           header("location:".BeUrl('login'));exit;
        }
    }
    
    /*
    |--------------------------------------------------------------------------
    | Datatable Build Action
    |--------------------------------------------------------------------------
    */
    public function _buildAction($id, $name, $type='default', $isTrash=false)
    {
        $url_edit = BeUrl($this->currentPath.'/edit/'.$id.'?'.uniqid());
        $url_delete = BeUrl($this->currentPath.'/delete/'.$id.'?'.uniqid().'&permanent='.$isTrash);
        $url_restore = BeUrl($this->currentPath.'/restore/'.$id.'?'.uniqid());
        return view($this->tmpl . 'box.global.button_'.$type, compact('id', 'name', 'url_edit', 'url_delete', 'url_restore', 'isTrash'));
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    |@params : $obj object
    |@params : $id int primary
    |@params : $flagging array update status
    */
    function _deleteData($obj, $id, $flagging=[])
    {
        if( $row = $obj->where('id', $id)->first() ) 
        {
            if ( $flagging )
                $action = $obj->find($id)->update($flagging);
            else
                $action = $obj->find($id)->delete();

            if ($action)
            {
                $status = true;
                $this->setNotif(trans('global.delete_success'), 'success');
                
                setLog(trans('log.delete_success', ['page'=>$this->dataView['title'], 'name'=>$row->name]));
            }
            else
            {
                $status = false;
                $this->setNotif(trans('global.delete_failed'), 'danger');
                
                setLog(trans('log.delete_failed', ['page'=>$this->dataView['title'], 'name'=>$row->name]));
            }
        }
        else
        {
            $status = false;
            $this->setNotif(trans('global.delete_404'), 'danger');
            
            setLog(trans('log.data_not_found', ['page'=>$this->dataView['title']]));
        }
        
        return $status;
    }
    
    /*
    |--------------------------------------------------------------------------
    | SAVE
    |--------------------------------------------------------------------------
    */
    function _saveData($obj, $validate, $input=null, $name='name', $unset=[], $hash=[])
    {
        $input = $input ? $input : Input::except('_token');
        
        $validator = \Validator::make($input, $validate);
        
        if($validator->fails())
        {
            $status = false;
            $this->setValidator($validator->messages());
        }
        else
        {
            //unset
            if ($unset) foreach($unset as $u) if (isset($input[$u])) unset($input[$u]);
            
            //hash
            if ($hash) foreach($hash as $h) if (isset($input[$h])) $input[$h] = Hash::make($input[$h]);
            
            if ( $input['id'] )
            {
                //UPDATE
                $input['updated_by'] = Session::get('ses_userid'); 
                $input['updated_at'] = date('Y-m-d H:i:s');
                
                if ($r=$obj->findOrFail($input['id']))
                {
                    $changed = $this->updatedField($r, $input);

                    if($r->fill($input)->save())
                    {                                                            
                        $status = $input['id'];
                        $this->setNotif(trans('global.edit_success'), 'success');
                        
                        setLog(trans('log.update_success', ['page'=>$this->dataView['title'], 'changed'=>$changed]));
                    }
                }
                else
                {
                    $status = false;
                    $this->setNotif(trans('global.edit_failed'), 'danger');
                    
                    setLog(trans('log.update_failed', ['page'=>$this->dataView['title'], 'name'=>$input[$name]]));
                }
            }
            else
            {
                //INSERT
                $input['created_by'] = Session::get('ses_userid'); 
                $input['created_at'] = date('Y-m-d H:i:s');
                
                if ($id = $obj->insertGetId($input))
                {
                    $status = $id;
                    $this->setNotif(trans('global.add_success'), 'success');
                    
                    setLog(trans('log.insert_success', ['page'=>$this->dataView['title'], 'name'=>$input[$name]]));
                }
                else
                {
                    $status = false;
                    $this->setNotif(trans('global.add_failed'), 'danger');
                    
                    setLog(trans('log.insert_failed', ['page'=>$this->dataView['title'], 'name'=>$input[$name]]));
                }
            }
        }
        
        return $status;
    }
    
    /*
    |--------------------------------------------------------------------------
    | Header
    |--------------------------------------------------------------------------
    */
    private function _buildHeader()
    {
        return view($this->tmpl . 'box.global.header', $this->dataView);
    }

    /*
    |--------------------------------------------------------------------------
    | Footer
    |--------------------------------------------------------------------------
    */
    private function _buildFooter()
    {
        return view($this->tmpl . 'box.global.footer', $this->dataView);
    }

    /*
    |--------------------------------------------------------------------------
    | Control, to change skin
    |--------------------------------------------------------------------------
    */
    private function _buildControl()
    {
        return view($this->tmpl . 'box.global.control');
    }
    
    /*
    |--------------------------------------------------------------------------
    | Sidebar Left Menu
    |--------------------------------------------------------------------------
    */
    private function _buildLMenu()
    {
        $this->dataView['title'] = config('app.title'); 
        $this->dataView['subtitle'] = 'Dashboard'; 
        $this->dataView['desc'] = '';

        $this->dataView['list'] = $this->_buildLMenuRecursive();
        
        setLog(trans('log.access_page', ['page'=>$this->dataView['title']]), $this->currentPath);
        
        return view($this->tmpl . 'box.global.left_menu', $this->dataView);
    }
    private function _buildLMenuRecursive($parent=0)
    {
        if ( isset($this->rowMenu['parent_id'][$parent]) )
        {
            $menu = $this->rowMenu['parent_id'][$parent];
            
            if(!empty($menu))
            {
                $list = '';
                foreach( $menu as $m )
                {
                    $activeMenuId = isset($this->rowMenu['url_id'][$this->currentPath]) ? $this->rowMenu['url_id'][$this->currentPath] : null;
                    $activeChild  = isset($this->rowMenu['parent_id'][$m]) ? $this->rowMenu['parent_id'][$m] : [];
                    $activeChild  = in_array($activeMenuId, $activeChild) ? true : false;
                    
                    $m = $this->rowMenu['id_row'][$m];
                    $m['sub'] = $this->_buildLMenuRecursive($m['id']);
                    $m['active'] = ($this->currentPath == $m['url'] || $activeChild ) ? 'active' : '';
                    //ACCESS VIEW
                    if ( in_array( $m['id'], array_keys($this->accessmenuID) ) )
                    {
                        $list .= view($this->tmpl . 'box.global.list_menu', $m);
                    }
                    
                    //set Active Menu
                    if ( isset($this->rowMenu['id_row'][$activeMenuId]) )
                    {
                        //set access url
                        if ( in_array( $activeMenuId, array_keys($this->accessmenuID) ) )
                        {
                            $this->dataView['active_menu'] = $this->rowMenu['id_row'][$activeMenuId];
                            $this->dataView['title'] = $this->dataView['active_menu']['name']. ' | '. config('app.title');
                            $this->dataView['subtitle'] = $this->dataView['active_menu']['name'];
                            $this->dataView['desc'] = $this->dataView['active_menu']['description'];
                        }
                        else
                        {
                            abort('401', trans('global.page_not_allowed'));
                        }
                    }
                    
                    //set breadcrumb
                    if( $m['active']=='active' && $m['url']!='/')
                    {
                        $this->rowBreadcrumbs[] = [ 'url'=> ($m['url']=='#' ? '#' : BeUrl($m['url'])), 'name'=>$m['name'] ];
                    }
                }
                
                return $list ? ( $parent==0 ? $list : '<ul class="treeview-menu">'.$list.'</ul>' ) : '';
            }
        }
    }
    
    /*
    |--------------------------------------------------------------------------
    | Set menu Global
    |--------------------------------------------------------------------------
    */
    private function _setMenu()
    {
        $row = Menu::where('status', '1')->orderBy('order_num')->get();

        $this->rowMenu = [
            'id_row' => getRowArray($row, 'id'),
            'id_name' => getRowArray($row, 'id', 'name'),
            'id_url' => getRowArray($row, 'id', 'url'),
            'url_id' => getRowArray($row, 'url', 'id'),
            'id_parent' => getRowArray($row, 'id', 'parent_id'),
            'parent_id' => getRowArray($row, ['parent_id'], 'id'),
        ];
    }    
    
    /*
    |--------------------------------------------------------------------------
    | SAVE
    |--------------------------------------------------------------------------
    */
    private function updatedField($old, $new)
    {
        $ret = array();
        
        if ( !empty($new) )
        {
            foreach( $new as $k=>$v )
            {
                if ( $old->$k!=$v && $k!='updated_at')
                {
                    $ret[] = trans('log.update_field', ['old'=>$old[$k], 'new'=>$v]);
                }
            }
        }        
        return implode(', ', $ret);
    }

    /*
    |--------------------------------------------------------------------------
    | GET CURRENT PAGE
    |--------------------------------------------------------------------------
    */
    private function _getCurrentPage()
    {
        $fullPath = Route::current()->uri();
        $backPath = config('app.backend').'/';
        $curnPath = $fullPath;

        if ( substr($fullPath, 0, strlen($backPath)) == $backPath  )
            $curnPath = explode('/', $fullPath)[1];
        
        return $curnPath;
    }
}