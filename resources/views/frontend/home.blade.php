@extends('layouts.master')

@section('title') Alquiler apartamento Sierra Nevada @endsection

@section('content')
@include('frontend.slider')
<section id="content" style="padding: 0;    clear: both;">

  <div id="content-form-book" class="content-wrap notoppadding" style="padding: 0;">

    <?php if (!$mobile->isMobile()): ?>
    <!-- DESKTOP -->
    <div class="row clearfix" style="background-color: #3F51B5;">
      <div id="close-form-book" style="position: absolute; top: 20px; right: 10px; z-index: 50;  cursor: pointer;">
        <span class="white text-white"><i class="fa fa-times fa-4x"></i></span>
      </div>
      <div id="content-book" class="container-fluid clearfix push-10" style="display: none; min-height:675px;">
        <div class="container-fluid clearfix" style="padding: 20px 0;">
          <div class="row">
            <div class="col-md-4">
              <div class="row" >
                <div class="col-xs-12">
                  <h3 class="text-center white">CALCULA TU PRECIO</h3>
                </div>
                <div id="form-content">
                  @include('frontend._formBook')
                </div>
              </div>
            </div>
            <div class="col-md-4" id="content-book-response">
              <div class="row back" style="background-color: #3F51B5;"></div>
            </div>
            <div class="col-md-4" id="content-book-payland">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php else: ?>
<style>

  #content-book-payland iframe{
    min-height: 378px!important;
    overflow: hidden!important;
  }
  .booking_mobile{
    display: block !important;
  }
  .booking_box{
    padding: 2em !important;
    margin: -2%;
    width: 104% !important;
    background-color: #3330c8;
  }
  
</style>
<!-- MOBILE -->
<section class="page-section degradado-background1 no-padding" style="letter-spacing: 0;line-height: 1;color: #fff!important;">

  <div class="row degradado-background1" style="">

    <div id="content-book" class="container-mobile clearfix push-10" style="display: none; height: 125vh;">
      <div id="close-form-book"
           style="position: absolute; top: 20px; right: 10px; z-index: 50;  cursor: pointer;">
        <span class="white text-white"><i class="fa fa-times fa-4x"></i></span>
      </div>
      <div class="clearfix" style="padding: 20px;">
        <div class="row booking_mobile">
          <div style="padding: 20px;">
              <h3 class="text-center white">CALCULA TU PRECIO</h3>
              <div id="form-content">
                @include('frontend._formBook')
              </div>
          </div>
          <div class="col-xs-12" id="content-book-response">
            <div class="col-xs-12 back booking_box" ></div>
          </div>
          <div class="col-xs-12 booking_box" id="content-book-payland">
          </div>
        </div>
      </div>
    </div>
  </div>

</section>
<?php endif; ?>
<!-- blog y edificio (sierra nevada) -->    
<section class="feature-home">
  <div class="box-feature-home">
    <div class="bfh-text">
      <div class="box">
        <h2 style="font-size: 22px;">{{$edificio['title']}}</h2>
        
        
    <figure class="mobil">
      @if($edificio['video_1'])
        <video width="100%" autoplay>
          <source src="{{$edificio['video_1']}}" type="video/mp4">
        </video>
      @else
      <img src="{{getCloudfl($edificio['imagen_1'])}}" alt="Edificio Miramar Ski a pie de pista, zona baja">
      @endif
    </figure>
      <div class="text">
        {!! $edificio['content_1'] !!}
      </div>
      </div>
    </div>
    <div class="bfh-img">
      @if($edificio['video_1'])
        <video width="470px" autoplay>
          <source src="{{$edificio['video_1']}}" type="video/mp4">
        </video>
      @else
      <figure><img src="{{getCloudfl($edificio['imagen_1'])}}" alt="Edificio Miramar Ski a pie de pista, zona baja"></figure>
      @endif
    </div>
  </div>
  <div class="box-feature-home  hidden-mobile">
    <div class="bfh-img">
      @if($edificio['video_2'])
        <video width="470px" autoplay>
          <source src="{{$edificio['video_2']}}" type="video/mp4">
        </video>
      @else
        <figure><img src="{{getCloudfl($edificio['imagen_2'])}}" alt=""></figure>
      @endif
    </div>
    <div class="bfh-text">
      <div class="box right">
      <h3>{{$edificio['title_2']}}</h3>
    <figure class="mobil">
      @if($edificio['video_2'])
        <video width="100%" autoplay>
          <source src="{{$edificio['video_2']}}" type="video/mp4">
        </video>
      @else
        <img src="{{getCloudfl($edificio['imagen_2'])}}" alt="Edificio Miramar Ski">
      @endif
      </figure>
      <div class="text">
       {!! $edificio['content_2'] !!}
      </div>
      <div class="button menu-booking">
            <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>RESERVAR
      </div>
      </div>
    </div>
  </div>
    
</section>
@if ($mobile->isMobile()):
  @include('frontend.blocks.othersRoomsMobile')
@else
  @include('frontend.blocks.othersRooms')
@endif
@include('frontend.blocks.services')
@include('frontend.blocks.info-links')
@endsection

@section('scripts')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/css/bootstrap.min.css" integrity="sha384-SI27wrMjH3ZZ89r4o+fGIJtnzkAnFs3E4qz9DIYioCQ5l9Rd/7UAa8DHcaL8jkWt" crossorigin="anonymous">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/js/bootstrap.min.js" integrity="sha384-3qaqj0lc6sV/qpzrc1N5DC6i1VRn/HyX4qdPaiEFbn54VjQBEU341pvjz7Dv3n6P" crossorigin="anonymous" async="async"></script>
@endsection