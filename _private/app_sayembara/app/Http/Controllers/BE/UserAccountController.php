<?php
namespace App\Http\Controllers\BE;

use App\Http\Requests,
    App\Models\System\User;

use Input, Hash, Lang, Session, Redirect, Cookie, Mail, Request;

class UserAccountController extends BaseController
{
    function __construct() {
        parent::__construct();        
    }
    
    /*
    |--------------------------------------------------------------------------
    | Action Logout and clear session
    |--------------------------------------------------------------------------
    */
    function logout()
    {
        $this->_clearSessionLogin();
        
        $this->setNotif( Lang::get('login.status_logout'), 'info');
        
        return redirect( BeUrl('login') );
    }
 
     /*
    |--------------------------------------------------------------------------
    | FORM Login
    |--------------------------------------------------------------------------
    */
    function login()
    {
        if ( $this->isLogin ) return redirect( BeUrl() );
        
        if ( Request::isMethod('get') )
        {
            $this->dataView['title'] = 'Login';
            $this->dataView['username'] = session::has('username') ? session::get('username') : '';  
            
            return view($this->tmpl . 'layouts.account.login', $this->dataView);
        }
        else
        {
            $userID = 0;
            
            $validator = \Validator::make(Input::all(), array(
                "username" => "required",
                "password" => "required",
            ));
            
            if($validator->fails())
            {
                $this->setValidator($validator->messages());
                
                return redirect( BeUrl('login') );
            }
            
            if ( $user = User::where('username', input::get('username'))->orWhere('email', input::get('username'))->first() )
            {
                $userID = $user->id;
                
                if ( $user->status=='1' )
                {
                    if (Hash::check(input::get('password'), $user->password))
                    {
                        $this->setNotif(Lang::get('login.status_success',['username'=>$user->username]), 'success', 'center', 'auto', 'false');
                        //remove user session
                        Session::forget('username');
                        
                        $this->_setSessionLogin($user);
                        
                        //UPDATE LAST LOGIN
                        $hash = sha1( date('Y-m-d H:i:s') . $user->email );                
                                User::where('id', $user->id)->update(['last_login'=>date('Y-m-d H:i:s'), 'hash'=>$hash]);
                        
                        //REMEMBER ME
                        if ( input::has('remember_me') )
                        {
                            Cookie::queue(Cookie::make('u_hash', $hash, (60*24*7)));
                            Cookie::queue(Cookie::make('e_hash', sha1($user->email), (60*24*7)));
                        }
                        
                        setLog(trans('log.login_success'));
                        
                        return redirect( BeUrl() );
                    }
                    else
                    {
                        $this->setNotif(Lang::get('login.incorrect_pass'), 'warning', 'center', 'auto', 'true', 'password');
                    }
                    
                    //set user session
                    Session::put('username', input::get('username'));
                }
                else
                {
                    $this->setNotif(Lang::get('login.user_deactive'), 'warning', 'center');
                }            
            }
            else
            {
                $this->setNotif(Lang::get('login.not_registered'), 'warning', 'center', 'auto', 'true', 'username');
            }
            
            setLog(trans('log.login_failed'), '', $userID);
            
            return redirect( BeUrl('login') );    
        }
    }
    
    /*
    |--------------------------------------------------------------------------
    | FORM Change Password
    |--------------------------------------------------------------------------
    */
    function changePassword()
    {
        if ( Request::isMethod('get') )
        {
            $this->dataView['title'] = Lang::get('passwords.text_caption');
            return view($this->tmpl . 'layouts.account.change_password', $this->dataView);
        }
        else
        {
            $validator = \Validator::make(Input::all(), array(
                "old_password" => "required",
                "new_password" => "required",
                "password_confirmation"  => "required|same:new_password",
            ));
            
            if($validator->fails())
            {
                $this->setValidator($validator->messages());
                
                return redirect( BeUrl('change-password') );
            }
            
            if ( $user = User::where('id', session::get('ses_userid'))->first() )
            {
                if (Hash::check(input::get('old_password'), $user->password))
                {
                    $this->setNotif(Lang::get('passwords.status_success'), 'success', 'center', 'auto', 'false');
                    $this->setNotif(Lang::get('passwords.status_relogin'), 'warning', 'center', 'auto', 'false');
                    
                    //remove session login
                    Session::forget('ses_userid');
                                    
                    //UPDATE PASSWORD
                    $hash = sha1( date('Y-m-d H:i:s') . $user->email );                
                            User::where('id', $user->id)->update(['password'=>Hash::make(input::get('new_password')), 'hash'=>$hash]);
                    
                    return redirect( BeUrl('login') );
                }
                else
                {
                    $this->setNotif(Lang::get('passwords.incorrect_pass'), 'warning', 'center', 'auto', 'true', 'old_password');
                }
            }
            else
            {
                $this->setNotif(Lang::get('login.not_registered'), 'warning', 'center', 'auto', 'true');
            }
            
            return redirect( BeUrl('change-password') );    
        }
            
    }
    
