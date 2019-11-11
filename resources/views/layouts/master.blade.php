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

        <script type="text/javascript" src="{{ assetV('/frontend/js/jquery-2.1.4.js')}}"></script><script src="{{assetV('/frontend/js/modernizr.custom.js')}}"></script>
        <title>@yield('title')</title>
<link rel="stylesheet" href="{{ assetV ('/css/frontend.css')}}" type="text/css"/>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-66225892-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-66225892-1');
</script>

</head>

<body class="stretched not-dark" data-loader="5">

    <div id="" class="clearfix">

        @include('layouts._header')

        @include('frontend.slider')

        @yield('content')
       

        @include('layouts._footer')

    <div id="gotoTop" class="fa fa-chevron-up" style="bottom:100px; right:15px;"></div>
 
    <link href="//fonts.googleapis.com/css?family=Open+Sans%3A800|Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700,800,900|Crete+Round:400italic" rel="stylesheet" type="text/css"/>
        
        
        <?php if ($mobile->isMobile() || $mobile->isTablet()): ?>
            <link rel="stylesheet" href="{{ assetV('/frontend/css/responsive-mobile.css')}}" type="text/css"/>
        <?php else: ?>
            <link rel="stylesheet" href="{{ assetV('/frontend/css/responsive.css')}}" type="text/css"/>
        <?php endif; ?>
        <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/>
    @include('layouts._generalScriptsFront')
     @yield('moreScripts')

     <style>

  
.header-extras-2 {
    float: none;
    width: 50%;
    /* margin: 0 auto; */
    position: absolute;
    /* float: right !important; */
    /* float: none; */
    right: 0;
}
.header-extras-2 li {
    float: right;
    margin-left: 20px;
    height: 40px;
    width: 40px;
    overflow: hidden;
    list-style: none;
    padding-top: 3px;
    /*display: inline;*/
}
#primary-menu.style-2{
      background-color: transparent;
}

  @media (max-width: 991px) {
    nav#primary-menu {
      display: none;
    }
    .primary-menu-open nav#primary-menu {
      display: block;
    }
    div#primary-menu-trigger {
      font-size: 25px;
      border: 1px solid;
      left: 1em;
      position: absolute;
      color: #3f51b5;
          width: auto;
    height: auto;
    line-height: initial;
    padding: 10px;

    }

    #primary-menu > ul,
    #primary-menu > div > ul {
      display: none;
      float: none;
      -webkit-transition: none;
      -o-transition: none;
      transition: none;
      margin-top: 5em !important;
      max-width: 90%;
    }
    div#top-bar {
    float: right;
    width: 80%;
    margin-top: 2em;
    background-color: transparent;
    border: none;
}
.header-extras-2{
      width: 100%;
}
.header-extras-2 li {
    margin-left: 0;
}

  nav#primary-menu {
    position: absolute;
    left: 25px;
    top: 10;
    width: 80%;
  }
  nav#primary-menu  .sf-js-enabled.show{
    background-color: #fff;
}
  }
    @media (max-width: 768px) {

    }
</style>
</html>
