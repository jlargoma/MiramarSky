
<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>

	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<!-- Stylesheets
	============================================= -->
	<link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="{{ assetV ('/css/app.css')}}" type="text/css" />

        <link rel="stylesheet" href="{{ assetV('/frontend/css/responsive.css')}}" type="text/css" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />

	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link href="https://cdn.rawgit.com/michalsnik/aos/2.1.1/dist/aos.css" rel="stylesheet">
	<link rel="stylesheet" href="{{ assetV('/frontend/css/components/daterangepicker.css')}}" type="text/css" />
		<link rel="stylesheet" type="text/css" href="{{ assetV ('/frontend/css/font-icons.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ assetV('/frontend/css/styles.css')}}" />  
	
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
		<script type="text/javascript" src="{{ assetV('/frontend/js/jquery.js') }}"></script>
	<script type="text/javascript">
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-66225892-1', 'auto');
      ga('send', 'pageview');

	</script>
	<!-- Google Code for conversiones_lead Conversion Page -->
	<script type="text/javascript">
      /* <![CDATA[ */
      var google_conversion_id = 834109020;
      var google_conversion_language = "en";
      var google_conversion_format = "3";
      var google_conversion_color = "ffffff";
      var google_conversion_label = "DHhZCN3wy3UQ3PzdjQM";
      var google_remarketing_only = false;
      /* ]]> */
	</script>
	<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
	</script>
	<noscript>
		<div style="display:inline;">
			<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/834109020/?label=DHhZCN3wy3UQ3PzdjQM&amp;guid=ON&amp;script=0"/>
		</div>
	</noscript>
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
	<div id="gotoTop" class="icon-angle-up" style="bottom:100px; right:15px;"></div>

	<!-- External JavaScripts
	============================================= -->
	<script type="text/javascript" src="{{ assetV('/pages/js/bootstrap-notify.js')}}"></script>
	
	<script type="text/javascript" src="{{assetV('/frontend/js/components/moment.js')}}"></script>
	<script type="text/javascript" src="{{assetV('/frontend/js/components/daterangepicker.js')}}"></script>
<link rel="stylesheet" href="{{ asset('/frontend/colors.php?color=3F51B5')}}" type="text/css"/>
	<?php /* view para todos los scripts generales de la pagina*/ ?>
	@yield('scripts')
	@include('layouts._generalScriptsFront')	
</body>
</html>