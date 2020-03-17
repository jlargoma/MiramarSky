<?php   
use \Carbon\Carbon;
use \App\Classes\Mobile;
setlocale(LC_TIME, "ES");
setlocale(LC_TIME, "es_ES");
$mobile = new Mobile();
$isMobile = $mobile->isMobile();
?>
@extends('layouts.admin-master')

@section('title') Gastos  @endsection

@section('externalScripts')
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.js"></script>
  <style>
    .table-resumen .first-col {
      white-space: nowrap;
    }

    tr.text-center.contab-site,
    tr.text-center.contab-site td{
      color: #fff;
      font-weight: 600;
      background-color: #6d5cae;
    }
    tr.text-center.contab-ch {
        color: blue;
        font-weight: 600;
    }

    i.fas.fa-plus-circle.toggle-contab-site,
    i.fas.fa-plus-circle.toggle-contab-extra,
    i.fas.fa-plus-circle.toggle-contab {
      padding: 5px;
      cursor: pointer;
    }
    
    .contab-ch.tr-close,
    .contab-extras.tr-close,
    .contab-room.tr-close{
      display: none;
    }
    .pieChart{
      max-width: 270px;
      margin: 1em auto;
    }
    button.del_expense.btn.btn-danger.btn-xs {
      margin: 3px 14px;
    }
    
    .table-responsive>.table>tbody#tableItems>tr>td{
       white-space: normal;
      border-left: solid 1px #cacaca;
      padding: 8px !important;
    }
    .table-responsive>.table>tbody#tableItems>tr.selected {
      color: #000;
    }
    .roomEspecifica.text-center.selected {
      background-color: #1b406c;
      color: #fff;
    }
    .roomEspecifica{
      width: 30px; float: left; margin: 5px 2px;
      cursor: pointer;
    }
    input[type="number"] {
        padding: 6px;
        border: none;
    }
  </style>
@endsection

@section('content')
    <div class="container padding-5 sm-padding-10">
        <div class="row bg-white">
            <div class="col-md-12 col-xs-12">

                <div class="col-md-3 col-md-offset-3 col-xs-12">
                    <h2 class="text-center">Gastos</h2>
                </div>
                <div class="col-md-2 col-xs-12 sm-padding-10" style="padding: 10px">
                    @include('backend.years._selector')
                </div>
            </div>
        </div>
       <div class="row mb-1em">
         @include('backend.sales._button-contabiliad')
        </div>
    </div>
    <div class="container-fluid">
      <button type="button" class="btn btn-success" id="addNew_ingr" type="button" data-toggle="modal" data-target="#modalAddNew"><i class="fas fa-plus-circle toggle-contab-site"></i> Añadir</button>
        <div class="row">
          <div class="col-md-6 col-xs-12">
             @include('backend.sales.gastos.resume-by-month')
          </div>
          <div class="col-md-6 col-xs-12">
              <canvas id="barTemporadas" style="width: 100%; height: 250px;"></canvas>
              <?php $dataChartYear = \App\Rooms::getPvpByMonth(($year->year - 1 )) ?>
              <?php $dataChartPrevYear = \App\Rooms::getPvpByMonth(($year->year - 2 )) ?>
              <canvas id="chartTotalByMonth" style="width: 100%; height: 250px;"></canvas>
          </div>
        </div>

       <br/> <br/> <br/>
      
          <div class="col-md-8 col-xs-12">
            <div class="month_select-box">
              <div class="month_select" id="ms_{{($year->year-2000)}}_0" data-month="0" data-year="{{$year->year}}">
              Todos
            </div>
            @foreach($lstMonths as $k=>$v)
            <div class="month_select" id="ms_{{$v['y'].'_'.$v['m']}}" data-month="{{$v['m']}}" data-year="{{$v['y']}}">
              {{getMonthsSpanish($v['m'])}}
            </div>
            @endforeach
            </div>
          </div>
          <div class="col-md-2 col-xs-6">
            <h3>Total Selec. <br/><span id="totalMounth">0</span></h3>
          </div>
          <div class="col-md-2 col-xs-6">
            <h3>Total Año <br/>{{moneda($total_year_amount)}}</h3>
          </div>
          
        <div class="col-md-12 col-xs-12" style="padding-right: 0; min-height: 43em;">
          <input type="hidden" id="year" value="">
          <input type="hidden" id="month" value="">
          <div class="table-responsive">
          <table class="table">
            <thead >
              <th class="text-center bg-complete text-white col-md-1"">Fecha</th>
              <th class="text-center bg-complete text-white col-md-2">Concepto</th>
              <th class="text-center bg-complete text-white col-md-2">Tipo</th>
              <th class="text-center bg-complete text-white col-md-1">Método de pago</th>
              <th class="text-center bg-complete text-white col-md-2">Importe</th>
              <th class="text-center bg-complete text-white col-md-2">Aptos</th>
              <th class="text-center bg-complete text-white">#</th>
              <th class="text-center bg-complete text-white col-md-2">Comentario</th>
            </thead>
            <tbody id="tableItems" class="text-center">
            </tbody>
          </table>
        </div>
      </div>
      
