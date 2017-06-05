<?php
namespace Modules\Membership\Http\Controllers;

use Illuminate\Routing\Controller,
    App\Http\Controllers\BE\BaseController,
    Modules\Membership\Models\Membership,
    Yajra\Datatables\Datatables;

use Input, Session, Request, Redirect;

class BeController extends BaseController
{
    function __construct() {
        parent::__construct();
    }

    /*
    |--------------------------------------------------------------------------
    | Management Membership
    |--------------------------------------------------------------------------
    */
    public function index($isTrash=false)
    {
        if ( Request::isMethod('get') )
        {
            $this->dataView['form'] = $this->form();

            $rows = Membership::query();
            $rows_ = clone $rows;

            $this->dataView['countAll'] = $rows->where('status', '<>', '-1')->count();
            $this->dataView['countTrash'] = $rows_->where('status', '-1')->count();

            $this->dataView['isTrash'] = $isTrash;
            
            return view('membership::index', $this->dataView);
        }
        else
        {
            $rows = $isTrash ? Membership::where('status', '-1') : Membership::where('status', '<>', '-1');

            return Datatables::of($rows)
            ->addColumn('action', function ($r) use ($isTrash) { return $this->_buildAction($r->id, $r->nama, 'default', $isTrash); })
            ->editColumn('nama', function ($r) { return createLink( url(config('membership.info.alias').'/'.$r->url.'.html'), $r->nama ); })
            ->editColumn('status', function ($r) { return $r->status=='1' ? trans('global.active') : trans('global.inactive'); })
            ->editColumn('created_at', function ($r) { return formatDate($r->created_at, 5); })
            ->editColumn('updated_at', function ($r) { return $r->updated_at ? formatDate($r->updated_at, 5) : '-'; })
            ->make(true);
        }
    }

    public function trash()
    {
        return $this->index(true);
    }

    /*
    |--------------------------------------------------------------------------
    | Build Form
    |--------------------------------------------------------------------------
    */
    public function form($id='')
    {
        $data = $id ? Membership::find($id) : null;
        
        $this->dataView['dataForm'] = $data ? $data->toArray() : []; 
        
        $this->dataView['dataForm']['form_title'] = $data ? trans('global.form_edit') : trans('global.form_add');

        return view('membership::form', $this->dataView);
    }

    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */
    function delete($id)
    {
        return Response()->json([ 
            'status' => $this->_deleteData(new Membership(), $id, (val($_GET, 'permanent')=='1' ? null : ['status'=>'-1'])), 
            'message'=> $this->_buildNotification(true)
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Restore
    |--------------------------------------------------------------------------
    */
    function restore($id)
    {
        return Response()->json([ 
            'status' => $this->_deleteData(new Membership(), $id, ['status'=>'1']), 
            'message'=> $this->_buildNotification(true)
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Save Data | Insert Or Update
    |--------------------------------------------------------------------------
    */
    function save()
    {
        $input  = Input::except('_token');
        $image  = isset($input['file']) ? $input['file'] : null;unset($input['file']);
        if ( val($input, '_post') )
        {
            parse_str($input['_post'], $params);
            $input  = $params;
        }
        unset($input['_image']);
        unset($input['_token']);
        
        if ( $image && is_string($image) )
        {
            $input['image'] = $image; $image = null;
        }

        $input['url'] = str_slug(trim($input['nama']));
        $input['status'] = val($input, 'status') ? 1 : 0;

        $status = $this->_saveData( new Membership(), [   
            //VALIDATOR
            "nama" => "required|unique:mod_membership". ($input['id'] ? ",nama,".$input['id'] : '')
        ], $input, 'nama');

        if ( $image )
        {
            $image = $this->_uploadImage($image, 'membership', ['600x800', '200x300'], $input['url']);
            
            if ( isset($image['600x800']) )
            {
                Membership::where('id', $status)->update(['image'=>$image['600x800']]);
            }
        }

        $this->clearCache( config('membership.info.alias').'/'.$input['url'].'.html' );
                
        return Response()->json([ 
            'status' => $status, 
            'message'=> $this->_buildNotification(true),
            'form'   => $status ? base64_encode($this->form()) : null
        ]);
    }
}