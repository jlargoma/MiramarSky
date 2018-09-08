<!DOCTYPE html>
<html dir="ltr" lang="es-ES">
<head>
    <meta name="description"
          content="Apartamentos de lujo en Sierra Nievada a pie de pista , sin remontes ni autobuses">
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="keywords"
          content="Alquiler apartamento Sierra Nevada;edificio miramarski; a pie de pista; apartamentos capacidad 6 / 8 personas; estudios capacidad 4 /5 personas; zona baja;piscina climatizada;gimansio;parking cubierto; a 5 minutos  de la plaza de Andalucia">

    <link href="//fonts.googleapis.com/css?family=Open+Sans%3A800|Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700,800,900|Crete+Round:400italic"
          rel="stylesheet" type="text/css"/>

    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('/img/miramarski/favicon/apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('/img/miramarski/favicon/apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('/img/miramarski/favicon/apple-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('/img/miramarski/favicon/apple-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('/img/miramarski/favicon/apple-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('/img/miramarski/favicon/apple-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('/img/miramarski/favicon/apple-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('/img/miramarski/favicon/apple-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/img/miramarski/favicon/apple-icon-180x180.png') }}">
    <link rel="icon" type="image/png" sizes="192x192"
          href="{{ asset('/img/miramarski/favicon/android-icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/img/miramarski/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('/img/miramarski/favicon/favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/img/miramarski/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('/img/miramarski/favicon/ms-icon-144x144.png' ) }}">
    <meta name="theme-color" content="#ffffff">


    <link rel="stylesheet" href="{{ asset ('/css/app.css')}}" type="text/css"/>
    {{--<link rel="stylesheet" type="text/css" href="{{ asset ('/frontend/css/bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset ('/frontend/style.css')}}">--}}
    <link rel="stylesheet" type="text/css" href="{{ asset ('/frontend/css/animate.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset ('/frontend/css/magnific-popup.css')}}">

	<?php if ($mobile->isMobile() || $mobile->isTablet()): ?>
    <link rel="stylesheet" href="{{ asset ('/frontend/css/responsive-mobile.css')}}" type="text/css"/>
	<?php else: ?>
    <link rel="stylesheet" href="{{ asset ('/frontend/css/responsive.css')}}" type="text/css"/>
	<?php endif; ?>

    <link rel="stylesheet" href="{{ asset ('/frontend/colors.php?color=3F51B5')}}" type="text/css"/>
    <link rel="stylesheet" type="text/css"
          href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/>

    {{--<link rel="stylesheet" href="{{ asset ('/css/slider.css')}}" type="text/css" />--}}
    <link rel="stylesheet" type="text/css" href="{{ asset ('/frontend/include/rs-plugin/settings.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset ('/frontend/include/rs-plugin/layers.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset ('/frontend/include/rs-plugin/navigation.css')}}">

    <link rel="stylesheet" href="{{ asset('/frontend/css/dark.css')}}" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('/frontend/css/font-icons.css')}}" type="text/css"/>
    <link href="https://cdn.rawgit.com/michalsnik/aos/2.1.1/dist/aos.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('/frontend/css/components/daterangepicker.css')}}" type="text/css"/>


    <link href="{{ asset('/frontend/hover.css')}}" rel="stylesheet" media="all">
    <link rel="stylesheet" href="{{ asset('/frontend/css/components/radio-checkbox.css')}}" type="text/css"/>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-66225892-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];

      function gtag() {
        dataLayer.push(arguments);
      }

      gtag('js', new Date());

      gtag('config', 'UA-66225892-1');
    </script>

    <script type="text/javascript">
      (function (i, s, o, g, r, a, m) {
        i['GoogleAnalyticsObject'] = r;
        i[r] = i[r] || function () {
          (i[r].q = i[r].q || []).push(arguments)
        }, i[r].l = 1 * new Date();
        a = s.createElement(o),
            m = s.getElementsByTagName(o)[0];
        a.async = 1;
        a.src = g;
        m.parentNode.insertBefore(a, m)
      })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');
      ga('create', 'UA-66225892-1', 'auto');
      ga('send', 'pageview');
    </script>

    <title>@yield('title')</title>

	<?php if ($mobile->isMobile()): ?>
        <style>

        </style>
        <style>

            .demos-filter {
                margin: 0;
                text-align: right;
            }

            .demos-filter li {
                list-style: none;
                margin: 10px 0px;
            }

            .demos-filter li a {
                display: block;
                border: 0;
                text-transform: uppercase;
                letter-spacing: 1px;
                color: #444;
            }

            .demos-filter li a:hover,
            .demos-filter li.activeFilter a { color: #1ABC9C; }

            @media (max-width: 991px) {
                .demos-filter { text-align: center; }

                .demos-filter li {
                    float: left;
                    width: 33.3%;
                    padding: 0 20px;
                }
            }

            @media (max-width: 767px) {
                .demos-filter li { width: 50%; }
            }
        </style>
    <?php else: ?>

    <style>

        .demos-filter {
            margin: 0;
            text-align: right;
        }

        .demos-filter li {
            list-style: none;
            margin: 10px 0px;
        }

        .demos-filter li a {
            display: block;
            border: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #444;
        }

        .demos-filter li a:hover,
        .demos-filter li.activeFilter a { color: #1ABC9C; }

        @media (max-width: 991px) {
            .demos-filter { text-align: center; }

            .demos-filter li {
                float: left;
                width: 33.3%;
                padding: 0 20px;
            }
        }

        @media (max-width: 767px) {
            .demos-filter li { width: 50%; }
        }
    <?php endif; ?>

    <style>

        @media screen and (max-width: 767px) {
            #primary-menu.style-2 {
                position: absolute;
                left: 15px;
                top: 80px;
                width: 90%;
            }
        }

    </style>


</head>

<body class="stretched not-dark" data-loader="5">

<div id="wrapper" class="clearfix">

    @include('layouts._header')

    @include('frontend.slider')

    @yield('content')

    @include('layouts._footer')

</div>

<div class="modal fade mapa" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-body">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">COMO LLEGAR</h4>
                </div>
                <div class="modal-body">
                    <iframe async
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3182.495919630369!2d-3.3991606847018305!3d37.09331097988919!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd71dd38d505f85f%3A0x4a99a1314ca01a9!2sAlquiler+Apartamento+de+Lujo+Sierra+Nevada+-+Edif+Miramar+Ski!5e0!3m2!1ses!2ses!4v1499417977280"
                            width="800" height="600" frameborder="0" style="border:0" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="gotoTop" class="fa fa-chevron-up"></div>
{{--<script type="text/javascript" src="{{ asset('/js/scripts.js')}}"></script>--}}

<script type="text/javascript" src="{{ asset('/frontend/js/jquery.js')}}"></script>
<script type="text/javascript" src="{{ asset('/frontend/js/plugins.js')}}"></script>
<script type="text/javascript" src="{{ asset('/frontend/js/functions.js')}}"></script>

{{--<script type="text/javascript" src="{{ asset('/js/scripts-slider.js')}}"></script>--}}
<script type="text/javascript" src="{{ asset('/frontend/include/rs-plugin/js/jquery.themepunch.tools.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('/frontend/include/rs-plugin/js/jquery.themepunch.revolution.min.js')}}"></script>
<?php if (!$mobile->isMobile()): ?>
<script type="text/javascript" src="{{ asset('/frontend/include/rs-plugin/js/addons/revolution.addon.slicey.min.js')}}"></script>
<?php else: ?>
<script type="text/javascript" src="{{ asset('/frontend/include/rs-plugin/js/addons/revolution.addon.particles.min.js')}}"></script>
<?php endif; ?>
<script type="text/javascript" src="{{ asset('/frontend/include/rs-plugin/js/extensions/revolution.extension.actions.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('/frontend/include/rs-plugin/js/extensions/revolution.extension.carousel.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('/frontend/include/rs-plugin/js/extensions/revolution.extension.kenburn.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('/frontend/include/rs-plugin/js/extensions/revolution.extension.layeranimation.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('/frontend/include/rs-plugin/js/extensions/revolution.extension.migration.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('/frontend/include/rs-plugin/js/extensions/revolution.extension.navigation.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('/frontend/include/rs-plugin/js/extensions/revolution.extension.parallax.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('/frontend/include/rs-plugin/js/extensions/revolution.extension.slideanims.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('/frontend/include/rs-plugin/js/extensions/revolution.extension.video.min.js')}}"></script>


<script type="text/javascript" src="{{ asset('/js/flip.js')}}"></script>

<script type="text/javascript" src="{{asset('/frontend/js/components/moment.js')}}"></script>
<script type="text/javascript" src="{{asset('/frontend/js/components/daterangepicker.js')}}"></script>
<script src="https://cdn.rawgit.com/michalsnik/aos/2.1.1/dist/aos.js"></script>
<?php /* view para todos los scripts generales de la pagina*/ ?>
@include('layouts._generalScripts')

<?php if (!$mobile->isMobile()): ?>
    <script type="text/javascript">
      var tpj=jQuery;

      var revapi27;
      tpj(document).ready(function() {
        if(tpj("#rev_slider_27_1_home").revolution == undefined){
          revslider_showDoubleJqueryError("#rev_slider_27_1");
        }else{
          revapi27 = tpj("#rev_slider_27_1_home").show().revolution({
            sliderType:"standard",
            jsFileLocation:"include/rs-plugin/js/",
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
                style:"bullet-bar",
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
      });	/*ready*/
    </script>

<?php else: ?>


    <script type="text/javascript">
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
            responsiveLevels:[1240,1024,778,480],
            visibilityLevels:[1240,1024,778,480],
            gridwidth:[1240,1024,778,480],
            gridheight:[868,768,960,720],
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
        }

        RsParticlesAddOn(revapi13);
      });	/*ready*/
    </script>
<?php endif; ?>


<script>
  AOS.init();
</script>
@yield('scripts')
</body>
</html>
