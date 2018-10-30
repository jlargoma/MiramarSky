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


	/*ICalendar links*/
	Route::post( '/ical/import/saveUrl' , 'ICalendarController@saveUrl' );
	Route::get( '/ical/urls/deleteUrl' , 'ICalendarController@deleteUrl' );
	Route::get( '/ical/urls/getAllUrl/{aptoID}' , 'ICalendarController@getAllUrl' );
	Route::get( '/ical/{aptoID}' , ['as' => 'import-iCalendar' , 'uses' => 'ICalendarController@getIcalendar'] )->where( 'aptoID' , '[0-9]+' );
	Route::get( '/ical/importFromUrl' , function () {
		\Artisan::call( 'ical:import' );

		return redirect( '/admin/reservas' );
	} );

	Route::auth();
	Route::get( '/' , 'HomeController@index' )->middleware( 'web' );
   Route::get( '/homeTest' , 'HomeTestController@index' )->middleware( 'web' );
   Route::get( '/homeTest' , 'HomeTestController@index' )->middleware( 'web' );
	Route::get( '/sitemap' , 'HomeController@siteMap' )->middleware( 'web' );
	Route::get( '/apartamentos/galeria/{apto}' , 'HomeController@galeriaApartamento' )->middleware( 'web' );
	Route::get( '/apartamentos/{apto}' , 'HomeController@apartamento' )->middleware( 'web' );
	Route::get( '/fotos/{apto}' , 'HomeController@apartamento' )->middleware( 'web' );
	Route::get( '/edificio-miramarski-sierra-nevada' , 'HomeController@edificio' )->middleware( 'web' );
	Route::get( '/contacto' , 'HomeController@contacto' )->middleware( 'web' );

	/*Correos Frontend */
	Route::post( '/contacto-form' , 'HomeController@formContacto' );
	Route::post( '/contacto-ayuda' , 'HomeController@formAyuda' );
	Route::post( '/contacto-propietario' , 'HomeController@formPropietario' );
	Route::post( '/contacto-grupos' , 'HomeController@formGrupos' );

	/* Correos Frontend */
	Route::get( '/terminos-condiciones' , 'HomeController@terminos' );
	Route::get( '/politica-cookies' , 'HomeController@politicaCookies' );
	Route::get( '/politica-privacidad' , 'HomeController@politicaPrivacidad' );
	Route::get( '/condiciones-generales' , 'HomeController@condicionesGenerales' );
	Route::get( '/preguntas-frecuentes' , 'HomeController@preguntasFrecuentes' );
	Route::get( '/eres-propietario' , 'HomeController@eresPropietario' );
	Route::get( '/grupos' , 'HomeController@grupos' );
	Route::get( '/quienes-somos' , 'HomeController@quienesSomos' );
	Route::get( '/ayudanos-a-mejorar' , 'HomeController@ayudanosAMejorar' );
	Route::get( '/aviso-legal' , 'HomeController@avisoLegal' );
	Route::get( '/huesped' , 'HomeController@huesped' );
	Route::get( '/el-tiempo' , 'HomeController@tiempo' );
	Route::get( '/condiciones-contratacion' , 'HomeController@condicionesContratacion' );


	Route::get( '/restaurantes' , function () {

		$mobile = new \App\Classes\Mobile();
		return view( 'frontend.restaurantes' , ['mobile' => $mobile] );
	} );
	Route::post( '/getDiffIndays' , 'HomeController@getDiffIndays' );

	Route::post( '/solicitudForfait' , 'HomeController@solicitudForfait' );

	Route::get( '/admin/links-stripe' , 'StripeController@link' );

	/* SUPERMERCADO */
	Route::get( '/supermercado' , function () {
		return redirect( 'http://miramarski.com/supermercado' );
	} );
	/* FIN SUPERMERCADO */

	/* ENCUESTAS */
	Route::get( '/encuesta-satisfaccion/{id}' , 'QuestionsController@index' );
	Route::post( '/questions/vote' , 'QuestionsController@vote' );


	/* FIN ENCUESTAS*/
	/* CRONTABS */
	Route::get( '/admin/reservas/api/checkSecondPay' , 'BookController@checkSecondPay' );


	/* Planing */
	Route::post( '/getPriceBook' , 'HomeController@getPriceBook' );
	Route::get( '/getFormBook' , 'HomeController@form' );
	Route::get( '/getCitiesByCountry' , 'HomeController@getCitiesByCountry' );
	Route::get( '/getCalendarMobile' , 'BookController@getCalendarMobileView' );
	Route::post( 'admin/reservas/create' , 'BookController@create' );

	Route::post( 'admin/reservas/stripe/save/fianza' , ['middleware' => 'auth' , 'uses' => 'StripeController@fianza'] );
	Route::post( 'admin/reservas/stripe/pay/fianza' , ['middleware' => 'auth' , 'uses' => 'StripeController@payFianza'] );

	Route::get( '/reservas/stripe/pagos/{id_book}' , 'StripeController@stripePayment' );
	Route::post( '/reservas/stripe/payment/' , 'StripeController@stripePaymentResponse' );
	Route::post( '/admin/reservas/stripe/paymentsBooking' , 'StripeController@stripePaymentBooking' );

	Route::get( 'admin/reservas' , ['middleware' => 'authSubAdmin' , 'uses' => 'BookController@index'] );
	Route::get( 'admin/reservas/emails/{id}' , ['middleware' => 'authSubAdmin' , 'uses' => 'BookController@emails'] );
	Route::get( 'admin/reservas/new' , ['middleware' => 'auth' , 'uses' => 'BookController@newBook'] );
	Route::get( 'admin/reservas/delete/{id}' , ['middleware' => 'auth' , 'uses' => 'BookController@delete'] );
	Route::get( 'admin/reservas/update/{id}' , ['middleware' => 'auth' , 'uses' => 'BookController@update'] );
	Route::post( 'admin/reservas/saveUpdate/{id}' , ['middleware' => 'auth' , 'uses' => 'BookController@saveUpdate'] );
	Route::get( 'admin/reservas/changeBook/{id}' , ['middleware' => 'auth' , 'uses' => 'BookController@changeBook'] );
	Route::get( '/admin/reservas/changeStatusBook/{id}' , ['middleware' => 'auth' , 'uses' => 'BookController@changeStatusBook'] );
	Route::get( 'admin/reservas/ansbyemail/{id}' , ['middleware' => 'auth' , 'uses' => 'BookController@ansbyemail'] );
	Route::post( 'admin/reservas/sendEmail' , ['middleware' => 'auth' , 'uses' => 'BookController@sendEmail'] );
	Route::get( 'admin/reservas/sendJaime' , ['middleware' => 'authSubAdmin' , 'uses' => 'BookController@sendJaime'] );
	Route::get( 'admin/reservas/saveCobro' , ['middleware' => 'authSubAdmin' , 'uses' => 'BookController@saveCobro'] );
	Route::get( 'admin/reservas/deleteCobro/{id}' , ['middleware' => 'authSubAdmin' , 'uses' => 'BookController@deleteCobro'] );
	Route::get( 'admin/reservas/saveFianza' , ['middleware' => 'auth' , 'uses' => 'BookController@saveFianza'] );
	Route::get( 'admin/reservas/reserva/{id}' , ['middleware' => 'auth' , 'uses' => 'BookController@tabReserva'] );
	Route::get( 'admin/reservas/cobrar/{id}' , ['middleware' => 'auth' , 'uses' => 'BookController@cobroBook'] );
	Route::get( 'admin/reservas/{year?}' , ['middleware' => 'auth' , 'uses' => 'BookController@index'] );

	Route::get( 'admin/reservas/search/searchByName' , 'BookController@searchByName' );
	Route::get( 'admin/reservas/api/getTableData' , ['middleware' => 'auth' , 'uses' => 'BookController@getTableData'] );
	Route::get( 'admin/reservas/api/lastsBooks' , ['middleware' => 'auth' , 'uses' => 'BookController@getLastBooks'] );
	Route::get( 'admin/reservas/api/calendarBooking' , ['middleware' => 'auth' , 'uses' => 'BookController@getCalendarBooking'] );
	Route::get( 'admin/reservas/api/alertsBooking' , ['middleware' => 'auth' , 'uses' => 'BookController@getAlertsBooking'] );
	Route::get( 'admin/api/reservas/getDataBook' , ['middleware' => 'auth' , 'uses' => 'BookController@getAllDataToBook'] );
	Route::get( 'admin/reservas/api/sendSencondEmail' , ['middleware' => 'auth' , 'uses' => 'BookController@sendSencondEmail'] );
	Route::get( '/admin/reservas/fianzas/cobrar/{id}' , ['middleware' => 'auth' , 'uses' => 'BookController@cobrarFianzas'] );
	Route::get( 'admin/cambiarCostes' , 'BookController@changeCostes' );