<div class="modal fade" id="modalAddNew" tabindex="-1" role="dialog"  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <strong class="modal-title" id="modalChangeBookTit" style="font-size: 1.4em;">Añadir Gasto</strong>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">@include('backend.sales.gastos._form')</div>
    </div>
  </div>
</div>
</div>
    
@endsection

<!---->
@section('scripts')
    <script type="text/javascript">
      var myBarChart = new Chart('barTemporadas', {
        type: 'bar',
        data: {
          labels: [
              <?php foreach ($totalYear as $k=>$v){ echo "'" . $k. "'," ;} ?>
          ],
          datasets: [
            {
              label: "Gastos por Temp",
              backgroundColor: 'rgba(54, 162, 235, 0.2)',
              borderColor: 'rgba(54, 162, 235, 1)',
              borderWidth: 1,
              data: [
                  <?php foreach ($totalYear as $k=>$v){ echo "'" . round($v). "'," ;} ?>
              ],
            }
          ]
          }
      });




      new Chart(document.getElementById("chartTotalByMonth"), {
        type: 'line',
        data: {
          labels: [
            <?php foreach ($dataChartMonths as $key => $value) echo "'" . $key . "',";?>
          ],
          datasets: [
            {
              data: [
		<?php 
                foreach ($yearMonths[$year->year-2] as $key => $value){
                  if($key>0) echo "'" . round($value) . "',";
                }  
                ?>
              ],
              label: '<?php echo $year->year - 2 ?>',
              borderColor: "rgba(232, 142, 132, 1)",
              fill: false
            },
            
            {
              data: [
		<?php 
                foreach ($yearMonths[$year->year-1] as $key => $value){
                  if($key>0) echo "'" . round($value) . "',";
                }  
                ?>
              ],
              label: '<?php echo $year->year - 1 ?>',
              borderColor: "rgba(104, 255, 0, 1)",
              fill: false
            },
            {
              data: [
                <?php 
                foreach ($yearMonths[$year->year] as $key => $value){
                  if($key>0) echo "'" . round($value) . "',";
                }  
                ?>
              ],
              label: '<?php echo $year->year ?>',
              borderColor: "rgba(54, 162, 235, 1)",
              fill: false
            },
            
          ]
        },
        options: {
          title: {
            display: true,
            text: 'Total x Año'
          }
        }
      });
      

      $('.toggle-contab-site').on('click',function(){
        var id = $(this).data('id');
        if($(this).hasClass('open')){
          $(this).removeClass('open');
          $('.contab-ch-'+id).addClass('tr-close');
          $('.contab-rsite-'+id).addClass('tr-close');
        } else {
          $(this).addClass('open');
          $('.contab-ch-'+id).removeClass('tr-close');
        }
      });
      $('.toggle-contab').on('click',function(){
        var id = $(this).data('id');
        if($(this).hasClass('open')){
          $(this).removeClass('open');
          $('.contab-room-'+id).addClass('tr-close');
          
        } else {
          $(this).addClass('open');
          $('.contab-room-'+id).removeClass('tr-close');
        }
      });
      $('.toggle-contab-extra').on('click',function(){
        if($(this).hasClass('open')){
          $(this).removeClass('open');
          $('.contab-extras').addClass('tr-close');
          
        } else {
          $(this).addClass('open');
          $('.contab-extras').removeClass('tr-close');
        }
      });




          
          
  new Chart(document.getElementById("chart_1"), {
    type: 'pie',
    data: {
      labels: [<?php foreach($gastos as $k=>$item) echo '"' . $gType[$k] . '",'; ?>],
      datasets: [{
          backgroundColor: [
          <?php 
          $auxStart = 455;
          foreach($gastos as $k=>$item){
            $auxStart +=50;
            echo '"#' . dechex($auxStart) . '",';
          } ?>
          ],
          data: [<?php foreach($gastos as $k=>$item) echo "'" .round($item[0]). "',"; ?>]
        }]
    },
    options: {
      title: {display: false},
      legend: {display: false},
    }
  });

        
      
  var expense_year  = 0;
  var expense_month = 0;
  var dataTable = function(year, month){

    $('#year').val(year);
    $('#month').val(month);
   
    $('.month_select.active').removeClass('active');
    expense_year  = year;
    expense_month = month;
    $('#loadigPage').show('slow');
    $.ajax({
        url: '/admin/gastos/gastosLst',
        type:'POST',
        data: {year:year, month:month, '_token':"{{csrf_token()}}"},
        success: function(response){
          if (response.status === 'true'){

            $('#ms_'+year+'_'+month).addClass('active');
            $('#tableItems').html('');
            $('#totalMounth').html(response.totalMounth);
            $.each((response.respo_list), function(index, val) {
              var row = '<tr><td>' + val.date + '</td>';
              row += '<td>' + val.concept + '</td>';
              row += '<td>' + val.type + '</td>';
              row += '<td>' + val.typePayment + '</td>';
              row += '<td class="editable">' + val.import+ '</td>';
              row += '<td>' + val.aptos + '</td>';
              row += '<td><button data-id="' + val.id + '" type="button" class="del_expense btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>';
              row += '<td>' + val.comment + '</td>';
              $('#tableItems').append(row);
            });
          } else{
            window.show_notif('ERROR','danger','El listado está vacío no ha sido guardado.');
          }
          $('#loadigPage').hide('slow');
        },
        error: function(response){
          window.show_notif('ERROR','danger','No se ha podido obtener los detalles de la consulta.');
          $('#loadigPage').hide('slow');
        }
    });
  }
  $(document).ready(function() {
    var dt = new Date();
    dataTable({!!$current!!});
    $('.month_select').on('click', function(){
    dataTable($(this).data('year'),$(this).data('month'));
    });
    
    $('#tableItems').on('click','.del_expense', function(){
      if (confirm('Eliminar el registro definitivamente?')){
        var id = $(this).data('id');
        $.ajax({
          url: '/admin/gastos/del',
          type:'POST',
          data: {id:id, '_token':"{{csrf_token()}}"},
          success: function(response){
             dataTable($('#year').val(),$('#month').val());
          }
        });
      }
    });
    
    
    $('#modalAddNew').on('click','#reload', function(e){
      location.reload();
    });
    $('#modalAddNew').on('submit','#formNewExpense', function(e){
      e.preventDefault();
      $.ajax({
          url: $(this).attr('action'),
          type:'POST',
          data: $( this ).serializeArray(),
          success: function(response){
            if (response == 'ok'){
              $('#import').val('');
              $('#concept').val('');
              $('#comment').val('');
              alert('Gasto Agregado');
            }
            else alert(response);
          }
        });
    });
  });

