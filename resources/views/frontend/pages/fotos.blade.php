<style>
  h1{
    margin-bottom: 0 !important;
  }
  
  .stars {
    width: auto;
    margin: 7px auto;
    padding-bottom: 2em;
    text-align: center;
  }
  .stars i{
    color: #f28d7b;
    font-size: 2em
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
                 <img src="{{url($img->file_rute.'/'.$img->file_name)}}" alt="{{$aptoHeading}}" data-skip-lazy/>
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
  </div>

    
  <?php if ($url == '9F'): ?>
    <input type="hidden" id="nameAptoFoto" value="ATICO DUPLEX DE LUJO <?php echo strtoupper($room->nameRoom); ?>">
  <?php else: ?>
    <input type="hidden" id="nameAptoFoto" value="<?php echo strtoupper($aptoHeading.' '.$room->nameRoom); ?>">
  <?php endif ?>
</section>
