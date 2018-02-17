<div class="col-xs-12 push-20">
	<h3 class="text-center font-w300">
		Datos de <span class="font-w800"><?php echo $room->name ?> (<?php echo $room->nameRoom ?>)</span>
	</h3>
</div>
<div class="row push-20">
	<form class="form" action="{{ url('admin/apartamentos/saveupdate') }}" method="post">
		<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
		<input type="hidden" name="id" value="<?php echo $room->id; ?>">
		<div class="row">
			<div class="col-md-1 col-xs-12 push-20">
			</div>
			<div class="col-md-2 col-xs-12 push-20">
				<label for="name">Nombre</label>
				<input type="text" name="name" class="form-control" value="<?php echo $room->name; ?>"/>
			</div>

			<div class="col-md-1 col-xs-4 push-20">
				<label for="nameRoom">piso</label>
				<input type="text" name="nameRoom" class="form-control" value="<?php echo $room->nameRoom; ?>"/>
			</div>
			<div class="col-md-1 col-xs-4 push-20">
				<label for="parking">Parking</label>
				<input type="text" name="parking" class="form-control only-numbers" value="<?php echo $room->parking; ?>"/>
			</div>
			<div class="col-md-1 col-xs-4 push-20">
				<label for="locker">Taquilla</label>
				<input type="text" name="locker" class="form-control only-numbers" value="<?php echo $room->locker; ?>"/>
			</div>
			<div class="col-md-2 col-xs-12 push-20">
				<label for="sizeApto">Tamaño Apto.</label>
				<select class="form-control minimal" name="sizeApto">
					<?php foreach (\App\SizeRooms::all() as $size): ?>                                   
						<option value="<?php echo $size->id; ?>" <?php echo ($size->id == $room->sizeApto) ? "selected" : "" ?>>
							<?php echo $size->name ?>
						</option>
					<?php endforeach ?>
				</select>
			</div>
			<div class="col-md-1 col-xs-4 push-20">
				<label for="minOcu">Ocu. Min</label>
				<input type="number" name="minOcu" class="form-control" value="<?php echo $room->minOcu; ?>"/>
			</div>
			<div class="col-md-1 col-xs-4 push-20">
				<label for="maxOcu">Ocu. Max</label>
				<input type="number" name="maxOcu" class="form-control" value="<?php echo $room->maxOcu; ?>"/>
			</div>
			
		</div>
		<div class="row">
			<div class="col-xs-12 col-md-12">
				<div class="col-md-1 col-xs-12 push-20"></div>

				<div class="col-md-2 col-xs-12 push-20">
					<label for="owned">Prop.</label>
					<select class="form-control minimal" name="owned">
						<?php $owneds = \App\User::whereIn('role', ['propietario', 'admin'])->whereNotIn('id', [58,59,63])->get() ?>
						<?php foreach ($owneds as $key => $owned): ?>
							<?php if ( ($owned->role == 'propietario') || $owned->name == 'jorge'): ?>
								<?php if( $owned->name == $room->user->name ){ $selected = "selected"; }else{$selected = "";} ?>
								<option value="<?php echo $owned->id; ?>" <?php echo $selected ?> >
									<?php echo $owned->name ?>
								</option>
							<?php endif ?>
						<?php endforeach ?>
					</select>
				</div>
				<div class="col-md-2 col-xs-12 push-20">
					<label for="type">T. Apto.</label>

					<select class=" form-control minimal" name="type">
						<?php foreach (\App\TypeApto::all() as $tipo): ?>
							<?php if( $tipo->id == $room->typeApto ){ $selected = "selected"; }else{$selected = "";} ?>
							<option value="<?php echo $tipo->id; ?>" <?php echo $selected ?> >
								<?php echo $tipo->name ?>
							</option>
						<?php endforeach ?>
					</select>
				</div>
				<div class="col-md-2 col-xs-12 push-20">
					<label for="minOcu">% Min Benef</label>
					<input type="number" name="profit_percent" class="form-control" value="<?php echo $room->profit_percent; ?>"/>
				</div>
							
			</div>
		</div>
		<div class="col-xs-12 text-center push-20">
			<button class="btn btn-success btn-cons" type="submit">
                <span class="bold">GUARDAR</span>
            </button>
		</div>
	</form>
</div>
<!-- FORMULARIO DE IMPORTACION DE ICAL POR ROOM -->
<div class="row" style="border-top: 2px dashed #000;">
	<div class="col-md-6 col-xs-12" style="padding: 0 15px">
		<h3 class="text-left font-w300">
			Importar ICAL
		</h3>
		<form class="form" action="{{ url('ical/import/saveUrl') }}" method="post" id="icalForm">
			<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
			<input type="hidden" name="id" value="<?php echo $room->id; ?>">
			<div class="col-md-6 col-xs-12 push-20">
				<input type="url" name="urlIcal" class="form-control" placeholder="url para importar..." required>
			</div>
			<div class="col-md-3 col-xs-12 push-20 text-left">
				<button class="btn btn-primary btn-cons" type="submit">
	                <span class="bold">AÑADIR URL A IMPORTAR</span>
	            </button>
			</div>
		</form>
	</div>
	<div class="col-md-6 col-xs-12" style="padding: 0 15px" >
		<div class="col-md-6 col-xs-12 " style="    padding: 20px 15px;">
			<label for="type">Link Expor. cal. Apto</label>
			<p><?php echo route("import-iCalendar", ["aptoID" => $room->id])?></p>
			<button class="btn btn-cons" type="button" id="copy-link-export" data-link="<?php echo route("import-iCalendar", ["aptoID" => $room->id])?>">
                <span class="bold">Copiar Link</span>
            </button>					
		</div>	
		
		
	</div>
</div>
<!-- FIN FORMULARIO DE IMPORTACION DE ICAL POR ROOM -->


<script type="text/javascript">
	$(".only-numbers").keydown(function (e) {
       // Allow: backspace, delete, tab, escape, enter and .
       if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190, 188]) !== -1 ||
            // Allow: Ctrl/cmd+A
           (e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
            // Allow: Ctrl/cmd+C
           (e.keyCode == 67 && (e.ctrlKey === true || e.metaKey === true)) ||
            // Allow: Ctrl/cmd+X
           (e.keyCode == 88 && (e.ctrlKey === true || e.metaKey === true)) ||
            // Allow: home, end, left, right
           (e.keyCode >= 35 && e.keyCode <= 39)) {
                // let it happen, don't do anything
                return;
       }
       // Ensure that it is a number and stop the keypress
       if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
           e.preventDefault();
       }
   });
	$(document).on("click","#copy-link-export", function(){
		var link = $(this).data("link");
		var $temp = $("<input>");
		$("body").append($temp);
		$temp.val(link).select();
		document.execCommand("copy");
		$temp.remove();
	});

	$('#icalForm').submit(function(event) {
		event.preventDefault();
		var _token = $('input[name="_token"]').val();
		var idRoom = $('input[name="id"]').val();
		var urlIcal    = $('input[name="urlIcal"]').val();

		var url    = $(this).attr('action');
		$.post( url , { _token: _token, idRoom: idRoom, urlIcal: urlIcal }, function(data) {
			$('#listUrl').empty().load('/ical/urls/getAllUrl/'+idRoom);
			$('input[name="urlIcal"]').val('');
		});
		
	});

	


</script>
