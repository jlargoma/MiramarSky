
<!DOCTYPE html>
<html dir="ltr" lang="es-ES">
	<head>
		<meta name="description" content="Evolutio es un centro de entrenamiento, actividad física y salud de Villaviciosa de Odón, muy cerca de Móstoles, que cuenta con expertos en nutrición deportiva y fisioterapia.">
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />

		<link href="//fonts.googleapis.com/css?family=Lato:300,400,700|Arimo:400,700|Playfair+Display:400,400italic,700|Cookie" rel="stylesheet" type="text/css" />
		<link href="//fonts.googleapis.com/css?family=Open+Sans%3A800|Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700,800,900|Crete+Round:400italic" rel="stylesheet" type="text/css" />
		<link href="//fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700,800,900|Roboto+Condensed:400|Poppins:600%2C400" rel="stylesheet" type="text/css" />
 		
		<link rel="stylesheet" type="text/css" href="{{ asset ('/css/app.css')}}" />
		
		<link rel="stylesheet" type="text/css" href="{{ asset ('/css/slider.css')}}" />

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

			@media (max-width: 767px) { .demos-filter li { width: 50%; } }

			#rev_slider_31_1 .uranus.tparrows {
				width:50px;
				height:50px;
				background:rgba(255,255,255,0);
			}
			#rev_slider_31_1 .uranus.tparrows:before {
				width:50px;
				height:50px;
				line-height:50px;
				font-size:40px;
				transition:all 0.3s;
				-webkit-transition:all 0.3s;
			}
			#rev_slider_31_1 .uranus.tparrows:hover:before { opacity:0.75; }
			.hermes .tp-bullet {
				overflow:hidden;
				border-radius:50%;
				width:16px;
				height:16px;
				background-color:rgba(0,0,0,0);
				box-shadow:inset 0 0 0 2px rgb(255,255,255);
				-webkit-transition:background 0.3s ease;
				transition:background 0.3s ease;
				position:absolute;
			}
			.hermes .tp-bullet:hover { background-color:rgba(0,0,0,0.21); }
			.hermes .tp-bullet:after {
				content:' ';
				position:absolute;
				bottom:0;
				height:0;
				left:0;
				width:100%;
				background-color:rgb(255,255,255);
				box-shadow:0 0 1px rgb(255,255,255);
				-webkit-transition:height 0.3s ease;
				transition:height 0.3s ease;
			}
			.hermes .tp-bullet.selected:after{height:100%}

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
		<script type="text/javascript" src="{{ asset('/js/scripts.js')}}"></script>
		<script type="text/javascript">
			var tpj=jQuery;
			var revapi31;
			tpj(document).ready(function() {
				if(tpj("#rev_slider_31_1").revolution == undefined){
					revslider_showDoubleJqueryError("#rev_slider_31_1");
				}else{
					revapi31 = tpj("#rev_slider_31_1").show().revolution({
						sliderType:"standard",
						jsFileLocation:"include/rs-plugin/js/",
						sliderLayout:"fullwidth",
						dottedOverlay:"none",
						delay:6000,
						navigation: {
							keyboardNavigation:"on",
							keyboard_direction: "horizontal",
							mouseScrollNavigation:"off",
                         	mouseScrollReverse:"default",
							onHoverStop:"off",
							arrows: {
								style:"uranus",
								enable:true,
								hide_onmobile: true,
								hide_onleave:false,
								tmp:'',
								left: {
									h_align:"left",
									v_align:"center",
									h_offset:-40,
	                                v_offset:0
								},
								right: {
									h_align:"right",
									v_align:"center",
									h_offset:-40,
	                                v_offset:0
								}
							}
							,
							bullets: {
								enable:true,
								hide_onmobile:false,
								style:"hermes",
								hide_onleave:false,
								direction:"horizontal",
								h_align:"center",
								v_align:"bottom",
								h_offset:0,
								v_offset:20,
	                            space:5,
								tmp:''
							},
							touch: {
 
						        touchenabled: 'on',
						        swipe_threshold: 75,
						        swipe_min_touches: 1,
						        swipe_direction: 'horizontal',
						        drag_block_vertical: true
						 
						    }
						},
						responsiveLevels:[1240,1024,778,480],
						visibilityLevels:[1240,1024,778,480],
						gridwidth:[1240,1024,778,480],
						gridheight:[700,600,500,500],
						lazyType:"none",
						shadow:0,
						spinner:"off",
						stopLoop:"off",
						stopAfterLoops:-1,
						stopAtSlide:-1,
						disableProgressBar:"on",
						shuffle:"off",
						autoHeight:"off",
						fullScreenAutoWidth:"off",
						fullScreenAlignForce:"off",
						fullScreenOffsetContainer: "",
						fullScreenOffset: "0px",
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

	            if(revapi31) revapi31.revSliderSlicey();

	            revapi31.bind("revolution.slide.onloaded",function (e) {
					setTimeout( function(){ SEMICOLON.slider.sliderParallaxDimensions(); }, 200 );
				});

				revapi31.bind("revolution.slide.onchange",function (e,data) {
					SEMICOLON.slider.revolutionSliderMenu();
				});
			});	
		</script>

		
		<script type="text/javascript">
			$(document).ready(function() {

			  	$('#banner-offert').hover(function() {
			  		$('#btn-hover-banner').addClass('hover-white');
			  	}, function() {
			  		$('#btn-hover-banner').removeClass('hover-white')
			  	});

			  	$('#banner-offert').click(function(event) {
			  		$('#content-book').show('400');
			  		$('html, body').animate({
			  		       scrollTop: $("#content-book").offset().top - 60
			  		   }, 2000);
			  	});

			  	/*$(window).scroll(function(){
				  var sticky = $('.sticky-top-bar'),
				      scroll = $(window).scrollTop();

				  if (scroll >= 100) sticky.addClass('fixed');
				  else sticky.removeClass('fixed');
				});*/
			});	
		</script>

	</body>
	</html>
