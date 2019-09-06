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
  Route::get('/admin/gastos/delete/{id}', function ($id) {
    if (\App\Expenses::find($id)->delete()) {
      return 'ok';
    } else {
      return 'error';
    }
  });
  Route::get('admin/ingresos', 'LiquidacionController@ingresos');
  Route::post('admin/ingresos/create', 'LiquidacionController@ingresosCreate');
  Route::get('/admin/ingresos/delete/{id}', function ($id) {
    if (\App\Incomes::find($id)->delete()) {
      return 'ok';
    } else {
      return 'error';
    }
  });
  Route::get('admin/estadisticas/{year?}','LiquidacionController@Statistics');
  Route::get('admin/contabilidad','LiquidacionController@contabilidad');
  Route::get('admin/perdidas-ganancias/{year?}','LiquidacionController@perdidasGanancias');
  Route::get('admin/limpiezas/{year?}','LiquidacionController@limpiezas');
  Route::post('admin/limpiezasLst/','LiquidacionController@get_limpiezas');
  Route::post('admin/limpiezasUpd/','LiquidacionController@upd_limpiezas');
  Route::post('admin/limpiezas/pdf','LiquidacionController@export_pdf_limpiezas');
  Route::get('admin/caja', 'LiquidacionController@caja');
  Route::get('admin/caja/getTableMoves/{year?}/{type}', 'LiquidacionController@getTableMoves');
  Route::post('admin/cashBox/create', 'LiquidacionController@cashBoxCreate');
  Route::get('admin/cashbox/updateSaldoInicial/{id}/{type}/{importe}', function ($id, $type, $importe) {
    $cashbox = \App\Cashbox::find($id);
    $cashbox->import = $importe;
    if ($cashbox->save()) {
      return "OK";
    }
  });
  Route::get('admin/banco', 'LiquidacionController@bank');
  Route::get('admin/banco/getTableMoves/{year?}/{type}', 'LiquidacionController@getTableMovesBank');
  Route::get('admin/bank/updateSaldoInicial/{id}/{type}/{importe}', function ($id, $type, $importe) {
    $cashbox = \App\Cashbox::find($id);
    $cashbox->import = $importe;
    if ($cashbox->save()) {
      return "OK";
    }
  });
  Route::get('/admin/rules/stripe/update', 'RulesStripeController@update');
  Route::get('/admin/days/secondPay/update/{id}/{days}', function ($id, $days) {
    $day = \App\DaysSecondPay::find($id);
    $day->days = $days;
    $day->save();
  });
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
  Route::get('/get-partee-msg', 'BookController@getParteeMsg');
  Route::post('/ajax/send-partee-finish', 'BookController@finishParteeCheckIn');
  Route::post('/ajax/send-partee-sms', 'BookController@finishParteeSMS');
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
