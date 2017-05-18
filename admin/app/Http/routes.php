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
//Planing 
	Route::get('planning' , 'BookController@index');
	Route::get('planning/new' , 'BookController@newBook');
	
	
// Usuarios
	Route::get('usuarios' , 'UsersController@index');
	Route::get('usuarios/new', 'UsersController@newUser');
	Route::get('usuarios/update', 'UsersController@update');
	Route::post('usuarios/saveupdate', 'UsersController@saveUpdate');
	Route::post('usuarios/create', 'UsersController@create');
	Route::get('usuarios/delete/{id}', 'UsersController@delete');

// Clientes
	Route::get('clientes' , 'CustomersController@index');
	Route::get('clientes/new', 'CustomersController@newUser');
	Route::get('clientes/update', 'CustomersController@update');
	Route::post('clientes/saveupdate', 'CustomersController@saveUpdate');
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

// Prices
	Route::get('precios' , 'PricesController@index');
	Route::get('precios/new', 'PricesController@newPrices');
	Route::get('precios/newSpecial', 'PricesController@newSpecialPrices');
	Route::get('precios/update', 'PricesController@update');
	Route::post('precios/saveupdate', 'PricesController@saveUpdate');
	Route::post('precios/create', 'PricesController@create');
	Route::post('precios/createSpecial', 'PricesController@createSpecial');
	Route::get('precios/delete/{id}', 'PricesController@delete');

// seasons
	Route::get('temporadas' , 'SeasonsController@index');
	Route::get('temporadas/new', 'SeasonsController@newSeasons');
	Route::get('temporadas/new-type', 'SeasonsController@newTypeSeasons');
	Route::get('temporadas/update', 'SeasonsController@update');
	Route::post('temporadas/saveupdate', 'SeasonsController@saveUpdate');
	Route::post('temporadas/create', 'SeasonsController@create');
	Route::post('temporadas/create-type', 'SeasonsController@createType');
	Route::get('temporadas/delete/{id}', 'SeasonsController@delete');

