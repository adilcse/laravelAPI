<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});
Route::group(['middleware' => ['cors']], function () {
    Route::get('getCatagory','AllCatagory@getAll');
    Route::get('nearbySellers','SellerController@getNearby');
    Route::group(['prefix' => 'user','middleware'=>'auth'], function () {
        Route::get('login/{uid}','UserController@getByUid');
        Route::post('userRegister','UserController@store');
        Route::post('addToCart','CartController@addToCart');

        Route::get('removeFromCart/{id}/delete','CartController@removeFromCart');

        Route::post('updateCart','CartController@updateCart'); 
    });
    Route::group(['prefix' => 'seller'], function () {
        Route::get('getSeller','SellerController@getAll');
        Route::post('addUser','UserController@store');
    });
});

