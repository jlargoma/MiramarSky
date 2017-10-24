<div class="col-md-12 col-xs-12">
	<div class="panel">
		<ul class="nav nav-tabs nav-tabs-simple bg-info-light fechas" role="tablist" data-init-reponsive-tabs="collapse">
			<?php $dateAux = $inicio->copy(); ?>
			<?php for ($i=1; $i <= 9 ; $i++) :?>
				<?php if(!$mobile->isMobile()){ $hidden = "";}else{ $hidden = "hidden"; } ?>
				<li class='<?php if($i == 4 ){ echo "active";} ?> <?php if($i < 4 || $i > 8){ echo $hidden;} ?>'>
					<a href="#tab<?php echo $i?>" data-toggle="tab" role="tab" style="padding:10px">
						<?php echo ucfirst($dateAux->copy()->formatLocalized('%b %y'))?>
					</a>
				</li>
				<?php $dateAux->addMonth(); ?>
			<?php endfor; ?>
		</ul>
		<div class="tab-content">
			<?php for ($z=1; $z <= 9; $z++):?>
				<div class="tab-pane <?php if($z == 4){ echo 'active';} ?>" id="tab<?php echo $z ?>">
					<div class="row">
						<div class="table-responsive">
							<table class="fc-border-separate calendar-table" style="width: 100%">
								<thead>
									<!-- <tr >
										<td class="text-center" colspan="<?php echo $arrayMonths[$date->copy()->format('n')]+1 ?>">
											<?php echo  ucfirst($inicio->copy()->formatLocalized('%B %Y'))?>
										</td> 
									</tr> -->
									<tr>
										<td rowspan="2" style="width: 1%!important"></td>
										<?php for ($i=1; $i <= $arrayMonths[$inicio->copy()->format('n')] ; $i++): ?> 
											<td style='border:1px solid black;width: 3%;font-size: 10px' class="text-center">
												<?php echo $i?> 
											</td> 
										<?php endfor; ?>
									</tr>
									<tr>

										<?php for ($i=1; $i <= $arrayMonths[$inicio->copy()->format('n')] ; $i++): ?> 
											<td style='border:1px solid black;width: 3%;font-size: 10px' class="text-center <?php echo $days[$inicio->copy()->format('n')][$i]?>">
												<?php echo $days[$inicio->copy()->format('n')][$i]?> 
											</td> 
										<?php endfor; ?> 
									</tr>
								</thead>
								<tbody>

									<?php foreach ($roomscalendar as $room): ?>
										<tr>
											<?php $inicio = $inicio->startOfMonth() ?>
											<td class="text-center">
												<b style="cursor: pointer;" data-placement="right" title="" data-toggle="tooltip" data-original-title="<?php echo $room->name ?>">
													<?php echo substr($room->nameRoom, 0,5)?>	
												</b>
											</td>

											<?php for ($i=01; $i <= $arrayMonths[$inicio->copy()->format('n')] ; $i++): ?> 
												<!-- Si existe la reserva para ese dia -->
												<?php if (isset($arrayReservas[$room->id][$inicio->copy()->format('Y')][$inicio->copy()->format('n')][$i])): ?>

													<?php $calendars = $arrayReservas[$room->id][$inicio->copy()->format('Y')][$inicio->copy()->format('n')][$i] ?>
													<!-- Si hay una reserva que sale y una que entra  -->
													<?php if (count($calendars) > 1): ?>
														
														<td style='border:1px solid grey;width: 3%'>
															<?php for ($x = 0; $x < count($calendars); $x++): ?>

																<?php if($calendars[$x]->finish == $inicio->copy()->format('Y-m-d')): ?>
																	<a 
																		href="{{url ('/admin/reservas/update')}}/<?php echo $calendars[$x]->id ?>" 
																		title="
																				<?php echo $calendars[$x]->customer['name'] ?> 

																				<?php echo 'PVP:'.$calendars[$x]->total_price ?>
																				<?php if (isset($payment[$calendars[$x]->id])): ?>
																					<?php echo 'PEND:'.($calendars[$x]->total_price - $payment[$calendars[$x]->id])?>
																				<?php else: ?>
																				<?php endif ?>"
																	>
																		<div class="<?php echo $book->getStatus($calendars[$x]->type_book) ?> end" style="width: 50%;float: left;">
																			&nbsp;
																		</div>
																	</a>
																<?php elseif ($calendars[$x]->start == $inicio->copy()->format('Y-m-d')): ?>

																	<a 
																		href="{{url ('/admin/reservas/update')}}/<?php echo $calendars[$x]->id ?>" 
																		title="
																				<?php echo $calendars[$x]->customer['name'] ?> 

																				<?php echo 'PVP:'.$calendars[$x]->total_price ?>
																				<?php if (isset($payment[$calendars[$x]->id])): ?>
																					<?php echo 'PEND:'.($calendars[$x]->total_price - $payment[$calendars[$x]->id])?>
																				<?php else: ?>
																				<?php endif ?>"
																	>
																		<div class="<?php echo $book->getStatus($calendars[$x]->type_book) ?> start" style="width: 50%;float: right;">
																			&nbsp;
																		</div>
																	</a>


																<?php else: ?>
																	
																	<?php if ($calendars[$x]->type_book != 9): ?>
																		<a 
																		href="{{url ('/admin/reservas/update')}}/<?php echo $calendars[$x]->id ?>" 
																		title="
																				<?php echo $calendars[$x]->customer['name'] ?> 

																				<?php echo 'PVP:'.$calendars[$x]->total_price ?>
																				<?php if (isset($payment[$calendars[$x]->id])): ?>
																					<?php echo 'PEND:'.($calendars[$x]->total_price - $payment[$calendars[$x]->id])?>
																				<?php else: ?>
																				<?php endif ?>"
																	>
																		<div class="<?php echo $book->getStatus($calendars[$x]->type_book) ?>" style="width: 100%;float: left;">
																			&nbsp;
																		</div>
																	</a>
																	<?php endif ?>
																<?php endif ?>
															<?php endfor ?>

														</td>

														<!-- Si no hay dos reservas el mismo dia  -->
													<?php else: ?>
														<?php if ($calendars[0]->start == $inicio->copy()->format('Y-m-d')): ?>
															<td 
																title="
															<?php echo $calendars[0]->customer['name'] ?> 

															<?php echo 'PVP:'.$calendars[0]->total_price ?>
															<?php if (isset($payment[$calendars[0]->id])): ?>
																<?php echo 'PEND:'.($calendars[0]->total_price - $payment[$calendars[0]->id])?>
															<?php else: ?>
															<?php endif ?>"
																style='border:1px solid grey;width: 3%'>

																<div class="<?php echo $book->getStatus($calendars[0]->type_book) ?> start" style="width: 100%;float: left;">
																	&nbsp;
																</div>

															</td>    
														<?php elseif($calendars[0]->finish == $inicio->copy()->format('Y-m-d')): ?>
															<td 
																title="
															<?php echo $calendars[0]->customer['name'] ?> 

															<?php echo 'PVP:'.$calendars[0]->total_price ?>
															<?php if (isset($payment[$calendars[0]->id])): ?>
																<?php echo 'PEND:'.($calendars[0]->total_price - $payment[$calendars[0]->id])?>
															<?php else: ?>
															<?php endif ?>"
																style='border:1px solid grey;width: 3%'>
																<div class="<?php echo $book->getStatus($calendars[0]->type_book) ?> end" style="width: 100%;float: left;">
																	&nbsp;
																</div>


															</td>
														<?php else: ?>

															<td 
															style='border:1px solid grey;width: 3%' 
															title="
															<?php echo $calendars[0]->customer['name'] ?> 

															<?php echo 'PVP:'.$calendars[0]->total_price ?>
															<?php if (isset($payment[$calendars[0]->id])): ?>
																<?php echo 'PEND:'.($calendars[0]->total_price - $payment[$calendars[0]->id])?>
															<?php else: ?>
															<?php endif ?>" 
															class="<?php echo $book->getStatus($calendars[0]->type_book) ?>"
															>
																<?php if ($calendars[0]->type_book == 9): ?>
																	<div style="width: 100%;height: 100%">
																		&nbsp;
																	</div>
																<?php else: ?>
																	<a href="{{url ('/admin/reservas/update')}}/<?php echo $calendars[0]->id ?>">
																		<div style="width: 100%;height: 100%">
																			&nbsp;
																		</div>
																	</a>
																<?php endif ?>


															</td>

														<?php endif ?>
													<?php endif ?>
													<!-- Si no existe nada para ese dia -->
												<?php else: ?>
													
													<td class="<?php echo $days[$inicio->copy()->format('n')][$i]?>" style='border:1px solid grey;width: 3%'>

													</td>

												<?php endif; ?>

												<?php if ($inicio->copy()->format('d') != $arrayMonths[$inicio->copy()->format('n')]): ?>
													<?php $inicio = $inicio->addDay(); ?>
												<?php else: ?>
													<?php $inicio = $inicio->startOfMonth() ?>
												<?php endif ?>
											<?php endfor; ?> 
									</tr>

								<?php endforeach; ?>
							</tbody>
						</table>
						<?php $inicio = $inicio->addMonth(); ?>
					</div>
				</div>
			</div>
		<?php endfor; ?>

	</div>
</div> 
