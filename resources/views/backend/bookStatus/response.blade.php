<div class="col-xs-12">
	<div class="col-xs-12 col-md-12">
				
		<div class="row push-10">
			<span class="push-10 font-s18 black font-w300 pull-left">Nombre:</span>
			<span class="font-w800 center push-10 font-s18 black pull-right"><?php echo ucfirst($name) ?></span>
		</div>
		<div class="row push-10">
			<span class="push-10 font-s18 black font-w300 pull-left">Numº Pers:</span>
			<span class="font-w800 center push-10 font-s18 black pull-right">
				<?php echo $pax ?> <?php if ($pax == 1 ): ?>Per<?php else: ?>Pers <?php endif ?>	
			</span>
		</div>

		<div class="row push-10">
			<span class="push-10 font-s18 black font-w300 pull-left">Apartamento:</span>
			<span class="font-w800 center push-10 font-s18 black font-w300 pull-right"><?php echo $apto ?></span>
		</div>
		<div class="row push-10">
			<span class="push-10 font-s18 black font-w300 pull-left">Noches:</span>
			<span class="center push-10 font-s18 black font-w300 pull-right"><span class="font-w800"><?php echo $nigths ?></span> Noches</span>
		</div>
		<div class="row push-10">
			<span class="push-10 font-s18 black font-w300 pull-left">Fechas:</span> 
			<span class="push-10 font-s18 black font-w300 pull-right"><b><?php echo $start->copy()->format('d-M') ?> - <?php echo $finish->copy()->format('d-M') ?></b></span>
		</div>
		<div class="row push-10">
			<span class="push-10 font-s18 black font-w300 pull-left">Sup. Lujo:<?php if($luxury > 0): ?>(SI)<?php else: ?>(NO)<?php endif; ?></span>
			<span class="center push-10 font-s18 black font-w300 pull-right"><span class="font-w800"><?php echo number_format($luxury,0,'','.')?>€</span></span>
		</div>
		<!-- <div class="row push-10">
			<span class="push-10 font-s18 black font-w300 pull-left">Parking:<?php if($priceParking > 0): ?>(SI)<?php else: ?>(NO)<?php endif; ?></span>
			<span class="center push-10 font-s18 black font-w300 pull-right"><span class="font-w800"><?php echo number_format($priceParking,0,'','.')?>€</span></span>
		</div> -->
	</div>
	<div class="line" style="margin-bottom: 10px;"></div>

	<div class="form-group col-sm-12 col-xs-12 col-md-12 text-center">
		<p class="push-20 font-s18 font-w300 text-center black">
			Precio total de la solicitud de reserva<br><br>
			<span class="font-w800 black" style="font-size: 48px;"><?php echo number_format($total ,0,'','.') ?>€</span>
		</p>
		<div class="col-md-7 col-xs-6 text-center">
			<form method="post" action="{{url('/admin/reservas/create')}}" id="confirm-book">
	    		<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
	    		<input type="hidden" name="newroom" value="<?php echo $id_apto; ?>">
	    		<input type="hidden" name="name" value="<?php echo $name; ?>">
	    		<input type="hidden" name="email" value="<?php echo $email; ?>">
	    		<input type="hidden" name="phone" value="<?php echo $phone; ?>">
	    		<input type="hidden" name="fechas" value="<?php echo $start->copy()->format('d M, y') ?> - <?php echo $finish->copy()->format('d M, y') ?>">
	    		<input type="hidden" name="pax" value="<?php echo $pax; ?>">
	    		<input type="hidden" name="nigths" value="<?php echo $nigths; ?>">
	    		<input type="hidden" name="parking" value="<?php echo $parking; ?>">
	    		<input type="hidden" name="agencia" value="0">
	    		<input type="hidden" name="lujo" value="<?php echo $luxury ?>">
	    		<input type="hidden" name="total" value="<?php echo $total ?>">
	    		<input type="hidden" name="book_comments" value="">
				<?php if($luxury > 0): ?>
					<input type="hidden" name="type_luxury" value="1">
				<?php else: ?>
					<input type="hidden" name="type_luxury" value="2">
				<?php endif; ?>
				<button type="submit" class="btn btn-success text-white btn-lg btn-cons center hvr-grow-shadow ">RESERVAR</button>
			</form>
		</div>
        <div class="col-md-5 col-xs-6 text-center">
        	<button class="btn btn-danger btn-lg btn-cons  text-white center hvr-grow-shadow btn-back-calculate">
        		VOLVER
        	</button>
        </div>
    </div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		 $('.btn-back-calculate').click(function(event) {
            $('#content-book-response .back').empty();
            $("#content-book-response .back").hide();
            $("#content-book-response .front").show();
        });
	});
</script>