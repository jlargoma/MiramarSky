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

Route::get('/', function () {
    return view('welcome');
});



Route::get('/home', 'HomeController@index');
Route::get('/admin',[
							'middleware' => 'auth',
							'uses' => 'Admin\BackendController@index'
						]);
//Planing 
	Route::get('admin/planning' , 'BookController@index');
	
	
// Usuarios
	Route::get('admin/usuarios' , 'UsersController@index');
	Route::get('admin/usuarios/new', 'UsersController@newUser');
	Route::get('admin/usuarios/update', 'UsersController@update');
	Route::post('admin/usuarios/saveupdate', 'UsersController@saveUpdate');
	Route::post('admin/usuarios/create', 'UsersController@create');
	Route::get('admin/usuarios/delete/{id}', 'UsersController@delete');

// Clientes
	Route::get('admin/clientes' , 'CustomersController@index');
	Route::get('admin/clientes/new', 'CustomersController@newUser');
	Route::get('admin/clientes/update', 'CustomersController@update');
	Route::post('admin/clientes/saveupdate', 'CustomersController@saveUpdate');
	Route::post('admin/clientes/create', 'CustomersController@create');
	Route::get('admin/clientes/delete/{id}', 'CustomersController@delete');

// Rooms
	Route::get('admin/apartamentos' , 'RoomsController@index');
	Route::get('admin/apartamentos/new', 'RoomsController@newRoom');
	Route::get('admin/apartamentos/new-type', 'RoomsController@newTypeRoom');
	Route::get('admin/apartamentos/new-size', 'RoomsController@newSizeRoom');
	Route::get('admin/apartamentos/update', 'RoomsController@update');
	Route::post('admin/apartamentos/saveupdate', 'RoomsController@saveUpdate');
	Route::post('admin/apartamentos/create', 'RoomsController@create');
	Route::post('admin/apartamentos/create-type', 'RoomsController@createType');
	Route::post('admin/apartamentos/create-size', 'RoomsController@createSize');
	Route::get('admin/apartamentos/delete/{id}', 'RoomsController@delete');

// Prices
	Route::get('admin/precios' , 'PricesController@index');
	Route::get('admin/precios/new', 'PricesController@newPrices');
	Route::get('admin/precios/newSpecial', 'PricesController@newSpecialPrices');
	Route::get('admin/precios/update', 'PricesController@update');
	Route::post('admin/precios/saveupdate', 'PricesController@saveUpdate');
	Route::post('admin/precios/create', 'PricesController@create');
	Route::post('admin/precios/createSpecial', 'PricesController@createSpecial');
	Route::get('admin/precios/delete/{id}', 'PricesController@delete');

// seasons
	Route::get('admin/temporadas' , 'SeasonsController@index');
	Route::get('admin/temporadas/new', 'SeasonsController@newSeasons');
	Route::get('admin/temporadas/new-type', 'SeasonsController@newTypeSeasons');
	Route::get('admin/temporadas/update', 'SeasonsController@update');
	Route::post('admin/temporadas/saveupdate', 'SeasonsController@saveUpdate');
	Route::post('admin/temporadas/create', 'SeasonsController@create');
	Route::post('admin/temporadas/create-type', 'SeasonsController@createType');
	Route::get('admin/temporadas/delete/{id}', 'SeasonsController@delete');

