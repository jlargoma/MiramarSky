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
				<select id="fecha" class="form-control minimal">
                     <?php $fecha = $inicio->copy(); ?>
                     <?php $init = Carbon::now()->copy()->SubYears(3); ?>
                     <?php for ($i=1; $i <= 3; $i++): ?>
                         <option value="<?php echo $init->copy()->format('Y'); ?>"
                            <?php if (  $fecha->copy()->format('Y') == $init->copy()->format('Y') ||
                                        $fecha->copy()->addYear()->format('Y') == $init->copy()->format('Y')
                                    ){ echo "selected"; }?> >
                            <?php echo $fecha->copy()->format('Y')."-".$fecha->copy()->addYear()->format('Y'); ?> 
                         </option>
                         <?php $init->addYear(); ?>
                     <?php endfor; ?>
                 </select>     
			</div>
		</div>
	</div>
</div>
<div class="container-fluid">
	<div class="row bg-white push-30">
		<div class="col-lg-8 col-md-10 col-xs-12 push-20">
			
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
                            <th class="text-center bg-complete text-white">total</th>
                            <th class="text-center bg-complete text-white">%</th>
        					<?php $months = $inicio->copy(); ?>
        					<?php for ($i=1; $i <= 12 ; $i++): ?>
        						<th class="text-center bg-complete text-white">&nbsp;<?php echo $months->formatLocalized('%b') ?>&nbsp;</th>
        						<?php $months->addMonth() ?>
        					<?php endfor; ?>
        					

        				</tr>
        			</thead>
        			<tbody>
                        <?php $totalAllRoom = 0; ?>

                        <?php foreach ($rooms as $key => $room): ?>
                            <?php $totalRoom = 0; ?>
                            <?php $monthsRooms = $inicio->copy(); ?>
                            <?php for ($i=1; $i <= 12 ; $i++): ?>
                                <?php $totalRoom += $priceBookRoom[$room->id][$monthsRooms->copy()->format('Y')][$monthsRooms->copy()->format('n')] ?>
                                <?php $monthsRooms->addMonth() ?>
                            <?php endfor; ?>
                            <?php $totalAllRoom += $totalRoom; ?>
                        <?php endforeach ?>    
        				<?php foreach ($rooms as $key => $room): ?>
        					<tr>
        						<td class="text-center" style="padding: 12px 20px!important">
        							<?php echo $room->name ?> <b><?php echo $room->nameRoom ?></b>
        						</td>
                                <?php $totalRoom = 0; ?>
                                <?php $monthsRooms = $inicio->copy(); ?>
                                <?php for ($i=1; $i <= 12 ; $i++): ?>
                                    <?php $totalRoom += $priceBookRoom[$room->id][$monthsRooms->copy()->format('Y')][$monthsRooms->copy()->format('n')] ?>
                                    <?php $monthsRooms->addMonth() ?>
                                <?php endfor; ?>

                                <td class="text-center">
                                    <b><?php echo number_format($totalRoom,0,',','.') ?>€</b>
                                </td>
                                <td class="text-center">
                                    <?php if($totalAllRoom == 0) {$totalAllRoom = 1;}?>
                                    <?php $percent = ($totalRoom / $totalAllRoom) * 100; ?>
                                    &nbsp;&nbsp;<b><?php echo number_format($percent,0,',','.') ?>%</b>&nbsp;&nbsp;
                                </td>
        						
        						<?php $monthsRooms = $inicio->copy(); ?>
    	    					<?php for ($i=1; $i <= 12 ; $i++): ?>
    	    						<td class="text-center" style="padding: 12px 20px!important">
    	    							<?php if ($priceBookRoom[$room->id][$monthsRooms->copy()->format('Y')][$monthsRooms->copy()->format('n')] == 0): ?>
    	    								---
    	    							<?php else: ?>
    	    								<b><?php echo number_format($priceBookRoom[$room->id][$monthsRooms->copy()->format('Y')][$monthsRooms->copy()->format('n')],0,',','.') ?> €</b>
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
                    <?php $dataChartMonths = \App\Rooms::getPvpByMonth($inicio->copy()->format('Y')) ?>

                   <canvas id="barChartMonth" style="width: 100%; height: 250px;"></canvas>
               </div>
            </div>
        </div>
	</div>
</div>
	
@endsection	


@section('scripts')
<script type="text/javascript">

	$('#fecha').change(function(event) {
	    
	    var year = $(this).val();
	    window.location = '/admin/contabilidad/'+year;

	});

    var data = {
        labels: [
                    <?php foreach ($arrayTotales as $key => $value): ?>
                        <?php echo "'".$key."'," ?>
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
                        <?php foreach ($arrayTotales as $key => $value): ?>
                            <?php echo "'".round($value)."'," ?>
                        <?php endforeach ?>
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
                            <?php echo "'".$key."'," ?>
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
                                            <?php echo "'".round($value)."'," ?>
                                        <?php endforeach ?>
                                        ],
                            }
                        ]
            },
    });
    
</script>
@endsection