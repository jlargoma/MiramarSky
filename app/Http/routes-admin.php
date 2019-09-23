<?php

Route::group(['middleware' => ['auth','role:admin|limpieza|subadmin'], 'prefix' => 'admin',], function () {
  
  //LIMPIEZA
  Route::get('/limpieza', 'LimpiezaController@index');
  Route::get('/limpiezas/{year?}','LiquidacionController@limpiezas');
  Route::post('/limpiezasLst/','LiquidacionController@get_limpiezas');
  Route::post('/limpiezasUpd/','LiquidacionController@upd_limpiezas');
  Route::post('/limpiezas/pdf','LiquidacionController@export_pdf_limpiezas');
  Route::get('/reservas/api/getTableData', 'BookController@getTableData');
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
  Route::get('/settings_msgs', 'SettingsController@messages')->name('settings.msgs');
  Route::post('/settings_msgs', 'SettingsController@messages_upd')->name('settings.msgs.upd');
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


