<div class="col-xs-12">
	<div class="col-xs-12">
		<h2 class="white text-center" style="text-transform: uppercase;">Solicita tu reserva</h2>
	</div>
	<div class="col-xs-12 col-md-6">
		<h3 class="white" style="text-transform: uppercase;"">Revisa los datos de tu reserva</h3>
		<p class="white push-10 font-s18 font-w300">Tipo de apartamento: <span class="font-w800"><?php echo $apto ?></span></p>
		<p class="white push-10 font-s18 font-w300">A nombre de: <span class="font-w800"><?php echo $name ?></span></p>
		<p class="white push-10 font-s18 font-w300">Email de contacto:: <span class="font-w800"><?php echo $email ?></span></p>
		<p class="white push-10 font-s18 font-w300">Entrada / Salida: <span class="font-w800"><?php echo $start->copy()->format('d-m-Y') ?> - <?php echo $finish->copy()->format('d-m-Y') ?></span></p>
		<p class="white push-10 font-s18 font-w300">Noches: <span class="font-w800"><?php echo $nigths ?></span></p>
		<p class="white push-10 font-s18 font-w300">Suplemento parking: <span class="font-w800"><?php echo $parking ?>€</span></p>
		<p class="white push-10 font-s18 font-w300">Suplemento lujo: <span class="font-w800"><?php echo $luxury ?>€</span></p>
		<p class="white push-10 font-s18 font-w300">Comentarios: <span class="font-w800"><?php echo $comment ?></span></p>
		
	</div>

	<div class="form-group col-sm-12 col-xs-12 col-md-6 text-center">
		<p class="white push-10 font-s18 font-w300 text-center">
			Precio total de la solicitud de reserva<br> <span class="font-w800" style="font-size: 48px;"><?php echo $total ?>€</span>
		</p>

		<form method="post" action="{{url('/admin/reservas/create')}}">
    		<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
    		<input type="hidden" name="newroom" value="<?php echo $id_apto; ?>">
    		<input type="hidden" name="name" value="<?php echo $name; ?>">
    		<input type="hidden" name="start" value="<?php echo $start->copy()->format('d/m/Y') ?>">
    		<input type="hidden" name="finish" value="<?php echo $finish->copy()->format('d/m/Y') ?>">
    		<input type="hidden" name="pax" value="<?php echo $pax; ?>">
    		<input type="hidden" name="nigths" value="<?php echo $nigths; ?>">
    		<input type="hidden" name="comments" value="<?php echo $comment; ?>">
    		<input type="hidden" name="agencia" value="">
    		<input type="hidden" name="book_comments" value="">
			
			<button type="submit" class="button button-3d button-xlarge button-rounded button-white button-light">Confirmar reserva</button>
		</form>
        
    </div>
</div>