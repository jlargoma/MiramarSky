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
//    use App\Http\Controllers\ForfaitsController;

    Route::auth();
    Route::get('/', 'HomeController@index');

    Route::get('/partee-checkHuespedes', function () {
        $partee = new \App\Services\ParteeService();
        $partee->conect();
        $id = 0;
        $partee->getCheckHuespedes($id);
        dd($partee);
    });
    
    Route::get('/no-allowed', function () {
        return view('no-allowed');
    });
    
  
    Route::get('/thanks-you', 'HomeController@thanksYou')->name('thanks-you');
    Route::get('/paymeny-error', 'HomeController@paymenyError')->name('paymeny-error');
    Route::get('/form-demo', 'BookController@demoFormIntegration');
    Route::post('/api/check_rooms_avaliables', 'BookController@apiCheckBook')->name('api.proccess');
    //        Route::get( '/homeTest' , 'HomeTestController@index' )->middleware( 'web' );
    //        Route::get( '/homeTest' , 'HomeTestController@index' )->middleware( 'web' );
    Route::get('/sitemap', 'HomeController@siteMap')->middleware('web');
    Route::get('/apartamentos/galeria/{apto}', 'HomeController@galeriaApartamento')->middleware('web');
    Route::get('/apartamentos/{apto}', 'HomeController@apartamento')->middleware('web');
    Route::get('/fotos/{apto}', 'HomeController@apartamento')->middleware('web');
    Route::get('/edificio-miramarski-sierra-nevada', 'HomeController@edificio')->middleware('web');
    Route::get('/contacto', 'HomeController@contacto')->middleware('web');
    //   /*Correos Frontend */
    Route::post('/contacto-form', 'HomeController@formContacto');
    Route::post('/contacto-ayuda', 'HomeController@formAyuda');
    Route::post('/contacto-propietario', 'HomeController@formPropietario');
    Route::post('/contacto-grupos', 'HomeController@formGrupos');
    //   /* Correos Frontend */getCalendarMobile
    Route::get('/terminos-condiciones', 'HomeController@terminos');
    Route::get('/politica-cookies', 'HomeController@politicaCookies');
    Route::get('/politica-privacidad', 'HomeController@politicaPrivacidad');
    Route::get('/condiciones-generales', 'HomeController@condicionesGenerales');
    Route::get('/preguntas-frecuentes', 'HomeController@preguntasFrecuentes');
    Route::get('/eres-propietario', 'HomeController@eresPropietario');
    Route::get('/grupos', 'HomeController@grupos');
    Route::get('/quienes-somos', 'HomeController@quienesSomos');
    Route::get('/ayudanos-a-mejorar', 'HomeController@ayudanosAMejorar');
    Route::get('/aviso-legal', 'HomeController@avisoLegal');
    Route::get('/huesped', 'HomeController@huesped');
    Route::get('/el-tiempo', 'HomeController@tiempo');
    Route::get('/condiciones-contratacion', 'HomeController@condicionesContratacion');
    Route::get('/restaurantes', function () {
        $mobile = new \App\Classes\Mobile();
        return view('frontend.restaurantes', ['mobile' => $mobile]);
    });
    Route::post('/getDiffIndays', 'HomeController@getDiffIndays');
    Route::post('/solicitudForfait', 'HomeController@solicitudForfait');
    Route::get('/admin/links-stripe', 'StripeController@link');
    Route::get('/admin/links-payland', 'PaylandsController@link');
    Route::get('/admin/links-payland-single', 'PaylandsController@linkSingle');
    /* SUPERMERCADO */
    Route::get('/supermercado', function () {
        return redirect('http://miramarski.com/supermercado');
    });
    /* FIN SUPERMERCADO */
    /* ENCUESTAS */
    Route::get('/encuesta-satisfaccion/{id}', 'QuestionsController@index');
    Route::post('/questions/vote', 'QuestionsController@vote');
    /* FIN ENCUESTAS*/
    /* CRONTABS */
    Route::get('/admin/reservas/api/checkSecondPay', 'BookController@checkSecondPay');
    /* Planing */
    Route::post('/getPriceBook', 'HomeController@getPriceBook');
    Route::get('/getFormBook', 'HomeController@form');
    Route::get('/getCitiesByCountry', 'HomeController@getCitiesByCountry');
    Route::get('/getCalendarMobile', 'BookController@getCalendarMobileView');
    Route::post('admin/reservas/create', 'BookController@create')->name('book.create');
   
    Route::get('/reservas/stripe/pagos/{id_book}', 'StripeController@stripePayment');
    Route::post('/reservas/stripe/payment/', 'StripeController@stripePaymentResponse');
    Route::post('/admin/reservas/stripe/paymentsBooking', 'StripeController@stripePaymentBooking');
    
   Route::group(['middleware' => 'authSubAdmin'], function () {
    Route::get('admin/reservas', 'BookController@index')->name('dashboard.planning');
    Route::get('admin/reservas/emails/{id}',  'BookController@emails');
    Route::get('admin/reservas/sendJaime',  'BookController@sendJaime');
    Route::get('admin/reservas/saveCobro', 'BookController@saveCobro');
    Route::get('admin/reservas/deleteCobro/{id}', 'BookController@deleteCobro');
   });
  
    Route::get('admin/reservas/search/searchByName', 'BookController@searchByName');

    //PDFÂ´s
    Route::get('admin/pdf/pdf-reserva/{id}', 'PdfController@invoice');
    Route::get('admin/pdf/descarga-excel-propietario/{id}', 'PdfController@pdfPropietario');
    Route::get('/importPaymenCashBank', 'Admin\BackendController@migrationCashBank');
    Route::get('/insertDNIS', 'Admin\BackendController@insertDNIS');
    Route::get('/refreshBloqueos', 'Admin\BackendController@refreshBloqueos');
    // AJAX REQUESTS
    Route::post('/ajax/requestPrice', 'FortfaitsController@calculatePrice');
    Route::post('/ajax/forfaits/updateRequestStatus', 'FortfaitsController@updateRequestStatus');
    Route::post('/ajax/forfaits/updateRequestPAN', 'FortfaitsController@updateRequestPAN');
    Route::post('/ajax/forfaits/updateRequestComments', 'FortfaitsController@updateRequestComments');
    Route::post('/ajax/forfaits/updateCommissions', 'FortfaitsController@updateCommissions');
    Route::post('/ajax/forfaits/updatePayments', 'FortfaitsController@updatePayments');
    Route::post('/ajax/forfaits/requestPriceForfaits', 'FortfaitsController@requestPriceForfaits');

    // ReCaptcha v3
    Route::post('/ajax/checkRecaptcha', 'FortfaitsController@checkReCaptcha');
    //AGENTES
    Route::post('/admin/agentRoom/create', 'SettingsController@createAgentRoom');
    Route::get('/admin/agentRoom/delete/{id}', 'SettingsController@deleteAgentRoom');
    // FORFATIS
    //PAYLANDS
    //Route::group(['middleware' => 'authAdmin'], function () {
    Route::post('/paylands/payment', 'PaylandsController@payment')->name('payland.payment');
    Route::get('/proccess/payment/book/{id}/{payment?}', 'PaylandsController@processPaymentBook')
         ->name('payland.proccess.payment.book');
    Route::post('/proccess/payment/book/{id}/{payment?}', 'PaylandsController@processPaymentBook')
         ->name('payland.proccess.payment.book');
    Route::get('/proccess/payment/book/customer/{id}/{payment?}', 'PaylandsController@processPaymentBook')
         ->name('payland.proccess.payment.book.customer');
    Route::post('/proccess/payment/book/customer/{id}/{payment?}', 'PaylandsController@processPaymentBook')
         ->name('payland.proccess.payment.book.customer');
    Route::get('payment/thansk-you/{key_token}', 'PaylandsController@thansYouPayment')->name('payland.thanks.payment');
   
    Route::get('payment/error/{key_token}', 'PaylandsController@errorPayment')->name('payland.error.payment');
    Route::get('payment/process/{key_token}', 'PaylandsController@processPayment')->name('payland.process.payment');
    //});
    
    Route::get('/paylands/payment', 'PaylandsController@paymentTest');

  

    Route::get('/api/forfaits/class', 'ForfaitsItemController@api_getClasses');
    Route::get('/api/forfaits/categ', 'ForfaitsItemController@api_getCategories');
    Route::get('/api/forfaits/items/{id}', 'ForfaitsItemController@api_items');
    Route::post('/api/forfaits/getCart', 'ForfaitsItemController@getCart');
    Route::post('/api/forfaits/checkout', 'ForfaitsItemController@checkout');
    Route::post('/api/forfaits/forfaits', 'ForfaitsItemController@getForfaitUser');
    Route::get('/api/forfaits/bookingData/{bID}/{uID}', 'ForfaitsItemController@bookingData');
    Route::get('/api/forfaits/getCurrentCart/{bID}/{uID}', 'ForfaitsItemController@getCurrentCart');
    Route::post('/api/forfaits/sendConsult', 'ForfaitsItemController@sendEmail');
    Route::get('/api/forfaits/getSeasons', 'ForfaitsItemController@getForfaitSeasons');


    include_once 'routes-admin.php';
    include_once 'routes-superAdmin.php';

    Route::get('/admin', function () {
      $user = \Auth::user();
      if ($user->role == "propietario") {
        $room = \App\Rooms::where('owned', $user->id)->first();
        if ($room)
          return redirect('/admin/propietario/' . $room->nameRoom);
        return redirect('/admin/propietario/');
      } else {
        return redirect('/admin/reservas');
      }
    })->middleware('auth');   
    
    /* ICalendar links */
    Route::get('/ical/{aptoID}', [
        'as' => 'import-iCalendar',
        'uses' => 'ICalendarController@getIcalendar'
    ])->where('aptoID', '[0-9]+');

    Route::get('/ical/importFromUrl', function () {
      \Artisan::call('ical:import');
//      return redirect('/admin/reservas');
    });