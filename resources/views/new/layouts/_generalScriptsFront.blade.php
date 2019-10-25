<!-- before general -->


<script type="text/javascript" src="{{ assetNew('/frontend/js/plugins.js')}}"></script>
<script type="text/javascript" src="{{ assetNew('/frontend/js/functions.js')}}"></script>
<script type="text/javascript" src="{{ assetNew('/frontend/js/progressbar.min.js')}}"></script>
<script type="text/javascript" src="{{ assetNew('/frontend/js/scripts.js')}}"></script>

<script type="text/javascript" src="{{ assetV('/js/flip.min.js')}}"></script>

<script type="text/javascript" src="{{assetNew('/frontend/js/components/moment.min.js')}}"></script>
<script type="text/javascript" src="{{assetNew('/frontend/js/components/daterangepicker.min.js')}}"></script>
<script type="text/javascript" src="{{assetNew('/frontend/js/aos/2.1.1/aos.js')}}"></script>

<script type="text/javascript" src="{{assetNew('/frontend/js/jquery.flip/1.1.2/jquery.flip.min.js')}}"></script>

<script src='https://www.google.com/recaptcha/api.js?render=6LdOoYYUAAAAAPKBszrHm6BWXPE8Gfm3ywnoOEUV'></script>

<?php /* view para todos los scripts generales de la pagina */ ?>

<!-- general scripts -->

<script type="text/javascript">
 

</script>

@yield('scripts')

<script type="text/javascript">

   <?php if (!$mobile->isMobile()): ?>
    $('div.bg-img img').attr('style', 'max-width:none !important;');
   <?php else: ?>
    $('div.bg-img img').attr('style', 'max-width:none !important;margin-left: -67.2vw;');
   <?php endif; ?>

    $('div.bg-img').attr('style', 'background:none !important;');
    $('div#boxgallery span.prev, div#boxgallery span.next').attr('style', 'z-index:10000;');

    $('div#blank_loader').fadeOut(500);


</script>
<script>
  AOS.init();
</script>

</body>