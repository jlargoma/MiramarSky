/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

 $(document).ready(function () {


  if ($("#image-gallery").length>0){
    $('#image-gallery').lightSlider({
      gallery: true,
      item: 1,
      thumbItem:5,
      slideMargin: 0,
      auto: true,
      loop: true,
      adaptiveHeight: false,
      keyPress: false,
      controls: true,
      pauseOnHover:true,
      arrows: true,
      mode: "slide",
      speed: 900,
      pause: 7000,
      useCSS: true,
      cssEasing: 'ease', //'cubic-bezier(0.25, 0, 0.25, 1)',//
      easing: 'linear', //'for jquery animation',////
       onSliderLoad: function() {
           $('#image-gallery').removeClass('cS-hidden');
       },
       onBeforeSlide: function (el) {
          var mim = el.height();
          $('.lslide').each(function() {
            var img = $(this).find('img');
            if (img.height()<mim){
              img.css('height','100%');
            }
          });
        },
    });
     
  }



 if ($("#moreRooms_1").length>0){
   var itemLightSlider = 4;
   
   if ($( window ).width()<426){
     itemLightSlider = 1;
   } else if ($( window ).width()<561){
     itemLightSlider = 2;
   } else if ($( window ).width()< 1025){
     itemLightSlider = 3;
   }
        $("#moreRooms_1").lightSlider({
          loop:true,
          keyPress:true,
          auto:true,
          speed:600,
          pause: 6000,
          item:itemLightSlider,
          arrows: true,
      });
    }






function formatNumber(num) {
  return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
}

function setVal(el,countToAux,finishAux){
// var countToAux = 1;
  // var finishAux = 1000;
//   setInterval(function() {
      var salt = (Math.floor(Math.random() * 17) + 17);
      countToAux = countToAux+salt;
     
      if (countToAux<finishAux){
        var total = countToAux/finishAux;
        el.animate(total, function() {
            el.setText("<span class=\"number\">" + formatNumber(countToAux) + "</span>");
        });
        setTimeout(function(){setVal(el,countToAux,finishAux);},10);
      } else {
        el.animate(1, function() {
            el.setText("<span class=\"number\">" + formatNumber(finishAux) + "</span>");
        });
      }
//  }, 10);
  }

  var Gradient = '<defs><linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="0%" gradientUnits="userSpaceOnUse"><stop offset="0%" stop-color="#3F51B5"/><stop offset="50%" stop-color="#2989d8"/><stop offset="100%" stop-color="#7db9e8"/></linearGradient></defs>';
  var progressData1 = document.getElementById('progressData1');
  if (progressData1){
    var progressData1_c = new ProgressBar.Circle(progressData1, {
        duration: 1,
        color: "url(#gradient)",
        trailColor: "#ddd",
        strokeWidth: 8,
    });
    progressData1_c.svg.insertAdjacentHTML('afterbegin', Gradient);
  }
  
  var progressData2 = document.getElementById('progressData2');
  if (progressData2){
    var progressData2_c = new ProgressBar.Circle(progressData2, {
        duration: 1,
        color: "url(#gradient)",
        trailColor: "#ddd",
        strokeWidth: 8,
    });
    progressData2_c.svg.insertAdjacentHTML('afterbegin', Gradient);
  }
  var progressData3 = document.getElementById('progressData3');
  if (progressData3){
    var progressData3_c = new ProgressBar.Circle(progressData3, {
        duration: 1,
        color: "url(#gradient)",
        trailColor: "#ddd",
        strokeWidth: 8,
    });
    progressData3_c.svg.insertAdjacentHTML('afterbegin', Gradient);
  }
  var progressData4 = document.getElementById('progressData4');
  if (progressData4){
    var progressData4_c = new ProgressBar.Circle(progressData4, {
        duration: 1,
        color: "url(#gradient)",
        trailColor: "#ddd",
        strokeWidth: 8,
    });
  
    progressData4_c.svg.insertAdjacentHTML('afterbegin', Gradient);
  }
  
function elementVisible(element) {
var visible = true;
var windowTop = $(document).scrollTop();
var windowBottom = windowTop + window.innerHeight;
var elementPositionTop = element.offset().top;
var elementPositionBottom = elementPositionTop + element.height();
if (elementPositionTop >= windowBottom || elementPositionBottom <= windowTop) {
visible = false;
}
return visible;
}

var inited = false
if (elementVisible($('#progressData1'))){
  inited = true;
  setVal(progressData1_c,1,6477);
  setVal(progressData2_c,1,10912);
  setVal(progressData3_c,1,17615);
  setVal(progressData4_c,1,25921);
}
  $(window).on('scroll resize', function () {
    if (!inited){
      if (elementVisible($('#progressData1'))){
        if (progressData1){
          inited = true;
          setVal(progressData1_c,1,6477);
          setVal(progressData2_c,1,10912);
          setVal(progressData3_c,1,17615);
          setVal(progressData4_c,1,25921);
        }
      }
    }
  });


    $(".only-numbers").keydown(function (e) {
      // Allow: backspace, delete, tab, escape, enter and .
      if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
          // Allow: Ctrl+A, Command+A
          (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
          // Allow: home, end, left, right, down, up
          (e.keyCode >= 35 && e.keyCode <= 40)) {
        // let it happen, don't do anything
        return;
      }
      // Ensure that it is a number and stop the keypress
      if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
        e.preventDefault();
      }
    });


    $("#content-book-response").flip({
      trigger: 'manual'
    });
    function showLoad() {
      $('#loading-book').show();
    }

    function hideLoad() {
      $('#loading-book').hide();
    }

    $('#form-book-apto-lujo').submit(function (event) {
      $('#content-book-response .back').empty();
      $('#content-book-payland').empty();
      event.preventDefault();

      var _token = $('input[name="_token"]').val();
      var name = $('input[name="name"]').val();
      var email = $('input[name="email"]').val();
      var phone = $('input[name="telefono"]').val();
      var date = $('input[name="date"]').val();
      var quantity = $('select[name="quantity"]').val();
      var apto = $('input:radio[name="apto"]:checked').val();
      var luxury = $('input:radio[name="luxury"]:checked').val();
      var parking = 'si';
      var comment = $('textarea[name="comment"]').val();

      var url = $(this).attr('action');

      var dateAux = date;

      var arrayDates = dateAux.split('-');
      var res1 = arrayDates[0].replace("Abr", "Apr");
      var date1 = new Date(res1);
      var start = date1.getTime();

      var res2 = arrayDates[1].replace("Abr", "Apr");
      var date2 = new Date(res2);
      var timeDiff = Math.abs(date2.getTime() - date1.getTime());


      $.post('/getDiffIndays', {date1: arrayDates[0], date2: arrayDates[1]}, function (data) {
        var diffDays = data.diff;
        var minDays = data.minDays;
          if (diffDays >= 2) {
            if (data.specialSegment != false && minDays>diffDays) {
              $('#content-book-response .back').empty();
              alert('ESTANCIA MÍNIMA EN ESTAS FECHAS:' + minDays + ' DÍAS');
            } else {
              $.post(url, {
                _token: _token,
                name: name,
                email: email,
                phone: phone,
                fechas: data.dates,
                quantity: quantity,
                apto: apto,
                luxury: luxury,
                parking: parking,
                comment: comment
              }, function (data) {

                $('#content-book-response .back').empty();
                $('#content-book-response .back').append(data);
                $("#content-book-response").flip(true);

                if ($( document ).width()<991){
                  var scrollTopData = $("#content-book-response").offset().top;
                  $('html, body').animate({
                    /*scrollTop: $("section#content").offset().top*/
                    scrollTop: scrollTopData
                  }, 2000);
                }
                if (data.specialSegment != false) {
                  $('.content-alert-min-special-days').append('<h2 class="text-center text-white white" ' +
                   'style="line-height: 1; letter-spacing: -1px;">ESTANCIA MÍNIMA EN ' +
                   'ESTAS ' +
                      'FECHAS: ' + minDays + ' DÍAS</h2>');
                }
              });
            }
          } else {
            alert('Estancia minima ' + minDays + ' NOCHES')
          }



      });


    });
    
     $('#banner-offert, .menu-booking').click(function (event) {
        $('#content-book').show('400');
        $('#banner-offert').hide();
        $('#line-banner-offert').hide();
        $('#desc-section').hide();
        $('section#content').css('z-index','20000');
        if ($( document ).width()>991)
          var scrollTopData = $("#content-book").offset().top - 80;
        else 
          var scrollTopData = $("#content-book").offset().top - 10;
        
        $('html, body').animate({
          /*scrollTop: $("section#content").offset().top*/
          scrollTop: scrollTopData
        }, 2000);
      });

    $('#close-form-book').click(function (event) {
      $('#banner-offert').show();
      $('#line-banner-offert').show();
      $('#content-book').hide('100');
      $('#desc-section').show();
      $('section#content').css('z-index','0');

      unflip();

      $('html, body').animate({
        scrollTop: $("body").offset().top
      }, 2000);
    });
    
    $('body').on('click','.backBooking',function (event) {
      unflip();
    });
    
    function unflip() {
      $("#content-book-response").flip(false);
      $('#content-book-response .back').empty();
    }
    
    
    console.log(typeof AOS,typeof LoadImgs,typeof LoadImgsBackground);
    
    if (typeof LoadImgs === "function") {
      LoadImgs();
    }
    if (typeof LoadImgsBackground === "function") {
     setTimeout(function(){LoadImgsBackground();},750);
    }
    
    setTimeout(function(){
      console.log(typeof AOS,typeof LoadImgs,typeof LoadImgsBackground);
      if (typeof AOS === "object") {
        AOS.init();
      }
    },1750);
    
    

 });