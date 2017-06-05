<?php
namespace Modules\Soal\Http\Controllers;

use Illuminate\Routing\Controller,
    App\Http\Controllers\BE\BaseController,
    Modules\Kategori\Models\Kategori,
    Modules\Soal\Models\Soal,
    Yajra\Datatables\Datatables;

use Input, Session, Request, Redirect;

class BeController extends BaseController
{
    function __construct() {
        parent::__construct();
    }

    /*
    |--------------------------------------------------------------------------
    | Management Soal
    |--------------------------------------------------------------------------
    */
    public function index($isTrash=false)
    {
        if ( Request::isMethod('get') )
        {
            $this->dataView['form'] = $this->form();

            $rows = Soal::query();
            $rows_ = clone $rows;

            $this->dataView['countAll'] = $rows->where('status', '<>', '-1')->count();
            $this->dataView['countTrash'] = $rows_->where('status', '-1')->count();

            $this->dataView['isTrash'] = $isTrash;
            
            return view('soal::index', $this->dataView);
        }
        else
        {
            $rows = $isTrash ? Soal::where('status', '-1') : Soal::where('status', '<>', '-1');

            return Datatables::of($rows->with('rel_kategori'))
            ->addColumn('action', function ($r) use ($isTrash) { return $this->_buildAction($r->id, $r->soal, 'default', $isTrash); })
            ->editColumn('soal', function ($r) { return htmlentities($r->soal); })
            ->editColumn('jawaban', function ($r) { 
                $pilihan = json_decode($r->pilihan, true);
                return htmlentities(val($pilihan['soal'], $pilihan['jawaban'], '-')); 
            })
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
        $data = $id ? Soal::find($id) : null;
        
        $this->dataView['dataForm'] = $data ? $data->toArray() : [];

        $this->dataView['categories'] = getRowArray(Kategori::where('status', '1')->get(), 'id', 'kategori'); 
        
        $this->dataView['dataForm']['form_title'] = $data ? trans('global.form_edit') : trans('global.form_add');

        return view('soal::form', $this->dataView);
    }

    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */
    function delete($id)
    {
        return Response()->json([ 
            'status' => $this->_deleteData(new Soal(), $id, (val($_GET, 'permanent')=='1' ? null : ['status'=>'-1'])), 
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
            'status' => $this->_deleteData(new Soal(), $id, ['status'=>'1']), 
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
        
        $input['url'] = null;
        $input['status'] = val($input, 'status') ? 1 : 0;
        $input['pilihan'] = json_encode(array_filter($input['pilihan']));

        $status = $this->_saveData( new Soal(), [   
            //VALIDATOR
            "soal" => "required|unique:mod_soal". ($input['id'] ? ",soal,".$input['id'] : '')
        ], $input, 'soal');

        $this->clearCache( config('soal.info.alias').'/'.$input['url'].'.html' );
                
        return Response()->json([ 
            'status' => $status, 
            'message'=> $this->_buildNotification(true),
            'form'   => $status ? base64_encode($this->form()) : null
        ]);
    }
}