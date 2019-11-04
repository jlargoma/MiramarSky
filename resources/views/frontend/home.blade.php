@extends('layouts.master')

@section('title') Alquiler apartamento Sierra Nevada @endsection

@section('moreScripts')
<script type="text/javascript" src="{{ assetV('/js/scripts-home.js')}}"></script>
  @endsection 
@section('content')
<section id="content" style="padding: 0;    clear: both;">

  <div class="content-wrap notoppadding" style="padding: 0;">

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
</style>
<!-- MOBILE -->
<section class="page-section degradado-background1 no-padding" style="letter-spacing: 0;line-height: 1;color: #fff!important;">

  <div class="row degradado-background1" style="">

    <div id="content-book" class="container-mobile clearfix push-10" style="display: none; height: 125vh;">
      <div id="close-form-book"
           style="position: absolute; top: 20px; right: 10px; z-index: 50;  cursor: pointer;">
        <span class="white text-white"><i class="fa fa-times fa-4x"></i></span>
      </div>
      <div class="container clearfix" style="padding: 20px;">
        <div class="row">
          <div class="col-md-4 col-xs-12">
            <div class="row" >
              <div class="col-xs-12">
                <h3 class="text-center white">CALCULA TU PRECIO</h3>
              </div>
              <div id="form-content">
                @include('frontend._formBook')
              </div>
            </div>
          </div>
          <div class="col-md-4 col-xs-12" id="content-book-response">
            <div class="col-xs-12 back" style="background-color: #3330c8;"></div>
          </div>
          <div class="col-md-4 col-xs-12" id="content-book-payland" style="padding: 0">
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
      <img src="{{$edificio['imagen_1']}}" alt="Edificio Miramar Ski a pie de pista, zona baja">
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
      <figure><img src="{{$edificio['imagen_1']}}" alt="Edificio Miramar Ski a pie de pista, zona baja"></figure>
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
        <figure><img src="{{$edificio['imagen_2']}}" alt=""></figure>
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
        <img src="{{$edificio['imagen_2']}}" alt="Edificio Miramar Ski">
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