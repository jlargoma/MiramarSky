<?php

Route::group(['middleware' => 'authAdmin'], function () {
  Route::get('admin/cambiarCostes', 'BookController@changeCostes');
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
  Route::get('/admin/galleries','RoomsController@galleries');
// Prices
  Route::get('admin/precios','PricesController@index');
  Route::get('admin/precios/update','PricesController@update');
  Route::get('admin/precios/updateExtra','PricesController@updateExtra');
  Route::post('admin/precios/create','PricesController@create');
  Route::get('admin/precios/delete/{id}','PricesController@delete');
  Route::get('admin/precios/deleteExtra/{id}','PricesController@delete');
  Route::post('admin/precios/createExtras','PricesController@createExtras');
  // Prices
  Route::get('admin/precios', 'PricesController@index');
  Route::get('admin/precios/update', 'PricesController@update');
  Route::get('admin/precios/updateExtra', 'PricesController@updateExtra');
  Route::post('admin/precios/create', 'PricesController@create');
  Route::get('admin/precios/delete/{id}', 'PricesController@delete');
  Route::get('admin/precios/deleteExtra/{id}', 'PricesController@delete');
  Route::post('admin/precios/createExtras', 'PricesController@createExtras');
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
  Route::get('admin/pagos', 'PaymentsController@index');
  Route::get('admin/pagos/create', 'PaymentsController@create');
  Route::get('admin/pagos/update', 'PaymentsController@update');
  // Pagos-Propietarios
  Route::get('admin/pagos-propietarios', 'PaymentsProController@index');
  Route::post('admin/pagos-propietarios/create', 'PaymentsProController@create');
  Route::get('admin/pagos-propietarios/update/{id}/{month?}', 'PaymentsProController@update');
  Route::get('admin/paymentspro/getBooksByRoom/{idRoom}', 'PaymentsProController@getBooksByRoom');
  Route::get('admin/paymentspro/getLiquidationByRoom', 'PaymentsProController@getLiquidationByRoom');
  Route::get('admin/pagos-propietarios/get/historic_production/{room_id}', 'PaymentsProController@getHistoricProduction');
  //Liquidacion
  Route::get('admin/liquidacion', 'LiquidacionController@index');
  Route::get('admin/liquidacion-apartamentos', 'LiquidacionController@apto');
  Route::get('admin/liquidacion/export/excel', 'LiquidacionController@exportExcel');
  Route::get('admin/gastos/{year?}', 'LiquidacionController@gastos');
  Route::post('admin/gastos/create', 'LiquidacionController@gastoCreate');
  Route::get('/admin/gastos/getTableGastos', 'LiquidacionController@getTableGastos');
  Route::get('admin/gastos/update/{id}', 'LiquidacionController@updateGasto');
  Route::get('/admin/gastos/getHojaGastosByRoom/{year?}/{id}', 'LiquidacionController@getHojaGastosByRoom');
  Route::get('/admin/gastos/containerTableExpensesByRoom/{year?}/{id}', 'LiquidacionController@getTableExpensesByRoom');
  Route::get('/admin/gastos/delete/{id}','RouterActionsController@gastos_delete');
  Route::get('admin/ingresos', 'LiquidacionController@ingresos');
  Route::post('admin/ingresos/create', 'LiquidacionController@ingresosCreate');
  Route::get('/admin/ingresos/delete/{id}','RouterActionsController@ingresos_delete');
  Route::get('admin/estadisticas/{year?}','LiquidacionController@Statistics');
  Route::get('admin/contabilidad','LiquidacionController@contabilidad');
  Route::get('admin/perdidas-ganancias/{year?}','LiquidacionController@perdidasGanancias');
  Route::post('admin/limpiezasUpd/','LiquidacionController@upd_limpiezas');
  Route::get('admin/caja', 'LiquidacionController@caja');
  Route::get('admin/caja/getTableMoves/{year?}/{type}', 'LiquidacionController@getTableMoves');
  Route::post('admin/cashBox/create', 'LiquidacionController@cashBoxCreate');
  Route::get('admin/cashbox/updateSaldoInicial/{id}/{type}/{importe}','RouterActionsController@cashbox_updateSaldoInicial');
  Route::get('admin/banco', 'LiquidacionController@bank');
  Route::get('admin/banco/getTableMoves/{year?}/{type}', 'LiquidacionController@getTableMovesBank');
  Route::get('admin/bank/updateSaldoInicial/{id}/{type}/{importe}','RouterActionsController@bank_updateSaldoInicial');
  Route::get('/admin/rules/stripe/update', 'RulesStripeController@update');
  Route::get('/admin/days/secondPay/update/{id}/{days}','RouterActionsController@days_secondPay_update');
  Route::get('admin/estadisticas/{year?}', 'LiquidacionController@Statistics');
  Route::get('admin/contabilidad', 'LiquidacionController@contabilidad');
  Route::get('admin/perdidas-ganancias/{year?}', 'LiquidacionController@perdidasGanancias');
  //Facturas
  Route::get('admin/facturas/', 'InvoicesController@index');


  Route::get('admin/encuestas/{year?}/{apto?}', 'QuestionsController@admin');

  // AUX PROPIETARIOS
  Route::get('admin/propietarios/dashboard/{name?}/{year?}', 'OwnedController@index');
  Route::post('/ajax/forfaits/deleteRequestPopup', 'FortfaitsController@deleteRequestPopup');
  Route::post('/ajax/booking/getBookingAgencyDetails', 'BookController@getBookingAgencyDetails');
  Route::get('/ajax/booking/getBookingAgencyDetails', 'BookController@getBookingAgencyDetails');
  Route::get('admin/get-partee', 'BookController@getParteeLst');
  Route::get('/get-partee-msg', 'BookController@getParteeMsg');
  Route::post('/ajax/send-partee-finish', 'BookController@finishParteeCheckIn');
  Route::post('/ajax/send-partee-sms', 'BookController@finishParteeSMS');
  
  //Paylands
  Route::get('/admin/orders-payland', 'PaylandsController@lstOrders');
  Route::post('/admin/getOrders-payland', 'PaylandsController@getOrders');
  Route::get('/admin/get-summary-payland', 'PaylandsController@getSummary');
  
  /* ICalendar links */
  Route::post('/ical/import/saveUrl', 'ICalendarController@saveUrl');
  Route::get('/ical/urls/deleteUrl', 'ICalendarController@deleteUrl');
  Route::get('/ical/urls/getAllUrl/{aptoID}', 'ICalendarController@getAllUrl');
});

