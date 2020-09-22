@extends('layouts.admin-master')

@section('title') Promociones @endsection

@section('externalScripts') 
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
      integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
<link href="/assets/css/font-icons.css" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
<style>
  input.datepicker {
    height: 3em;
    border: 1px solid;
    float: left;
    margin-right: 1%;
    margin-bottom: 1em;
    width: 7em;
    padding: 1em 2px;
    text-align: center;
  }
  .box-row{
    padding: 1em 2em !important;
  }
  .box{ background-color: #FFF;}
  .box ul {padding: 5px;}
  .box ul li {list-style: none;}
  select[multiple], select[size] {
    height: 18em !important;
}
</style>

<div class="container-fluid padding-25 sm-padding-10">
  <div class="row">
    <div class="col-md-12">
      <div class="row">
        <div class="col-md-3 col-xs-12">
          <h3>Promociones de Temporadas:</h3>
        </div>
        <div class="col-xs-12 col-md-7">
          <a class="text-white btn btn-md btn-primary" href="{{route('precios.base')}}">PRECIO BASE X TEMP</a>
          <a class="text-white btn btn-md btn-primary" href="{{route('channel.price.cal')}}">UNITARIA</a>
          <a class="text-white btn btn-md btn-primary" href="{{route('channel.price.site')}}">EDIFICIO</a>
          <button class="btn btn-md btn-primary active"  disabled>PROMOCIONES</button>
          <a class="text-white btn btn-md btn-primary" href="{{route('precios.pricesOTAs')}}">PRECIOS OTAs</a>
        </div>
        <div class="col-md-2 row">
          @include('backend.years._selector', ['minimal' => true])
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6 col-lg-8 col-xs-12 box-row">
      @if($lstPromotions)
      @foreach($lstPromotions as $item)
      <div class="box row">
        <div class="col-md-4">
          <h4>{{$item['value']}}%</h4>
          <strong>{{$item['start'].' - '.$item['finish']}}</strong><br>
          <button class="btn btn-default editPromotion" type="button" >Editar</button>
        </div>
        <div class="col-md-4">
          <strong>Apartamentos</strong>
          @if($item['rooms'])
          <ul>
            @foreach($item['rooms'] as $room)
            <li>{{$room}}</li>
            @endforeach
          </ul>
          @endif
        </div>
        <div class="col-md-4">
          <strong>Excepciones</strong>
          @if($item['except'])
          <ul>
            @foreach($item['except'] as $day)
              <li>{{$day}}</li>
            @endforeach
          </ul>
          @endif
        </div>
      </div>
      @endforeach
      @endif
    </div>
    <div class="pt-1 col-md-6 col-lg-4 col-xs-12">
      <form method="POST" action="{{route('channel.promotions.new')}}" id="channelForm">
        <input type="hidden" id="_token" name="_token" value="<?php echo csrf_token(); ?>">
        <div class="row">
          <div class="pt-1 col-md-8  col-xs-12">
            <label>Rango de Fechas</label>
            <input type="text" class="form-control daterange01" id="range" name="range" value="">
          </div>
          <div class="pt-1 col-md-4 col-xs-12">
            <label>Descuento</label>
            <input type="number" class="form-control" name="discount" id="discount" value="15">
          </div>
          <div class="pt-1 col-xs-12">
            <div class="row">
              @if($ch_group)
              <div class="form-group">
                <label for="">Apartamentos</label>
                <select multiple class="form-control" name="ch_group[]" id="ch_group[]">
                  @foreach($ch_group as $k=>$v)
                  <option value="{{$k}}">{{$v}}</option>
                  @endforeach
                </select>
              </div>
              @endif
            </div>
          </div>
          <div class="pt-1 col-xs-12">
            <label>Excluir Fechas</label>

            <div id="datebox">
              <input class="datepicker" name="date_0" type="text" />
            </div>
            <button class="btn btn-primary" id="add" type="button">Agregar</button>
          </div>

        </div>
        <div class="pt-1 ">
          <button class="btn btn-primary m-t-20">Guardar</button>
        </div>
      </form>
    </div>
  </div>
  <div class="row"></div>
</div>


@endsection

@section('scripts')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />


<script type="text/javascript" src="/js/datePicker01.js"></script>
<script type="text/javascript">
$(document).ready(function () {
  /********************************************************/
  $('.datepicker').datepicker();
  $('#add').click(function () {
    var name = 'date_' + ($('.datepicker').length + 1);
    // APPEND NEW DATE AND BIND IT AT THE SAME TIME
    $('#datebox').append(
            $('<input/>', {name: name, type: 'text', class: 'datepicker'}).datepicker()
            );
  });
  /********************************************************/
});
</script>
@endsection