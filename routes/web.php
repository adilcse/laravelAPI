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
/**
 * no authentication needed for these API calls
 */
Route::group(['middleware' => ['cors']], function () {
    Route::get('getCatagory','CatagoryController@getAll');
    Route::get('nearbySellers','SellerController@getNearby');
/**
 * only authenticated user can access these APIS
 */
    Route::group(['prefix' => 'user','middleware'=>'auth'], function () {
        Route::get('login','UserController@getByUid');
        Route::post('register','UserController@store');
        Route::post('addToCart','CartController@addToCart');
        Route::post('placeOrder','OrderController@store');
        Route::get('getOrders/{per_page}','OrderController@getUserOrder');
        Route::post('updateAddress','AddressController@updateAddress');
        Route::get('removeFromCart/{id}/delete','CartController@removeFromCart');
        Route::post('updateAddress','AddressController@updateAddress');
        Route::post('updateCart','CartController@updateCart'); 
    });
    /**
     * only registered seller can access these APIs 
     */
    Route::group(['prefix' => 'seller','middleware'=>'auth'], function () {
        Route::get('getItems','ProductController@getSellerItems');
        Route::post('register','SellerController@register');
        Route::post('addItem','ProductController@store');
        Route::post('updateItem','ProductController@update');
        Route::post('orderAcceptReject','OrderController@acceptReject');
        Route::get('updateOrderStatus/{id}','OrderController@update');
        Route::get('deleteItems','ProductController@delete');
        Route::get('login','SellerController@login');
        Route::get('getOrders/{perPage}','OrderController@getUserOrder');

    });
});

