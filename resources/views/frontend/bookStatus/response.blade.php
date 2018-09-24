<div id="loading-book" style="display:none; position: absolute;width: 100%;top: 0;padding-top: 150px;height: 100%;z-index: 99;background-color: rgba(63, 81, 181, 0.5);">
	<div class="col-padding">
		<div class="heading-block center nobottomborder nobottommargin">
			<div class="fbox-icon white">
				<i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i>
			</div>
		</div>
	</div>
</div>
<div class="col-xs-12" id="content-response">
	<div class="col-xs-12">
		<h2 class="white text-center" style="text-transform: uppercase;">Solicita tu reserva</h2>
	</div>
	<div class="col-xs-12 col-md-12 not-padding">
				
		<div class="row push-10">
			<span class="white push-10 font-s18 font-w300 pull-left">Nombre:</span>
			<span class="font-w800 white center push-10 font-s18 pull-right"><?php echo ucfirst($name) ?></span>
		</div>
		<div class="row push-10">
			<span class="white push-10 font-s18 font-w300 pull-left">Numº Pers:</span>
			<span class="font-w800 white center push-10 font-s18 pull-right">
				<?php echo $pax ?> <?php if ($pax == 1 ): ?>Per<?php else: ?>Pers <?php endif ?>	
			</span>
		</div>

		<div class="row push-10">
			<span class="white push-10 font-s18 font-w300 pull-left">Apartamento:</span>
			<span class="font-w800 white center push-10 font-s18 font-w300 pull-right"><?php echo $apto ?></span>
		</div>
		<div class="row push-10">
			<span class="white push-10 font-s18 font-w300 pull-left">Noches:</span>
			<span class="white center push-10 font-s18 font-w300 pull-right"><span class="font-w800"><?php echo $nigths ?></span> Noches</span>
		</div>
		<div class="row push-10">
			<span class="white push-10 font-s18 font-w300 pull-left">Fechas:</span> 
			<span class="white push-10 font-s18 font-w300 pull-right"><b><?php echo $start->copy()->format('d-M') ?> - <?php echo $finish->copy()->format('d-M') ?></b></span>
		</div>
		<div class="row push-10">
			<span class="white push-10 font-s18 font-w300 pull-left">Sup. Lujo:<?php if($luxury > 0): ?>(SI)<?php else: ?>(NO)<?php endif; ?></span>
			<span class="white center push-10 font-s18 font-w300 pull-right"><span class="font-w800"><?php echo number_format($luxury,0,'','.')?>€</span></span>
		</div>
		
	</div>
	<div class="line" style="margin-bottom: 10px;"></div>

	<div class="form-group col-sm-12 col-xs-12 col-md-12 text-center">
		<p class="white push-10 font-s18 font-w300 text-center" style="line-height: 1">
			Precio total de la solicitud de reserva<br> <span class="font-w800" style="font-size: 48px;"><?php echo number_format($total ,0,'','.') ?>€</span>
		</p>
		<div class="row">
			<div class="col-md-7 col-xs-7 not-padding-mobile">
				<form method="post" action="{{url('/admin/reservas/create')}}" id="confirm-book">
		    		<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
		    		<input type="hidden" name="newroom" value="<?php echo $id_apto; ?>">
		    		<input type="hidden" name="name" value="<?php echo $name; ?>">
		    		<input type="hidden" name="email" value="<?php echo $email; ?>">
		    		<input type="hidden" name="phone" value="<?php echo $phone; ?>">
		    		<input type="hidden" name="fechas" value="<?php echo $start->copy()->format('d M, y') ?> - <?php echo $finish->copy()->format('d M, y') ?>">
		    		<input type="hidden" name="pax" value="<?php echo $pax; ?>">
		    		<input type="hidden" name="nigths" value="<?php echo $nigths; ?>">
		    		<input type="hidden" name="comments" value="<?php echo $comment; ?>">
		    		<input type="hidden" name="from" value="frontend">
		    		<input type="hidden" name="parking" value="<?php echo $parking; ?>">
		    		<input type="hidden" name="agencia" value="0">
		    		<input type="hidden" name="lujo" value="<?php echo $luxury ?>">
		    		<input type="hidden" name="dni" value="<?php echo $dni ?>">
		    		<input type="hidden" name="address" value="<?php echo $address ?>">
		    		<input type="hidden" name="book_comments" value="">
					<?php if($luxury > 0): ?>
						<input type="hidden" name="type_luxury" value="1">
					<?php else: ?>
						<input type="hidden" name="type_luxury" value="2">
					<?php endif; ?>
					<button type="submit" class="button button-rounded button-reveal button-large button-green tright center hvr-grow-shadow font-s16" style="letter-spacing: 1px;"><i class="icon-angle-right"></i><span style=" font-size: 16px">SOLICITAR</span></button>
				</form>
			</div>
	        <div class="col-md-5 col-xs-5">
	        	<button onclick="unflip()" class="button button-rounded button-reveal button-large button-red tright center hvr-grow-shadow "><i class="icon-angle-right"></i><span>volver</span></button>
	        </div>
    	</div>
		<div class="row content-alert-min-special-days"></div>
    </div>
</div>
<script type="text/javascript">
	function showLoad() {
		$('#loading-book').show();
	}

	function hideLoad() {
		$('#loading-book').hide();
	}



	$('#confirm-book').submit(function(event) {

		event.preventDefault();
		showLoad();


		var _token        = $('input[name="_token"]').val();
		var newroom       = $('input[name="newroom"]').val();
		var name          = $('input[name="name"]').val();
		var email         = $('input[name="email"]').val();
		var phone         = $('input[name="phone"]').val();
		var fechas        = $('input[name="fechas"]').val();
		var pax           = $('input[name="pax"]').val();
		var nigths        = $('input[name="nigths"]').val();
		var comments      = $('input[name="comments"]').val();
		var from          = $('input[name="from"]').val();
		var parking       = $('input[name="parking"]').val();
		var agencia       = $('input[name="agencia"]').val();
		var agency        = 0;
		var book_comments = $('input[name="book_comments"]').val();
		var lujo 		  = $('input[name="lujo"]').val();
		var type_luxury 		  = $('input[name="type_luxury"]').val();

		var url = $(this).attr('action');

		$.post( url , {
				_token : _token,
				newroom : newroom,
				name : name,
				email : email,
				phone : phone,
				fechas : fechas,
				pax : pax,
				nigths : nigths,
				comments : comments,
				from : from,
				parking : <?php echo $parking; ?>,
				agencia : agencia,
				agency : agency,
				book_comments : book_comments,
				Suplujo : lujo,
				type_luxury : type_luxury,
			}, function(data) {
		hideLoad();
		$('#content-response').empty().append(data).fadeIn('300');
				
		});

	});
</script>