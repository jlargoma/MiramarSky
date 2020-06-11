<?php

use \Carbon\Carbon;

setlocale(LC_TIME, "ES");
setlocale(LC_TIME, "es_ES");
?>
@extends('layouts.admin-master')

@section('title') Administrador de reservas MiramarSKI @endsection

@section('externalScripts')
<link href="/assets/css/font-icons.css" rel="stylesheet" type="text/css"/>
<link href="/assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" type="text/css"
      media="screen">
<link rel="stylesheet" href="{{ asset('/frontend/css/components/daterangepicker.css')}}" type="text/css"/>
<style>
  #TT_FyTwLBdBd1arY8FUjfzjDjjjD6lUMWzFrd1dEZi5KkjI3535G {
    margin: 10px auto;
  }
</style>
<link href="{{ assetV('/css/backend/planning.css')}}" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.20/datatables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/3.3.0/js/dataTables.fixedColumns.min.js"></script>

@endsection
@section('content')
<?php if (!$mobile->isMobile()): ?>
  <div class="container-fluid  p-l-15 p-r-15 p-t-20 bg-white">
    <div class="row push-10">
      <div class="container">
        <div class="col-xs-12 text-center">
          <div class="col-md-4 col-md-offset-4 not-padding">
            <h2 style="margin: 0;">
              <b>Planning</b>
            </h2>
          </div>
        </div>
      </div>
    </div>
    <div class="row ">

      <div class="col-md-7">
        <button class="btn btn-success tab_books" type="button" data-type="checkin">
          <span class="bold">Check IN</span>
        </button>

        <button class="btn btn-primary tab_books" type="button" data-type="checkout">
          <span class="bold">Check OUT</span>
        </button>
        <div class="col-xs-12" id="resultSearchBook" style="display: none; padding-left: 0;"></div>
        <div class="col-xs-12 content-tables" style="padding-left: 0;padding-right: 0;">
          <div id="checkin" class="list_bookings">
            @include('backend.limpieza._checkin')
          </div>
          <div id="checkout" style="display:none;" class="list_bookings">
            @include('backend.limpieza._checkout')
          </div>
        </div>
      </div>
      <div class="col-md-5">
        <div class="calendar-mobile content-calendar" style="min-height: 515px;">
          <div class="col-xs-12 text-center sending" style="padding: 120px 15px;">
            <i class="fa fa-spinner fa-5x fa-spin" aria-hidden="true"></i><br>
            <h2 class="text-center">CARGANDO CALENDARIO</h2>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php else: ?>
  <div class="container-fluid  p-l-15 p-r-15 p-t-20 bg-white">
    <div class="row  p-l-15 p-r-15 p-t-20">
      <a class="pull-right" href="{{ url('logout') }}">Cerrar sesion</a>
    </div>
    <div class="row push-10">
      <div class="container">
        <div class="col-xs-12 text-center">
          <div class="col-md-4 col-md-offset-4 not-padding">
            <h2 style="margin: 0;">
              <b>Planning</b>
            </h2>
          </div>
        </div>
      </div>
    </div>
    <div class="row hidden-md hidden-lg">
      <div class="col-xs-2 push-10 text-center">
        <button class="btn btn-primary btn-sm calend" type="button" >
          <span class="bold"><i class="fa fa-calendar"></i></span>
        </button>
      </div>
    </div>
    <div class="row">
      <div class="col-md-8">

        <button class="btn btn-success btn-tables btn-cons" type="button" data-type="checkin">
          <span class="bold">Check IN</span>
        </button>

        <button class="btn btn-primary btn-tables btn-cons" type="button" data-type="checkout">
          <span class="bold">Check OUT</span>
        </button>
        <div class="col-xs-12" id="resultSearchBook" style="display: none; padding-left: 0;"></div>
        <div class="col-xs-12 content-tables" style="padding-left: 0;padding-right: 0;">
          @include('backend.planning._table', ['type'=> 'checkin'])
        </div>
      </div>
      <div class="col-md-4">
        <div class="row calendar-mobile content-calendar push-20" style="min-height: 515px;">
          <div class="col-xs-12 text-center sending" style="padding: 120px 15px;">
            <i class="fa fa-spinner fa-5x fa-spin" aria-hidden="true"></i><br>
            <h2 class="text-center">CARGANDO CALENDARIO</h2>
          </div>
        </div>
      </div>
  </div>
  </div>
<?php endif ?>

@endsection
@section('scripts')
<script src="/assets/js/notifications.js" type="text/javascript"></script>
<script>
$(document).ready(function () {
  $('.calend').click(function (event) {
  $('html, body').animate({
    scrollTop: $(".calendar-mobile").offset().top
  }, 2000);
  });
  // Cargar tablas de reservas
  $('.tab_books').click(function (event) {
  $('.list_bookings').toggle();
  });

  setTimeout(function () {
  $('.content-calendar').empty().load('/getCalendarMobile');
  }, 1500);
  
      var dataTable = $('.tableCheckIn').DataTable({
          "paging":   false,
          paging:  true,
          "columnDefs": [
            {"targets": [1,2,5], "orderable": false }
          ],
          order: [[ 6, "asc" ]],
          pageLength: 30,
          pagingType: "full_numbers",
          @if($isMobile)
            scrollX: true,
            scrollY: false,
            scrollCollapse: true,
            fixedColumns:   {
              leftColumns: 1
            },
          @endif

        });
         var dataTable = $('.tableCheckOut').DataTable({
          "paging":   false,
          paging:  true,
          "columnDefs": [
            {"targets": [1,2,5], "orderable": false }
          ],
          order: [[ 3, "asc" ]],
          pageLength: 30,
          pagingType: "full_numbers",
          @if($isMobile)
            scrollX: true,
            scrollY: false,
            scrollCollapse: true,
            fixedColumns:   {
              leftColumns: 1
            },
          @endif

        });
});

</script>

<style>

  .show-comment:hover{
    background-color: #6d5cae;
  }
  .show-comment:hover .comment-floating{
    display: block !important;
  }
</style>
@endsection