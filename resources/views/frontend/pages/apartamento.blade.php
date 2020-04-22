@extends('layouts.master')
@section('styles')
<script src="{{asset('/frontend/js/modernizr.custom.js')}}"></script>

<style type="text/css">
  label{
    color: white!important
  }
  #content-form-book {
    padding: 40px 15px;
  }
  .content-colmun{
    margin-top: 1em;
  }
  .btn_reservar{
    margin-bottom: 4em;
  }
  .apartamento {
      background-image: url('{{ $photoHeader }}') !important;
  }
  .btn_reservar .menu-booking-apt img{
    width: 15em;
    min-width: auto;
    margin-right: 7em;
  }
  @media (max-width: 768px){
    .container-mobile{
      padding: 0!important
    }
    .container-image-box img{
      height: 180px!important;
    }

    #content-form-book {
      padding: 0px 0 40px 0
    }
    .daterangepicker {
      position: fixed!important;
      top: 15%!important;
    }
    .img{
      max-height: 530px;
    }
    .button.button-desc.button-3d{
      background-color: #4cb53f!important;
    }

    .img.img-slider-apto{
      height: 250px!important;
    }
  }
  .flex-control-nav.flex-control-paging{
    display: none;
  }
  
  .lSAction .lSNext:before, .lSAction .lSPrev:before {
    width: 37px;
    display: block;
    color: #232323;
    border-radius: 11px;
    font-size: 40px;
    background-color: #eaeaea;
    height: 60px;
}
</style>
@endsection
@section('metadescription') {{ $meta_descript }} @endsection
@section('title') {{ $meta_tit }} @endsection

@section('content')

  <section class="page-title apartamento centered">
    <div class="container">
      <div class="content-box">
        <?php if ($url == '9F'): ?>
          <h1 class="center hidden-sm hidden-xs psuh-20">ATICO DUPLEX DE LUJO</h2>
            <h1 class="center hidden-lg hidden-md green push-10">ATICO DUPLEX DE LUJO</h2>
        <?php else: ?>
          <h1 class="center hidden-sm hidden-xs psuh-20"><?php echo strtoupper($room->title); ?></h1>
          <h1 class="center hidden-lg hidden-md green push-10"><?php echo strtoupper($room->title); ?></h1>
        <?php endif ?>
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
                <li data-thumb="{{$img->file_rute}}/{{$img->file_name}}"> 
                  <img src="{{$img->file_rute}}/{{$img->file_name}}" alt="{{$aptoHeading}}"/>
                </li>
                @endforeach
              </ul>
            @endif
          </div>
        </div>

        <div class="col-lg-6 col-md-5 content-colmun" >
          {!! $room->description !!}
        </div>
      </div>
  </section>

  <section id="content">
    <?php if (!$mobile->isMobile()): ?>
      <div id="content-form-book" class="row bg-bluesky push-30" style="display: none; background-image: url({{asset('/img/miramarski/esquiadores.png')}}); background-position: left bottom; background-repeat: no-repeat; background-size: 45%;">

        <span style="padding: 0 5px; cursor: pointer; opacity: 1" class="close pull-right white text-white sm-m-r-20 sm-m-t-10">
          <i class="fa fa-times"></i>
        </span>

        <div class="container clearfix" style="height: 645px;">
          <div class="col-md-6 col-md-offset-3">
            <div class="row" id="content-book-response">
              <div class="front" style="max-height: 520px!important;">
                <div class="col-xs-12">
                  <h3 class="text-center white">CALCULA TU PRECIO</h3>
                </div>
                <div id="form-content">
                  @include('frontend._formBook')
                </div>
              </div>
              <div class="back" style="background-color: #3F51B5; max-height: 520px!important;">

              </div>
            </div>
          </div>
        </div>
      </div>
    <?php else: ?>
      <div id="content-form-book" class="col-xs-12 bg-bluesky push-30" style="display: none;">

        <span style="padding: 0 5px; cursor: pointer; opacity: 1" class="close pull-right white text-white sm-m-r-20 sm-m-t-10">
          <i class="fa fa-times"></i>
        </span>

        <div class="container-mobile clearfix" style="margin-top: 10px; height: 133vh;">
          <div class="col-md-6 col-md-offset-3">
            <div class="row" id="content-book-response">
              <div class="front" style="max-height: 520px!important;">
                <div class="col-xs-12">
                  <h3 class="text-center white">CALCULA TU PRECIO</h3>
                </div>
                <div id="form-content">
                  @include('frontend._formBook')
                </div>
              </div>
              <div class="back" style="background-color: #3F51B5; max-height: 520px!important;">

              </div>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>
    <div class="col-xs-12">
      @if ($mobile->isMobile()):
      @include('frontend.blocks.othersRoomsFilterMobile')
      @else
      @include('frontend.blocks.othersRoomsFilter')
      @endif
    </div>
    <div id="fixed-book" class="col-xs-12 text-center center bg-white" >
      <div class="content">
        <div class=" btn_reservar">
          <img class="image_shine effect_shine" src="{{url('/img/miramarski/offer_tag_300.png')}}" style="max-width:125px;"/>
        </div>
        <button  class="button button-desc button-3d button-rounded showFormBook " style="">¡Reserva YA!</button>
      </div>
    </div>
  </section>


  @include('frontend.blocks.services')
  @include('frontend.blocks.info-links')
@endsection

@section('scripts')
<script src="{{ getCloudfl(assetV('/frontend/vendor/lightslider-master/dist/js/lightslider.min.js'))}}"  defer=""></script>
@endsection