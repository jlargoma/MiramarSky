@extends('layouts.master')

@section('title') Alquiler apartamento Sierra Nevada @endsection

@section('moreScripts')
 <!--HTML5shiv Js-->
  <script src="{{ assetV('/frontend/vendor/camera-slider-master/js/modernizr-3.5.0.min.js')}}"></script>
  <!--Camera JS with Required jQuery Easing Plugin-->
  <script src="{{ assetV('/frontend/vendor/camera-slider-master/js/easing.min.js')}}" type="text/javascript"></script>
  <script src="{{ assetV('/frontend/vendor/camera-slider-master/js/camera.min.js')}}" type="text/javascript"></script>
  <!-- Bootstrap Js -->
  <script src="{{ assetV('/frontend/vendor/camera-slider-master/js/bootstrap.min.js')}}" type="text/javascript"></script>
  <!-- Custom JS --->
  <script src="{{ assetV('/frontend/vendor/camera-slider-master/js/plugins.js')}}"></script>
  <script src="{{ assetV('/frontend/vendor/lightslider-master/src/js/lightslider.js')}}"></script>
  <link rel="stylesheet" href="{{ assetV('/frontend/vendor/lightslider-master/src/css/lightslider.css')}}" />
  <script type="text/javascript" src="{{ assetV('/frontend/js/form_booking.js')}}"></script>
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
                  <h3 class="text-center white">FORMULARIO DE RESERVA</h3>
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
      <div class="container clearfix" style="padding: 20px 0;">
        <div class="row">
          <div class="col-md-4 col-xs-12">
            <div class="row" >
              <div class="col-xs-12">
                <h3 class="text-center white">FORMULARIO DE RESERVA</h3>
              </div>
              <div id="form-content">
                @include('frontend._formBook')
              </div>
            </div>
          </div>
          <div class="col-md-4 col-xs-12" id="content-book-response" style="padding: 0">
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
        <h2 style="font-size: 22px;">EL EDIFICIO MIRAMAR SKI…LUJO A PIE DE PISTA</h2>
    <figure class="mobil"><img src="{{ assetV('/frontend/images/home/edificio-1.jpg')}}" alt="Edificio Miramar Ski a pie de pista, zona baja"></figure>
      <div class="text">
        <p>
          El edificio Miramar Ski está situado en la Zona baja, <b>a 5 minutos andando de la plaza de Andalucía…</b> además puedes llegar y salir esquiando desde el propio edificio: <b>acceso directo a las pistas</b>
          <br/><b>Piscina climatizada, gimnasio, parking cubierto, taquilla guarda esquís.</b>Se trata de uno de <b>edificios más modernos de Sierra Nevada, de más reciente construcción.</b>
          <a href="{{ route('web.edificio') }}" class="text-center" title="Ver Más" style="color: #3f51b5;">Ver más..</a>
        </p>
      </div>
      </div>
    </div>
    <div class="bfh-img">
      <figure><img src="{{ assetV('/frontend/images/home/edificio-1.jpg')}}" alt="Edificio Miramar Ski a pie de pista, zona baja"></figure>
    </div>
  </div>
  <div class="box-feature-home  hidden-mobile">
    <div class="bfh-img">
      <figure><img src="{{ assetV('/frontend/images/home/edificio-2.jpg')}}" alt=""></figure>
    </div>
    <div class="bfh-text">
      <div class="box right">
      <h3>EXCELENTE SITUACION…..Y ADEMAS  SALES DE CASA ESQUIANDO!!</h3>
    <figure class="mobil"><img src="{{ assetV('/frontend/images/home/edificio-2.jpg')}}" alt="Edificio Miramar Ski"></figure>
      <div class="text">
        En Sierra Nevada es muy importante la ubicación para que disfrutes tus vacaciones. Te es uno de los edificios más modernos den el que te olvidas de coger el coche y ni siquiera remontes para llegar a tu apartamento.
        <b>Podrás Alquilar Alojamiento de diferentes tamaños según tus necesidades:</b>
      <ul>
        <li><b>Estudios</b> con capacidad <b>para 4 personas</b></li> 
        <li><b>Apartamentos</b> con una habitación, con capacidad <b>para 4/5 personas</b></li> 
        <li><b>Apartamentos</b> con dos habitación, con capacidad <b>para 6/8 personas</b></li> 
        <li><b>Apartamentos</b> con tres habitaciones, con capacidad <b>para 10/12 personas</b></li>
        <li><b>Apartamentos</b> con cuatro habitaciones, con capacidad <b>para 12/14 personas</b></li> 
      </ul>
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