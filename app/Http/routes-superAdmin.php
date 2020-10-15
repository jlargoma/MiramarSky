<?php

Route::group(['middleware' => 'authAdmin'], function () {
  Route::get('/partee-checkStatus/{id}', function ($id) {
      $partee = new \App\Services\ParteeService();
      $partee->conect();
      $partee->getCheckStatus($id);
      dd($partee);
  });
  Route::get('/partee-getPDF/{id}', function ($id) {
      $partee = new \App\Services\ParteeService();
      $partee->conect();
      $partee->getParteePDF($id);
      dd($partee);
  });
    
  // Usuarios
  Route::get('admin/usuarios', 'UsersController@index');
  Route::get('admin/usuarios/update/{id}', 'UsersController@update');
  Route::post('admin/usuarios/saveAjax', 'UsersController@saveAjax');
  Route::post('admin/usuarios/saveupdate', 'UsersController@saveUpdate');
  Route::post('admin/usuarios/create', 'UsersController@create');
  Route::get('admin/usuarios/delete/{id}', 'UsersController@delete');
  Route::post('admin/usuarios/search', 'UsersController@searchUserByName');

  // Clientes
  Route::get('admin/clientes', 'CustomersController@index');
  Route::get('/admin/galleries/{id?}','RoomsController@galleries');
// Prices
  Route::get('admin/precios','PricesController@index')->name('precios.base');
  Route::get('admin/precios/update','PricesController@update');
  Route::get('admin/precios/updateExtra','PricesController@updateExtra');
  Route::post('admin/precios/create','PricesController@create');
  Route::get('admin/precios/delete/{id}','PricesController@delete');
  Route::get('admin/precios/deleteExtra/{id}','PricesController@delete');
  Route::post('admin/precios/createExtras','PricesController@createExtras');
  Route::delete('admin/precios/createExtras','PricesController@delteExtraPrices')->name('precios.extr_price.del');
  
  Route::get('admin/precios/preciosOTAs','PricesController@pricesOTAs')->name('precios.pricesOTAs');
  Route::post('admin/precios/preciosOTAs','PricesController@pricesOTAsUpd')->name('precios.pricesOTAs.upd');
  
  Route::post('admin/precios/prepare-crom','PricesController@prepareYearPrices')->name('precios.prepare-cron');
  Route::post('admin/precios/prepare-crom-minStay','PricesController@prepareYearMinStay')->name('precios.prepare-cron-minStay');
  // Prices

  // seasons
  Route::get('admin/temporadas', 'SeasonsController@index');
  Route::get('admin/temporadas/new', 'SeasonsController@newSeasons');
  Route::get('admin/temporadas/new-type', 'SeasonsController@newTypeSeasons');
  Route::get('admin/temporadas/update/{id}', 'SeasonsController@update');
  Route::post('admin/temporadas/update/{id}', 'SeasonsController@update');
  Route::post('admin/temporadas/saveupdate', 'SeasonsController@saveUpdate');
  Route::post('admin/temporadas/create', 'SeasonsController@create');
  Route::post('admin/temporadas/create-type', 'SeasonsController@createType');
  Route::get('admin/temporadas/delete/{id}', 'SeasonsController@delete');
  // Pagos
  Route::get('admin/pagos/create', 'PaymentsController@create');
  Route::get('admin/pagos/update', 'PaymentsController@update');
  Route::post('admin/pagos/cobrar', 'PaymentsController@cobrarFianza');
  Route::get('admin/pagos', 'PaymentsController@index');

  //Liquidacion
  Route::get('admin/perdidas-ganancias/{year?}','LiquidacionController@perdidasGanancias');
  Route::get('admin/perdidas-ganancias/show-detail/{key}','LiquidacionController@perdidasGananciasShowDetail');
  Route::post('admin/perdidas-ganancias/show-hide','LiquidacionController@perdidasGananciasShowHide');
  Route::post('admin/perdidas-ganancias/upd-ingr','LiquidacionController@perdidasGananciasUpdIngr');
  Route::post('admin/perdidas-ganancias/upd-benef','LiquidacionController@perdidasGananciasUpdBenef');
  Route::post('admin/perdidas-ganancias/upd-iva','LiquidacionController@perdidasGananciasUpdIVA');

//  Route::get('admin/perdidas-ganancias-funcional/{year?}','LiquidacionController@perdidasGananciasFuncional');
  //Facturas
  Route::get('admin/facturas/', 'InvoicesController@index');

  Route::get('admin/encuestas/{year?}/{apto?}', 'QuestionsController@admin');

  // AUX PROPIETARIOS
  Route::get('admin/propietarios/dashboard/{name?}/{year?}', 'OwnedController@index');
//  Route::post('/ajax/forfaits/deleteRequestPopup', 'FortfaitsController@deleteRequestPopup');
  Route::post('/ajax/booking/getBookingAgencyDetails', 'LiquidacionController@getBookingAgencyDetails');
  Route::get('/ajax/booking/getBookingAgencyDetails', 'LiquidacionController@getBookingAgencyDetails');
  

  
  
  /* ICalendar links */
  Route::post('/ical/import/saveUrl', 'ICalendarController@saveUrl');
  Route::get('/ical/urls/deleteUrl', 'ICalendarController@deleteUrl');
  Route::get('/ical/getLasts', 'ICalendarController@getLasts');
  Route::get('/ical/urls/getAllUrl/{aptoID}', 'ICalendarController@getAllUrl');
  
  
  Route::get('admin/contents-home/{type?}', 'ContentsControllers@index')->name('contents.index');
  Route::post('admin/contents-home/{type?}', 'ContentsControllers@update')->name('contents.upd');
  
  
});

