<?php

use \Carbon\Carbon;

setlocale(LC_TIME, "ES");
setlocale(LC_TIME, "es_ES");
?>
@extends('layouts.admin-master')

@section('title') Contabilidad  @endsection

@section('externalScripts') 
<style type="text/css">
  .after-banks{
    min-height: 60em;
  }
</style>
@endsection

@section('content')
<div class="container padding-5 sm-padding-10">
  <div class="row bg-white">
    <div class="col-md-12 col-xs-12">

      <div class="col-md-3 col-md-offset-3 col-xs-12">
        <h2 class="text-center">
          BANCO 
        </h2>
      </div>

    </div>
  </div>
</div>
<div class="container-fluid">
  <div class="row bg-white push-30">
    <div class="col-lg-8 col-md-10 col-xs-12 push-20">
      @include('backend.sales._button-contabiliad')
    </div>
  </div>

  <div class="row bg-white">
    <div class="col-md-12 col-xs-12 contentBank">

      <iframe 
        src="https://www.afterbanks.com/appmain/static/?startAt=globalPosition#" 
        width="100%"
        class="after-banks"
        ></iframe>


    </div>
  </div>
</div>

@endsection	
