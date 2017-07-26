<?php setlocale(LC_TIME, "ES"); setlocale(LC_TIME, "es_ES"); use \Carbon\Carbon;?>
<div>
	<div class="col-xs-12"><h3> Reserva <?php echo $book->customer->name ?></h3></div>

	<div>
		Entrada : <?php echo Carbon::CreateFromFormat('Y-m-d',$book->start)->formatLocalized('%d-%B') ?><br>
		Salida : <?php echo Carbon::CreateFromFormat('Y-m-d',$book->finish)->formatLocalized('%d-%B') ?><br>
		PVP: <?php echo number_format($book->total_price,2,',','.') ?> €<br>
		Pendiente : <?php echo number_format($book->total_price - $pending,2,',','.') ?> €<br>

		<div class="col-xs-12 m-t-10"> 
			<form action="{{ url('/admin/reservas/saveCobro') }}">
				<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
				<input type="hidden" name="id" value="<?php echo $book->id ?>">
				<div class="col-xs-6">
					Fecha de cobro:<br>
					<input type="text" name="fecha" class="form-control" value="<?php echo Carbon::now()->format('d-m-Y') ?>">
				</div>
				<div class="col-xs-6">
					Importe:<br>
					<input type="number" name="import" class="form-control">
				</div>
				<div class="text-center">
					<input type="submit" class="btn btn-success  m-t-10">
				</div>
			</form>
		</div>
	</div>
</div>