<!-- before general -->

<!--<script src='https://www.google.com/recaptcha/api.js?render=6LdOoYYUAAAAAPKBszrHm6BWXPE8Gfm3ywnoOEUV' async="async"></script>-->

<?php /* view para todos los scripts generales de la pagina */ ?>

@yield('scripts')

<script type="text/javascript">
  var LoadJs="{{ getCloudfl(assetV('/js/scripts-footer-min.js'))}}";
  function LoadImgs(){
    $( ".loadJS" ).each(function( index ) {
       $( this ).attr('src',$( this ).data('src'));
    });
  }
  function LoadImgsBackground(){
    $( ".loadJSBackground" ).each(function( index ) {
      $(this).css("background-image", "url('"+$( this ).data('src')+"')"); 
//       $( this ).attr('src',$( this ).data('src'));
    });
  }
$(document).ready(function () {
   <?php if (!$mobile->isMobile()): ?>
    $('div.bg-img img').attr('style', 'max-width:none !important;');
   <?php else: ?>
    $('div.bg-img img').attr('style', 'max-width:none !important;margin-left: -67.2vw;');
   <?php endif; ?>

    $('div.bg-img').attr('style', 'background:none !important;');
    $('div#boxgallery span.prev, div#boxgallery span.next').attr('style', 'z-index:10000;');

    $('div#blank_loader').fadeOut(500);

  @if(($mobile->isMobile() || $mobile->isTablet()))
    var my_awesome_script = document.createElement('script');
            my_awesome_script.setAttribute('src',"{{ getCloudfl(assetV('/js/scripts-ext.js'))}}");
            document.body.appendChild(my_awesome_script);
      setTimeout(
          function(){
            var my_awesome_style = document.createElement('link');
            my_awesome_style.setAttribute('href',"{{ getCloudfl(assetV('/frontend/css/responsive-mobile.css'))}}");
            my_awesome_style.setAttribute('type','text/css');
            my_awesome_style.setAttribute('rel','stylesheet');
            document.body.appendChild(my_awesome_style);
            
            var recaptcha_script = document.createElement('script');
            recaptcha_script.setAttribute('src',"https://www.google.com/recaptcha/api.js?render=6LdOoYYUAAAAAPKBszrHm6BWXPE8Gfm3ywnoOEUV");
            document.body.appendChild(recaptcha_script);
          },1700);
  
  @endif
  
 
  });
</script>

</body>