/**
 * Aptos
 */
Route::group(['middleware' => 'authAdmin', 'prefix' => 'admin/aptos',], function () {
  Route::get('/edit-room-descript/{id}', 'RoomsController@editRoomDescript');
  Route::get('/edit-descript/{id}', 'RoomsController@editDescript');
  Route::post('/edit-room-descript', 'RoomsController@updRoomDescript');
  Route::post('/edit-descript', 'RoomsController@updDescript');
  Route::get('/rooms-type', 'RoomsController@getRoomsType');
  Route::post('/rooms-type', 'RoomsController@updRoomsType');
});


/**
 * GENERAL
 */
Route::group(['middleware' => 'authAdmin', 'prefix' => 'admin'], function () {
  

  Route::get('/apartamentos/fast-payment', 'RoomsController@updateFastPayment');
  Route::get('/apartamentos/update-order-payment', 'RoomsController@updateOrderFastPayment');
  Route::get('/sizeAptos/update-num-fast-payment', 'RoomsController@updateSizeAptos');
  Route::get('/rooms/getUpdateForm', 'RoomsController@getUpdateForm');
  Route::get('/rooms/cupos', 'RoomsController@getCupos');
  
  Route::get('/reservas/api/activateAlertLowProfits', 'BookController@activateAlertLowProfits');
  Route::get('/reservas/fianzas/cobrar/{id}', 'BookController@cobrarFianzas');


  // Rooms
  Route::get('/apartamentos', 'RoomsController@index');
  Route::get('/apartamentos/new', 'RoomsController@newRoom');
  Route::get('/apartamentos/new-type', 'RoomsController@newTypeRoom');
  Route::get('/apartamentos/new-size', 'RoomsController@newSizeRoom');
  Route::post('/apartamentos/update', 'RoomsController@update');
  Route::get('/apartamentos/update-type', 'RoomsController@updateType');
  Route::get('/apartamentos/update-name', 'RoomsController@updateName');
  Route::get('/apartamentos/update-nameRoom', 'RoomsController@updateNameRoom');
  Route::get('/apartamentos/update-order', 'RoomsController@updateOrder');
  Route::get('/apartamentos/update-size', 'RoomsController@updateSize');
  Route::get('/apartamentos/update-owned', 'RoomsController@updateOwned');
  Route::get('/apartamentos/update-parking', 'RoomsController@updateParking');
  Route::get('/apartamentos/update-taquilla', 'RoomsController@updateTaquilla');
  Route::post('/apartamentos/saveupdate', 'RoomsController@saveUpdate');
  Route::post('/apartamentos/create', 'RoomsController@create');
  Route::post('/apartamentos/create-type', 'RoomsController@createType');
  Route::post('/apartamentos/create-size', 'RoomsController@createSize');
  Route::get('/apartamentos/state', 'RoomsController@state');
  Route::get('/apartamentos/percentApto', 'RoomsController@percentApto');
  Route::get('/apartamentos/update-Percent', 'RoomsController@updatePercent');
  Route::get('/apartamentos/email/{id}', 'RoomsController@email');
  Route::get('/apartamentos/fotos/{id}', 'RoomsController@photo');
  Route::get('/apartamentos/gallery/{id}', 'RoomsController@gallery');
  Route::get('/apartamentos/headers/{type}/{id}', 'RoomsController@headers');
  Route::get('/apartamentos/deletePhoto/{id}', 'RoomsController@deletePhoto');
  Route::post('/apartamentos/deletePhoto', 'RoomsController@deletePhotoItem');
  Route::post('/apartamentos/photo_main', 'RoomsController@photoIsMain');
  Route::post('/apartamentos/photo_orden', 'RoomsController@photoOrden');
  Route::post('/apartamentos/send/email/owned', 'RoomsController@sendEmailToOwned');

  Route::post('/apartamentos/upload-img-header', 'RoomsController@uploadHeaderFile');
  Route::post('/apartamentos/uploadFile', 'RoomsController@uploadRoomFile');
  Route::post('/apartamentos/uploadFile/{id}', 'RoomsController@uploadFile');
  Route::get('/apartamentos/assingToBooking', 'RoomsController@assingToBooking');
  Route::get('/apartamentos/download/contrato/{userId}', 'RoomsController@downloadContractoUser');

  // Clientes
  Route::get('/clientes/update', 'CustomersController@update');
  Route::get('/clientes/save', 'CustomersController@save');
  Route::post('/clientes/create', 'CustomersController@create');
  Route::get('/clientes/export-excel', 'CustomersController@createExcel');
  Route::get('/customers/importExcelData', 'CustomersController@createExcel');
  Route::get('/clientes/delete/{id}', 'CustomersController@delete');
  Route::get('/customers/searchByName/{searchString?}','CustomersController@searchByName');
  Route::get('/customer/delete/{id}','CustomersController@delete');
  
  //Facturas
  Route::get('/facturas/solicitudes/{year?}', 'InvoicesController@solicitudes');
  Route::get('/facturas/isde/create', 'InvoicesController@createIsde');
  Route::post('/facturas/isde/create', 'InvoicesController@createIsde');
  Route::get('/facturas/isde/{year?}', 'InvoicesController@isde');
  Route::get('/facturas/isde/ver/{id}', 'InvoicesController@viewIsde');
  Route::get('/facturas/isde/descargar/{id}', 'InvoicesController@downloadIsde');
  Route::get('/facturas/isde/delete/{id}', 'InvoicesController@deleteIsde');
  Route::get('/facturas/isde/editar/{id}', 'InvoicesController@updateIsde');
  Route::post('/facturas/isde/saveUpdate', 'InvoicesController@saveUpdate');
 
});

