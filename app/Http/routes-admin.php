<?php

Route::group(['middleware' => ['auth','role:admin|subadmin|recepcionista']], function () {
  
  Route::post('admin/sendEncuesta', 'BookController@sendEncuesta')->name('sendEncuesta');
  Route::get('admin/showFormEncuesta/{id?}', 'BookController@showFormEncuesta')->name('showFormEncuesta');
  
  Route::get('admin/get-SafetyBox', 'BookController@getSafetyBoxLst');
  Route::get('/admin/get-CustomersRequest', 'BookController@getCustomersRequestLst');
  Route::post('admin/hideCustomersRequest', 'BookController@hideCustomersRequest');
  Route::post('admin/saveCustomerRequest', 'BookController@saveCustomerRequest');
  Route::post('admin/getCustomersRequest', 'BookController@getCustomersRequest');
  Route::get('/admin/getCustomerRequestBook/{bID}', 'BookController@getCustomersRequest_book');
  Route::post('admin/removeAlertPax', 'BookController@removeAlertPax');
  
  Route::get('/admin/get-books-without-cvc', 'BookController@getBooksWithoutCvc');
 
  //informes
  Route::get('admin/sales/{year?}','InformesController@sales_index');
  Route::post('admin/salesLst/','InformesController@get_sales_list');
  
  //bloqueo automático
  Route::get('/admin/multiple-room-lock', 'BookController@multipleRoomLock_print');
  Route::post('/admin/multiple-room-lock', 'BookController@multipleRoomLock_send');
  Route::post('/admin/multiple-room-lock-task', 'BookController@multipleRoomLock_tasks');
});

Route::group(['middleware' => ['auth','role:admin|limpieza|subadmin|recepcionista|conserje']], function () {
  
  // CALCULAR RESERVAS
  Route::get('/admin/reservas/help/calculateBook','AppController@calculateBook');
  Route::post('/admin/reservas/help/calculateBook','AppController@calculateBook');
  
  //LIMPIEZA
  Route::post('admin/limpieza/bloquear', 'LimpiezaController@bloqueos');
  Route::get('admin/limpieza/delete-block/{id}', 'LimpiezaController@bloqueos_delete');
  Route::get('admin/limpiezas/{year?}','LimpiezaController@limpiezas');
  Route::post('admin/limpiezasLst/','LimpiezaController@get_limpiezas');
  Route::post('admin/limpiezasUpd/','LimpiezaController@upd_limpiezas');
  Route::post('admin/limpiezas/pdf','LimpiezaController@export_pdf_limpiezas');
  Route::post('admin/limpiezasUpd/','LimpiezaController@upd_limpiezas');
  Route::get('admin/limpieza', 'LimpiezaController@index');
  
  //BUZON  
  Route::get('/ajax/showSafetyBox/{bookID}', 'BookController@showSafetyBox');
  Route::get('/ajax/editSafetyBox', 'BookController@editSafetyBox');
  Route::get('/ajax/updSafetyBox/{bookID}/{value}/{min?}', 'BookController@updSafetyBox');
  Route::get('/ajax/SafetyBoxMsg/{bookID}', 'BookController@getSafetyBoxMsg');
  Route::post('/ajax/send-SafetyBox-sms', 'BookController@sendSafetyBoxSMS');
  Route::post('/ajax/createSafetyBox', 'BookController@createSafetyBox');
  Route::post('/ajax/SafetyBox-updKey', 'BookController@updKeySafetyBox');
  Route::post('/ajax/send-SafetyBox-mail', 'BookController@sendSafetyBoxMail');
  Route::get('admin/get-SafetyBox', 'BookController@getSafetyBoxLst');
  
  Route::post('/ajax/toggleCliHas', 'BookController@toggleCliHas');
});

