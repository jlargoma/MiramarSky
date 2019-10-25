<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
Route::group(['prefix' => 'new'], function () {
  Route::get('/', 'HomeControllerNew@index');
  Route::get('/apartamentos/galeria/{apto}', 'HomeControllerNew@galeriaApartamento')->name('web.galeria');
  Route::get('/apartamentos/{apto}', 'HomeControllerNew@apartamento')->name('web.apto');
  Route::get('/fotos/{apto}', 'HomeControllerNew@apartamento')->name('web.fotos');
  Route::get('/edificio-miramarski-sierra-nevada', 'HomeControllerNew@edificio')->name('web.edificio');
  Route::get('/contacto', 'HomeControllerNew@contacto')->name('web.contact');
});