// Usuarios
	Route::get( 'admin/usuarios' , ['middleware' => 'authAdmin' , 'uses' => 'UsersController@index'] );
	Route::get( 'admin/usuarios/update/{id}' , ['middleware' => 'authAdmin' , 'uses' => 'UsersController@update'] );
	Route::post( 'admin/usuarios/saveAjax' , ['middleware' => 'authAdmin' , 'uses' => 'UsersController@saveAjax'] );
	Route::post( 'admin/usuarios/saveupdate' , ['middleware' => 'authAdmin' , 'uses' => 'UsersController@saveUpdate'] );
	Route::post( 'admin/usuarios/create' , ['middleware' => 'authAdmin' , 'uses' => 'UsersController@create'] );
	Route::get( 'admin/usuarios/delete/{id}' , ['middleware' => 'authAdmin' , 'uses' => 'UsersController@delete'] );
	Route::post( 'admin/usuarios/search' , ['middleware' => 'authAdmin' , 'uses' => 'UsersController@searchUserByName'] );


// Clientes
	Route::get( 'admin/clientes' , ['middleware' => 'authAdmin' , 'uses' => 'CustomersController@index'] );
	Route::get( 'admin/clientes/update' , ['middleware' => 'auth' , 'uses' => 'CustomersController@update'] );
	Route::get( 'admin/clientes/save' , ['middleware' => 'auth' , 'uses' => 'CustomersController@save'] );
	Route::post( 'admin/clientes/create' , ['middleware' => 'auth' , 'uses' => 'CustomersController@create'] );
	Route::get( 'admin/clientes/export-excel' , ['middleware' => 'auth' , 'uses' => 'CustomersController@createExcel'] );
	Route::get( 'admin/customers/importExcelData' , ['middleware' => 'auth' , 'uses' => 'CustomersController@createExcel'] );
	Route::get( 'admin/clientes/delete/{id}' , ['middleware' => 'auth' , 'uses' => 'CustomersController@delete'] );

	Route::get( 'admin/cliente/dni/{idCustomer}/update/{dni}' , function ( $idCustomer , $dni ) {

		$customer      = \App\Customers::find( $idCustomer );
		$customer->DNI = $dni;

		if ( $customer->save() ) {
			return "OK";
		}

	} );

