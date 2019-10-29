<section class="page-section darkgrey">
  <div class="row" style="margin:2em auto;">
    <h2 class="text-center white font-w300">
      OTROS SERVICIOS
    </h2>
    <div class="col-md-12 col-xs-12">
      <div class="col-md-4 col-xs-6 home-service ">
        <a href="{{ url('/restaurantes')}}">
          <div class=" container-image-box hover-effect">
            
              <img class="img-responsive imga"
                   src="{{ asset('/img/posts/restaurante-sierra-nevada.jpg')}}"
                   alt="Apartamento standard sierra nevada"/>
            <div class="text-right overlay-text">
              <h2 class="font-w200 center push-10 text-center text font-s24 white">
                <span class="font-w800 white"> BARES Y  RESTAURANTES</span>
              </h2>
            </div>
          </div>
        </a>
      </div>

      <div class="col-md-4 col-xs-6 home-service">
        <a href="//miramarski.com/forfait">
          <div class=" container-image-box  hover-effect">
            
              <img class="img-responsive imga"
                   src="{{ assetV('/frontend/images/home/fondo-servicio-1.jpg')}}"
                   alt="VENTA DE FORFAITS"/>
            <div class="text-right overlay-text">
              <h2 class="font-w200 center push-10 text-center text font-s24 white">
                <span class="font-w800 white" style="letter-spacing: -2px;">VENTA DE FORFAITS</span>
              </h2>
            </div>
          </div>
        </a>
      </div>

      <div class="col-md-4 col-xs-12 home-service">
        <a href="{{ url('//miramarski.com/forfait')}}">
          <div class=" container-image-box hover-effect">
            

              <img class="img-responsive"
                   src="{{ assetV('/frontend/images/home/fondo-servicio-2.jpg')}}"
                   alt="CLASES DE SKI"/>
            <div class="text-right overlay-text">
              <h2 class="font-w200 center push-10 text-center text font-s24 white">
                <span class="font-w800 white" style="letter-spacing: -2px;">CLASES DE SKI</span>
              </h2>
           
            </div>
          </div>
        </a>
      </div>
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