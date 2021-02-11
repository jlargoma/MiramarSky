<?php

    Route::get('/payments-forms-forfaits/{token}', 'ForfaitsItemController@paymentsForms')->name('front.payments.forfaits');
    Route::get('/payments-forms-forfaits-complete/{token}', 'ForfaitsItemController@paymentsFormsAll')->name('front.payments.forfaits.all');
    Route::get('/api/forfaits/thansk-you/{key_token}', 'ForfaitsItemController@thansYouPayment')->name('payland.thanks.forfait');
    Route::get('/api/forfaits/error/{key_token}', 'ForfaitsItemController@errorPayment')->name('payland.error.forfait');
    Route::get('/api/forfaits/process/{key_token}', 'ForfaitsItemController@processPayment')->name('payland.process.forfait');
    Route::post('/api/forfaits/process/{key_token}', 'ForfaitsItemController@processPayment')->name('payland.process.forfait');
    Route::get('/api/forfaits/token', 'ForfaitsItemController@getUserAdmin');
    Route::post('/api/forfaits/getPayments', 'ForfaitsItemController@getPayments');
    Route::post('/api/forfaits/createPayment', 'ForfaitsItemController@createPayment');
    Route::post('/api/forfaits/createPaylandsUrl', 'ForfaitsItemController@createPaylandsUrl');
    Route::post('/api/forfaits/quickOrders', 'ForfaitsItemController@quickOrders');
    Route::post('/api/forfaits/changeStatus', 'ForfaitsItemController@changeStatus');
    Route::get('/api/forfaits/class', 'ForfaitsItemController@api_getClasses');
    Route::get('/api/forfaits/categ', 'ForfaitsItemController@api_getCategories');
    Route::get('/api/forfaits/items/{id}', 'ForfaitsItemController@api_items');
    Route::post('/api/forfaits/cancelItem', 'ForfaitsItemController@cancelItem');
    Route::post('/api/forfaits/saveCart', 'ForfaitsItemController@saveCart');
    Route::post('/api/forfaits/checkout', 'ForfaitsItemController@checkout');
    Route::post('/api/forfaits/forfaits', 'ForfaitsItemController@getForfaitUser');
    Route::post('/api/forfaits/insurances', 'ForfaitsItemController@getInsurances');
    Route::get('/api/forfaits/bookingData/{bID}/{uID}', 'ForfaitsItemController@bookingData');
    Route::get('/api/forfaits/getCurrentCart/{bID}/{uID}', 'ForfaitsItemController@getCurrentCart');
    Route::get('/api/forfaits/sedOrder/{bID}/{uID}/{type}', 'ForfaitsItemController@sendClientEmail');
    Route::post('/api/forfaits/sendOrder', 'ForfaitsItemController@sendOrdenToClient');
    Route::post('/api/forfaits/showOrder', 'ForfaitsItemController@showOrder');
    Route::post('/api/forfaits/sendConsult', 'ForfaitsItemController@sendEmail');
    Route::get('/api/forfaits/getSeasons', 'ForfaitsItemController@getForfaitSeasons');
    Route::post('/api/forfaits/createNewOrder', 'ForfaitsItemController@createNewOrder');
    Route::post('/api/forfaits/sendClassToAdmin', 'ForfaitsItemController@sendClassToAdmin');
    Route::post('/api/forfaits/getFFOrders', 'ForfaitsItemController@getFFOrders');
    Route::post('/api/forfaits/remove-order', 'ForfaitsItemController@removeOrder');
    Route::post('/api/forfaits/orders-history', 'ForfaitsItemController@ordersHistory');
    Route::post('/api/forfaits/get-payment', 'ForfaitsItemController@getPayment');
    Route::post('/api/forfaits/setOrderStatus', 'ForfaitsItemController@setOrderStatus');
//    Route::get('/aaaa', 'ForfaitsItemController@aaaa');
    
    
    /**
 * FORFAITS
 */
Route::group(['middleware' => ['auth','role:admin|subadmin'], 'prefix' => 'admin/forfaits',], function () {
  Route::get('/orders', 'ForfaitsItemController@listOrders');
  Route::get('/balance', 'ForfaitsItemController@getBalance');
  Route::post('/open', 'ForfaitsItemController@getOpenData');
  Route::get('/edit/{id}', 'ForfaitsItemController@edit');
  Route::post('/upd', 'ForfaitsItemController@update');
  Route::get('/createItems', 'ForfaitsItemController@createItems');
  Route::get('/getBookItems/{bookingID}', 'ForfaitsItemController@getBookingFF');
  Route::post('/loadComment', 'ForfaitsItemController@loadComment');
  Route::post('/sendBooking', 'ForfaitsItemController@sendBooking');
  Route::get('/resume/{id}', 'ForfaitsItemController@getResume');
  Route::get('/resume-by-book/{id}', 'ForfaitsItemController@getResumeBy_book');
  Route::get('/resent_thansYouPayment/{id}', 'ForfaitsItemController@resent_thansYouPayment');
  Route::get('/getBookData/{id}', 'ForfaitsItemController@getBookData');
  Route::get('/changeBook/{id}/{ffID}', 'ForfaitsItemController@changeBook');
  Route::get('/sendFFExpress/{order_id}/{ffID}', 'ForfaitsItemController@sendFFExpress');
  Route::get('/{class?}', 'ForfaitsItemController@index');
});