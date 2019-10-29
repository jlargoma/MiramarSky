/*
 *   Author : Azhar Hussain Masum
     Email: azharhussain4647@gmail.com
     Website: http://azharmasum.com/
     Facebook: https://www.facebook.com/azhar.hussainmasum
*/
// Avoid `console` errors in browsers that lack a console.
(function () {
    var method;
    var noop = function () {};
    var methods = [
        'assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error',
        'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log',
        'markTimeline', 'profile', 'profileEnd', 'table', 'time', 'timeEnd',
        'timeline', 'timelineEnd', 'timeStamp', 'trace', 'warn'
    ];
    var length = methods.length;
    var console = (window.console = window.console || {});

    while (length--) {
        method = methods[length];

        // Only stub undefined methods.
        if (!console[method]) {
            console[method] = noop;
        }
    }
}());

// Place any jQuery/helper plugins in here.


//Camera Slide-Show Custom Js Here.
$(function () {
  var hh = $( window ).height();
//  var aux = $( document ).width();
  var isMobile = true;
  var hh = $( window ).height();
//  var aux = $( document ).width();
  if ($( window ).width()>678){
    hh = parseInt(hh*0.75);
    isMobile = false;
  }
  
   $('#home_camera').camera({
        playPause: true,
        height:hh+'px',
        navigation: true,
        navigationHover: true,
        hover: true,
        loader: 'bar',
        loaderColor: 'red',
        loaderBgColor: '#222222',
        loaderOpacity: 1,
        loaderPadding: 0,
        time: 10000,
        fx: 'simpleFade',
//        transPeriod: 1500,
        pauseOnClick: true,
        pagination: true,
    });
if (isMobile){
   $('.sliderContent').css('margin-top','110px');
} else {
    var hContent = 280;
    var topContent = (hh-hContent)/2;
    if (topContent>160)
    $('.sliderContent').css('margin-top',topContent+'px');
}


  // 'random','simpleFade', 'curtainTopLeft', 'curtainTopRight', 'curtainBottomLeft', 'curtainBottomRight', 'curtainSliceLeft', 'curtainSliceRight', 'blindCurtainTopLeft', 'blindCurtainTopRight', 'blindCurtainBottomLeft', 'blindCurtainBottomRight', 'blindCurtainSliceBottom', 'blindCurtainSliceTop', 'stampede', 'mosaic', 'mosaicReverse', 'mosaicRandom', 'mosaicSpiral', 'mosaicSpiralReverse', 'topLeftBottomRight', 'bottomRightTopLeft', 'bottomLeftTopRight', 'bottomLeftTopRight'
});