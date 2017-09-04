<!DOCTYPE html>
<html dir="ltr" lang="es-ES">
	<head>
		<meta name="description" content="Apartamentos de lujo en Sierra Nievada a pie de pista , sin remontes ni autobuses">
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />

		<link href="//fonts.googleapis.com/css?family=Lato:300,400,700|Arimo:400,700|Playfair+Display:400,400italic,700|Cookie" rel="stylesheet" type="text/css" />
		<link href="//fonts.googleapis.com/css?family=Open+Sans%3A800|Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700,800,900|Crete+Round:400italic" rel="stylesheet" type="text/css" />
		<link href="//fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700,800,900|Roboto+Condensed:400|Poppins:600%2C400" rel="stylesheet" type="text/css" />
 	
		
		<!-- <link rel="stylesheet" type="text/css" href="{{ asset ('/frontend/css/bootstrap.css')}}" />
		<link rel="stylesheet" type="text/css" href="{{ asset ('/frontend/style.css')}}" />
		<link rel="stylesheet" type="text/css" href="{{ asset ('/frontend/css/font-icons.css')}}" />
		<link rel="stylesheet" type="text/css" href="{{ asset ('/frontend/css/animate.css')}}" />
		<link rel="stylesheet" type="text/css" href="{{ asset ('/frontend/css/magnific-popup.css')}}" /> -->
		
		<link rel="stylesheet" href="{{ asset ('/css/app.css')}}" type="text/css" />

		<?php if ($mobile->isMobile() || $mobile->isTablet()): ?>
			<link rel="stylesheet" href="{{ asset ('/frontend/css/responsive-mobile.css')}}" type="text/css" />
		<?php else: ?>
			<link rel="stylesheet" href="{{ asset ('/frontend/css/responsive.css')}}" type="text/css" />
		<?php endif; ?>

		<link rel="stylesheet" href="{{ asset ('/frontend/colors.php?color=3F51B5')}}" type="text/css" />
		<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/> 
		
		<!-- <link rel="stylesheet" type="text/css" href="{{ asset ('/frontend/include/rs-plugin/css/settings.css')}}" media="screen" />
		<link rel="stylesheet" type="text/css" href="{{ asset ('/frontend/include/rs-plugin/css/layers.css')}}">
		<link rel="stylesheet" type="text/css" href="{{ asset ('/frontend/include/rs-plugin/css/navigation.css')}}"> -->

		<link rel="stylesheet" href="{{ asset ('/css/slider.css')}}" type="text/css" />
		
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

	<body class="stretched not-dark no-transition">

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
							<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3182.495919630369!2d-3.3991606847018305!3d37.09331097988919!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd71dd38d505f85f%3A0x4a99a1314ca01a9!2sAlquiler+Apartamento+de+Lujo+Sierra+Nevada+-+Edif+Miramar+Ski!5e0!3m2!1ses!2ses!4v1499417977280" width="800" height="600" frameborder="0" style="border:0" allowfullscreen></iframe>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="gotoTop" class="fa fa-chevron-up"></div>
		<script type="text/javascript" src="{{ asset('/js/scripts.js')}}"></script>

		<script type="text/javascript" src="{{ asset('/js/scripts-slider.js')}}"></script>
		<script type="text/javascript" src="{{ asset('/js/flip.js')}}"></script>

		<!-- <script type="text/javascript" src="{{ asset('/frontend/js/jquery.js') }}"></script>
		<script type="text/javascript" src="{{ asset('/frontend/js/plugins.js') }}"></script>
		<script type="text/javascript" src="{{ asset('/frontend/js/functions.js') }}"></script>
		
		<script type="text/javascript" src="/frontend/include/rs-plugin/js/jquery.themepunch.tools.min.js"></script>
		<script type="text/javascript" src="/frontend/include/rs-plugin/js/jquery.themepunch.revolution.min.js"></script>

		<script type="text/javascript" src="/frontend/include/rs-plugin/js/extensions/revolution.extension.video.min.js"></script>
		<script type="text/javascript" src="/frontend/include/rs-plugin/js/extensions/revolution.extension.slideanims.min.js"></script>
		<script type="text/javascript" src="/frontend/include/rs-plugin/js/extensions/revolution.extension.actions.min.js"></script>
		<script type="text/javascript" src="/frontend/include/rs-plugin/js/extensions/revolution.extension.layeranimation.min.js"></script>
		<script type="text/javascript" src="/frontend/include/rs-plugin/js/extensions/revolution.extension.navigation.min.js"></script> -->

		<script type="text/javascript" src="{{asset('/frontend/js/components/moment.js')}}"></script>
		<script type="text/javascript" src="{{asset('/frontend/js/components/daterangepicker.js')}}"></script>

		<?php /* view para todos los scripts generales de la pagina*/ ?>
		@include('layouts._generalScripts')
		
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

		<script type="text/javascript">

			
			jQuery(function($) {			 
			  var doAnimations = function() {					   
			    var offset = $(window).scrollTop() + $(window).height(),
			        $animatables = $('.animatable');					   
			        $animatables.each(function(i) {
			       var $animatable = $(this);			 			     
			            if (($animatable.offset().top + $animatable.height() + 50) < offset) {			   			       
			        if (!$animatable.hasClass('animate-in')) {
			          $animatable.removeClass('animate-out').css('top', $animatable.css('top')).addClass('animate-in');
			        }

			            }			 			     
			      else if (($animatable.offset().top + $animatable.height() + 50) > offset) {			   			       
			        if ($animatable.hasClass('animate-in')) {
			          $animatable.removeClass('animate-in').css('top', $animatable.css('top')).addClass('animate-out');
			        }

			      }

			    });

			    };			 
			    $(window).on('scroll', doAnimations);
			  $(window).trigger('scroll');

			});
		</script>
		@yield('scripts')
	</body>
</html>
