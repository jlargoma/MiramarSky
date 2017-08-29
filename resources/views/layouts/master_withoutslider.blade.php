
<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>

	<meta http-equiv="content-type" content="text/html; charset=utf-8" />

	<!-- Stylesheets
	============================================= -->
	<link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="{{ asset ('/css/app.css')}}" type="text/css" />

	<link rel="stylesheet" href="/frontend/css/dark.css" type="text/css" />
	<link rel="stylesheet" href="/frontend/css/responsive.css" type="text/css" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />

	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<!-- Document Title
	============================================= -->
	<title>@yield('title')</title>
	<style type="text/css">
		#contact-form input{
			color: white!important;
		}
		*::-webkit-input-placeholder {
	    /* Google Chrome y Safari */
	    color: rgba(255,255,255,0.85) !important;
		}
		*:-moz-placeholder {
		    /* Firefox anterior a 19 */
		    color: rgba(255,255,255,0.85) !important;
		}
		*::-moz-placeholder {
		    /* Firefox 19 y superior */
		    color: rgba(255,255,255,0.85) !important;
		}
		*:-ms-input-placeholder {
		    /* Internet Explorer 10 y superior */
		    color: rgba(255,255,255,0.85) !important;
		}
	</style>
</head>

<body class="stretched no-transition">

	<!-- Document Wrapper
	============================================= -->
	<div id="wrapper" class="clearfix">

		@include('layouts._header')

		@yield('content')

		@include('layouts._footer')
	</div><!-- #wrapper end -->

	<!-- Go To Top
	============================================= -->
	<div id="gotoTop" class="icon-angle-up"></div>

	<!-- External JavaScripts
	============================================= -->
	<script type="text/javascript" src="{{ asset('/js/scripts.js')}}"></script>

	<script type="text/javascript" src="{{ asset('/js/flip.js')}}"></script>

	<script type="text/javascript" src="{{asset('/frontend/js/components/moment.js')}}"></script>
		<script type="text/javascript" src="{{asset('/frontend/js/components/daterangepicker.js')}}"></script>
		<script type="text/javascript">
			$(function() {
			$(".daterange1").daterangepicker({
				"buttonClasses": "button button-rounded button-mini nomargin",
				"applyClass": "button-color",
				"cancelClass": "button-light",
			 	locale: {
			      format: 'DD MMM, YYYY',
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
	@yield('scripts')
</body>
</html>