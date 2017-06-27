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

// Route::get('/',['middleware' => 'authSubAdmin','middleware' => 'authRole','uses' => 'Admin\BackendController@index']);
Route::get('/','HomeController@index');
Route::get('/apartamentos/apartamento-lujo-sierra-nevada','HomeController@apartamentoLujo');
Route::get('/apartamentos/estudio-lujo-sierra-nevada','HomeController@estudioLujo');
Route::get('/apartamentos/apartamento-standard-sierra-nevada','HomeController@apartamentoStandard');
Route::get('/apartamentos/estudio-standard-sierra-nevada','HomeController@estudioStandard');
Route::get('/edificio-miramarski-sierra-nevada','HomeController@edificio');
Route::get('/contacto','HomeController@contacto');

Route::post('/getPriceBook','HomeController@getPriceBook');

Route::post('admin/reservas/create' , 'BookController@create');

// Route::get('/admin/propietario',['middleware' => 'authSubAdmin','uses' => 'Admin\BackendController@index']);
// Route::get('/admin/propietario',['middleware' => 'authRole','uses' => 'Admin\BackendController@index']);

Route::group(['middleware' => 'authSubAdmin'], function () {

//Planing 
	
	Route::get('admin/reservas/new' , 'BookController@newBook');
	// Route::post('admin/reservas/create' , 'BookController@create');
	Route::get('admin/reservas/update/{id}' , 'BookController@update');	
	Route::post('admin/reservas/saveUpdate/{id}' , 'BookController@saveUpdate');	
	Route::get('admin/reservas/changeBook/{id}' , 'BookController@changeBook');
	Route::get('admin/reservas/getPriceBook' , 'BookController@getPriceBook');
	Route::get('admin/reservas/getCostBook' , 'BookController@getCostBook');
	Route::get('admin/reservas/getPricePark' , 'BookController@getPricePark');
	Route::get('admin/reservas/getCostPark' , 'BookController@getCostPark');
	Route::get('admin/reservas/{month?}' , 'BookController@index');

	
// Usuarios
	Route::get('admin/usuarios' , 'UsersController@index');
	Route::get('admin/usuarios/new', 'UsersController@newUser');
	Route::get('admin/usuarios/update/{id}', 'UsersController@update');
	Route::post('admin/usuarios/saveAjax', 'UsersController@saveAjax');
	Route::post('admin/usuarios/saveupdate', 'UsersController@saveUpdate');
	Route::post('admin/usuarios/create', 'UsersController@create');
	Route::get('admin/usuarios/delete/{id}', 'UsersController@delete');

// Clientes
	Route::get('admin/clientes' , 'CustomersController@index');
	Route::get('admin/clientes/new', 'CustomersController@newUser');
	Route::get('admin/clientes/update', 'CustomersController@update');
	Route::get('admin/clientes/save', 'CustomersController@save');
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
	Route::get('admin/apartamentos/getPaxPerRooms/{id}', 'RoomsController@getPaxPerRooms');

// Prices
	Route::get('admin/precios' ,['middleware' => 'authSubAdmin','uses' =>  'PricesController@index']);
	Route::get('admin/precios/update', 'PricesController@update');
	Route::get('admin/precios/updateExtra', 'PricesController@updateExtra');
	Route::post('admin/precios/create', 'PricesController@create');
	Route::get('admin/precios/delete/{id}', 'PricesController@delete');
	Route::get('admin/precios/deleteExtra/{id}', 'PricesController@delete');
	Route::post('admin/precios/createExtras', 'PricesController@createExtras');

// seasons
	Route::get('admin/temporadas' , 'SeasonsController@index');
	Route::get('admin/temporadas/new', 'SeasonsController@newSeasons');
	Route::get('admin/temporadas/new-type', 'SeasonsController@newTypeSeasons');
	Route::get('admin/temporadas/update', 'SeasonsController@update');
	Route::post('admin/temporadas/saveupdate', 'SeasonsController@saveUpdate');
	Route::post('admin/temporadas/create', 'SeasonsController@create');
	Route::post('admin/temporadas/create-type', 'SeasonsController@createType');
	Route::get('admin/temporadas/delete/{id}', 'SeasonsController@delete');

// Pagos
	Route::get('admin/pagos' , 'PaymentsController@index');
	Route::get('admin/pagos/create', 'PaymentsController@create');
	Route::get('admin/pagos/update', 'PaymentsController@update');


//Liquidacion
	Route::get('admin/liquidacion' , 'LiquidacionController@index');

//Propietario
	Route::get('admin/propietario' , 'BookController@owned');
	Route::post('admin/propietario/bloquear' , 'BookController@bloqOwned');

});