$("#tableItems").on('click','tr',function(){
   $(this).addClass('selected').siblings().removeClass('selected');    
});


$(document).ready(function () {
  const hTable = $('#tableItems');
     
     
      function edit (currentElement) {
        var input = $('<input>', {type: "number"})
          .val(currentElement.html())
        currentElement.html(input)
        input.focus(); 
      }

      hTable.on('click','.editable', function () {
        var that = $(this);
        
        /*** Edit info  ****/
          clearAll();
          that.data('val',that.text());
          that.addClass('tSelect')
          edit($(this));
      });

      hTable.on('keyup','.tSelect',function (e) {
        if (e.keyCode == 13) {
          var data = new Array();
          var value = 0;
          hTable.find('.tSelect').each(function() {
            value = $(this).find('input').val();
            data.push($(this).data('id'));
            $(this).text(value).removeClass('tSelect');
          });
          updValues(data,value);
        } else {
          hTable.find('.tSelect').find('input').val($(this).find('input').val());
        }
      });
      
      var clearAll= function(){
         hTable.find('.tSelect').each(function() {
            var value = $(this).find('input').val();
            $(this).text(value).removeClass('tSelect');
          });
        }
        
      var updValues = function(data,value,type){
        var url = "/admin/gastos/update";
        $.ajax({
          type: "POST",
          method : "POST",
          url: url,
          data: {_token: "{{ csrf_token() }}",items: data, val: value,type:type},
          success: function (response)
          {
            if (response.status == 'OK') {
              hTable.find('.tSelect').each(function() {
                $(this).text(value).removeClass('tSelect');
              });
            } else {
//              $('#error').text(data.msg).show();
            }
            console.log(data.msg); // show response from the php script.
          }
        });
    
      }
        
});

    
    </script>
@endsection