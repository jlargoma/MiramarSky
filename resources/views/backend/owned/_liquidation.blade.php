<?php use \Carbon\Carbon;  setlocale(LC_TIME, "ES"); setlocale(LC_TIME, "es_ES"); ?>
<div class="col-md-6 bg-complete push-20">
	<div class="col-md-8">
		<h5 class="text-center white">Total ingresos propietario</h5>
	</div>
	<div class="col-md-4 text-center text-white">
		<h5 class="text-center white"><strong><?php echo number_format($total,2,',','.'); ?>€</strong></h5>
	</div>
</div>
<div class="row push-20">
	<?php if (count($pagos)> 0): ?>
		<?php foreach ($pagos as $pago): ?>
			<div class="col-md-12 ">
				<div class="col-md-3 not-padding" >
					<div class="col-xs-12  bg-complete push-0">
						<h5 class="text-left white">
							Fecha de pago
						</h5>
					</div>
					<div class="col-xs-12  push-20">
						<h5 class="text-right"><?php echo Carbon::createFromFormat('Y-m-d',$pago->datePayment)->format('d-m-Y')?></h5>
					</div>
				</div>
				<div class="col-md-3 not-padding" >
					<div class="col-xs-12  bg-complete push-0">
						<h5 class="text-left white">
							Importe
						</h5>
					</div>
					<div class="col-xs-12 push-20">
						<h5 class="text-left"><?php echo number_format($pago->import,2,',','.') ?>€</h5>
					</div>
				</div>
				<div class="col-md-3 not-padding" >
					<div class="col-xs-12   bg-complete push-0">
						<h5 class="text-left white">
							Observaciones
						</h5>
					</div>
					<div class="col-xs-12  push-20">
						<h5 class="text-left"><?php echo $pago->comment ?></h5>
					</div>
				</div>
				<div class="col-md-3 not-padding">
					<div class="col-xs-12   bg-complete push-0">
						<h5 class="text-left white">
							Pendiente
						</h5>
					</div>
					<div class="col-xs-12  push-20" style="border-right: 1px solid black; border-left: 1px solid black; ">
						<h5 class="text-left"><?php echo number_format($total-$pagototal,2,',','.'); ?>€</h5>
					</div>
				</div>
			</div>
		<?php endforeach ?>
	<?php else: ?>
		<div class="col-md-12 text-center">
			Aun no hay pagos realizados
		</div>
	<?php endif ?>
			
</div>