<!-- before general -->

{{--<script type="text/javascript" src="{{ asset('/js/scriptsTest.js')}}"></script>--}}

<script type="text/javascript" src="{{ asset('/frontend/js/jquery.js')}}"></script>
<script type="text/javascript" src="{{ asset('/frontend/js/plugins.js')}}"></script>
<script type="text/javascript" src="{{ asset('/frontend/js/functionsTest.js')}}"></script>

{{--<script type="text/javascript" src="{{ asset('/js/scripts-slider.js')}}"></script>--}}
{{--<script type="text/javascript" src="{{ asset('/frontend/include/rs-plugin/js/jquery.themepunch.tools.min.js')}}"></script>--}}
{{--<script type="text/javascript" src="{{ asset('/frontend/include/rs-plugin/js/jquery.themepunch.revolution.min.js')}}"></script>--}}
<?php if (!$mobile->isMobile()): ?>
<{{--script type="text/javascript" src="{{ asset('/frontend/include/rs-plugin/js/addons/revolution.addon.slicey.min.js')}}"></script>--}}
<?php else: ?>
{{--<script type="text/javascript" src="{{ asset('/frontend/include/rs-plugin/js/addons/revolution.addon.particles.min.js')}}"></script>--}}
<?php endif; ?>
{{--<script type="text/javascript" src="{{ asset('/frontend/include/rs-plugin/js/extensions/revolution.extension.actions.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('/frontend/include/rs-plugin/js/extensions/revolution.extension.carousel.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('/frontend/include/rs-plugin/js/extensions/revolution.extension.kenburn.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('/frontend/include/rs-plugin/js/extensions/revolution.extension.layeranimation.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('/frontend/include/rs-plugin/js/extensions/revolution.extension.migration.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('/frontend/include/rs-plugin/js/extensions/revolution.extension.navigation.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('/frontend/include/rs-plugin/js/extensions/revolution.extension.parallax.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('/frontend/include/rs-plugin/js/extensions/revolution.extension.slideanims.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('/frontend/include/rs-plugin/js/extensions/revolution.extension.video.min.js')}}"></script>--}}


{{-- <script type="text/javascript" src="{{ asset('/js/flip.js')}}"></script> --}}
<script type="text/javascript" src="{{ asset('/js/flip.min.js')}}"></script>

{{-- <script type="text/javascript" src="{{asset('/frontend/js/components/moment.js')}}"></script> --}}
<script type="text/javascript" src="{{asset('/frontend/js/components/moment.min.js')}}"></script>
{{-- <script type="text/javascript" src="{{asset('/frontend/js/components/daterangepicker.js')}}"></script> --}}
<script type="text/javascript" src="{{asset('/frontend/js/components/daterangepicker.min.js')}}"></script>
{{-- <script src="https://cdn.rawgit.com/michalsnik/aos/2.1.1/dist/aos.js"></script> --}}
<script type="text/javascript" src="{{asset('/frontend/js/aos/2.1.1/aos.js')}}"></script>

<?php /* view para todos los scripts generales de la pagina*/ ?>

<!-- general scripts -->

{{-- <script type="text/javascript" src="https://cdn.rawgit.com/nnattawat/flip/master/dist/jquery.flip.min.js"></script> --}}
{{-- <script type="text/javascript" src="{{asset('/frontend/js/jquery.flip/1.1.2/jquery.flip.min.js')}}"></script> --}}

