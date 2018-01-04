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
Route::get('testt', function() {
	return 'ok';
});
Route::auth();
Route::get('/','HomeController@index');
Route::get('/sitemap','HomeController@siteMap');
Route::get('/apartamentos/galeria/{apto}','HomeController@galeriaApartamento');
Route::get('/apartamentos/{apto}','HomeController@apartamento');
Route::get('/fotos/{apto}','HomeController@apartamento');
Route::get('/edificio-miramarski-sierra-nevada','HomeController@edificio');
Route::get('/contacto','HomeController@contacto');

/*Correos Frontend */
	Route::post('/contacto-form','HomeController@formContacto');
	Route::post('/contacto-ayuda','HomeController@formAyuda');
	Route::post('/contacto-propietario','HomeController@formPropietario');
	Route::post('/contacto-grupos','HomeController@formGrupos');
/* Correos Frontend */
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

Route::get('/restaurantes', function ()
{
	
	$mobile = new \App\Classes\Mobile();
	return view('frontend.restaurantes', ['mobile' => $mobile]);
});


Route::post('/solicitudForfait','HomeController@solicitudForfait');

Route::get('/admin/links-stripe', 'StripeController@link');

/* SUPERMERCADO */
	Route::get('/supermercado','StoreController@index');
	Route::get('/supermercado/reserva/{id}','StoreController@cartByBook');
	Route::get('/supermercado/addCart','StoreController@addCart');
	Route::get('/supermercado/search/searchByName','StoreController@searchByName');
	Route::get('/supermercado/getSummaryCart','StoreController@getSummaryCart');
	Route::get('/searchBook','StoreController@searchBook');
	Route::get('/supermercado/pedidos/delete/{id}',function ($id)
	{
		$orderProduct = \App\Products_orders::find($id);
		if ($orderProduct->delete()) {
			return redirect()->back();
		}
	});
	Route::get('/supermercado/checkout/{id}','StoreController@checkout');
	Route::post('/supermercado/stripe/payment','StoreController@payment');
/* FIN SUPERMERCADO */

/* ENCUESTAS */
Route::get('/encuesta-satisfaccion/{id}','QuestionsController@index');
Route::post('/questions/vote','QuestionsController@vote');


/* FIN ENCUESTAS*/
/* CRONTABS */
Route::get('/admin/reservas/api/checkSecondPay','BookController@checkSecondPay');


/* Planing */
	Route::post('/getPriceBook','HomeController@getPriceBook');
	Route::get('/getFormBook','HomeController@form');
	Route::get('/getCitiesByCountry','HomeController@getCitiesByCountry');
	Route::get('/getCalendarMobile','BookController@getCalendarMobileView');
	Route::post('admin/reservas/create' , 'BookController@create');

	Route::post('admin/reservas/stripe/save/fianza' ,['middleware' => 'auth', 'uses' =>  'StripeController@fianza']);
	Route::post('admin/reservas/stripe/pay/fianza' ,['middleware' => 'auth', 'uses' =>  'StripeController@payFianza']);

	Route::get('/reservas/stripe/pagos/{id_book}', 'StripeController@stripePayment');
	Route::post('/reservas/stripe/payment/', 'StripeController@stripePaymentResponse');
	Route::post('/admin/reservas/stripe/paymentsBooking', 'StripeController@stripePaymentBooking');

	Route::get('admin/reservas' ,['middleware' => 'authSubAdmin', 'uses' =>  'BookController@index']);
	Route::get('admin/reservas/emails/{id}' ,['middleware' => 'authSubAdmin', 'uses' =>  'BookController@emails']);
	Route::get('admin/reservas/new' ,['middleware' => 'auth', 'uses' =>  'BookController@newBook']);
	Route::get('admin/reservas/delete/{id}' ,['middleware' => 'auth', 'uses' =>  'BookController@delete']);
	Route::get('admin/reservas/update/{id}' ,['middleware' => 'auth', 'uses' =>  'BookController@update']);	
	Route::post('admin/reservas/saveUpdate/{id}' ,['middleware' => 'auth', 'uses' =>  'BookController@saveUpdate']);	
	Route::get('admin/reservas/changeBook/{id}' ,['middleware' => 'auth', 'uses' =>  'BookController@changeBook']);
	Route::get('/admin/reservas/changeStatusBook/{id}' ,['middleware' => 'auth', 'uses' =>  'BookController@changeStatusBook']);
	Route::get('admin/reservas/ansbyemail/{id}' ,['middleware' => 'auth', 'uses' =>  'BookController@ansbyemail']);
	Route::post('admin/reservas/sendEmail' ,['middleware' => 'auth', 'uses' =>  'BookController@sendEmail']);
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
	Route::get('admin/reservas/saveFianza' ,['middleware' => 'auth', 'uses' =>  'BookController@saveFianza']);
	Route::get('admin/reservas/reserva/{id}' ,['middleware' => 'auth', 'uses' =>  'BookController@tabReserva']);
	Route::get('admin/reservas/cobrar/{id}' ,['middleware' => 'auth', 'uses' =>  'BookController@cobroBook']);
	Route::get('admin/reservas/{year?}' ,['middleware' => 'auth', 'uses' =>  'BookController@index']);

	Route::get('admin/reservas/search/searchByName' , 'BookController@searchByName');
	Route::get('admin/reservas/api/getTableData' ,['middleware' => 'auth', 'uses' =>  'BookController@getTableData']);
	Route::get('admin/reservas/api/lastsBooks' ,['middleware' => 'auth', 'uses' =>  'BookController@getLastBooks']);
	Route::get('admin/reservas/api/calendarBooking' ,['middleware' => 'auth', 'uses' =>  'BookController@getCalendarBooking']);
	Route::get('admin/reservas/api/alertsBooking' ,['middleware' => 'auth', 'uses' =>  'BookController@getAlertsBooking']);

	Route::get('admin/reservas/api/sendSencondEmail' ,['middleware' => 'auth', 'uses' =>  'BookController@sendSencondEmail']);
	Route::get('/admin/reservas/fianzas/cobrar/{id}' ,['middleware' => 'auth', 'uses' =>  'BookController@cobrarFianzas']);
	Route::get('admin/cambiarCostes', 'BookController@changeCostes');
	
