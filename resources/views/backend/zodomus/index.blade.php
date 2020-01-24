@extends('layouts.admin-master')

@section('title') Channel Manager @endsection

@section('externalScripts') 
<link href="/assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" type="text/css" media="screen">
<link rel="stylesheet" href="{{ asset('/frontend/css/components/daterangepicker.css')}}" type="text/css" />
@endsection

@section('content')
<div class="container-fluid padding-25 sm-padding-10">
  <div class="row">
    <div class="col-md-10 col-md-offset-1 ">
      <h3>Listado de Apartamentos:</h3>
      <div class="row">
        <div class="form-material pt-1 col-xs-10 col-md-6">
          
          @foreach($aptos as $k=>$item)
          <ul>
            <li>
              <h2>{{$item->name}}</h2>
              <ol>
              @foreach($item->rooms as $room)
              <li>
                {{$room->name}}
                @if(isset($channels[$room->channel]))
                <b>({{$channels[$room->channel]}})</b>
                @endif
              </li>
              @endforeach
              </ol>
            </li>
          </ul>
          @endforeach
          
          
          
         
        </div>   
        <div class="col-xs-2 col-md-2" style="padding-top:2.7em;">
          <a href="" class="btn btn-danger" 
            onclick="return confirm('Confirmar que desea enviar al Channel Manager la disponibilidad actual de los Aptos asociados a la OTA seleccionada?');">
            Actualizar Disponbilidad
          </a>
        </div>
      </div>
      <div class="">
      </div>  
    </div>

  </div>
</div>


@endsection

@section('scripts')


<script type="text/javascript">
$(document).ready(function () {

  $('#datepicker_start,#datepicker_end').datepicker();

  
  $('#room_list').on('change', function (e) {
     var rId = $(this).val();
     location.href = '{{route('channel.price.cal')}}/'+rId;
  });
  
  
  $('#upd_avail').on('click', function (e) {
     e.preventDefault();
     location.href = '{{route('channel.price.cal')}}/'+rId;
  });
  
  function upd_avail(){
    return 
  }
  
  
  
//alert(now.toLocaleDateString("es-ES"));
});
</script>
<style>
  .fc-event, .fc-event-dot{
    color: #000;
    padding: 5px 1em;
    margin: 5px;
    font-weight: 600;
  }
  .fc-event:hover, .fc-event-dot:hover{
    color: inherit;
  }
  .tag-room{
    float: left;
    min-width: 10em;
  }
  .tag-room span {
      display: block;
      width: 17px;
      float: left;
      border-radius: 50%;
      margin-right: 7px;
  }
  .fc-ltr .fc-dayGrid-view .fc-day-top .fc-day-number {
    float: right;
    font-weight: 800;
  }
</style>
@endsection