Route::group(['middleware' => ['auth','role:admin|subadmin|agente|recepcionista'], 'prefix' => 'admin',], function () {
  Route::get('/reservas/update/{id}', 'BookController@update')->name('book.update');
  Route::post('/reservas/saveUpdate/{id}', 'BookController@saveUpdate');
});
Route::group(['middleware' => ['auth','role:admin|subadmin|recepcionista'], 'prefix' => 'admin',], function () {
  
  // Clientes
  Route::get('/clientes/update', 'CustomersController@update');
  Route::get('/clientes/save', 'CustomersController@save');
  Route::post('/clientes/create', 'CustomersController@create');
  
  Route::get('/get_mails', 'ChatEmailsController@index');
  Route::get('/galleries', 'RoomsController@galleries');

//  Route::get('/forfaits/deleteRequest/{id}', 'FortfaitsController@deleteRequest');
//  Route::get('/reservas/ff_status_popup/{id}', 'BookController@getBookFFData');

//  Route::get('/reservas/ff_change_status_popup/{id}/{status}', 'BookController@updateBookFFStatus');
    
  Route::post('/reservas/get-visa', 'BookController@getVisa')->name('booking.get_visa');
  Route::post('/reservas/upd-visa', 'BookController@updVisa')->name('booking.upd_visa');
  Route::post('/reservas/change-mail-notif', 'BookController@changeMailNotif')->name('booking.changeMailNotif');
  Route::get('/book-logs/see-more/{id}', 'BookController@getBookLog');
  Route::get('/book-logs/see-more-mail/{id}', 'BookController@getMailLog');
  Route::get('/book-logs/{id}/{month?}', 'BookController@printBookLogs');
  Route::post('/response-email', 'BookController@sendEmailResponse');
  
  Route::post('/reservas/stripe/save/fianza', 'StripeController@fianza');
  Route::post('/reservas/stripe/pay/fianza', 'StripeController@payFianza');
  Route::get('/reservas/delete/{id}', 'BookController@delete');

  Route::get('/reservas/changeBook/{id}', 'BookController@changeBook');
  Route::get('/reservas/changeStatusBook/{id}', 'BookController@changeBook');
  Route::get('/reservas/ansbyemail/{id}', 'BookController@ansbyemail');
  Route::post('/reservas/sendEmail', 'BookController@sendEmail');
  Route::get('/reservas/saveFianza', 'BookController@saveFianza');
  Route::get('/reservas/reserva/{id}', 'BookController@tabReserva');
  Route::get('/reservas/cobrar/{id}', 'BookController@cobroBook');
  Route::get('/reservas/api/lastsBooks', 'BookController@getLastBooks');
  Route::get('/reservas/api/intercambio', 'BookController@getIntercambio');
  Route::get('/reservas/api/intercambio-search/{block}/{search?}', 'BookController@getIntercambioSearch');
  Route::post('/reservas/api/intercambio-change', 'BookController@intercambioChange');
  Route::get('/reservas/api/calendarBooking', 'BookController@getCalendarBooking');
  Route::get('/reservas/api/alertsBooking', 'BookController@getAlertsBooking');

  Route::get('/reservas/api/sendSencondEmail', 'BookController@sendSencondEmail');
  Route::get('/reservas/api/toggleAlertLowProfits', 'BookController@toggleAlertLowProfits');
  
    //Paylands
  Route::get('/orders-payland', 'PaylandsController@lstOrders');
  Route::post('/getOrders-payland', 'PaylandsController@getOrders');
  Route::get('/get-summary-payland', 'PaylandsController@getSummary');
  

    
  // OTAS  
  Route::get('/channel-manager/disponibilidad/{apto}/{start}/{end}','ZodomusController@listDisponibilidad');
  Route::get('/channel-manager/price/{apto?}','OtaGate@calendRoom')->name('channel.price.cal');
  Route::post('/channel-manager/price/{apto?}','OtaGate@calendRoomUPD')->name('channel.price.cal.upd');
  Route::get('/channel-manager/price/precios/list/{apto}','OtaGate@listBy_room')->name('channel.price.cal.list');
  Route::get('/channel-manager/list/{apto}','ZodomusController@listBy_apto')->name('channel.cal.list');
  Route::get('/channel-manager/config', 'ZodomusController@generate_config');
  
  Route::get('/channel-manager/price-site/{site?}/{month?}/{year?}','OtaGate@calendSite')->name('channel.price.site');
  Route::post('/channel-manager/upd-price-site/','ZodomusController@calendSiteUpd')->name('channel.price.site.upd');
  Route::get('/channel-manager/promocion/{promoID?}','PromotionsController@getItem')->name('channel.promotions.get');
  Route::get('/channel-manager/promociones/','PromotionsController@index')->name('channel.promotions');
  Route::post('/channel-manager/promociones/create','PromotionsController@create')->name('channel.promotions.new');
  Route::post('/channel-manager/promociones/upd','PromotionsController@update')->name('channel.promotions.upd');
  Route::delete('/channel-manager/promociones','PromotionsController@delete')->name('channel.promotions.delete');
  Route::post('/Wubook/sendMinStay', 'WubookController@sendMinStayGroup')->name('Wubook.sendMinStay');  
  Route::get('/channel-manager/ZODOMUS', 'ZodomusController@zodomusTest');
  Route::get('/channel-manager/index', 'ZodomusController@index')->name('channel.index');
  Route::get('/channel-manager/forceImport', 'ZodomusController@forceImport')->name('channel.forceImport');
  Route::post('/channel-manager/send-avail/{apto}', 'ZodomusController@sendAvail')->name('channel.sendAvail');
  Route::get('/channel-manager', 'ZodomusController@calendRoom')->name('channel');
  
  
  // WUBOOK
  Route::get('/Wubook', 'WubookController@index');
  Route::post('/Wubook/sendPrices', 'WubookController@sendPricesGroup')->name('Wubook.sendPrices');
  Route::get('/Wubook/createAvails', 'WubookController@createAvails')->name('Wubook.createAvails');
  Route::get('/Wubook/sendAvails', 'WubookController@sendAvails')->name('Wubook.sendAvails');
  Route::get('/Wubook/processHook', 'WubookController@processHook')->name('Wubook.processHook');
  
  Route::get('/liquidacion', 'LiquidacionController@index');
    Route::get('/liquidacion-apartamentos', 'LiquidacionController@apto');
  Route::get('/liquidacion/export/excel', 'LiquidacionController@exportExcel');
});
  
