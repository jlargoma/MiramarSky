<?php use \Carbon\Carbon;  setlocale(LC_TIME, "ES"); setlocale(LC_TIME, "es_ES"); ?>
<?php if (!$mobile->isMobile()): ?>

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
			<div class="col-md-12 ">
				<div class="col-md-3 not-padding" >
					<div class="col-xs-12  bg-complete push-0">
						<h5 class="text-left white">
							Fecha de pago
						</h5>
					</div>
				</div>
				<div class="col-md-3 not-padding" >
					<div class="col-xs-12  bg-complete push-0">
						<h5 class="text-left white">
							Importe
						</h5>
					</div>
				</div>
				<div class="col-md-3 not-padding" >
					<div class="col-xs-12   bg-complete push-0">
						<h5 class="text-left white">
							Observaciones
						</h5>
					</div>
				</div>
				<div class="col-md-3 not-padding">
					<div class="col-xs-12   bg-complete push-0">
						<h5 class="text-left white">
							Pendiente
						</h5>
					</div>
				</div>
			</div>
			<?php foreach ($pagos as $pago): ?>
				<div class="col-md-12 ">
					<div class="col-md-3 not-padding" >
						<div class="col-xs-12  push-20">
							<h5 class="text-left"><?php echo Carbon::createFromFormat('Y-m-d',$pago->date)->format('d-m-Y')?></h5>
						</div>
					</div>
					<div class="col-md-3 not-padding" >
						<div class="col-xs-12 push-20">
							<h5 class="text-left"><?php echo number_format($pago->import,2,',','.') ?>€</h5>
						</div>
					</div>
					<div class="col-md-3 not-padding" >
						<div class="col-xs-12  push-20">
							<h5 class="text-left"><?php echo $pago->comment ?></h5>
						</div>
					</div>
					<div class="col-md-3 not-padding">
						<div class="col-xs-12  push-20" style="">
							<h5 class="text-left"><?php echo number_format($total - $pagototal,2,',','.'); ?>€</h5>
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
<?php else: ?>
	<div class="row bg-complete push-20">
		<div class="col-xs-12">
			<div class="col-xs-6">
				<h5 class="text-center white">Total ingresos</h5>
			</div>
			<div class="col-xs-6 text-center text-white">
				<h5 class="text-center white"><strong><?php echo number_format($total,2,',','.'); ?>€</strong></h5>
			</div>
		</div>
	</div>
	<div class="row">

		<?php if (count($pagos)> 0): ?>
			<table class="table table-hover no-footer" id="basicTable" role="grid">
				<thead>
					
					<th class="bg-complete text-white text-center"><i class="fa fa-calendar" aria-hidden="true"></i></th>
					<th class="bg-complete text-white text-center"><i class="fa fa-money" aria-hidden="true"></i></th>
					<th class="bg-complete text-white text-center">Tipo</th>
					<th class="bg-complete text-white text-center">Pend</th>
				</thead>
				<tbody>

					<?php foreach ($pagos as $pago): ?>
					<tr>

						<td class="text-center"  style="padding: 8px!important">
							<?php $date = Carbon::createFromFormat('Y-m-d',$pago->date) ?>
							<?php echo $date->format('d')?>-<?php echo $date->format('M')?>-<?php echo $date->format('y')?>
						</td>
						<td class="text-center" style="padding: 8px!important">
							<b><?php echo number_format($pagototal,2,',','.') ?>€</b>
						</td>
						<td class="text-center" style="padding: 8px!important">
							<?php echo \App\Book::getTypeCobro($pago->type) ?>
						</td>

						<td class="text-center" style="padding: 8px!important">
							<?php echo number_format($total-$pagototal,2,',','.'); ?>€
						</td>						
						
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		<?php else: ?>
			<div class="col-md-12 text-center">
				Aun no hay pagos realizados
			</div>
		<?php endif ?>
	</div>
	
<?php endif; ?>