// Rooms
	Route::get( 'admin/apartamentos' , ['middleware' => 'auth' , 'uses' => 'RoomsController@index'] );
	Route::get( 'admin/apartamentos/new' , ['middleware' => 'auth' , 'uses' => 'RoomsController@newRoom'] );
	Route::get( 'admin/apartamentos/new-type' , ['middleware' => 'auth' , 'uses' => 'RoomsController@newTypeRoom'] );
	Route::get( 'admin/apartamentos/new-size' , ['middleware' => 'auth' , 'uses' => 'RoomsController@newSizeRoom'] );
	Route::get( 'admin/apartamentos/update' , ['middleware' => 'auth' , 'uses' => 'RoomsController@update'] );
	Route::get( 'admin/apartamentos/update-type' , ['middleware' => 'auth' , 'uses' => 'RoomsController@updateType'] );
	Route::get( 'admin/apartamentos/update-name' , ['middleware' => 'auth' , 'uses' => 'RoomsController@updateName'] );
	Route::get( 'admin/apartamentos/update-nameRoom' , ['middleware' => 'auth' , 'uses' => 'RoomsController@updateNameRoom'] );
	Route::get( 'admin/apartamentos/update-order' , ['middleware' => 'auth' , 'uses' => 'RoomsController@updateOrder'] );
	Route::get( 'admin/apartamentos/update-size' , ['middleware' => 'auth' , 'uses' => 'RoomsController@updateSize'] );
	Route::get( 'admin/apartamentos/update-owned' , ['middleware' => 'auth' , 'uses' => 'RoomsController@updateOwned'] );
	Route::get( 'admin/apartamentos/update-parking' , ['middleware' => 'auth' , 'uses' => 'RoomsController@updateParking'] );
	Route::get( 'admin/apartamentos/update-taquilla' , ['middleware' => 'auth' , 'uses' => 'RoomsController@updateTaquilla'] );
	Route::post( 'admin/apartamentos/saveupdate' , ['middleware' => 'auth' , 'uses' => 'RoomsController@saveUpdate'] );
	Route::post( 'admin/apartamentos/create' , ['middleware' => 'auth' , 'uses' => 'RoomsController@create'] );
	Route::post( 'admin/apartamentos/create-type' , ['middleware' => 'auth' , 'uses' => 'RoomsController@createType'] );
	Route::post( 'admin/apartamentos/create-size' , ['middleware' => 'auth' , 'uses' => 'RoomsController@createSize'] );
	Route::get( 'admin/apartamentos/state' , ['middleware' => 'auth' , 'uses' => 'RoomsController@state'] );
	Route::get( 'admin/apartamentos/percentApto' , ['middleware' => 'auth' , 'uses' => 'RoomsController@percentApto'] );
	Route::get( 'admin/apartamentos/update-Percent' , ['middleware' => 'auth' , 'uses' => 'RoomsController@updatePercent'] );
	Route::get( 'admin/apartamentos/email/{id}' , ['middleware' => 'auth' , 'uses' => 'RoomsController@email'] );
	Route::get( 'admin/apartamentos/fotos/{id}' , ['middleware' => 'auth' , 'uses' => 'RoomsController@photo'] );
	Route::get( 'admin/apartamentos/deletePhoto/{id}' , ['middleware' => 'auth' , 'uses' => 'RoomsController@deletePhoto'] );
	Route::post( 'admin/apartamentos/send/email/owned' , ['middleware' => 'auth' , 'uses' => 'RoomsController@sendEmailToOwned'] );
	Route::get( 'admin/apartamentos/getPaxPerRooms/{id}' , 'RoomsController@getPaxPerRooms' );
	Route::get( 'admin/apartamentos/getLuxuryPerRooms/{id}' , 'RoomsController@getLuxuryPerRooms' );
	Route::post( 'admin/apartamentos/uploadFile/{id}' , ['middleware' => 'auth' , 'uses' => 'RoomsController@uploadFile'] );
	Route::get( 'admin/apartamentos/assingToBooking' , ['middleware' => 'auth' , 'uses' => 'RoomsController@assingToBooking'] );
	Route::get( 'admin/apartamentos/download/contrato/{userId}' , ['middleware' => 'auth' , 'uses' => 'RoomsController@downloadContractoUser'] );