    /*
    |--------------------------------------------------------------------------
    | FORM Reset Password
    |--------------------------------------------------------------------------
    */
    function resetPassword()
    {
        if ( Request::isMethod('get') )
        {
            if ( $this->isLogin ) return redirect( BeUrl() );  
            
            $this->dataView['title'] = Lang::get('login.title_reset');
            
            setLog(trans('log.request_password', ['email'=>'']));
            
            return view($this->tmpl . 'layouts.account.reset_password', $this->dataView);
        }
        else
        {
            $validator = \Validator::make(Input::all(), array(
                "username" => "required"
            ));
            
            if($validator->fails())
            {
                $this->setValidator($validator->messages());
                
                return redirect( BeUrl('reset-password') );
            }
            
            $userID = '';
            
            if ( $user = User::where('username', input::get('username'))->orWhere('email', input::get('username'))->first() )
            {
                $userID = $user->id;
                
                if ( $user->status=='1' )
                {
                    $this->dataView['username'] = ucwords($user->username);
                    $this->dataView['link'] = BeUrl('create-password').'?code='.base64_encode(json_encode([
                        'hash' => $user->hash,
                        'expired' => strtotime('+2 hours', time())
                    ]));
                    
                    Mail::send('global.emails.reset-password', $this->dataView, function ($message) use($user) {
                        $message->subject( Lang::get('login.title_reset') );
                        $message->from(config('mail.from.address'), config('app.title'));
                        $message->to($user->email);
                    });
                    
                    $this->setNotif(Lang::get('login.reset_success',['email'=>$user->email]), 'success', 'center', 'auto', 'true');
                }
                else
                {
                    $this->setNotif(Lang::get('login.user_deactive'), 'danger', 'center', 'auto', false, 'username');
                }
            }
            else
            {
                $this->setNotif(Lang::get('login.not_registered'), 'warning', 'center', 'auto', 'true', 'username');
            }
            
            setLog(trans('log.request_password', ['email'=>input::get('username')]), '', $userID);
            
            return redirect( BeUrl('reset-password') );    
        }
    }

    /*
    |--------------------------------------------------------------------------
    | FORM Create Password and validate link request new password
    |--------------------------------------------------------------------------
    */
    function createPassword()
    {
        if ( Request::isMethod('get') )
        {
            if ( $this->isLogin ) return redirect( BeUrl() );
            
            $this->dataView['title'] = Lang::get('login.title_create');  
        
            $code = input::has('code') ? json_decode(base64_decode(input::get('code')), true) : null;
            
            if ( $code && isset($code['hash']) && isset($code['expired']) )
            {
                if ( $code['expired']>time() )
                {
                    if ( $user = User::where('hash', $code['hash'])->first() )
                    {
                        if ( $user->status=='1' )
                        {
                            $this->dataView['code'] = input::get('code');
                            $this->dataView['user'] = $user;
                            
                            return view($this->tmpl . 'layouts.account.create_password', $this->dataView);
                        }
                        else
                        {
                            $this->setNotif(Lang::get('login.user_deactive'), 'warning', 'center');
                        }            
                    }          
                }
            }
    
            $this->setNotif( Lang::get('login.create_expired'), 'danger' );
            
            return redirect( BeUrl('login') );
        }
        else
        {
            $validator = \Validator::make(Input::all(), array(
                "code" => "required",
                "new_password" => "required",
                "password_confirmation"  => "required|same:new_password",
            ));
            
            if($validator->fails())
            {
                $this->setValidator($validator->messages());
                
                return redirect( BeUrl('create-password') .'?code='.input::get('code') );
            }
            
            $code = input::has('code') ? json_decode(base64_decode(input::get('code')), true) : null;
            
            if ( $code )
            {
                if ( $user = User::where('hash', $code['hash'])->first() )
                {
                    $this->setNotif(Lang::get('passwords.create_success'), 'success', 'center', 'auto', 'false');
                    $this->setNotif(Lang::get('passwords.status_relogin'), 'warning', 'center', 'auto', 'false');
                    
                    //UPDATE PASSWORD
                    $hash = sha1( date('Y-m-d H:i:s') . $user->email );                
                            User::where('id', $user->id)->update(['password'=>Hash::make(input::get('new_password')), 'hash'=>$hash]);
                }
                else
                {
                    $this->setNotif(Lang::get('login.not_registered'), 'warning', 'center', 'auto', 'true');
                }
            }
            else
            {
                $this->setNotif( Lang::get('login.create_expired'), 'danger' );   
            }
            
            return redirect( BeUrl('login') );
        }
    }
}