<!DOCTYPE html>
<html dir="ltr" lang="es-ES">
    <head>
        <meta name="description" content="Apartamentos de lujo en Sierra Nievada a pie de pista , sin remontes ni autobuses">
        <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <meta name="keywords" content="Alquiler apartamento Sierra Nevada;edificio miramarski; a pie de pista; apartamentos capacidad 6 / 8 personas; estudios capacidad 4 /5 personas; zona baja;piscina climatizada;gimansio;parking cubierto; a 5 minutos  de la plaza de Andalucia">

        <link href="//fonts.googleapis.com/css?family=Open+Sans%3A800|Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700,800,900|Crete+Round:400italic" rel="stylesheet" type="text/css"/>

        <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('/img/miramarski/favicon/apple-icon-57x57.png') }}">
        <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('/img/miramarski/favicon/apple-icon-60x60.png') }}">
        <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('/img/miramarski/favicon/apple-icon-72x72.png') }}">
        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('/img/miramarski/favicon/apple-icon-76x76.png') }}">
        <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('/img/miramarski/favicon/apple-icon-114x114.png') }}">
        <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('/img/miramarski/favicon/apple-icon-120x120.png') }}">
        <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('/img/miramarski/favicon/apple-icon-144x144.png') }}">
        <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('/img/miramarski/favicon/apple-icon-152x152.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/img/miramarski/favicon/apple-icon-180x180.png') }}">
        <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('/img/miramarski/favicon/android-icon-192x192.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/img/miramarski/favicon/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('/img/miramarski/favicon/favicon-96x96.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/img/miramarski/favicon/favicon-16x16.png') }}">
        <link rel="manifest" href="/manifest.json">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="{{ asset('/img/miramarski/favicon/ms-icon-144x144.png' ) }}">
        <meta name="theme-color" content="#ffffff">

        <link rel="stylesheet" href="{{ asset ('/css/app.css')}}" type="text/css"/>

        <link rel="stylesheet" type="text/css" href="{{ asset ('/frontend/css/animate.css')}}">
        <link rel="stylesheet" type="text/css" href="{{ asset ('/frontend/css/magnific-popup.css')}}">

        <?php if ($mobile->isMobile() || $mobile->isTablet()): ?>
            <link rel="stylesheet" href="{{ asset ('/frontend/css/responsive-mobile.css')}}" type="text/css"/>
        <?php else: ?>
            <link rel="stylesheet" href="{{ asset ('/frontend/css/responsive.css')}}" type="text/css"/>
        <?php endif; ?>

        <link rel="stylesheet" href="{{ asset ('/frontend/colors.php?color=3F51B5')}}" type="text/css"/>

        <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/>

        <link rel="stylesheet" type="text/css" href="{{ asset ('/frontend/include/rs-plugin/css/settings.css')}}">
        <link rel="stylesheet" type="text/css" href="{{ asset ('/frontend/include/rs-plugin/css/layers.css')}}">
        <link rel="stylesheet" type="text/css" href="{{ asset ('/frontend/include/rs-plugin/css/navigation.css')}}">

        <link rel="stylesheet" href="{{ asset('/frontend/css/font-icons.css')}}" type="text/css"/>

        <link rel="stylesheet" href="{{ asset('/frontend/css/aos.css')}}" type="text/css"/>

        <link rel="stylesheet" href="{{ asset('/frontend/css/components/daterangepicker.css')}}" type="text/css"/>

        <link href="{{ asset('/frontend/hover.css')}}" rel="stylesheet" media="all">
        <link rel="stylesheet" href="{{ asset('/frontend/css/components/radio-checkbox.css')}}" type="text/css"/>
        <link rel="stylesheet" href="{{ asset('/frontend/custom.css')}}" type="text/css"/>

        <link rel="stylesheet" type="text/css" href="{{ asset('/frontend/css/normalize.css')}}" />
        {{--<link rel="stylesheet" type="text/css" href="{{ asset('/frontend/css/fourBoxSlider.css')}}" />--}}
        <link rel="stylesheet" type="text/css" href="{{ asset('/frontend/css/fourBoxSlider.min.css')}}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('/frontend/css/component.css')}}" />    
        <script src="{{asset('/frontend/js/modernizr.custom.js')}}"></script>

        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-66225892-1"></script>
        <script async>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }

            gtag('js', new Date());

            gtag('config', 'UA-66225892-1');
        </script>

        <script async type="text/javascript">
            (function (i, s, o, g, r, a, m) {
                i['GoogleAnalyticsObject'] = r;
                i[r] = i[r] || function () {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
                a = s.createElement(o),
                        m = s.getElementsByTagName(o)[0];
                a.async = 1;
                a.src = g;
                m.parentNode.insertBefore(a, m)
            })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');
            ga('create', 'UA-66225892-1', 'auto');
            ga('send', 'pageview');
        </script>

        <title>@yield('title')</title>

        <?php if ($mobile->isMobile()): ?>
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

                span.prev, span.next{
                    height:6% !important;
                    width:5px !important;
                }

                .boxgallery > nav span {
                    width: 30px !important;
                    background:rgba(0,0,0,0.5);
                    border-radius: 0 5px 5px 0px;
                }

                .boxgallery > nav span.prev {
                    border-radius: 0 5px 5px 0px;
                }

                .boxgallery > nav span.next {
                    border-radius: 5px 0 0 5px;
                }

                .boxgallery > nav span.prev::before, .boxgallery > nav span.prev::after, .boxgallery > nav span.prev i::before, .boxgallery > nav span.prev i::after{
                    left:25% !important;
                }

                .boxgallery > nav span.next::before, .boxgallery > nav span.next::after, .boxgallery > nav span.next i::before, .boxgallery > nav span.next i::after{
                    left:75% !important;
                }

                html, body, .container{
                    background-color:none !important;
                    background:none !important;
                }

                /*.degradado-background1, .degradado-background2 {
                   background-color: #FFFFFF !important;
               }*/

                section.page-section.degradado-background1{
                    background-color:#FFFFFF !important;
                }

            </style>
        <?php else: ?>

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

                section#content{
                    top:40px;
                }
                
                html, body, .container{
                    background-color:none !important;
                    background:none !important;
                }
                
                #header.transparent-header {
                    z-index: 2000 !important;
                }

            </style>

        <?php endif; ?>

        <style>

            @media screen and (max-width: 767px) {
                #primary-menu.style-2 {
                    position: absolute;
                    left: 15px;
                    top: 80px;
                    width: 90%;
                }
            }

            a.blog_link{
                color:#3F51B5;
            }

            a.blog_link:hover{
                color:#000000;
            }


            #logo a, body {
                color: #000;
            }
            
            @media screen and (max-width: 990px){
                  div#content-book{
                      min-height: 980px !important;
                  }
            }
               
            @media screen and (max-width: 767px){
                     div#content-book{
                         min-height: 750px !important;
                     }
            }
            .claim-icons.claim-icons-big {
                padding: 30px 0 0;
            }

            .claim-icons.claim-icons-big .claim-icons-img {
                width: 91px;
            }

            .claim-icons .claim-icons-img {
                background: rgba(153,17,85,1);
                border-radius: 300px;
                box-shadow: 0 0 0 8px rgba(204,204,204,1);
                margin: 0 auto 15px;
            }
            span.forfait_icons{font-size:16px;}
        </style>
        <script type="text/javascript">
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
              m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

          ga('create', 'UA-66225892-1', 'auto');
          ga('send', 'pageview');

        </script>
        <!-- Google Code for conversiones_lead Conversion Page -->
        <script async type="text/javascript">
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

<body class="stretched not-dark" data-loader="5">

    <div id="" class="clearfix">

        @include('layouts._header')

        @include('frontend.slider')

        @yield('content')

        @include('layouts._footer')

    </div>

    <div id="gotoTop" class="fa fa-chevron-up" style="bottom:100px; right:15px;"></div>

    @include('layouts._generalScripts')

</html>