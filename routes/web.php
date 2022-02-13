<?php

use Illuminate\Support\Facades\Route;

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

Route::get('getCarList', 'MainController@getCarList');

Route::get('getCar/{Id}', 'MainController@getCar');

Route::post('addCar', 'MainController@addCar');

Route::patch('editCar/{Id}', 'MainController@editCar');

Route::delete('delCar/{Id}', 'MainController@delCar');
