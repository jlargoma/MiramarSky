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
  @if (\Session::has('sent'))<p class="alert alert-success">{!! \Session::get('sent') !!}</p>@endif
  @if($errors->any())<p class="alert alert-danger">{{$errors->first()}}</p>@endif
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
        @if (Auth::user()->email == "jlargo@mksport.es")
          <div class="col-md-12">
            <form action="{{route('precios.prepare-cron')}}" method="post" class="inline">
              <input type="hidden" id="_token" name="_token" value="<?php echo csrf_token(); ?>">
              <button class="btn btn-success" title="{{$sendDataInfo}}">Sincr. precios OTAs</button>
            </form>
            <small>(Sincronizar toda la temporada)</small>
          </div>
        @endif
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6 col-lg-8 col-xs-12 box-row">
      @if($lstPromotions)
      @foreach($lstPromotions as $item)
      <div class="box row box_promotion">
        <div class="col-md-4">
          <h4>{{$item['value']}}%</h4>
          <strong>{{$item['start'].' - '.$item['finish']}}</strong><br>
          <button class="btn btn-default editPromotion" type="button" data-id="{{$item['id']}}">Editar</button>
          <button class="btn btn-danger deletePromotion" type="button" data-id="{{$item['id']}}">Eliminar</button>
       
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
        <input type="hidden" id="itemID" name="itemID" value="">
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
              <div class="form-group row">
                <label class="col-md-12">Apartamentos</label>
                @foreach($ch_group as $k=>$v)
                <div class="form-check col-md-6">
                  <input type="checkbox" class="form-check-input" id="apto{{$k}}" name="apto{{$k}}">
                  <label class="form-check-label" >{{$v}}</label>
                </div>
                @endforeach
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
          <button class="btn btn-primary">Guardar</button>
          <button class="btn btn-primary" id="new" type="button">Nueva</button>
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
  $('#new').on('click',function () {
        $("#discount").val(15);
        $("#itemID").val(null);
        $('#datebox').html('');
        $('.form-check-input').prop('checked', true);
  });
  $('.editPromotion').on('click',function () {
    var id = $(this).data('id');
    var url = "{{route('channel.promotions.get')}}/"+id;
    $.get(url, function(resp) {
      if (resp == 'not_found'){
      } else {
        $("#ch_group").val('');
        $("#discount").val(resp.value);
        $("#itemID").val(id);
        $("#datepicker").val(id);
        $('#range').data('daterangepicker').setStartDate(resp.start);
        $('#range').data('daterangepicker').setEndDate(resp.finish);
        
        $('#datebox').html('');
        for(var i in resp.except){
          var name = 'date_' + i;
          $('#datebox').append(
            $('<input/>', {name: name, type: 'text', class: 'datepicker',value: resp.except[i]}).datepicker()
          );
        }
        
        for(var i in resp.rooms){
          $("#apto"+resp.rooms[i]).prop('checked', true);
          
//          $("#ch_group option[value=" + resp.rooms[i] + "]").prop('selected', true);
        }
      }
      });
    
  });
  
  
  $('.deletePromotion').click(function (event) {
          
          if (confirm('Eliminar la promoción?')){
            var data = {
              id: $(this).data('id'),
              _token: "{{csrf_token()}}"
            };
            
            var elemet = $(this).closest('.box_promotion');
            
            $.ajax({
                url: "{{route('channel.promotions.delete')}}",
                data: data,
                type: 'DELETE',
                success: function(result) {
                  if (result == 'OK'){
                    window.show_notif('OK','success','Registro Eliminado.');
                    elemet.remove(); 
                  } else{
                    window.show_notif('ERROR','danger','Registro no encontrado');
                  }
                },
                error: function(e){
                  console.log(e);
                  window.show_notif('ERROR','danger','Error de sistema');
                }
            });
          }
        });
  
});
</script>
@endsection