<?php

namespace App\Http\Controllers\BE;

use session,
    Illuminate\Http\Request,
    App\Models\System\Log,
    App\Models\System\User,
    App\Http\Requests,
    Yajra\Datatables\Datatables;    

class DashboardController extends BaseController
{
    function __construct() {
        parent::__construct();        
    }
    
    function homepage()
    {
        switch(session::get('ses_level_name'))
        {
            case 'Resto - Pelayan';
                return view($this->tmpl . 'dashboard.resto.pelayan', $this->dataView);
            break;
            default:
                $this->dataView['totalMember'] = formatNumber(0);
                $this->dataView['totalBook'] = formatNumber(0);
                $this->dataView['totalRating'] = formatNumber(0);
                $this->dataView['totalTransaksi'] = formatNumber(0);
                
                return view($this->tmpl . 'dashboard.default', $this->dataView);
            break;
        }
        
    }

    function switchProfile($id)
    {
        if ( val(session::get('ses_switch_to'), $id) )
        {
            session::put('ses_switch_active', $id);
        }

        return Redirect(val($_SERVER, 'HTTP_REFERER', BeUrl()));
    }
    
    public function systemLog()
    {
        if ( empty($_POST) )
        {
            return view($this->tmpl . 'system.log', $this->dataView);    
        }
        else
        {
            $user = getRowArray(User::get(), 'id', 'username');
            return Datatables::of(Log::query()->orderBy('created_at', 'DESC'))
                                ->addColumn('created_by', function ($r) use ($user) { return $user[$r->created_by]; })
                                ->make(true);
        }
    }
}
