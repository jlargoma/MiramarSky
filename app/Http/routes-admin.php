<?php

/**
 * GENERAL
 */
Route::group(['middleware' => 'authAdmin','prefix' => 'admin',], function () {
    Route::get('/galleries', 'RoomsController@galleries');
    //LIMPIEZA
    Route::get('/limpieza', 'LimpiezaController@index');
    
    Route::get('/forfaits/deleteRequest/{id}','FortfaitsController@deleteRequest');
    Route::get('/reservas/ff_status_popup/{id}', 'BookController@getBookFFData');
    Route::get('/reservas/ff_change_status_popup/{id}/{status}', 'BookController@updateBookFFStatus');
});
/**
 * FORFAITS
 */
Route::group(['middleware' => 'authAdmin','prefix' => 'admin/aptos',], function () {
  Route::get('/edit-room-descript/{id}','RoomsController@editRoomDescript');
  Route::get('/edit-descript/{id}', 'RoomsController@editDescript');
  Route::post('/edit-room-descript', 'RoomsController@updRoomDescript');
  Route::post('/edit-descript', 'RoomsController@updDescript');
});
Route::group(['middleware' => 'authAdmin','prefix' => 'admin/forfaits',], function () {
Route::get('/{class?}', 'ForfaitsItemController@index');
Route::get('/edit/{id}', 'ForfaitsItemController@edit');
Route::post('/upd', 'ForfaitsItemController@update');
Route::get('/createItems', 'ForfaitsItemController@createItems');
Route::get('/getBookItems/{bookingID}', 'ForfaitsItemController@getBookingFF');
Route::post('/loadComment', 'ForfaitsItemController@loadComment');
Route::post('/sendBooking', 'ForfaitsItemController@sendBooking');
});
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