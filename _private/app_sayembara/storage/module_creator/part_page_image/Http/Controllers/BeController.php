<?php
namespace Modules\___PT___\Http\Controllers;

use Illuminate\Routing\Controller,
    App\Http\Controllers\BE\BaseController,
    Modules\___PT___\Models\___PT___,
    Yajra\Datatables\Datatables;

use Input, Session, Request, Redirect;

class BeController extends BaseController
{
    function __construct() {
        parent::__construct();
    }

    /*
    |--------------------------------------------------------------------------
    | Management ___PT___
    |--------------------------------------------------------------------------
    */
    public function index($isTrash=false)
    {
        if ( Request::isMethod('get') )
        {
            $this->dataView['form'] = $this->form();

            $rows = ___PT___::query();
            $rows_ = clone $rows;

            $this->dataView['countAll'] = $rows->where('status', '<>', '-1')->count();
            $this->dataView['countTrash'] = $rows_->where('status', '-1')->count();

            $this->dataView['isTrash'] = $isTrash;
            
            return view('___SC___::index', $this->dataView);
        }
        else
        {
            $rows = $isTrash ? ___PT___::where('status', '-1') : ___PT___::where('status', '<>', '-1');

            return Datatables::of($rows)
            ->addColumn('action', function ($r) use ($isTrash) { return $this->_buildAction($r->id, $r->___FIELD_NAME___, 'default', $isTrash); })
            ->editColumn('___FIELD_NAME___', function ($r) { return createLink( url(config('___SC___.info.alias').'/'.$r->url.'.html'), $r->___FIELD_NAME___ ); })
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
        $data = $id ? ___PT___::find($id) : null;
        
        $this->dataView['dataForm'] = $data ? $data->toArray() : []; 
        
        $this->dataView['dataForm']['form_title'] = $data ? trans('global.form_edit') : trans('global.form_add');

        return view('___SC___::form', $this->dataView);
    }

    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */
    function delete($id)
    {
        return Response()->json([ 
            'status' => $this->_deleteData(new ___PT___(), $id, (val($_GET, 'permanent')=='1' ? null : ['status'=>'-1'])), 
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
            'status' => $this->_deleteData(new ___PT___(), $id, ['status'=>'1']), 
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

        $input['url'] = str_slug(trim($input['___FIELD_NAME___']));
        $input['status'] = val($input, 'status') ? 1 : 0;

        $status = $this->_saveData( new ___PT___(), [   
            //VALIDATOR
            "___FIELD_NAME___" => "required|unique:mod____SC___". ($input['id'] ? ",___FIELD_NAME___,".$input['id'] : '')
        ], $input, '___FIELD_NAME___');

        if ( $image )
        {
            $image = $this->_uploadImage($image, '___SC___', ['600x800', '200x300'], $input['url']);
            
            if ( isset($image['600x800']) )
            {
                ___PT___::where('id', $status)->update(['image'=>$image['600x800']]);
            }
        }

        $this->clearCache( config('___SC___.info.alias').'/'.$input['url'].'.html' );
                
        return Response()->json([ 
            'status' => $status, 
            'message'=> $this->_buildNotification(true),
            'form'   => $status ? base64_encode($this->form()) : null
        ]);
    }
}