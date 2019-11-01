<!DOCTYPE html>
<html dir="ltr" lang="es-ES">
    <head>
      <?php
      $oContents = new App\Contents();
      $meta_descripcion = $oContents->getContentByKey('meta_descripcion');
      ?>
        <meta name="description" content="{{$meta_descripcion['text']}}">
        <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <meta name="keywords" content="Alquiler apartamento Sierra Nevada;edificio miramarski; a pie de pista; apartamentos capacidad 6 / 8 personas; estudios capacidad 4 /5 personas; zona baja;piscina climatizada;gimansio;parking cubierto; a 5 minutos  de la plaza de Andalucia">
        <link href="//fonts.googleapis.com/css?family=Open+Sans%3A800|Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700,800,900|Crete+Round:400italic" rel="stylesheet" type="text/css"/>

        <link rel="apple-touch-icon" sizes="57x57" href="{{ assetV('/img/miramarski/favicon/apple-icon-57x57.png') }}">
        <link rel="apple-touch-icon" sizes="60x60" href="{{ assetV('/img/miramarski/favicon/apple-icon-60x60.png') }}">
        <link rel="apple-touch-icon" sizes="72x72" href="{{ assetV('/img/miramarski/favicon/apple-icon-72x72.png') }}">
        <link rel="apple-touch-icon" sizes="76x76" href="{{ assetV('/img/miramarski/favicon/apple-icon-76x76.png') }}">
        <link rel="apple-touch-icon" sizes="114x114" href="{{ assetV('/img/miramarski/favicon/apple-icon-114x114.png') }}">
        <link rel="apple-touch-icon" sizes="120x120" href="{{ assetV('/img/miramarski/favicon/apple-icon-120x120.png') }}">
        <link rel="apple-touch-icon" sizes="144x144" href="{{ assetV('/img/miramarski/favicon/apple-icon-144x144.png') }}">
        <link rel="apple-touch-icon" sizes="152x152" href="{{ assetV('/img/miramarski/favicon/apple-icon-152x152.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ assetV('/img/miramarski/favicon/apple-icon-180x180.png') }}">
        <link rel="icon" type="image/png" sizes="192x192" href="{{ assetV('/img/miramarski/favicon/android-icon-192x192.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ assetV('/img/miramarski/favicon/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="96x96" href="{{ assetV('/img/miramarski/favicon/favicon-96x96.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ assetV('/img/miramarski/favicon/favicon-16x16.png') }}">
        <!--<link rel="manifest" href="/manifest.json">-->
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="{{ assetV('/img/miramarski/favicon/ms-icon-144x144.png' ) }}">
        <meta name="theme-color" content="#ffffff">

        <link rel="stylesheet" href="{{ assetV ('/css/frontend.css')}}" type="text/css"/>
        
        <?php if ($mobile->isMobile() || $mobile->isTablet()): ?>
            <link rel="stylesheet" href="{{ assetV('/frontend/css/responsive-mobile.css')}}" type="text/css"/>
        <?php else: ?>
            <link rel="stylesheet" href="{{ assetV('/frontend/css/responsive.css')}}" type="text/css"/>
        <?php endif; ?>
        <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/>
        <script type="text/javascript" src="{{ assetV('/frontend/js/jquery-2.1.4.js')}}"></script><script src="{{assetV('/frontend/js/modernizr.custom.js')}}"></script>

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

    <div id="gotoTop" class="fa fa-chevron-up" style="bottom:100px; right:15px;"></div>
 
    @include('layouts._generalScriptsFront')
     @yield('moreScripts')

</html>