// Usuarios
	Route::get('admin/usuarios' ,['middleware' => 'authAdmin', 'uses' =>   'UsersController@index' ]);
	Route::get('admin/usuarios/update/{id}', ['middleware' => 'authAdmin', 'uses' =>'UsersController@update']);
	Route::post('admin/usuarios/saveAjax',['middleware' => 'authAdmin', 'uses' => 'UsersController@saveAjax']);
	Route::post('admin/usuarios/saveupdate',['middleware' => 'authAdmin', 'uses' => 'UsersController@saveUpdate']);
	Route::post('admin/usuarios/create',['middleware' => 'authAdmin', 'uses' => 'UsersController@create']);
	Route::get('admin/usuarios/delete/{id}',['middleware' => 'authAdmin', 'uses' => 'UsersController@delete']);

// Clientes
	Route::get('admin/clientes' ,['middleware' => 'authAdmin', 'uses' => 'CustomersController@index']);
	Route::get('admin/clientes/update',['middleware' => 'auth', 'uses' => 'CustomersController@update']);
	Route::get('admin/clientes/save',['middleware' => 'auth', 'uses' => 'CustomersController@save']);
	Route::post('admin/clientes/create',['middleware' => 'auth', 'uses' => 'CustomersController@create']);
	Route::get('admin/clientes/export-excel' , ['middleware' => 'auth', 'uses' => 'CustomersController@createExcel']);
	Route::get('admin/customers/importExcelData' , ['middleware' => 'auth', 'uses' => 'CustomersController@createExcel']);
	Route::get('admin/clientes/delete/{id}',['middleware' => 'auth', 'uses' => 'CustomersController@delete']); 


