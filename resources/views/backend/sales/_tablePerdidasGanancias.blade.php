<?php   use \Carbon\Carbon;?>
<table class=" table table-bordered table-striped table-header-bg">
	<thead>
		<tr>
			<th class="bg-complete text-white text-center"></th>
			<th class="bg-complete text-white text-center">Total</th>
			<th class="bg-danger text-white text-center">Pendiente</th>
			<?php $x = Carbon::createFromFormat('Y-m-d', $year->start_date); ?>
			<?php for($i = 1 ; $i <= $diff; $i++): ?>
				<th class="bg-complete text-white text-center"><?php echo $x->formatlocalized('%B'); ?></th>
				<?php $x->addMonths(1); ?>

			<?php endfor; ?>
			
		</tr>
	</thead >
	<tbody>
		<tr>
			<?php $totalYear = 0; ?>
			<?php $init = Carbon::createFromFormat('Y-m-d', $year->start_date); ?>
			<?php for($i = 1 ; $i <= $diff; $i++): ?>
				<?php $totalMonth = 	$arrayTotales['meses'][$init->copy()->format('n')] + 
				            								$arrayIncomes['INGRESOS EXTRAORDINARIOS'][$init->copy()->format('n')] + 
				            								$arrayIncomes['RAPPEL CLOSES'][$init->copy()->format('n')] + 
				            								$arrayIncomes['RAPPEL FORFAITS'][$init->copy()->format('n')] + 
				            								$arrayIncomes['RAPPEL ALQUILER MATERIAL'][$init->copy()->format('n')];
									?>
				<?php $totalYear += $totalMonth; ?>
				<?php $init->addMonths(1); ?>
			<?php endfor; ?>
			<td class="text-center" style="color: #fff; background-color:#46c37b!important; border-bottom-color:#46c37b; padding: 8px 5px!important;">
				<b>INGRESOS</b>
			</td>
			<td class="text-center" style="color: #fff; background-color: #46c37b!important; border-bottom-color:#46c37b; padding: 8px 5px!important;">
				<b>
					<?php echo number_format($totalYear,0,',','.' ); ?> €
				</b>
			</td>
			<td class="text-center" style="color: #fff; background-color: #f55753!important; border-bottom-color:#46c37b; padding: 8px 5px!important;">
				<b>----</b>
			</td>

			<?php $init = Carbon::createFromFormat('Y-m-d', $year->start_date); ?>
			<?php for($i = 1 ; $i <= $diff; $i++): ?>
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
				</td>
				<?php $init->addMonths(1); ?>
			<?php endfor; ?>
			
		</tr>
	
		<tr>
			<?php $totalYear = 0; ?>
			<?php $init = Carbon::createFromFormat('Y-m-d', $year->start_date); ?>
			<?php for($i = 1 ; $i <= $diff; $i++): ?>
				<?php $totalMonth = $arrayTotales['meses'][$init->copy()->format('n')];?>
				<?php $totalYear += $totalMonth; ?>
				<?php $init->addMonths(1); ?>
			<?php endfor; ?>
			<td class="text-center" style="padding: 8px 5px!important;">
				<b>VENTAS TEMPORADA</b>
			</td>
			
			<td class="text-center" style="padding: 8px 5px!important;">
				<b>
					<?php echo number_format($totalYear,0,',','.' ); ?> €
				</b>
			</td>
			<td class="text-center" style="padding: 8px 5px!important;">
				<b>----</b>
			</td>
			<?php $init = Carbon::createFromFormat('Y-m-d', $year->start_date); ?>
			<?php for($i = 1 ; $i <= $diff; $i++): ?>
				<td class="text-center" style=" padding: 8px 5px!important;">
					
					<b>
						<?php if ($arrayTotales['meses'][$init->copy()->format('n')] > 0): ?>
							<?php echo number_format($arrayTotales['meses'][$init->copy()->format('n')], 0,',' , '.'); ?> €
						<?php else: ?>
							---
						<?php endif ?>
						
					</b>
				</td>
				<?php $init->addMonths(1); ?>
			<?php endfor; ?>
			
		</tr>

		<?php foreach ($arrayIncomes as $key => $income): ?>
			<?php $totalYear = 0; ?>
			<?php $init = Carbon::createFromFormat('Y-m-d', $year->start_date); ?>
			<?php for($i = 1 ; $i <= $diff; $i++): ?>

				<?php $totalYear += $income[$init->copy()->format('n')]; ?>
				<?php $init->addMonths(1); ?>
			<?php endfor; ?>
			<tr>
				<td class="text-center" style="padding: 8px 5px!important;">
					<b><?php echo $key; ?></b>
				</td>
				<td class="text-center" style="padding: 8px 5px!important;">
					<b>
						<?php echo number_format($totalYear,0,',','.' ); ?> €
					</b>
				</td>
				<td class="text-center" style="padding: 8px 5px!important;">
					<b>----</b>
				</td>
				<?php $totalYear = 0; ?>
				<?php $init = Carbon::createFromFormat('Y-m-d', $year->start_date); ?>
				<?php for($i = 1 ; $i <= $diff; $i++): ?>
					<td class="text-center" style=" padding: 8px 5px!important;">
						
						<b>
							<?php if ($income[$init->copy()->format('n')] > 0): ?>
								<?php echo number_format($income[$init->copy()->format('n')], 0,',' , '.'); ?> €
							<?php else: ?>
								---
							<?php endif ?>
						</b>
					</td>
					<?php $init->addMonths(1); ?>
				<?php endfor; ?>
			</tr>		
		<?php endforeach ?>





		
		<tr>
			<?php $totalYear = 0; ?>
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
				<?php $totalYear += $totalMonth; ?>
				<?php $init->addMonths(1); ?>
			<?php endfor; ?>
			<td class="text-center" style="color: #fff; background-color: #a94442!important; border-bottom-color: #a94442!important;padding: 8px 5px!important;">
				<b>GASTOS</b>
			</td>
			<td class="text-center" style="color: #fff; background-color: #a94442!important; border-bottom-color:#46c37b; padding: 8px 5px!important;">
				<b>
					<?php echo number_format($totalYear,0,',','.' ); ?> €
				</b>
			</td>
			<!-- GASTOS PENDIENTES  -->
			<td class="text-center" style="color: #fff; background-color: #f55753!important; border-bottom-color:#46c37b; padding: 8px 5px!important;">
				<?php 
					$totalExpensesPending = array_sum($arrayExpensesPending['PAGO PROPIETARIO']) +
											array_sum($arrayExpensesPending['AGENCIAS']) + 
											array_sum($arrayExpensesPending['STRIPE']) + 
											array_sum($arrayExpensesPending['LIMPIEZA']) + 
											array_sum($arrayExpensesPending['LAVANDERIA']) - 
											$totalYear
											;
				?>
				<b> <?php echo number_format($totalExpensesPending,0,',','.' ); ?> € </b>
			</td>


			<?php $totalYear = 0; ?>
			<?php $init = Carbon::createFromFormat('Y-m-d', $year->start_date); ?>
			<?php for($i = 1 ; $i <= $diff; $i++): ?>
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
				</td>
				<?php $init->addMonths(1); ?>
			<?php endfor; ?>
		</tr>

		<?php foreach ($arrayExpenses as $key => $expense): ?>	
			<?php $totalYear = 0; ?>
			<?php $init = Carbon::createFromFormat('Y-m-d', $year->start_date); ?>
			<?php for($i = 1 ; $i <= $diff; $i++): ?>
				<?php $totalYear += $expense[$init->copy()->format('n')]; ?>
				<?php $init->addMonths(1); ?>
			<?php endfor; ?>
			<tr>
				<td class="text-center" style="padding: 8px 5px!important;">
					<b><?php echo $key; ?></b>
				</td>
				<td class="text-center" style="padding: 8px 5px!important;">
					<b>
						<?php echo number_format($totalYear,0,',','.' ); ?> €
					</b>
				</td>
				<td class="text-center" style=" padding: 8px 5px!important;">
					<?php foreach ($arrayExpensesPending as $type => $expenseX): ?>
						<?php if ($type == $key): ?>
							<?php if (array_sum($expenseX) > 0): ?>
								<b><?php echo number_format( (array_sum($expenseX) - $totalYear) ,0,',','.' ); ?> €</b>
							<?php endif ?>
						
						<?php endif ?>
					<?php endforeach ?>
					<?php if ( !isset( $arrayExpensesPending[$key] ) ): ?>
						<b>---</b>
					<?php endif ?>
					

				</td>
				<?php $init = Carbon::createFromFormat('Y-m-d', $year->start_date); ?>
				<?php for($i = 1 ; $i <= $diff; $i++): ?>
					<td class="text-center" style=" padding: 8px 5px!important;">
						
						<b>
							<?php if ($expense[$init->copy()->format('n')] > 0): ?>
								<?php echo number_format($expense[$init->copy()->format('n')], 0,',' , '.'); ?> €
							<?php else: ?>
								---
							<?php endif ?>
						</b>
					</td>
					<?php $init->addMonths(1); ?>
				<?php endfor; ?>
			</tr>		
		<?php endforeach ?>



		<!-- BENEFICIOS FISCALES  -->
		<tr >
			<?php $totalYearBeneficios = 0; ?>
			<?php $totalYearExpenses = 0; ?>
			<?php $init = Carbon::createFromFormat('Y-m-d', $year->start_date); ?>
			<?php for($i = 1 ; $i <= $diff; $i++): ?>
				<?php 	$totalMonthGastos = 
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

						$totalYearBeneficios += ($totalMonthIngresos - abs($totalMonthGastos)); 
						$totalYearExpenses += abs($totalMonthGastos); 
				?>

				<?php $init->addMonths(1); ?>
			<?php endfor; ?>

			<?php 
				$totalExpensesPending =  array_sum($arrayExpensesPending['PAGO PROPIETARIO']) +  
										array_sum($arrayExpensesPending['AGENCIAS']) + 
										array_sum($arrayExpensesPending['STRIPE']) + 
										array_sum($arrayExpensesPending['LIMPIEZA']) + 
										array_sum($arrayExpensesPending['LAVANDERIA'])-
										$totalYearExpenses;
			?>

			<td class="text-center" style="color: #fff; background-color: #5c90d2!important; border-bottom-color: #5c90d2!important; font-size: 18px; padding: 8px 5px!important">
				<b>BENEFICIO CONTABLE</b><br>
				<b>
					<?php echo number_format( ($totalYearBeneficios - $totalExpensesPending) ,0,',','.' ); ?> €
				</b>
			</td>


			
			
			<td class="text-center" style="color: #fff; background-color: #5c90d2!important; border-bottom-color: #5c90d2; font-size: 18px; padding: 8px 5px!important">
				<b><?php echo number_format(($totalYearBeneficios ),2,',','.'); ?> €</b>
			</td>
			<td class="text-center" style="color: #fff; background-color: #f55753!important; border-bottom-color:#46c37b; padding: 8px 5px!important;">
				<b>GASTOS PENDIENTES</b><br>
				
				<b> <?php echo number_format(($totalExpensesPending ) ,0,',','.' ); ?> € </b>
			</td>
			<?php $init = Carbon::createFromFormat('Y-m-d', $year->start_date); ?>
			<?php for($i = 1 ; $i <= $diff; $i++): ?>
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

	            		$totalMonthBeneficios = ($totalMonthIngresos - abs($totalMonthGastos)); 
					?>
					<b>
						<?php if ($totalMonthBeneficios == 0): ?>
							---
						<?php else: ?>
							<?php echo number_format($totalMonthBeneficios,2,',','.'); ?>€
						<?php endif ?>
					</b>
				</td>
				<?php $init->addMonths(1); ?>
			<?php endfor; ?>
			
		</tr>
	</tbody>
</table>