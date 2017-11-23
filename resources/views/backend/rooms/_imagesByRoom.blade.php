<div class="row">
	<div class="col-md-3 col-xs-12 pull-right">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
		    <i class="pg-close fs-14" style="color: black!important"></i>
		</button>
	</div>
	<div class="col-xs-12">
		<?php foreach ($images as $key => $image): ?>
			<div class="col-md-3 col-xs-6 push-10">
				<!--  -->
				<img src="{{ asset('/img/miramarski/apartamentos/'.$room->nameRoom.'/thumbnails/'.$image->getFilename()) }}" class="img-responsive" style="height: 300px">
			</div>
		<?php endforeach ?>
	</div>
</div>