// Prices
	Route::get( 'admin/precios' , ['middleware' => 'authAdmin' , 'uses' => 'PricesController@index'] );
	Route::get( 'admin/precios/update' , ['middleware' => 'authAdmin' , 'uses' => 'PricesController@update'] );
	Route::get( 'admin/precios/updateExtra' , ['middleware' => 'authAdmin' , 'uses' => 'PricesController@updateExtra'] );
	Route::post( 'admin/precios/create' , ['middleware' => 'authAdmin' , 'uses' => 'PricesController@create'] );
	Route::get( 'admin/precios/delete/{id}' , ['middleware' => 'authAdmin' , 'uses' => 'PricesController@delete'] );
	Route::get( 'admin/precios/deleteExtra/{id}' , ['middleware' => 'authAdmin' , 'uses' => 'PricesController@delete'] );
	Route::post( 'admin/precios/createExtras' , ['middleware' => 'authAdmin' , 'uses' => 'PricesController@createExtras'] );

// seasons
	Route::get( 'admin/temporadas' , ['middleware' => 'authAdmin' , 'uses' => 'SeasonsController@index'] );
	Route::get( 'admin/temporadas/new' , ['middleware' => 'authAdmin' , 'uses' => 'SeasonsController@newSeasons'] );
	Route::get( 'admin/temporadas/new-type' , ['middleware' => 'authAdmin' , 'uses' => 'SeasonsController@newTypeSeasons'] );
	Route::get( 'admin/temporadas/update/{}' , ['middleware' => 'authAdmin' , 'uses' => 'SeasonsController@update'] );
	Route::post( 'admin/temporadas/update/{}' , ['middleware' => 'authAdmin' , 'uses' => 'SeasonsController@update'] );
	Route::post( 'admin/temporadas/saveupdate' , ['middleware' => 'authAdmin' , 'uses' => 'SeasonsController@saveUpdate'] );
	Route::post( 'admin/temporadas/create' , ['middleware' => 'authAdmin' , 'uses' => 'SeasonsController@create'] );
	Route::post( 'admin/temporadas/create-type' , ['middleware' => 'authAdmin' , 'uses' => 'SeasonsController@createType'] );
	Route::get( 'admin/temporadas/delete/{id}' , ['middleware' => 'authAdmin' , 'uses' => 'SeasonsController@delete'] );

// Pagos
	Route::get( 'admin/pagos' , ['middleware' => 'authAdmin' , 'uses' => 'PaymentsController@index'] );
	Route::get( 'admin/pagos/create' , ['middleware' => 'authAdmin' , 'uses' => 'PaymentsController@create'] );
	Route::get( 'admin/pagos/update' , ['middleware' => 'authAdmin' , 'uses' => 'PaymentsController@update'] );

// Pagos-Propietarios
	Route::get( 'admin/pagos-propietarios/{month?}' , ['middleware' => 'authAdmin' , 'uses' => 'PaymentsProController@index'] );
	Route::post( 'admin/pagos-propietarios/create' , ['middleware' => 'authAdmin' , 'uses' => 'PaymentsProController@create'] );
	Route::get( 'admin/pagos-propietarios/update/{id}/{month?}' , ['middleware' => 'authAdmin' , 'uses' => 'PaymentsProController@update'] );
	Route::get( 'admin/paymentspro/getBooksByRoom/{idRoom}' , 'PaymentsProController@getBooksByRoom' );
	Route::get( 'admin/paymentspro/getLiquidationByRoom' , 'PaymentsProController@getLiquidationByRoom' );


