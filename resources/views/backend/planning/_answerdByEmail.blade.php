
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
		    <textarea name="texto" id="" cols="100" rows="15">Hola <?php echo $book->customer->name ?><?php echo "\n" ?><?php echo "\n" ?>Si hay disponibilidad para tu reserva en apartamento dos dormitorios.<?php echo "\n" ?>El precio te incluye, parking cubierto, piscina climatizada, gimnasio, taquilla guarda esquíes, sabanas y toallas. <?php echo "\n" ?><?php echo "\n" ?><?php echo "\n" ?>Entrada: <?php echo Carbon::CreateFromFormat('Y-m-d',$book->start)->format('d-m-Y') ?><?php echo "\n" ?>Salida: <?php echo Carbon::CreateFromFormat('Y-m-d',$book->finish)->format('d-m-Y') ?><?php echo "\n" ?>Noches: <?php echo $book->nigths; ?><?php echo "\n" ?>Ocupantes: <?php echo $book->pax; ?><?php echo "\n" ?><?php if ($book->sup_lujo > 0): ?>Suplemento Lujo: <?php echo $book->sup_lujo ?>€<?php echo "\n" ?><?php endif ?><?php if ($book->sup_park > 0): ?>Suplemento Parking: <?php echo $book->sup_park ?>€<?php echo "\n" ?><?php endif ?>Precio total: <?php echo number_format($book->total_price,2,',','.') ?>€<?php echo "\n" ?><?php echo "\n" ?><?php echo "\n" ?>Quedamos a la espera de tu respuesta.<?php echo "\n" ?>Un cordial saludo
			</textarea><br>
		    <input type="submit">
      	</form>
	</div>
</div>

<script type="text/javascript">
	  var markupStr = 'hello world';
	  $('#summernote').summernote('code', markupStr);
</script>