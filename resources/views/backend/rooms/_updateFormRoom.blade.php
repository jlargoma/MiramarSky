<div class="col-xs-12 push-20">
	<h3 class="text-center font-w300">
		Datos de <span class="font-w800"><?php echo $room->name ?> (<?php echo $room->nameRoom ?>)</span>
	</h3>
</div>
<div class="row">
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
				<input type="number" name="parking" class="form-control" value="<?php echo $room->parking; ?>"/>
			</div>
			<div class="col-md-1 col-xs-4 push-20">
				<label for="locker">Taquilla</label>
				<input type="number" name="locker" class="form-control" value="<?php echo $room->locker; ?>"/>
			</div>
			<div class="col-md-1 col-xs-4 push-20">
				<label for="minOcu">Ocu. Min</label>
				<input type="number" name="minOcu" class="form-control" value="<?php echo $room->minOcu; ?>"/>
			</div>
			<div class="col-md-1 col-xs-4 push-20">
				<label for="maxOcu">Ocu. Max</label>
				<input type="number" name="maxOcu" class="form-control" value="<?php echo $room->maxOcu; ?>"/>
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
		</div>
		<div class="row">
			<div class="col-xs-12 col-md-12">
				<div class="col-md-1 col-xs-12 push-20"></div>

				<div class="col-md-2 col-xs-12 push-20">
					<label for="owned">Prop.</label>
					<select class="form-control minimal" name="owned">
						<?php foreach (\App\User::all() as $key => $owned): ?>
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
			</div>
		</div>
		<div class="col-xs-12 text-center push-20">
			<button class="btn btn-success btn-cons" type="submit">
                <span class="bold">GUARDAR</span>
            </button>
		</div>
	</form>
</div>