<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
		<meta charset="utf-8" />
		<title>@yield('title')</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no" />
		<link rel="apple-touch-icon" href="pages/ico/60.png">
		<link rel="apple-touch-icon" sizes="76x76" href="pages/ico/76.png">
		<link rel="apple-touch-icon" sizes="120x120" href="pages/ico/120.png">
		<link rel="apple-touch-icon" sizes="152x152" href="pages/ico/152.png">
		<link rel="icon" type="image/x-icon" href="/favicon.ico" />
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-touch-fullscreen" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="default">
		<meta content="" name="description" />
		<meta content="" name="author" />
		<link href="{{ asset('/assets/plugins/pace/pace-theme-flash.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('/assets/plugins/bootstrapv3/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('/assets/plugins/font-awesome/css/font-awesome.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('/assets/plugins/jquery-scrollbar/jquery.scrollbar.css') }}" rel="stylesheet" type="text/css" media="screen" />
		<link href="{{ asset('/assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" media="screen" />
		<link href="{{ asset('/assets/plugins/switchery/css/switchery.min.css') }}" rel="stylesheet" type="text/css" media="screen" />
		<link href="{{ asset('/assets/plugins/nvd3/nv.d3.min.css') }}" rel="stylesheet" type="text/css" media="screen" />
		<link href="{{ asset('/assets/plugins/mapplic/css/mapplic.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('/assets/plugins/rickshaw/rickshaw.min.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('/assets/plugins/bootstrap-datepicker/css/datepicker3.css') }}" rel="stylesheet" type="text/css" media="screen">
		<link href="{{ asset('/assets/plugins/jquery-metrojs/MetroJs.css') }}" rel="stylesheet" type="text/css" media="screen" />
		<link href="{{ asset('/pages/css/pages-icons.css') }}" rel="stylesheet" type="text/css">
		<link class="main-stylesheet" href="{{ asset('/pages/css/pages.css') }}" rel="stylesheet" type="text/css" />
	


		@yield('externalScripts')
	    <!--[if lte IE 9]>
	    <link href="assets/plugins/codrops-dialogFx/dialog.ie.css" rel="stylesheet" type="text/css" media="screen" />
	    <![endif]-->
	   	<style>
	   		.navbar-inverse {
	   		  background-color: #B0B5B9;
	   		  border-color: #B0B5B9;
	   		}
	   		.navbar-inverse .navbar-nav>li>a {
	   		    color: white;
	   		    font-weight: bold;
	   		    font-size: 15px;
	   		}
	   		.navbar {
	   			margin-bottom: 0px;
	   		}
	   	</style>
	   	<?php 
   			use App\Classes\Mobile; 
   			$mobile = new Mobile();
	   	?>
	</head>
	<body class="fixed-header   windows desktop pace-done sidebar-visible menu-pin" style="padding-top:0px!important">
	<!-- <body class="fixed-header dashboard  windows desktop sidebar-visible pace-done menu-pin"> -->
		<?php if (Auth::user()->role == 'admin' || Auth::user()->role == 'subadmin'): ?>
			<nav class="navbar navbar-inverse" role="navigation">
			    <div class="navbar-header">
			        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
			            <span class="icon-bar"></span>
			            <span class="icon-bar"></span>
			            <span class="icon-bar"></span>
			        </button>    
			    </div>
			    <div class="navbar-collapse collapse">
			        <ul class="nav navbar-nav navbar-left">
			        	<li class="{{ Request::path() == 'admin/reservas' ? 'active' : '' }}"><a href="{{ url('admin/reservas') }}" class="detailed" >Reservas</a></li>
				      	<li class="{{ Request::path() == 'admin/apartamentos' ? 'active' : '' }}"><a href="{{ url('admin/apartamentos') }}" class="detailed">Apartamentos</a></li>
				      	<li class="{{ Request::path() == 'admin/precios' ? 'active' : '' }}"><a href="{{ url('admin/precios') }}" class="detailed">Precios</a></li>
				      	<li class="{{ Request::path() == 'admin/temporadas' ? 'active' : '' }}"><a href="{{ url('admin/temporadas') }}" class="detailed">Temporadas</a></li>
				      	<li class="{{ Request::path() == 'admin/usuarios' ? 'active' : '' }}"><a href="{{ url('admin/usuarios') }}" class="detailed">Usuarios</a></li>
				      	<li class="{{ Request::path() == 'admin/clientes' ? 'active' : '' }}"><a href="{{ url('admin/clientes') }}" class="detailed">Clientes</a></li>
				      	<li class="{{ Request::path() == 'admin/liquidacion' || Request::path() == 'admin/liquidacion-apartamentos' || Request::path() == 'admin/pagos-propietarios' || Request::path() == 'admin/pagos-estadisticas' || Request::path() == 'admin/perdidas-ganancias' ? 'active' : '' }}"><a href="{{ url('admin/liquidacion') }}" class="detailed">Liquidacion</a></li>
			        </ul>
			        <ul class="nav navbar-nav navbar-right">
			            <li><a href="{{url ('/logout')}}">Log Out</a></li>
			        </ul>
			    </div>
			    <?php if (stristr(Request::path(), 'liquidacion') == TRUE || Request::path() == 'admin/liquidacion-apartamentos' || Request::path() == 'admin/pagos-propietarios' || Request::path() == 'admin/pagos-estadisticas' || Request::path() == 'admin/perdidas-ganancias' ): ?>
				    <div class="navbar-collapse collapse">
		    	        @yield('headerButtoms') 
				    </div>
			    <?php endif ?>

			</nav>
		<?php else: ?>
		<?php endif ?>
		<!-- BEGIN SIDEBPANEL-->
		
		<!-- END SIDEBAR -->
		<!-- START PAGE-CONTAINER -->
			<div class="page-container ">			
				<div class="page-content-wrapper ">

					<div class="content sm-gutter " style="padding-left: 0px!important;padding-top: 0px!important;">
						@yield('content')
					</div>
					<!-- END CONTENT -->
				</div>
			</div>
		<!-- END CONTAINER FLUID -->
	</div>
	<!-- FOOTER -->
	<!-- <div class="container-fluid container-fixed-lg footer">
		<div class="copyright sm-text-center">
			<p class="small no-margin pull-left sm-pull-reset">
				<span class="hint-text">Copyright &copy; 2014 </span>
				<span class="font-montserrat">REVOX</span>.
				<span class="hint-text">All rights reserved. </span>
				<span class="sm-block"><a href="#" class="m-l-10 m-r-10">Terms of use</a> | <a href="#" class="m-l-10">Privacy Policy</a></span>
			</p>
			<p class="small no-margin pull-right sm-pull-reset">
				<a href="#">Hand-crafted</a> <span class="hint-text">&amp; Made with Love ®</span>
			</p>
			<div class="clearfix"></div>
		</div>
	</div> -->
	<!-- END FOOTER -->
