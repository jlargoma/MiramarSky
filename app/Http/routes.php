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
Route::get('/sitemap','HomeController@siteMap');
Route::get('/apartamentos/{apto}','HomeController@apartamento');
Route::get('/edificio-miramarski-sierra-nevada','HomeController@edificio');
Route::get('/contacto','HomeController@contacto');
//Correos Frontend
	Route::post('/contacto-form','HomeController@formContacto');
	Route::post('/contacto-ayuda','HomeController@formAyuda');
	Route::post('/contacto-propietario','HomeController@formPropietario');
	Route::post('/contacto-grupos','HomeController@formGrupos');
// Correos Frontend
Route::get('/terminos-condiciones','HomeController@terminos');
Route::get('/politica-cookies','HomeController@politicaCookies');
Route::get('/politica-privacidad','HomeController@politicaPrivacidad');
Route::get('/condiciones-generales','HomeController@condicionesGenerales');
Route::get('/preguntas-frecuentes','HomeController@preguntasFrecuentes');
Route::get('/eres-propietario','HomeController@eresPropietario');
Route::get('/grupos','HomeController@grupos');
Route::get('/quienes-somos','HomeController@quienesSomos');
Route::get('/ayudanos-a-mejorar','HomeController@ayudanosAMejorar');
Route::get('/aviso-legal','HomeController@avisoLegal');
Route::get('/el-tiempo','HomeController@tiempo');


Route::post('/getPriceBook','HomeController@getPriceBook');
Route::get('/getFormBook','HomeController@form');

Route::post('admin/reservas/create' , 'BookController@create');
// Route::get('/test' , 'MailController@sendEmailBookSuccess');
// Route::get('/admin/propietario',['middleware' => 'authSubAdmin','uses' => 'Admin\BackendController@index']);
// Route::get('/admin/propietario',['middleware' => 'authRole','uses' => 'Admin\BackendController@index']);

Route::group(['middleware' => 'authSubAdmin'], function () {
	Route::get('/admin','BookController@index');

//Planing 
	
	Route::get('admin/reservas/emails/{id}' , 'BookController@emails');
	Route::get('admin/reservas/new' , 'BookController@newBook');
	Route::get('admin/reservas/delete/{id}' , 'BookController@delete');
	// Route::post('admin/reservas/create' , 'BookController@create');
	Route::get('admin/reservas/update/{id}' , 'BookController@update');	
	Route::post('admin/reservas/saveUpdate/{id}' , 'BookController@saveUpdate');	
	Route::get('admin/reservas/changeBook/{id}' , 'BookController@changeBook');
	Route::get('admin/reservas/ansbyemail/{id}' , 'BookController@ansbyemail');
	Route::post('admin/reservas/sendEmail' , 'BookController@sendEmail');
	Route::get('admin/reservas/sendJaime' , 'BookController@sendJaime');
	Route::get('admin/reservas/getPriceBook' , 'BookController@getPriceBook');
	Route::get('admin/reservas/getCostBook' , 'BookController@getCostBook');
	Route::get('admin/reservas/getPricePark' , 'BookController@getPricePark');
	Route::get('admin/reservas/getCostPark' , 'BookController@getCostPark');
	Route::get('admin/reservas/getPriceLujo' , 'BookController@getPriceLujo');
	Route::get('admin/reservas/getCostLujo' , 'BookController@getCostLujo');
	Route::get('admin/reservas/getPriceLujoAdmin' , 'BookController@getPriceLujoAdmin');
	Route::get('admin/reservas/getCostLujoAdmin' , 'BookController@getCostLujoAdmin');
	Route::get('admin/reservas/saveCobro' , 'BookController@saveCobro');
	Route::get('admin/reservas/deleteCobro/{id}' , 'BookController@deleteCobro');
	Route::get('admin/reservas/saveFianza' , 'BookController@saveFianza');
	Route::get('admin/reservas/{year?}' , 'BookController@index');
	Route::get('admin/reservas/reserva/{id}' , 'BookController@tabReserva');
	Route::get('admin/reservas/cobrar/{id}' , 'BookController@cobroBook');


	
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
	Route::get('admin/apartamentos/update-type', 'RoomsController@updateType');
	Route::get('admin/apartamentos/update-name', 'RoomsController@updateName');
	Route::get('admin/apartamentos/update-order', 'RoomsController@updateOrder');
	Route::get('admin/apartamentos/update-size', 'RoomsController@updateSize');
	Route::post('admin/apartamentos/saveupdate', 'RoomsController@saveUpdate');
	Route::post('admin/apartamentos/create', 'RoomsController@create');
	Route::post('admin/apartamentos/create-type', 'RoomsController@createType');
	Route::post('admin/apartamentos/create-size', 'RoomsController@createSize');
	Route::get('admin/apartamentos/state', 'RoomsController@state');
	Route::get('admin/apartamentos/getPaxPerRooms/{id}', 'RoomsController@getPaxPerRooms');
	Route::get('admin/apartamentos/getLuxuryPerRooms/{id}', 'RoomsController@getLuxuryPerRooms');
	Route::get('admin/apartamentos/uploadfile', 'RoomsController@uploadFile');

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

// Pagos-Propietarios
	Route::get('admin/pagos-propietarios/{month?}' , 'PaymentsProController@index');
	Route::post('admin/pagos-propietarios/create', 'PaymentsProController@create');
	Route::get('admin/pagos-propietarios/update/{id}/{month?}', 'PaymentsProController@update');


//Liquidacion
	Route::get('admin/liquidacion/{year?}' , 'LiquidacionController@index');
	Route::get('admin/liquidacion-apartamentos/{year?}' , 'LiquidacionController@apto');
	Route::get('admin/estadisticas' , 'LiquidacionController@Statistics');
	Route::get('admin/perdidas-ganancias' , 'LiquidacionController@perdidas');
	

//Propietario
	Route::get('admin/propietario/{year?}' , 'OwnedController@index');
	Route::post('admin/propietario/bloquear' , 'OwnedController@bloqOwned');

});

//PDF´s

	Route::get('admin/pdf/pdf-reserva/{id}','PdfController@invoice');