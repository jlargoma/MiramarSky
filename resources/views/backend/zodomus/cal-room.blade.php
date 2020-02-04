@extends('layouts.admin-master')

@section('title') Precios de apartamentos @endsection

@section('externalScripts') 
<link href='/vendors/fullcalendar-4.3.1/packages/core/main.min.css' rel='stylesheet' />
<link href='/vendors/fullcalendar-4.3.1/packages/daygrid/main.min.css' rel='stylesheet' />
<script src='/vendors/fullcalendar-4.3.1/packages/core/locales/es.js'></script>
<script src='/vendors/fullcalendar-4.3.1/packages/core/main.min.js'></script>
<script src='/vendors/fullcalendar-4.3.1/packages/interaction/main.min.js'></script>
<script src='/vendors/fullcalendar-4.3.1/packages/daygrid/main.min.js'></script>
<!--<script src='/vendors/fullcalendar-4.3.1/packages/daygrid/resourceDayGrid.min.js'></script>-->

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<!--<script type="text/javascript" src="{{asset('/frontend/js/components/moment.js')}}"></script>

<script type="text/javascript" src="{{asset('/frontend/js/components/daterangepicker.js')}}"></script>

<link rel="stylesheet" href="{{ asset('/frontend/css/components/daterangepicker.css')}}" type="text/css" />-->
<style type="text/css" media="screen"> 
  .daterangepicker{
    z-index: 10000!important;
  }
  .pg-close{
    font-size: 45px!important;
    color: white!important;
  }
  @media only screen and (max-width: 767px){
    .daterangepicker {
      left: 12%!important;
      top: 3%!important; 
    }
  }

</style>

<!--<link href="/assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" type="text/css" media="screen">
<link rel="stylesheet" href="{{ asset('/frontend/css/components/daterangepicker.css')}}" type="text/css" />-->
@endsection