// Rooms

	Route::get('admin/apartamentos' , ['middleware' => 'auth', 'uses' => 'RoomsController@index']);
	Route::get('admin/apartamentos/new', ['middleware' => 'auth', 'uses' => 'RoomsController@newRoom']);
	Route::get('admin/apartamentos/new-type', ['middleware' => 'auth', 'uses' => 'RoomsController@newTypeRoom']);
	Route::get('admin/apartamentos/new-size', ['middleware' => 'auth', 'uses' => 'RoomsController@newSizeRoom']);
	Route::get('admin/apartamentos/update', ['middleware' => 'auth', 'uses' => 'RoomsController@update']);
	Route::get('admin/apartamentos/update-type', ['middleware' => 'auth', 'uses' => 'RoomsController@updateType']);
	Route::get('admin/apartamentos/update-name', ['middleware' => 'auth', 'uses' => 'RoomsController@updateName']);
	Route::get('admin/apartamentos/update-nameRoom', ['middleware' => 'auth', 'uses' => 'RoomsController@updateNameRoom']);
	Route::get('admin/apartamentos/update-order', ['middleware' => 'auth', 'uses' => 'RoomsController@updateOrder']);
	Route::get('admin/apartamentos/update-size', ['middleware' => 'auth', 'uses' => 'RoomsController@updateSize']);
	Route::get('admin/apartamentos/update-owned', ['middleware' => 'auth', 'uses' => 'RoomsController@updateOwned']);
	Route::get('admin/apartamentos/update-parking', ['middleware' => 'auth', 'uses' => 'RoomsController@updateParking']);
	Route::get('admin/apartamentos/update-taquilla', ['middleware' => 'auth', 'uses' => 'RoomsController@updateTaquilla']);
	Route::post('admin/apartamentos/saveupdate', ['middleware' => 'auth', 'uses' => 'RoomsController@saveUpdate']);
	Route::post('admin/apartamentos/create', ['middleware' => 'auth', 'uses' => 'RoomsController@create']);
	Route::post('admin/apartamentos/create-type', ['middleware' => 'auth', 'uses' => 'RoomsController@createType']);
	Route::post('admin/apartamentos/create-size', ['middleware' => 'auth', 'uses' => 'RoomsController@createSize']);
	Route::get('admin/apartamentos/state', ['middleware' => 'auth', 'uses' => 'RoomsController@state']);
	Route::get('admin/apartamentos/percentApto', ['middleware' => 'auth', 'uses' => 'RoomsController@percentApto']);
	Route::get('admin/apartamentos/update-Percent', ['middleware' => 'auth', 'uses' => 'RoomsController@updatePercent']);
	Route::get('admin/apartamentos/email/{id}' , ['middleware' => 'auth', 'uses' => 'RoomsController@email']);
	Route::get('admin/apartamentos/fotos/{id}' , ['middleware' => 'auth', 'uses' => 'RoomsController@photo']);
	Route::get('admin/apartamentos/deletePhoto/{id}' , ['middleware' => 'auth', 'uses' => 'RoomsController@deletePhoto']);
	Route::post('admin/apartamentos/send/email/owned' , ['middleware' => 'auth', 'uses' => 'RoomsController@sendEmailToOwned']);
	Route::get('admin/apartamentos/getPaxPerRooms/{id}', ['middleware' => 'auth', 'uses' => 'RoomsController@getPaxPerRooms']);
	Route::get('admin/apartamentos/getLuxuryPerRooms/{id}', ['middleware' => 'auth', 'uses' => 'RoomsController@getLuxuryPerRooms']);
	Route::post('admin/apartamentos/uploadFile/{id}', ['middleware' => 'auth', 'uses' => 'RoomsController@uploadFile']);
	Route::get('admin/apartamentos/assingToBooking', ['middleware' => 'auth', 'uses' => 'RoomsController@assingToBooking']);

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
	Route::get('admin/pagos' , ['middleware' => 'authAdmin', 'uses' => 'PaymentsController@index']);
	Route::get('admin/pagos/create',['middleware' => 'authAdmin', 'uses' =>  'PaymentsController@create']);
	Route::get('admin/pagos/update',['middleware' => 'authAdmin', 'uses' =>  'PaymentsController@update']);

// Pagos-Propietarios
	Route::get('admin/pagos-propietarios/{month?}' ,['middleware' => 'authAdmin', 'uses' => 'PaymentsProController@index']);
	Route::post('admin/pagos-propietarios/create', ['middleware' => 'authAdmin', 'uses' =>'PaymentsProController@create']);
	Route::get('admin/pagos-propietarios/update/{id}/{month?}',['middleware' => 'authAdmin', 'uses' => 'PaymentsProController@update']);
	Route::get('admin/paymentspro/getBooksByRoom/{idRoom}' , 'PaymentsProController@getBooksByRoom');

//Liquidacion
	Route::get('admin/liquidacion/{year?}' ,['middleware' => 'authAdmin', 'uses' => 'LiquidacionController@index']);
	Route::get('admin/liquidacion-apartamentos/{year?}' ,['middleware' => 'authAdmin', 'uses' => 'LiquidacionController@apto']);
	Route::get('admin/estadisticas/{year?}' ,['middleware' => 'authAdmin', 'uses' => 'LiquidacionController@Statistics']);
	Route::get('admin/contabilidad/{year?}' ,['middleware' => 'authAdmin', 'uses' => 'LiquidacionController@contabilidad']);
	Route::get('admin/perdidas-ganancias' ,['middleware' => 'authAdmin', 'uses' => 'LiquidacionController@perdidas']);

//Facturas
	Route::get('admin/facturas/{year?}' ,['middleware' => 'authAdmin', 'uses' => 'InvoicesController@index']);
	Route::get('admin/facturas/ver/{id}' ,['middleware' => 'auth', 'uses' => 'InvoicesController@view']);
	Route::get('admin/facturas/descargar/{id}' ,['middleware' => 'auth', 'uses' => 'InvoicesController@download']);

//Facturas
	Route::get('admin/encuestas/{year?}/{apto?}' ,['middleware' => 'authAdmin', 'uses' => 'QuestionsController@admin']);
	
