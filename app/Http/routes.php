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

// Usuarios
	Route::get('admin/usuarios' , 'UsersController@index');
	Route::get('admin/usuarios/new', 'UsersController@newUser');
	Route::get('admin/usuarios/update', 'UsersController@update');
	Route::post('admin/usuarios/saveupdate', 'UsersController@saveUpdate');
	Route::post('admin/usuarios/create', 'UsersController@create');
	Route::get('admin/usuarios/delete/{id}', 'UsersController@delete');

// Rooms
	Route::get('admin/apartamento' , 'RoomsController@index');
	Route::get('admin/apartamento/new', 'RoomsController@newRoom');
	Route::get('admin/apartamento/update', 'RoomsController@update');
	Route::post('admin/apartamento/saveupdate', 'RoomsController@saveUpdate');
	Route::post('admin/apartamento/create', 'RoomsController@create');
	Route::get('admin/apartamento/delete/{id}', 'RoomsController@delete');