/**
 * Aptos
 */
Route::group(['middleware' => 'authAdmin', 'prefix' => 'admin/aptos',], function () {
  Route::get('/edit-room-descript/{id}', 'RoomsController@editRoomDescript');
  Route::get('/edit-descript/{id}', 'RoomsController@editDescript');
  Route::post('/edit-room-descript', 'RoomsController@updRoomDescript');
  Route::post('/edit-descript', 'RoomsController@updDescript');
});
/**
 * FORFAITS
 */
Route::group(['middleware' => 'authAdmin', 'prefix' => 'admin/forfaits',], function () {
  Route::get('/{class?}', 'ForfaitsItemController@index');
  Route::get('/edit/{id}', 'ForfaitsItemController@edit');
  Route::post('/upd', 'ForfaitsItemController@update');
  Route::get('/createItems', 'ForfaitsItemController@createItems');
  Route::get('/getBookItems/{bookingID}', 'ForfaitsItemController@getBookingFF');
  Route::post('/loadComment', 'ForfaitsItemController@loadComment');
  Route::post('/sendBooking', 'ForfaitsItemController@sendBooking');
});

/**
 * GENERAL
 */
Route::group(['middleware' => 'authAdmin', 'prefix' => 'admin'], function () {
  Route::get('/get_mails', 'ChatEmailsController@index');
  Route::get('/galleries', 'RoomsController@galleries');

  Route::get('/forfaits/deleteRequest/{id}', 'FortfaitsController@deleteRequest');
  Route::get('/reservas/ff_status_popup/{id}', 'BookController@getBookFFData');
  Route::get('/reservas/ff_change_status_popup/{id}/{status}', 'BookController@updateBookFFStatus');
  Route::get('/book-logs/{id}', 'BookController@printBookLogs');
  Route::post('/response-email', 'BookController@sendEmailResponse');
  Route::get('/book-logs/get/{id}', 'BookController@getBookLog');


  Route::post('/reservas/stripe/save/fianza', 'StripeController@fianza');
  Route::post('/reservas/stripe/pay/fianza', 'StripeController@payFianza');
  Route::get('/reservas/new', 'BookController@newBook');
  Route::get('/reservas/delete/{id}', 'BookController@delete');
  Route::get('/reservas/update/{id}', 'BookController@update')->name('book.update');
  Route::post('/reservas/saveUpdate/{id}', 'BookController@saveUpdate');
  Route::get('/reservas/changeBook/{id}', 'BookController@changeBook');
  Route::get('/reservas/changeStatusBook/{id}', 'BookController@changeStatusBook');
  Route::get('/reservas/ansbyemail/{id}', 'BookController@ansbyemail');
  Route::post('/reservas/sendEmail', 'BookController@sendEmail');
  Route::get('/reservas/saveFianza', 'BookController@saveFianza');
  Route::get('/reservas/reserva/{id}', 'BookController@tabReserva');
  Route::get('/reservas/cobrar/{id}', 'BookController@cobroBook');
  Route::get('/reservas/api/lastsBooks', 'BookController@getLastBooks');
  Route::get('/reservas/api/calendarBooking', 'BookController@getCalendarBooking');
  Route::get('/reservas/api/alertsBooking', 'BookController@getAlertsBooking');
  Route::get('/api/reservas/getDataBook', 'BookController@getAllDataToBook');
  Route::get('/reservas/api/sendSencondEmail', 'BookController@sendSencondEmail');
  Route::get('/reservas/api/toggleAlertLowProfits', 'BookController@toggleAlertLowProfits');


  Route::get('/reservas/api/activateAlertLowProfits', 'BookController@activateAlertLowProfits');
  Route::get('/reservas/fianzas/cobrar/{id}', 'BookController@cobrarFianzas');

  // Rooms
  Route::get('/apartamentos', 'RoomsController@index');
  Route::get('/apartamentos/new', 'RoomsController@newRoom');
  Route::get('/apartamentos/new-type', 'RoomsController@newTypeRoom');
  Route::get('/apartamentos/new-size', 'RoomsController@newSizeRoom');
  Route::get('/apartamentos/update', 'RoomsController@update');
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
  Route::get('/apartamentos/deletePhoto/{id}', 'RoomsController@deletePhoto');
  Route::post('/apartamentos/deletePhoto', 'RoomsController@deletePhotoItem');
  Route::post('/apartamentos/photo_main', 'RoomsController@photoIsMain');
  Route::post('/apartamentos/photo_orden', 'RoomsController@photoOrden');
  Route::post('/apartamentos/send/email/owned', 'RoomsController@sendEmailToOwned');
  Route::get('/apartamentos/getPaxPerRooms/{id}', 'RoomsController@getPaxPerRooms');
  Route::get('/apartamentos/getLuxuryPerRooms/{id}', 'RoomsController@getLuxuryPerRooms');
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
