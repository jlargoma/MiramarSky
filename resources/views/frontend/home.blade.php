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


  #form-content .col-md-3.col-xs-3{
    width: 25% !important;
    margin-top: 1em;
  }
  #form-content  .col-md-6.col-xs-6 {
    width: 50% !important;
    margin: 1em 0;
}
  #form-content .radio-style-1-label:before, .radio-style-2-label:before, .radio-style-3-label:before {
    margin-bottom: 6px;
}
#form-book-apto-lujo label.col-md-12.luxury.white{
  text-align: left;
}
#form-content label.col-xs-12.col-md-12.text-left.parking.white,
#form-content label.col-md-12.luxury.white{
    margin-left: -15px;
    margin-top: 1em;
}
</style>
  <div id="content-book" class="col-xs-12 bg-bluesky" style="display: none;">

    <span style="padding: 0 5px; cursor: pointer; opacity: 1" id="close-form-book" class="close pull-right white text-white sm-m-r-20 sm-m-t-10">
                      <i class="fa fa-times"></i>
                    </span>

                    <div class="container-mobile clearfix" style="margin-top: 10px; height: 133vh;">
                      <div class="col-md-6 col-md-offset-3">
                        <div class="row" id="content-book-response">
                          <div class="front" style="max-height: 520px!important;">
                            <div class="col-xs-12">
                              <h3 class="text-center white">CALCULA TU PRECIO</h3>
                            </div>
                            <div id="form-content">
                              @include('frontend._formBook')
                            </div>
                          </div>
                          <div class="back" style="background-color: #3F51B5; max-height: 520px!important;">

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