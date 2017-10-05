@extends('layouts.admin-master')

@section('title') Liquidacion @endsection

@section('externalScripts') 
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

@endsection

@section('headerButtoms')
	@include('layouts/headerbuttoms')
@endsection

@section('content')
<?php use \Carbon\Carbon; ?>

<style>
	.table>thead>tr>th {
		padding:0px!important;
	}
	th{
		/*font-size: 15px!important;*/
	}
	td{
		font-size: 13px!important;
		padding: 10px 5px!important;
	}
	.pagos{
		background-color: rgba(255,255,255,0.5)!important;
	}

	td[class$="bi"] {border-left: 1px solid black;}
	td[class$="bf"] {border-right: 1px solid black;}
	
	.coste{
		background-color: rgba(200,200,200,0.5)!important;
	}
	th.text-center.bg-complete.text-white{
		padding: 10px 5px;
		font-weight: 300;
		font-size: 15px!important;
		text-transform: capitalize!important;
	}
</style>
<div class="container-fluid padding-5 sm-padding-10">

	<?php if (isset($jesusito)): ?>
		    <div class="row">
		    	<div class="col-md-12 text-center">
		    		<h2>Estadisticas</h2>
		    	</div>
		        <div class="col-md-12">
					<div class="tab-content">
						<pre>
							<?php foreach ($años as $key => $año): ?>
								<?php foreach ($meses as $key => $mes): ?>
									<?php foreach ($arrayBooks as $clave => $book): ?>
										<?php if (isset($arrayBooks[$año][$key][$clave])): ?>
										<?php echo $año." ".$key." " ?>
										<?php endif ?>
									<?php endforeach ?>
								<?php endforeach ?>
							<?php endforeach ?>
						<div id="curve_chart" style="width: 900px; height: 500px"></div>
					</div>
					<div class="col-md-2 m-t-20">
					    <div class="col-md-12 col-xs-12">
					        <canvas id="barChart" style="width: 100%; height: 250px;"></canvas>
					    </div>
					</div>
					<div class="col-md-2 m-t-20">
					    <div class="col-md-12">
					        <table class="table table-hover demo-table-search table-responsive " style="min-height: 137px" >
					            <thead>
					                <th class="ingresos_temp"  colspan="2">Ingresos Temporada <?php echo $inicio->copy()->format('Y') ?>-<?php echo $inicio->copy()->addYear()->format('Y') ?></th>
					            </thead>
					            <tr>
					                <td class="ingresos_temp">Ventas Temporada</td>
					                <td class="ingresos_temp"> <?php echo number_format($arrayTotales[$inicio->copy()->format('Y')],2,',','.') ?> € </td>
					            </tr>
					            <tr>
					                <td class="ingresos_temp">Total Cobrado</td>
					                <td class="ingresos_temp"><?php echo ($paymentSeason["total"]) ? number_format($paymentSeason["total"],2,',','.') : "---" ?></td>
					            </tr>
					            <thead>
					                <th class="ingresos_temp">Pend Cobro</th>
					                <th class="ingresos_temp"><?php echo number_format($arrayTotales[$inicio->copy()->format('Y')]-$paymentSeason["total"],2,',','.')?></th>
					            </thead>
					        </table>
					        <div class="col-xs-12 not-padding push-20">
					            <canvas id="pie-ing" ></canvas>
					        </div>
					    </div>
					    
					</div>
					<div class="col-md-2 m-t-20">
					    <div class="col-md-12">
					        <table class="table table-hover demo-table-search table-responsive " >
					            <thead>
					                <th class="cobros_temp"  colspan="2">Cobros temporada <?php echo $inicio->copy()->format('Y') ?>-<?php echo $inicio->copy()->addYear()->format('Y') ?></th>
					            </thead>
					            <tr>
					                <td class="cobros_temp">Banco</td>
					                <td class="cobros_temp"><?php echo number_format($paymentSeason["banco"],2,',','.')?>€</td>
					                
					            </tr>
					            <tr>
					                <td class="cobros_temp">Cash</td>
					                <td class="cobros_temp"><?php echo number_format($paymentSeason["cash"],2,',','.')?>€</td>
					            </tr>
					            <thead>
					                <th class="cobros_temp">Total Cobrado</th>
					                <th class="cobros_temp"> <?php echo number_format($paymentSeason["banco"]+$paymentSeason["cash"],2,',','.') ?> € </th>
					            </thead>
					        </table>
					    </div>
					    <div class="col-xs-12 not-padding push-20">
					        <canvas id="pie-cob" style="max-height: 245px!important;max-width: 245px!important" ></canvas>
					    </div>
					</div>
		        </div>
		    </div>

		    <!-- Seccion Estadistica -->
		    <!-- <div class="col-md-12 m-t-20">
		        <div class="row">
		            <div>
		                <table class="table table-hover demo-table-search table-responsive " >
		                    <thead>
		                        <th class="text-center bg-complete text-white" colspan="7">Ingresos de la temporada <?php //echo $inicio->copy()->format('Y') ?>-<?php //echo $inicio->copy()->addYear()->format('Y') ?></th>
		                    </thead>
		                    <thead>
		                        <th class="text-center bg-complete text-white">&nbsp;</th>
		                        <th class="text-center bg-complete text-white">Nov/Dic</th>
		                        <th class="text-center bg-complete text-white">Ene</th>
		                        <th class="text-center bg-complete text-white">Feb</th>
		                        <th class="text-center bg-complete text-white">Mar</th>
		                        <th class="text-center bg-complete text-white">Abr/May</th>
		                        <th class="text-center bg-complete text-white">Total&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
		                    </thead>
		                    <tbody>
		                        <tr>
		                            <th class="text-center p-t-5 p-b-5">Ventas</th>
		                            <td class="text-center p-t-5 p-b-5"><?php //echo number_format($ventas["Ventas"][12],2,',','.')?></td>
		                            <td class="text-center p-t-5 p-b-5"><?php //echo number_format($ventas["Ventas"][1],2,',','.')?></td>
		                            <td class="text-center p-t-5 p-b-5"><?php //echo number_format($ventas["Ventas"][2],2,',','.')?></td>
		                            <td class="text-center p-t-5 p-b-5"><?php //echo number_format($ventas["Ventas"][3],2,',','.')?></td>
		                            <td class="text-center p-t-5 p-b-5"><?php //echo number_format($ventas["Ventas"][4],2,',','.')?></td>
		                            <td class="text-center p-t-5 p-b-5"><?php //echo number_format(array_sum($ventas["Ventas"]),2,',','.')?></td>
		                        </tr>
		                        <tr>
		                            <th class="text-center p-t-5 p-b-5">Benº</th>
		                            <td class="text-center p-t-5 p-b-5"><?php //echo number_format($ventas["Ben"][12],2,',','.')?></td>
		                            <td class="text-center p-t-5 p-b-5"><?php //echo number_format($ventas["Ben"][1],2,',','.')?></td>
		                            <td class="text-center p-t-5 p-b-5"><?php //echo number_format($ventas["Ben"][2],2,',','.')?></td>
		                            <td class="text-center p-t-5 p-b-5"><?php //echo number_format($ventas["Ben"][3],2,',','.')?></td>
		                            <td class="text-center p-t-5 p-b-5"><?php //echo number_format($ventas["Ben"][4],2,',','.')?></td>
		                            <td class="text-center p-t-5 p-b-5"><?php //echo number_format(array_sum($ventas["Ben"]),2,',','.')?></td>
		                        </tr>
		                        <thead>
		                            <th colspan="7" class="ingresos_temp">Ingresos de la temporada <?php //echo $inicio->copy()->subYear()->format('Y') ?>-<?php //echo $inicio->copy()->format('Y') ?> </th>
		                        </thead>
		                        <tr>
		                            <th class="text-center ingresos_temp text-white"></th>
		                            <th class="text-center ingresos_temp text-white">Nov/Dic</th>
		                            <th class="text-center ingresos_temp text-white">Ene</th>
		                            <th class="text-center ingresos_temp text-white">Feb</th>
		                            <th class="text-center ingresos_temp text-white">Mar</th>
		                            <th class="text-center ingresos_temp text-white">Abr/May</th>
		                            <th class="text-center ingresos_temp text-white">Total&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
		                        </tr>
		                        <tr>
		                            <th class="text-center">Ventas</th>
		                            <td class="text-center"><?php //echo number_format($ventasOld["Ventas"][12],2,',','.') ?></td>
		                            <td class="text-center"><?php //echo number_format($ventasOld["Ventas"][1],2,',','.') ?></td>
		                            <td class="text-center"><?php //echo number_format($ventasOld["Ventas"][2],2,',','.') ?></td>
		                            <td class="text-center"><?php //echo number_format($ventasOld["Ventas"][3],2,',','.') ?></td>
		                            <td class="text-center"><?php //echo number_format($ventasOld["Ventas"][4],2,',','.') ?></td>
		                            <td class="text-center"><?php //echo number_format(array_sum($ventasOld["Ventas"]),2,',','.') ?></td>
		                        </tr>
		                        <tr>
		                            <th class="text-center">Benº</th>
		                            <td class="text-center"><?php //echo number_format($ventasOld["Ben"][12],2,',','.') ?></td>
		                            <td class="text-center"><?php //echo number_format($ventasOld["Ben"][1],2,',','.') ?></td>
		                            <td class="text-center"><?php //echo number_format($ventasOld["Ben"][2],2,',','.') ?></td>
		                            <td class="text-center"><?php //echo number_format($ventasOld["Ben"][3],2,',','.') ?></td>
		                            <td class="text-center"><?php //echo number_format($ventasOld["Ben"][4],2,',','.') ?></td>
		                            <td class="text-center"><?php //echo number_format(array_sum($ventasOld["Ben"]),2,',','.') ?></td>
		                        </tr>
		                        <thead>
		                            <th colspan="7" class="comparativa bg-primary text-center text-white" >Comparativa de la temporada <?php //echo $inicio->copy()->subYear()->format('Y') ?>-<?php //echo $inicio->copy()->format('Y') ?> </th>
		                        </thead>
		                        <tr>
		                            <th class="text-center  bg-primary text-white"></th>
		                            <th class="text-center  bg-primary text-white">Nov/Dic</th>
		                            <th class="text-center  bg-primary text-white">Ene</th>
		                            <th class="text-center  bg-primary text-white">Feb</th>
		                            <th class="text-center  bg-primary text-white">Mar</th>
		                            <th class="text-center  bg-primary text-white">Abr/May</th>
		                            <th class="text-center  bg-primary text-white">Total&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
		                        </tr>
		                        <tr>
		                            <th class="text-center">Comp. Ventas</th>
		                            <td class="text-center">
		                                <?php //echo number_format($ventas["Ventas"][12]-$ventasOld["Ventas"][12],2,',','.');
		                                    //echo ($ventas["Ventas"][12]-$ventasOld["Ventas"][12] > 0) ? "<i class='fa fa-arrow-up' aria-hidden='true'></i>" : "<i class='fa fa-arrow-down' aria-hidden='true'></i>";
		                                     ?>
		                            </td>
		                            <td class="text-center">
		                                <?php //echo number_format($ventas["Ventas"][1]-$ventasOld["Ventas"][1],2,',','.');
		                                    //echo ($ventas["Ventas"][1]-$ventasOld["Ventas"][1] > 0) ? "<i class='fa fa-arrow-up' aria-hidden='true'></i>" : "<i class='fa fa-arrow-down' aria-hidden='true'></i>";
		                                     ?>
		                            </td>
		                            <td class="text-center">
		                                <?php //echo number_format($ventas["Ventas"][2]-$ventasOld["Ventas"][2],2,',','.');
		                                    //echo ($ventas["Ventas"][2]-$ventasOld["Ventas"][2] > 0) ? "<i class='fa fa-arrow-up' aria-hidden='true'></i>" : "<i class='fa fa-arrow-down' aria-hidden='true'></i>";
		                                     ?>
		                            </td>
		                            <td class="text-center">
		                                <?php //echo number_format($ventas["Ventas"][3]-$ventasOld["Ventas"][3],2,',','.');
		                                    //echo ($ventas["Ventas"][3]-$ventasOld["Ventas"][3] > 0) ? "<i class='fa fa-arrow-up' aria-hidden='true'></i>" : "<i class='fa fa-arrow-down' aria-hidden='true'></i>";
		                                     ?>
		                            </td>
		                            <td class="text-center">
		                                <?php //echo number_format($ventas["Ventas"][4]-$ventasOld["Ventas"][4],2,',','.');
		                                    //echo ($ventas["Ventas"][4]-$ventasOld["Ventas"][4] > 0) ? "<i class='fa fa-arrow-up' aria-hidden='true'></i>" : "<i class='fa fa-arrow-down' aria-hidden='true'></i>";
		                                     ?>
		                            </td>
		                            <td class="text-center">
		                                <?php //echo number_format(array_sum($ventas["Ventas"])-array_sum($ventasOld["Ventas"]),2,',','.');
		                                    //echo (array_sum($ventas["Ventas"])-array_sum($ventasOld["Ventas"]) > 0) ? "<i class='fa fa-arrow-up' aria-hidden='true'></i>" : "<i class='fa fa-arrow-down' aria-hidden='true'></i>";
		                                     ?>
		                            </td>
		                        </tr>
		                        <tr>
		                            <th class="text-center">Comp. Benº</th>
		                            <td class="text-center">
		                                <?php //echo number_format($ventas["Ben"][12]-$ventasOld["Ben"][12],2,',','.');
		                                    //echo ($ventas["Ben"][12]-$ventasOld["Ben"][12] > 0) ? "<i class='fa fa-arrow-up' aria-hidden='true'></i>" : "<i class='fa fa-arrow-down' aria-hidden='true'></i>";
		                                     ?>
		                            </td>
		                            <td class="text-center">
		                                <?php //echo number_format($ventas["Ben"][1]-$ventasOld["Ben"][1],2,',','.');
		                                    //echo ($ventas["Ben"][1]-$ventasOld["Ben"][1] > 0) ? "<i class='fa fa-arrow-up' aria-hidden='true'></i>" : "<i class='fa fa-arrow-down' aria-hidden='true'></i>";
		                                     ?>
		                            </td>
		                            <td class="text-center">
		                                <?php //echo number_format($ventas["Ben"][2]-$ventasOld["Ben"][2],2,',','.');
		                                    //echo ($ventas["Ben"][2]-$ventasOld["Ben"][2] > 0) ? "<i class='fa fa-arrow-up' aria-hidden='true'></i>" : "<i class='fa fa-arrow-down' aria-hidden='true'></i>";
		                                     ?>
		                            </td>
		                            <td class="text-center">
		                                <?php //echo number_format($ventas["Ben"][3]-$ventasOld["Ben"][3],2,',','.');
		                                    //echo ($ventas["Ben"][3]-$ventasOld["Ben"][3] > 0) ? "<i class='fa fa-arrow-up' aria-hidden='true'></i>" : "<i class='fa fa-arrow-down' aria-hidden='true'></i>";
		                                     ?>
		                            </td>
		                            <td class="text-center">
		                                <?php //echo number_format($ventas["Ben"][4]-$ventasOld["Ben"][4],2,',','.');
		                                    //echo ($ventas["Ben"][4]-$ventasOld["Ben"][4] > 0) ? "<i class='fa fa-arrow-up' aria-hidden='true'></i>" : "<i class='fa fa-arrow-down' aria-hidden='true'></i>";
		                                     ?>
		                            </td>
		                            <td class="text-center">
		                                <?php //echo number_format(array_sum($ventas["Ben"])-array_sum($ventasOld["Ben"]),2,',','.');
		                                    //echo (array_sum($ventas["Ben"])-array_sum($ventasOld["Ben"]) > 0) ? "<i class='fa fa-arrow-up' aria-hidden='true'></i>" : "<i class='fa fa-arrow-down' aria-hidden='true'></i>";
		                                     ?>
		                            </td>
		                        </tr>
		                    </tbody>
		                </table>
		            </div>
		        </div>
		    </div> -->
		    <!-- Seccion Estadistica -->
	<?php endif ?>

