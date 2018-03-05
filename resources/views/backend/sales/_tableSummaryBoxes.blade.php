<?php 
	use \App\Classes\Mobile;
	$mobile = new Mobile();
?>
<style type="text/css">
	.bordered{
		padding: 15px;
		border:1px solid #e8e8e8;
		background: white;
	}
</style>
<?php $dataSales = \App\Http\Controllers\LiquidacionController::getSalesByYear($temporada->copy()->format('Y')); ?>
<?php if ( !$mobile->isMobile() ): ?>
<div class="row">
	<div class="col-md-3">
		<div class="col-md-6 bordered">
			<div class="card-title text-black hint-text">
				Total Reservas
			</div>
			<div class="p-l-20">
				<h3 class="text-black font-w400 text-center"><?php echo count($books) ?></h3>
			</div>
		</div>
		<div class="col-md-6 bordered">
			<div class="card-title text-black hint-text">
				Nº Inquilinos
			</div>
			<div class="p-l-20">
				<h3 class="text-black font-w400 text-center"><?php echo $data['num-pax'] ?></h3>
			</div>
		</div>
	</div>
	<div class="col-md-5">
		<div class="col-md-3 bordered">
			<div class="card-title text-black hint-text">
				Estancia media
			</div>
			<div class="p-l-20">
				<h3 class="text-black font-w400 text-center"><?php echo round($data['estancia-media']) ?></h3>
			</div>
		</div>
		<div class="col-md-3 bordered">
			<div class="card-title text-black hint-text">
				Total Noches.
			</div>
			<div class="p-l-20">
				<h3 class="text-black font-w400 text-center"><?php echo $data['days-ocupation'] + $data['dias-propios']  ?></h3>
				
			</div>
		</div>
		<div class="col-md-3 bordered">
			<div class="card-title text-black hint-text">
				Venta propia
			</div>
			<div class="p-l-20">
				<h3 class="text-black font-w400 text-center"><?php echo round($data['propios']) ?>%</h3>
			</div>
		</div>
		<div class="col-md-3 bordered">
			<div class="card-title text-black hint-text">
				Venta agencia
			</div>
			<div class="p-l-20">
				<h3 class="text-black font-w400 text-center"><?php echo round($data['agencia']) ?>%</h3>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="col-md-3 bordered">
			<div class="card-title text-black hint-text">
				Total vnts temp
			</div>
			<div class="p-l-20">
				<h4 class="text-black font-w400 text-center"><?php echo number_format($dataSales['ventas'],0,',','.')?>€</h4>
			</div>
		</div>
		<div class="col-md-3 bordered">
			<div class="card-title text-black hint-text">
				Ing neto reservas
			</div>
			<div class="p-l-20">
				<?php $sumTotalBenef = 0; ?>
				<?php foreach($books as $book): ?>
					<?php $sumTotalBenef += $book->total_ben; ?>
				<?php endforeach; ?>
				<h3 class="text-black font-w400 text-center"><?php echo number_format($sumTotalBenef,0,',','.')?>€</h3>
			</div>
		</div>
		<div class="col-md-3 bordered">
			<div class="card-title text-black hint-text">
				% benef reservas
			</div>
			<div class="p-l-20">
				<?php $totoalDiv = ($totales["total"] == 0)?1:$totales["total"]; ?>
				<h3 class="text-black font-w400 text-center"><?php echo number_format( ( $totales["beneficio"] / $totoalDiv )* 100 ,2 ,',','.') ?>%</h3>
			</div>
		</div>
		<div class="col-md-3 bordered">
			<div class="card-title text-black hint-text">
				Dias totales temp
			</div>
			<div class="p-l-20">
				<input class="form-control text-black font-w400 text-center seasonDays" value="<?php echo $data['total-days-season'] ?>" style="border: none; font-size: 32px;margin: 10px 0;color:red!important"/>
			</div>
		</div>
		
	</div>
</div>
<?php else: ?>

<?php endif; ?>