<?php setlocale(LC_TIME, "ES"); ?>
<?php setlocale(LC_TIME, "es_ES"); ?>
<?php use \Carbon\Carbon; ?>
<table class="table table-bordered table-striped table-header-bg no-footer">
	<thead>
		<tr>
			<th class="text-center bg-complete text-white">#</th>
			<th class="text-center bg-complete text-white">Fecha</th>
			<th class="text-center bg-complete text-white">Concepto</th>
			<th class="text-center bg-complete text-white">Debe</th>
			<th class="text-center bg-complete text-white">Haber</th>
			<th class="text-center bg-complete text-white">Saldo</th>
			<th class="text-center bg-complete text-white">Comentario</th>
		</tr>
	</thead>	
	<tbody>
		<?php $total = 0;//$saldoInicial->import; ?>
		<?php foreach ($cashbox as $key => $cash): ?>
			
			<tr>
				<td class="text-center" style="padding: 8px 5px!important">
					<?php echo $key+1 ?>
				</td>
				<td class="text-center" style="padding: 8px 5px!important">
					<?php $date = Carbon::createFromFormat('Y-m-d', $cash->date); ?>
					<b><?php echo strtoupper($date->format('d-m-Y')); ?></b>
				</td>
				<td class="text-center" style="padding: 8px 5px!important">
					<?php echo $cash->concept; ?>
				</td>

				
				
				<td class="text-center" style="padding: 8px 5px!important">
					<?php if ($cash->type == 1): ?>
						<b class="text-danger">-<?php echo number_format($cash->import,2,',','.'); ?> €</b>
						<?php $total -= $cash->import ?>
					<?php endif ?>
					
				</td>
				<td class="text-center" style="padding: 8px 5px!important">
					<?php if ($cash->type == 0): ?>
						<b class="text-success">+<?php echo number_format($cash->import,2,',','.'); ?> €</b>
						<?php $total += $cash->import ?>
					<?php endif ?>
					
				</td>
				<td class="text-center">
					<b><?php echo number_format($total,2,',','.'); ?> €</b>
				</td>
				
				<td class="text-center" style="padding: 8px 5px!important">
					<?php echo $cash->comment ?>
				</td>
				
				
			</tr>
		<?php endforeach ?>
	</tbody>			
</table>