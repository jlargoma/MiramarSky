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

@endsection