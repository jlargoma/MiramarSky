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
Route::auth();

Route::get('/',[
				'middleware' => 'auth',
				'uses' => 'Admin\BackendController@index'
			]);

Route::group(['middleware' => 'auth'], function () {

//Planing 
	Route::get('reservas' , 'BookController@index');
	Route::get('reservas/new' , 'BookController@newBook');
	Route::post('reservas/create' , 'BookController@create');
	Route::get('reservas/update/{id}' , 'BookController@update');	
	Route::post('reservas/saveUpdate/{id}' , 'BookController@saveUpdate');	
	Route::get('reservas/changeBook/{id}' , 'BookController@changeBook');
	Route::get('reservas/getPriceBook' , 'BookController@getPriceBook');
	Route::get('reservas/getCostBook' , 'BookController@getCostBook');
	Route::get('reservas/getPricePark' , 'BookController@getPricePark');
	Route::get('reservas/getCostPark' , 'BookController@getCostPark');
	
// Usuarios
	Route::get('usuarios' , 'UsersController@index');
	Route::get('usuarios/new', 'UsersController@newUser');
	Route::get('usuarios/update/{id}', 'UsersController@update');
	Route::post('usuarios/saveAjax', 'UsersController@saveAjax');
	Route::post('usuarios/saveupdate', 'UsersController@saveUpdate');
	Route::post('usuarios/create', 'UsersController@create');
	Route::get('usuarios/delete/{id}', 'UsersController@delete');

// Clientes
	Route::get('clientes' , 'CustomersController@index');
	Route::get('clientes/new', 'CustomersController@newUser');
	Route::get('clientes/update', 'CustomersController@update');
	Route::get('clientes/save', 'CustomersController@save');
	Route::post('clientes/create', 'CustomersController@create');
	Route::get('clientes/delete/{id}', 'CustomersController@delete');

// Rooms
	Route::get('apartamentos' , 'RoomsController@index');
	Route::get('apartamentos/new', 'RoomsController@newRoom');
	Route::get('apartamentos/new-type', 'RoomsController@newTypeRoom');
	Route::get('apartamentos/new-size', 'RoomsController@newSizeRoom');
	Route::get('apartamentos/update', 'RoomsController@update');
	Route::post('apartamentos/saveupdate', 'RoomsController@saveUpdate');
	Route::post('apartamentos/create', 'RoomsController@create');
	Route::post('apartamentos/create-type', 'RoomsController@createType');
	Route::post('apartamentos/create-size', 'RoomsController@createSize');
	Route::get('apartamentos/delete/{id}', 'RoomsController@delete');
	Route::get('apartamentos/getPaxPerRooms/{id}', 'RoomsController@getPaxPerRooms');

// Prices
	Route::get('precios' , 'PricesController@index');
	Route::get('precios/update', 'PricesController@update');
	Route::get('precios/updateExtra', 'PricesController@updateExtra');
	Route::post('precios/create', 'PricesController@create');
	Route::get('precios/delete/{id}', 'PricesController@delete');
	Route::get('precios/deleteExtra/{id}', 'PricesController@delete');
	Route::post('precios/createExtras', 'PricesController@createExtras');

// seasons
	Route::get('temporadas' , 'SeasonsController@index');
	Route::get('temporadas/new', 'SeasonsController@newSeasons');
	Route::get('temporadas/new-type', 'SeasonsController@newTypeSeasons');
	Route::get('temporadas/update', 'SeasonsController@update');
	Route::post('temporadas/saveupdate', 'SeasonsController@saveUpdate');
	Route::post('temporadas/create', 'SeasonsController@create');
	Route::post('temporadas/create-type', 'SeasonsController@createType');
	Route::get('temporadas/delete/{id}', 'SeasonsController@delete');

// Pagos
	Route::get('pagos' , 'PaymentsController@index');
	Route::get('pagos/create', 'PaymentsController@create');

});