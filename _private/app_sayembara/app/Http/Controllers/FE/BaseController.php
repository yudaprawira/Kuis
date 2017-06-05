<?php
namespace App\Http\Controllers\FE;

use App\Http\Controllers\Controller,
    App\Http\Requests, config;

use Lang, Route, Session, Request, Cookie, Redirect, Hash;

class BaseController extends Controller
{
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
        $this->tmpl = config('app.template');
        
        /**
         * Current Path
        **/
        $this->currentPath = $this->_getCurrentPage();

        /**
         * Template Public URL 
        **/
        $this->dataView = [
            'title'  => trans('global.dashboard').' | '.config('app.title'),
            'notif'  => $this->_buildNotification(),
            'path'   => $this->currentPath,
            'pub_url'=> url(str_replace('.', '/', config('app.template')))
        ];
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
            session::put('ses_feuserid', $obj->id);
            session::put('ses_feusername', $obj->nama);
            session::put('ses_feuserfoto', $obj->image);
        }
    }
    
    /*
    |--------------------------------------------------------------------------
    | STATIC PAGE
    |--------------------------------------------------------------------------
    */
    function page($code='404')
    {
        return view($this->tmpl . 'page.'.$code, $this->dataView);
    }
    

    /*
    |--------------------------------------------------------------------------
    | GET CURRENT PAGE
    |--------------------------------------------------------------------------
    */
    private function _getCurrentPage()
    {
        $fullPath = Route::current()->uri();
        $backPath = '/';
        $curnPath = $fullPath;

        if ( substr($fullPath, 0, strlen($backPath)) == $backPath  )
            $curnPath = explode('/', $fullPath)[1];
        
        return $curnPath;
    }
}