<!-- before general -->

<!--<script src='https://www.google.com/recaptcha/api.js?render=6LdOoYYUAAAAAPKBszrHm6BWXPE8Gfm3ywnoOEUV' async="async"></script>-->

<?php /* view para todos los scripts generales de la pagina */ ?>

@yield('scripts')

<script type="text/javascript">
  var LoadJs = "{{ getCloudfl(asset('/js/scripts-footer-min-v2.js'))}}";
  function LoadImgs() {
    $(".loadJS").each(function (index) {
      $(this).attr('src', $(this).data('src'));
    });
  }
  function LoadImgsBackground() {
    $(".loadJSBackground").each(function (index) {
      $(this).css("background-image", "url('" + $(this).data('src') + "')");
    });
  }
  $('div.bg-img').attr('style', 'background:none !important;');
  $('div#boxgallery span.prev, div#boxgallery span.next').attr('style', 'z-index:10000;');

  $('div#blank_loader').fadeOut(500);
</script>

<?php
if (($mobile->isMobile() || $mobile->isTablet())):
  ?>

  <script type="text/javascript">
    $(document).ready(function () {
      $('div.bg-img img').attr('style', 'max-width:none !important;margin-left: -67.2vw;');
      setTimeout(
              function () {
                var my_awesome_script = document.createElement('script');
                my_awesome_script.setAttribute('src', "{{ getCloudfl(asset('/js/scripts-ext-v2.js'))}}");
                document.body.appendChild(my_awesome_script);

                var my_awesome_style = document.createElement('link');
                my_awesome_style.setAttribute('href', "{{ getCloudfl(assetV('/frontend/css/responsive-mobile.css'))}}");
                my_awesome_style.setAttribute('type', 'text/css');
                my_awesome_style.setAttribute('rel', 'stylesheet');
                document.body.appendChild(my_awesome_style);

                var recaptcha_script = document.createElement('script');
                recaptcha_script.setAttribute('src', "https://www.google.com/recaptcha/api.js?render=6LdOoYYUAAAAAPKBszrHm6BWXPE8Gfm3ywnoOEUV");
                document.body.appendChild(recaptcha_script);
                $('.carousel-caption').css('display','block');
              }, 1700);
    });
  </script>
<?php else: ?>

  <script type="text/javascript">
    $(document).ready(function () {
      $('div.bg-img img').attr('style', 'max-width:none !important;');
    });
  </script>
  <link href="//fonts.googleapis.com/css?family=Open+Sans%3A800|Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700,800,900|Crete+Round:400italic" rel="stylesheet" type="text/css"/>
  <link rel="stylesheet" href="{{ getCloudfl(assetV('/frontend/css/responsive.css'))}}" type="text/css"/>
  <script src="{{ getCloudfl(assetV('/frontend/vendor/lightslider-master/dist/js/lightslider.min.js'))}}"  defer=""></script>
  <script type="text/javascript" src="{{ getCloudfl(assetV('/js/scripts-ext.js'))}}" async="async"></script>
<?php endif; ?>



</body>