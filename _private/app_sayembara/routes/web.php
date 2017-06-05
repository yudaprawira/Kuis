<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => config('app.backend')], function()
{

    Route::get('/', 'BE\DashboardController@homepage');

    //log
    Route::match(['GET', 'POST'], '/system-log', 'BE\DashboardController@systemLog');

    Route::get('/switch-profile/{id}', 'BE\DashboardController@switchProfile')->where('id', '[a-z0-9\-]+');

//USER ACCOUNT
    //logout
    Route::get('/logout', 'BE\UserAccountController@logout');
    
    //login
    Route::match(['GET', 'POST'], '/login', 'BE\UserAccountController@login');
    
    //change password
    Route::match(['GET', 'POST'], '/change-password', 'BE\UserAccountController@changePassword');
    
    //reset password
    Route::match(['GET', 'POST'], '/reset-password', 'BE\UserAccountController@resetPassword');

    //create password
    Route::match(['GET', 'POST'], '/create-password', 'BE\UserAccountController@createPassword');

//SYSTEM
    //User Level
    Route::group(['prefix' => '/system-level'], function()
    {
        Route::match(['GET', 'POST'], '/', 'BE\SystemController@level');
        Route::match(['GET', 'POST'], '/trash', 'BE\SystemController@levelTrash');
        Route::post('/save', 'BE\SystemController@saveLevel');
        Route::get('/edit/{id}', 'BE\SystemController@buildFormLevel')->where('id', '[0-9]+');
        Route::get('/restore/{id}', 'BE\SystemController@restoreLevel')->where('id', '[0-9]+');
        Route::get('/delete/{id}', 'BE\SystemController@deleteLevel')->where('id', '[0-9]+');
    });

    //Module
    Route::group(['prefix' => '/system-modules'], function()
    {
        Route::match(['GET', 'POST'], '/', 'BE\SystemController@module');
    });

    //Documentations
    Route::group(['prefix' => '/system-documentations'], function()
    {
        Route::get('/', 'BE\SystemController@documentation');
    });

    //Menu
    Route::group(['prefix' => '/system-menu'], function()
    {
        Route::match(['GET', 'POST'], '/', 'BE\SystemController@menu');
        Route::post('/save', 'BE\SystemController@saveMenu');
        Route::post('/order', 'BE\SystemController@saveOrder');
        Route::post('/access/{id}', 'BE\SystemController@saveAccess')->where('id', '[0-9]+');
        Route::get('/edit/{id}', 'BE\SystemController@buildFormMenu')->where('id', '[0-9]+');
        Route::get('/delete/{id}', 'BE\SystemController@deleteMenu')->where('id', '[0-9]+');
    });

    //menu Access
    Route::match(['GET', 'POST'], '/system-menu-access', 'BE\SystemController@menuAccess');
    
    //User Account
    Route::group(['prefix' => '/system-user'], function()
    {
        Route::match(['GET', 'POST'], '/', 'BE\SystemController@user');
        Route::match(['GET', 'POST'], '/trash', 'BE\SystemController@userTrash');
        Route::post('/save', 'BE\SystemController@saveUser');
        Route::get('/edit/{id}', 'BE\SystemController@buildFormUser')->where('id', '[0-9]+');
        Route::get('/restore/{id}', 'BE\SystemController@restoreUser')->where('id', '[0-9]+');
        Route::get('/delete/{id}', 'BE\SystemController@deleteUser')->where('id', '[0-9]+');
    });
    

});

//FRONTEND
Route::group(['middleware'=>'web', 'prefix' => '/'], function()
{
    Route::get('/', 'FE\HomeController@index');
    Route::get('/start', 'FE\HomeController@start');
    Route::get('/ping', 'FE\HomeController@ping');
    Route::get('/hasil/{id}', 'FE\HomeController@hasil')->where('id', '[0-9]+');
    Route::get('/hasil', 'FE\HomeController@hasil');
    Route::get('/ranking', 'FE\HomeController@ranking');
    Route::get('/logout', 'FE\HomeController@logout');
    Route::post('/save-answer', 'FE\HomeController@saveAnswer');
});

include_once __DIR__.'/../app/Helpers/core.php';
include_once __DIR__.'/../app/Helpers/layout.php';
include_once __DIR__.'/../app/Helpers/compress.php';
