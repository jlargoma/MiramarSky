<style type="text/css">

  .lSNext::before{
    content: ">";
    width: 0px;
    display: block;
    color: #fff;
    font-size: 4em;
    margin-left: -10px;
    margin-right: 0;
  }
  .lSPrev::before{
    content: "<";
    width: 0px;
    display: block;
    color: #fff;
    font-size: 4em;
    margin-right: -10px;
  }

  @media only screen and (max-width: 768px){
    .page-title {
      padding-top: 0px !important;
    }
  }

</style>
<style>

  .images-apto {
    /*opsition: relative;*/
    width: 100%;
    height: 70vh;
    background-size: cover;
    background-position: center center;
    background-repeat: no-repeat;
  }
  .thumbnail-gall {
    width: 75px !important;
    height: 75px;
    display: inline-block;
    background-size: cover;
    background-repeat: no-repeat;
    margin: 5px;
  }
  a.carousel-control-next,
  .carousel-control-prev{
    height: 70%;
  }
  .apartamento h1{
    transform-origin: center center 0px;
    transform: scale(1) translate3d(0px, 0px, 0px);
    font-family: 'miramar';
    color: #fff;
    text-shadow: rgb(8, 8, 8) 1px 1px;
    line-height: 80px;
    font-weight: bold;
    font-size: 5em;
    line-height: 1;
    padding: 1em;
  }
  .apartamento.fotos {
    background-image: url('{{ $photoHeader }}') !important;
  }

  .page-title {
    padding: 5em 0 3em;
    background-size: cover; 
    background-position: center center; 
    background-repeat: no-repeat; 
    margin-top: -12em;
    padding-top: 14em;
    margin-bottom: 2em;
  }
  section.page-section.darkgrey {
    margin: 2em 0;
    padding: 2.2em;
  }
  h2.text-center.white.font-w300 {
    width: 100%;
    font-size: 3.2em;
    margin-bottom: 1em;
  }

  h2.subtit {
    width: 100%;
    text-align: center;
    /* transform-origin: center center 0px; */
    /* transform: scale(1) translate3d(0px, 0px, 0px); */
    font-family: 'miramar';
    color: #fff;
    text-shadow: rgb(8, 8, 8) 1px 1px;
    line-height: 80px;
    font-weight: bold;
    font-size: 3em;
    line-height: 1;
    padding: 0;
    margin: 0;
  }
  @media (max-width: 768px){
    .apartamento h1{
      padding: 0;
    }
    .page-title.apartamento.fotos{
      margin-top: 0;
    }
  }
</style>
<section >
  <div class="container">
    <div class="row">
      <div class="col-lg-6 col-md-7 img-colmun">
        <div class="clearfix">

          @if($photos)
          <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
              <?php 
              $start = 'active';
              $count = 0;
              foreach($photos as $img):
                ?>
                 <li data-target="#carouselExampleIndicators" data-slide-to="{{$count}}" class="{{$start}}"></li>
                <?php
                $start = '';
              endforeach;
              ?>
            </ol>
            <div class="carousel-inner">
              <?php 
              $start = 'active';
              foreach($photos as $img):
                ?>
                <div class="carousel-item  {{$start}}">
                 <img src="{{url($img->file_rute.'/'.$img->file_name)}}" alt="{{$aptoHeading}}"/>
                </div>
                <?php
                $start = '';
              endforeach;
              ?>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="sr-only">Next</span>
            </a>
          </div>
          @endif
        </div>
      </div>

      <div class="col-lg-6 col-md-5 content-colmun" >
        {!! $room->content_front !!}

      </div>
    </div>

</section>
