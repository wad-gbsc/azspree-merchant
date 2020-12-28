<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function () {

    Route::post('login', 'AuthController@login');
    Route::get('logout', 'AuthController@logout');
    Route::get('refresh', 'AuthController@refresh');
    Route::get('me', 'AuthController@me');
    
    

});

 
Route::middleware('auth:api')->group(function () {

    Route::get('/','ProfileController@index');

    Route::put('changepassword/{id}', 'AuthController@changepassword');
    Route::get('sumr/{id}', 'Controller\OrdersController@sumr');
    Route::get('products', 'Controller\ProductsController@index');
    Route::get('orders', 'Controller\OrdersController@orders');
    Route::get('logs', 'Controller\LogsController@logs');
    Route::get('sohr', 'Controller\OrdersController@index');
    Route::post('soln', 'Controller\OrdersController@index');
    Route::put('products/delete/{id}', 'Controller\ProductsController@delete');
    Route::put('products/{id}', 'Controller\ProductsController@update');
    Route::get('products/{id}', 'Controller\ProductsController@show');
    Route::put('acceptneworder/{id}', 'Controller\OrdersController@AcceptNewOrder');
    Route::put('declineneworder/{id}', 'Controller\OrdersController@DeclineNewOrder');
    Route::put('acceptdropoff/{id}', 'Controller\OrdersController@AcceptIntransit');
    Route::put('delivered/delete/{id}', 'Controller\OrdersController@Delivered');
    Route::get('show/{id}', 'Controller\OrdersController@show');
    Route::put('declineintransit/delete/{id}', 'Controller\OrdersController@DeclineIntransit');
    Route::put('acceptintransitpickup/{id}', 'Controller\OrdersController@AcceptIntransitPickup');
    Route::put('todeliver/{id}', 'Controller\OrdersController@Todeliver');
    Route::post('products', 'Controller\ProductsController@create');
    Route::post('refresh', 'Controller\OrdersController@refresh');
    Route::resource('posts', 'Controller\OrdersController');
    Route::post('upload','Controller\ProductsController@upload');
    Route::get('dashboardcheck/{id}', 'Controller\OrdersController@checkIfUsed');

    Route::post('product/upload', 'Controller\ProductsController@upload');
    
});
Route::get('logs/printreport/{from}/{to}', 'Controller\LogsController@PrintReport');