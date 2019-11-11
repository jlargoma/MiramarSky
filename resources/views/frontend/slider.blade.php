<?php
if (!isset($oContents)) $oContents = new App\Contents();
$sliderHome = $oContents->getContentByKey('slider_home');
?>

  <style>
    
   .camera_wrap {
  margin-top: -140px;
  margin-bottom: 0 !important;
  } 
  .sliderContent{
      margin-top: 160px;
  }
.slider-text-1 {
  width: 100%;
  -webkit-animation-name: text-efect-1; /* Safari 4.0 - 8.0 */
  -webkit-animation-duration: 2s; /* Safari 4.0 - 8.0 */
  animation-name: text-efect-1;
  animation-duration: 2s;
  text-align: center;

}
.slider-text-2 {
  animation-delay: 1s;
  width: 100%;
  -webkit-animation-name: text-efect-2;
  -webkit-animation-duration: 2s;
  animation-name: text-efect-2;
  text-align: center;
  animation-fill-mode: forwards;
  padding: 1em;
  opacity: 0;
  z-index: 6;
  /* text-shadow: rgb(0, 0, 0) 1px 1px; */
  position: relative;
  font-size: 25px;
  font-family: 'Ek Mukta',sans-serif;
  line-height: 30px;
  color: #3f51b5;
  margin-bottom: 16px;
  /* background-color: rgba(54, 73, 92, 0.45); */

}

.slider-text-3 {
  animation-delay: 2s;
  width: 100%;
  -webkit-animation-name: text-efect-3; /* Safari 4.0 - 8.0 */
  -webkit-animation-duration: 2s; /* Safari 4.0 - 8.0 */
  animation-name: text-efect-3;
  text-align: center;
  animation-fill-mode: forwards;
  padding: 1em;
  opacity: 0;
}
.slider-text-3 button.main{
  background-color: rgb(89, 186, 65);
  padding: 1.2em;
  font-size: 1.43em;
  border-color: rgb(89, 186, 65);
  box-shadow: 4px 2px 4px rgb(56, 101, 45);
  color: #fff;
}
.slider-text-3 button.main .fa{
  margin-right: 7px;
}

.slider-text-1 h2{
    width: 100%;
    font-family: 'miramar';
    font-size: 80px;
    color: #FFFFFF;
    text-shadow: rgb(0, 0, 0) 1px 1px;
    white-space: normal;
    line-height: 80px;
    font-weight: bold;
    text-align: center;
    font-size: 42px;
    line-height: 1;
}
.slider-text-2 .text{ 
    margin: auto;
    white-space: nowrap;
    font-family: 'miramar';
    font-size: 24px;
    color: #FFFFFF;
    text-shadow: rgb(0, 0, 0) 1px 1px;
    white-space: normal;
    text-align: center !important;
    line-height: 1;
        max-width: 425px;
        font-weight: 400;
    }
.slider-text-2 .text b{     
  display: block;
}
.camera_prev, .camera_next {
    right: 0;
    opacity: 1 !important;
    background-color: transparent !important;
}
.camera_prev span, .camera_next span {
  display:none;
   }
.camera_prev:hover, .camera_next:hover {
  background-color: transparent !important;
   }
.camera_next::before{
    content: ">";
    width: 0px;
    display: block;
    color: #fff;
    font-size: 4em;
    margin-left: -10px;
    margin-right: 0;
}
.camera_prev::before{
    content: "<";
width: 0px;
    display: block;
    color: #fff;
    font-size: 4em;
    margin-right: -10px;
}
.camera_prev:hover::before, .camera_next:hover::before {
  font-size: 4.5em;
  margin-top: -5px;
}
.camera_commands{
  display: none;
}
.cameraCont, .cameraContents {
    background-color: rgba(63, 80, 181, 0.11);
}
.camera_bar{
  display: none;
}
.camera_pag {
    margin-top: -65px;
    margin-right: 1em;
}
.camera_wrap .camera_pag .camera_pag_ul{
  text-align: center;
}
/* Safari 4.0 - 8.0 */
@-webkit-keyframes text-efect-1 {
  0% {opacity: 1;margin-left: -30%;}
  100% {opacity: 1; margin-left: 0;}
}

/* Standard syntax */
@keyframes text-efect-1 {
  0% {opacity: 1;margin-left: -30%;}
  100% {opacity: 1; margin-left: 0;}
}
/* Safari 4.0 - 8.0 */
@-webkit-keyframes text-efect-2 {
  0% {opacity: 0;margin-left: 30%;}
  100% {opacity: 1; margin-left: 0;}
}

/* Standard syntax */
@keyframes text-efect-2 {
  0% {opacity: 0;margin-left: 30%;}
  100% {opacity: 1; margin-left: 0;}
}
/* Safari 4.0 - 8.0 */
@-webkit-keyframes text-efect-3 {
  0% {opacity: 0;margin-top: -30%;}
  100% {opacity: 1; margin-left: 0;}
}

/* Standard syntax */
@keyframes text-efect-3 {
  0% {opacity: 0;margin-top: -30%;}
  100% {opacity: 1; margin-left: 0;}
}
@media (min-width: 990px){
  .slider-text-1 h2{
    font-size: 63px;
  }
  .slider-text-2 .text{
    font-size: 36px;
  }
  .slider-text-3 button.main{
    padding: 1em;
    font-size: 2.3em;
    font-weight: 600;
  }
}
  </style>
 <div class="camera_wrap banner" id="home_camera">
   <?php for($i=1; $i<4; $i++): ?>
   @if($sliderHome['imagen_'.$i])
    <div data-src="{{$sliderHome['imagen_'.$i]}}">
      <img class="img-responsive">
      <div class="sliderContent">
        <div class="slider-text-1">
          <h2 class="cp-title2">{{$sliderHome['title_'.$i]}}</h2>
        </div>
        <div class="slider-text-2">
          <div class="text">
          {!! $sliderHome['content_'.$i] !!}  
          </div>
        </div>
        <div class="slider-text-3">
          <button class="main menu-booking">
            <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>RESERVAR
          </button>
        </div>
        </div>
    </div>
   @endif
   <?php endfor; ?>
</div> 
