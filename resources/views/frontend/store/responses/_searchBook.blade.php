<?php 
	use \Carbon\Carbon;
	setlocale(LC_TIME, "ES"); 
    setlocale(LC_TIME, "es_ES"); 
?>
<div class="list-group">
	<?php foreach ($books as $key => $book): ?>
		<?php if ($avaliable == 0): ?>
			<div class="list-group-item">
				<h4 class="list-group-item-heading">{{ $book->customer->name }}</h4>
				<p class="list-group-item-text">
					<b>Email:</b> <?php echo $book->customer->email ?><br>
					<b>Fecha entrada:</b> <?php echo Carbon::CreateFromFormat('Y-m-d',$book->start)->formatLocalized('%d %b')?><br>
					<b>Fecha salida:</b> <?php echo Carbon::CreateFromFormat('Y-m-d',$book->finish)->formatLocalized('%d %b')?><br>
				</p>
				<span class="label label-success" style="position: absolute; top: 10px; right: 5px;">Pedido realizado</span>
			</div>
		<?php elseif($avaliable == 1): ?>
			<a href="{{ url('/supermercado/reserva') }}/<?php echo base64_encode($book->id) ?>" class="list-group-item">
				<h4 class="list-group-item-heading">{{ $book->customer->name }}</h4>
				<p class="list-group-item-text">
					<b>Email:</b> <?php echo $book->customer->email ?><br>
					<b>Fecha entrada:</b> <?php echo Carbon::CreateFromFormat('Y-m-d',$book->start)->formatLocalized('%d %b')?><br>
					<b>Fecha salida:</b> <?php echo Carbon::CreateFromFormat('Y-m-d',$book->finish)->formatLocalized('%d %b')?><br>
				</p>
				<span class="label label-danger" style="position: absolute; top: 10px; right: 5px;">Pedido en proceso</span>
			</a>
		<?php elseif ($avaliable == 2):?>
			<a href="{{ url('/supermercado/reserva') }}/<?php echo base64_encode($book->id) ?>" class="list-group-item">
				<h4 class="list-group-item-heading">{{ $book->customer->name }}</h4>
				<p class="list-group-item-text">
					<b>Email:</b> <?php echo $book->customer->email ?><br>
					<b>Fecha entrada:</b> <?php echo Carbon::CreateFromFormat('Y-m-d',$book->start)->formatLocalized('%d %b')?><br>
					<b>Fecha salida:</b> <?php echo Carbon::CreateFromFormat('Y-m-d',$book->finish)->formatLocalized('%d %b')?><br>
				</p>
			</a>
		<?php endif ?>
		
	<?php endforeach ?>
	
</div>