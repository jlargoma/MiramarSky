<?php

use \Carbon\Carbon;

setlocale(LC_TIME, "ES");
setlocale(LC_TIME, "es_ES");
?>
@extends('layouts.admin-master')

@section('title') Perdidas y ganancias  @endsection

@section('externalScripts') 
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.js"></script>
<style>
  @media only screen and (max-width: 991px){
    .resume-box .col-md-4{
      width: 33% !important;
      float: left;
      padding: 2px;
      text-align: center;
    }
  }

  td{
    height: 3em !important;
    padding: 7px 9px !important;
  }
  @media only screen and (max-width: 768px){

    td.static {
      position: absolute !important;
      white-space: nowrap;
    }
    .col-ingr{
      width: 180px;
      overflow-x: scroll;
      text-align: left !important;
      margin-top: 1px;
      padding: 13px 9px !important;
    }
    tr:last-child td{
      height: 4em !important;
    }
  }
  @media only screen and (max-width: 425px){
    .resume-box .col-md-4 .p-l-20{
      padding: 1em 0px !important;
    }

    .resume-box .col-md-4 h3{
      font-size: 22px;
    }
    .resume-box .col-md-4 h5{
      font-size: 15px;
    }
  }
</style>
@endsection

@section('content')
<div class="container padding-5 sm-padding-10">
  <div class="row bg-white">
    <div class="col-md-12 col-xs-12">

      <div class="col-md-3 col-md-offset-3 col-xs-12">
        <h2 class="text-center">PERDIDAS Y GANANCIAS</h2>
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
  <div class="row bg-white push-30">
    <div class="col-lg-3 col-md-4 col-xs-12">
      <div>
        <canvas id="barChart" style="width: 100%; height: 250px;"></canvas>
      </div>
    </div>

    <div class="col-lg-9 col-md-8 col-xs-12">
      <div class="row resume-box">
        <div class="col-md-3 m-b-10">
          <div class="box-resumen" style="background-color: #46c37b">
            <h5 class="no-margin p-b-5 text-white ">
              <b>INGRESOS</b>
            </h5>
              {{moneda($totalIngr)}}
          </div>
        </div>
        
        <div class="col-md-3 m-b-10">
          <div class="box-resumen" style="background-color: #a94442">
            <h5 class="no-margin p-b-5 text-white "><b>GASTOS</b></h5>
              {{moneda($totalGasto)}}
          </div>
        </div>
        <div class="col-md-3 m-b-10">
          <div class="box-resumen" style="background-color: #2c5d9b">
            <h5 class="no-margin p-b-5 text-white ">
              <b>BENEFICIO BRUTO</b>
            </h5>
            {{moneda($ingr_bruto)}}
            <?php if ($ingr_bruto > 0 ): ?>
                    <i class="fa fa-arrow-up text-success result"></i>
            <?php else: ?>
                    <i class="fa fa-arrow-down text-danger result"></i>
            <?php endif ?>
          </div>
        </div>
        <div class="col-md-3 m-b-10">
          <div class="box-resumen light-blue" >
            <h5 class="no-margin p-b-5 text-white ">
              <b>BENEFICIO NETO</b>
            </h5>
            {{moneda($ingr_bruto-$lstT_gast['impuestos'])}}
            <?php if (($ingr_bruto-$lstT_gast['impuestos']) > 0 ): ?>
                    <i class="fa fa-arrow-up text-success result"></i>
            <?php else: ?>
                    <i class="fa fa-arrow-down text-danger result"></i>
            <?php endif ?>
          </div>
        </div>
      </div>
      
      <div style="clear: both;"></div>
        @include('backend.sales._tableSummaryBoxes_PyG')
      
      
      
    </div>
  </div>
  <div class="row bg-white">
    <div class="col-md-12 col-xs-12">
      <div class="row table-responsive" style="border: 0px!important">
        @include('backend.sales._tablePerdidasGanancias')
      </div>
    </div>

  </div>
</div>

@endsection	


@section('scripts')
<script type="text/javascript">
  /* GRAFICA INGRESOS/GASTOS */
  var data = {
          labels: [@foreach($lstMonths as $month) "{{$month['name']}}", @endforeach],
          datasets: [
          {
          label: "Ingresos",
                  backgroundColor: 'rgba(67, 160, 71, 0.3)',
                  borderColor:'rgba(67, 160, 71, 1)',
                  borderWidth: 1,
                  data: [
                    @foreach($tIngByMonth as $k=>$v) {{round($v)}}, @endforeach
                  ],
          },
          {
          label: "Gastos",
                  backgroundColor: 'rgba(229, 57, 53, 0.3)',
                  borderColor: 'rgba(229, 57, 53, 1)',
                  borderWidth: 1,
                  data: [
                    @foreach($tGastByMonth as $k=>$v) {{round($v)}}, @endforeach
                  ],
          }

          ]
  };
  var myBarChart = new Chart('barChart', {
  type: 'line',
          data: data,
  });

</script>
<style>
  .fa.result{
    font-size: 1.13em
  }
  .box-resumen{
    background-color: #a94442;
    padding: 5px 26px;
    font-size: 2em;
    color: #FFF;
    font-weight: 800;
    min-height: 5em;
  }
  .light-blue{
    background-color: #48b0f7
  }
</style>
@endsection