//Liquidacion
	Route::get( 'admin/liquidacion/{year?}' , ['middleware' => 'authAdmin' , 'uses' => 'LiquidacionController@index'] );
	Route::get( 'admin/liquidacion-apartamentos/{year?}' , ['middleware' => 'authAdmin' , 'uses' => 'LiquidacionController@apto'] );
	Route::get( 'admin/liquidacion/export/excel' , ['middleware' => 'authAdmin' , 'uses' => 'LiquidacionController@exportExcel'] );

	Route::get( 'admin/gastos/{year?}' , ['middleware' => 'authAdmin' , 'uses' => 'LiquidacionController@gastos'] );
	Route::post( 'admin/gastos/create' , ['middleware' => 'authAdmin' , 'uses' => 'LiquidacionController@gastoCreate'] );
	Route::get( '/admin/gastos/getTableGastos/{year?}' , ['middleware' => 'authAdmin' , 'uses' => 'LiquidacionController@getTableGastos'] );

	Route::get( 'admin/gastos/update/{id}' , ['middleware' => 'authAdmin' , 'uses' => 'LiquidacionController@updateGasto'] );
	Route::get( '/admin/gastos/getHojaGastosByRoom/{year?}/{id}' , ['middleware' => 'authAdmin' , 'uses' => 'LiquidacionController@getHojaGastosByRoom'] );
	Route::get( '/admin/gastos/containerTableExpensesByRoom/{year?}/{id}' , 'LiquidacionController@getTableExpensesByRoom' );


	Route::get( '/admin/gastos/delete/{id}' , function ( $id ) {

		if ( \App\Expenses::find( $id )->delete() ) {
			return 'ok';
		} else {
			return 'error';
		}

	} );
	Route::get( 'admin/ingresos/{year?}' , ['middleware' => 'authAdmin' , 'uses' => 'LiquidacionController@ingresos'] );
	Route::post( 'admin/ingresos/create' , ['middleware' => 'authAdmin' , 'uses' => 'LiquidacionController@ingresosCreate'] );
	Route::get( '/admin/ingresos/delete/{id}' , function ( $id ) {

		if ( \App\Incomes::find( $id )->delete() ) {
			return 'ok';
		} else {
			return 'error';
		}

	} );

	Route::get( 'admin/caja/{year?}' , ['middleware' => 'authAdmin' , 'uses' => 'LiquidacionController@caja'] );
	Route::get( 'admin/caja/getTableMoves/{year?}/{type}' , ['middleware' => 'authAdmin' , 'uses' => 'LiquidacionController@getTableMoves'] );
	Route::post( 'admin/cashBox/create' , ['middleware' => 'authAdmin' , 'uses' => 'LiquidacionController@cashBoxCreate'] );

	Route::get( 'admin/cashbox/updateSaldoInicial/{id}/{type}/{importe}' , function ( $id , $type , $importe ) {
		$cashbox         = \App\Cashbox::find( $id );
		$cashbox->import = $importe;
		if ( $cashbox->save() ) {
			return "OK";
		}

	} );

	Route::get( 'admin/banco/{year?}' , ['middleware' => 'authAdmin' , 'uses' => 'LiquidacionController@bank'] );
	Route::get( 'admin/banco/getTableMoves/{year?}/{type}' , ['middleware' => 'authAdmin' , 'uses' => 'LiquidacionController@getTableMovesBank'] );
	Route::get( 'admin/bank/updateSaldoInicial/{id}/{type}/{importe}' , function ( $id , $type , $importe ) {
		$cashbox         = \App\Cashbox::find( $id );
		$cashbox->import = $importe;
		if ( $cashbox->save() ) {
			return "OK";
		}

	} );

	Route::get( '/admin/rules/stripe/update' , ['middleware' => 'authAdmin' , 'uses' => 'RulesStripeController@update'] );
	Route::get( '/admin/days/secondPay/update/{id}/{days}' , function ( $id , $days ) {
		$day       = \App\DaysSecondPay::find( $id );
		$day->days = $days;
		$day->save();
	} );


	Route::get( 'admin/estadisticas/{year?}' , ['middleware' => 'authAdmin' , 'uses' => 'LiquidacionController@Statistics'] );
	Route::get( 'admin/contabilidad/{year?}' , ['middleware' => 'authAdmin' , 'uses' => 'LiquidacionController@contabilidad'] );
	Route::get( 'admin/perdidas-ganancias/{year?}' , ['middleware' => 'authAdmin' , 'uses' => 'LiquidacionController@perdidasGanancias'] );


