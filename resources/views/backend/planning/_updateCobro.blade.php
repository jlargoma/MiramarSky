<?php setlocale(LC_TIME, "ES"); setlocale(LC_TIME, "es_ES"); use \Carbon\Carbon;?>
<div>
	<div class="col-xs-12"><h3><?php echo $book->customer->name ?> <a href="tel:<?php echo $book->customer->phone ?>"><i class="fa fa-phone"></i></a></h3></div>

	<div>
		<b>Entrada :</b> <?php echo Carbon::CreateFromFormat('Y-m-d',$book->start)->formatLocalized('%d-%B') ?>
		<b>Salida : </b><?php echo Carbon::CreateFromFormat('Y-m-d',$book->finish)->formatLocalized('%d-%B') ?><br>
		<b>PVP : </b><?php echo number_format($book->total_price,2,',','.') ?> €
		<b>Pendiente : </b>
			<?php if ($book->total_price - $pending > 0): ?>
				<b style="color:red"><?php echo number_format($book->total_price - $pending,2,',','.') ?> €</b><br>
			<?php else: ?>
				<?php echo number_format($book->total_price - $pending,2,',','.') ?> €<br>
			<?php endif ?>

		<div class="col-xs-12"> 
			<form action="{{ url('/admin/reservas/saveCobro') }}">
				<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
				<input type="hidden" name="id" value="<?php echo $book->id ?>">
				<div class="col-xs-6">
					Fecha: <br>
					<input type="text" name="fecha" class="form-control" value="<?php echo Carbon::now()->format('d-m-Y') ?>">
				</div>
				<div class="col-xs-6">
					Importe:<br>
					<input type="number" name="import" class="form-control">
				</div>
				<div class="col-xs-12 text-left">
					Metodo de pago:<br>
					<select name="tipo" id="tipo">
						<?php for ($i=0; $i <= 2 ; $i++):?>
							<option value="<?php echo $i ?>"><?php echo $book->getTypeCobro($i) ?></option>
						<?php endfor; ?>
					</select>
				</div>
				<div style="clear: both;"></div>
				<div class="text-center">
					<input type="submit" class="btn btn-success  m-t-10" value="Cobrar">
				</div>
			</form>
			<form action="{{ url('/admin/reservas/saveFianza') }}">
				<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
				<input type="hidden" name="id" value="<?php echo $book->id ?>">
				<div class="col-xs-6">
					Fecha: <br>
					<input type="text" name="fecha" class="form-control" value="<?php echo Carbon::now()->format('d-m-Y') ?>">
				</div>
				<div class="col-xs-6">
					Fianza:<br>
					<input type="number" name="fianza" class="form-control">
				</div>
				<div class="col-xs-12 text-left">
					Comentario: <br>
					<input type="text" name="comentario" class="form-control">
				</div>
				<div style="clear: both;"></div>
				<div class="text-center">
					<input type="submit" class="btn btn-success  m-t-10" value="Fianza">
				</div>
			</form>
		</div>
	</div>
</div>