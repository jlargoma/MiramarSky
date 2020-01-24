@extends('layouts.admin-master')

@section('title') Precios de apartamentos @endsection

@section('externalScripts') 
<link href='/vendors/fullcalendar/core/main.min.css' rel='stylesheet' />
<link href='/vendors/fullcalendar/dayGrid/main.min.css' rel='stylesheet' />

<script src='/vendors/fullcalendar/core/main.min.js'></script>
<script src='/vendors/fullcalendar/core/interaction.min.js'></script>
<script src='/vendors/fullcalendar/dayGrid/main.min.js'></script>
<script src='/vendors/fullcalendar/dayGrid/resourceDayGrid.min.js'></script>

<link href="/assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" type="text/css" media="screen">
<link rel="stylesheet" href="{{ asset('/frontend/css/components/daterangepicker.css')}}" type="text/css" />
@endsection

@section('content')
<div class="container-fluid padding-25 sm-padding-10">
  <div class="row">
    <div class="col-md-7">
      <h3>Listado de Precios:</h3>
      <div class="row">
        <div class="form-material pt-1 col-xs-12 col-md-6">
          <label>Apartamento</label>
          <select class="form-control" id="room_list">
            @foreach($rooms as $k=>$name)
            <option value="{{$k}}" @if($room == $k) selected @endif>{{$name}}</option>
            @endforeach
          </select>
        </div>     
      </div>
      <div id='calendar'></div>   
    </div>
    <div class="col-md-5">

      <h3>Editar Precios:</h3>
      <div class="row">
        <form method="POST" action="{{route('channel.price.cal.upd',$room)}}" id="channelForm">
          <input type="hidden" id="_token" name="_token" value="<?php echo csrf_token(); ?>">
          <div class="form-material pt-1 col-xs-12 col-md-6">
            <label>Fecha Inicio</label>
            <div id="datepicker_start" class="input-group date col-xs-12">
              <input type="text" class="form-control" name="date_start" id="date_start" value="" style="font-size: 12px">
              <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
            </div>
          </div>
          <div class="form-material pt-1 col-xs-12 col-md-6">
            <label>Fecha Fin</label>
            <div id="datepicker_end" class="input-group date">
              <input type="text" class="form-control" name="date_end" id="date_end" value="" style="font-size: 12px">
              <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
            </div>
          </div>

          <div class="pt-1 col-md-12">
            @foreach($dw as $k=>$v)
            <div class="input-group">
              <input type="checkbox" name="dw_{{$k}}" id="dw_{{$k}}" data-init-plugin="switchery" data-size="small" data-color="primary" checked="checked" />
              <label class="inline">&nbsp; {{$v}}</label>
            </div> 
            @endforeach
          </div>
          <div class="pt-1 col-md-6">
            <label>Precio por día (€)</label>
            <input type="number" class="form-control" name="price" id="price">
          </div>
          <div class="pt-1 col-md-6">
            <label>Estancia Mín.</label>
            <input type="number" class="form-control" name="min_estancia" id="min_estancia">
          </div>
          <div class="form-material pt-1 col-md-12">
            <button class="btn btn-primary m-t-20">Guardar</button>
          </div>
        </form>
      </div>
      <div class="row pt-1">
        <p class="alert alert-danger" style="display:none;" id="error"></p>
        <p class="alert alert-success" style="display:none;" id="success"></p>
      </div>

    </div>
  </div>
</div>



@endsection

@section('scripts')


<script type="text/javascript">
$(document).ready(function () {

  $('#datepicker_start,#datepicker_end').datepicker();

  var calendarEl = document.getElementById('calendar');
  var calendar = new FullCalendar.Calendar(calendarEl, {
    plugins: ['interaction', 'dayGrid', 'resourceDayGridMonth', 'resourceDayGridWeek'],
    selectable: true,
    events: function(info,callback,failureCallback) {
      var start = new Date(info.start);
      var end = new Date(info.end);
    
    $.get('{{route("channel.price.cal.list",$room)}}',
      { "start": start.toLocaleDateString("es-ES"), "end": end.toLocaleDateString("es-ES") },
      function (data) {
        console.log(data);
        callback((data));
    });
  },
//    events: '{{route("channel.price.cal.list",$room)}}'

  });

  calendar.setOption('locale', 'es');

  calendar.on('select', function (info) {
    var start = new Date(info.start);
    var end = new Date(info.end);
    end.setDate(end.getDate() - 1);
    $('#date_start').datepicker('setDate', start);
    $('#date_end').datepicker('setDate', end);
//    $('#date_start').val(start.toLocaleDateString("es-ES"));
  });



  calendar.render();
  
  

  var pintar = function (start, end) {
    let event = calendar.getEventById('event_edit');
    if (event) {
      if (start)
        event.setStart(start);
      event.setEnd(end);
    } else {
      calendar.addEventSource(
              {
                events: [
                  {
                    id: 'event_edit',
                    title: 'EDITANDO',
                    start: start,
                    end: end
                  },
                ],
                color: 'yellow', // an option!
                textColor: 'black' // an option!
              }
      );
    }

  }
  $('#datepicker_start').on('changeDate', function (e) {
    var start = new Date(e.date);
    var end = new Date(e.date);
    end.setDate(start.getDate() + 1);
    pintar(start, end);
  });
  $('#datepicker_end').on('changeDate', function (e) {
    var end = new Date(e.date);
    end.setDate(end.getDate() + 1);
    pintar(null, end);
  });


  $('#room_list').on('change', function (e) {
    var rId = $(this).val();
    location.href = '{{route('channel.price.cal')}}/' + rId;
  });


$('#channelForm').on('submit', function (event) {

    event.preventDefault();
    $('#error').text('').hide();
    $('#success').text('').hide();
    var form_data = $(this).serialize();
    var url = $(this).attr('action');
    $.ajax({
           type: "POST",
           url: url,
           data: form_data, // serializes the form's elements.
           success: function(data)
           {
             if (data.status == 'OK'){
               $('#success').text(data.msg).show();
               let event = calendar.getEventById('event_edit');
               if (event) event.remove()
               calendar.refetchEvents();
             } else {
               $('#error').text(data.msg).show();
             }
               console.log(data.msg); // show response from the php script.
           }
         });
  });



//alert(now.toLocaleDateString("es-ES"));
});
</script>
<style>
  .fc-event, .fc-event-dot{
    background-color: transparent;
    color: inherit;
    padding: 5px 1em;
    font-weight: 600;
  }
  .fc-event:hover, .fc-event-dot:hover{
    color: red;
  }
</style>
@endsection