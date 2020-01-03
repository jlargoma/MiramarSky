<?php

use \Carbon\Carbon;

setlocale(LC_TIME, "ES");
setlocale(LC_TIME, "es_ES");
?>
@extends('layouts.admin-master')

@section('title') Ingresos  @endsection

@section('externalScripts') 
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.js"></script>

<style type="text/css">
  .bordered{
    padding: 15px;
    border:1px solid #e8e8e8;
    background: white;
  }
  .form-control{
    border: 1px solid rgba(0, 0, 0, 0.07)!important;
  }
   td{
      height: 3em !important;
      padding: 7px 9px !important;
    }
  @media only screen and (max-width: 768px){
   
    td.static {
    position: absolute !important;
    }
    .col-ingr{
      width: 180px;
      overflow-x: scroll;
      text-align: left;
      margin-top: 10px;
      padding: 16px 9px !important;
    }
    tr:first-child .col-ingr{
      height: 3em !important;
      margin-top: 0px;
    }
  }
</style>

@endsection

@section('content')
<div class="container padding-5 sm-padding-10">
  <div class="row bg-white">
    <div class="col-md-12 col-xs-12">
      <div class="col-md-3 col-md-offset-3 col-xs-12">
        <h2 class="text-center">
          Ingresos
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
    <div class="col-md-12 col-xs-12 push-20">
      @include('backend.sales._button-contabiliad')
    </div>
  </div>

  <div class="row bg-white push-30">

    @if($errors->any())
    <p class="alert alert-danger">{{$errors->first()}}</p>
    @endif
    <div class="col-xs-12 col-md-12 push-30" >
      @include('backend.sales.ingresos._formIngreso')
    </div>
    <div class="col-xs-12 col-md-12">

      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th class="text-center bg-complete text-white static" style="width: 180px;padding: 16px !important;height: 60px;">
                  AREA DE NEGOCIO</th>
              <th class="text-center bg-complete text-white first-col">
                total<br/>
                <?php echo number_format( $total, 0, ',', '.' ); ?>€
              </th>
              <th class="text-center bg-complete text-white">%</th>
              @foreach($lstMonths as $k => $month)
              <th class="text-center bg-complete text-white">
                {{getMonthsSpanish($month['m'])}}<br/>
                <?php
                if (isset($tMonths[$k]) && $tMonths[$k]>0){
                  echo number_format( $tMonths[$k], 0, ',', '.' ).'€';
                } else {
                  echo '--';
                }
                ?>
              </th>
              @endforeach
            </tr>
          </thead>           
          <tbody>
            <tr>
              <td class="static col-ingr">VENTAS TEMPORADA</td>
              <td class="first-col">
                <?php echo number_format($totalBooks, 0, ',', '.'); ?>€
              </td>
              <td>
                <?php $percent = ($totalBooks / $t_year) * 100; ?>
                <?php echo number_format($percent, 2, '.', ',') ?>%
              </td>

              @foreach($lstMonths as $k => $month)
              <td>
                <?php
                if (isset($salesBook[$k]) && $salesBook[$k] > 0) {
                  echo number_format($salesBook[$k], 0, ',', '.') . '€';
                } else {
                  echo '--';
                }
                ?>
              </td>
              @endforeach
            </tr>
            @foreach($aIncomes as $k => $income)
            <tr>
              <td class="static col-ingr">{{$income[0]}}</td>
              <td class="first-col">
              <?php echo number_format($income[1], 0, ',', '.'); ?>€
              </td>
              <td>
                <?php $percent = ($income[1] / $t_year) * 100; ?>
                <span id="total_income_{{$k}}">
                <?php echo number_format($percent, 2, '.', ',') ?>%
                </span>
              </td>
              @foreach($lstMonths as $m => $month)
              <td>
                <?php
                if (isset($arrayIncomes[$k]) && isset($arrayIncomes[$k][$m]) && $arrayIncomes[$k][$m] > 0) {
                  $valAux = $arrayIncomes[$k][$m];
                } else {
                  $valAux =  0;
                }
                ?>
                <input 
                  type="text" 
                  id='icome_<?php echo $k.'_'.$m; ?>' 
                  value="<?php echo $valAux; ?>" 
                  class="form-control incomesVal"
                  data-type="<?php echo $income[0]; ?>"
                  data-m="<?php echo $month['m']; ?>"
                  data-y="<?php echo $month['y']; ?>"
                  >
              </td>
              @endforeach
            </tr>
             @endforeach
          </tbody>
        </table>

      </div>
    </div>


  </div>

  <div class="row bg-white push-30" id="contentTableExpenses">

  </div>

</div>

@endsection	


@section('scripts')
<script>
  $(document).ready(function() {
    $('.incomesVal').on('change',function(){
      
      var value = $(this).val();
      
      if ($.isNumeric( value )){
        var data = {
          val: value,
          k:  $(this).data('type'),
          m:  $(this).data('m'),
          y:  $(this).data('y'),
        };
        $.post("/admin/ingresos/upd",data).done(function(resp){
          if (resp == 'ok') {         
            window.show_notif('Ingresos:','success','Monto Actualizado');
          } else {
            window.show_notif('Ingresos:','danger','No se pudo actualizar el valor solicitado');
          }
                
        });
        
      } else {
        alert('Error: El valor debe ser numérico');
      }
    });
  });


</script>
@endsection