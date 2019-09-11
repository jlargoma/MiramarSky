<?php

use \Carbon\Carbon;

setlocale(LC_TIME, "ES");
setlocale(LC_TIME, "es_ES");
?>
@extends('layouts.admin-master')

@section('title') Payland  @endsection

@section('externalScripts')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.js"></script>
<script type="text/javascript" src="{{asset('/js/bootbox.min.js')}}"></script>
@endsection

@section('content')
<div class="container padding-5 sm-padding-10">
  <div class="row bg-white">
    <div class="col-md-12 col-xs-12">
      <div class="col-md-3 col-md-offset-3 col-xs-12">
        <h2 class="text-center">
          Payland
        </h2>
      </div>
      <div class="col-md-2 col-xs-12 sm-padding-10" style="padding: 10px">
        @include('backend.years._selector')
      </div>
    </div>
  </div>
</div>
<div class="container-fluid">
  <div class="row bg-white push-30">
    <div class="col-lg-8 col-md-12 col-xs-12 push-20">
      @include('backend.sales._button-contabiliad')
    </div>
  </div>
</div>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-8 col-xs-12">
      <div class="row push-10">
        <h2 class="text-left font-w800">
          Resumen liquidación
        </h2>
      </div>
      <div class="col-md-12 col-xs-12" style="padding-right: 0;">
        <div class="month_select-box">
          @foreach($months_obj as $m)
          <div class="month_select" id="ms_{{$m['id']}}" data-month="{{$m['month']}}" data-year="{{$m['year']}}">{{$m['name']}} {{$m['year']}}</div>
          @endforeach
        </div>
        <div class="table-responsive" id="limpieza_table">
          <table class="table">
            <thead >
            <th class ="text-center bg-complete text-white col-md-2">Fecha</th>
            <th class ="bg-complete text-white col-md-3">Nombre</th>
            <th class ="text-right bg-complete text-white col-md-2">Importe</th>
            <th class ="text-center bg-complete text-white col-md-2">Estado</th>
            <th class ="text-center bg-complete text-white col-md-3">Card</th>
            </thead>
            <tbody id="tableItems">
            </tbody>
          </table>
        </div>
        <div class="paginate">
          <span id="payland_ant" class="action"> << </span>
        <span id="payland_page"> 1 </span>
        <span id="payland_sgt" class="action"> >> </span>
        </div>
      </div>
    </div>

    <div class="col-md-4 col-xs-12">
      <div class="row bg-white push-30">
        <div class="col-md-4 bordered">
          <div class="card-title text-black hint-text">Hoy</div>
            <h3 class="text-black font-w400 text-center"><span id="payland_today"></span>€</h3>
        </div>
        <div class="col-md-4 bordered">
          <div class="card-title text-black hint-text">Mes</div>
          <h3 class="text-black font-w400 text-center"><span id="payland_month"></span>€</h3>
        </div>
        <div class="col-md-4 bordered">
          <div class="card-title text-black hint-text">Temporada</div>
            <h3 class="text-black font-w400 text-center"><span id="payland_temp"></span>€</h3>
        </div>
        <div class="col-md-6 bordered">
          <div class="card-title text-black hint-text">Total de pagos</div>
          <div class="p-l-20">
            <h3 class="text-black font-w400 text-center"><span id="payland_total"></span></h3>
          </div>
        </div>
        <div class="col-md-6 bordered">
          <div class="card-title text-black hint-text">Promedio por pagos</div>
            <h3 class="text-black font-w400 text-center"><span id="payland_average"></span>€</h3>
        </div>
      </div>
      <div>
        <canvas id="barChartTemp" style="width: 100%; height: 250px;"></canvas>
      </div>
    </div>
  </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">


/////////////////////////////////////////