</div>
	<!-- END PAGE CONTENT WRAPPER -->
	<!-- END PAGE CONTAINER -->

			
		<!-- BEGIN VENDOR JS -->
		<script src="{{ asset('assets/plugins/pace/pace.min.js') }}" type="text/javascript"></script>
		<script src="{{ asset('assets/plugins/jquery/jquery-1.11.1.min.js') }}" type="text/javascript"></script>
		<script src="{{ asset('assets/plugins/modernizr.custom.js') }}" type="text/javascript"></script>
		<script src="{{ asset('assets/plugins/jquery-ui/jquery-ui.min.js') }}" type="text/javascript"></script>
		<script src="{{ asset('assets/plugins/bootstrapv3/js/bootstrap.min.js') }}" type="text/javascript"></script>
		<script src="{{ asset('assets/plugins/jquery/jquery-easy.js') }}" type="text/javascript"></script>
		<script src="{{ asset('assets/plugins/jquery-unveil/jquery.unveil.min.js') }}" type="text/javascript"></script>
		<script src="{{ asset('assets/plugins/jquery-bez/jquery.bez.min.js') }}"></script>
		<script src="{{ asset('assets/plugins/jquery-ios-list/jquery.ioslist.min.js') }}" type="text/javascript"></script>
		<script src="{{ asset('assets/plugins/jquery-actual/jquery.actual.min.js') }}"></script>
		<script src="{{ asset('assets/plugins/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
		<script type="text/javascript" src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
		<script type="text/javascript" src="{{ asset('assets/plugins/classie/classie.js') }}"></script>
		<script src="{{ asset('assets/plugins/switchery/js/switchery.min.js') }}" type="text/javascript"></script>
		<script src="{{ asset('assets/plugins/nvd3/lib/d3.v3.js') }}" type="text/javascript"></script>
		<script src="{{ asset('assets/plugins/nvd3/nv.d3.min.js') }}" type="text/javascript"></script>
		<script src="{{ asset('assets/plugins/nvd3/src/utils.js') }}" type="text/javascript"></script>
		<script src="{{ asset('assets/plugins/nvd3/src/tooltip.js') }}" type="text/javascript"></script>
		<script src="{{ asset('assets/plugins/nvd3/src/interactiveLayer.js') }}" type="text/javascript"></script>
		<script src="{{ asset('assets/plugins/nvd3/src/models/axis.js') }}" type="text/javascript"></script>
		<script src="{{ asset('assets/plugins/nvd3/src/models/line.js') }}" type="text/javascript"></script>
		<script src="{{ asset('assets/plugins/nvd3/src/models/lineWithFocusChart.js') }}" type="text/javascript"></script>
		<script src="{{ asset('assets/plugins/mapplic/js/hammer.js') }}"></script>
		<script src="{{ asset('assets/plugins/mapplic/js/jquery.mousewheel.js') }}"></script>
		<script src="{{ asset('assets/plugins/mapplic/js/mapplic.js') }}"></script>
		<script src="{{ asset('assets/plugins/rickshaw/rickshaw.min.js') }}"></script>
		<script src="{{ asset('assets/plugins/jquery-metrojs/MetroJs.min.js') }}" type="text/javascript"></script>
		<script src="{{ asset('assets/plugins/jquery-sparkline/jquery.sparkline.min.js') }}" type="text/javascript"></script>
		<script src="{{ asset('assets/plugins/skycons/skycons.js') }}" type="text/javascript"></script>
		<script src="{{ asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}" type="text/javascript"></script>
		@yield('scripts')
		<!-- END VENDOR JS -->
		<!-- BEGIN CORE TEMPLATE JS -->
		<script src="{{ asset('pages/js/pages.min.js') }}"></script>
		<!-- END CORE TEMPLATE JS -->
		<!-- BEGIN PAGE LEVEL JS -->
		<script src="{{ asset('assets/js/datatables.js') }}" type="text/javascript"></script>
		 <script src="{{ asset('assets/js/form_elements.js') }}" type="text/javascript"></script>
		<script src="{{ asset('assets/js/scripts.js') }}" type="text/javascript"></script>

		
		<!-- END PAGE LEVEL JS -->
	</body>
</html>