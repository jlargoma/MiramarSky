<?php 
	use \Carbon\Carbon;
	setlocale(LC_TIME, "ES"); 
    setlocale(LC_TIME, "es_ES"); 
?>
<div class="list-group">
	<?php foreach ($books as $key => $book): ?>
		<a href="{{ url('/supermercado/reserva') }}/<?php echo base64_encode($book->id) ?>" class="list-group-item">
			<h4 class="list-group-item-heading">{{ $book->customer->name }}</h4>
			<p class="list-group-item-text">
				<b>Email:</b> <?php echo $book->customer->email ?><br>
				<b>Fecha entrada:</b> <?php echo Carbon::CreateFromFormat('Y-m-d',$book->start)->formatLocalized('%d %b')?><br>
				<b>Fecha salida:</b> <?php echo Carbon::CreateFromFormat('Y-m-d',$book->finish)->formatLocalized('%d %b')?><br>
			</p>
		</a>
	<?php endforeach ?>
	
</div>