Route::group(['middleware' => ['auth','role:admin|limpieza|subadmin|recepcionista']], function () {
   //Estadísticas XML
  Route::post('admin/INE', 'INEController@sendData')->name('INE.sendEncuesta');
  Route::get('admin/show-INE', 'INEController@index');
  Route::post('admin/show-INE', 'INEController@showData');
  Route::get('admin/download-INE/{type}/{range}/{force}', 'INEController@download');
  
  Route::get('admin/createFianza/{id}', 'BookController@createFianza');
  //PARTEE
  Route::get('admin/sendPartee/{id}', 'BookController@sendPartee');
  Route::get('ajax/partee-checkHuespedes/{id}', 'BookController@seeParteeHuespedes')->name('partee.checkHuespedes');
  Route::get('ajax/partee-syncCheckInStatus', 'BookController@syncCheckInStatus')->name('partee.sinc');
  Route::get('admin/get-partee', 'BookController@getParteeLst');
  Route::get('/get-partee-msg', 'BookController@getParteeMsg');
  Route::post('/ajax/send-partee-finish', 'BookController@finishParteeCheckIn');
  Route::post('/ajax/send-partee-sms', 'BookController@finishParteeSMS');
  Route::post('/ajax/send-fianza-sms', 'BookController@sendFianzaSMS');
  Route::post('/ajax/send-partee-mail', 'BookController@finishParteeMail');
  Route::post('/ajax/send-fianza-mail', 'BookController@sendFianzaMail');
  Route::get('/ajax/showSendRemember/{bookID}', 'BookController@showSendRemember');
  Route::get('/ajax/showFianza/{bookID}', 'BookController@showFianza');
  
  // Route::get('/resume-by-book/{id}', 'ForfaitsItemController@getResumeBy_book');
  Route::get('/ajax/get-book-comm/{bookID}', 'BookController@getComment');
  

  
});
Route::group(['middleware' => ['auth','role:admin|propietario'], 'prefix' => 'admin',], function () {
  
  //Facturas
  Route::get('/facturas/ver/{id}', 'InvoicesController@view')->name('invoice.view');
  Route::get('/facturas/editar/{id}', 'InvoicesController@update')->name('invoice.edit');
  Route::post('/facturas/guardar', 'InvoicesController@save')->name('invoice.save');
  Route::post('/facturas/enviar', 'InvoicesController@sendMail')->name('invoice.sendmail');
  Route::get('/facturas/modal/editar/{id}', 'InvoicesController@update_modal');
  Route::post('/facturas/modal/guardar', 'InvoicesController@save_modal');
  Route::delete('/facturas/borrar', 'InvoicesController@delete')->name('invoice.delete');
  Route::get('/facturas/descargar/{id}', 'InvoicesController@download')->name('invoice.downl');
  Route::get('/facturas/descargar-todas', 'InvoicesController@downloadAll');
  Route::get('/facturas/descargar-todas/{year}/{id}', 'InvoicesController@downloadAllProp');
  Route::get('/facturas/solicitudes/{year?}', 'InvoicesController@solicitudes');
  Route::get('/facturas/{order?}', 'InvoicesController@index');
  
 //Propietario
  Route::get('/propietario/bloquear', 'OwnedController@bloqOwned');
  Route::get('/propietario/{name?}/operativa', 'OwnedController@operativaOwned');
  Route::get('/propietario/{name?}/tarifas', 'OwnedController@tarifasOwned');
  Route::get('/propietario/{name?}/descuentos', 'OwnedController@descuentosOwned');
  Route::get('/propietario/{name?}/fiscalidad', 'OwnedController@fiscalidadOwned');
  Route::get('/propietario/{name?}/facturas', 'OwnedController@facturasOwned');
//  Route::get('/propietario/facturas/ver/{id}', 'InvoicesController@viewProp')->name('invoice.prop.view');
  Route::get('/propietario/{name?}', 'OwnedController@index');
  Route::get('/propietario/create/password/{email}', 'UsersController@createPasswordUser');
  Route::post('/propietario/create/password/{email}', 'UsersController@createPasswordUser');
  
  Route::get('/propietario/contrato/sign/{file?}', 'RouterActionsController@getSign');
  Route::post('/propietario/contrato/sign', 'OwnedController@setSign')->name('contract.sign');
  Route::get('/propietario/contrato/downl/{id}', 'OwnedController@downlContract')->name('contract.downl');
  Route::get('/propietario/contrato/{id}', 'OwnedController@seeContracts')->name('contract.see');
  
});

