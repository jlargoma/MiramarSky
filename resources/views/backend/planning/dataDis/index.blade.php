@extends('layouts.admin-master')

@section('title') DataDis @endsection

@section('externalScripts')
<style>
  .calendarBox td.pwOn {
    border-bottom: 4px solid red;
  }
  .calendarBox td.pwOff {
    border-bottom: 4px solid grey;
  }
  .calLink {
    background-color: #0173ff;
    color: #dadada;
    font-weight: bold;
    text-align: center;
  }

  .btn-fechas-calendar {
    color: #fff;
    background-color: #899098;
  }
  #btn-active {
    background-color: #10cfbd;
  }


  .calendarBox .tip:hover .end,
  .calendarBox .tip:hover .start,
  .calendarBox .tip:hover .total{
    background-color: red !important;
  }


  a.tip:hover span {
    bottom: 5px;
    top: auto !important;
    cursor: default;
  }

  .calendarBox .td-calendar{
    border:1px solid grey;
    width: 24px;
    height: 20px;
  }
  .calendarBox .no-event{
    border:1px solid grey;
    width: 24px;
    height: 20px;
  }
  .calendarBox .ev-doble{
    border:1px solid grey;
    width: 24px;
    height: 20px;
  }
  .calendarBox .start{
    width: 45%;
    float: right;
    cursor: pointer;
  }
  .calendarBox .end{
    width: 45%;
    float: left;
    cursor: pointer;
  }
  .calendarBox .total{
    width: 100%;
    height: 100%;
    cursor: pointer;
  }
  .calendarBox .td-month{
    border:1px solid black;
    width: 24px;
    height: 20px;
    font-size: 10px;
    padding: 5px!important;
    text-align: center;
    min-width: 25px;
  }

  .calendarBox .td-month {
    border: 1px solid black;
    width: 24px;
    height: 20px;
    font-size: 10px;
    padding: 5px !important;
    text-align: center;
    min-width: 25px;
  }
  .calendarBox .fixed-td {
    left: 10px;
  }
  div#calendarBox {
    padding: 2em 1em 0 2em;
  }
  a.tip:hover span {
    position: absolute !important;
  }
  @media screen and (max-width:450px){

  }
</style>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.js"></script>
@endsection

@section('content')
<h1 class="text-center">DataDis</h1>
  <div id="calendarBox" class="calendarBox"></div>
@endsection

@section('scripts')
<script type="text/javascript">
window["csrf_token"] = "{{ csrf_token() }}";
window["URLCalendar"] = '/admin/dataDis/calendar/';
</script>
<script type="text/javascript">
  $(document).ready(function () {
      
      $('.calendarBox .tip').on('click', '.calLink', function (event) {
          location.href = $(this).data('href');
      });
      $('.calendarBox .tip').hover(function (event) {
          var span = $(this).find('span');
          if (screen.width < 768) {
              span.css('top', 'auto');
              span.css('bottom', '-9px');
              span.css('left', 'auto');
              span.css('right', '3px');
          }
      });
  });
</script>

@include('backend.planning.dataDis.calendar.scripts');
@endsection