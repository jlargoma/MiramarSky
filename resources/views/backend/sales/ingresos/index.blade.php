<?php   
use \Carbon\Carbon;
use \App\Classes\Mobile;
setlocale(LC_TIME, "ES");
setlocale(LC_TIME, "es_ES");
$mobile = new Mobile();
$isMobile = $mobile->isMobile();
?>
@extends('layouts.admin-master')

@section('title') Ingresos  @endsection

@section('externalScripts')
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.js"></script>
  <style>
      tr.text-center.contab-ch,
    tr.text-center.contab-ch td{
      color: #fff;
      font-weight: 600;
      background-color: #2b5d9b;
    }
    i.fas.fa-plus-circle.toggle-contab {
      padding: 5px;
      cursor: pointer;
    }
    
    .contab-ch.tr-close,
    .contab-extras.tr-close,
    .contab-room.tr-close{
      display: none;
    }
    span.ocup {
        color: red;
        font-size: 11px;
        display: block;
    }
    .pieChart {
      max-width: 220px;
      margin: 0 auto;
    }
    .circle-percent {
      position: relative;
      max-width: 200px;
      margin: 4em auto 0;
    }
    .circle-percent span {
        position: absolute;
        top: 53%;
        left: 36%;
        font-size: 1.5em;
    }
    .circle-percent canvas {
          margin-top: -90px;
    }
    .table-responsive>.table>tbody#tableItems>tr>td{
       white-space: normal;
      border-left: solid 1px #cacaca;
      padding: 8px !important;
    }
    .table-responsive>.table>tbody#tableItems>tr.selected {
      color: #000;
    }
    
    .table-contab .static{
      white-space: nowrap; width: 130px;color: black;
      overflow-x: scroll;
      margin-top: 1px;
      padding: 5px 9px !important;
    }
  </style>
@endsection

@section('content')
    <div class="container padding-5 sm-padding-10">
        <div class="row bg-white">
            <div class="col-md-12 col-xs-12">

                <div class="col-md-3 col-md-offset-3 col-xs-12">
                    <h2 class="text-center">Ingresos</h2>
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
            @include('backend.sales.ingresos.resume-by-month')
          </div>
          <div class="col-md-6 col-xs-12">
            @include('backend.sales.ingresos.resume-by-forfaits')
          </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-6 col-xs-12">
              <canvas id="barChart2" style="width: 100%; height: 250px;"></canvas>
            </div>
            <div class="col-lg-4 col-md-6 col-xs-12">
                    <?php $dataChartYear = \App\Rooms::getPvpByMonth(($year->year - 1 )) ?>
                    <?php $dataChartPrevYear = \App\Rooms::getPvpByMonth(($year->year - 2 )) ?>
                    <canvas id="chartTotalByMonth" style="width: 100%; height: 250px;"></canvas>
                </div>
            <div class="col-lg-4 col-md-6 col-xs-12">
                  <canvas id="barChartMonth" style="width: 100%; height: 250px;"></canvas>
            </div>

        </div>
<div class=" table-responsive" >
    <table class="table">
       <thead>
        <tr>
          @if($isMobile)
            <th class="text-center bg-complete text-white static" style="width: 130px;padding: 16px !important;height: 60px;">Apto</th>
            <th class="text-center bg-complete text-white first-col" style="padding-left: 145px !important;padding-right: 11px !important;">total<br/>
          @else
            <th class="text-center bg-complete text-white" >Apto</th>
            <th class="text-center bg-complete text-white" >total<br/>
          @endif
              <?php echo number_format( $t_all_rooms, 0, ',', '.' ); ?>€
            </th>
            
            <th class="text-center bg-complete text-white">%</th>
            @foreach($lstMonths as $k => $month)
            <th class="text-center bg-complete text-white">
              {{getMonthsSpanish($month['m'])}}<br/>
              <?php
              if (isset($t_room_month[$month['m']]) && $t_room_month[$month['m']]>1){
                echo number_format( $t_room_month[$month['m']], 0, ',', '.' ).'€';
              } else {
                echo '--';
              }
              ?>
            </th>
            @endforeach

       <tbody>

<!-- BEGIN: channels-->                               
      @foreach($chRooms as $ch => $data2)
          <tr class="text-center contab-ch contab-ch">
          @if($isMobile)
          <td class="text-left static" >
                <i class="fas fa-plus-circle toggle-contab" data-id="{{$ch}}"></i>{{$channels[$ch]}}
                <span class="ocup">% Ocup.</span>
              </td>
              <th class="text-center first-col" style="padding-right:13px !important;padding-left: 135px!important">  
          @else
              <td class="text-left" style="width: 130px;">  
                <i class="fas fa-plus-circle toggle-contab" data-id="{{$ch}}"></i>{{$data2['channel']}}
                <span class="ocup">% Ocup.</span>
              </td>
              <th class="text-center ">  
          @endif
              {{moneda($data2['months'][0])}}
              </th>
              <td class="text-center">
                <?php 
                $percent = 0;
                if ($data2['months']>1)
                  $percent = ($data2['months'][0] * 100 / $t_all_rooms); 
