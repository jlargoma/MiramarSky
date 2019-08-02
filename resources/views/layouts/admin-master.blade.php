<!DOCTYPE html>
<html>
    <head>

        <meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
        <meta charset="utf-8"/>
        <title>@yield('title')</title>
        <meta name="viewport"
              content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no"/>
        <meta name="TWpUeE0zeDhNZkQ2STF3ZU1mVHhjcT0y" value="1003272df3af89e0ab299138ff66db15"/>
        <link rel="apple-touch-icon" href="pages/ico/60.png">
        <link rel="apple-touch-icon" sizes="76x76" href="pages/ico/76.png">
        <link rel="apple-touch-icon" sizes="120x120" href="pages/ico/120.png">
        <link rel="apple-touch-icon" sizes="152x152" href="pages/ico/152.png">
        <link rel="icon" type="image/x-icon" href="/favicon.ico"/>
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-touch-fullscreen" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">
        <meta content="" name="description"/>
        <meta content="" name="author"/>
        <link href="{{ asset('/assets/plugins/pace/pace-theme-flash.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('/assets/plugins/bootstrapv3/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.9/css/all.css"
              integrity="sha384-5SOiIsAziJl6AWe0HWRKTXlfcSHKmYV4RBF18PPJ173Kzn7jzMyFuTtk8JA7QQG1" crossorigin="anonymous">
        <link href="{{ asset('/assets/plugins/jquery-scrollbar/jquery.scrollbar.css') }}" rel="stylesheet" type="text/css"
              media="screen"/>
        <link href="{{ asset('/assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"
              media="screen"/>
        <link href="{{ asset('/assets/plugins/switchery/css/switchery.min.css') }}" rel="stylesheet" type="text/css"
              media="screen"/>
        <link href="{{ asset('/assets/plugins/nvd3/nv.d3.min.css') }}" rel="stylesheet" type="text/css" media="screen"/>
        <link href="{{ asset('/assets/plugins/mapplic/css/mapplic.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('/assets/plugins/rickshaw/rickshaw.min.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('/assets/plugins/bootstrap-datepicker/css/datepicker3.css') }}" rel="stylesheet"
              type="text/css" media="screen">
        <link href="{{ asset('/assets/plugins/jquery-metrojs/MetroJs.css') }}" rel="stylesheet" type="text/css"
              media="screen"/>
        <link href="{{ asset('/pages/css/pages-icons.css') }}" rel="stylesheet" type="text/css">
        <link class="main-stylesheet" href="{{ asset('/pages/css/pages.css') }}" rel="stylesheet" type="text/css"/>
        <script src="//code.jquery.com/jquery.js"></script>
        <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"
              integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
        @yield('externalScripts')
           <!--[if lte IE 9]>
        <link href="/assets/plugins/codrops-dialogFx/dialog.ie.css" rel="stylesheet" type="text/css" media="screen"/>
        <![endif]-->
        <link rel="stylesheet" type="text/css" href="{{ asset('/pages/css/custom.css')}}">

        <style>
            .phpdebugbar.phpdebugbar-minimized {
                display: none !important
            }

            .navbar-inverse .navbar-nav > li > a {
                color: white;
                font-weight: bold;
                font-size: 14px;
            }

            .navbar {
                margin-bottom: 0px;
            }

            .navbar-toggle {
                float: left !important;
                margin-left: 10px !important;
            }

            .navbar-inverse .navbar-nav > .active > a, .navbar-inverse .navbar-nav > .active > a:focus, .navbar-inverse .navbar-nav > .active > a:hover {
                background: rgb(81, 81, 81);
            }

        </style>
        <?php
        use App\Classes\Mobile;
        $mobile = new Mobile();
        ?>
    </head>
    <body class="fixed-header   windows desktop pace-done sidebar-visible menu-pin" style="padding-top:0px!important">
        <!-- <body class="fixed-header dashboard  windows desktop sidebar-visible pace-done menu-pin"> -->
        <?php if (preg_match('/subadmin/i', Auth::user()->role) || preg_match('/admin/i', Auth::user()->role) || preg_match('/agente/i', Auth::user()->role)): ?>
            <nav class="navbar navbar-inverse" role="navigation">
            <?php if (env('APP_APPLICATION') == "riad"): ?>
                <a class="navbar-brand" href="{{ route('dashboard.planning') }}" style="max-width: 155px;">
                    <img src="{{ asset('img/riad/logo_riad_b.png') }}" alt="" style="width: 100%">
                </a>
            <?php endif; ?>
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav navbar-left">

                    <li class="{{ Request::path() == 'admin/reservas' ? 'active' : '' }}"><a href="{{ url('admin/reservas') }}"
                                                                                             class="detailed">Reservas</a></li>

                    <?php if ( Auth::user()->role == "admin" || Auth::user()->role == "subadmin"): ?>
                        <li class="{{ Request::path() == 'admin/liquidacion'  ? 'active' : '' }}">
                            <a href="{{ url('admin/liquidacion') }}" class="detailed">Liq. por reservas</a>
                        </li>

                        {{--<li class="{{ Request::path() == 'admin/pagos-propietarios'  ? 'active' : '' }}">--}}
                            {{--<a href="{{ url('admin/pagos-propietarios') }}" class="detailed">Pagos a propietarios</a>--}}
                        {{--</li>--}}


                        <li class="{{ Request::path() == 'admin/contabilidad'  ? 'active' : '' }}">
                            <a href="{{ url('admin/contabilidad') }}" class="detailed">Contabilidad</a>
                        </li>
                    <?php endif ?>
                    <?php if ( Auth::user()->role == "admin" ): ?>
                    <?php
	                    $defaultRoom = '';
                        if ( count(Auth::user()->rooms) > 0)
                        {
                            foreach (Auth::user()->rooms as $room)
                            {
	                            $defaultRoom = $room->name;
	                            break;
                            }
                        }
                    ?>
                    {{--<li class="{{  (preg_match('/propietario/i',Request::path()))  ? 'active' : '' }}">--}}
                        {{--<a href="{{ url('admin/propietario/'. $defaultRoom) }}" class="detailed">Area Propietario</a>--}}
                    {{--</li>--}}

                    <li class="{{ Request::path() == 'admin/precios' ? 'active' : '' }}">
                        <a href="{{ url('admin/precios') }}" class="detailed">Precios y temporadas</a>
                    </li>

                    <li class="{{ Request::path() == 'admin/usuarios' ? 'active' : '' }}">
                        <a href="{{ url('admin/usuarios') }}"  class="detailed">Usuarios</a>
                    </li>

                    <li class="{{ Request::path() == 'admin/clientes' ? 'active' : '' }}">
                        <a href="{{ url('admin/clientes') }}" class="detailed">Clientes</a>
                    </li>

                    <li class="{{ Request::path() == 'admin/apartamentos' ? 'active' : '' }}">
                        <a href="{{ url('admin/apartamentos') }}" class="detailed">Aptos</a>
                    </li>

                    {{--<li class="{{ Request::path() == 'admin/facturas' ? 'active' : '' }}">--}}
                        {{--<a href="{{ url('admin/facturas') }}"  class="detailed">Facturas</a>--}}
                    {{--</li>--}}

                    {{--<li class="{{ Request::path() == 'admin/encuestas' ? 'active' : '' }}">--}}
                    {{--<a href="{{ url('admin/encuestas') }}" class="detailed">Encuestas</a>--}}
                    {{--</li>--}}

                    {{--<li class="{{ Request::path() == 'admin/supermercado' ? 'active' : '' }}">--}}
                    {{--<a href="#" class="detailed">Super</a>--}}
                    {{--</li>--}}

                    {{--<li class="{{ Request::path() == 'admin/forfaits' ? 'active' : '' }}"><a href="{{ url('admin/forfaits') }}" class="detailed">Forfaits</a></li>--}}

                    <li class="{{ Request::path() == 'admin/settings' ? 'active' : '' }}">
                        <a href="{{ url('admin/settings') }}" class="detailed">Settings</a>
                    </li>
                    {{--<li class="{{ Request::path() == 'admin/stripe-connect' ? 'active' : '' }}">--}}
                        {{--<a href="{{ url('admin/stripe-connect') }}" class="detailed">Stripe Connect</a>--}}
                    {{--</li>--}}
                    
                    <li class="{{ Request::path() == 'admin/settings_msgs' ? 'active' : '' }}">
                        <a href="{{ url('admin/settings_msgs') }}" class="detailed">Txt Email</a>
                    </li>
                    <?php endif ?>

                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li style="color:white"><a href="#"
                                               style="pointer-events: none"><?php echo ucwords(Auth::user()->name) ?></a></li>
                    <li><a href="{{url ('/logout')}}">Log Out</a></li>
                </ul>
            </div>
            <?php if (stristr(Request::path(), 'liquidacion') == TRUE || Request::path() == 'admin/liquidacion-apartamentos' || Request::path() == 'admin/pagos-propietarios' || Request::path() == 'admin/pagos-estadisticas' || Request::path() == 'admin/perdidas-ganancias' ): ?>
                <div class="navbar-collapse collapse">
                    @yield('headerButtoms')
                </div>
            <?php endif ?>

        </nav>
        <?php endif ?>
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
        <div class="overlay loading-box" id="loadigPage">
          <div >
            <h3 class="text-center"><i class="fas fa-spinner fa-spin"></i>Loading...</h3>
          </div>
        </div>
        <div class="overlay message-bottom-box" id="bottom_msg">
          <div id="bottom_msg_text">
          </div>
        </div>
        <!-- BEGIN VENDOR JS -->
        <script src="{{ asset('assets/plugins/pace/pace.min.js') }}" type="text/javascript"></script>

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
        <script type="text/javascript" src="{{ asset('/pages/js/bootstrap-notify.js')}}"></script>

        <script src="{{ asset('pages/js/pages.min.js') }}"></script>
        <script src="{{ asset('js/custom.js') }}"></script>
        @yield('scripts')
    </body>
</html>