//Facturas


	Route::get( 'admin/facturas/' , ['middleware' => 'authAdmin' , 'uses' => 'InvoicesController@index'] );
	Route::get( 'admin/facturas/ver/{id}' , ['middleware' => 'auth' , 'uses' => 'InvoicesController@view'] );
	Route::get( 'admin/facturas/descargar/{id}' , ['middleware' => 'auth' , 'uses' => 'InvoicesController@download'] );
	Route::get( 'admin/facturas/descargar-todas' , ['middleware' => 'auth' , 'uses' => 'InvoicesController@downloadAll'] );
	Route::get( 'admin/facturas/descargar-todas/{year}/{id}' , ['middleware' => 'auth' , 'uses' => 'InvoicesController@downloadAllProp'] );

	Route::get( 'admin/facturas/solicitudes/{year?}' , ['middleware' => 'auth' , 'uses' => 'InvoicesController@solicitudes'] );
	Route::get( 'admin/facturas/isde/create' , ['middleware' => 'auth' , 'uses' => 'InvoicesController@createIsde'] );
	Route::post( 'admin/facturas/isde/create' , ['middleware' => 'auth' , 'uses' => 'InvoicesController@createIsde'] );

	Route::get( 'admin/facturas/isde/{year?}' , ['middleware' => 'auth' , 'uses' => 'InvoicesController@isde'] );
	Route::get( 'admin/facturas/isde/ver/{id}' , ['middleware' => 'auth' , 'uses' => 'InvoicesController@viewIsde'] );
	Route::get( 'admin/facturas/isde/descargar/{id}' , ['middleware' => 'auth' , 'uses' => 'InvoicesController@downloadIsde'] );
	Route::get( 'admin/facturas/isde/delete/{id}' , ['middleware' => 'auth' , 'uses' => 'InvoicesController@deleteIsde'] );
	Route::get( 'admin/facturas/isde/editar/{id}' , ['middleware' => 'auth' , 'uses' => 'InvoicesController@updateIsde'] );
	Route::post( 'admin/facturas/isde/saveUpdate' , ['middleware' => 'auth' , 'uses' => 'InvoicesController@saveUpdate'] );

//Facturas
	Route::get( 'admin/encuestas/{year?}/{apto?}' , ['middleware' => 'authAdmin' , 'uses' => 'QuestionsController@admin'] );

//Propietario
	Route::get( 'admin/propietario/bloquear' , 'OwnedController@bloqOwned' );
	Route::get( 'admin/propietario/{name?}/operativa' , 'OwnedController@operativaOwned' );
	Route::get( 'admin/propietario/{name?}/tarifas' , 'OwnedController@tarifasOwned' );
	Route::get( 'admin/propietario/{name?}/descuentos' , 'OwnedController@descuentosOwned' );
	Route::get( 'admin/propietario/{name?}/fiscalidad' , 'OwnedController@fiscalidadOwned' );
	Route::get( 'admin/propietario/{name?}/facturas' , 'OwnedController@facturasOwned' );
	Route::get( 'admin/propietario/{name?}/{year?}' , 'OwnedController@index' );


	Route::get( 'admin/propietario/create/password/{email}' , 'UsersController@createPasswordUser' );
	Route::post( 'admin/propietario/create/password/{email}' , 'UsersController@createPasswordUser' );

// AUX PROPIETARIOS 
	Route::get( 'admin/propietarios/dashboard/{name?}/{year?}' , ['middleware' => 'authAdmin' , 'uses' => 'OwnedController@index'] );

