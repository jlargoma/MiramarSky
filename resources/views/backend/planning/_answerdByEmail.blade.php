<link href="/assets/plugins/summernote/css/summernote.css" rel="stylesheet" type="text/css" media="screen">
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
		<form  action="{{ url('/admin/reservas/sendEmail') }}" method="post" id="form-email">
			<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
			<input type="hidden" class="id" name="id" value="<?php echo $book->id; ?>">
	        <div class="summernote-wrapper" style="margin-bottom: 30px;">
	          <div id="summernote">
				Hola <b><?php echo $book->customer->name ?></b> <b>Si tenemos disponibilidad para tu reserva </b><br><br>

				Nombre: <b><?php echo $book->customer->name ?></b><br>
				Teléfono: <b><?php echo $book->customer->phone ?></b><br>
				Email: <b><?php echo $book->customer->email ?></b><br>
				Apartamento: <b><?php echo $book->room->sizeRooms->name ?> // <?php echo ($book->type_luxury == 1)? "Lujo" : "Estandar" ?></b><br>
				Fechas: <b><?php echo Carbon::CreateFromFormat('Y-m-d',$book->start)->formatLocalized('%d %b') ?> - <?php echo Carbon::CreateFromFormat('Y-m-d',$book->finish)->formatLocalized('%d %b') ?></b><br>
				Noches: <b><?php echo $book->nigths; ?></b><br>
				Ocupantes: <b><?php echo $book->pax; ?></b><br>
				Suplemento Lujo: <?php if ($book->sup_lujo > 0): ?><b>(SI)</b> <?php echo $book->sup_lujo ?>€ <?php else: ?><b>(NO)</b> 0<?php endif ?><br>
				Suplemento Parking: <?php if ($book->sup_park > 0): ?><b>(SI)</b> <?php echo $book->sup_park ?>€ <?php else: ?><b>(NO)</b> 0<?php endif ?><br>

				<b><span style="font-size: 32px;">Precio total: <?php echo number_format($book->total_price,2,',','.') ?>€</span></b><br><br>

				El precio te incluye, parking cubierto, piscina climatizada, gimnasio, taquilla guarda esquíes, sabanas y toallas. <br>
				Posteriormente a tu contratación <b>te ofrecemos descuentos para la compra de tus forfaits, en cursillos de esquí o alquiler de material</b>.<br><br>

				Quedamos a la espera de tu respuesta.<br>
				Un cordial saludo
	          </div>
	        </div>
	        <div class="wrapper" style="text-align: center;">
	        	<button type="submit" class="btn btn-lg btn-success"><i class="fa fa-paper-plane-o" aria-hidden="true"></i> Contestar</button>
	        </div>
	    </form>
	</div>
</div>

<script src="/assets/plugins/pace/pace.min.js" type="text/javascript"></script>
<script src="/assets/plugins/jquery/jquery-1.11.1.min.js" type="text/javascript"></script>
<script src="/assets/plugins/modernizr.custom.js" type="text/javascript"></script>
<script src="/assets/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="/assets/plugins/bootstrapv3/js/bootstrap.min.js" type="text/javascript"></script>
<script src="/assets/plugins/jquery/jquery-easy.js" type="text/javascript"></script>
<script src="/assets/plugins/jquery-unveil/jquery.unveil.min.js" type="text/javascript"></script>
<script src="/assets/plugins/jquery-bez/jquery.bez.min.js"></script>
<script src="/assets/plugins/jquery-ios-list/jquery.ioslist.min.js" type="text/javascript"></script>
<script src="/assets/plugins/jquery-actual/jquery.actual.min.js"></script>
<script src="/assets/plugins/jquery-scrollbar/jquery.scrollbar.min.js"></script>
<script type="text/javascript" src="/assets/plugins/select2/js/select2.full.min.js"></script>
<script type="text/javascript" src="/assets/plugins/classie/classie.js"></script>
<script src="/assets/plugins/switchery/js/switchery.min.js" type="text/javascript"></script>
<script src="/assets/plugins/bootstrap3-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<script type="text/javascript" src="/assets/plugins/jquery-autonumeric/autoNumeric.js"></script>
<script type="text/javascript" src="/assets/plugins/dropzone/dropzone.min.js"></script>
<script type="text/javascript" src="/assets/plugins/bootstrap-tag/bootstrap-tagsinput.min.js"></script>
<script type="text/javascript" src="/assets/plugins/jquery-inputmask/jquery.inputmask.min.js"></script>
<script src="/assets/plugins/bootstrap-form-wizard/js/jquery.bootstrap.wizard.min.js" type="text/javascript"></script>
<script src="/assets/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="/assets/plugins/summernote/js/summernote.min.js" type="text/javascript"></script>
<script src="/assets/plugins/moment/moment.min.js"></script>
<script src="/assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="/assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
<script src="/assets/plugins/bootstrap-typehead/typeahead.bundle.min.js"></script>
<script src="/assets/plugins/bootstrap-typehead/typeahead.jquery.min.js"></script>
<script src="/assets/plugins/handlebars/handlebars-v4.0.5.js"></script>
<!-- END VENDOR JS -->
<!-- BEGIN CORE TEMPLATE JS -->
<script src="/pages/js/pages.min.js"></script>
<!-- END CORE TEMPLATE JS -->
<!-- BEGIN PAGE LEVEL JS -->
<script src="/assets/js/form_elements.js" type="text/javascript"></script>
<script src="/assets/js/scripts.js" type="text/javascript"></script>
<script type="text/javascript">
	$('#form-email').submit(function(event) {
		event.preventDefault();

		var formURL   = $(this).attr("action");
		var token     = $('input[name="_token"]').val();
		var id        = $('.id').val();
		var textEmail = $('.note-editable').text();
		var type      = 1;


		$.post(formURL, {_token: token, textEmail: textEmail, id: id, type: type}, function(data) {
			if (data == 1) {
				$('.pg-close').trigger('click');
			} else {
				alert('Error al guardar estado');
			}
			
		});
	});
</script>