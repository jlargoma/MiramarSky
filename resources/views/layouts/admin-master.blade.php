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
	</head>
	<body class="fixed-header dashboard">
	<!-- <body class="fixed-header dashboard  windows desktop sidebar-visible pace-done menu-pin"> -->
	
		<!-- BEGIN SIDEBPANEL-->
		<nav class="page-sidebar" data-pages="sidebar">
			<!-- END SIDEBAR MENU TOP TRAY CONTENT-->
			<!-- BEGIN SIDEBAR MENU HEADER-->
			<div class="sidebar-header">
				<a href="{{ url('/admin') }}"><img src="{{asset ('assets/img/logo_white.png') }}" alt="logo" class="brand" data-src="{{asset ('assets/img/logo_white.png') }}" data-src-retina="{{asset ('assets/img/logo_white_2x.png') }}" width="78" height="22"></a>
			</div>
			<!-- END SIDEBAR MENU HEADER-->
			<!-- START SIDEBAR MENU -->
			<div class="sidebar-menu">
				<!-- BEGIN SIDEBAR MENU ITEMS-->
				<ul class="menu-items">

					<li class="m-t-10 ">
						<a href="{{ url('/reservas') }}" class="detailed">
							<span class="title">Planning</span>
							<!-- <span class="details">12 New Updates</span> -->
						</a>
						<span class="{{ Request::path() == '/reservas' ? 'bg-success' : '' }} icon-thumbnail"><i class="pg-calender"></i></span>
					</li>
					
					<li class="m-t-10 ">
						<a href="{{ url('/apartamentos') }}" class="detailed">
							<span class="title">Apartamentos</span>
						</a>
						<span class="{{ Request::path() == '/apartamentos' ? 'bg-success' : '' }} icon-thumbnail"><i class="pg-home"></i></span>
					</li>

					<li class="m-t-10 ">
						<a href="{{ url('/precios') }}" class="detailed">
							<span class="title">Precios</span>
						</a>
						<span class="{{ Request::path() == '/precios' ? 'bg-success' : '' }} icon-thumbnail"><i class="pg-home"></i></span>
					</li>

					<li class="m-t-10 ">
						<a href="{{ url('/temporadas') }}" class="detailed">
							<span class="title">Temporadas</span>
						</a>
						<span class="{{ Request::path() == '/temporadas' ? 'bg-success' : '' }} icon-thumbnail"><i class=" pg-clock"></i></span>
					</li>
					
					<li class="m-t-10 ">
						<a href="{{ url('/pagos') }}" class="detailed">
							<span class="title">Pagos de reservas</span>
						</a>
						<span class="{{ Request::path() == '/pagos' ? 'bg-success' : '' }} icon-thumbnail"><i class="fa fa-money"></i></span>
					</li>

					<li class="m-t-10 ">
						<a href="{{ url('/usuarios') }}" class="detailed">
							<span class="title">Usuarios</span>
						</a>
						<span class="{{ Request::path() == '/usuarios' ? 'bg-success' : '' }} icon-thumbnail"><i class="fa fa-user"></i></span>
					</li>

					<li class="m-t-10 ">
						<a href="{{ url('/clientes') }}" class="detailed">
							<span class="title">Clientes</span>
						</a>
						<span class="{{ Request::path() == '/clientes' ? 'bg-success' : '' }} icon-thumbnail"><i class="fa  fa-users"></i></span>
					</li>
				</ul>
				<div class="clearfix"></div>
			</div>
			<!-- END SIDEBAR MENU -->
		</nav>
		<!-- END SIDEBAR -->
		<!-- START PAGE-CONTAINER -->
		<div class="page-container ">
			<!-- START HEADER -->
			<div class="header ">
				<!-- START MOBILE CONTROLS -->
				<div class="container-fluid relative">
					<!-- LEFT SIDE -->
					<div class="pull-left full-height visible-sm visible-xs">
						<!-- START ACTION BAR -->
						<div class="header-inner">
							<a href="#" class="btn-link toggle-sidebar visible-sm-inline-block visible-xs-inline-block padding-5" data-toggle="sidebar">
								<span class="icon-set menu-hambuger"></span>
							</a>
						</div>
						<!-- END ACTION BAR -->
					</div>
					<div class="pull-center hidden-md hidden-lg">
						<div class="header-inner">
							<div class="brand inline">
								<img src="{{asset ('assets/img/logo.png') }}" alt="logo" data-src="{{asset ('assets/img/logo.png') }}" data-src-retina="{{asset ('assets/img/logo_2x.png') }}" width="78" height="22">
							</div>
						</div>
					</div>
					<!-- RIGHT SIDE -->
					<div class="pull-right full-height visible-sm visible-xs">
						<!-- START ACTION BAR -->
						<div class="header-inner">
							<a href="#" class="btn-link visible-sm-inline-block visible-xs-inline-block" data-toggle="quickview" data-toggle-element="#quickview">
								<span class="icon-set menu-hambuger-plus"></span>
							</a>
						</div>
						<!-- END ACTION BAR -->
					</div>
				</div>
				<!-- END MOBILE CONTROLS -->
				<div class=" pull-left sm-table hidden-xs hidden-sm">
					<div class="header-inner">
						<div class="brand inline">
							<img src="{{asset ('assets/img/miramar-logo-t.png') }}" alt="logo" data-src="{{asset ('assets/img/miramar-logo-t.png') }}" data-src-retina="{{asset ('assets/img/miramar-logo-t.png') }}" width="50" height="43">
						</div>
						<!-- START NOTIFICATION LIST -->
						<!-- END NOTIFICATIONS LIST -->
						<a href="#" class="search-link" data-toggle="search"><i class="pg-search"></i>Type anywhere to <span class="bold">search</span></a> 
					</div>
				</div>
				<div class=" pull-right">
					<div class="header-inner">
						<a href="#" class="btn-link icon-set menu-hambuger-plus m-l-20 sm-no-margin hidden-sm hidden-xs" data-toggle="quickview" data-toggle-element="#quickview"></a>
					</div>
				</div>
				<div class=" pull-right">
					<!-- START User Info-->
					<div class="visible-lg visible-md m-t-10">
						<div class="pull-left p-r-10 p-t-10 fs-16 font-heading">
							<span class="semi-bold">{{ Auth::user()->name }}</span>
						</div>
						<div class="dropdown pull-right">
							<button class="profile-dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<span class="thumbnail-wrapper d32 circular inline m-t-5">
									<img src="{{asset ('assets/img/profiles/avatar.jpg') }}" alt="" data-src="{{asset ('assets/img/profiles/avatar.jpg') }}" data-src-retina="{{asset ('assets/img/profiles/avatar_small2x.jpg') }}" width="32" height="32">
								</span>
							</button>
							<ul class="dropdown-menu profile-dropdown" role="menu">
								<li><a href="#"><i class="pg-settings_small"></i> Settings</a>
								</li>
								<li class="bg-master-lighter">
									<a href="{{ url('/logout') }}" class="clearfix">
										<span class="pull-left">Logout</span>
										<span class="pull-right"><i class="pg-power"></i></span>
									</a>
								</li>
							</ul>
						</div>
					</div>
					<!-- END User Info-->
				</div>
			</div>
			<!-- END HEADER -->
			<!-- START CONTAINER FLUID -->
			<div class="page-content-wrapper ">
				<!-- START CONTENT -->
				<div class="content sm-gutter">

						@yield('content')
				</div>
				<!-- END CONTENT -->
			</div>
		</div>
		<!-- END CONTAINER FLUID -->
	</div>
	<!-- FOOTER -->
	<div class="container-fluid container-fixed-lg footer">
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
	</div>
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