<script type="text/javascript">
  /* Calendario */
  $(function () {
    $(".daterange1").daterangepicker({
      "buttonClasses": "button button-rounded button-mini nomargin",
      "applyClass": "button-color",
      "cancelClass": "button-light",
      locale: {
        format: 'DD MMM, YY',
        "applyLabel": "Aplicar",
        "cancelLabel": "Cancelar",
        "fromLabel": "From",
        "toLabel": "To",
        "customRangeLabel": "Custom",
        "daysOfWeek": [
          "Do",
          "Lu",
          "Mar",
          "Mi",
          "Ju",
          "Vi",
          "Sa"
        ],
        "monthNames": [
          "Enero",
          "Febrero",
          "Marzo",
          "Abril",
          "Mayo",
          "Junio",
          "Julio",
          "Agosto",
          "Septiembre",
          "Octubre",
          "Noviembre",
          "Diciembre"
        ],
        "firstDay": 1,
      },

    });
  });

  function unflip() {

    $("#content-book-response").flip(false);
    $('#content-book-response .back').empty();
  }

  // function calculateDays(date1, date2){
  //  var result = "";
  //  $.post( '/getDiffIndays' , { date1: date1, date2: date2}, function(data) {
  //     result = data;
  //  });

  //  return result;
  // }

  $(document).ready(function () {

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


    $('#form-book-apto-lujo').submit(function (event) {

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
              if (data.specialSegment != false) {
                $('.content-alert-min-special-days').append('<h2 class="text-center text-white white" ' +
                 'style="line-height: 1; letter-spacing: -1px;">ESTANCIA MÍNIMA EN ' +
                 'ESTAS ' +
                    'FECHAS: ' + minDays + ' DÍAS</h2>');
              }
            });
          } else {
            alert('Estancia minima ' + minDays + ' NOCHES')
          }
        


      });


    });

     <?php if ($mobile->isMobile() || $mobile->isTablet()): ?>
      $('#banner-offert, .menu-booking').click(function (event) {
        $('#content-book').show('400');
        $('#banner-offert').hide();
        $('#line-banner-offert').hide();
        $('#desc-section').hide();
        $('section#content').css('z-index','20000');
        $('html, body').animate({
          /*scrollTop: $("section#content").offset().top*/
          scrollTop: $("#content-book").offset().top+10
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

     <?php else: ?>
      $('#banner-offert, .menu-booking').click(function (event) {
        $('#content-book').show('400');
        $('#banner-offert').hide();
        $('#line-banner-offert').hide();

        $('html, body').animate({
          scrollTop: $("#content-book").offset().top - 85
        }, 2000);
      });


    $('#close-form-book').click(function (event) {
      $('#banner-offert').show();
      $('#line-banner-offert').show();
      $('#content-book').hide('100');

      unflip();
      $('html, body').animate({
        scrollTop: $("body").offset().top
      }, 2000);
    });
     <?php endif; ?>

      $('.daterange1').change(function (event) {
        var date = $(this).val();

        var arrayDates = date.split('-');

        var date1 = new Date(arrayDates[0]);
        var date2 = new Date(arrayDates[1]);
        var timeDiff = Math.abs(date2.getTime() - date1.getTime());
        var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
        console.log(diffDays);
        if (diffDays < 2) {
          $('.min-days').show();
        } else {
          $('.min-days').hide();
        }

      });
    $(".apto-3dorm").click(function (event) {

      $("#luxury-yes").trigger('click');


      $("#luxury-no").prop("disabled", true);
      $("#luxury-no").hide();


    });
    $(".apto-chlt").click(function (event) {

      $("#luxury-no").trigger('click');


      $("#luxury-yes").prop("disabled", true);
      $("#luxury-yes").hide();


    });

    $('#quantity').change(function (event) {
      var pax = $(this).val();

      if (pax <= 4) {
        $("#apto-estudio").prop("disabled", false);

        $("#apto-estudio").trigger('click');
        $("#apto-estudio").show();

      }
      if (pax == 8) {
        $(".apto-2dorm").trigger('click');
        $("#apto-estudio").prop("disabled", true);
        $("#apto-estudio").hide();

      } else if (pax > 8) {
        $(".apto-3dorm").trigger('click');

        $("#apto-2dorm").prop("disabled", true);
        $("#apto-2dorm").hide();

        $("#apto-estudio").prop("disabled", true);
        $("#apto-estudio").hide();
      }

    });

  });

  /* Para pagina de apartamentos */

  $('span.close').click(function (event) {
    $('#content-form-book').hide('400');
    unflip();
    $('html, body').animate({
      scrollTop: $("body").offset().top
    }, 2000);
    $('#fixed-book').fadeIn();
  });

  <?php if (!$mobile->isMobile()): ?>

  $('#showFormBook').click(function (event) {
    $('#content-form-book').slideDown('400');
    $('html,body').animate({
          scrollTop: $("#content-form-book").offset().top - 80
        },
        'slow');
  });
  <?php else: ?>

  $('#showFormBook').click(function (event) {
    $('#content-form-book').slideDown('400');
    $('html,body').animate({
          scrollTop: $("#content-form-book").offset().top
        },
        'slow');

    $('#fixed-book').fadeOut();

  });
   <?php endif; ?>
</script>

<!-- after general -->

<?php if (!$mobile->isMobile()): ?>
    <!--<script type="text/javascript">
      var tpj=jQuery;

      var revapi27;
      tpj(document).ready(function() {
        if(tpj("#rev_slider_27_1_home").revolution == undefined){
          revslider_showDoubleJqueryError("#rev_slider_27_1");
        }else{
          revapi27 = tpj("#rev_slider_27_1_home").show().revolution({
            sliderType:"standard",
            jsFileLocation:"/frontend/include/rs-plugin/js/",
            sliderLayout:"fullscreen",
            dottedOverlay:"none",
            delay:9000,
            navigation: {
              keyboardNavigation:"off",
              keyboard_direction: "horizontal",
              mouseScrollNavigation:"off",
              mouseScrollReverse:"default",
              onHoverStop:"off",
              bullets: {
                enable:true,
                hide_onmobile:false,
                style:"uranus",
                hide_onleave:false,
                direction:"horizontal",
                h_align:"center",
                v_align:"bottom",
                h_offset:0,
                v_offset:50,
                space:5,
                tmp:''
              }
            },
            responsiveLevels:[1240,1024,778,480],
            visibilityLevels:[1240,1024,778,480],
            gridwidth:[1240,1024,778,480],
            gridheight:[868,768,960,720],
            lazyType:"none",
            shadow:0,
            spinner:"off",
            stopLoop:"off",
            stopAfterLoops:-1,
            stopAtSlide:-1,
            shuffle:"off",
            autoHeight:"off",
            fullScreenAutoWidth:"off",
            fullScreenAlignForce:"off",
            fullScreenOffsetContainer: "",
            fullScreenOffset: "0",
            hideThumbsOnMobile:"off",
            hideSliderAtLimit:0,
            hideCaptionAtLimit:0,
            hideAllCaptionAtLilmit:0,
            debugMode:false,
            fallbacks: {
              simplifyAll:"off",
              nextSlideOnWindowFocus:"off",
              disableFocusListener:false,
            }
          });
          revapi27.bind("revolution.slide.onloaded",function (e) {
            revapi27.addClass("tiny_bullet_slider");
          });
        }

        if(revapi27) revapi27.revSliderSlicey();
      });   /*ready*/
    </script>-->

<?php else: ?>


   <!--<script type="text/javascript">
      var tpj=jQuery;

      var revapi13;
      tpj(document).ready(function() {
        if(tpj("#rev_slider_13_1").revolution == undefined){
          revslider_showDoubleJqueryError("#rev_slider_13_1");
        }else{
          revapi13 = tpj("#rev_slider_13_1").show().revolution({
            sliderType:"standard",
            jsFileLocation:"/frontend/include/rs-plugin/js/",
            sliderLayout:"fullscreen",
            dottedOverlay:"none",
            delay:9000,
            particles: {startSlide: "first", endSlide: "last", zIndex: "1",
              particles: {
                number: {value: 80}, color: {value: "#000000"},
                shape: {
                  type: "circle", stroke: {width: 0, color: "#FFF", opacity: 1},
                  image: {src: ""}
                },
                opacity: {value: 0.3, random: false, min: 0.25, anim: {enable: false, speed: 3, opacity_min: 0, sync: false}},
                size: {value: 10, random: true, min: 1, anim: {enable: false, speed: 40, size_min: 1, sync: false}},
                line_linked: {enable: true, distance: 200, color: "#000000", opacity: 0.2, width: 1},
                move: {enable: true, speed: 3, direction: "none", random: true, min_speed: 3, straight: false, out_mode: "out"}},
              interactivity: {
                events: {onhover: {enable: true, mode: "bubble"}, onclick: {enable: false, mode: "repulse"}},
                modes: {grab: {distance: 400, line_linked: {opacity: 0.5}}, bubble: {distance: 400, size: 150, opacity: 1}, repulse: {distance: 200}}
              }
            },
            navigation: {
              keyboardNavigation:"off",
              keyboard_direction: "horizontal",
              mouseScrollNavigation:"off",
              mouseScrollReverse:"default",
              onHoverStop:"off",
              arrows: {
                style:"gyges",
                enable:true,
                hide_onmobile:false,
                hide_onleave:false,
                tmp:'',
                left: {
                  h_align:"center",
                  v_align:"bottom",
                  h_offset:-20,
                  v_offset:0
                },
                right: {
                  h_align:"center",
                  v_align:"bottom",
                  h_offset:20,
                  v_offset:0
                }
              }
            },
            responsiveLevels:[1240,1024,767,480],
            visibilityLevels:[1240,1024,767,480],
            gridwidth:[1240,1024,778,480],
            gridheight:[868,768,960,767],
            lazyType:"none",
            shadow:0,
            spinner:"off",
            stopLoop:"on",
            stopAfterLoops:0,
            stopAtSlide:1,
            shuffle:"off",
            autoHeight:"off",
            fullScreenAutoWidth:"off",
            fullScreenAlignForce:"off",
            fullScreenOffsetContainer: "",
            fullScreenOffset: "0",
            disableProgressBar:"on",
            hideThumbsOnMobile:"on",
            hideSliderAtLimit:0,
            hideCaptionAtLimit:0,
            hideAllCaptionAtLilmit:0,
            debugMode:false,
            fallbacks: {
              simplifyAll:"off",
              nextSlideOnWindowFocus:"off",
              disableFocusListener:false,
            }
          });
        }

        RsParticlesAddOn(revapi13);
      });   /*ready*/
    </script>-->
<?php endif; ?>


@yield('scripts')


 <script src="{{asset('/frontend/js/classie.min.js')}}"></script>
 <script src="{{asset('/frontend/js/boxesFx.min.js')}}"></script>

<script type="text/javascript">

        new BoxesFx( document.getElementById( 'boxgallery' ) );
        
        function setRandomSlide(){
               random_slide = 1 + Math.floor(Math.random() * 3);
                                 
               if(random_slide == 2){
                  $('div#boxgallery span.next').click();
              }else if(random_slide == 3){
                  $('div#boxgallery span.prev').click();
               }else{
                  setTimeout(function(){
                     $('p.sp-layer-1').fadeIn(1500);
                     /*$('p.sp-layer-'+random_slide).fadeIn(1500);*/
                 },500);
               }
        }
        
        function setSliderAuto(){
            sliderAuto = setInterval(function(){
                                    $('div#boxgallery span.next').click();
                                 }, 10000);
        }
        
        function boxgalleryLoadTextNextSlider(){
            clearInterval(sliderAuto);
            $('p.sp-layer').hide();
            
             setTimeout(function(){
                  setTimeout(function(){
                     if(typeof $('div.panel.active').attr('id') != 'undefined'){
                         layer_id = $('div.panel.active').attr('id');
                     }else{
                         layer_id = $('div.panel.current').attr('id')
                     }
                     $('p.'+layer_id).fadeIn(1500);
                   },1500);
                   setSliderAuto();
              },500);
        };
        
        $('div#boxgallery span.next').on('click',function(){
         layer = $('div.panel.current');
         layer_next_bgr = layer.attr("data-next-url");
         layer.attr("style",layer_next_bgr);
         
         boxgalleryLoadTextNextSlider();
        });
        
        $('div#boxgallery span.prev').on('click',function(){
         layer = $('div.panel.current');
         layer_prev_bgr = layer.attr("data-prev-url");
         layer.attr("style",layer_prev_bgr);

         boxgalleryLoadTextNextSlider();
        });
        
        <?php if (!$mobile->isMobile()): ?>
            $('div.bg-img img').attr('style','max-width:none !important;');
        <?php else: ?>
            $('div.bg-img img').attr('style','max-width:none !important;margin-left: -67.2vw;');
        <?php endif; ?>

        $('div.bg-img').attr('style','background:none !important;');
        $('div#boxgallery span.prev, div#boxgallery span.next').attr('style','z-index:10000;');
        
        $('div#blank_loader').fadeOut(500);

        setSliderAuto();
       setRandomSlide();

</script>
<script>
  AOS.init();
</script>

</body>