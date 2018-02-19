
<style>
    .table-ingresos , .table-ingresos >tbody> tr > td{
        background-color: #92B6E2!important;
        margin: 0px ;
        padding: 5px 8px;
    }
    .table-cobros , .table-cobros >tbody> tr > td{
        background-color: #38C8A7!important;
        margin: 0px ;
        padding: 5px 8px;
    }
    .tr-cobros:hover{
        background-color: #2ca085!important;
    }
    .tr-cobros:hover td {
        background-color: #2ca085!important;
    }
    .fa-arrow-up{
        color: green;
    }
    .fa-arrow-down{
        color: red;
    }
    .bg-complete-grey{
        background-color: #92B6E2!important;
    }
</style>

<?php $dataStats = \App\http\Controllers\LiquidacionController::getSalesByYear($inicio->copy()->format('Y')); ?>
<div class="col-md-3">
	
	<table class="table table-hover table-striped table-ingresos" style="background-color: #92B6E2">
		<thead class="bg-complete" style="background: #d3e8f7">
			<th colspan="2" class="text-black text-center"> Ingresos Temporada</th>
		</thead>
		<tbody>
			<tr>
				<td class="" style="padding: 5px 8px!important; background-color: #d3e8f7!important;"><b>VENTAS TEMPORADA</b></td>
				<td class=" text-center" style="padding: 5px 8px!important; background-color: #d3e8f7!important;">
					<b><?php echo number_format( round($dataStats['ventas']),0,',','.')?> €</b>
				</td>
			</tr>
			<tr style="background-color: #38C8A7;">
				<td class="text-white" style="padding: 5px 8px!important;background-color: #38C8A7!important;">
					Cobrado Temporada
				</td>
				<td class="text-white text-center" style="padding: 5px 8px!important;background-color: #38C8A7!important;">
					<?php echo number_format( round($dataStats['cobrado']),0,',','.')?> € 
				</td>
			</tr>
			<tr style="background-color: #8e5ea2;">
				<td class="text-white" style="padding: 5px 8px!important;background-color: #8e5ea2!important;">Pendiente Cobro</td>
				<td class="text-white text-center" style="padding: 5px 8px!important;background-color: #8e5ea2!important;">
					<?php echo number_format( round($dataStats['pendiente']),0,',','.') ?> €
				</td>
			</tr>
		</tbody>
	</table>

	<div>
		<canvas id="pieIng" style="width: 100%; height: 250px;"></canvas>
	</div>
</div>

<div class="col-md-3">

	<table class="table table-hover table-striped table-cobros" style="background-color: #38C8A7">
		<thead style="background-color: #38C8A7">
			<th colspan="2" class="text-white text-center">Cobros Temporada</th>
		</thead>
		<tbody style="background-color: #38C8A7">
			<tr class="tr-cobros">
				<th class="text-white" style="padding: 5px 8px!important;background-color: #38C8A7!important;">TOTAL COBRADO</th>
				<th class="text-white text-center" style="padding: 5px 8px!important;background-color: #38C8A7!important;"><?php echo number_format( round($dataStats['cobrado']),0,',','.')?> €
				</th>
			</tr>
			<tr class="tr-cobros">
				<td class="text-white" style="padding: 5px 8px!important; background-color: #2ba840!important;">Metalico</td>
				<td class="text-white text-center" style="padding: 5px 8px!important; background-color: #2ba840!important;">
					<?php echo number_format( round($dataStats['metalico']),0,',','.')?> €
				</td>
			</tr>
			<tr class="tr-cobros">
				<td class="text-white" style="padding: 5px 8px!important;background-color: #2ca085!important;">Banco</td>
				<td class="text-white text-center" style="padding: 5px 8px!important;background-color: #2ca085!important;">
					<?php echo number_format( round($dataStats['banco']),0,',','.')?> €
				</td>
			</tr>
			
		</tbody>
	</table>

	<div>
		<canvas id="pieCobros" style="width: 100%; height: 250px;"></canvas>
	</div>
</div>

<div class="col-md-6">
	<div class="row ">
		<?php $oldTotalPVP = 0; ?>
		<?php $arrayColors = [ 1 => 'bg-info', 2 => 'bg-complete', 3 => 'bg-primary', ]; ?>
		<?php $lastThreeSeason = $inicio->copy()->subYears(2) ?>
		<?php for ($i=1; $i < 4; $i++): ?>
			<div class="col-md-4 m-b-10">
			
				<div class="widget-9 no-border <?php echo $arrayColors[$i] ?> no-margin widget-loader-bar">
					<div class="full-height d-flex flex-column">

						<div class="p-l-20" style="padding: 10px 20px;">
							<h5 class="no-margin p-b-5 text-white ">
								Temp <b><?php echo $lastThreeSeason->copy()->format('y'); ?>-<?php echo $lastThreeSeason->copy()->addYear()->format('y'); ?></b>
							</h5>
							<?php $totalPVP = \App\Rooms::getPvpByYear($lastThreeSeason->copy()->format('Y')); ?>
							<h3 class="no-margin p-b-5 text-white">
								<?php echo number_format( $totalPVP, 0, ',', '.'); ?>€ 
								<span style="font-size: 14px;">
									<?php if ($i > 1): ?>
										<?php if ($totalPVP > $oldTotalPVP): ?>
											<i class="fa fa-arrow-up text-success fa-2x"></i>
										<?php else: ?>
											<i class="fa fa-arrow-down text-danger fa-2x"></i>

										<?php endif ?>
									<?php endif ?>
								</span>
							</h3>
						</div>
					</div>
				</div>

			</div>
			<?php $oldTotalPVP = $totalPVP; ?>
			<?php $lastThreeSeason->addYear(); ?>
		<?php endfor; ?>
		
    </div>
	    
</div>  


<script type="text/javascript">
	
    new Chart(document.getElementById("pieIng"), {
        type: 'pie',
        data: {
         labels: ["Cobrado", "Pendiente",],
          datasets: [{
            label: "Population (millions)",
            backgroundColor: ["#38C8A7", "#8e5ea2"],
            data: [

            	//Comprobamos si existen cobros
            	<?php echo round($dataStats['cobrado']) ?>, 
            	<?php echo round($dataStats['pendiente']) ?>, 
            	
                ]
          }]
        },
        options: {
          title: {
            display: true,
            text: 'Ingresos de la temporada <?php echo $fecha->copy()->subYear()->format('Y');?> - <?php echo $fecha->copy()->format('Y');?>'
          }
        }
    });

    new Chart(document.getElementById("pieCobros"), {
        type: 'pie',
        data: {
         labels: ["Metalico", "Banco",],
          datasets: [{
            backgroundColor: ["#2ba840", "#2ca085"],
            data: [
            	//Comprobamos si existen cobros
            	<?php echo round($dataStats['metalico']) ?>, 
            	<?php echo round($dataStats['banco']) ?>, 
                ]
          }]
        },
        options: {
          title: {
            display: true,
            text: 'Cobros de la temporada <?php echo $fecha->copy()->subYear()->format('Y');?> - <?php echo $fecha->copy()->format('Y');?>'
          }
        }
    });

</script>
