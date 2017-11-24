<div class="row loading-emailImages text-center" style="position: absolute; top: 50%; left: 50%; z-index: 1010;display: none;">
	<i class="fa fa-spinner fa-5x fa-spin text-black" aria-hidden="true"></i><br>
	<h2 class="text-center text-black">ENVIANDO</h2>
</div>
<div class="row sended-emailImages text-center" style="position: absolute; top: 50%; left: 50%; z-index: 1010;display: none;">
	<i class="fa fa-check-circle-o text-black" aria-hidden="true"></i><br>
	<h2 class="text-center text-black">ENVIADO</h2>
</div>
<div class="row content-loading">
	<div class="col-md-3 col-xs-12 pull-right">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
		    <i class="pg-close fs-14" style="color: black!important"></i>
		</button>
	</div>
	<div class="col-xs-12">
		<div class="col-md-4">
			<div class="col-xs-12">
				<h2 class="text-left font-w300" style="margin: 0;">
					ENVIAR POR <span class="font-w800">EMAIL</span>: 
				</h2>
				
			</div>
			<div class="col-xs-10 push-20">
				<input type="email" id="shareEmailImages" class="form-control minimal" placeholder="Email...">
			</div>
			<div class="col-xs-2 push-20">
				<button class="btn btn-primary" id="sendShareImagesEmail">
					<i class="fa fa-envelope"></i> Enviar
				</button>
			</div>
		</div>
	</div>
	<div class="col-xs-12">
		<?php foreach ($images as $key => $image): ?>
			<div class="col-md-2 col-xs-12 push-10">
				<!--  -->
				<img src="{{ asset('/img/miramarski/apartamentos/'.$room->nameRoom.'/thumbnails/'.$image->getFilename()) }}" class="img-responsive" style="height: 200px">
			</div>
		<?php endforeach ?>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$('#sendShareImagesEmail').click(function(event) {
			$(".content-loading").css({ opacity: 0.5 });
			$('.loading-emailImages').show();
			var email = $('#shareEmailImages').val();
			var roomId = <?php echo $room->id; ?>

			$.get('/sendImagesRoomEmail', {email: email, roomId: roomId,}, function(data) {
				$('.loading-emailImages').hide();
				$('.sended-emailImages').show();
				$(".content-loading").css({ opacity: 1 });
				$('#shareEmailImages').val('');
				$('.sended-emailImages').hide();
			});
		});
	});
</script>