/** Moved form routers */
Route::group(['middleware' => 'auth', 'prefix' => 'admin'], function () {
  Route::get('/rooms/api/getImagesRoom/{id?}/{bookId?}', 'RoomsController@getImagesRoom');
  Route::get('/reservas/api/getTableData', 'BookController@getTableData');
  Route::get('/reservas/new', 'BookController@newBook');
  Route::post('/reservas/new', 'BookController@newBook');
  Route::get('/apartamentos/getPaxPerRooms/{id}', 'RoomsController@getPaxPerRooms');
  Route::get('/apartamentos/getLuxuryPerRooms/{id}', 'RoomsController@getLuxuryPerRooms');
  Route::get('/api/reservas/getDataBook', 'BookController@getAllDataToBook');
  Route::get('/api/reservas/getRoomsCostProp/{id}', 'RoomsController@getRoomsCostProp');
  Route::get('/update/seasonsDays/{val}', 'RouterActionsController@seasonsDays');
  Route::get('/update/percentBenef/{val}', 'LiquidacionController@changePercentBenef');
  Route::post('/reservas/help/getTotalBook', 'BookController@getTotalBook');
  Route::get('/delete/nofify/{id}', 'RouterActionsController@nofify');
  Route::get('/reservas/changeSchedule/{id}/{type}/{schedule}', 'RouterActionsController@changeSchedule');
  Route::get('/reservas/restore/{id}/', 'RouterActionsController@restore');
  Route::get('/books/{idBook}/comments/{type}/save', 'BookController@saveComment');
  Route::get('/liquidation/searchByName', 'LiquidacionController@searchByName');
  Route::get('/liquidation/searchByRoom', 'LiquidacionController@searchByRoom');
  Route::get('/liquidation/orderByBenefCritico', 'LiquidacionController@orderByBenefCritico');
  Route::get('/apartamentos/rooms/getTableRooms/', 'RouterActionsController@getTableRooms');
  Route::get('/rooms/search/searchByName', 'RoomsController@searchByName');

  
  Route::get('/paymentspro/delete/{id}', 'RouterActionsController@paymentspro_del');

  Route::get('/customer/change/phone/{id}/{phone}','RouterActionsController@customer_change');
  
  Route::get('/sendImagesRoomEmail', 'RoomsController@sendImagesRoomEmail');
  Route::get('/books/getStripeLink/{book}/{importe}','RouterActionsController@books_getStripeLink');

  Route::get('/sales/updateLimpBook/{id}/{importe}','RouterActionsController@sales_updateLimpBook');
  Route::get('/sales/updateExtraCost/{id}/{importe}','RouterActionsController@sales_updateExtraCost');
  Route::get('/sales/updateCostApto/{id}/{importe}','RouterActionsController@sales_updateCostApto');
  Route::get('/sales/updateCostPark/{id}/{importe}','RouterActionsController@sales_updateCostPark');
  Route::get('/sales/updateCostTotal/{id}/{importe}','RouterActionsController@sales_updateCostTotal');
  Route::get('/sales/updatePVP/{id}/{importe}','RouterActionsController@sales_updatePVP');
  Route::post('/sales/updateBook','LiquidacionController@updateBook');
  
  Route::get('/invoices/searchByName/{searchString?}','RouterActionsController@invoices_searchByName');
  Route::get('/settings', 'SettingsController@index');
  Route::post('/settings-general', 'SettingsController@upd_general')->name('settings.gral.upd');
  Route::get('/settings_msgs/{lng?}/{key?}', 'SettingsController@messages')->name('settings.msgs');
  Route::post('/settings_msgs/{lng?}/{key?}', 'SettingsController@messages_upd')->name('settings.msgs.upd');
  Route::post('/specialSegments/create', 'SpecialSegmentController@create');
  Route::get('/specialSegments/update/{id?}', 'SpecialSegmentController@update');
  Route::post('/specialSegments/update/{id?}', 'SpecialSegmentController@update');
  Route::post('/specialSegments/delete/{id?}', 'SpecialSegmentController@delete');
  Route::get('/stripe-connect/{id}/acceptStripeConnect', 'StripeConnectController@acceptStripe');
  Route::get('/stripe-connect', 'StripeConnectController@index');
  Route::post('/stripe-connect/create-account-stripe-connect', 'StripeConnectController@createAccountStripeConnect');
  Route::post('/stripe-connect/load-transfer-form', 'StripeConnectController@loadTransferForm');
  Route::get('/stripe-connect/load-table-owneds', 'StripeConnectController@loadTableOwneds');
  Route::post('/stripe-connect/send-transfers', 'StripeConnectController@sendTransfers');
  //YEARS
  Route::post('/years/change', 'YearsController@changeActiveYear')->name('years.change');
  Route::post('/years/change/months', 'YearsController@changeMonthActiveYear')->name('years.change.month');
  Route::get('/years/get', 'YearsController@getYear')->name('years.get');
  //SETTINGS
  Route::post('/settings/createUpdate', 'SettingsController@createUpdateSetting')->name('settings.createUpdate');
  Route::post('settings/updExtraPaxPrice','SettingsController@updExtraPaxPrice')->name('settings.extr_pax_price');
  Route::post('settings/updIva','SettingsController@updIva')->name('settings.updIva');
  //PAYMENTS
//  Route::post('/', 'SettingsController@createUpdateSetting')->name('settings.createUpdate');
  Route::get('/links-payland', 'PaylandsController@link');
  Route::get('/links-payland-single', 'PaylandsController@linkSingle');
});


