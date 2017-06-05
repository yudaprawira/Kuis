<?php

namespace Modules\Membership\Http\Controllers;

use Redirect, Input, Session,
    Illuminate\Http\Request,
    Illuminate\Http\Response,
    Illuminate\Routing\Controller,
    Modules\Membership\Models\Membership,
    App\Http\Controllers\FE\BaseController;

class FeController extends BaseController
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return 'Front End Here';
    }

    function login() 
    {
        if ( session::has('ses_feuserid') )
        {
            return redirect(url('/'));
        }

        return view($this->tmpl . 'login', $this->dataView);
    }

    function loginSave()
    {
        $input  = Input::except('_token');

        if ( val($input, 'id') )
        {
            $data = [
                'nama' => ucwords(trim(val($input, 'nama'))),
                'email' => strtolower(trim(val($input, 'email'))),
                'image' => trim(val($input, 'image')),
            ];

            if ( val($input, 'type')=='google' ) $sosMedId = 'google_id';
            if ( val($input, 'type')=='twitter' ) $sosMedId = 'twitter_id';
            if ( val($input, 'type')=='facebook' ) $sosMedId = 'facebook_id';

            $data[$sosMedId] = val($input, 'id');

            if ( Session::has('ses_feuserid') )
            {
                $rowUser = Membership::where('id', Session::get('ses_feuserid'));
            }
            else
            {
                $rowUser = Membership::where($sosMedId, $data[$sosMedId])->orWhere('email', $data['email']);
            }

            //add update
            if ( $user = $rowUser->first())
            {
                Membership::where('id', $user->id)->update($data);
            }
            else
            {
                $userID = Membership::insertGetId($data);

                $user = Membership::where('id', $userID)->first();
            }
        }

        if ( $user )
        {
            $this->dataView['row'] = $user;

            if ( substr(val($user, 'email'), -strlen(config('app.limit_user'))) == config('app.limit_user') || in_array(val($user, 'email'), config('app.allowed_user')) )
            {
                $this->_setSessionLogin($user);

                $dataUser = [
                    'title' => val($user, 'nama'),
                    'text'  => '<img src="'.val($user, 'image').'" style="width:100px"/>',
                    'html'  => true,
                    'type'  => 'success',
                    'closeOnConfirm' => false,
                    'showLoaderOnConfirm' => true,
                ];
            }
            else
            {
                $dataUser = [
                    'title' => 'FORBIDDEN',
                    'text'  => 'This email <b>'.val($user, 'email').'</b> is not allowed to access',
                    'html'  => true,
                    'type'  => 'info',
                    'closeOnConfirm' => false,
                    'showLoaderOnConfirm' => true,
                ];
            }

            return Response()->json([ 
                'data_user'=> $dataUser
            ]);
        }
    }
}
