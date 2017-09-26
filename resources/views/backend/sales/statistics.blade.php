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
</div>
@endsection

@section('scripts')

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
@endsection