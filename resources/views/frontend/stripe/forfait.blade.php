<?php
setlocale(LC_TIME, "ES");
setlocale(LC_TIME, "es_ES");

use \Carbon\Carbon;
?>
@extends('layouts.master_onlybody')

@section('title')Pago realizado @endsection

@section('content')
<style>
  html {
    overflow: hidden;
  }
    .logo-payments{
    width: 18em;
    margin: 2em auto;
  }
</style>
<section class="section full-screen nobottommargin" style="background-color: #3f51b5;margin: 0; padding: 0;height: 100vh;" >
  <div class="container clearfix" style="height: 100vh;">
    <div class="row" style="margin-top: 5em;">
      <?php if (env('APP_APPLICATION') == "riad"): ?>
        <div class="logo-payments">
          <img src="{{ asset('img/riad/logo_riad_b.png') }}" alt="Riad Puertas del Albaicín" width="100%">
        </div>
      <?php endif; ?>
    </div>
    <div class="text-center">
      <h2 class="text-center ls1" style="line-height: 1; color: white">
        ¡Muchas gracias por confiar en nosotros!
      </h2>
      <br>
      <p style="line-height: 1; color: white">
        Su pago fue registrado con éxito<br><br>
	Un saludo
      </p>
    </div>
  </div>
</section>
@endsection