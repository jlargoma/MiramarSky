<table class=" table table-bordered table-striped table-header-bg">
	<thead>
		<tr>
			<th class="bg-complete text-white text-center"></th>
			<?php $x = $inicio->copy(); ?>
			<?php for($i = 1 ; $i <= 12; $i++): ?>
				<th class="bg-complete text-white text-center"><?php echo $x->formatlocalized('%B'); ?></th>
				<?php $x->addMonths(1); ?>

			<?php endfor; ?>
			<th class="bg-complete text-white text-center">Total</th>
		</tr>
	</thead >
	<tbody>
		<tr>
			<td class="text-center" style="color: #fff; background-color:#46c37b!important; border-bottom-color:#46c37b; padding: 8px 5px!important;">
				<b>INGRESOS</b>
			</td>
			<?php $totalYear = 0; ?>
			<?php $init = $inicio->copy(); ?>
			<?php for($i = 1 ; $i <= 12; $i++): ?>
				<td class="text-center" style="color: #fff; background-color:#46c37b!important; border-bottom-color:#46c37b; padding: 8px 5px!important;">
					<?php $totalMonth = 	$arrayTotales['meses'][$init->copy()->format('n')] + 
            								$arrayIncomes['INGRESOS EXTRAORDINARIOS'][$init->copy()->format('n')] + 
            								$arrayIncomes['RAPPEL CLOSES'][$init->copy()->format('n')] + 
            								$arrayIncomes['RAPPEL FORFAITS'][$init->copy()->format('n')] + 
            								$arrayIncomes['RAPPEL ALQUILER MATERIAL'][$init->copy()->format('n')];
					?>
					<b>
						<?php if ($totalMonth > 0): ?>
							<?php echo number_format($totalMonth, 0,',' , '.'); ?> €
						<?php else: ?>
							---
						<?php endif ?>
						
					</b>
					<?php $totalYear += $totalMonth; ?>
				</td>
				<?php $init->addMonths(1); ?>
			<?php endfor; ?>
			<td class="text-center" style="color: #fff; background-color: #46c37b!important; border-bottom-color:#46c37b; padding: 8px 5px!important;">
				<b>
					<?php echo number_format($totalYear,0,',','.' ); ?> €
				</b>
			</td>
		</tr>

		<?php $totalYear = 0; ?>		
		<tr>
			<td class="text-center" style="padding: 8px 5px!important;">
				<b>VENTAS TEMPORADA</b>
			</td>
			<?php $totalYear = 0; ?>
			<?php $init = $inicio->copy(); ?>
			<?php for($i = 1 ; $i <= 12; $i++): ?>
				<td class="text-center" style=" padding: 8px 5px!important;">
					
					<b>
						<?php if ($arrayTotales['meses'][$init->copy()->format('n')] > 0): ?>
							<?php echo number_format($arrayTotales['meses'][$init->copy()->format('n')], 0,',' , '.'); ?> €
						<?php else: ?>
							---
						<?php endif ?>
						
					</b>
					<?php $totalYear += $totalMonth; ?>
				</td>
				<?php $init->addMonths(1); ?>
			<?php endfor; ?>
			<td class="text-center" style="padding: 8px 5px!important;">
				<b>
					<?php echo number_format($totalYear,0,',','.' ); ?> €
				</b>
			</td>
		</tr>

		<?php foreach ($arrayIncomes as $key => $income): ?>
			<?php $totalYear = 0; ?>	
			<tr>
				<td class="text-center" style="padding: 8px 5px!important;">
					<b><?php echo $key; ?></b>
				</td>
				<?php $totalYear = 0; ?>
				<?php $init = $inicio->copy(); ?>
				<?php for($i = 1 ; $i <= 12; $i++): ?>
					<td class="text-center" style=" padding: 8px 5px!important;">
						
						<b>
							<?php if ($income[$init->copy()->format('n')] > 0): ?>
								<?php echo number_format($income[$init->copy()->format('n')], 0,',' , '.'); ?> €
							<?php else: ?>
								---
							<?php endif ?>
						</b>
						<?php $totalYear += $totalMonth; ?>
					</td>
					<?php $init->addMonths(1); ?>
				<?php endfor; ?>
				<td class="text-center" style="padding: 8px 5px!important;">
					<b>
						<?php echo number_format($totalYear,0,',','.' ); ?> €
					</b>
				</td>
			</tr>		
		<?php endforeach ?>





		
		<tr>
			<td class="text-center" style="color: #fff; background-color: #a94442!important; border-bottom-color: #a94442!important;padding: 8px 5px!important;">
				<b>GASTOS</b>
			</td>
			<?php $totalYear = 0; ?>
			<?php $init = $inicio->copy(); ?>
			<?php for($i = 1 ; $i <= 12; $i++): ?>
				<td class="text-center" style="color: #fff; background-color: #a94442!important; border-bottom-color: #a94442!important;padding: 8px 5px!important;">
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
					<b>
						<?php if ($totalMonth > 0): ?>
							<?php echo number_format($totalMonth, 0,',' , '.'); ?> €
						<?php else: ?>
							---
						<?php endif ?>
					</b>
					<?php $totalYear += $totalMonth; ?>
				</td>
				<?php $init->addMonths(1); ?>
			<?php endfor; ?>
			<td class="text-center" style="color: #fff; background-color: #a94442!important; border-bottom-color: #a94442!important;padding: 8px 5px!important;">
				<b>
					<?php echo number_format($totalYear,2,',','.') ; ?> €
				</b>
			</td>
		</tr>
		<?php foreach ($arrayExpenses as $key => $expense): ?>
			<?php $totalYear = 0; ?>	
			<tr>
				<td class="text-center" style="padding: 8px 5px!important;">
					<b><?php echo $key; ?></b>
				</td>
				<?php $totalYear = 0; ?>
				<?php $init = $inicio->copy(); ?>
				<?php for($i = 1 ; $i <= 12; $i++): ?>
					<td class="text-center" style=" padding: 8px 5px!important;">
						
						<b>
							<?php if ($expense[$init->copy()->format('n')] > 0): ?>
								<?php echo number_format($expense[$init->copy()->format('n')], 0,',' , '.'); ?> €
							<?php else: ?>
								---
							<?php endif ?>
						</b>
						<?php $totalYear += $totalMonth; ?>
					</td>
					<?php $init->addMonths(1); ?>
				<?php endfor; ?>
				<td class="text-center" style="padding: 8px 5px!important;">
					<b>
						<?php echo number_format($totalYear,0,',','.' ); ?> €
					</b>
				</td>
			</tr>		
		<?php endforeach ?>

		<tr >


			<td class="text-center" style="color: #fff; background-color: #5c90d2!important; border-bottom-color: #5c90d2!important; font-size: 18px; padding: 8px 5px!important">
				<b>BENEFICIO CONTABLE</b>
			</td>
			<?php $totalYearBeneficios = 0; ?>
			<?php $init = $inicio->copy(); ?>
			<?php for($i = 1 ; $i <= 12; $i++): ?>
				<td class="text-center" style="color: #fff; background-color: #5c90d2!important; border-bottom-color: #5c90d2!important; font-size: 18px; padding: 8px 5px!important">
					<?php 
						
						$totalMonthGastos = 
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

						$totalMonthIngresos =  	$arrayTotales['meses'][$init->copy()->format('n')] + 
	            								$arrayIncomes['INGRESOS EXTRAORDINARIOS'][$init->copy()->format('n')] + 
	            								$arrayIncomes['RAPPEL CLOSES'][$init->copy()->format('n')] + 
	            								$arrayIncomes['RAPPEL FORFAITS'][$init->copy()->format('n')] + 
	            								$arrayIncomes['RAPPEL ALQUILER MATERIAL'][$init->copy()->format('n')];

						$totalMonthBeneficios = $totalMonthIngresos - abs($totalMonthGastos);
					?>
					<b>
						<?php if ($totalMonthBeneficios > 0): ?>
							<?php echo number_format($totalMonthBeneficios,2,',','.'); ?>€
						<?php else: ?>
							---
						<?php endif ?>
					</b>
					<?php $totalYearBeneficios += $totalMonthBeneficios; ?>
				</td>
				<?php $init->addMonths(1); ?>
			<?php endfor; ?>
			<td class="text-center" style="color: #fff; background-color: #5c90d2!important; border-bottom-color: #5c90d2; font-size: 18px; padding: 8px 5px!important">
				<b><?php echo number_format($totalYearBeneficios,2,',','.'); ?> €</b>
			</td>
		</tr>
	</tbody>