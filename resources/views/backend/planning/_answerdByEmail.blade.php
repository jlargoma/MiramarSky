
<?php use \Carbon\Carbon; ?>

<div class="modal-content">
	<div class="modal-header clearfix text-left">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14" style="font-size: 40px!important"></i>
		</button>
		<h5>Contestacion a <span class="semi-bold"><?php echo $book->customer->name ?></span></h5>
	</div>
	<div class="modal-body">
		<form  action="{{ url('/admin/reservas/sendEmail') }}" method="post">
			<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
		    <div id="summernote" name="summernote">
		     	Hola <b><?php echo $book->customer->name ?></b> <br><br>
		      	Si hay disponibilidad para tu reserva en apartamento dos dormitorios.<br>
		      	El precio te incluye, parking cubierto, piscina climatizada, gimnasio, taquilla guarda esquíes, sabanas y toallas. <br><br><br>
		      	Entrada: <?php echo Carbon::CreateFromFormat('Y-m-d',$book->start)->format('d-m-Y') ?><br>
		      	Salida: <?php echo Carbon::CreateFromFormat('Y-m-d',$book->finish)->format('d-m-Y') ?><br>
		      	Noches: <?php echo $book->nigths; ?><br>
		      	Ocupantes: <?php echo $book->pax; ?><br>
		      	<?php if ($book->sup_lujo > 0): ?>
		      		Suplemento Lujo: <?php echo $book->sup_lujo ?>€<br>
		      	<?php endif ?>
		      	<?php if ($book->sup_park > 0): ?>
		      		Suplemento Parking: <?php echo $book->sup_park ?>€<br>
		      	<?php endif ?>
		      	Precio total: <?php echo number_format($book->total_price,2,',','.') ?>€<br><br><br>
		      	Quedamos a la espera de tu respuesta.<br>
		      	Un cordial saludo
		    </div>

		    <input type="submit">
      	</form>
	</div>
</div>

<script type="text/javascript">
	  $('#summernote').summernote();
</script>