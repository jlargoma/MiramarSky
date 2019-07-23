<?php   use \Carbon\Carbon;
setlocale(LC_TIME, "ES");
setlocale(LC_TIME, "es_ES");
?>
@extends('layouts.admin-master')

@section('title') Contabilidad  @endsection

@section('externalScripts')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.js"></script>
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
                <div class="col-lg-6 col-md-12 col-xs-12">
                    <div>
                        <canvas id="barChart" style="width: 100%; height: 250px;"></canvas>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-xs-12">
                    <div>
                        <canvas id="barChart2" style="width: 100%; height: 250px;"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-6 col-xs-12">
                @include('backend.sales._stats')
            </div>

        </div>
        <div class="row bg-white">
            <div class="col-lg-8 col-md-8 col-xs-12">
                <div class="row table-responsive" style="border: 0px!important">
                    <table class="table  table-striped " style="margin-top: 0;">
                        <thead>
                        <tr>
                            <th class="text-center bg-complete text-white">Apto</th>
                            <th class="text-center bg-complete text-white">
                              total
                              <?php $totalMain = 0; ?>
                              <div id="main_total"></div>
                            </th>
                            <th class="text-center bg-complete text-white">%</th>
							<?php $months = new Carbon($year->start_date); ?>
							<?php for ($i = 1; $i <= $diff ; $i++): ?>
                            <th class="text-center bg-complete text-white">
                                &nbsp;<?php echo $months->formatLocalized('%b') ?>&nbsp;
                                <?php 
                                  $totalMonth = 0; 
                                  $aux_year = $months->copy()->format('Y');
                                  $aux_month = $months->copy()->format('n');
                                ?>
                                <?php 
                                foreach ($rooms as $key => $room):
                                  if (
                                    isset($priceBookRoom[$room->id]) 
                                    && isset($priceBookRoom[$room->id][$aux_year]) 
                                    && isset($priceBookRoom[$room->id][$aux_year][$aux_month])
                                  )
                                  $totalMonth += $priceBookRoom[$room->id][$aux_year][$aux_month];
                                endforeach; 
                                ?>
                                <br/>
                                <?php 
                                  if ($totalMonth>0):
                                    echo number_format( $totalMonth, 0, ',', '.' ).' €';
                                    $totalMain += $totalMonth;
                                  else:
                                    echo '---';
                                  endif;
                                ?>
                            </th>
							<?php $months->addMonth() ?>
							<?php endfor; ?>


                        </tr>
                        </thead>
                        <tbody>
						<?php $totalAllRoom = 0; ?>

						<?php foreach ($rooms as $key => $room): ?>
                            <?php $totalRoom = 0; ?>
                            <?php $monthsRooms = new Carbon($year->start_date); ?>
                            <?php 
                            for ($i = 1; $i <= $diff; $i++): 
                              if (
                                  isset($priceBookRoom[$room->id]) 
                                  && isset($priceBookRoom[$room->id][$monthsRooms->copy()->format('Y')]) 
                                  && isset($priceBookRoom[$room->id][$monthsRooms->copy()->format('Y')][$monthsRooms->copy()->format('n')])
                                ){
                                $totalRoom += $priceBookRoom[$room->id][$monthsRooms->copy()->format('Y')][$monthsRooms->copy()->format('n')];
                                $monthsRooms->addMonth();
                                }
                            endfor; 
                            ?>
                            <?php $totalAllRoom += $totalRoom; ?>
                        <?php endforeach ?>
						<?php foreach ($rooms as $key => $room): ?>
                        <tr>
                            <td class="text-center" style="padding: 12px 20px!important">
								<?php echo $room->name ?> <b><?php echo $room->nameRoom ?></b>
                            </td>
                              <?php $totalRoom = 0; ?>
                              <?php $monthsRooms = new Carbon($year->start_date); ?>
                              <?php 
                              for ($i = 1; $i <= $diff; $i++): 
                                if (
                                  isset($priceBookRoom[$room->id]) 
                                  && isset($priceBookRoom[$room->id][$monthsRooms->copy()->format('Y')]) 
                                  && isset($priceBookRoom[$room->id][$monthsRooms->copy()->format('Y')][$monthsRooms->copy()->format('n')])
                                ){
                                  $totalRoom += $priceBookRoom[$room->id][$monthsRooms->copy()->format('Y')][$monthsRooms->copy()->format('n')];
                                  $monthsRooms->addMonth();
                                }
                              endfor; 
                              ?>
                            <td class="text-center">
                                <b><?php echo number_format($totalRoom, 0, ',', '.') ?>€</b>
                            </td>
                            <td class="text-center">
								<?php if ($totalAllRoom == 0) {
									$totalAllRoom = 1;
								}?>
								<?php $percent = ($totalRoom / $totalAllRoom) * 100; ?>
                                &nbsp;&nbsp;<b><?php echo number_format($percent, 0, ',', '.') ?>%</b>&nbsp;&nbsp;
                            </td>

							<?php $monthsRooms = new Carbon($year->start_date);  ?>
							<?php for ($i = 1; $i <= $diff ; $i++): ?>
                            <td class="text-center" style="padding: 12px 20px!important">
								<?php if ($priceBookRoom[$room->id][$monthsRooms->copy()->format('Y')][$monthsRooms->copy()->format('n')] == 0): ?>
                                ---
								<?php else: ?>
                                <b><?php echo number_format($priceBookRoom[$room->id][$monthsRooms->copy()->format('Y')][$monthsRooms->copy()->format('n')], 0, ',', '.') ?>
                                    €</b>
								<?php endif ?>

                            </td>
							<?php $monthsRooms->addMonth() ?>
							<?php endfor; ?>

                        </tr>
						<?php endforeach ?>


                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-xs-12">
                <div class="col-md-12 col-xs-12">
                    <div>
						<?php $dataChartMonths = \App\Rooms::getPvpByMonth($year->year) ?>

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
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script type="text/javascript">

    $('#main_total').text("<?php echo number_format( $totalMain, 0, ',', '.' ); ?> €");
      
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