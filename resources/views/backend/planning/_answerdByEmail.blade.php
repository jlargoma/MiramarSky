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
		          	<textarea class="form-control note-editable" style="width: 100%;">Hola <?php echo $book->customer->name ?> Si tenemos disponibilidad para tu reserva  <?php echo "\n" ?><?php echo "\n" ?>Nombre: <?php echo $book->customer->name ?><?php echo "\n" ?>Teléfono: <?php echo $book->customer->phone ?><?php echo "\n" ?>Email: <?php echo $book->customer->email ?><?php echo "\n" ?>Apartamento: <?php echo $book->room->sizeRooms->name ?> // <?php echo ($book->type_luxury == 1)? "Lujo" : "Estandar" ?><?php echo "\n" ?>Fechas: <?php echo Carbon::CreateFromFormat('Y-m-d',$book->start)->formatLocalized('%d %b') ?> - <?php echo Carbon::CreateFromFormat('Y-m-d',$book->finish)->formatLocalized('%d %b') ?><?php echo "\n" ?>Noches: <?php echo $book->nigths; ?><?php echo "\n" ?>Ocupantes: <?php echo $book->pax; ?><?php echo "\n" ?>Suplemento Lujo: <?php if ($book->sup_lujo > 0): ?>(SI) <?php echo $book->sup_lujo ?>€ <?php else: ?>(NO) 0<?php endif ?><?php echo "\n" ?>Suplemento Parking: <?php if ($book->sup_park > 0): ?>(SI) <?php echo $book->sup_park ?>€ <?php else: ?>(NO) 0<?php endif ?><?php echo "\n" ?><?php echo "\n" ?>Precio total: <?php echo number_format($book->total_price,2,',','.') ?>€<?php echo "\n" ?><?php echo "\n" ?>El precio te incluye, parking cubierto, piscina climatizada, gimnasio, taquilla guarda esquíes, sabanas y toallas. <?php echo "\n" ?>Posteriormente a tu contratación te ofrecemos descuentos para la compra de tus forfaits, en cursillos de esquí o alquiler de material.<?php echo "\n" ?><?php echo "\n" ?>Quedamos a la espera de tu respuesta.<?php echo "\n" ?>Un cordial saludo
		          	</textarea>
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
		var textEmail = $('.note-editable').html();
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