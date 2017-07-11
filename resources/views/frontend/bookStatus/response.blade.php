<div class="col-xs-12" id="content-response">
	<div class="col-xs-12">
		<h2 class="white text-center" style="text-transform: uppercase;">Solicita tu reserva</h2>
	</div>
	<div class="col-xs-12 col-md-12">
		<h3 class="white" style="text-transform: uppercase;"">Revisa los datos de tu reserva</h3>
		<div class="col-md-6">
			<p class="white push-10 font-s18 font-w300">Tipo de apartamento: <span class="font-w800"><?php echo $apto ?></span></p>
			<p class="white push-10 font-s18 font-w300">Email de contacto: <span class="font-w800"><?php echo $email ?></span></p>
			<p class="white push-10 font-s18 font-w300">Noches: <span class="font-w800"><?php echo $nigths ?></span></p>
			<p class="white push-10 font-s18 font-w300">Suplemento lujo: <span class="font-w800"><?php echo $luxury ?>€</span></p>
		</div>
		<div class="col-md-6">
			<p class="white push-10 font-s18 font-w300">A nombre de: <span class="font-w800"><?php echo $name ?></span></p>
			<p class="white push-10 font-s18 font-w300">Entrada / Salida: <span class="font-w800"><?php echo $start->copy()->format('d-m-Y') ?> - <?php echo $finish->copy()->format('d-m-Y') ?></span></p>
			<p class="white push-10 font-s18 font-w300">Suplemento parking: <span class="font-w800"><?php echo $priceParking ?>€</span></p>
		</div>
		
		<div class="col-md-12">
			<p class="white push-10 font-s18 font-w300">Comentarios: <span class="font-w800"><?php echo $comment ?></span></p>
		</div>
		
	</div>

	<div class="form-group col-sm-12 col-xs-12 col-md-12 text-center">
		<p class="white push-10 font-s18 font-w300 text-center">
			Precio total de la solicitud de reserva<br> <span class="font-w800" style="font-size: 48px;"><?php echo $total ?>€</span>
		</p>
		<div class="col-md-6 col-xs-12">
			<form method="post" action="{{url('/admin/reservas/create')}}" id="confirm-book">
	    		<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
	    		<input type="hidden" name="newroom" value="<?php echo $id_apto; ?>">
	    		<input type="hidden" name="name" value="<?php echo $name; ?>">
	    		<input type="hidden" name="email" value="<?php echo $email; ?>">
	    		<input type="hidden" name="phone" value="<?php echo $phone; ?>">
	    		<input type="hidden" name="start" value="<?php echo $start->copy()->format('d/m/Y') ?>">
	    		<input type="hidden" name="finish" value="<?php echo $finish->copy()->format('d/m/Y') ?>">
	    		<input type="hidden" name="pax" value="<?php echo $pax; ?>">
	    		<input type="hidden" name="nigths" value="<?php echo $nigths; ?>">
	    		<input type="hidden" name="comments" value="<?php echo $comment; ?>">
	    		<input type="hidden" name="from" value="frontend">
	    		<input type="hidden" name="parking" value="<?php echo $parking; ?>">
	    		<input type="hidden" name="agencia" value="0">
	    		<input type="hidden" name="book_comments" value="">
				
				<button type="submit" class="button button-3d button-xlarge button-rounded button-green white button-light" style="line-height: 1;letter-spacing: 1px;">Confirmar<br>reserva</button>
			</form>
		</div>
        <div class="col-md-6 col-xs-12">
        	<a href="{{ url('/') }}" class="button button-3d button-xlarge button-rounded button-red white button-light">Cancelar</a>
        </div>
    </div>
</div>
<script type="text/javascript">
	$('#confirm-book').submit(function(event) {

		event.preventDefault();

		var _token        = $('input[name="_token"]').val();
		var newroom       = $('input[name="newroom"]').val();
		var name          = $('input[name="name"]').val();
		var email         = $('input[name="email"]').val();
		var phone         = $('input[name="phone"]').val();
		var start         = $('input[name="start"]').val();
		var finish        = $('input[name="finish"]').val();
		var pax           = $('input[name="pax"]').val();
		var nigths        = $('input[name="nigths"]').val();
		var comments      = $('input[name="comments"]').val();
		var from          = $('input[name="from"]').val();
		var parking       = $('input[name="parking"]').val();
		var agencia       = $('input[name="agencia"]').val();
		var book_comments = $('input[name="book_comments"]').val();

		var url = $(this).attr('action');

		$.post( url , {
						_token : _token,
						newroom : newroom,
						name : name,
						email : email,
						phone : phone,
						start : start,
						finish : finish,
						pax : pax,
						nigths : nigths,
						comments : comments,
						from : from,
						parking : parking,
						agencia : agencia,
						book_comments : book_comments
					}, function(data) {

				$('#content-response').empty().append(data);
		});

	});
</script>