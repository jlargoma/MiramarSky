<?php   use \Carbon\Carbon;  
        setlocale(LC_TIME, "ES"); 
        setlocale(LC_TIME, "es_ES"); 
?>
@extends('layouts.admin-master')

@section('title') Perdidas y ganancias  @endsection

@section('externalScripts') 
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.js"></script>
@endsection

@section('content')
<div class="container padding-5 sm-padding-10">
	<div class="row bg-white">
		<div class="col-md-12 col-xs-12">

			<div class="col-lg-4 col-lg-offset-3 col-md-6 col-md-offset-3 col-xs-12">
				<h2 class="text-center">
					PERDIDAS Y GANANCIAS
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
		<div class="col-lg-3 col-md-4 col-xs-12">
		   <div>
		       <canvas id="barChart" style="width: 100%; height: 250px;"></canvas>
		   </div>
		</div>
		<?php $totalYearIncomes = 0; ?>
		<?php $totalYearExpenses = 0; ?>
		<?php $init = Carbon::createFromFormat('Y-m-d', $year->start_date) ?>
			<?php for($i = 1 ; $i <= $diff; $i++): ?>

				<?php 
					$totalMonthIncomes = 	
									$arrayTotales['meses'][$init->copy()->format('n')] + 
    								$arrayIncomes['INGRESOS EXTRAORDINARIOS'][$init->copy()->format('n')] + 
    								$arrayIncomes['RAPPEL CLOSES'][$init->copy()->format('n')] + 
    								$arrayIncomes['RAPPEL FORFAITS'][$init->copy()->format('n')] + 
    								$arrayIncomes['RAPPEL ALQUILER MATERIAL'][$init->copy()->format('n')];
					
					$totalYearIncomes += $totalMonthIncomes;
					$totalMonthExpenses = 
									$arrayExpenses['PAGO PROPIETARIO'][$init->copy()->format('n')] +
									$arrayExpenses['SERVICIOS PROF INDEPENDIENTES'][$init->copy()->format('n')] +
									$arrayExpenses['VARIOS'][$init->copy()->format('n')] +
									$arrayExpenses['REGALO BIENVENIDA'][$init->copy()->format('n')] +
									$arrayExpenses['LAVANDERIA'][$init->copy()->format('n')] +
									$arrayExpenses['LIMPIEZA'][$init->copy()->format('n')] +
									$arrayExpenses['EQUIPAMIENTO VIVIENDA'][$init->copy()->format('n')] +
									$arrayExpenses['DECORACION'][$init->copy()->format('n')] +
									$arrayExpenses['MENAJE'][$init->copy()->format('n')] +
									$arrayExpenses['SABANAS Y TOALLAS'][$init->copy()->format('n')] +
									$arrayExpenses['IMPUESTOS'][$init->copy()->format('n')] +
									$arrayExpenses['GASTOS BANCARIOS'][$init->copy()->format('n')] +
									$arrayExpenses['MARKETING Y PUBLICIDAD'][$init->copy()->format('n')] +
									$arrayExpenses['REPARACION Y CONSERVACION'][$init->copy()->format('n')] +
									$arrayExpenses['SUELDOS Y SALARIOS'][$init->copy()->format('n')] +
									$arrayExpenses['SEG SOCIALES'][$init->copy()->format('n')] +
									$arrayExpenses['MENSAJERIA'][$init->copy()->format('n')] +
									$arrayExpenses['COMISIONES COMERCIALES'][$init->copy()->format('n')] ;
					
					$totalYearExpenses += $totalMonthExpenses; ?>

				<?php $init->addMonths(1); ?>
		<?php endfor; ?>
		<div class="col-lg-6 col-md-8 col-xs-12">
		   <div class="col-md-12 col-xs-12">
		   		<div class="col-md-4 m-b-10">
		   			<div class="widget-9 no-border bg-success no-margin widget-loader-bar" style="background-color: #46c37b!important;">
		   				<div class="full-height d-flex flex-column">

		   					<div class="p-l-20" style="padding: 10px 20px;">
		   						<h5 class="no-margin p-b-5 text-white ">
		   							<b>INGRESOS</b>
		   						</h5>
		   						
		   						<h3 class="no-margin p-b-5 text-white font-w600">
									
									<?php if ($totalYearIncomes > 0): ?>
										<?php echo number_format($totalYearIncomes, 0, ',', '.') ?> €
									<?php else: ?>
										---
									<?php endif ?>
		   						</h3>
		   					</div>
		   				</div>
		   			</div>
		   		</div>

		   		<div class="col-md-4 m-b-10">
		   			<div class="widget-9 no-border bg-danger no-margin widget-loader-bar" style="background-color: #a94442!important;">
		   				<div class="full-height d-flex flex-column">

		   					<div class="p-l-20" style="padding: 10px 20px;">
		   						<h5 class="no-margin p-b-5 text-white ">
		   							<b>GASTOS</b>
		   						</h5>
		   						
		   						<h3 class="no-margin p-b-5 text-white font-w600">
		   							<?php 
		   									$totalExpensesPending = array_sum($arrayExpensesPending['PAGO PROPIETARIO']) +  
																	array_sum($arrayExpensesPending['AGENCIAS']) + 
																	array_sum($arrayExpensesPending['STRIPE']) + 
																	array_sum($arrayExpensesPending['LIMPIEZA']) + 
																	array_sum($arrayExpensesPending['LAVANDERIA']);

											$totalYearExpenses = $totalYearExpenses + ($totalExpensesPending - $totalYearExpenses)

		   							?>
		   							<?php if ($totalYearExpenses > 0): ?>
										<?php echo number_format(($totalYearExpenses), 0, ',', '.') ?> €
									<?php else: ?>
										---
									<?php endif ?>
		   						</h3>
		   					</div>
		   				</div>
		   			</div>
		   		</div>
		   		<div class="col-md-4 m-b-10">
		   			<div class="widget-9 no-border bg-complete no-margin widget-loader-bar">
		   				<div class="full-height d-flex flex-column">

		   					<div class="p-l-20" style="padding: 10px 20px;">
		   						<h5 class="no-margin p-b-5 text-white ">
		   							<b>RESULTADO</b>
		   						</h5>
		   						
		   						<h3 class="no-margin text-white font-w600">
		   							<?php if (($totalYearIncomes - $totalYearExpenses) > 0): ?>
										<?php echo number_format(($totalYearIncomes - $totalYearExpenses), 0, ',', '.') ?> €
									<?php else: ?>
										---
									<?php endif ?>

									<?php if (($totalYearIncomes - $totalYearExpenses) > 0 ): ?>
                                                                                <i class="fa fa-arrow-up text-success result"></i>
									<?php else: ?>
										<i class="fa fa-arrow-down text-danger result"></i>
									<?php endif ?>
		   							
		   						</h3>
		   					</div>
		   				</div>
		   			</div>

		   		</div>
		   </div>
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

	$('#fecha').change(function(event) {
	    
	    var year = $(this).val();
	    window.location = '/admin/perdidas-ganancias/'+year;

	});


	/* GRAFICA INGRESOS/GASTOS */
	var data = {
	    labels: [
	    			<?php $init = Carbon::createFromFormat('Y-m-d', $year->start_date); ?>
					<?php for($i = 1 ; $i <= $diff; $i++): ?>
						<?php if ($i == $diff): ?>
							"<?php echo substr(ucfirst($init->formatlocalized('%B')), 0, 3); ?>"
						<?php else: ?>
							"<?php echo substr(ucfirst($init->formatlocalized('%B')), 0, 3); ?>",
						<?php endif; ?>
						<?php $init->addMonths(1); ?>
					<?php endfor; ?>
	    			],
	    datasets: [
	        {
	            label: "Ingresos",
	            backgroundColor: [
	                'rgba(67, 160, 71, 0.3)',
	                'rgba(67, 160, 71, 0.3)',
	                'rgba(67, 160, 71, 0.3)',
	                'rgba(67, 160, 71, 0.3)',
	                'rgba(67, 160, 71, 0.3)',
	                'rgba(67, 160, 71, 0.3)',
	                'rgba(67, 160, 71, 0.3)',
	                'rgba(67, 160, 71, 0.3)',
	                'rgba(67, 160, 71, 0.3)',
	                'rgba(67, 160, 71, 0.3)',
	                'rgba(67, 160, 71, 0.3)',
	                'rgba(67, 160, 71, 0.3)',
	            ],
	            borderColor: [
	                'rgba(67, 160, 71, 1)',
	                'rgba(67, 160, 71, 1)',
	                'rgba(67, 160, 71, 1)',
	                'rgba(67, 160, 71, 1)',
	                'rgba(67, 160, 71, 1)',
	                'rgba(67, 160, 71, 1)',
	                'rgba(67, 160, 71, 1)',
	                'rgba(67, 160, 71, 1)',
	                'rgba(67, 160, 71, 1)',
	                'rgba(67, 160, 71, 1)',
	                'rgba(67, 160, 71, 1)',
	                'rgba(67, 160, 71, 1)',
	            ],
	            borderWidth: 1,
	            data: [

							<?php $init = Carbon::createFromFormat('Y-m-d', $year->start_date); ?>
							<?php for($i = 1 ; $i <= $diff; $i++): ?>
							<?php 
	            				$totalMonth = 	$arrayTotales['meses'][$init->copy()->format('n')] + 
	            								$arrayIncomes['INGRESOS EXTRAORDINARIOS'][$init->copy()->format('n')] + 
	            								$arrayIncomes['RAPPEL CLOSES'][$init->copy()->format('n')] + 
	            								$arrayIncomes['RAPPEL FORFAITS'][$init->copy()->format('n')] + 
	            								$arrayIncomes['RAPPEL ALQUILER MATERIAL'][$init->copy()->format('n')];
	            			?>
            				<?php if ($i == $diff): ?>
            					<?php echo $totalMonth; ?>
            				<?php else: ?>
            					<?php echo $totalMonth; ?>,
            				<?php endif ?>
							<?php $init->addMonths(1); ?>
						<?php endfor; ?>

	            	],
	        },
	        {
	            label: "Gastos",
	            backgroundColor: [
	                'rgba(229, 57, 53, 0.3)',
	                'rgba(229, 57, 53, 0.3)',
	                'rgba(229, 57, 53, 0.3)',
	                'rgba(229, 57, 53, 0.3)',
	                'rgba(229, 57, 53, 0.3)',
	                'rgba(229, 57, 53, 0.3)',
	                'rgba(229, 57, 53, 0.3)',
	                'rgba(229, 57, 53, 0.3)',
	                'rgba(229, 57, 53, 0.3)',
	                'rgba(229, 57, 53, 0.3)',
	                'rgba(229, 57, 53, 0.3)',
	                'rgba(229, 57, 53, 0.3)',
	            ],
	            borderColor: [
	                'rgba(229, 57, 53, 1)',
	                'rgba(229, 57, 53, 1)',
	                'rgba(229, 57, 53, 1)',
	                'rgba(229, 57, 53, 1)',
	                'rgba(229, 57, 53, 1)',
	                'rgba(229, 57, 53, 1)',
	                'rgba(229, 57, 53, 1)',
	                'rgba(229, 57, 53, 1)',
	                'rgba(229, 57, 53, 1)',
	                'rgba(229, 57, 53, 1)',
	                'rgba(229, 57, 53, 1)',
	                'rgba(229, 57, 53, 1)',
	            ],
	            borderWidth: 1,
	            data: [
					<?php $init = Carbon::createFromFormat('Y-m-d', $year->start_date); ?>
					<?php for($i = 1 ; $i <= $diff; $i++): ?>
            			<?php 
            				$totalMonth = 
            								$arrayExpenses['PAGO PROPIETARIO'][$init->copy()->format('n')] +
											$arrayExpenses['SERVICIOS PROF INDEPENDIENTES'][$init->copy()->format('n')] +
											$arrayExpenses['VARIOS'][$init->copy()->format('n')] +
											$arrayExpenses['REGALO BIENVENIDA'][$init->copy()->format('n')] +
											$arrayExpenses['LAVANDERIA'][$init->copy()->format('n')] +
											$arrayExpenses['LIMPIEZA'][$init->copy()->format('n')] +
											$arrayExpenses['EQUIPAMIENTO VIVIENDA'][$init->copy()->format('n')] +
											$arrayExpenses['DECORACION'][$init->copy()->format('n')] +
											$arrayExpenses['MENAJE'][$init->copy()->format('n')] +
											$arrayExpenses['SABANAS Y TOALLAS'][$init->copy()->format('n')] +
											$arrayExpenses['IMPUESTOS'][$init->copy()->format('n')] +
											$arrayExpenses['GASTOS BANCARIOS'][$init->copy()->format('n')] +
											$arrayExpenses['MARKETING Y PUBLICIDAD'][$init->copy()->format('n')] +
											$arrayExpenses['REPARACION Y CONSERVACION'][$init->copy()->format('n')] +
											$arrayExpenses['SUELDOS Y SALARIOS'][$init->copy()->format('n')] +
											$arrayExpenses['SEG SOCIALES'][$init->copy()->format('n')] +
											$arrayExpenses['MENSAJERIA'][$init->copy()->format('n')] +
											$arrayExpenses['COMISIONES COMERCIALES'][$init->copy()->format('n')] ;
            			?>
        				<?php if ($i == $diff): ?>
        					<?php echo abs($totalMonth); ?>
        				<?php else: ?>
        					<?php echo abs($totalMonth); ?>,
        				<?php endif ?>
							<?php $init->addMonths(1); ?>

            		<?php endfor; ?>
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
</style>
@endsection