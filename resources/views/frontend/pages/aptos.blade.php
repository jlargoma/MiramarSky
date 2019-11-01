@extends('layouts.master_withoutslider')
<link rel="stylesheet" href="{{ asset('/frontend/css/components/daterangepicker.css')}}" type="text/css" />
<link rel="stylesheet" href="{{ asset('/frontend/css/components/radio-checkbox.css')}}" type="text/css" />

<link rel="stylesheet" type="text/css" href="{{ asset('/frontend/css/normalize.css')}}" />
{{--<link rel="stylesheet" type="text/css" href="{{ asset('/frontend/css/fourBoxSlider.css')}}" />--}}
<link rel="stylesheet" type="text/css" href="{{ asset('/frontend/css/fourBoxSlider.min.css')}}" />
<link rel="stylesheet" type="text/css" href="{{ asset('/frontend/css/component.css')}}" />    
<script src="{{asset('/frontend/js/modernizr.custom.js')}}"></script>

<style type="text/css">
   #primary-menu ul li  a{
      color: #3F51B5!important;
   }
   #primary-menu ul li  a div{
      text-align: left!important;
   }
   #primary-menu-trigger i.fa.fa-bars{
      margin-top: 15px;
   }

   label{
      color: white!important
   }
   #content-form-book {
      padding: 40px 15px;
   }
   @media (max-width: 768px){
      .container-mobile{
         padding: 0!important
      }
      #primary-menu{
         padding: 40px 15px 0 15px;
      }
      #primary-menu-trigger {

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
  #content p {
    line-height: 1.8;
    margin: 0;
}</style>
@section('metadescription') {{ $aptoTitle }} en Sierra Nevada @endsection
@section('title') {{ $aptoTitle }} en Sierra Nevada @endsection

@section('content')
    @include('frontend.slider_transparent')
   <section id="content">

      <div class="container container-mobile clearfix push-0">
         <div class="row">
               <h1 class="center hidden-sm hidden-xs psuh-20">{{ $aptoTitle }}</h2>
               <h1 class="center hidden-lg hidden-md green push-10">{{ $aptoTitle }}</h2>
         </div>
      </div>
      
      <div class="row clearfix  push-30">
         <div class="col-xs-12 col-md-6">
            <div class="fslider" data-animation="fade" data-thumbs="flase" data-arrows="true" data-speed="1200" >
               <div class="flexslider">
                  <div class="slider-wrap">
                     <?php foreach ($slides as $slide): ?>
                        <div class="slide" data-thumb="{{ $slide->file_rute }}/thumbnails/{{ $slide->file_name }}">
                           <a>
                              <img class="img img-slider-apto" src="{{ $slide->file_rute }}/{{ $slide->file_name }}"  title="{{ $aptoTitle }}" >
                           </a>
                        </div>
                     <?php endforeach ?>
                  </div>
               </div>
            </div>
         </div>

         <div class="col-xs-12 col-md-6 clearfix" style="text-align: initial;">

            <div class="col-md-12 push-0 not-padding-mobile">
              {!! $description !!}
            </div>
            
            <?php if (!$mobile->isMobile()): ?>
                                
                                    <div class="col-xs-4 col-md-4">
                                        <nav class="codrops-demos" style="padding:0; right:0; position: absolute;text-align:left;">
                                            <a href="#" class="menu-booking-apt" style="width:100%; margin:0; padding:0; border:none;/*background:rgba(0,0,0,0.5); padding:0.8em 1.1em; text-align:center; font-size:0.8vw;*/"><img class="image_shine effect_shine" src="{{url('/img/miramarski/offer_tag_300.png')}}" style="max-width:200px;"/></a>
                                        </nav>
                                    </div>
                                    <div class="col-xs-8 col-md-8 text-left">
                                        <button id="showFormBook" class="button button-desc button-3d button-rounded bg-bluesky center white" >¡Reserva YA!</button>
                                    </div>
                            
            <?php endif; ?>

         </div>
      </div>
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
      <?php endif; ?>

      <div class="col-xs-12">
         <h3 class="text-center font-w300">
            OTROS <span class="green font-w800">APARTAMENTOS</span>
         </h3>
      </div>
      <div class="col-xs-12">
         <?php if ($aptoLst): foreach ($aptoLst as $apto): ?>
               <div class="col-md-3 col-sm-6  col-12 not-padding-mobile hover-effect push-20">
                  <a href="{{url('/apartamentos')}}/{{$apto->name}}">
                     <div class="col-xs-12 not-padding  container-image-box push-mobile-20">
                        <div class="col-xs-12 not-padding push-0">
                           
                           <img class="img-responsive" src="{{$apto->file_rute}}/{{$apto->file_name}}"  alt="{{$apto->title}}"/>
                        </div>
                        <div class="col-xs-12 not-padding text-right overlay-text">
                           <h2 class="font-w600 center push-10 text-center text white font-s24 hvr-reveal" >
                              <?php echo strtoupper($apto->title ) ?>
                           </h2>
                        </div>
                     </div>
                  </a>
               </div>
         <?php endforeach; endif; ?>
      </div>


      <div id="fixed-book" class="col-xs-12 text-center center hidden-lg hidden-md bg-white" style="position: fixed; bottom: 0px; width: 100%; background-color: #FFF!important; padding: 15px 15px 15px 0;">
                    <div class="col-xs-4">
                        <nav class="codrops-demos" style="padding:0; right:0; position: absolute;text-align:left;">
                            <a href="#" class="menu-booking-apt" style="width:100%; margin: 14px 0 0 0; padding:0; border:none;/*background:rgba(0,0,0,0.5); padding:0.8em 1.1em; text-align:center; font-size:0.8vw;*/"><img class="image_shine effect_shine" src="{{url('/img/miramarski/offer_tag_300.png')}}" style="max-width:125px;"/></a>
                        </nav>
                    </div>
                    <div class="col-xs-8" style="padding-left:0px;">
                        <button id="showFormBook" class="button button-desc button-3d button-rounded bg-bluesky center white" style="background-color: #4cb53f!important; width: 98%; padding: 24px 10px; margin: 0px auto;z-index: 90">¡Reserva YA!</button>
                    </div>
      </div>
   </section>


   
@endsection