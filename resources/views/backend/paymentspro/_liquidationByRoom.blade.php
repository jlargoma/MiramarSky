<?php

use \Carbon\Carbon;
setlocale( LC_TIME , "ES" );
setlocale( LC_TIME , "es_ES" );
?>
<style type="text/css">
	.total {
		font-weight: bold;
		color: black;
		background-color: rgba(0, 100, 255, 0.2) !important;
	}

</style>
<button style="z-index: 20;" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
			class="fa fa-close fa-2x"></i></button>
<div class="row">
	<?php if ($room != 'all'): ?>
	<h2 class="text-center font-w800" style="margin-top: 0;">
			<?php echo strtoupper( $room->user->name ) ?> (<?php echo $room->nameRoom ?>)
		</h2>
	<?php else: ?>
	<h2 class="text-center font-w800" style="margin-top: 0;">
			TODOS LOS APTOS
		</h2>
	<?php endif ?>
	
</div>
<div class="row push-20">
	<div class="col-md-12 col-xs-12 resumen blocks">
		<div class="col-md-6 col-xs-12 ">
			<div class="row">
				<div class="col-md-12">
					<h2 class="text-center font-w800">Listado de reservas</h2>
				</div>
				<?php if (!$mobile->isMobile()): ?>
				<div class="col-xs-12" style="overflow-y: auto; max-height: 550px;">
						<table class="table no-footer ">
							<thead>
								<th class="text-center bg-complete text-white" style="width: 20%; padding: 4px 10px">Cliente</th>
								<th class="text-center bg-complete text-white" style="width: 10%; padding: 4px 10px">Pers</th>
								<th class="text-center bg-complete text-white"
								    style="width: 10%; padding: 4px 10px">IN</th>
								<th class="text-center bg-complete text-white"
								    style="width: 10%; padding: 4px 10px">OUT</th>
								<th class="text-center bg-complete text-white" style="width: 15%; padding: 4px 10px">ING. PROP</th>
								<th class="text-center bg-complete text-white" style="width: 10%; padding: 4px 10px">Apto</th>
								<th class="text-center bg-complete text-white" style="width: 10%; padding: 4px 10px">Park.</th>
								<?php if ($room != 'all'): ?>
								<?php if ($room->luxury == 1): ?>
								<th class="text-center bg-complete text-white" style="width: 12%">Sup.Lujo</th>
								<?php endif ?>
								<?php endif ?>
								<th class="text-center bg-complete text-white">&nbsp;</th>
							</thead>
							<tbody>
								<?php foreach ($books as $book): ?>
									<tr>
										<td class="text-center" style="padding: 8px" data-id="<?php echo $book->id; ?>">
											<a href="{{ url('/admin/reservas/update') }}/ <?php echo $book->id ?>">
												<?php echo ucfirst( strtolower( $book->customer->name ) ) ?>
											</a>
										</td>
										<td class="text-center" style="padding: 8px"><?php echo $book->pax ?> </td>
										<td class="text-center" style="padding: 8px">
											<?php
											$start = Carbon::CreateFromFormat( 'Y-m-d' , $book->start );
											echo $start->formatLocalized( '%d-%b' );
											?>
										</td>
										<td class="text-center" style="padding: 8px">
											<?php
											$finish = Carbon::CreateFromFormat( 'Y-m-d' , $book->finish );
											echo $finish->formatLocalized( '%d-%b' );
											?>
										</td>
										<td class="text-center total" style="padding: 8px; ">
											<?php if ($book->type_book != 7 && $book->type_book != 8 ): ?>
												<?php $cost = ($book->cost_apto + $book->cost_park + $book->cost_lujo) ?>
												<?php if ($cost > 0 ): ?>
													<?php echo number_format( $cost , 0 , ',' , '.' ) ?>€
												<?php else: ?>
											        ---€
												<?php endif ?>
											<?php else: ?>
												---€
											<?php endif ?>

										</td>
										<td class="text-center" style="padding: 8px; ">

											<?php if ($book->type_book != 7 && $book->type_book != 8 ): ?>
											<?php if ($book->cost_apto > 0 ): ?>
											<?php echo number_format( $book->cost_apto , 0 , ',' , '.' ) ?>€
											<?php else: ?>
												---€
											<?php endif ?>
											<?php else: ?>
											    ---€
											<?php endif ?>

										</td>
										<td class="text-center" style="padding: 8px; ">
											<?php if ($book->type_book != 7 && $book->type_book != 8 ): ?>
												<?php if ($book->cost_park > 0 ): ?>
													<?php echo number_format( $book->cost_park , 2 , '.' , ',' ) ?>€
												<?php else: ?>
													---€
												<?php endif ?>
											<?php else: ?>
											    ---€
											<?php endif ?>
										</td>
										<?php if ($room != 'all'): ?>
											<?php if ($room->luxury == 1): ?>
												<td class="text-center" style="padding: 8px; ">
													<?php if ($book->type_book != 7 && $book->type_book != 8 ): ?>
														<?php $auxLuxury = $book->cost_lujo ?>
														<?php if ($auxLuxury > 0): ?>
															<?php echo $auxLuxury ?>€
														<?php else: ?>
														    ---€
														<?php endif ?>
													<?php else: ?>
														---€
													<?php endif ?>
												</td>
											<?php endif ?>
										<?php endif ?>
										<?php if (!empty( $book->book_owned_comments ) && $book->promociones != 0): ?>
											<td class="text-center" style="padding: 8px; ">
												<img src="/pages/oferta.png" style="width: 40px;">
											</td>
										<?php endif ?>
									</tr>
								<?php endforeach ?>
							</tbody>
						</table>
					</div>
				<?php else: ?>
				<div class="table-responsive" style="overflow-y: auto; max-height: 250px;">
						<table class="table no-footer ">
							<thead>
								<th class="text-center bg-complete text-white" style="width: 20%; padding: 4px 10px">Cliente</th>
								<th class="text-center bg-complete text-white" style="width: 10%; padding: 4px 10px">Pers</th>
								<th class="text-center bg-complete text-white"
								    style="width: 10%; padding: 4px 10px">IN</th>
								<th class="text-center bg-complete text-white"
								    style="width: 10%; padding: 4px 10px">OUT</th>
								<th class="text-center bg-complete text-white" style="width: 15%; padding: 4px 10px">ING. PROP</th>
								<th class="text-center bg-complete text-white" style="width: 10%; padding: 4px 10px">Apto</th>
								<th class="text-center bg-complete text-white" style="width: 10%; padding: 4px 10px">Park.</th>
								@if ($room != 'all' && $room->luxury == 1)
									<th class="text-center bg-complete text-white" style="width: 12%">Sup.Lujo</th>
								@endif
								<th class="text-center bg-complete text-white">&nbsp;</th>
							</thead>
							<tbody>
								<?php foreach ($books as $book): ?>
								<tr>
										<td class="text-center" style="padding: 8px"
										    data-id="<?php echo $book->id; ?>"><?php echo ucfirst( strtolower( $book->customer->name ) ) ?> </td>
										<td class="text-center" style="padding: 8px"><?php echo $book->pax ?> </td>
										<td class="text-center" style="padding: 8px">
											<?php
											$start = Carbon::CreateFromFormat( 'Y-m-d' , $book->start );
											echo $start->formatLocalized( '%d-%b' );
											?>
										</td>
										<td class="text-center" style="padding: 8px">
											<?php
											$finish = Carbon::CreateFromFormat( 'Y-m-d' , $book->finish );
											echo $finish->formatLocalized( '%d-%b' );
											?>
										</td>
										<td class="text-center total" style="padding: 8px; ">
											<?php if ($book->type_book != 7 && $book->type_book != 8 ): ?>
											<?php $cost = ($book->cost_apto + $book->cost_park + $book->cost_lujo) ?>
											<?php if ($cost > 0 ): ?>
											<?php echo number_format( $cost , 0 , ',' , '.' ) ?>€
											<?php else: ?>
											                                                    ---€
											<?php endif ?>
											<?php else: ?>
											                                                    ---€
											<?php endif ?>

										</td>
										<td class="text-center" style="padding: 8px; ">

											<?php if ($book->type_book != 7 && $book->type_book != 8 ): ?>
											<?php if ($book->cost_apto > 0 ): ?>
											<?php echo number_format( $book->cost_apto , 0 , ',' , '.' ) ?>€
											<?php else: ?>
											                                                               ---€
											<?php endif ?>
											<?php else: ?>
											                                                               ---€
											<?php endif ?>

										</td>
										<td class="text-center" style="padding: 8px; ">
											<?php if ($book->type_book != 7 && $book->type_book != 8 ): ?>
											<?php if ($book->cost_park > 0 ): ?>
											<?php echo number_format( $book->cost_park , 0 , ',' , '.' ) ?>€
											<?php else: ?>
											                                                               ---€
											<?php endif ?>
											<?php else: ?>
											                                                               ---€
											<?php endif ?>
										</td>
									@if ($room != 'all' && $room->luxury == 1)
										<td class="text-center" style="padding: 8px; ">
												<?php if ($book->type_book != 7 && $book->type_book != 8 ): ?>
											<?php $auxLuxury = $book->cost_lujo ?>
											<?php if ($auxLuxury > 0): ?>
											<?php echo $auxLuxury ?>€
											<?php else: ?>
												                    ---€
											<?php endif ?>
											<?php else: ?>
												                    ---€
											<?php endif ?>
											</td>
									@endif
									<?php if (!empty( $book->book_owned_comments )): ?>
									<td class="text-center" style="padding: 8px; ">
												<img src="/pages/oferta.png" style="width: 40px;">
											</td>
									<?php endif ?>
									</tr>
								<?php endforeach ?>
							</tbody>
						</table>
					</div>
				<?php endif ?>
			</div>
		</div>
		<div class="col-md-6 col-xs-12 " >
			<div class="row push-20" style="padding: 10px; background-color: rgba(16,207,189,0.5);">
				<h4 class="text-left hidden-sm hidden-xs" style="line-height: 1; letter-spacing: -1px;">
					Liquidación:
					<span class="font-w800"><?php echo $startDate ?></span> -
					<span class="font-w800"><?php echo $finishDate ?></span>
				</h4>
				<h4 class="text-left  hidden-md hidden-lg" style="line-height: 1; letter-spacing: -1px;">
					Liquidación:<br>
					<span class="font-w800"><?php echo $startDate ?></span> -
					<span class="font-w800"><?php echo $finishDate ?></span>
				</h4>
				<table class="table table-bordered table-hover  no-footer" id="basicTable" role="grid">
					<tr>
						<th class="text-center bg-complete text-white">ING. PROP</th>
						<th class="text-center bg-complete text-white">Apto</th>
						<th class="text-center bg-complete text-white">Park</th>
						<?php if ($room != 'all'): ?>
							<?php if ($room->luxury == 1): ?>
								<th class="text-center bg-complete text-white">Sup.Lujo</th>
							<?php endif ?>
						<?php endif ?>
					</tr>
					<tr>
						<td class="text-center total">
							<?php if ($total > 0): ?>
								<?php echo number_format( $total , 0 , ',' , '.' ); ?>€
							<?php else: ?>
			                    --- €
							<?php endif ?>
						</td>
						<td class="text-center">
							<?php if ($apto > 0): ?>
								<?php echo number_format( $apto , 0 , ',' , '.' ); ?>€
							<?php else: ?>
								--- €
							<?php endif ?>
						</td>
						<td class="text-center">
							<?php if ($park > 0): ?>
								<?php echo $park ; ?>€
							<?php else: ?>
								--- €
							<?php endif ?>
						</td>
						<?php if ($room != 'all'): ?>
							<?php if ($room->luxury == 1): ?>
							<td class="text-center">
								<?php if ($lujo > 0): ?>
									<?php echo number_format( $lujo , 0 , ',' , '.' ); ?>€
								<?php else: ?>
									--- €
								<?php endif ?>
									</td>
							<?php endif ?>
					<?php endif ?>
					</tr>
				</table>
			</div>

			<div class="row">
				<h2 class="text-center font-w800">Gastos</h2>
			</div>
			<div class="row">
				<table class="table table-bordered no-footer">
					<?php $sumPagos = 0; ?>
					<?php if( count( $pagos ) > 0): ?>
						<?php foreach ($pagos as $pago): ?>
							<?php $sumPagos += $pago->import ?>
							<tr>
								<td class="text-center">
									<?php echo Carbon::createFromFormat( 'Y-m-d' , $pago->date )->format( 'd-m-Y' )?>
								</td>
								<td class="text-center">
									<?php echo $pago->concept ?>
								</td>
								<td class="text-center">
									<?php
										$divisor = 0;
										if ( preg_match( '/,/' , $pago->PayFor ) ) {
											$aux = explode( ',' , $pago->PayFor );
											for ( $i = 0 ; $i < count( $aux ) ; $i++ ) {
												if ( !empty( $aux[ $i ] ) ) {
													$divisor++;
												}
											}

										} else {
											$divisor = 1;
										}
										$expense = $pago->import / $divisor;
									?>
									<?php echo number_format( $expense , 2 , ',' , '.' ) ?>€

									<?php $pagototalProp += $expense;?>
								</td>
								<td class="text-center"><?php echo number_format( $total - $pagototalProp , 2 , ',' , '.');	?></td>
							</tr>
						<?php endforeach ?>
					<?php else: ?>
					<tr>
						<td colspan="4" class="text-center">
							No hay pagos para este apartamento
						</td>
					</tr>
					<?php endif ?>
				</table>
				<table class="table table-bordered no-footer">
					<tr>
						<td class="text-center white" style="background-color: #48b0f7;">
							<h5 class="text-center white" style="margin-top: 0">GENERADO</h5>
							<strong><?php echo number_format( $total , 2 , ',' , '.' ); ?>€</strong>
						</td>
						<td class="text-center white" style="background-color: #10cfbd;">
							 <h5 class="text-center white" style="margin-top: 0">PAGADO</h5>
							<strong><?php echo number_format( $pagototalProp , 2 , ',' , '.' );?>€</strong>
						</td>
						<td class="text-center white" style="background-color: #f55753;">
							<h5 class="text-center white" style="margin-top: 0">PENDIENTE</h5>
							<strong><?php echo number_format( ($total - $pagototalProp) , 2 , ',' ,	'.'	); ?>€</strong>
						</td>
					</tr>
				</table>
			</div>
	</div>
</div>

