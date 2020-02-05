<?php use \App\Classes\Mobile;
	$mobile = new Mobile(); ?>
<style>
	.fileUpload {
		position: relative;
		overflow: hidden;
		margin: 10px;
	}
	.fileUpload input.upload {
		position: absolute;
		top: 0;
		right: 0;
		margin: 0;
		padding: 0;
		font-size: 20px;
		cursor: pointer;
		opacity: 0;
		filter: alpha(opacity=0);
	}
	.btn-file input[type=file] {
		position: absolute;
		top: 0;
		right: 0;
		min-width: 100%;
		min-height: 100%;
		font-size: 100px;
		text-align: right;
		filter: alpha(opacity=0);
		opacity: 0;
		outline: none;
		background: white;
		cursor: inherit;
		display: block;
	}
	input{
		min-height: 35px!important;
	}
	.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th{
		vertical-align: middle;
	}
	.padding-room{
		padding: 5px 10px!important;
	}
	.input-group-addon.bg-transparent{
		border: none;
	}
</style>
<div class="table-responsive">
	<table class="table ">
		<thead>
			<tr>
				<th class ="text-center bg-complete text-white font-s12" style="width:7px">#</th>
				<th class ="text-center bg-complete text-white font-s12" style="min-width: 20%;">APTO</th>
				<th class ="text-center bg-complete text-white font-s12" style="min-width: 10px">Lujo</th>
				<th class ="text-center bg-complete text-white font-s12" style="min-width: 10px">Estado</th>
				<th class ="text-center bg-complete text-white font-s12" style="min-width: 10px">Booking</th>
				<th class ="text-center bg-complete text-white font-s12" style="min-width: 80px">Acc</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($rooms as $room): ?>
				<tr>
					<td class="text-center" >
						<input class="orden order-<?php echo $room->id?>" type="number" name="orden" data-id="<?php echo $room->id ?>" value="<?php echo $room->order?>" style="width: 100%;text-align: center;border-style: none none">
					</td>
					<td class="text-left" >
						<a class="aptos" data-id="<?php echo $room->id?>" style="cursor: pointer;">
							<?php echo $room->name?> (<?php echo $room->nameRoom?>)
						</a>
					</td>
					<td class="text-center" >
						<span class="input-group-addon bg-transparent">
							<input type="checkbox" class="editable" data-id="<?php echo $room->id ?>" name="luxury" data-init-plugin="switchery" data-size="small" data-color="primary" <?php echo ($room->luxury == 0) ? "" : "checked" ?>/>
						</span>
					</td>
					<td class="text-center" >
						<span class="input-group-addon bg-transparent">
							<input type="checkbox" class="estado" data-id="<?php echo $room->id ?>" name="state" data-init-plugin="switchery" data-size="small" data-color="success" <?php echo ($room->state == 0) ? "" : "checked" ?>> 
						</span>
					</td>
					<td class="text-center" >
						<span class="input-group-addon bg-transparent">
							<input type="checkbox" class="assingToBooking" data-id="<?php echo $room->id ?>" name="assingToBooking" data-init-plugin="switchery" data-size="small" data-color="danger" <?php echo ( $room->isAssingToBooking() ) ? "checked" : "" ?>> 
						</span>
					</td>
					<td class="text-center" style="min-width: 80px">
                                          @if($room->user)
						<a class="btn btn-default btn-pdf btn-sm" href="{{ url
							('/admin/apartamentos/download/contrato/'.$room->user->id) }}">
							<i class="fa fa-file-pdf"></i>
						</a>
                                          @endif
						<a type="button" class="btn btn-default btn-sm" href="{{ url ('/fotos') }}/<?php echo $room->nameRoom ?>" target="_blank" data-original-title="Enlace de Apartamento" data-toggle="tooltip">
							<i class="fa fa-paperclip"></i>
						</a>
 @if($room->user)
						<button class="btn btn-default btn-emiling btn-sm" type="button" data-toggle="modal" data-target="#modalEmailing" data-id="<?php echo $room->user->id ?>">
							<i class=" pg-mail"></i>
						</button>
