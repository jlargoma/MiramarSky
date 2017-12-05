
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
	<link href="https://cdn.rawgit.com/michalsnik/aos/2.1.1/dist/aos.css" rel="stylesheet">
	<link rel="stylesheet" href="{{ asset('/frontend/css/components/daterangepicker.css')}}" type="text/css" />
	
	
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
	@yield('css')
		<script type="text/javascript" src="{{ asset('/frontend/js/jquery.js') }}"></script>
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
	<script type="text/javascript" src="{{ asset('/pages/js/bootstrap-notify.js')}}"></script>
	<script type="text/javascript" src="{{ asset('/frontend/js/plugins.js') }}"></script>
	<script type="text/javascript" src="{{ asset('/frontend/js/functions.js') }}"></script>
	
	<script type="text/javascript" src="{{asset('/frontend/js/components/moment.js')}}"></script>
	<script type="text/javascript" src="{{asset('/frontend/js/components/daterangepicker.js')}}"></script>
	<?php /* view para todos los scripts generales de la pagina*/ ?>
	@include('layouts._generalScripts')	
	@yield('scripts')
</body>
</html>