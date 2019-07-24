<?php

use \Carbon\Carbon;

setlocale(LC_TIME, "ES");
setlocale(LC_TIME, "es_ES");
?>
@extends('layouts.admin-master')

@section('title') Limpiezas  @endsection

@section('externalScripts')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.js"></script>
<script type="text/javascript" src="{{asset('/forfait/js/bootbox.min.js')}}"></script>
@endsection

@section('content')
<div class="container padding-5 sm-padding-10">
  <div class="row bg-white">
    <div class="col-md-12 col-xs-12">
      <div class="col-md-3 col-md-offset-3 col-xs-12">
        <h2 class="text-center">
          Limpiezas
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
            <th class ="text-center bg-complete text-white col-md-2">Nombre</th>
            <th class ="text-center bg-complete text-white col-md-1">tipo</th>
            <th class ="text-center bg-complete text-white col-md-1">Pax</th>
            <th class ="text-center bg-complete text-white col-md-1">apto</th>
            <th class ="text-center bg-complete text-white col-md-2">in - out</th>
            <th class ="text-center bg-complete text-white col-md-2"><i class="fa fa-moon"></i></th>
            <th class ="text-center bg-complete text-white col-md-2">Limpieza<br><b id="t_limp"></b></th>
            <th class ="text-center bg-complete text-white col-md-2">Extras<br><b id="t_extr"></b></th>
            <th class ="text-center bg-complete text-white col-md-1">Actualizar</th>
            </thead>
            <tbody >
              <tr>
                <td colspan="6"><strong>Monto Fijo Mensual</strong></td>
                <td><input id="limp_fix" type="text" class="form-control"></td>
                <td><input id="extr_fix" type="text" class="form-control" readonly=""></td>
                <td><button type="button" data-id="fix" class="btn btn-link limpieza_upd">Actualizar</button></td>
                <td></td>
              </tr>
            </tbody>
            <tbody id="tableItems">
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="col-md-4 col-xs-12">
      <div>
        <canvas id="barChartMonth" style="width: 100%; height: 250px;"></canvas>
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

  var myBarChart = new Chart('barChartMonth', {
      type: 'bar',
      data: {
          labels: [{!!$months_1['months_label']!!}],
              datasets: [
              {
                label: "Total Limpieza:",
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 0.2)',
                borderWidth: 1,
                data: [{!!$months_1['months_val']!!}],
              }
              ]
      },
  });
  
   new Chart(document.getElementById("barChartTemp"), {
        type: 'line',
        data: {
          labels: [{!!$months_1['months_label']!!}],
          datasets: [
            {
            data: [{!!$months_1['months_val']!!}],
            label: '{{$months_1['year']}} - {{$months_1['year']-1}}',
            borderColor: "rgba(54, 162, 235, 1)",
            fill: false
            },
            {
            data: [{!!$months_2['months_val']!!}],
            label: '{{$months_2['year']}} - {{$months_2['year']-1}}',
            borderColor: "rgba(104, 255, 0, 1)",
            fill: false
            },
            {
            data: [{!!$months_3['months_val']!!}],
            label: '{{$months_3['year']}} - {{$months_3['year']-1}}',
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
      
      /////////////////////////////////////////
  
  
  var limp_year  = 0;
  var limp_month = 0;
  var dataTable = function(year, month){
    $('.month_select.active').removeClass('active');
    limp_year  = year;
    limp_month = month;
    $('#loadigPage').show('slow');
    $('#limp_fix').val('');
    $('#extr_fix').val('');
    $.ajax({
        url: '/admin/limpiezasLst',
        type:'POST',
        data: {year:year, month:month, '_token':"{{csrf_token()}}"},
        success: function(response){
        if (response.status === 'true'){

          $('#ms_'+year+'_'+month).addClass('active');
          
          $('#t_limp').text(response.total_limp);
          $('#t_extr').text(response.total_extr);
          $('#limp_fix').val(response.month_cost);
          $('#monthly_extr').text(0);
          $('#tableItems').html('');
          $.each((response.respo_list), function(index, val) {
            var row = '';
            if (val.agency){
              var name = '<img style="width: 20px;" src="' + val.agency + '" align="center" />' + val.name;
            }
            var name = val.name

            var row = '<tr><td>' + name + '</td>';
            row += '<td class="text-center">' + val.type + '</td>';
            row += '<td class="text-center">' + val.pax + '</td>';
            row += '<td class="text-center">' + val.apto + '</td>';
            row += '<td class="text-center">' + val.check_in + '</td>';
            row += '<td class="text-center">' + val.nigths + '</td>';
            row += '<td class="text-center"><input id="limp_' + val.id + '" type="text" class="form-control" value="' + val.limp + '"></td>';
            row += '<td class="text-center"><input id="extr_' + val.id + '" type="text" class="form-control" value="' + val.extra + '"></td>';
            row += '<td class="text-center"><button type="button" data-id="' + val.id + '" class="btn btn-link limpieza_upd">Actualizar</button></td></tr>';
            $('#tableItems').append(row);
          });

          
        } else{
        bootbox.alert({
        message: '<div class="text-danger bold" style="margin-top:10px">Se ha producido un ERROR. El PAN no ha sido guardado.<br/>Contacte con el administrador.</div>',
                backdrop: true
        });
        }
        $('#loadigPage').hide('slow');
        },
        error: function(response){
        bootbox.alert({
        message: '<div class="text-danger bold" style="margin-top:10px">Se ha producido un ERROR. No se ha podido obtener los detalles de la consulta.<br/>Contacte con el administrador.</div>',
                backdrop: true
        });
        $('#loadigPage').hide('slow');
        }
    });
  }
  $(document).ready(function() {
  var dt = new Date();
  dataTable({!!$selected!!});
  $('.month_select').on('click', function(){
  dataTable($(this).data('year'),$(this).data('month'));
  });

  $('#limpieza_table').on('click','.limpieza_upd', function(){
    var id = $(this).data('id');
    var row = $(this).closest('tr');
    var data = {
        'id':id,
        'year':limp_year,
        'month':limp_month,
        '_token':"{{csrf_token()}}",
        'limp_value': row.find('#limp_'+id).val(),
        'extr_value': row.find('#extr_'+id).val(),
      }
    $('#loadigPage').show('slow');
    $.ajax({
          url: '/admin/limpiezasUpd',
          type:'POST',
          data: data,
          success: function(response){
          if (response.status === 'true'){
            bootbox.alert({
          message: '<div class="text-success bold" >Registro Guardado</div>',
                  backdrop: true
          });
          } else{
          bootbox.alert({
          message: '<div class="text-danger bold">'+response.msg+'</div>',
                  backdrop: true
          });
          }
          $('#loadigPage').hide('slow');
          },
          error: function(response){
          bootbox.alert({
          message: '<div class="text-danger bold" style="margin-top:10px">Se ha producido un ERROR.<br/>Contacte con el administrador.</div>',
                  backdrop: true
          });
          $('#loadigPage').hide('slow');
          }
    });
  });


});

</script>
@endsection