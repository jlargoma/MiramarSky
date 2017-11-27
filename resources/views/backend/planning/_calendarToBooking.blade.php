<?php   use \Carbon\Carbon;  
        setlocale(LC_TIME, "ES"); 
        setlocale(LC_TIME, "es_ES"); 
        use Illuminate\Support\Facades\Cache;

?>
<style type="text/css">
	.calendar-day{
		width: 20px; height: 15px; float: left; text-align: center;
	}
</style>
<div class="row">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="position: absolute; top: 0px; right: 10px; z-index: 100">
        <i class="pg-close fs-20" style="color: #000!important;"></i>
    </button>
</div>
<div class="row">
	<h2 class="text-center font-w300">
		Calendario <span class="font-w800">BOOKING</span>
	</h2>
</div>
<div class="row push-40">
	<div class="col-xs-12" style="padding: 0px 20px;">
		<ul class="nav nav-tabs nav-tabs-simple bg-info-light fechas" role="tablist" data-init-reponsive-tabs="collapse">
			<?php $dateAux = $dateX->copy(); ?>
				<?php for ($i=1; $i <= 9 ; $i++) :?>
					<?php if(!$mobile->isMobile()){ $hidden = "";}else{ $hidden = "hidden"; } ?>
					<li class='<?php if($i == 4 ){ echo "active";} ?> <?php if($i < 4 && $i > 8){ echo $hidden;} ?>'>
						<a href="#booking<?php echo $i?>" data-toggle="tab" role="tab" style="padding:10px" data-month="<?php echo $i?>">
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

										'estudio' => [
														'title' => 'EST',
														'rooms' => [],
													], 		
										
										'2dorm-stand' => [
														'title' => '2D',
														'rooms' => [],
													],

										'chalet' => [
														'title' => 'CHLT',
														'rooms' => [],
													],
										
									];
				$roomsBooking = \App\Rooms::whereIn('id', $arrayRoomId)->where('state', 1)->orderBy('order', 'ASC')->get();
				$arrayCountRoomBooking = [ 0, 0, 0 ,0];
				foreach ($roomsBooking as $key => $roomB) {

					if ($roomB->id != 144) {
						if ($roomB->luxury == 1 && $roomB->sizeApto == 2) {

							$arrayCountRoomBooking[0]++;
						}

						if($roomB->luxury == 1 && $roomB->sizeApto == 1){

							$arrayCountRoomBooking[1]++;
						}

						if($roomB->luxury == 0 && $roomB->sizeApto == 2){

							$arrayCountRoomBooking[2]++;
						}

						if($roomB->luxury == 0 && $roomB->sizeApto == 1){

							$arrayCountRoomBooking[1]++; 
						}
					}else{
						$arrayCountRoomBooking[3]++; 
					}

				}


				$rooms = \App\Rooms::where('state', 1)->get();

				foreach ($rooms as $key => $room) {

					if ($room->id != 144) {

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

					}else{

						$arrayRoomsForType['chalet']['rooms'][] = $room; 

					}

					

				}
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
									<?php $inx = 0; ?>
									<?php foreach ($arrayRoomsForType as $key => $room): ?>
										
										<tr>
											<?php $dateX = $dateX->startOfMonth() ?>
											<td class="text-center " style='width: 3%;text-align: center'>
												<b>
													<?php echo substr($room['title'], 0, 5)?>
													<span class="text-danger">
														(<?php echo $arrayCountRoomBooking[$inx] ?>)
													</span>	
												</b>
											</td>

											<?php for ($i=1; $i <= $arrayMonths[$dateX->copy()->format('n')] ; $i++): ?> 
												<?php $count         = count($room['rooms']) ?>

												<?php $countReservas = false ?>
												<?php if ( $count > 0): ?>
													<?php foreach ($room['rooms'] as $key => $data): ?>
														<?php $countBooks = 0; ?>
														<?php if (isset($arrayReservas[$data->id][$dateX->copy()->format('Y')][$dateX->copy()->format('n')][$i]) ): ?>
															
																
																<?php foreach ($arrayReservas[$data->id][$dateX->copy()->format('Y')][$dateX->copy()->format('n')][$i] as $key => $bk): ?>
																	<?php if ($bk->type_book != 9): ?>
																		<?php $countBooks++; ?>
																	<?php endif ?>
																<?php endforeach ?>

																	
																<?php if ($countBooks >= 1): ?>
																	<?php $count--; ?>
																<?php endif ?>
																

														<?php endif ?>
														
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
										<?php $inx++; ?>
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
</div>