@endif
						<button type="button" class="btn btn-success btn-sm uploadFile" data-toggle="modal" data-target="#modalFiles" data-id="<?php echo $room->nameRoom ?>" title="Subir imagenes aptos">
							<i class="fa fa-upload" aria-hidden="true"></i>
						</button>    
                                          <button type="button" class="btn btn-success btn-sm editAptoText" data-toggle="modal" data-target="#modalTexts" data-id="{{$room->id}}" title="Editar textos aptos">
                                                          <i class="fa fa-pencil" aria-hidden="true"></i>
                                                        </button>
                                          <button type="button" class="btn btn-success btn-sm uploadHeader" data-toggle="modal" data-target="#modalHeaders" data-id="<?php echo $room->id ?>" title="Subir imagenes cabeceras aptos">
							<i class="fa fa-upload" aria-hidden="true"></i>
						</button>  
					</td>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>
</div>

<script type="text/javascript">

    $('.uploadFile').click(function(event) {
      var id = $(this).attr('data-id');
      $.get('/admin/apartamentos/fotos/'+id, function(data) {
        $('.upload-body').empty().append(data);
      });
    });

 $('.uploadHeader').click(function(event) {
    var id = $(this).attr('data-id');
    $.get('/admin/apartamentos/headers/room/'+id, function(data) {
      $('.upload-body').empty().append(data);
    });
  });
	$('.editable').change(function(event) {
		var id = $(this).attr('data-id');
		var luxury = $(this).is(':checked');

		if (luxury == true) {
			luxury = 1;
		}else{
			luxury = 0;
		}

		var minOcu = $('.minOcu-'+id).val();
		var maxOcu = $('.maxOcu-'+id).val();

		$.get('/admin/apartamentos/update', {  id: id, luxury: luxury, maxOcu: maxOcu, minOcu: minOcu}, function(data) {
			// $('.content-table-rooms').empty().load('/admin/apartamentos/rooms/getTableRooms');
			alert('cambiado')
		});
	});

	$('.estado').change(function(event) {
		var id = $(this).attr('data-id');
		var state = $(this).is(':checked');

		if (state == true) {
			state = 1;
		}else{
			state = 0;
		}

		$.get('/admin/apartamentos/state', {  id: id, state: state}, function(data) {
			if (data == 0) {
				alert('No se puede cambiar')
				// $('.content-table-rooms').empty().load('/admin/apartamentos/rooms/getTableRooms');
			}else{
				alert('cambiado')
				// $('.content-table-rooms').empty().load('/admin/apartamentos/rooms/getTableRooms');
			}
		});
	});

	$('.assingToBooking').change(function(event) {
		var id = $(this).attr('data-id');
		var assing = $(this).is(':checked');

		if (assing == true) {
			assing = 1;
		}else{
			assing = 0;
		}

		$.get('/admin/apartamentos/assingToBooking', {  id: id, assing: assing}, function(data) {

			alert(data);
			// $('.content-table-rooms').empty().load('/admin/apartamentos/rooms/getTableRooms');
		});
	});

	
	$('.orden').change(function(event) {
		var id = $(this).attr('data-id');
		var orden = $(this).val();

		$.get('/admin/apartamentos/update-order', {  id: id, orden: orden}, function(data) {
			$('.content-table-rooms').empty().load('/admin/apartamentos/rooms/getTableRooms');
		});

	});
</script>
<?php if(isset($show) && !empty($show)): ?>
<script type="text/javascript">
	$('.btn-emiling').click(function(event) {
		var id = $(this).attr('data-id');
		$('.modal-content.emailing').empty().load('/admin/apartamentos/email/'+id);
	});
	<?php if (!$mobile->isMobile()): ?>
		$('.aptos').click(function(event) {
			var id = $(this).attr('data-id');

			$.get('/admin/rooms/getUpdateForm', {id: id}, function(data) {
				$('.contentUpdateForm').empty().append(data)
			});
		});
	<?php else: ?>
		$('.aptos').click(function(event) {
			var id = $(this).attr('data-id');

			$.get('/admin/rooms/getUpdateForm', {id: id}, function(data) {
				$('.contentUpdateForm').empty().append(data);
				$('html,body').animate({
				        scrollTop: $(".contentUpdateForm").offset().top},
		        'slow');
			});
		});
	<?php endif ?>
</script>
<script src="/assets/plugins/switchery/js/switchery.min.js" type="text/javascript"></script>
<script src="/pages/js/pages.min.js"></script>
<?php endif; ?>