@section('content')
<div class="container-fluid padding-25 sm-padding-10">
  <div class="row">
    <div class="col-md-7">
      <h3>Listado de Precios:</h3>
      <div class="row">
        <div class="form-material pt-1 col-xs-12 col-md-6">
          <label class="hidden-mobile">Apartamento</label>
          <select class="form-control" id="room_list">
            @foreach($rooms as $k=>$name)
            <option value="{{$k}}" @if($room == $k) selected @endif>{{$name}}</option>
            @endforeach
          </select>
        </div>     
        <div class="form-material pt-1 col-xs-12 col-md-6">
          
          <table class="table-prices">
            <tr>
              <td><span class="price-booking">+22%</span></td>
              <td><span class="price-airbnb">+20%</span></td>
              <td><span class="price-expedia">+15%</span></td>
              <td><span class="disp-layout">Disponib</span></td>
            </tr>
          </table>
        </div>
      </div>
      <div class="calendar-blok">
      <div id='calendar'></div>   
      </div>
    </div>
    <div class="col-md-5">
      <p class="mobile-tit">Editar Precios:</p>
      <div class="row">
        <form method="POST" action="{{route('channel.price.cal.upd',$room)}}" id="channelForm">
          <input type="hidden" id="_token" name="_token" value="<?php echo csrf_token(); ?>">
          <div class="pt-1 col-md-12">
            <div class="row">
              <div class="col-md-3 col-xs-5 pt-1"><label>Rango de Fechas</label></div>
              <div class="col-md-9 col-xs-7">
                <input type="text" class="form-control daterange1" id="date_range" name="date_range" value="">
              </div>
            </div>

          </div>
          <div class="pt-1 col-md-12 row">
            <button id="selAllDays" type="button"  class="btn_days">Todos</button>
            <button id="selWorkdays" type="button" class="btn_days">Laborales</button>
            <button id="selHolidays" type="button" class="btn_days">Fin de semana</button>
          </div>
          <div class="pt-1 col-md-12 row">
            @foreach($dw as $k=>$v)
            <div class="weekdays">
              <label>
                <input type="checkbox" name="dw_{{$k}}" id="dw_{{$k}}" checked="checked"/>
                <span> {{$v}}</span>
              </label>
            </div> 
            @endforeach
          </div>
          <div class="pt-1 col-xs-6">
            <label>Precio por día (€)</label>
            <input type="number" class="form-control" name="price" id="price" step="any">
          </div>
          <div class="pt-1 col-xs-6">
            <label>Estancia Mín.</label>
            <input type="number" class="form-control" name="min_estancia" id="min_estancia">
          </div>
          <div class=" pt-1 col-md-12" style="clear:both;">
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





  $(".daterange1").daterangepicker({
    "buttonClasses": "button button-rounded button-mini nomargin",
    "applyClass": "button-color",
    "cancelClass": "button-light",
    autoUpdateInput: true,
//    locale: 'es',
    locale: {
      firstDay: 2,
      format: 'DD/MM/YYYY',
      "applyLabel": "Aplicar",
      "cancelLabel": "Cancelar",
      "fromLabel": "From",
      "toLabel": "To",
      "customRangeLabel": "Custom",
      "daysOfWeek": [
        "Do",
        "Lu",
        "Mar",
        "Mi",
        "Ju",
        "Vi",
        "Sa"
      ],
      "monthNames": [
        "Enero",
        "Febrero",
        "Marzo",
        "Abril",
        "Mayo",
        "Junio",
        "Julio",
        "Agosto",
        "Septiembre",
        "Octubre",
        "Noviembre",
        "Diciembre"
      ],
    },

  });

  Date.prototype.ddmmmyyyy = function () {
    var mm = this.getMonth() + 1; // getMonth() is zero-based
    var dd = this.getDate();
    return [
      (dd > 9 ? '' : '0') + dd,
      (mm > 9 ? '' : '0') + mm,
      this.getFullYear()
    ].join('/');
  };
  Date.prototype.yyyymmmdd = function () {
    var mm = this.getMonth() + 1; // getMonth() is zero-based
    var dd = this.getDate();
    return [
      this.getFullYear(),
      (mm > 9 ? '' : '0') + mm,
      (dd > 9 ? '' : '0') + dd
    ].join('-');
  };
  var render_yyyymmmdd = function (dates, moreDay = 0) {
    var date = dates.trim().split('/');
    return date[2] + '-' + date[1] + '-' + date[0];
  };


  $('.daterange1').change(function (event) {
    var date = $(this).val();

    var arrayDates = date.split('-');
    var date1 = render_yyyymmmdd(arrayDates[0]);
    var date2 = render_yyyymmmdd(arrayDates[1], 1);
    date2 = new Date(date2);
    date2.setDate(date2.getDate() + 1);
    pintar(date1, date2);
  });



  $('#datepicker_start,#datepicker_end').datepicker();

  var calendarEl = document.getElementById('calendar');
  var calendar = new FullCalendar.Calendar(calendarEl, {
    plugins: ['interaction', 'dayGrid', 'resourceDayGridMonth', 'resourceDayGridWeek'],
    selectable: true,
    header: {
      left: '',
      center: 'title',
      right: 'today prev,next'
    },
    textEscape: false,
    eventRender: function (info) {
      info.el.querySelector('.fc-title').innerHTML = info.event.title;
    },
    events: function (info, callback, failureCallback) {
      var start = new Date(info.start);
      var end = new Date(info.end);

      $.get('{{route("channel.price.cal.list",$room)}}',
              {"start": start.toLocaleDateString("es-ES"), "end": end.toLocaleDateString("es-ES")},
              function (data) {
                callback((data));
              });
    },
//    events: '{{route("channel.price.cal.list",$room)}}'

  });

  calendar.setOption('locale', 'es');

  calendar.on('select', function (info) {
    console.log(info);
    var start = new Date(info.start);
    var end = new Date(info.end);
    end.setDate(end.getDate() - 1);
    $('#date_range').data('daterangepicker').setStartDate(start.ddmmmyyyy());
    $('#date_range').data('daterangepicker').setEndDate(end.ddmmmyyyy());

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

  $('#selAllDays').on('click', function (e) {
    $('.btn_days').removeClass('active');
    $(this).addClass('active');
     for(i=0; i<7; i++){
      $('#dw_'+i).prop("checked", true);
    }
  });
  $('#selWorkdays').on('click', function (e) {
     $('.btn_days').removeClass('active');
    $(this).addClass('active');
    $('#dw_0').prop("checked", false);
    $('#dw_6').prop("checked", false);
    for(i=1; i<6; i++){
      $('#dw_'+i).prop("checked", true);
    }
  });
   $('#selHolidays').on('click', function (e) {
      $('.btn_days').removeClass('active');
    $(this).addClass('active');
    $('#dw_0').prop("checked", true);
    $('#dw_6').prop("checked", true);
    for(i=1; i<6; i++){
      $('#dw_'+i).prop("checked", false);
    }
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
      success: function (data)
      {
        if (data.status == 'OK') {
          $('#success').text(data.msg).show();
          let event = calendar.getEventById('event_edit');
          if (event)
            event.remove()
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
    border: none;
    text-align: center;
    font-size: 1.2em;
    color: inherit;
  }
  .fc-event:hover, .fc-event-dot:hover{
    color: red;
  }
  .fc-day-grid-event .fc-time {
    display: none;
  }
  .weekdays:first-child {
    margin-left: 10px;
  }
  .weekdays {
    float: left;

    width: 14%;
  }
  .col-xs-5.pt-1 {
    padding-top: 7px;
  }
  .pt-1.col-md-12.row{
    margin: auto;
  }
  .weekdays label {
    display: block;
    padding-left: 10px;
    text-indent: -17px;
    color:#a7a7a7;
  }

  .weekdays input {
    width: 1px;
    height: 1px;
    padding: 0;
    margin: 0;
    vertical-align: bottom;
    position: relative;
    top: -1px;
  }


  input:checked + span {
    color: #2b5d9b;
  }
  
  .btn_days {
    padding: 4px 15px;
  }
  .btn_days.active {
    background-color: #2b5d9b;
    color: #fff;
  }

  p.mobile-tit {
    padding: 7px 11px;
    margin: 8px auto 2px;
    background-color: #4886d2;
    color: #fff;
    font-size: 1.4em;
    box-shadow: 1px 3px 2px #00132b;
    font-weight: 600;
  }
  .fc-center h2 {
    font-weight: 800;
  }
  a.fc-day-grid-event.fc-h-event.fc-event.fc-start.fc-end.availibility {
    position: absolute;
    top: 0;
    font-size: 11px;
    color: #fff;
    padding: 2px 4px;
    margin: 6px;

  }
  a.fc-day-grid-event.fc-h-event.fc-event.fc-start.fc-end.availibility.yes {
    background-color: rgba(32, 113, 0, 0.4);
  }
  a.fc-day-grid-event.fc-h-event.fc-event.fc-start.fc-end.availibility.no {
    background-color: rgba(255, 0, 0, 0.40);
  }
  
  .calendar-blok{
    width: 100%;
    overflow: scroll;
  }
  div#calendar {
    min-width: 706px;
  }
 
  .disp-layout{
        background-color: #9ec4a0;
    padding: 5px !important;
    color: #fff;
    border-radius: 4px;
  }
@media only screen and (max-width: 768px) {
     .daterangepicker {
    top: auto !important;
  }
  }
</style>
@endsection