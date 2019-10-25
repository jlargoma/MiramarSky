@extends('new.layouts.master_withoutslider')
<link rel="stylesheet" href="{{ asset('/frontend/css/components/daterangepicker.css')}}" type="text/css" />
<link rel="stylesheet" href="{{ asset('/frontend/css/components/radio-checkbox.css')}}" type="text/css" />

<link rel="stylesheet" type="text/css" href="{{ asset('/frontend/css/normalize.css')}}" />
<link rel="stylesheet" type="text/css" href="{{ asset('/frontend/css/fourBoxSlider.min.css')}}" />
<link rel="stylesheet" type="text/css" href="{{ asset('/frontend/css/component.css')}}" />    
<script src="{{asset('/frontend/js/modernizr.custom.js')}}"></script>

<style type="text/css">

  .lSNext::before{
    content: ">";
    width: 0px;
    display: block;
    color: #fff;
    font-size: 4em;
    margin-left: -10px;
    margin-right: 0;
  }
  .lSPrev::before{
    content: "<";
    width: 0px;
    display: block;
    color: #fff;
    font-size: 4em;
    margin-right: -10px;
  }

  label{
    color: white!important
  }
  #content-form-book {
    padding: 40px 15px;
  }
  .content-colmun{
    margin-top: 1em;
  }
  .btn_reservar{
    margin-bottom: 4em;
  }
  .btn_reservar .menu-booking-apt img{
    width: 15em;
    min-width: auto;
    margin-right: 7em;
  }
  @media (max-width: 818px){
    .apartamento h1{
      padding-top: 2em;
    }
  }
  @media (max-width: 768px){
    .apartamento h1{
      padding-top: 2em;
    }
    .container-mobile{
      padding: 0!important
    }
    .container-image-box img{
      height: 180px!important;
    }

    #content-form-book {
      padding: 0px 0 40px 0
    }
    .daterangepicker {
      position: fixed!important;
      top: 15%!important;
    }
    .img{
      max-height: 530px;
    }
    .button.button-desc.button-3d{
      background-color: #4cb53f!important;
    }

    .img.img-slider-apto{
      height: 250px!important;
    }
  }
  .flex-control-nav.flex-control-paging{
    display: none;
  }
</style>

@section('metadescription') {{ $aptoHeading }} en Sierra Nevada @endsection
@section('title') {{ $aptoHeading }} en Sierra Nevada @endsection

@section('content')

<section class="page-title apartamento centered">
  <div class="container">
    <div class="content-box">
      <h1 class="center ">{{$aptoHeading}}</h1>
    </div>
  </div>
</section>

<section >
  <div class="container">
    <div class="row">
      <div class="col-md-7 img-colmun">
        <div class="clearfix">

          @if($slides)
          <ul id="image-gallery" class="gallery list-unstyled cS-hidden">
            @foreach($slides as $img)
            <li data-thumb="{{$img}}"> 
              <img src="{{$img}}" alt="{{$aptoHeading}}"/>
            </li>
            @endforeach
          </ul>
          @endif
        </div>
      </div>

      <div class="col-md-5 content-colmun" >
        @include('new.frontend.pages._infoEdificio')
        
      </div>
    </div>
        <br/>
        @include('new.frontend.pages._infoEdificio2')
</section>

                <?php if (!$mobile->isMobile()): ?>
                  <div id="content-form-book" class="row bg-bluesky push-30" style="display: none; background-image: url({{asset('/img/miramarski/esquiadores.png')}}); background-position: left bottom; background-repeat: no-repeat; background-size: 45%;">

                    <span style="padding: 0 5px; cursor: pointer; opacity: 1" class="close pull-right white text-white sm-m-r-20 sm-m-t-10">
                      <i class="fa fa-times"></i>
                    </span>

                    <div class="container clearfix" style="height: 645px;">
                      <div class="col-md-6 col-md-offset-3">
                        <div class="row" id="content-book-response">
                          <div class="front" style="max-height: 520px!important;">
                            <div class="col-xs-12">
                              <h3 class="text-center white">FORMULARIO DE RESERVA</h3>
                            </div>
                            <div id="form-content">
                              @include('new.frontend._formBook')
                            </div>
                          </div>
                          <div class="back" style="background-color: #3F51B5; max-height: 520px!important;">

                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php else: ?>
                  <div id="content-form-book" class="col-xs-12 bg-bluesky push-30" style="display: none;">

                    <span style="padding: 0 5px; cursor: pointer; opacity: 1" class="close pull-right white text-white sm-m-r-20 sm-m-t-10">
                      <i class="fa fa-times"></i>
                    </span>

                    <div class="container-mobile clearfix" style="margin-top: 10px; height: 133vh;">
                      <div class="col-md-6 col-md-offset-3">
                        <div class="row" id="content-book-response">
                          <div class="front" style="max-height: 520px!important;">
                            <div class="col-xs-12">
                              <h3 class="text-center white">FORMULARIO DE RESERVA</h3>
                            </div>
                            <div id="form-content">
                              @include('new.frontend._formBook')
                            </div>
                          </div>
                          <div class="back" style="background-color: #3F51B5; max-height: 520px!important;">

                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php endif; ?>
<section id="content">
  <div class="col-xs-12">
    @if ($mobile->isMobile()):
      @include('new.frontend.blocks.othersRoomsMobile')
    @else
      @include('new.frontend.blocks.othersRooms')
    @endif
  </div>


<div id="fixed-book" class="col-xs-12 text-center center bg-white" >
                  <div class="content">
                    <div class=" btn_reservar">
                      <img class="image_shine effect_shine" src="{{url('/img/miramarski/offer_tag_300.png')}}" style="max-width:125px;"/>
                    </div>
                    <button  class="button button-desc button-3d button-rounded showFormBook " style="">¡Reserva YA!</button>
                    </div>
                </div>
</section>



@endsection

@section('scripts')
<script src="{{ assetNew('/frontend/vendor/lightslider-master/src/js/lightslider.js')}}"></script>
<link rel="stylesheet" href="{{ assetNew('/frontend/vendor/lightslider-master/src/css/lightslider.css')}}" />
<script type="text/javascript" src="{{ assetNew('/frontend/js/form_booking.js')}}"></script>
@endsection