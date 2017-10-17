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
Route::get('/apartamentos/galeria/{apto}','HomeController@galeriaApartamento');
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
Route::get('/huesped','HomeController@huesped');
Route::get('/el-tiempo','HomeController@tiempo');


Route::post('/getPriceBook','HomeController@getPriceBook');
Route::get('/getFormBook','HomeController@form');

Route::post('admin/reservas/create' , 'BookController@create');

Route::get('/reservas/stripe/pagos/{id_book}', 'StripeController@stripePayment');
Route::post('/reservas/stripe/payment/', 'StripeController@stripePaymentResponse');

//Planing 
	
	// Route::post('admin/reservas/create' , 'BookController@create');
	
	Route::get('admin/reservas/emails/{id}' ,['middleware' => 'authSubAdmin', 'uses' =>  'BookController@emails']);
	Route::get('admin/reservas/new' ,['middleware' => 'authSubAdmin', 'uses' =>  'BookController@newBook']);
	Route::get('admin/reservas/delete/{id}' ,['middleware' => 'authSubAdmin', 'uses' =>  'BookController@delete']);
	Route::get('admin/reservas/update/{id}' ,['middleware' => 'authSubAdmin', 'uses' =>  'BookController@update']);	
	Route::post('admin/reservas/saveUpdate/{id}' ,['middleware' => 'authSubAdmin', 'uses' =>  'BookController@saveUpdate']);	
	Route::get('admin/reservas/changeBook/{id}' ,['middleware' => 'authSubAdmin', 'uses' =>  'BookController@changeBook']);
	Route::get('/admin/reservas/changeStatusBook/{id}' ,['middleware' => 'authSubAdmin', 'uses' =>  'BookController@changeStatusBook']);
	Route::get('admin/reservas/ansbyemail/{id}' ,['middleware' => 'authSubAdmin', 'uses' =>  'BookController@ansbyemail']);
	Route::post('admin/reservas/sendEmail' ,['middleware' => 'authSubAdmin', 'uses' =>  'BookController@sendEmail']);
	Route::get('admin/reservas/sendJaime' ,['middleware' => 'authSubAdmin', 'uses' =>  'BookController@sendJaime']);
	Route::get('admin/reservas/getPriceBook' ,['middleware' => 'authSubAdmin', 'uses' =>  'BookController@getPriceBook']);
	Route::get('admin/reservas/getCostBook' ,['middleware' => 'authSubAdmin', 'uses' =>  'BookController@getCostBook']);
	Route::get('admin/reservas/getPricePark' ,['middleware' => 'authSubAdmin', 'uses' =>  'BookController@getPricePark']);
	Route::get('admin/reservas/getCostPark' ,['middleware' => 'authSubAdmin', 'uses' =>  'BookController@getCostPark']);
	Route::get('admin/reservas/getPriceLujo' ,['middleware' => 'authSubAdmin', 'uses' =>  'BookController@getPriceLujo']);
	Route::get('admin/reservas/getCostLujo' ,['middleware' => 'authSubAdmin', 'uses' =>  'BookController@getCostLujo']);
	Route::get('admin/reservas/getPriceLujoAdmin' ,['middleware' => 'authSubAdmin', 'uses' =>  'BookController@getPriceLujoAdmin']);
	Route::get('admin/reservas/getCostLujoAdmin' ,['middleware' => 'authSubAdmin', 'uses' =>  'BookController@getCostLujoAdmin']);
	Route::get('admin/reservas/saveCobro' ,['middleware' => 'authSubAdmin', 'uses' =>  'BookController@saveCobro']);
	Route::get('admin/reservas/deleteCobro/{id}' ,['middleware' => 'authSubAdmin', 'uses' =>  'BookController@deleteCobro']);
	Route::get('admin/reservas/saveFianza' ,['middleware' => 'authSubAdmin', 'uses' =>  'BookController@saveFianza']);
	Route::get('admin/reservas/reserva/{id}' ,['middleware' => 'authSubAdmin', 'uses' =>  'BookController@tabReserva']);
	Route::get('admin/reservas/cobrar/{id}' ,['middleware' => 'authSubAdmin', 'uses' =>  'BookController@cobroBook']);
	Route::get('admin/reservas/{year?}' ,['middleware' => 'authSubAdmin', 'uses' =>  'BookController@index']);



	
// Usuarios
	Route::get('admin/usuarios' ,['middleware' => 'authAdmin', 'uses' =>   'UsersController@index' ]);
	Route::get('admin/usuarios/update/{id}', ['middleware' => 'authAdmin', 'uses' =>'UsersController@update']);
	Route::post('admin/usuarios/saveAjax',['middleware' => 'authAdmin', 'uses' => 'UsersController@saveAjax']);
	Route::post('admin/usuarios/saveupdate',['middleware' => 'authAdmin', 'uses' => 'UsersController@saveUpdate']);
	Route::post('admin/usuarios/create',['middleware' => 'authAdmin', 'uses' => 'UsersController@create']);
	Route::get('admin/usuarios/delete/{id}',['middleware' => 'authAdmin', 'uses' => 'UsersController@delete']);

// Clientes
	Route::get('admin/clientes' ,['middleware' => 'authAdmin', 'uses' => 'CustomersController@index']);
	Route::get('admin/clientes/update',['middleware' => 'authAdmin', 'uses' => 'CustomersController@update']);
	Route::get('admin/clientes/save',['middleware' => 'authAdmin', 'uses' => 'CustomersController@save']);
	Route::post('admin/clientes/create',['middleware' => 'authAdmin', 'uses' => 'CustomersController@create']);
	Route::get('admin/clientes/delete/{id}',['middleware' => 'authAdmin', 'uses' => 'CustomersController@delete']); 

