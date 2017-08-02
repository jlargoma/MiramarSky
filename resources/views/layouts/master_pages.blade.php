<!DOCTYPE html>
<html dir="ltr" lang="es-ES">
	<head>
		<meta name="description" content="">
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
			
			@yield('slider')

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
		<script type="text/javascript" src="/frontend/include/rs-plugin/js/addons/revolution.addon.snow.min.js"></script>

		<script type="text/javascript" src="/frontend/include/rs-plugin/js/extensions/revolution.extension.actions.min.js"></script>
		<script type="text/javascript" src="/frontend/include/rs-plugin/js/extensions/revolution.extension.carousel.min.js"></script>
		<script type="text/javascript" src="/frontend/include/rs-plugin/js/extensions/revolution.extension.kenburn.min.js"></script>
		<script type="text/javascript" src="/frontend/include/rs-plugin/js/extensions/revolution.extension.layeranimation.min.js"></script>
		<script type="text/javascript" src="/frontend/include/rs-plugin/js/extensions/revolution.extension.migration.min.js"></script>
		<script type="text/javascript" src="/frontend/include/rs-plugin/js/extensions/revolution.extension.navigation.min.js"></script>
		<script type="text/javascript" src="/frontend/include/rs-plugin/js/extensions/revolution.extension.parallax.min.js"></script>
		<script type="text/javascript" src="/frontend/include/rs-plugin/js/extensions/revolution.extension.slideanims.min.js"></script>
		<script type="text/javascript" src="/frontend/include/rs-plugin/js/extensions/revolution.extension.video.min.js"></script>

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
		</script>	
		<script type="text/javascript">
			var tpj=jQuery;

			var revapi15;
			tpj(document).ready(function() {
				if(tpj("#rev_slider_15_1").revolution == undefined){
					revslider_showDoubleJqueryError("#rev_slider_15_1");
				}else{
					revapi15 = tpj("#rev_slider_15_1").show().revolution({
						sliderType:"standard",
						jsFileLocation:"/frontend/include/rs-plugin/js/",
						sliderLayout:"fullwidth",
						dottedOverlay:"none",
						delay:9000,
						snow: {
							startSlide: "first",
							endSlide: "last",
							maxNum: "400",
							minSize: "0.2",
							maxSize: "6",
							minOpacity: "0.3",
							maxOpacity: "1",
							minSpeed: "30",
							maxSpeed: "100",
							minSinus: "1",
							maxSinus: "100",
						},
						navigation: {
							keyboardNavigation:"on",
							keyboard_direction: "horizontal",
							mouseScrollNavigation:"off",
							mouseScrollReverse:"default",
							onHoverStop:"off",
							touch:{
								touchenabled:"on",
								swipe_threshold: 75,
								swipe_min_touches: 1,
								swipe_direction: "horizontal",
								drag_block_vertical: false
							}
							,
							arrows: {
								style:"uranus",
								enable:true,
								hide_onmobile:false,
								hide_onleave:false,
								tmp:'',
								left: {
									h_align:"left",
									v_align:"center",
									h_offset:10,
									v_offset:0
								},
								right: {
									h_align:"right",
									v_align:"center",
									h_offset:10,
									v_offset:0
								}
							}
						},
						responsiveLevels:[1240,1024,778,480],
						visibilityLevels:[1240,1024,778,480],
						gridwidth:[1240,1024,778,480],
						gridheight:[550,450,450,300],
						lazyType:"none",
						scrolleffect: {
							blur:"on",
							maxblur:"20",
							on_slidebg:"on",
							direction:"top",
							multiplicator:"2",
							multiplicator_layers:"2",
							tilt:"10",
							disable_on_mobile:"off",
						},
						parallax: {
							type:"scroll",
							origo:"slidercenter",
							speed:400,
							levels:[5,10,15,20,25,30,35,40,45,46,47,48,49,50,51,55],
						},
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
						hideProgressBar:true,
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

				RsSnowAddOn(tpj, revapi15);
			});	/*ready*/
		</script>
		@yield('scripts')
	</body>
</html>