Route::group(['middleware' => ['auth','role:admin|subadmin'], 'prefix' => 'admin',], function () {
   //Liquidacion
  
  Route::post('/gastos/importar', 'LiquidacionController@gastos_import');
  Route::get('/gastos/{year?}', 'LiquidacionController@gastos');
  Route::post('/gastos/create', 'LiquidacionController@gastoCreate');
  Route::get('/gastos/getTableGastos', 'LiquidacionController@getTableGastos');
  Route::post('/gastos/gastosLst', 'LiquidacionController@getTableGastos');
  Route::post('/gastos/update', 'LiquidacionController@updateGasto');
  Route::get('/gastos/getHojaGastosByRoom/{year?}/{id}', 'PaymentsProController@getHojaGastosByRoom');
  Route::get('/gastos/containerTableExpensesByRoom/{year?}/{id}', 'LiquidacionController@getTableExpensesByRoom');
  Route::post('/gastos/del','RouterActionsController@gastos_delete');
  Route::get('/ingresos', 'LiquidacionController@ingresos');
  Route::post('/ingresos/create', 'LiquidacionController@ingresosCreate');
  Route::post('/ingresos/upd', 'LiquidacionController@ingresosUpd');
  Route::get('/ingresos/delete/{id}','RouterActionsController@ingresos_delete');
  Route::get('/estadisticas/{year?}','LiquidacionController@Statistics');
  Route::get('/contabilidad','LiquidacionController@contabilidad');
 
  
  Route::get('/caja', 'LiquidacionController@caja');
  Route::post('/caja/cajaLst', 'LiquidacionController@getTableCaja');
  Route::get('/caja/getTableMoves/{year?}/{type}', 'LiquidacionController@getTableMoves');
  Route::post('/caja/del-item', 'LiquidacionController@delCajaItem');
  Route::post('/cashBox/create', 'LiquidacionController@cashBoxCreate');
  Route::get('/cashbox/updateSaldoInicial/{id}/{type}/{importe}','RouterActionsController@cashbox_updateSaldoInicial');
  Route::get('/banco', 'LiquidacionController@bank');
  Route::get('/banco/getTableMoves/{year?}/{type}', 'LiquidacionController@getTableMovesBank');
  Route::get('/bank/updateSaldoInicial/{id}/{type}/{importe}','RouterActionsController@bank_updateSaldoInicial');
  Route::get('/rules/stripe/update', 'RulesStripeController@update');
  Route::get('/days/secondPay/update/{id}/{days}','RouterActionsController@days_secondPay_update');
  Route::get('/estadisticas/{year?}', 'LiquidacionController@Statistics');
  Route::get('/contabilidad', 'LiquidacionController@contabilidad');
  Route::post('/arqueo/create', 'LiquidacionController@arqueoCreate');

  Route::get('/contabilidad','LiquidacionController@contabilidad');
  
  

  Route::get('/perdidas-ganancias-funcional/{year?}','LiquidacionController@perdidasGananciasFuncional');
  
  // Pagos-Propietarios
  Route::get('/pagos-propietarios', 'PaymentsProController@index');
  Route::post('/pagos-propietarios/create', 'PaymentsProController@create');
  Route::get('/pagos-propietarios/update/{id}/{month?}', 'PaymentsProController@update');
  Route::get('/paymentspro/getBooksByRoom/{idRoom}', 'PaymentsProController@getBooksByRoom');
  Route::get('/paymentspro/getLiquidationByRoom', 'PaymentsProController@getLiquidationByRoom');
  Route::get('/paymentspro/getLiquidationByMonth', 'PaymentsProController@getLiquidationByMonth');
  Route::get('/pagos-propietarios/get/historic_production/{room_id}', 'PaymentsProController@getHistoricProduction');
  Route::post('/pagos-propietarios', 'PaymentsProController@indexByDate');
  Route::post('/paymentspro/seeLiquidationProp', 'PaymentsProController@seeLiquidationProp');
  
  
  Route::get('/ical/importFromUrl', function () {
      \Artisan::call('ical:import');
  });
  
  Route::get('/zodomus/import', function () {
      \Artisan::call('zodomus:importAll');
  });
  Route::get('/ota-gateway/import', function () {
      \Artisan::call('OGImportAll:import');
  });
  
  Route::get('/channel-manager-test', function(){
    $book = new \App\Book();
    $book->sendAvailibility_test();
  });
  
  
    
  // OTA Gateway  
  Route::get('/otaGate/test','OtaGate@test');

  
});
  Route::get('/connect_airbnb',function(){ var_dump($_REQUEST); die; });
  Route::post('/connect_airbnb',function(){ var_dump($_REQUEST); die; });