
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

<table class="table table-condensed table-hover">
	<thead>
		<tr>
			<th class ="text-center hidden">ID</th>
			<th class ="text-center bg-complete text-white font-s12">Nick</th>
			<th class ="text-center bg-complete text-white font-s12" style="width: 5%">Piso</th>
			<th class ="text-center bg-complete text-white font-s12" style="width: 5%">Parking</th>
			<th class ="text-center bg-complete text-white font-s12" style="width: 5%">Taquilla</th>
			<th class ="text-center bg-complete text-white font-s12" style="width: 6%">Ocu min</th>
			<th class ="text-center bg-complete text-white font-s12" style="width: 6%">Ocu max</th>
			<th class ="text-center bg-complete text-white font-s12" style="width: 8%">Tamaño</th>
			<th class ="text-center bg-complete text-white font-s12" style="width: 5%">Lujo</th>                        
			<th class ="text-center bg-complete text-white font-s12" style="width: 8%">Tipo</th>
			<th class ="text-center bg-complete text-white font-s12" style="width: 10%">Prop</th>
			<th class ="text-center bg-complete text-white font-s12" style="width: 5%">Orden</th>
			<th class ="text-center bg-complete text-white font-s12" style="width: 5%">Estado</th>
			<th class ="text-center bg-complete text-white font-s12" style="width: 5%">Booking</th>
			<th class ="text-center bg-complete text-white font-s12">Acc</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($rooms as $room): ?>
			<tr>
				<td class="text-center padding-room hidden"><?php echo $room->id?></td>
				<td class="text-center padding-room">
					<input class="name name-<?php echo $room->name?>" type="text" name="name" data-id="<?php echo $room->id ?>" value="<?php echo $room->name?>" style="width: 100%;text-align: center;border-style: none none ">
				</td>
				<td class="text-center padding-room">
					<input class="nameRoom nameRoom-<?php echo $room->nameRoom?>" type="text" name="nameRoom" data-id="<?php echo $room->id ?>" value="<?php echo $room->nameRoom?>" style="width: 100%;text-align: center;border-style: none none ">
				</td>
				<td class="text-center padding-room">
					<input class="parking parking-<?php echo $room->parking?>" type="text" name="parking" data-id="<?php echo $room->id ?>" value="<?php echo $room->parking?>" style="width: 100%;text-align: center;border-style: none none ">
				</td>
				<td class="text-center padding-room">
					<input class="taquilla taquilla-<?php echo $room->locker?>" type="text" name="taquilla" data-id="<?php echo $room->id ?>" value="<?php echo $room->locker?>" style="width: 100%;text-align: center;border-style: none none ">
				</td>
				<td class="text-center padding-room">
					<input class="editable minOcu-<?php echo $room->id?>" type="text" name="cost" data-id="<?php echo $room->id ?>" value="<?php echo $room->minOcu?>" style="width: 100%;text-align: center;border-style: none none ">
				</td>  
				<td class="text-center padding-room">
					<input class="editable maxOcu-<?php echo $room->id?>" type="text" name="cost" data-id="<?php echo $room->id ?>" value="<?php echo $room->maxOcu?>" style="width: 100%;text-align: center;border-style: none none">
				</td> 
				<td class="text-center padding-room">
					<select class="sizes form-control minimal" data-id="<?php echo $room->id ?>">
						<?php foreach ($sizes as $size): ?>                                   
							<option value="<?php echo $size->id; ?>" <?php echo ($size->id == $room->sizeApto) ? "selected" : "" ?>>
								<?php echo $size->name ?>
							</option>
						<?php endforeach ?>
					</select>
				</td>
				<td class="text-center padding-room">
					<span class="input-group-addon bg-transparent">
						<input type="checkbox" class="editable" data-id="<?php echo $room->id ?>" name="luxury" data-init-plugin="switchery" data-size="small" data-color="primary" <?php echo ($room->luxury == 0) ? "" : "checked" ?>/>
					</span>

				</td> 
				<td class="text-center padding-room">
					<select class="type form-control minimal" data-id="<?php echo $room->id ?>">
						<?php foreach ($tipos as $tipo): ?>
							<?php if( $tipo->id == $room->typeApto ){ $selected = "selected"; }else{$selected = "";} ?>
							<option value="<?php echo $tipo->id; ?>" <?php echo $selected ?> >
								<?php echo $tipo->name ?>
							</option>
						<?php endforeach ?>
					</select>
				</td>
				<td class="text-center padding-room">
					<select class="owned form-control minimal" data-id="<?php echo $room->id ?>">
						<?php foreach (\App\User::all() as $key => $owned): ?>
							<?php if ( ($owned->role == 'propietario') || $owned->name == 'jorge'): ?>
								<?php if( $owned->name == $room->user->name ){ $selected = "selected"; }else{$selected = "";} ?>
								<option value="<?php echo $owned->id; ?>" <?php echo $selected ?> >
									<?php echo $owned->name ?>
								</option>
							<?php endif ?>

						<?php endforeach ?>
						<?php// echo $room->user->name;?>
					</select>
				</td> 
				<td class="text-center padding-room">
					<p style="display: none"><?php echo $room->order ?></p>
					<input class="orden order-<?php echo $room->id?>" type="text" name="orden" data-id="<?php echo $room->id ?>" value="<?php echo $room->order?>" style="width: 100%;text-align: center;border-style: none none">
				</td>             
				<td class="text-center padding-room">
					<span class="input-group-addon bg-transparent">
						<input type="checkbox" class="estado" data-id="<?php echo $room->id ?>" name="state" data-init-plugin="switchery" data-size="small" data-color="success" <?php echo ($room->state == 0) ? "" : "checked" ?>> 
					</span>
				</td>
				<td class="text-center padding-room">
					<span class="input-group-addon bg-transparent">
						<input type="checkbox" class="assingToBooking" data-id="<?php echo $room->id ?>" name="assingToBooking" data-init-plugin="switchery" data-size="small" data-color="danger" <?php echo ( $room->isAssingToBooking() ) ? "checked" : "" ?>> 
					</span>
				</td>
				<td class="text-center">
					<a type="button" class="btn btn-default" href="{{ url ('/fotos') }}/<?php echo $room->nameRoom ?>" target="_blank" data-original-title="Enlace de Apartamento" data-toggle="tooltip">
						<i class="fa fa-paperclip"></i>
					</a>

					<button class="btn btn-default btn-emiling" type="button" data-toggle="modal" data-target="#modalEmailing" data-id="<?php echo $room->user->id ?>">
						<i class=" pg-mail"></i>
					</button>

					<button type="button" class="btn btn-success uploadFile" data-toggle="modal" data-target="#modalFiles" data-id="<?php echo $room->nameRoom ?>" title="Subir imagenes aptos">
						<i class="fa fa-upload" aria-hidden="true"></i>
					</button>                    
				</td>
			</tr>
		<?php endforeach ?>
		<?php foreach ($roomsdesc as $room): ?>
			<tr>
				<td class="text-center padding-room hidden"><?php echo $room->id?></td>
				<td class="text-center padding-room">
					<input class="name name-<?php echo $room->name?>" type="text" name="name" data-id="<?php echo $room->id ?>" value="<?php echo $room->name?>" style="width: 100%;text-align: center;border-style: none none ">
				</td>
				<td class="text-center padding-room">
					<input class="nameRoom nameRoom-<?php echo $room->nameRoom?>" type="text" name="nameRoom" data-id="<?php echo $room->id ?>" value="<?php echo $room->nameRoom?>" style="width: 100%;text-align: center;border-style: none none ">
				</td>
				<td class="text-center padding-room">
					<input class="parking parking-<?php echo $room->parking?>" type="text" name="parking" data-id="<?php echo $room->id ?>" value="<?php echo $room->parking?>" style="width: 100%;text-align: center;border-style: none none ">
				</td>
				<td class="text-center padding-room">
					<input class="taquilla taquilla-<?php echo $room->locker?>" type="text" name="taquilla" data-id="<?php echo $room->id ?>" value="<?php echo $room->locker?>" style="width: 100%;text-align: center;border-style: none none ">
				</td>
				<td class="text-center padding-room">
					<input class="editable minOcu-<?php echo $room->id?>" type="text" name="cost" data-id="<?php echo $room->id ?>" value="<?php echo $room->minOcu?>" style="width: 100%;text-align: center;border-style: none none ">
				</td>  
				<td class="text-center padding-room">
					<input class="editable maxOcu-<?php echo $room->id?>" type="text" name="cost" data-id="<?php echo $room->id ?>" value="<?php echo $room->maxOcu?>" style="width: 100%;text-align: center;border-style: none none">
				</td> 
				<td class="text-center padding-room">
					<select class="sizes form-control minimal" data-id="<?php echo $room->id ?>">
						<?php foreach ($sizes as $size): ?>                                   
							<option value="<?php echo $size->id; ?>" <?php echo ($size->id == $room->sizeApto) ? "selected" : "" ?>>
								<?php echo $size->name ?>
							</option>
						<?php endforeach ?>
					</select>
				</td>
				<td class="text-center padding-room">
					<span class="input-group-addon bg-transparent">
						<input type="checkbox" class="editable" data-id="<?php echo $room->id ?>" name="luxury" data-init-plugin="switchery" data-size="small" data-color="primary" <?php echo ($room->luxury == 0) ? "" : "checked" ?>/>
					</span>

				</td> 
				<td class="text-center padding-room">
					<select class="type form-control minimal" data-id="<?php echo $room->id ?>">
						<?php foreach ($tipos as $tipo): ?>
							<?php if( $tipo->id == $room->typeApto ){ $selected = "selected"; }else{$selected = "";} ?>
							<option value="<?php echo $tipo->id; ?>" <?php echo $selected ?> >
								<?php echo $tipo->name ?>
							</option>
						<?php endforeach ?>
					</select>
				</td>
				<td class="text-center padding-room">
					<select class="owned form-control minimal" data-id="<?php echo $room->id ?>">
						<?php foreach (\App\User::all() as $key => $owned): ?>
							<?php if ( ($owned->role == 'propietario') || $owned->name == 'jorge'): ?>
								<?php if( $owned->name == $room->user->name ){ $selected = "selected"; }else{$selected = "";} ?>
								<option value="<?php echo $owned->id; ?>" <?php echo $selected ?> >
									<?php echo $owned->name ?>
								</option>
							<?php endif ?>

						<?php endforeach ?>
						<?php// echo $room->user->name;?>
					</select>
				</td> 
				<td class="text-center padding-room">
					<p style="display: none"><?php echo $room->order ?></p>
					<input class="orden order-<?php echo $room->id?>" type="text" name="orden" data-id="<?php echo $room->id ?>" value="<?php echo $room->order?>" style="width: 100%;text-align: center;border-style: none none">
				</td>             
				<td class="text-center padding-room">
					<span class="input-group-addon bg-transparent">
						<input type="checkbox" class="estado" data-id="<?php echo $room->id ?>" name="state" data-init-plugin="switchery" data-size="small" data-color="success" <?php echo ($room->state == 0) ? "" : "checked" ?>> 
					</span>
				</td>
				<td class="text-center padding-room">
					<span class="input-group-addon bg-transparent">
						<input type="checkbox" class="assingToBooking" data-id="<?php echo $room->id ?>" name="assingToBooking" data-init-plugin="switchery" data-size="small" data-color="danger" <?php echo ( $room->isAssingToBooking() ) ? "checked" : "" ?>> 
					</span>
				</td>
				<td class="text-center">
					<a type="button" class="btn btn-default" href="{{ url ('/fotos') }}/<?php echo $room->nameRoom ?>" target="_blank" data-original-title="Enlace de Apartamento" data-toggle="tooltip">
						<i class="fa fa-paperclip"></i>
					</a>

					<button class="btn btn-default btn-emiling" type="button" data-toggle="modal" data-target="#modalEmailing" data-id="<?php echo $room->user->id ?>">
						<i class=" pg-mail"></i>
					</button>

					<button type="button" class="btn btn-success uploadFile" data-toggle="modal" data-target="#modalFiles" data-id="<?php echo $room->nameRoom ?>" title="Subir imagenes aptos">
						<i class="fa fa-upload" aria-hidden="true"></i>
					</button>                    
				</td>
			</tr>
		<?php endforeach ?>
	</tbody>
</table>
<?php if(isset($show) && !empty($show)): ?>
<script src="/assets/plugins/jquery/jquery-1.11.1.min.js" type="text/javascript"></script>
<script src="/assets/plugins/switchery/js/switchery.min.js" type="text/javascript"></script>
<script src="/pages/js/pages.min.js"></script>
<?php endif; ?>