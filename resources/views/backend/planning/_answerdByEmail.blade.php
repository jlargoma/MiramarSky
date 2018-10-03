<?php if (!$mobile->isMobile()): ?> 
	<link href="/assets/plugins/summernote/css/summernote.css" rel="stylesheet" type="text/css" media="screen">
<?php endif; ?>
<?php 	use \Carbon\Carbon;
		setlocale(LC_TIME, "ES"); 
        setlocale(LC_TIME, "es_ES"); 
 ?>
 <style>
 	.note-editor, .note-editable{
 	    min-height: 500px!important;
 	}
 	.dropdown-toggle > i{
 		font-size: 10px!important;
 	}
 </style>
<div class="modal-content">
	<div class="modal-header clearfix text-left">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14" style="font-size: 40px!important;color: black!important"></i>
		</button>
		<h5>Mensaje para <span class="semi-bold"><?php echo $book->customer->name ?></span></h5>
	</div>
	<div class="modal-body">
		<div class="loading" style="display: none;  position: absolute;top: 0;width: 100%;background-color: rgba(255,255,255,0.6);z-index: 15;min-height: 600px;left: 0;padding: 210px 0;">
			<div class="col-xs-12 text-center sending" style="display: none;">
				<i class="fa fa-spinner fa-5x fa-spin" aria-hidden="true"></i><br>
				<h2 class="text-center">ENVIANDO</h2>
			</div>

			<div class="col-xs-12 text-center sended" style="display: none;">
				<i class="fa fa-check-circle-o text-black" aria-hidden="true"></i><br>
				<h2 class="text-center">ENVIADO</h2>
			</div>
		</div>
		<form  action="{{ url('/admin/reservas/sendEmail') }}" method="post" id="form-email">
			<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
			<input type="hidden" class="id" name="id" value="<?php echo $book->id; ?>">
			
		        <div class="summernote-wrapper" style="margin-bottom: 30px;">
		          	<?php if ($book->room->nameRoom != "CHLT"): ?>
						<textarea class="form-control note-editable" name="text" style="width: 100%;">Hola <?php echo $book->customer->name ?><?php echo "\n" ?><?php echo "\n" ?>Si tenemos disponibilidad para tu reserva  <?php echo "\n" ?><?php echo "\n" ?>Nombre: <?php echo $book->customer->name ?><?php echo "\n" ?><?php echo "\n" ?>Teléfono: <?php echo $book->customer->phone ?><?php echo "\n" ?><?php echo "\n" ?>Email: <?php echo $book->customer->email ?><?php echo "\n" ?><?php echo "\n" ?>Apartamento: <?php echo $book->room->sizeRooms->name ?> // <?php echo ($book->type_luxury == 1)? "Lujo" : "Estandar" ?><?php echo "\n" ?>Fechas: <?php echo Carbon::CreateFromFormat('Y-m-d',$book->start)->formatLocalized('%d %b') ?> - <?php echo Carbon::CreateFromFormat('Y-m-d',$book->finish)->formatLocalized('%d %b') ?><?php echo "\n" ?><?php echo "\n" ?>Noches: <?php echo $book->nigths; ?><?php echo "\n" ?><?php echo "\n" ?>Ocupantes: <?php echo $book->pax; ?><?php echo "\n" ?><?php echo "\n" ?><?php if ($book->sup_lujo > 0): ?>Suplemento Lujo: (SI) <?php echo $book->sup_lujo ?>€ <?php echo "\n" ?><?php echo "\n" ?><?php endif ?>Precio total: <?php echo number_format($book->total_price,2,',','.') ?>€<?php echo "\n" ?><?php echo "\n" ?>El precio te incluye, una plaza de parking cubierto (dos plazas en los aptos. gran ocupación), piscina climatizada, gimnasio, taquilla guarda esquíes, sabanas y toallas. <?php echo "\n" ?><?php echo "\n" ?>SERVICIOS ADICIONALES<?php echo "\n" ?><?php echo "\n" ?>Te ofrecemos un descuento especial que hemos pactado con el proveedor para vosotros en:<?php echo "\n" ?><?php echo "\n" ?>- Forfait <?php echo "\n" ?>- Clases de esquí<?php echo "\n" ?>- Alquiler de material<?php echo "\n" ?><?php echo "\n" ?>Para solicitar alguno de estos servicios solo es necesario que rellenes un formulario entrando en https://www.apartamentosierranevada.net/forfait/ <?php echo "\n" ?><?php echo "\n" ?>Para tu comodidad te llevamos el forfait a tu apartamento, no tienes que esperar colas<?php echo "\n" ?><?php echo "\n" ?>Gracias por confiarnos tus vacaciones, haremos todo lo posible para que pases unos días agradables. <?php echo "\n" ?>
							www.apartamentosierranevada.net<?php echo "\n" ?><?php echo "\n" ?>Quedamos a la espera de tu respuesta.
		          		</textarea>
		          	<?php else: ?>
		          		<textarea class="form-control note-editable" name="text" style="width: 100%;">Hola <?php echo $book->customer->name ?> Si tenemos disponibilidad para tu reserva  <?php echo "\n" ?><?php echo "\n" ?>Nombre: <?php echo $book->customer->name ?><?php echo "\n" ?>Teléfono: <?php echo $book->customer->phone ?><?php echo "\n" ?>Email: <?php echo $book->customer->email ?><?php echo "\n" ?>Apartamento: <?php echo $book->room->sizeRooms->name ?> // <?php echo ($book->type_luxury == 1)? "Lujo" : "Estandar" ?><?php echo "\n" ?>Fechas: <?php echo Carbon::CreateFromFormat('Y-m-d',$book->start)->formatLocalized('%d %b') ?> - <?php echo Carbon::CreateFromFormat('Y-m-d',$book->finish)->formatLocalized('%d %b') ?><?php echo "\n" ?>Noches: <?php echo $book->nigths; ?><?php echo "\n" ?>Ocupantes: <?php echo $book->pax; ?><?php echo "\n" ?><?php if ($book->sup_lujo > 0): ?>Suplemento Lujo: (SI) <?php echo $book->sup_lujo ?>€ <?php else: ?> <?php endif ?><?php echo "\n" ?><?php echo "\n" ?><?php echo "\n" ?>Precio total: <?php echo number_format($book->total_price,2,',','.') ?>€<?php echo "\n" ?><?php echo "\n" ?>El precio te incluye,una plaza de parking cubierto,  taquilla guarda esquíes, sabanas y toallas. <?php echo "\n" ?><?php echo "\n" ?>Puedes hacer toda la gestión de los fortfaits, cursillos de esquí o alquiler de material con nosotros.<?php echo "\n" ?><?php echo "\n" ?>Te los llevamos a la puerta de tu apartamento (olvidate de hacer colas)<?php echo "\n" ?><?php echo "\n" ?>Tambien te llevamos la cesta de la compra desde el supermercado a tu apartamento, más comodo imposible.<?php echo "\n" ?><?php echo "\n" ?>Quedamos a la espera de tu respuesta.
		          		</textarea>
		          	<?php endif ?>
		        </div>
	        
	        <div class="wrapper push-20" style="text-align: center;">
	        	<button type="submit" class="btn btn-lg btn-success"><i class="fa fa-paper-plane-o" aria-hidden="true"></i> Contestar</button>
	        </div>
	    </form>
	</div>
</div>

<script src="/assets/plugins/pace/pace.min.js" type="text/javascript"></script>
<script src="/assets/plugins/jquery/jquery-1.11.1.min.js" type="text/javascript"></script>

<script type="text/javascript">

	function sending(){
		$('.loading').show();
		$('.loading .sending').show();
	}

	function sended(){
		$('.loading .sending').hide();
		$('.loading .sendend').show();
	}


	$('#form-email').submit(function(event) {
		event.preventDefault();

		sending();

		var formURL   = $(this).attr("action");
		var token     = $('input[name="_token"]').val();
		var id        = $('.id').val();
		var textEmail = $('.note-editable').val();
		var type      = 1;

		$.post(formURL, {_token: token, textEmail: textEmail, id: id, type: type}, function(data) {
			if (data == 1) {
				sended();
				var type = $('.table-data').attr('data-type');
		        var year = $('#fecha').val();
		        $.get('/admin/reservas/api/getTableData', { type: type, year: year }, function(data) {
		    
		            $('.content-tables').empty().append(data);

		        });
				$('.close').trigger('click');

			} else {
				alert('Error al guardar estado');
			}
			
		});
	});
</script>