</div>
@endsection

@section('scripts')

<?php if (isset($jesusito)): ?>
	    <script type="text/javascript">
	      google.charts.load('current', {'packages':['corechart','line']});
	      google.charts.setOnLoadCallback(drawChart);

	      function drawChart() {
	        var data = google.visualization.arrayToDataTable([
	          // ['Mes', 'Sales', 'Expenses'],
	          <?php echo $leyenda;?>
	   			
				['Noviembre',2,5,7],
	        ]);

	        var options = {
	          title: 'Company Performance',
	          curveType: 'function',
	          legend: { position: 'bottom' }
	        };

	        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

	        chart.draw(data, options);
	      }
	    </script>
		<script type="text/javascript">  
		    new Chart(document.getElementById("pie-ing"), {
		        type: 'pie',
		        data: {
		          labels: ["Pend", "Cob"],
		          datasets: [{
		            backgroundColor: ["#99BCE7", "#295d9b"],
		            data: [<?php echo ($arrayTotales[$inicio->copy()->subYear()->format('Y')]-$paymentSeason["total"]) ;?>,<?php echo $paymentSeason["total"]?>]
		          }]
		        },
		        options: {
		          title: {
		            display: true,
		            text: 'Ingresos Temporada <?php echo $inicio->copy()->format('Y') ?>-<?php echo $inicio->copy()->addYear()->format('Y') ?>'
		          }
		        }
		    });

		    new Chart(document.getElementById("pie-cob"), {
		        type: 'pie',
		        data: {
		          labels: ["Banco", "Cash"],
		          datasets: [{
		            backgroundColor: ["#2dcdaf", "#0d967b"],
		            data: [<?php echo ($paymentSeason["banco"]) ;?>,<?php echo $paymentSeason["cash"]?>]
		          }]
		        },
		        options: {
		          title: {
		            display: true,
		            text: 'Cobros Temporada <?php echo $inicio->copy()->format('Y') ?>-<?php echo $inicio->copy()->addYear()->format('Y') ?>'
		          }
		        }
		    });

		    var data = {
		        labels: [
		                    <?php foreach ($arrayTotales as $key => $value): ?>
		                        <?php echo "'".$key."'," ?>
		                    <?php endforeach ?>2018,2019
		                ],
		        datasets: [
		            {
		                label: "Ingresos por Año",
		                backgroundColor: [
		                     'rgba(54, 162, 235, 0.2)',
		                ],
		                borderColor: [
		                    'rgba(54, 162, 235, 1)',
		                ],
		                borderWidth: 1,
		                data: [
		                        <?php foreach ($arrayTotales as $key => $value): ?>
		                            <?php echo "'".$value."'," ?>
		                        <?php endforeach ?>
		                        ],
		            }
		        ]
		    };

		    var myBarChart = new Chart('barChart', {
		        type: 'line',
		        data: data,
		    });

		</script>
<?php endif ?>

@endsection