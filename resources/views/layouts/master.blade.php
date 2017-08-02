<!DOCTYPE html>
<html dir="ltr" lang="es-ES">
	<head>
		<meta name="description" content="Apartamentos de lujo en Sierra Nievada a pie de pista , sin remontes ni autobuses">
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />

		<link href="//fonts.googleapis.com/css?family=Lato:300,400,700|Arimo:400,700|Playfair+Display:400,400italic,700|Cookie" rel="stylesheet" type="text/css" />
		<link href="//fonts.googleapis.com/css?family=Open+Sans%3A800|Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700,800,900|Crete+Round:400italic" rel="stylesheet" type="text/css" />
		<link href="//fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700,800,900|Roboto+Condensed:400|Poppins:600%2C400" rel="stylesheet" type="text/css" />
 	
		
		<link rel="stylesheet" type="text/css" href="{{ asset ('/frontend/css/bootstrap.css')}}" />
		<link rel="stylesheet" type="text/css" href="{{ asset ('/frontend/style.css')}}" />
		<link rel="stylesheet" type="text/css" href="{{ asset ('/frontend/css/font-icons.css')}}" />
		<link rel="stylesheet" type="text/css" href="{{ asset ('/frontend/css/animate.css')}}" />
		<link rel="stylesheet" type="text/css" href="{{ asset ('/frontend/css/magnific-popup.css')}}" />

		<?php if ($mobile->isMobile() || $mobile->isTablet()): ?>
			<!-- <link rel="stylesheet" href="{{ asset ('/frontend/css/dark-mobile.css')}}" type="text/css" /> -->
			<link rel="stylesheet" href="{{ asset ('/frontend/css/responsive-mobile.css')}}" type="text/css" />
		<?php else: ?>
			<!-- <link rel="stylesheet" href="{{ asset ('/frontend/css/dark.css')}}" type="text/css" /> -->
			<link rel="stylesheet" href="{{ asset ('/frontend/css/responsive.css')}}" type="text/css" />
		<?php endif; ?>

		<link rel="stylesheet" href="{{ asset ('/frontend/colors.php?color=3F51B5')}}" type="text/css" />
		<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/> 
		
		<link rel="stylesheet" type="text/css" href="{{ asset ('/frontend/include/rs-plugin/css/settings.css')}}" media="screen" />
		<link rel="stylesheet" type="text/css" href="{{ asset ('/frontend/include/rs-plugin/css/layers.css')}}">
		<link rel="stylesheet" type="text/css" href="{{ asset ('/frontend/include/rs-plugin/css/navigation.css')}}">
		
		<link rel="stylesheet" href="{{ asset('/frontend/css/components/daterangepicker.css')}}" type="text/css" />
		<title>@yield('title')</title>
		

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

			#rev_slider_15_1_wrapper .tp-loader.spinner3 { background-color: #333333 !important; }
			#rev_slider_15_1 .uranus.tparrows {
				width:50px;
				height:50px;
				background:rgba(255,255,255,0);
				z-index: 60;
			}
			#rev_slider_15_1 .uranus.tparrows:before {
				width:50px;
				height:50px;
				line-height:50px;
				font-size:40px;
				transition:all 0.3s;
				-webkit-transition:all 0.3s;
			}
			#rev_slider_15_1 .uranus.tparrows:hover:before{opacity:0.75; }


		</style>


	</head>

	<body class="stretched no-transition not-dark">

		<div id="wrapper" class="clearfix">
			
			@include('layouts._header')
			
			@include('frontend.slider')

			@yield('content')

			@include('layouts._footer')

		</div>
		<div id="gotoTop" class="fa fa-chevron-up"></div>
		<!-- <script type="text/javascript" src="{{ asset('/js/scripts.js')}}"></script> -->

		<script type="text/javascript" src="{{ asset('/frontend/js/jquery.js') }}"></script>
		<script type="text/javascript" src="{{ asset('/frontend/js/plugins.js') }}"></script>
		<script type="text/javascript" src="{{ asset('/frontend/js/functions.js') }}"></script>
		
		<script type="text/javascript" src="/frontend/include/rs-plugin/js/jquery.themepunch.tools.min.js"></script>
		<script type="text/javascript" src="/frontend/include/rs-plugin/js/jquery.themepunch.revolution.min.js"></script>

		<script type="text/javascript" src="/frontend/include/rs-plugin/js/extensions/revolution.extension.video.min.js"></script>
		<script type="text/javascript" src="/frontend/include/rs-plugin/js/extensions/revolution.extension.slideanims.min.js"></script>
		<script type="text/javascript" src="/frontend/include/rs-plugin/js/extensions/revolution.extension.actions.min.js"></script>
		<script type="text/javascript" src="/frontend/include/rs-plugin/js/extensions/revolution.extension.layeranimation.min.js"></script>
		<script type="text/javascript" src="/frontend/include/rs-plugin/js/extensions/revolution.extension.navigation.min.js"></script>
		<script type="text/javascript" src="{{ asset('/frontend/js/scrollreveal.js')}}"></script>
		<script type="text/javascript" src="{{ asset('/js/typed.js')}}"></script>
		<script type="text/javascript" src="{{asset('/frontend/js/components/moment.js')}}"></script>
		<script type="text/javascript" src="{{asset('/frontend/js/components/daterangepicker.js')}}"></script>
		<script type="text/javascript">
			$(function() {
				$(".daterange1").daterangepicker({
					"buttonClasses": "button button-rounded button-mini nomargin",
					"applyClass": "button-color",
					"cancelClass": "button-light",
				 	locale: {
				      format: 'DD/MM/YYYY',
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
			$(document).ready(function() {
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
		});
		</script>	
		<script type="text/javascript">
			var tpj = jQuery;

			var revapi202;
			tpj(document).ready(function() {
				if (tpj("#rev_slider_202_1").revolution == undefined) {
					revslider_showDoubleJqueryError("#rev_slider_202_1");
				} else {
					revapi202 = tpj("#rev_slider_202_1").show().revolution({
						sliderType: "standard",
						jsFileLocation: "/forntend/include/rs-plugin/js/",
						sliderLayout: "fullwidth",
						dottedOverlay: "none",
						delay: 9000,
						navigation: {
							keyboardNavigation: "off",
							keyboard_direction: "horizontal",
							mouseScrollNavigation: "off",
							onHoverStop: "off",
							touch: {
								touchenabled: "on",
								swipe_threshold: 75,
								swipe_min_touches: 50,
								swipe_direction: "horizontal",
								drag_block_vertical: false
							},
							arrows: {
								style:"uranus",
								enable:true,
								hide_onmobile:false,
								hide_onleave:false,
								tmp:'',
								left: {
									h_align:"left",
									v_align:"center",
									h_offset:-10,
									v_offset:0
								},
								right: {
									h_align:"right",
									v_align:"center",
									h_offset:-10,
									v_offset:0
								}
							},
							bullets: {
								enable: true,
								hide_onmobile: false,
								style: "zeus",
								hide_onleave: false,
								direction: "horizontal",
								h_align: "center",
								v_align: "bottom",
								h_offset: 0,
								v_offset: 30,
								space: 5,
								tmp: '<span class="tp-bullet-inner"></span>'
							}
						},
						responsiveLevels: [1240, 1024, 778, 480],
						visibilityLevels: [1240, 1024, 778, 480],
						gridwidth: [1240, 1024, 778, 480],
						gridheight: [700, 768, 960, 600],
						lazyType: "none",
						shadow: 0,
						spinner: "off",
						stopLoop: "on",
						stopAfterLoops: 0,
						stopAtSlide: 1,
						shuffle: "off",
						autoHeight: "off",
						fullScreenAutoWidth: "off",
						fullScreenAlignForce: "off",
						fullScreenOffsetContainer: "",
						fullScreenOffset: "",
						disableProgressBar: "on",
						hideThumbsOnMobile: "off",
						hideSliderAtLimit: 0,
						hideCaptionAtLimit: 0,
						hideAllCaptionAtLilmit: 0,
						debugMode: false,
						fallbacks: {
							simplifyAll: "off",
							nextSlideOnWindowFocus: "off",
							disableFocusListener: false,
						}
					});
					revapi202.bind("revolution.slide.onchange",function (e,data) {
						if( $(window).width() > 992 ) {
							if( $('#slider ul > li').eq(data.slideIndex-1).hasClass('dark') ){
								$('#header.transparent-header:not(.sticky-header,.semi-transparent)').addClass('dark');
								$('#header.transparent-header.sticky-header,#header.transparent-header.semi-transparent.sticky-header').removeClass('dark');
								$('#header-wrap').removeClass('not-dark');
							} else {
								if( $('body').hasClass('dark') ) {
									$('#header.transparent-header:not(.semi-transparent)').removeClass('dark');
									$('#header.transparent-header:not(.sticky-header,.semi-transparent)').find('#header-wrap').addClass('not-dark');
								} else {
									$('#header.transparent-header:not(.semi-transparent)').removeClass('dark');
									$('#header-wrap').removeClass('not-dark');
								}
							}
							SEMICOLON.header.logo();
						}
					});
				}
			}); /*ready*/
		</script>
		<script>
			window.sr = ScrollReveal();
			sr.reveal('.fadeInAppear', { duration: 1000 }, 50);
		</script>
		@yield('scripts')
	</body>
</html>
