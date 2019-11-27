<?php
if (!isset($oContents)) $oContents = new App\Contents();
$sliderHome = $oContents->getContentByKey('slider_home',true);
?>

  <style>
div#carouselHome {
    height: 70em !important;
}
  </style>

  
<div id="carouselHome" class="carousel slide" data-ride="carousel">
  <div class="carousel-inner">
  <?php 
  $first = 'active';
  for($i=1; $i<4; $i++): ?>
     @if($sliderHome['imagen_'.$i])
    <div class="carousel-item {{$first}}">
      <img class="img-responsive" src="{{getCloudfl($sliderHome['imagen_'.$i])}}" alt="First slide" >
       <div class="carousel-caption">
          <div class="slider-text-1">
          <h2 class="cp-title2">{{$sliderHome['title_'.$i]}}</h2>
        </div>
        <div class="slider-text-2">
          <div class="text">
          {!! $sliderHome['content_'.$i] !!}  
          </div>
        </div>
        <div class="slider-text-3">
          <button class="main menu-booking btn_reservar">
            <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>RESERVAR
          </button>
        </div>
        </div>
    </div>
     @endif
   <?php 
   $first = '';
   endfor; 
   ?>
   
  </div>
 <a class="carousel-control-prev" href="#carouselHome" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#carouselHome" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>
  
