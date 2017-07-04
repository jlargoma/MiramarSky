
<!DOCTYPE html>
<html dir="ltr" lang="es-ES">
	<head>
		<meta name="description" content="Evolutio es un centro de entrenamiento, actividad física y salud de Villaviciosa de Odón, muy cerca de Móstoles, que cuenta con expertos en nutrición deportiva y fisioterapia.">
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
		<link rel="stylesheet" type="text/css" href="{{ asset ('/frontend/css/responsive.css')}}" />

		<link rel="stylesheet" href="{{ asset ('/frontend/colors.php?color=3F51B5')}}" type="text/css" />
		<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/> 
		
		<title>@yield('title')</title>

	</head>

	<body class="stretched no-transition not-dark">

		<div id="wrapper" class="clearfix">
			
			@include('layouts._header')
			
			@yield('content')

			@include('layouts._footer')

		</div>
		<div id="gotoTop" class="fa fa-chevron-up"></div>

		<script type="text/javascript" src="{{ asset('/frontend/js/jquery.js') }}"></script>
		<script type="text/javascript" src="{{ asset('/frontend/js/plugins.js') }}"></script>
		<script type="text/javascript" src="{{ asset('/frontend/js/functions.js') }}"></script>

		@yield('scripts')
		
	</body>
</html>
