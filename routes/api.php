<?php

use App\Http\Controllers\AuthController;
use GuzzleHttp\Psr7\Uri;
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
    Route::get('profile', 'AuthController@ProfileInfo');
    Route::put('profile/{id}', 'AuthController@updateProfile');
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
    Route::resource('posts', 'Controller\OrdersController');
    Route::post('upload','Controller\ProductsController@uploadCreate');
    Route::post('upload/{id}','Controller\ProductsController@uploadUpdate');
    Route::get('dashboardcheck/{id}', 'Controller\OrdersController@checkIfUsed');
    Route::put('products/remove/{id}', 'Controller\ProductsController@removeImages');

    Route::put('products/approve/{id}', 'Controller\ProductsController@ApproveProduct');
    Route::put('products/disapprove/{id}', 'Controller\ProductsController@DisapproveProduct');
    Route::put('products/banned/{id}', 'Controller\ProductsController@BannedProduct');
    // Route::get('profile/{fileName}', 'Controller\ProductsController@showImage');
    Route::post('logs/', 'Controller\LogsController@create');
    Route::put('issuance/delete/{id}', 'Controller\LogsController@DeleteIssuance');
    Route::put('logs/{id}', 'Controller\LogsController@update');
    Route::get('issuance/{id}', 'Controller\LogsController@show');
    Route::put('markpaid/{id}', 'Controller\LogsController@MarkPaid');
    Route::get('comments', 'Controller\CommentsController@index');
    Route::get('commentslength', 'Controller\CommentsController@commentslength');
    Route::get('comments/{id}', 'Controller\CommentsController@show');
    Route::put('comments/{id}', 'Controller\CommentsController@update');
    Route::get('logs/printreport/{id}', 'Controller\LogsController@PrintReport');

    Route::get('merchants', 'Controller\MerchantsController@index');
    Route::post('merchants/', 'Controller\MerchantsController@create');
    Route::get('merchants/{id}', 'Controller\MerchantsController@show');
    Route::put('merchants/delete/{id}', 'Controller\MerchantsController@delete');

    Route::get('variant/{id}', 'Controller\ProductsController@showVariant');
});
Route::get('logs/print/{from}/{to}', 'Controller\LogsController@PrintReport');
Route::get('logs/printreport/{id}', 'Controller\LogsController@PrintInvoice');
// Route::get('logs/printreport/{id}', 'Controller\LogsController@PrintReport');
Route::get('waybill/{id}', 'Controller\WaybillController@PrintWaybill');
Route::get('deliveryform/{id}', 'Controller\WaybillController@PrintDeliveryForm');
Route::get('terms', 'Controller\LogsController@printTerms');
// Route::get('logs/printreport/{id}', , function ($id)  {
//     return Hasher::decode($id);
// });