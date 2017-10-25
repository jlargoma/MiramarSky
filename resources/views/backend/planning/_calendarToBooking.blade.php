<?php   use \Carbon\Carbon;  
        setlocale(LC_TIME, "ES"); 
        setlocale(LC_TIME, "es_ES"); 
?>
<style type="text/css">
	.calendar-day{
		width: 20px; height: 15px; float: left; text-align: center;
	}
</style>
<div class="row">
	<h2 class="text-center font-w300">
		Calendario <span class="font-w800">BOOKING</span>
	</h2>
</div>
<div class="row">
	<ul class="nav nav-tabs nav-tabs-simple bg-info-light fechas" role="tablist" data-init-reponsive-tabs="collapse">
		<?php $dateAux = $dateX->copy(); ?>
			<?php for ($i=1; $i <= 9 ; $i++) :?>
				<?php if(!$mobile->isMobile()){ $hidden = "";}else{ $hidden = "hidden"; } ?>
				<li class='<?php if($i == 4 ){ echo "active";} ?> <?php if($i < 4 || $i > 8){ echo $hidden;} ?>'>
					<a href="#booking<?php echo $i?>" data-toggle="tab" role="tab" style="padding:10px">
						<?php echo ucfirst($dateAux->copy()->formatLocalized('%b %y'))?>
					</a>
				</li>
				<?php $dateAux->addMonth(); ?>
			<?php endfor; ?>
	</ul>
	<div class="tab-content">
		<?php 
			$arrayRoomId = array();
			$booksAux = \App\Book::where('type_book', 9)->get();
			foreach ($booksAux as $key => $book) {
				if (!in_array( $book->room->id , $arrayRoomId )) {
					$arrayRoomId[] = $book->room_id;
				}
			}
			$arrayRoomsForType = [
									'2dorm-lujo' => [
													'title' => '2DL',
													'rooms' => [],
												] ,
									
									'2dorm-stand' => [
													'title' => '2D',
													'rooms' => [],
												],
									'estudio' => [
													'title' => 'EST',
													'rooms' => [],
												], 
								];
			$rooms = \App\Rooms::whereIn('id', $arrayRoomId)->get();

			foreach ($rooms as $key => $room) {
				if ($room->luxury == 1 && $room->sizeApto == 2) {

					$arrayRoomsForType['2dorm-lujo']['rooms'][] = $room; 

				}

				if($room->luxury == 1 && $room->sizeApto == 1){

					$arrayRoomsForType['estudio']['rooms'][] = $room; 

				}

				if($room->luxury == 0 && $room->sizeApto == 2){

					$arrayRoomsForType['2dorm-stand']['rooms'][] = $room; 

				}

				if($room->luxury == 0 && $room->sizeApto == 1){

					$arrayRoomsForType['estudio']['rooms'][] = $room; 

				}
			}

			// echo "<pre>";
			// print_r($arrayRoomsForType);

		?>
		<?php for ($z=1; $z <= 9; $z++):?>
			<div class="tab-pane <?php if($z == 4){ echo 'active';} ?>" id="booking<?php echo $z ?>" style="padding: 0 5px;">
				<div class="row">
					<div class="table-responsive">
						<table class="fc-border-separate calendar-table" style="width: 100%">
							<thead>
								<tr>
									<td rowspan="2" style="width: 1%!important"></td>
									<?php for ($i=1; $i <= $arrayMonths[$dateX->copy()->format('n')] ; $i++): ?> 
										<td style='border:1px solid black;width: 3%;font-size: 10px' class="text-center">
											<?php echo $i?> 
										</td> 
									<?php endfor; ?>
								</tr>
								<tr>

									<?php for ($i=1; $i <= $arrayMonths[$dateX->copy()->format('n')] ; $i++): ?> 
										<td style='border:1px solid black;width: 3%;font-size: 10px' class="text-center <?php echo $days[$dateX->copy()->format('n')][$i]?>">
											<?php echo $days[$dateX->copy()->format('n')][$i]?> 
										</td> 
									<?php endfor; ?> 
								</tr>
							</thead>
							<tbody>

								<?php foreach ($arrayRoomsForType as $room): ?>
									
									<tr>
										<?php $dateX = $dateX->startOfMonth() ?>
										<td class="text-center" style='width: 3%;text-align: center'>
											<b>
												<?php echo substr($room['title'], 0, 5)?>	
											</b>
										</td>

										<?php for ($i=01; $i <= $arrayMonths[$dateX->copy()->format('n')] ; $i++): ?> 
											<?php $count         = count($room['rooms']) ?>
											<?php $countReservas = false ?>
											<?php if (count($room['rooms']) > 0): ?>
												<?php foreach ($room['rooms'] as $key => $data): ?>
													

													<?php 
														if ( isset($arrayReservas[$data->id][$dateX->copy()->format('Y')][$dateX->copy()->format('n')][$i]) ){
															
															$count = $count - (count($arrayReservas[$data->id][$dateX->copy()->format('Y')][$dateX->copy()->format('n')][$i]) - 1);
															
														}
													?>	
												<?php endforeach ?>
												
												
											<?php endif ?>
											
												<td class="" style='border:1px solid grey;width: 3%;text-align: center'>
													<?php if ( $count == 0 ): ?>
														<span class="text-danger">
															<b><?php echo $count ?></b>
														</span>
													<?php else: ?>
														<span>
															<b><?php echo $count ?></b>
														</span>
													<?php endif ?>
												</td>

											<?php if ($dateX->copy()->format('d') != $arrayMonths[$dateX->copy()->format('n')]): ?>
												<?php $dateX = $dateX->addDay(); ?>
											<?php else: ?>
												<?php $dateX = $dateX->startOfMonth() ?>
											<?php endif ?>
										<?php endfor; ?> 
									</tr>

								<?php endforeach; ?>
							</tbody>
						</table>
					<?php $dateX = $dateX->addMonth(); ?>
					</div>
				</div>
			</div>
		<?php endfor; ?>
	</div>
</div>