var select_year = 0;
var select_month = 0;
var dataTable = function(year, month){
$('#year').val(year);
$('#month').val(month);
$('.month_select.active').removeClass('active');
select_year = year;
select_month = month;
$('#loadigPage').show('slow');
$.ajax({
url: '/admin/getOrders-payland',
        type:'POST',
        data: {year:year, month:month, '_token':"{{csrf_token()}}"},
        success: function(response){
        if (response.status === 'true'){

        $('#ms_' + year + '_' + month).addClass('active');
        $('#tableItems').html('');
        $('#payland_month').text(response.total_month);
        var count = 0;
        $.each((response.respo_list), function(index, val) {

        count++;
        var page = Math.ceil(count/10);

        var row = '';
        var row = '<tr class="payland_page'+page+'" '
        if (page == 1) row += '>';
        else row += 'style="display: none">';
        
        row += '<td class="text-center">' + val.date + '</td>';
        row += '<td >' + val.customer_name + '<br>' + val.customer + '</td>';
        row += '<td class="text-right">' + val.amount + ' ' + val.currency + '</td>';
        row += '<td class="text-center pay-status-' + val.status + '">' + val.status + '</td>';
        row += '<td class="text-center">' + val.sourceType + '<br>' + val.pan + '</td>';
        $('#tableItems').append(row);
        });
        } else{
        window.show_notif('ERROR', 'danger', 'El listado está vacío no ha sido guardado.');
        }
        $('#loadigPage').hide('slow');
        },
        error: function(response){
        window.show_notif('ERROR', 'danger', 'No se ha podido obtener los detalles de la consulta.');
        $('#loadigPage').hide('slow');
        }
});
}


var dataPaylandSummary = function(){
$.ajax({
url: '/admin/get-summary-payland',
        type:'GET',
        success: function(response){
        console.log(response);
        if (response.status === 'true'){
          $('#payland_today').text(response.today);
          
          $('#payland_temp').text(response.totals.SUCCESS)
          $('#payland_total').text(response.count.SUCCESS);
          $('#payland_average').text((response.totals.SUCCESS/response.count.SUCCESS).toFixed(2));
          
          
        new Chart(document.getElementById("barChartTemp"), {
        type: 'line',
                data: {
                labels: [{!!$months_label!!}],
                        datasets: [
                        {
                        data: response.result.SUCCESS,
                                label: 'Pagadas',
                                borderColor: "rgba(104, 255, 0, 1)",
                                fill: false
                        },
                        {
                        data: response.result.REFUSED,
                                label: 'Rechazadas',
                                borderColor: "rgb(248, 208, 83)",
                                fill: false
                        },
                        {
                        data: response.result.ERROR,
                                label: 'error',
                                borderColor: "rgba(232, 142, 132, 1)",
                                fill: false
                        },
                        ]
                },
                options: {
                title: {
                display: false,
                        text: ''
                }
                }
        });
        } else{
        window.show_notif('ERROR', 'danger', 'El listado está vacío no ha sido guardado.');
        console.log('error');
        }
        $('#loadigPage').hide('slow');
        },
        error: function(response){
        window.show_notif('ERROR', 'danger', 'No se ha podido obtener los detalles de la consulta.');
        console.log('error');
        $('#loadigPage').hide('slow');
        }
});
console.log('asdfadf');
}

var currentPage = 1;
$(document).ready(function() {
  var dt = new Date();
  dataTable({!!$selected!!});
  dataPaylandSummary();
  $('.month_select').on('click', function(){
    dataTable($(this).data('year'), $(this).data('month'));
  });
  
  $('#payland_ant').on('click', function(){
    console.log('payland_ant',currentPage);
    if (currentPage>1){
      $('.payland_page'+currentPage).hide('150');
      $('.payland_page'+(currentPage-1)).show();
      currentPage--;
      $('#payland_page').text(currentPage);
    }
  });
  $('#payland_sgt').on('click', function(){
    $('.payland_page'+currentPage).hide('150');
    $('.payland_page'+(currentPage+1)).show();
    currentPage++;
    $('#payland_page').text(currentPage);
  });
});

</script>
@endsection