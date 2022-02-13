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

$router->group(['middleware' => ['auth']], function() use($router){

	$router->get('getCarList', 'MainController@getCarList');

	$router->get('getCar/{Id}', 'MainController@getCar');

	$router->post('addCar', 'MainController@addCar');

	$router->patch('editCar/{Id}', 'MainController@editCar');

	$router->delete('delCar/{Id}', 'MainController@delCar');

});