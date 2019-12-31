<?php   
use \Carbon\Carbon;
use \App\Classes\Mobile;
setlocale(LC_TIME, "ES");
setlocale(LC_TIME, "es_ES");
$mobile = new Mobile();
$isMobile = $mobile->isMobile();
?>
@extends('layouts.admin-master')

@section('title') Contabilidad  @endsection

@section('externalScripts')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.js"></script>
    <style>
   
  
    </style>
@endsection

@section('content')
    <div class="container padding-5 sm-padding-10">
        <div class="row bg-white">
            <div class="col-md-12 col-xs-12">

                <div class="col-md-3 col-md-offset-3 col-xs-12">
                    <h2 class="text-center">
                        Contabilidad
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
        <div class="row bg-white push-30">
            <div class="col-lg-4 col-md-6 col-xs-12">
                <div class="col-lg-6 col-md-6 hidden-mobile">
                    <div>
                        <canvas id="barChart" style="width: 100%; height: 250px;"></canvas>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6  hidden-mobile">
                    <div>
                        <canvas id="barChart2" style="width: 100%; height: 250px;"></canvas>
                    </div>
                </div>
              <div class="col-md-12 col-xs-12">
                @include('backend.sales._by_season')
                </div>
            </div>
            <div class="col-lg-8 col-md-6 col-xs-12">
                @include('backend.sales._stats')
            </div>

        </div>
        <div class="row bg-white">
            <div class="col-lg-8 col-md-8 col-xs-12">
                <div class="row table-responsive" style="border: 0px!important">
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
                              if (isset($t_room_month[$k]) && $t_room_month[$k]>1){
                                echo number_format( $t_room_month[$k], 0, ',', '.' ).'€';
                              } else {
                                echo '--';
                              }
                              ?>
                            </th>
                            @endforeach
                            
                       <tbody>
                            @foreach($lstRooms as $roomID => $name)
                            
                            <tr class="text-center">
                          @if($isMobile)
                              <td class="text-left static" style="white-space: nowrap; width: 130px;color: black;overflow-x: scroll;margin-top: 2px;padding: 7px 9px !important;">  
                                {!!$name!!}
                              </td>
                              <th class="text-center first-col" style="padding-right:13px !important;padding-left: 135px!important">  
                          @else
                              <td class="text-left" style="width: 130px;">  
                                {!!$name!!}
                              </td>
                              <th class="text-center ">  
                          @endif
                          
                          
                             <?php
                                $totalRoom = 0;
                                if (isset($t_rooms[$roomID]) && $t_rooms[$roomID]>1){
                                  $totalRoom = $t_rooms[$roomID];
                                  echo number_format( $totalRoom, 0, ',', '.' ).'€';
                                } else {
                                  echo '--';
                                }
                                ?>
                              </th>
                              <td class="text-center">
                                <?php 
                                $percent = ($totalRoom / $t_all_rooms) * 100; 
                                echo number_format($percent, 0, ',', '.');
                                ?>%
                              </td>
                              @foreach($lstMonths as $k => $month)
                              <th class="text-center">
                              <?php
                              if (isset($sales_rooms[$roomID]) && isset($sales_rooms[$roomID][$k]) && $sales_rooms[$roomID][$k]>1){
                                echo number_format( $sales_rooms[$roomID][$k], 0, ',', '.' ).'€';
                              } else {
                                echo '--';
                              }
                              ?>
                            </th>
                              @endforeach
                            </tr>
                            @endforeach
                            </tbody>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-xs-12">
                <div class="col-md-12 col-xs-12">
                    <div>
                        <canvas id="barChartMonth" style="width: 100%; height: 250px;"></canvas>
                    </div>
                </div>
                <div class="col-md-12 col-xs-12">
                    <div>
			<?php $dataChartYear = \App\Rooms::getPvpByMonth(($year->year - 1 )) ?>
                        <?php $dataChartPrevYear = \App\Rooms::getPvpByMonth(($year->year - 2 )) ?>
                        <canvas id="barChartTemp" style="width: 100%; height: 250px;"></canvas>
                    </div>
                </div>
              
                @if(env('APP_APPLICATION') != "riad")
                <?php 
                 $t_forfaits = $t_equipos = $t_clases = $t_otros = 0;
                ?>

                <div class="col-md-12 col-xs-12">
                  <h3>Resumen Forfaits</h3>
                  <div class=" table-responsive">
                  <table class="table table-resumen">
                    <thead>
                      <tr class="resume-head">
                        <th class="static">Concepto</th>
                        <th class="first-col">Total</th>
                        @foreach($months_ff as $item)
                        <th>{{$item['name']}} {{$item['year']}}</th>
                         <?php 
                           $t_forfaits += $item['data']['forfaits'];
                           $t_equipos  += $item['data']['equipos'];
                           $t_clases   += $item['data']['clases'];
                           $t_otros    += $item['data']['otros'];
                         ?>
                        @endforeach
                      </tr>
                   </thead>
                   <tbody>
                      <tr>
                        <td class="static">Forfaits</td>
                        <td class="first-col"><?php echo number_format($t_forfaits, 0, ',', '.') ?> €</td>
                        @foreach($months_ff as $item)
                        <td><?php echo number_format($item['data']['forfaits'], 0, ',', '.'); ?>€</td>
                        @endforeach
                      </tr>
                      <tr>
                        <td class="static">Materiales</td>
                        <td class="first-col"><?php echo number_format($t_equipos, 0, ',', '.') ?> €</td>
                        @foreach($months_ff as $item)
                        <td><?php echo number_format($item['data']['equipos'], 0, ',', '.'); ?>€</td>
                        @endforeach
                      </tr>
                      <tr>
                        <td class="static">Clases</td>
                        <td class="first-col"><?php echo number_format($t_clases, 0, ',', '.') ?> €</td>
                        @foreach($months_ff as $item)
                        <td><?php echo number_format($item['data']['clases'], 0, ',', '.'); ?>€</td>
                        @endforeach
                      </tr>
                      <tr>
                        <td class="static">Otros</td>
                        <td class="first-col"><?php echo number_format($t_otros, 0, ',', '.') ?> €</td>
                        @foreach($months_ff as $item)
                        <td><?php echo number_format($item['data']['otros'], 0, ',', '.'); ?>€</td>
                        @endforeach
                      </tr>
                     </tbody>
                  </table>
                </div>
              </div>
              @endif
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
	            <?php echo "'" . $lastThreeSeason->format('y') . "-".$lastThreeSeason->copy()->addYear()->format('y')."'," ?>
                <?php $lastThreeSeason->addYear(); ?>
            <?php endfor; ?>
        ],
        datasets: [
          {
            label: "Ingresos por Temp",
            backgroundColor: [
              'rgba(54, 162, 235, 0.2)',
              'rgba(54, 162, 235, 0.2)',
              'rgba(54, 162, 235, 0.2)',
              'rgba(54, 162, 235, 0.2)'
            ],
            borderColor: [
              'rgba(54, 162, 235, 1)',
              'rgba(54, 162, 235, 1)',
              'rgba(54, 162, 235, 1)',
              'rgba(54, 162, 235, 1)'
            ],
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

      var myBarChart = new Chart('barChart', {
        type: 'line',
        data: data,
      });

      var myBarChart = new Chart('barChart2', {
        type: 'bar',
        data: data,
      });


      var myBarChart = new Chart('barChartMonth', {
        type: 'bar',
        data: {
          labels: [
			  <?php foreach ($dataChartMonths as $key => $value): ?>
                    <?php echo "'" . $key . "'," ?>
                <?php endforeach ?>
          ],
          datasets: [
            {
              label: "Ingresos por Año",
              backgroundColor: [
                'rgba(54, 162, 235, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(54, 162, 235, 0.2)',
              ],
              borderColor: [
                'rgba(54, 162, 235, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(54, 162, 235, 1)',
              ],
              borderWidth: 1,

              data: [
				  <?php foreach ($dataChartMonths as $key => $value): ?>
                    <?php echo "'" . round($value) . "'," ?>
                <?php endforeach ?>
              ],
            }
          ]
        },
      });


      new Chart(document.getElementById("barChartTemp"), {
        type: 'line',
        data: {
          labels: [
			  <?php foreach ($dataChartMonths as $key => $value): ?>
                <?php echo "'" . $key . "'," ?>
            <?php endforeach ?>
          ],
          datasets: [{
            data: [
				<?php foreach ($dataChartMonths as $key => $value): ?>
                <?php echo "'" . round($value) . "'," ?>
            <?php endforeach ?>
            ],
            label: '<?php echo $year->year ?>-<?php echo $year->year + 1?>',
            borderColor: "rgba(54, 162, 235, 1)",
            fill: false
          },
            {
              data: [
				  <?php foreach ($dataChartYear as $key => $value): ?>
                <?php echo "'" . round($value) . "'," ?>
              <?php endforeach ?>
              ],
				<?php $aux = $year->year - 1?>
                label: '<?php echo $aux ?>-<?php echo $aux + 1?>',
              borderColor: "rgba(104, 255, 0, 1)",
              fill: false
            },
            {
              data: [
				  <?php foreach ($dataChartPrevYear as $key => $value): ?>
                <?php echo "'" . round($value) . "'," ?>
              <?php endforeach ?>
              ],
				<?php $aux = $year->year - 1 ?>
                label: '<?php echo $aux - 1 ?>-<?php echo $aux ?>',
              borderColor: "rgba(232, 142, 132, 1)",
              fill: false
            }
          ]
        },
        options: {
          title: {
            display: false,
            text: ''
          }
        }
      });

    </script>
@endsection