Route::group(['middleware' => ['auth','role:admin|subadmin|recepcionista']], function () {
//  Route::get('admin/revenue/getRateCheckWubook', 'RevenueController@getRateCheckWubook');
//  Route::get('admin/revenue/PICK-UP/{apto?}', 'RevenueController@pickUp')->name('revenue.pickUp');
//  Route::get('admin/revenue/PICK-UP/{apto?}/{range?}', 'RevenueController@pickUp');
//  Route::get('admin/revenue/DISPONIBLIDAD-x-ALOJAMIENTO/{apto?}/{range?}', 'RevenueController@disponibilidad')->name('revenue.disponibilidad');
//  Route::post('admin/revenue/descargar-disponibilidad', 'RevenueController@donwlDisponib')->name('revenue.donwlDisponib');
//  Route::post('admin/revenue/upd-disponibl', 'RevenueController@updDisponib');
//  Route::get('admin/revenue/PICK-UP-new/{month?}', 'RevenueController@pickUpNew')->name('revenue.pickUpNew');
//  Route::get('admin/revenue/RATE-SHOPPER', 'RevenueController@rate_shopper')->name('revenue.rate');
//  Route::get('admin/revenue/RATE-SHOPPER/generate', 'RevenueController@setRateCheckWubook');
//  Route::get('admin/revenue/DASHBOARD', 'RevenueController@index')->name('revenue');
//  Route::post('admin/revenue/generate', 'RevenueController@generate')->name('revenue.generate');
//  Route::post('admin/revenue/generatePickUp', 'RevenueController@generatePickUp')->name('revenue.generatePickUp');
//  Route::post('admin/revenue/donwlPickUp', 'RevenueController@donwlPickUp')->name('revenue.donwlPickUp');
//  Route::post('admin/revenue/PickUp/update', 'RevenueController@updPickUp')->name('revenue.updPickUp');
//  Route::get('admin/revenue/VENTAS-POR-DIA', 'RevenueController@daily')->name('revenue.daily');
//  Route::post('admin/revenue/donwlVtasDia', 'RevenueController@donwlDaily')->name('revenue.donwlVtasDia');
  
  
  
  Route::get('admin/revenue/getRateCheckWubook', 'RevenueController@getRateCheckWubook');
  Route::get('admin/revenue/DISPONIBLIDAD-x-ALOJAMIENTO/{apto?}/{site?}/{range?}', 'RevenueController@disponibilidad')->name('revenue.disponibilidad');
  Route::post('admin/revenue/descargar-disponibilidad', 'RevenueController@donwlDisponib')->name('revenue.donwlDisponib');
  Route::post('admin/revenue/upd-disponibl', 'RevenueController@updDisponib');
  Route::get('admin/revenue/PICK-UP/{month?}', 'RevenueController@pickUp')->name('revenue.pickUp');
  Route::get('admin/revenue/RATE-SHOPPER', 'RevenueController@rate_shopper')->name('revenue.rate');
  Route::get('admin/revenue/VENTAS-POR-DIA', 'RevenueController@daily')->name('revenue.daily');
  Route::post('admin/revenue/donwlVtasDia', 'RevenueController@donwlDaily')->name('revenue.donwlVtasDia');
  Route::get('admin/revenue/RATE-SHOPPER/generate', 'RevenueController@setRateCheckWubook');
  Route::get('admin/revenue/DASHBOARD', 'RevenueController@index')->name('revenue');
  Route::post('admin/revenue/generate', 'RevenueController@generate')->name('revenue.generate');
  Route::post('admin/revenue/generatePickUp', 'RevenueController@generatePickUp')->name('revenue.generatePickUp');
  Route::post('admin/revenue/donwlPickUp', 'RevenueController@donwlPickUp')->name('revenue.donwlPickUp');
  Route::post('admin/revenue/PickUp/update', 'RevenueController@updPickUp')->name('revenue.updPickUp');
  Route::get('admin/revenue/getMonthKPI/{mes}', 'RevenueController@getMonthKPI');
  Route::get('admin/revenue/getMonthDisp/{mes}', 'RevenueController@getMonthDisp');
  Route::get('admin/revenue/getOverview/{mes}', 'RevenueController@getOverview');
  Route::post('admin/revenue/upd-Overview', 'RevenueController@updOverview');
  Route::post('admin/revenue/upd-fixedcosts', 'RevenueController@updFixedcosts');
  Route::get('admin/revenue/getComparativaAnual/{year}', 'RevenueController@getComparativaAnual');
  Route::get('admin/revenue/getFixedcostsAnual/{year}', 'RevenueController@getFixedcostsAnual');
  Route::post('admin/revenue/copyFixedcostsAnualTo/{year}', 'RevenueController@copyFixedcostsAnualTo');
  
  
  
  
  
  
  
  
  
  
  
  
});

Route::get('test-text/{lng}/{key?}/{ota?}', 'SettingsController@testText');