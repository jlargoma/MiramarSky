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
      Route::get('/home', 'HomeController@index');
      Route::get('create-cache', 'HomeController@index')->middleware('page-cache');
      
      Route::get('/404', function () {
         return view('404');
      });
      Route::get('/no-allowed', function () {
          return view('no-allowed');
      });
      Route::get('403', function () {
          return view('no-allowed');
      });
    
      Route::post('static-token', function () {
          return csrf_token();
      });
    

    Route::post('zodomus-Webhook','ZodomusController@webHook');
    Route::post('wubook-Webhook', 'WubookController@webHook');
    Route::post('Ota-Gateway-Webhook', 'OtaGate@webHook');
    
    Route::get('/partee-checkHuespedes', function () {
        $partee = new \App\Services\ParteeService();
        $partee->conect();
        $id = 0;
        $partee->getCheckHuespedes($id);
        dd($partee);
    });
    
   
    
  
    Route::get('/thanks-you', 'HomeController@thanksYou')->name('thanks-you');
    Route::get('/thanks-you-forfait',function () {return view('frontend.stripe.forfait');})->name('thanks-you-forfait');
    Route::get('/paymeny-error', 'HomeController@paymenyError')->name('paymeny-error');
    Route::get('/form-demo', 'BookController@demoFormIntegration');
    Route::post('/api/check_rooms_avaliables', 'BookController@apiCheckBook')->name('api.proccess');
    
    Route::group(['middleware' => 'web'], function () {
      Route::get('/sitemap', 'HomeController@siteMap');
      Route::get('/apartamentos/galeria/{apto}', 'HomeController@galeriaApartamento');
      Route::get('/apartamentos/{apto}', 'HomeController@apartamento')->name('web.apto');
      Route::get('/fotos/{apto}', 'HomeController@apartamento');
      Route::get('/edificio-miramarski-sierra-nevada', 'HomeController@edificio')->name('web.edificio');
      Route::get('/contacto', 'HomeController@contacto');
    });
    //   /*Correos Frontend */
    Route::post('/contacto-form', 'HomeController@formContacto');
    Route::post('/contacto-ayuda', 'HomeController@formAyuda');
    Route::post('/contacto-propietario', 'HomeController@formPropietario');
    Route::post('/contacto-grupos', 'HomeController@formGrupos');
    //   /* Correos Frontend */getCalendarMobile
    Route::get('/buzon', 'HomeController@buzon');
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
    Route::get('/condiciones-contratacion', 'HomeController@condicionesContratacion')->name('cond.contratacion');
    Route::get('/condiciones-fianza', 'HomeController@condicionesFianza')->name('cond.fianza');
    Route::get('/restaurantes', 'HomeController@restaurantes')->name('cond.resto');
    Route::post('/getDiffIndays', 'HomeController@getDiffIndays');
    Route::post('/solicitudForfait', 'HomeController@solicitudForfait');
    Route::get('/admin/links-stripe', 'StripeController@link');
    Route::get('/payments-forms/{token}', 'PaylandsController@paymentsForms')->name('front.payments');
    Route::post('/payments-save-dni/{token}', 'PaylandsController@saveDni')->name('front.payments.dni')->middleware('cors');

    
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
    Route::get('/getCalendarMobile/{month?}', 'BookController@getCalendarMobileView');
    Route::get('/getCalendarChannel/{room}/{month?}', 'BookController@getCalendarChannelView');
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
   
   Route::group(['middleware' => ['auth','role:admin|limpieza|subadmin|recepcionista|conserje'], 'prefix' => 'admin',], function () {
    Route::get('/reservas', 'BookController@index')->name('dashboard.planning');
   });
  
    Route::get('admin/reservas/search/searchByName', 'BookController@searchByName');

    //PDFÂ´s
    Route::get('admin/pdf/pdf-reserva/{id}', 'PdfController@invoice');
//    Route::get('admin/pdf/descarga-excel-propietario/{id}', 'PdfController@pdfPropietario');
    Route::get('/importPaymenCashBank', 'Admin\BackendController@migrationCashBank');
    Route::get('/insertDNIS', 'Admin\BackendController@insertDNIS');
//    Route::get('/refreshBloqueos', 'Admin\BackendController@refreshBloqueos');
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
    Route::post('/paylands/get-payment-by-type', 'PaylandsController@getPaymentByType')->name('payland.get_payment');
//    Route::get('/proccess/payment/book/{id}/{payment?}', 'PaylandsController@processPaymentBook')
//         ->name('payland.proccess.payment.book');
//    Route::post('/proccess/payment/book/{id}/{payment?}', 'PaylandsController@processPaymentBook')
//         ->name('payland.proccess.payment.book');
//    Route::get('/proccess/payment/book/customer/{id}/{payment?}', 'PaylandsController@processPaymentBook')
//         ->name('payland.proccess.payment.book.customer');
//    Route::post('/proccess/payment/book/customer/{id}/{payment?}', 'PaylandsController@processPaymentBook')
//         ->name('payland.proccess.payment.book.customer');
    Route::get('payment/thansk-you/{key_token}', 'PaylandsController@thansYouPayment')->name('payland.thanks.payment');
    Route::get('payment/thansk-you-deferred/{key_token}', 'PaylandsController@thansYouPaymentDeferred')->name('payland.thanks.deferred');
   
    Route::get('payment/error/{key_token}', 'PaylandsController@errorPayment')->name('payland.error.payment');
    Route::get('payment/process/{key_token}', 'PaylandsController@processPayment')->name('payland.process.payment');
    Route::post('payment/process/{key_token}', 'PaylandsController@processPayment')->name('payland.process.payment');
    //});
    
    Route::get('/paylands/payment-test',function(){
      return view('Paylands.test');
    });
    Route::get('/paylands/payment-test/{bID}', 'PaylandsController@paymentTest');

  



    include_once 'routes-forfaits.php';
    include_once 'routes-admin.php';
    include_once 'routes-superAdmin.php';
    include_once 'routes-api.php';
//    include_once 'new.php';

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
    
    
  
     
    Route::get('admin/OTAs/sendPricesSeason', function () {
        \Artisan::call('OTAs:sendPricesSeason');
        die('finish');
    });
    
    Route::get('admin/Wubook/Availables', function () {
        \Artisan::call('wubook:sendAvaliables');
        die('finish');
    });
     Route::get('admin/rum/Process-data', function () {
        \Artisan::call('ProcessData:all');
        die('finish');
    });
    
    /* ICalendar links */
    Route::get('/ical/{aptoID}', [
        'as' => 'import-iCalendar',
        'uses' => 'ICalendarController@getIcalendar'
    ])->where('aptoID', '[0-9]+');
    
    
    Route::get('/list-expenses', function(){
      App\Expenses::getTypesOrderned();
    });
    
    
    Route::get('forfait', function () {
        return redirect('https://miramarski.com/forfait/');
    });

    Route::get('/factura/{id}/{num}/{emial}', 'InvoicesController@donwload_external');
//    Route::get('/ical/importFromUrl', function () {
//      \Artisan::call('ical:import');
////      return redirect('/admin/reservas');
//    });