//PDF´s

	Route::get( 'admin/pdf/pdf-reserva/{id}' , 'PdfController@invoice' );
	Route::get( 'admin/pdf/descarga-excel-propietario/{id}' , 'PdfController@pdfPropietario' );

	Route::group( ['middleware' => 'auth'] , function () {
		Route::get( '/admin/rooms/api/getImagesRoom/{id?}/{bookId?}' , 'RoomsController@getImagesRoom' );

		Route::get( '/admin' , function () {
			$user = \Auth::user();
			if ( $user->role == "propietario" ) {
				$room = \App\Rooms::where( 'owned' , $user->id )->first();
				return redirect( 'admin/propietario/' . $room->nameRoom );
			} else {
				return redirect( 'admin/reservas' );
			}


		} );

		Route::get( 'admin/reservas/help/calculateBook' , function () {
			return view( 'backend.planning._calculateBook' );
		} );

		Route::get( 'admin/update/seasonsDays/{val}' , function ( $val ) {

			$seasonDays          = \App\SeasonDays::first();
			$seasonDays->numDays = $val;

			if ( $seasonDays->save() ) {
				return "Cambiado";
			}

		} );

		Route::get( 'admin/update/percentBenef/{val}' , 'LiquidacionController@changePercentBenef' );

		Route::post( 'admin/reservas/help/getTotalBook' , 'BookController@getTotalBook' );

		Route::get( 'admin/delete/nofify/{id}' , function ( $id ) {
			$notify = \App\BookNotification::find( $id );

			if ( $notify->delete() ) {
				return view( 'backend.planning._tableAlertBooking' , ['notifications' => \App\BookNotification::all()] );
			}
		} );

		Route::get( 'admin/reservas/changeSchedule/{id}/{type}/{schedule}' , function ( $id , $type , $schedule ) {


			$book = \App\Book::find( $id );
			if ( $type == 1 ) {
				$book->schedule = $schedule;
			} else {
				$book->scheduleOut = $schedule;
			}

			if ( $book->save() ) {
				return ['status' => 'success' , 'title' => 'OK' , 'response' => "Hora actualizada"];
			}

		} );
		Route::get( 'admin/reservas/restore/{id}/' , function ( $id ) {


			$book            = \App\Book::find( $id );
			$book->type_book = 3;
			if ( $book->save() ) {
				return ['status' => 'success' , 'title' => 'OK' , 'response' => "Reserva restaurada"];
			}

		} );

		Route::get( '/admin/books/{idBook}/comments/{type}/save' , 'BookController@saveComment' );


		Route::get( 'admin/liquidation/searchByName' , 'LiquidacionController@searchByName' );
		Route::get( 'admin/liquidation/searchByRoom' , 'LiquidacionController@searchByRoom' );
		Route::get( 'admin/liquidation/orderByBenefCritico' , 'LiquidacionController@orderByBenefCritico' );


		Route::get( '/admin/apartamentos/rooms/getTableRooms/' , function () {

			return view( 'backend.rooms._tableRooms' , ['rooms' => \App\Rooms::where( 'state' , 1 )->orderBy( 'order' , 'ASC' )->get() , 'roomsdesc' => \App\Rooms::where( 'state' , 0 )->orderBy( 'order' , 'ASC' )->get() , 'sizes' => \App\SizeRooms::all() , 'types' => \App\TypeApto::all() , 'tipos' => \App\TypeApto::all() , 'owners' => \App\User::all() , 'show' => 1 ,] );
		} );

		Route::get( '/admin/rooms/search/searchByName' , 'RoomsController@searchByName' );
		Route::get( '/admin/rooms/getUpdateForm' , 'RoomsController@getUpdateForm' );
		Route::get( '/admin/paymentspro/delete/{id}' , function ( $id ) {

			if ( \App\Paymentspro::find( $id )->delete() ) {
				return 'ok';
			} else {
				return 'error';
			}

		} );

		Route::get( '/admin/customer/delete/{id}' , function ( $id ) {

			if ( \App\Customers::find( $id )->delete() ) {
				return 'ok';
			} else {
				return 'error';
			}

		} );

		Route::get( '/admin/customer/change/phone/{id}/{phone}' , function ( $id , $phone ) {
			$customer        = \App\Customers::find( $id );
			$customer->phone = $phone;
			if ( $customer->save() ) {
				return ['status' => 'success' , 'title' => 'OK' , 'response' => "Teléfono cambiado"];
			} else {
				return ['status' => 'danger' , 'title' => 'Error' , 'response' => "No se ha cambiado el teléfono"];
			}

		} );

		Route::get( '/sendImagesRoomEmail' , 'RoomsController@sendImagesRoomEmail' );


		Route::get( '/admin/books/getStripeLink/{book}/{importe}' , function ( $book , $importe ) {
			$book   = \App\Book::find( $book );
			$import = $importe;

			return view( 'backend.planning._links' , ['book' => $book , 'import' => $import ,] );

		} );

		Route::get( '/admin/sales/updateLimpBook/{id}/{importe}' , function ( $id , $importe ) {
			$book            = \App\Book::find( $id );
			$book->cost_limp = $importe;
			if ( $book->save() ) {
				return "OK";
			}


		} );

		Route::get( '/admin/sales/updateExtraCost/{id}/{importe}' , function ( $id , $importe ) {
			$book            = \App\Book::find( $id );
			$book->extraCost = $importe;
			if ( $book->save() ) {
				return "OK";
			}


		} );

		Route::get( '/admin/sales/updateCostApto/{id}/{importe}' , function ( $id , $importe ) {
			$book            = \App\Book::find( $id );
			$book->cost_apto = $importe;
			if ( $book->save() ) {
				return "OK";
			}


		} );

		Route::get( '/admin/sales/updateCostPark/{id}/{importe}' , function ( $id , $importe ) {
			$book            = \App\Book::find( $id );
			$book->cost_park = $importe;
			if ( $book->save() ) {
				return "OK";
			}


		} );

		Route::get( '/admin/sales/updateCostTotal/{id}/{importe}' , function ( $id , $importe ) {
			$book             = \App\Book::find( $id );
			$book->cost_total = $importe;
			if ( $book->save() ) {
				return "OK";
			}


		} );

		Route::get( '/admin/sales/updatePVP/{id}/{importe}' , function ( $id , $importe ) {
			$book              = \App\Book::find( $id );
			$book->total_price = $importe;
			if ( $book->save() ) {
				return "OK";
			}


		} );


		Route::get( '/admin/customers/searchByName/{searchString?}' , function ( $searchString = "" ) {

			if ( $searchString == "" ) {
				$arraycorreos    = array();
				$correosUsuarios = \App\User::all();

				foreach ( $correosUsuarios as $correos ) {
					$arraycorreos[] = $correos->email;

				}


				$arraycorreos[] = "iankurosaki@gmail.com";
				$arraycorreos[] = "jlargoma@gmail.com";
				$arraycorreos[] = "victorgerocuba@gmail.com";
				$customers      = \App\Customers::whereNotIn( 'email' , $arraycorreos )->where( 'email' , '!=' , ' ' )->distinct( 'email' )->orderBy( 'created_at' , 'DESC' )->get();
			} else {
				$customers = \App\Customers::where( 'name' , 'LIKE' , '%' . $searchString . '%' )->orWhere( 'email' , 'LIKE' , '%' . $searchString . '%' )->get();
			}

			return view( 'backend.customers._table' , ['customers' => $customers ,] );
		} );
		Route::get( '/admin/invoices/searchByName/{searchString?}' , function ( $searchString = "" ) {
			if ( $searchString == "" ) {
				$arraycorreos    = array();
				$correosUsuarios = \App\User::all();

				foreach ( $correosUsuarios as $correos ) {
					$arraycorreos[] = $correos->email;

				}


				$arraycorreos[] = "iankurosaki@gmail.com";
				$arraycorreos[] = "jlargoma@gmail.com";
				$arraycorreos[] = "victorgerocuba@gmail.com";
				$customers      = \App\Customers::whereNotIn( 'email' , $arraycorreos )->where( 'email' , '!=' , ' ' )->distinct( 'email' )->orderBy( 'created_at' , 'DESC' )->get();
			} else {
				$customers = \App\Customers::where( 'name' , 'LIKE' , '%' . $searchString . '%' )->orWhere( 'email' , 'LIKE' , '%' . $searchString . '%' )->get();
				echo "asdfasdf";
			}
			$arrayIdCustomers = array();
			foreach ( $customers as $customer ) {
				$arrayIdCustomers[] = $customer->id;
			}

			$books = \App\Book::where( 'type_book' , 2 )->whereIn( 'customer_id' , $arrayIdCustomers )->orderBy( 'start' , 'DESC' )->paginate( 25 );

			return view( 'backend.invoices._table' , ['books' => $books ,] );
		} );

		Route::get( '/admin/settings' , 'SettingsController@index' );
		Route::post( '/admin/agentRoom/create' , 'SettingsController@createAgentRoom' );
		Route::get( '/admin/agentRoom/delete/{id}' , 'SettingsController@deleteAgentRoom' );


		Route::post( '/admin/specialSegments/create' , 'SpecialSegmentController@create' );
		Route::get( '/admin/specialSegments/update/{id?}' , 'SpecialSegmentController@update' );
		Route::post( '/admin/specialSegments/update/{id?}' , 'SpecialSegmentController@update' );
		Route::get( '/admin/specialSegments/delete/{id?}' , 'SpecialSegmentController@delete' );

		Route::get( '/admin/stripe-connect/{id}/acceptStripeConnect' , 'StripeConnectController@acceptStripe' );

		Route::get( '/admin/stripe-connect' , 'StripeConnectController@index' );
		Route::post( '/admin/stripe-connect/create-account-stripe-connect' , 'StripeConnectController@createAccountStripeConnect' );
		Route::post( '/admin/stripe-connect/load-transfer-form' , 'StripeConnectController@loadTransferForm' );
		Route::get( '/admin/stripe-connect/load-table-owneds' , 'StripeConnectController@loadTableOwneds' );
		Route::post( '/admin/stripe-connect/send-transfers' , 'StripeConnectController@sendTransfers' );

	} );

	Route::get( '/importPaymenCashBank' , 'Admin\BackendController@migrationCashBank' );
	Route::get( '/insertDNIS' , 'Admin\BackendController@insertDNIS' );
	Route::get( '/refreshBloqueos' , 'Admin\BackendController@refreshBloqueos' );


