@extends('layouts.master')

@section('title') Alquiler apartamento Sierra Nevada @endsection

@section('content')
@include('frontend.slider')
<section id="content" style="padding: 0;    clear: both;">

  <div id="content-form-book" class="content-wrap notoppadding" style="padding: 0;">

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
                  @include('frontend._formBook_static')
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


<style>

@media only screen and (max-width: 426px) {
  .form-group.col-sm-12.col-xs-6.col-md-5.apto-type,
  .form-group.col-sm-12.col-xs-6.col-md-5.apto-lujo, {
      clear: both;
      margin: 1em auto;
  }
  .apto-type .col-md-3.col-xs-6{
    max-width: 24%;
  }
  .apto-lujo .col-md-6{
    max-width: 50%;
    float: left;
    margin: 1em auto;
  }
}
</style>

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
      <img class="loadJS" src="/img/firts-min.png" data-src="{{getCloudfl($edificio['imagen_1'])}}" alt="Edificio Miramar Ski a pie de pista, zona baja">
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
      <figure><img class="loadJS" src="/img/firts-min.png" data-src="{{getCloudfl($edificio['imagen_1'])}}" alt="Edificio Miramar Ski a pie de pista, zona baja"></figure>
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
        <figure><img class="loadJS" src="/img/firts-min.png" data-src="{{getCloudfl($edificio['imagen_2'])}}" alt=""></figure>
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
        <img class="loadJS" src="/img/firts-min.png" data-src="{{getCloudfl($edificio['imagen_2'])}}" alt="Edificio Miramar Ski">
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
<div class="show-mobile">
  @include('frontend.blocks.othersRoomsMobile')
</div>
<div class="hidden-mobile">
  @include('frontend.blocks.othersRooms')
</div>
@include('frontend.blocks.services')
@include('frontend.blocks.info-links')
@endsection

@section('scripts')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/css/bootstrap.min.css" integrity="sha384-SI27wrMjH3ZZ89r4o+fGIJtnzkAnFs3E4qz9DIYioCQ5l9Rd/7UAa8DHcaL8jkWt" crossorigin="anonymous">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/js/bootstrap.min.js" integrity="sha384-3qaqj0lc6sV/qpzrc1N5DC6i1VRn/HyX4qdPaiEFbn54VjQBEU341pvjz7Dv3n6P" crossorigin="anonymous" ></script>
@endsection