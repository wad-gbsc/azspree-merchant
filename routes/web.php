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
Auth::routes();
Route::get('/', function () {
    return view('welcome');
});

// Route::get('qr_code', function () {
//     \QrCode::size(500)
//               ->format('png')
//               ->generate('ItSolutionStuff.com', public_path('images/qrcode.png'));
      
//     return view('qr_code');
      
//   });