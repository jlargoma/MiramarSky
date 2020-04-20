<?php
if (!isset($oContents)) $oContents = new App\Contents();
$sliderHome = $oContents->getContentByKey('slider_home',true);
?>

  <style>
div#carouselHome {
    height: 70em !important;
}
.carousel-item{
  background-repeat: no-repeat;
  background-size: cover;
  background-position: center;
}
.img-ccarousel{
  visibility: hidden;
   
}
  </style>

  
  
   <!--style="background-image: url('');"-->
<div id="carouselHome" class="carousel slide" data-ride="carousel">
  <div class="carousel-inner">
  <?php 
  $first = 'active';
  for($i=1; $i<4; $i++): ?>
     @if($sliderHome['imagen_'.$i])
     <div class="carousel-item {{$first}}" data-img="url('{{getCloudfl($sliderHome['imagen_'.$i])}}')" data-img2="url('{{getCloudfl($sliderHome['imagen_'.$i.'_mobile'])}}')">

      <div class="carousel-caption" @if(!$first) style="display:none;" @endif>
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
  
