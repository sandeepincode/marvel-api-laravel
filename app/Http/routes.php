<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['middleware' => 'web'], function () {
    Route::controller('/', 'apiController');
    Route::get('/404',function (){
        dd('404');
    });
});

Route::get('/404',['as'=>'404','uses'=>'ErrorHandlerController@errorCode404']);
Route::get('/405',['as'=>'405','uses'=>'ErrorHandlerController@errorCode405']);