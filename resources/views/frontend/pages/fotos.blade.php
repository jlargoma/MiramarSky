@extends('layouts.master_onlybody')

@section('styles')
<script src="{{asset('/frontend/js/modernizr.custom.js')}}"></script>

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
@endsection

@section('title') {!! $room->meta_title !!} @endsection
@section('metadescription') {!! $room->meta_descript !!} @endsection

@section('content')
<section class="page-title apartamento fotos centered">
  <div class="container">
    <div class="content-box">
      <?php if ($url == '9F'): ?>
        <h1 class="center hidden-sm hidden-xs psuh-20">ATICO DUPLEX DE LUJO</h1>
        <h1 class="center hidden-lg hidden-md green push-10">ATICO DUPLEX DE LUJO</h1>
      <?php else: ?>
        <h1 class="center hidden-sm hidden-xs psuh-20"><?php echo strtoupper($aptoHeading); ?></h1>
        <h1 class="center hidden-lg hidden-md green push-10"><?php echo strtoupper($aptoHeadingMobile); ?></h1>
      <?php endif ?>
      <h2 class="subtit">{{$room->nameRoom}}</h2>
    </div>
  </div>
</section>
<section >
  <div class="container">
    <div class="row">
      <div class="col-lg-6 col-md-7 img-colmun">
        <div class="clearfix">

          @if($photos)
          <ul id="image-gallery" class="gallery list-unstyled cS-hidden">
            @foreach($photos as $img)
            <li data-thumb="{{$directoryThumbnail}}{{$img->file_name}}"> 
              <img src="{{$img->file_rute}}/{{$img->file_name}}" alt="{{$aptoHeading}}"/>
            </li>
            @endforeach
          </ul>
          @endif
        </div>
      </div>

      <div class="col-lg-6 col-md-5 content-colmun" >
        {!! $room->content_front !!}

      </div>
    </div>
    <div id="fixed-book" class="col-xs-12 text-center center bg-white" >
      <div class="content">
        <div class=" btn_reservar">
          <img class="image_shine effect_shine" src="{{url('/img/miramarski/offer_tag_300.png')}}" style="max-width:125px;"/>
        </div>
        <a href="https://www.apartamentosierranevada.net/reservar/" class="button button-desc button-3d button-rounded showFormBook " style="">¡Reserva YA!</a>
      </div>
    </div>
</section>
@section('scripts')
<script type="text/javascript" src="{{ asset('/js/flip.min.js')}}"></script>
<script type="text/javascript" src="{{ assetV('/frontend/js/progressbar.min.js')}}"></script>
<script src="{{ assetV('/frontend/vendor/lightslider-master/src/js/lightslider.js')}}"></script>
<script type="text/javascript" src="{{asset('/frontend/js/components/moment.min.js')}}"></script>
<script type="text/javascript" src="{{asset('/frontend/js/components/daterangepicker.min.js')}}"></script>
<script type="text/javascript" src="{{ assetV('/frontend/js/scripts.js')}}"></script>
<script type="text/javascript" src="{{ assetV('/frontend/js/form_booking.js')}}"></script>
@endsection