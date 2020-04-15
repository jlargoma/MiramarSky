<?php

Route::group(['middleware' => ['auth','role:admin|limpieza|subadmin|recepcionista']], function () {
  
  Route::get('admin/sendEncuesta/{id?}', 'BookController@sendEncuesta')->name('sendEncuesta');
    
  //LIMPIEZA
  Route::get('admin/limpieza', 'LimpiezaController@index');
  Route::get('admin/limpiezas/{year?}','LimpiezaController@limpiezas');
  Route::post('admin/limpiezasLst/','LimpiezaController@get_limpiezas');
  Route::post('admin/limpiezasUpd/','LimpiezaController@upd_limpiezas');
  Route::post('admin/limpiezas/pdf','LimpiezaController@export_pdf_limpiezas');
  Route::post('admin/limpiezasUpd/','LimpiezaController@upd_limpiezas');

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
  
  Route::get('/ajax/showSafetyBox/{bookID}', 'BookController@showSafetyBox');
  Route::get('/ajax/updSafetyBox/{bookID}/{value}/{min?}', 'BookController@updSafetyBox');
  Route::get('/ajax/SafetyBoxMsg/{bookID}', 'BookController@getSafetyBoxMsg');
  Route::post('/ajax/send-SafetyBox-sms', 'BookController@sendSafetyBoxSMS');
  Route::post('/ajax/send-SafetyBox-mail', 'BookController@sendSafetyBoxMail');
  Route::get('admin/get-SafetyBox', 'BookController@getSafetyBoxLst');
  
});
Route::group(['middleware' => ['auth','role:admin|propietario'], 'prefix' => 'admin',], function () {
  //Facturas
  Route::get('/facturas/ver/{id}', 'InvoicesController@view');
  Route::get('/facturas/descargar/{id}', 'InvoicesController@download');
  Route::get('/facturas/descargar-todas', 'InvoicesController@downloadAll');
  Route::get('/facturas/descargar-todas/{year}/{id}', 'InvoicesController@downloadAllProp');
  
 //Propietario
  Route::get('/propietario/bloquear', 'OwnedController@bloqOwned');
  Route::get('/propietario/{name?}/operativa', 'OwnedController@operativaOwned');
  Route::get('/propietario/{name?}/tarifas', 'OwnedController@tarifasOwned');
  Route::get('/propietario/{name?}/descuentos', 'OwnedController@descuentosOwned');
  Route::get('/propietario/{name?}/fiscalidad', 'OwnedController@fiscalidadOwned');
  Route::get('/propietario/{name?}/facturas', 'OwnedController@facturasOwned');
  Route::get('/propietario/{name?}', 'OwnedController@index');
  Route::get('/propietario/create/password/{email}', 'UsersController@createPasswordUser');
  Route::post('/propietario/create/password/{email}', 'UsersController@createPasswordUser');
  
});

/** Moved form routers */
Route::group(['middleware' => 'auth', 'prefix' => 'admin'], function () {
  Route::get('/rooms/api/getImagesRoom/{id?}/{bookId?}', 'RoomsController@getImagesRoom');
  Route::get('/reservas/api/getTableData', 'BookController@getTableData');
  Route::get('/reservas/new', 'BookController@newBook');
  Route::get('/apartamentos/getPaxPerRooms/{id}', 'RoomsController@getPaxPerRooms');
  Route::get('/apartamentos/getLuxuryPerRooms/{id}', 'RoomsController@getLuxuryPerRooms');
  Route::get('/api/reservas/getDataBook', 'BookController@getAllDataToBook');
  
  Route::get('/reservas/help/calculateBook', function () {
    return view('backend.planning._calculateBook');
  });
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
  Route::get('/customer/delete/{id}','RouterActionsController@customer_delete');
  Route::get('/customer/change/phone/{id}/{phone}','RouterActionsController@customer_change');
  
  Route::get('/sendImagesRoomEmail', 'RoomsController@sendImagesRoomEmail');
  Route::get('/books/getStripeLink/{book}/{importe}','RouterActionsController@books_getStripeLink');

  Route::get('/sales/updateLimpBook/{id}/{importe}','RouterActionsController@sales_updateLimpBook');
  Route::get('/sales/updateExtraCost/{id}/{importe}','RouterActionsController@sales_updateExtraCost');
  Route::get('/sales/updateCostApto/{id}/{importe}','RouterActionsController@sales_updateCostApto');
  Route::get('/sales/updateCostPark/{id}/{importe}','RouterActionsController@sales_updateCostPark');
  Route::get('/sales/updateCostTotal/{id}/{importe}','RouterActionsController@sales_updateCostTotal');
  Route::get('/sales/updatePVP/{id}/{importe}','RouterActionsController@sales_updatePVP');
  Route::get('/customers/searchByName/{searchString?}','RouterActionsController@customers_searchByName');
  Route::get('/invoices/searchByName/{searchString?}','RouterActionsController@invoices_searchByName');
  Route::get('/settings', 'SettingsController@index');
  Route::post('/settings-general', 'SettingsController@upd_general')->name('settings.gral.upd');
  Route::get('/settings_msgs/{lng?}/{key?}', 'SettingsController@messages')->name('settings.msgs');
  Route::post('/settings_msgs/{lng?}/{key?}', 'SettingsController@messages_upd')->name('settings.msgs.upd');
  Route::post('/specialSegments/create', 'SpecialSegmentController@create');
  Route::get('/specialSegments/update/{id?}', 'SpecialSegmentController@update');
  Route::post('/specialSegments/update/{id?}', 'SpecialSegmentController@update');
  Route::get('/specialSegments/delete/{id?}', 'SpecialSegmentController@delete');
  Route::get('/stripe-connect/{id}/acceptStripeConnect', 'StripeConnectController@acceptStripe');
  Route::get('/stripe-connect', 'StripeConnectController@index');
  Route::post('/stripe-connect/create-account-stripe-connect', 'StripeConnectController@createAccountStripeConnect');
  Route::post('/stripe-connect/load-transfer-form', 'StripeConnectController@loadTransferForm');
  Route::get('/stripe-connect/load-table-owneds', 'StripeConnectController@loadTableOwneds');
  Route::post('/stripe-connect/send-transfers', 'StripeConnectController@sendTransfers');
  //YEARS
  Route::post('/years/change', 'YearsController@changeActiveYear')->name('years.change');
  Route::post('/years/change/months', 'YearsController@changeMonthActiveYear')->name('years.change.month');
  //SETTINGS
  Route::post('/settings/createUpdate', 'SettingsController@createUpdateSetting')->name('settings.createUpdate');
  //PAYMENTS
//  Route::post('/', 'SettingsController@createUpdateSetting')->name('settings.createUpdate');
  Route::get('/links-payland', 'PaylandsController@link');
  Route::get('/links-payland-single', 'PaylandsController@linkSingle');
});