// Rooms

	Route::get('admin/apartamentos' , ['middleware' => 'authAdmin', 'uses' => 'RoomsController@index']);
	Route::get('admin/apartamentos/new', ['middleware' => 'authAdmin', 'uses' => 'RoomsController@newRoom']);
	Route::get('admin/apartamentos/new-type', ['middleware' => 'authAdmin', 'uses' => 'RoomsController@newTypeRoom']);
	Route::get('admin/apartamentos/new-size', ['middleware' => 'authAdmin', 'uses' => 'RoomsController@newSizeRoom']);
	Route::get('admin/apartamentos/update', ['middleware' => 'authAdmin', 'uses' => 'RoomsController@update']);
	Route::get('admin/apartamentos/update-type', ['middleware' => 'authAdmin', 'uses' => 'RoomsController@updateType']);
	Route::get('admin/apartamentos/update-name', ['middleware' => 'authAdmin', 'uses' => 'RoomsController@updateName']);
	Route::get('admin/apartamentos/update-nameRoom', ['middleware' => 'authAdmin', 'uses' => 'RoomsController@updateNameRoom']);
	Route::get('admin/apartamentos/update-order', ['middleware' => 'authAdmin', 'uses' => 'RoomsController@updateOrder']);
	Route::get('admin/apartamentos/update-size', ['middleware' => 'authAdmin', 'uses' => 'RoomsController@updateSize']);
	Route::post('admin/apartamentos/saveupdate', ['middleware' => 'authAdmin', 'uses' => 'RoomsController@saveUpdate']);
	Route::post('admin/apartamentos/create', ['middleware' => 'authAdmin', 'uses' => 'RoomsController@create']);
	Route::post('admin/apartamentos/create-type', ['middleware' => 'authAdmin', 'uses' => 'RoomsController@createType']);
	Route::post('admin/apartamentos/create-size', ['middleware' => 'authAdmin', 'uses' => 'RoomsController@createSize']);
	Route::get('admin/apartamentos/state', ['middleware' => 'authAdmin', 'uses' => 'RoomsController@state']);
	Route::get('admin/apartamentos/getPaxPerRooms/{id}', ['middleware' => 'authSubAdmin', 'uses' => 'RoomsController@getPaxPerRooms']);
	Route::get('admin/apartamentos/getLuxuryPerRooms/{id}', ['middleware' => 'authSubAdmin', 'uses' => 'RoomsController@getLuxuryPerRooms']);
	Route::get('admin/apartamentos/uploadFile/{id}', ['middleware' => 'authAdmin', 'uses' => 'RoomsController@uploadFile']);


// Prices
	Route::get('admin/precios' ,['middleware' => 'authAdmin', 'uses' =>'PricesController@index']);
	Route::get('admin/precios/update',['middleware' => 'authAdmin', 'uses' => 'PricesController@update']);
	Route::get('admin/precios/updateExtra',['middleware' => 'authAdmin', 'uses' => 'PricesController@updateExtra']);
	Route::post('admin/precios/create',['middleware' => 'authAdmin', 'uses' => 'PricesController@create']);
	Route::get('admin/precios/delete/{id}',['middleware' => 'authAdmin', 'uses' => 'PricesController@delete']);
	Route::get('admin/precios/deleteExtra/{id}',['middleware' => 'authAdmin', 'uses' => 'PricesController@delete']);
	Route::post('admin/precios/createExtras',['middleware' => 'authAdmin', 'uses' => 'PricesController@createExtras']);

// seasons
	Route::get('admin/temporadas' ,['middleware' => 'authAdmin', 'uses' => 'SeasonsController@index']);
	Route::get('admin/temporadas/new',['middleware' => 'authAdmin', 'uses' => 'SeasonsController@newSeasons']);
	Route::get('admin/temporadas/new-type',['middleware' => 'authAdmin', 'uses' => 'SeasonsController@newTypeSeasons']);
	Route::get('admin/temporadas/update',['middleware' => 'authAdmin', 'uses' => 'SeasonsController@update']);
	Route::post('admin/temporadas/saveupdate',['middleware' => 'authAdmin', 'uses' => 'SeasonsController@saveUpdate']);
	Route::post('admin/temporadas/create',['middleware' => 'authAdmin', 'uses' => 'SeasonsController@create']);
	Route::post('admin/temporadas/create-type',['middleware' => 'authAdmin', 'uses' => 'SeasonsController@createType']);
	Route::get('admin/temporadas/delete/{id}',['middleware' => 'authAdmin', 'uses' => 'SeasonsController@delete']);

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
	Route::get('admin/propietario/bloquear' , 'OwnedController@bloqOwned');
	Route::get('admin/propietario/{name?}/operativa' , 'OwnedController@operativaOwned');
	Route::get('admin/propietario/{name?}/tarifas' , 'OwnedController@tarifasOwned');
	Route::get('admin/propietario/{name?}/descuentos' , 'OwnedController@descuentosOwned');
	Route::get('admin/propietario/{name?}/fiscalidad' , 'OwnedController@fiscalidadOwned');
	Route::get('admin/propietario/{name?}/{year?}' , 'OwnedController@index');


//PDF´s

Route::get('admin/pdf/pdf-reserva/{id}','PdfController@invoice');

Route::group(['middleware' => 'auth'], function () {
		Route::get('/admin', function ()    {
    	$user = \Auth::user(); 
			if ($user->role == "propietario") {
				$room = \App\Rooms::where('owned', $user->id)->first();
			 	return redirect('admin/propietario/'.$room->nameRoom);
			}else{
				return redirect('admin/reservas');
			}


	});
});