//                                  $percent = ($data2['months'][0] * 100 / $data['months'][0]); 
                ?>
                {{round($percent)}}%
              </td>
          @foreach($lstMonths as $k => $month)
            <th class="text-center">
              <?php
              $k_month = $month['m'];
              if (isset($data2['months'][$k_month]) && $data2['months'][$k_month]>1){
                echo moneda($data2['months'][$k_month]);
              } else {
                echo '--';
              }
//                              dd($ch_monthOcupPercent);
              ?>
              @if(isset($ch_monthOcupPercent[$ch][$k_month]) && $ch_monthOcupPercent[$ch][$k_month]>0)
              <span class="ocup">{{$ch_monthOcupPercent[$ch][$k_month]}} %</span>
              @endif
            </th>
          @endforeach
          </tr>
            <!-- BEGIN: ROOMS-->   
           @foreach($data2['rooms'] as $roomID => $name)
          <tr class="text-center contab-room contab-room-{{$ch}} tr-close">
          @if($isMobile)
              <td class="text-left static">
                {!!$name!!}
              </td>
              <th class="text-center first-col" style="padding-right:13px !important;padding-left: 135px!important">  
          @else
              <td class="text-left" style="width: 130px;padding-left: 5px!important">  
                {!!$name!!}
              </td>
              <th class="text-center ">  
          @endif
               <?php
                $totalRoom = 0;
                if (isset($t_rooms[$roomID]) && $t_rooms[$roomID]>1){
                  $totalRoom = $t_rooms[$roomID];
                  echo moneda($totalRoom);
                } else {
                  echo '--';
                }
                ?>
              </th>
              <td class="text-center">
                <?php 
                $percent = ($totalRoom / $t_all_rooms) * 100; 
                echo round($percent).'%';
                ?>
              </td>
          @foreach($lstMonths as $k => $month)
            <th class="text-center">
              <?php
              $k_month = $month['m'];
              if (isset($sales_rooms[$roomID]) && isset($sales_rooms[$roomID][$k_month]) && $sales_rooms[$roomID][$k_month]>1){
                echo moneda( $sales_rooms[$roomID][$k_month]);
              } else {
                echo '--';
              }
              ?>
            </th>
          @endforeach
          </tr>
          @endforeach

<!-- END: ROOMS-->                      
      @endforeach
  <!-- END: channels-->                    
       </tbody>
    </table>
  </div>
    
      
<div class="modal fade" id="modalAddNew" tabindex="-1" role="dialog"  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <strong class="modal-title" id="modalChangeBookTit" style="font-size: 1.4em;">Añadir Ingreso</strong>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">@include('backend.sales.ingresos._form')</div>
    </div>
  </div>
</div>
</div>
    
@endsection

<!---->
@section('scripts')
    <script type="text/javascript">
      var data = {
        labels: [

	        <?php $lastThreeSeason = Carbon::createFromFormat('Y', $year->year)->subYears(3) ?>
	        <?php for ($i=1; $i <= 4; $i++): ?>
	            <?php echo "'" . $lastThreeSeason->format('Y') ."'," ?>
                <?php $lastThreeSeason->addYear(); ?>
            <?php endfor; ?>
        ],
        datasets: [
          {
            label: "Ingresos por Temp",
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1,
            data: [
	            <?php $lastThreeSeason = Carbon::createFromFormat('Y', $year->year)->subYears(3) ?>
                <?php for ($i=1; $i <= 4; $i++): ?>
                    <?php $totalYear = \App\Rooms::getPvpByYear($lastThreeSeason->copy()->format('Y')); ?>
                    <?php echo "'" . $totalYear. "'," ?>
                    <?php $lastThreeSeason->addYear(); ?>
                <?php endfor; ?>
            ],
          }
        ]
      };
      var myBarChart = new Chart('barChart2', {
        type: 'bar',
        data: data,
      });




      new Chart(document.getElementById("chartTotalByMonth"), {
        type: 'line',
        data: {
          labels: [
            <?php foreach ($dataChartMonths as $key => $value) echo "'" . $key . "',";?>
          ],
          datasets: [{
            data: [
              <?php foreach ($dataChartMonths as $key => $value) echo "'" . round($value) . "'," ?>
            ],
            label: '<?php echo $year->year ?>',
            borderColor: "rgba(54, 162, 235, 1)",
            fill: false
            },
            {
              data: [
		<?php foreach ($dataChartYear as $key => $value) echo "'" . round($value) . "',"; ?>
              ],
              label: '<?php echo $year->year - 1 ?>',
              borderColor: "rgba(104, 255, 0, 1)",
              fill: false
            },
            {
              data: [
		<?php foreach ($dataChartPrevYear as $key => $value) echo "'" . round($value) . "',"; ?>
              ],
              label: '<?php echo $year->year - 2 ?>',
              borderColor: "rgba(232, 142, 132, 1)",
              fill: false
            }
          ]
        },
        options: {
          title: {
            display: true,
            text: 'Total x Año'
          }
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

           
           
  
  
 
  
    </script>
@endsection