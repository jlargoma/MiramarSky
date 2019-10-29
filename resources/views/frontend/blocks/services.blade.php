<?php
$oContents = new App\Contents();
$services = $oContents->getContentByKey('services');
?>


<section class="page-section darkgrey">
  <div class="row" style="margin:2em auto;">
    <h2 class="text-center white font-w300">
      OTROS SERVICIOS
    </h2>
    <div class="col-md-12 col-xs-12">
      <?php for($i=1;$i<4;$i++): ?>
      <div class="col-md-4   <?php echo ($i<3) ? 'col-xs-6' : 'col-xs-12'; ?> home-service">
        <a href="{{ $services['link_'.$i]}}">
          <div class=" container-image-box hover-effect">
              <img class="img-responsive imga"  src="{{ $services['imagen_'.$i]}}"  alt="{{$services['title_'.$i]}}"/>
            <div class="text-right overlay-text">
              <h2 class="font-w200 center push-10 text-center text font-s24 white">
                <span class="font-w800 white">{{$services['title_'.$i]}}</span>
              </h2>
            </div>
          </div>
        </a>
      </div>
      <?php endfor; ?>

    </div>
  </div>
</section>
<style>
  @media only screen and (max-width: 1356px){
    .home-service .container-image-box{
      height: 220px;
    }
  }
  
  @media only screen and (max-width: 1134px){
    .home-service .container-image-box{
      height: 190px;
    }
  }
  @media only screen and (max-width: 991px){
    .col-xs-12.home-service .container-image-box{
      height: 290px;
    }
  }
  @media only screen and (max-width: 632px){
    .home-service .container-image-box{
          height: 150px;
    }
  }
  @media only screen and (max-width: 540px){
    .home-service .container-image-box{
      max-height: 260px;
      height: auto !important;
    }
  }
  
  </style>