//Propietario
	Route::get('admin/propietario/bloquear' , 'OwnedController@bloqOwned');
	Route::get('admin/propietario/{name?}/operativa' , 'OwnedController@operativaOwned');
	Route::get('admin/propietario/{name?}/tarifas' , 'OwnedController@tarifasOwned');
	Route::get('admin/propietario/{name?}/descuentos' , 'OwnedController@descuentosOwned');
	Route::get('admin/propietario/{name?}/fiscalidad' , 'OwnedController@fiscalidadOwned');
	Route::get('admin/propietario/{name?}/facturas' , 'OwnedController@facturasOwned');
	Route::get('admin/propietario/{name?}/{year?}' , 'OwnedController@index');


	Route::get('admin/propietario/create/password/{email}' , 'UsersController@createPasswordUser');
	Route::post('admin/propietario/create/password/{email}' , 'UsersController@createPasswordUser');

// AUX PROPIETARIOS 
	Route::get('admin/propietarios/dashboard/{name?}/{year?}' , ['middleware' => 'authAdmin', 'uses' => 'OwnedController@index'] );

//PDF´s

Route::get('admin/pdf/pdf-reserva/{id}','PdfController@invoice');

Route::group(['middleware' => 'auth'], function () {
	Route::get('/admin/rooms/api/getImagesRoom/{id?}', 'RoomsController@getImagesRoom');

	Route::get('/admin', function ()    {
	$user = \Auth::user(); 
		if ($user->role == "propietario") {
			$room = \App\Rooms::where('owned', $user->id)->first();
		 	return redirect('admin/propietario/'.$room->nameRoom);
		}else{
			return redirect('admin/reservas');
		}


	});

	Route::get('admin/reservas/help/calculateBook', function () {
		return view('backend.planning._calculateBook');
	});

	Route::get('admin/update/seasonsDays/{val}', function ($val) {

		$seasonDays = \App\SeasonDays::first();
		$seasonDays->numDays = $val;

		if ($seasonDays->save()) {
			return "Cambiado";
		}

	});

	Route::post('admin/reservas/help/getTotalBook', 'BookController@getTotalBook');
	
	Route::get('admin/delete/nofify/{id}', function ($id) {
		$notify = \App\BookNotification::find($id);

		if ($notify->delete()) {
			return view('backend.planning._tableAlertBooking', ['notifications' => \App\BookNotification::all()]);
		}
	});

	Route::get('admin/reservas/changeSchedule/{id}/{type}/{schedule}', function ($id,$type, $schedule) {
		

		$book = \App\Book::find($id);
		if ( $type == 1) {
			$book->schedule = $schedule;
		} else {
			$book->scheduleOut = $schedule;
		}

		if ($book->save()) {
			echo "Cambiado!";
		}

	});

	Route::get('admin/liquidation/searchByName', 'LiquidacionController@searchByName');

	Route::get('admin/liquidation/searchByRoom', 'LiquidacionController@searchByRoom');


	

	Route::get('/admin/apartamentos/rooms/getTableRooms/', function(){

		return view('backend.rooms._tableRooms',[
						                    'rooms'     => \App\Rooms::where('state',1)->orderBy('order','ASC')->get(),
						                    'roomsdesc' => \App\Rooms::where('state',0)->orderBy('order','ASC')->get(),
						                    'sizes'     => \App\SizeRooms::all(),
						                    'types'     => \App\TypeApto::all(),
						                    'tipos'     => \App\TypeApto::all(),
						                    'owners'    => \App\User::all(),
						                    'show'      => 1,
										]);
	});
	
	Route::get('/admin/rooms/search/searchByName', 'RoomsController@searchByName');
	
	Route::get('/admin/rooms/getUpdateForm', 'RoomsController@getUpdateForm');

	Route::get('/admin/paymentspro/delete/{id}', function($id){

		if ( \App\PaymentsPro::find($id)->delete() ) {
			return 'ok';
		}else{
			return 'error';
		}

	});

	Route::get('/admin/customer/delete/{id}', function($id){

		if (\App\Customers::find($id)->delete()) {
			return 'ok';
		}else{
			return 'error';
		}

	});

	Route::get('/admin/customer/change/phone/{id}/{phone}', function($id, $phone){
		$customer = \App\Customers::find($id);
		$customer->phone = $phone;
		if ($customer->save()) {
			return ['status' => 'success','title' => 'OK', 'response' => "Teléfono cambiado"];
		}else{
			return ['status' => 'danger','title' => 'Error', 'response' => "No se ha cambiado el teléfono"];
		}

	});

	

	Route::get('/sendImagesRoomEmail', 'RoomsController@sendImagesRoomEmail');
	
	

});
Route::group(['middleware' => 'authAdmin'], function () {

	


	
});

