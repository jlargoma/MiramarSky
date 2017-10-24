<?php   use \Carbon\Carbon;  
        setlocale(LC_TIME, "ES"); 
        setlocale(LC_TIME, "es_ES"); 
?>
<div class="col-xs-12">
	<h2 class="text-center font-w300">
		Calendario <span class="font-w800">BOOKING</span>
	</h2>
</div>
<div class="col-xs-12">
	<div class="panel">
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
				
				$rooms = \App\Rooms::whereIn('id', $arrayRoomId)->get();

			?>
			<?php for ($z=1; $z <= 9; $z++):?>
				<div class="tab-pane <?php if($z == 4){ echo 'active';} ?>" id="booking<?php echo $z ?>">
					
					



				</div>
			<?php endfor; ?>
		</div>
</div>