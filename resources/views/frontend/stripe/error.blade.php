<?php
setlocale(LC_TIME, "ES");
setlocale(LC_TIME, "es_ES");

use \Carbon\Carbon;
?>
@extends('layouts.master_onlybody')

@section('title')Graicas por tu reserva @endsection

@section('content')
<style>
  html {
    overflow: hidden;
  }
</style>
<section class="section full-screen nobottommargin" style="background-color: #3f51b5;margin: 0; padding: 0;" >
  <div class="container clearfix">
    <div class="row" style="margin-top: 5em;">
      <?php if (env('APP_APPLICATION') == "riad"): ?>
        <div class="col-md-2 col-md-offset-5">
          <img src="{{ asset('img/riad/logo_riad.png') }}" alt="" style="width: 100%">
        </div>
      <?php endif; ?>
    </div>
    <div class="text-center">
      <h2 class="text-center ls1" style="line-height: 1; color: white">
        Ups! A ocurrido un error con su pago.
      </h2>
      <br>
      <p style="line-height: 1; color: white">
        Por favor, inténtelo nuevamente<br/><br/>
        O comuníquese con nosotros.
      </p>
    </div>
  </div>
</section>
@endsection