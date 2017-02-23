<!DOCTYPE html>
    <head>
        <meta charset="utf-8">

        <title>@yield('title')</title>

        <meta name="description" content="Admin - New sponsor">
        <meta name="robots" content="noindex, nofollow">
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1.0">


        <link rel="shortcut icon" href="{{ asset('/admin-css/assets/img/favicons/favicon.png') }}">

        <link rel="icon" type="image/png" href="{{ asset('/admin-css/assets/img/favicons/favicon-16x16.png') }}" sizes="16x16">
        <link rel="icon" type="image/png" href="{{ asset('/admin-css/assets/img/favicons/favicon-32x32.png') }}" sizes="32x32">
        <link rel="icon" type="image/png" href="{{ asset('/admin-css/assets/img/favicons/favicon-96x96.png') }}" sizes="96x96">
        <link rel="icon" type="image/png" href="{{ asset('/admin-css/assets/img/favicons/favicon-160x160.png') }}" sizes="160x160">
        <link rel="icon" type="image/png" href="{{ asset('/admin-css/assets/img/favicons/favicon-192x192.png') }}" sizes="192x192">

        <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('/admin-css/assets/img/favicons/apple-touch-icon-57x57.png') }}">
        <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('/admin-css/assets/img/favicons/apple-touch-icon-60x60.png') }}">
        <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('/admin-css/assets/img/favicons/apple-touch-icon-72x72.png') }}">
        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('/admin-css/assets/img/favicons/apple-touch-icon-76x76.png') }}">
        <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('/admin-css/assets/img/favicons/apple-touch-icon-114x114.png') }}">
        <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('/admin-css/assets/img/favicons/apple-touch-icon-120x120.png') }}">
        <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('/admin-css/assets/img/favicons/apple-touch-icon-144x144.png') }}">
        <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('/admin-css/assets/img/favicons/apple-touch-icon-152x152.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/admin-css/assets/img/favicons/apple-touch-icon-180x180.png') }}">

        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400italic,600,700%7COpen+Sans:300,400,400italic,600,700">

        <link rel="stylesheet" href="{{ asset('/admin-css/assets/js/plugins/slick/slick.min.css') }}">
        <link rel="stylesheet" href="{{ asset('/admin-css/assets/js/plugins/slick/slick-theme.min.css') }}">

        <link rel="stylesheet" href="{{ asset('/admin-css/assets/css/bootstrap.min.css') }}">
        <link rel="stylesheet" id="css-main" href="{{ asset('/admin-css/assets/css/oneui.css') }}">
        @yield('externalScripts')
    </head>
    <body>
       
        <div id="page-container" class="sidebar-l sidebar-mini sidebar-o side-scroll header-navbar-fixed">            
            <nav id="sidebar">                
                <div id="sidebar-scroll">                                        
                    <div class="sidebar-content">                        
                        <div class="side-header side-content bg-white-op">                            
                            <button class="btn btn-link text-gray pull-right hidden-md hidden-lg" type="button" data-toggle="layout" data-action="sidebar_close">
                                <i class="fa fa-times"></i>
                            </button>
                            <a class="h5 text-white text-center" href="{{ url('/admin') }}">
                                <img class="img-responsive" align="middle" src="{{ asset ('/admin-css/assets/logo-blanco.png')}}" style="max-width: 30px;">
                                <span class="sidebar-mini-hide">MiramarSKI</span>
                            </a>
                        </div>                        
                        
                        <div class="side-content">
                            <ul class="nav-main">
                                <li>
                                    <a href="{{ url('/admin') }}" class="{{ Request::path() == 'admin' ? 'active' : '' }}">
                                        <i class="fa fa-calendar"></i><span class="sidebar-mini-hide">Planning</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ url('/admin/rooms') }}" class="{{ Request::path() == 'admin/rooms' ? 'active' : '' }}">
                                        <i class="fa fa-home"></i><span class="sidebar-mini-hide">Apartamentos</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ url('/admin/seasons') }}" class="{{ Request::path() == 'admin/seasons' ? 'active' : '' }}">
                                        <i class="fa fa-clock-o"></i><span class="sidebar-mini-hide">Temporadas</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ url('/admin/users') }}" class="{{ Request::path() == 'admin/users' ? 'active' : '' }}">
                                        <i class="fa fa-users"></i><span class="sidebar-mini-hide">Usuarios</span>
                                    </a>
                                </li>
                                <li  class="text-danger">
                                    <a tabindex="-1" href="{{ url('/logout') }}" alt="Salir" class="text-danger" style="color: #d26a5c;">
                                        <i class="fa fa-btn fa-sign-out"></i><span class="sidebar-mini-hide">Salir ({{ Auth::user()->name }})</span>
                                    </a>
                                </li>
                            </ul>
                        </div>                        
                    </div>                    
                </div>                
            </nav>            
            
            <header id="header-navbar" class="content-mini content-mini-full">                
                <ul class="nav-header pull-right">
                   
                </ul>                
                
                <ul class="nav-header pull-left">
                    <li class="hidden-md hidden-lg">                        
                        <button class="btn btn-default" data-toggle="layout" data-action="sidebar_toggle" type="button">
                            <i class="fa fa-navicon"></i>
                        </button>
                    </li>
                    <li class="hidden-xs hidden-sm">                        
                        <button class="btn btn-default" data-toggle="layout" data-action="sidebar_mini_toggle" type="button">
                            <i class="fa fa-ellipsis-v"></i>
                        </button>
                    </li>
                    <li class="js-header-search header-search">
                        <div class="form-material form-material-primary input-group remove-margin-t remove-margin-b">
                            <input class="form-control" type="text" id="searchRoutes" placeholder="Buscar...">
                            <span class="input-group-addon"><i class="si si-magnifier"></i></span>
                        </div>
                    </li>

                    @yield('headerButtoms')
                </ul>                
            </header>            


            <main id="main-container" >
                @yield('content')
            </main>

            <footer id="page-footer" class="content-mini content-mini-full font-s12 bg-white clearfix">
                <div class="content content-boxed">
                    <div class="pull-right">
                        
                    </div>
                    <div class="pull-left">
                        <a class="font-w600" href="/admin">MiramarSKI</a> &copy; <span class="js-year-copy"></span>
                    </div>  
                </div>
                
            </footer>
        </div>

        <script src="{{ asset('/admin-css/assets/js/core/jquery.min.js') }}"></script>
        <script src="{{ asset('/admin-css/assets/js/core/bootstrap.min.js') }}"></script>
        <script src="{{ asset('/admin-css/assets/js/core/jquery.slimscroll.min.js') }}"></script>
        <script src="{{ asset('/admin-css/assets/js/core/jquery.scrollLock.min.js') }}"></script>
        <script src="{{ asset('/admin-css/assets/js/core/jquery.appear.min.js') }}"></script>
        <script src="{{ asset('/admin-css/assets/js/core/jquery.countTo.min.js') }}"></script>
        <script src="{{ asset('/admin-css/assets/js/core/jquery.placeholder.min.js') }}"></script>
        <script src="{{ asset('/admin-css/assets/js/core/js.cookie.min.js') }}"></script>
        <script src="{{ asset('/admin-css/assets/js/app.js') }}"></script>
        @yield('scripts')
    </body>
</html>