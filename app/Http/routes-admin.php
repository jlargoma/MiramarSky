<?php
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

  /* ICalendar links */
  Route::post('/ical/import/saveUrl', 'ICalendarController@saveUrl');
  Route::get('/ical/urls/deleteUrl', 'ICalendarController@deleteUrl');
  Route::get('/ical/urls/getAllUrl/{aptoID}', 'ICalendarController@getAllUrl');
  Route::get('/ical/{aptoID}', [
      'as' => 'import-iCalendar',
      'uses' => 'ICalendarController@getIcalendar'
  ])->where('aptoID', '[0-9]+');

  Route::get('/ical/importFromUrl', function () {
    \Artisan::call('ical:import');
    return redirect('/reservas');
  });

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
  Route::get('/reservas/api/getTableData', 'BookController@getTableData');
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
  Route::get('/facturas/ver/{id}', 'InvoicesController@view');
  Route::get('/facturas/descargar/{id}', 'InvoicesController@download');
  Route::get('/facturas/descargar-todas', 'InvoicesController@downloadAll');
  Route::get('/facturas/descargar-todas/{year}/{id}', 'InvoicesController@downloadAllProp');
  Route::get('/facturas/solicitudes/{year?}', 'InvoicesController@solicitudes');
  Route::get('/facturas/isde/create', 'InvoicesController@createIsde');
  Route::post('/facturas/isde/create', 'InvoicesController@createIsde');
  Route::get('/facturas/isde/{year?}', 'InvoicesController@isde');
  Route::get('/facturas/isde/ver/{id}', 'InvoicesController@viewIsde');
  Route::get('/facturas/isde/descargar/{id}', 'InvoicesController@downloadIsde');
  Route::get('/facturas/isde/delete/{id}', 'InvoicesController@deleteIsde');
  Route::get('/facturas/isde/editar/{id}', 'InvoicesController@updateIsde');
  Route::post('/facturas/isde/saveUpdate', 'InvoicesController@saveUpdate');
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
  Route::get('/update/seasonsDays/{val}', 'AppController@seasonsDays');
  Route::get('/update/percentBenef/{val}', 'LiquidacionController@changePercentBenef');
  Route::post('/reservas/help/getTotalBook', 'BookController@getTotalBook');
  Route::get('/delete/nofify/{id}', 'AppController@nofify');
  Route::get('/reservas/changeSchedule/{id}/{type}/{schedule}', 'AppController@changeSchedule');
  Route::get('/reservas/restore/{id}/', 'AppController@restore');
  Route::get('/books/{idBook}/comments/{type}/save', 'BookController@saveComment');
  Route::get('/liquidation/searchByName', 'LiquidacionController@searchByName');
  Route::get('/liquidation/searchByRoom', 'LiquidacionController@searchByRoom');
  Route::get('/liquidation/orderByBenefCritico', 'LiquidacionController@orderByBenefCritico');
  Route::get('/apartamentos/rooms/getTableRooms/', 'AppController@getTableRooms');
  Route::get('/rooms/search/searchByName', 'RoomsController@searchByName');
  Route::get('/rooms/getUpdateForm', 'RoomsController@getUpdateForm');
  Route::get('/rooms/cupos', 'RoomsController@getCupos');
  Route::get('/apartamentos/fast-payment', 'RoomsController@updateFastPayment');
  Route::get('/apartamentos/update-order-payment', 'RoomsController@updateOrderFastPayment');
  Route::get('/sizeAptos/update-num-fast-payment', 'RoomsController@updateSizeAptos');
  Route::get('/paymentspro/delete/{id}', function ($id) {
    if (\App\Paymentspro::find($id)->delete()) {
      return 'ok';
    } else {
      return 'error';
    }
  });
  Route::get('/customer/delete/{id}', function ($id) {
    if (\App\Customers::find($id)->delete()) {
      return 'ok';
    } else {
      return 'error';
    }
  });
  Route::get('/customer/change/phone/{id}/{phone}', function ($id, $phone) {
    $customer = \App\Customers::find($id);
    $customer->phone = $phone;
    if ($customer->save()) {
      return [
          'status' => 'success',
          'title' => 'OK',
          'response' => "TelÃ©fono cambiado"
      ];
    } else {
      return [
          'status' => 'danger',
          'title' => 'Error',
          'response' => "No se ha cambiado el telÃ©fono"
      ];
    }
  });
  Route::get('/sendImagesRoomEmail', 'RoomsController@sendImagesRoomEmail');
  Route::get('/books/getStripeLink/{book}/{importe}', function ($book, $importe) {
    $book = \App\Book::find($book);
    $import = $importe;
    return view('backend.planning._links', [
        'book' => $book,
        'import' => $import,
    ]);
  });
  Route::get('/sales/updateLimpBook/{id}/{importe}', function ($id, $importe) {
    $book = \App\Book::find($id);
    $book->cost_limp = $importe;
    if ($book->save()) {
      return "OK";
    }
  });
  Route::get('/sales/updateExtraCost/{id}/{importe}', function ($id, $importe) {
    $book = \App\Book::find($id);
    $book->extraCost = $importe;
    if ($book->save()) {
      return "OK";
    }
  });
  Route::get('/sales/updateCostApto/{id}/{importe}', function ($id, $importe) {
    $book = \App\Book::find($id);
    $book->cost_apto = $importe;
    if ($book->save()) {
      return "OK";
    }
  });
  Route::get('/sales/updateCostPark/{id}/{importe}', function ($id, $importe) {
    $book = \App\Book::find($id);
    $book->cost_park = $importe;
    if ($book->save()) {
      return "OK";
    }
  });
  Route::get('/sales/updateCostTotal/{id}/{importe}', function ($id, $importe) {
    $book = \App\Book::find($id);
    $book->cost_total = $importe;
    if ($book->save()) {
      return "OK";
    }
  });
  Route::get('/sales/updatePVP/{id}/{importe}', function ($id, $importe) {
    $book = \App\Book::find($id);
    $book->total_price = $importe;
    if ($book->save()) {
      return "OK";
    }
  });
  Route::get('/customers/searchByName/{searchString?}', function ($searchString = "") {
    if ($searchString == "") {
      $arraycorreos = array();
      $correosUsuarios = \App\User::all();
      foreach ($correosUsuarios as $correos) {
        $arraycorreos[] = $correos->email;
      }
      $arraycorreos[] = "iankurosaki@gmail.com";
      $arraycorreos[] = "jlargoma@gmail.com";
      $arraycorreos[] = "victorgerocuba@gmail.com";
      $customers = \App\Customers::whereNotIn('email', $arraycorreos)->where('email', '!=', ' ')
                      ->distinct('email')->orderBy('created_at', 'DESC')->get();
    } else {
      $customers = \App\Customers::where('name', 'LIKE', '%' . $searchString . '%')
                      ->orWhere('email', 'LIKE', '%' . $searchString . '%')->get();
    }
    return view('backend.customers._table', ['customers' => $customers,]);
  });
  Route::get('/invoices/searchByName/{searchString?}', function ($searchString = "") {
    if ($searchString == "") {
      $arraycorreos = array();
      $correosUsuarios = \App\User::all();
      foreach ($correosUsuarios as $correos) {
        $arraycorreos[] = $correos->email;
      }
      $arraycorreos[] = "iankurosaki@gmail.com";
      $arraycorreos[] = "jlargoma@gmail.com";
      $arraycorreos[] = "victorgerocuba@gmail.com";
      $customers = \App\Customers::whereNotIn('email', $arraycorreos)->where('email', '!=', ' ')
                      ->distinct('email')->orderBy('created_at', 'DESC')->get();
    } else {
      $customers = \App\Customers::where('name', 'LIKE', '%' . $searchString . '%')
                      ->orWhere('email', 'LIKE', '%' . $searchString . '%')->get();
      echo "asdfasdf";
    }
    $arrayIdCustomers = array();
    foreach ($customers as $customer) {
      $arrayIdCustomers[] = $customer->id;
    }
    $books = \App\Book::where('type_book', 2)->whereIn('customer_id', $arrayIdCustomers)->orderBy('start', 'DESC')
            ->paginate(25);
    return view('backend.invoices._table', ['books' => $books,]);
  });
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
  Route::post('/settings/createUpdate', 'SettingsController@createUpdateSetting')
          ->name('settings.createUpdate');
});


Route::group(['middleware' => ['auth','role:admin|limpieza'], 'prefix' => 'admin',], function () {
  //LIMPIEZA
  Route::get('/limpieza', 'LimpiezaController@index');
});