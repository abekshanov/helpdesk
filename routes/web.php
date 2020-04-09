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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/orders', 'OrderController@index')->name('orders.index');
Route::get('/orders/show/{id}', 'OrderController@show')->name('orders.show');
Route::get('/orders/close/{id}/{status}', 'OrderController@close')->name('orders.close');
Route::get('/orders/accept/{id}/{user_id}', 'OrderController@accept')->name('orders.accept');
Route::get('/orders/create', function (){
    return view('orders.create');
})->name('orders.create');
Route::post('/orders', 'OrderController@store')->name('orders.store');
