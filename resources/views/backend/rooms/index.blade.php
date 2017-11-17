<?php 
	use \App\Classes\Mobile;
	$mobile = new Mobile();
?>
@extends('layouts.admin-master')

@section('title') Administrador de reservas MiramarSKI @endsection

@section('externalScripts') 

	<style type="text/css">
		.name-back{
			background-color: rgba(72,176,247,0.5)!important;
		}
		.name-back input{
			background-color: transparent;
			color: black;
			font-weight: 800;
		}
		.ocupation-back{
			background-color: rgba(72,176,247,0.5)!important;
		}
		.ocupation-back input{
			background-color: transparent;
			color: black;
			font-weight: 800;
		}
	</style>
@endsection

@section('content')

<?php if (!$mobile->isMobile()): ?>
	<div class="container-fluid padding-25 sm-padding-10 table-responsive">
		<div class="row">
			<div class="col-md-12 text-center">
				<h2>LISTADO DE <span class="font-w800">APARTAMENTOS</span></h2>
			</div>
			<div class="col-md-2 col-xs-12 push-20">
				<input type="text" id="searchRoomByName" class="form-control" placeholder="Buscar..." />
			</div>
			<div class="col-md-1 col-xs-4 push-20">
				<button class="btn btn-success btn-cons" type="button" data-toggle="modal" data-target="#modalNewSize">
	                <i class="fa fa-plus-square" aria-hidden="true"></i> <span class="bold">Crear tamaño</span>
	            </button>
			</div>
			<div class="col-md-1 col-xs-4 push-20">
				<button class="btn btn-success btn-cons" type="button" data-toggle="modal" data-target="#modalNewTypeApto">
	                <i class="fa fa-plus-square" aria-hidden="true"></i> <span class="bold">Tipo Apto.</span>
	            </button>
			</div>
			<div class="col-md-1 col-xs-4 push-20">
				<button class="btn btn-success btn-cons" type="button" data-toggle="modal" data-target="#modalNewApto">
	                <i class="fa fa-plus-square" aria-hidden="true"></i> <span class="bold">Nuevo Apto.</span>
	            </button>
			</div>
			<div class="clearfix"></div>
			<div class="row">
				<div class="col-md-5 col-xs-12 content-table-rooms" style="max-height: 680px; overflow-y: auto;">
					@include('backend.rooms._tableRooms', ['rooms' => $rooms, 'roomsdesc' => $roomsdesc])
				</div>
				<div class="col-md-7 col-xs-12">
					<div class="col-md-12 col-xs-12 push-20">
						<div class="row contentUpdateForm" style="border: 2px dashed black;">
							<div class="col-xs-12" style="">
								<h2 class="text-center">
									HAZ CLIC PARA CARGAR LOS DATOS DE UN APTO.
								</h2>
							</div>
						</div>
					</div>
					<div class="col-md-5 col-xs-12">
					    <div class="row">

				        	<div class="col-xs-12 col-md-12 push-20">
				        		<h3 class="text-center">
				        			Reparto de Beneficios por tipo
				        		</h3>
				        	</div>
					        		
				        	<div class="col-md-12 col-xs-12">
				        		<table class="table table-condensed table-hover">
									<thead>
										<th class="text-center bg-complete text-white font-s12">Tipo</th>
										<th class="text-center bg-complete text-white font-s12">% Jorge</th>
										<th class="text-center bg-complete text-white font-s12">% Jaime</th>
									</thead>
									<tbody>
										<?php foreach ($typesApto as $typeApto): ?>
											<?php if ( $typeApto->name != 'Propio'): ?>											
												<tr>
													<td><?php echo $typeApto->name ?></td>
													<td>
														<input class="percentage percentJorge-<?php echo $typeApto->id?>" type="text" name="Jorge" data-id="<?php echo $typeApto->id ?>" value="<?php echo $typeApto->PercentJorge?>" style="width: 100%;text-align: center;border-style: none none ">
													</td>
													<td>
														<input class="percentage percentJaime-<?php echo $typeApto->id?>" type="text" name="Jaime" data-id="<?php echo $typeApto->id ?>" value="<?php echo $typeApto->PercentJaime?>" style="width: 100%;text-align: center;border-style: none none ">
													</td>
												</tr>
											<?php endif ?>
										<?php endforeach ?>
									</tbody>
				        		</table>
				        	</div>
					    </div>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php else: ?>
	


<?php endif ?>


	<div class="modal fade slide-up in" id="modalFiles" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-xs">
			<div class="modal-content-wrapper">
				<div class="modal-content">
					<div class="block">
						<div class="block-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14" style="font-size: 40px!important;color: black!important"></i>
							</button>
							<h2 class="text-center">
								Subida de archivos
							</h2>
						</div>

						<div class="container-xs-height full-height">
							<div class="row-xs-height">
								<div class="modal-body col-xs-height col-middle text-center   ">
									<div class="upload-body">
									</div>
								</div>
							</div>
						</div>

					</div>


				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>

	<div class="modal fade slide-up in" id="modalEmailing" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content-wrapper">
				<div class="modal-content emailing"></div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>

	<div class="modal fade slide-up in" id="modalNewSize" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content-wrapper">
				<div class="modal-content">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="position: absolute; top: 0px; right: 10px; z-index: 100;">
			            <i class="pg-close fs-20" ></i>
			        </button>
					<div class="panel-body">
						<div class="panel panel-default" style="margin-top: 15px;">
							<div class="panel-heading">
								<div class="panel-title col-md-12">Tamaño de  Apartamento
								</div>
							</div>
							<div class="panel-body">
								<div class="col-md-6">
									<form role="form"  action="{{ url('/admin/apartamentos/create-size') }}" method="post">
										<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
										<div class="input-group transparent">
											<span class="input-group-addon">
												<i class="fa fa-user"></i>
											</span>
											<input type="text" class="form-control" name="name" placeholder="nombre" required="" aria-required="true" aria-invalid="false">
										</div>
										<br>
										<div class="input-group">
											<button class="btn btn-complete" type="submit">Guardar</button>
										</div>
									</form>
								</div>
								<div class="col-md-6">
									<?php foreach ($sizes as $size): ?>
										<?php echo $size->name ?><br>
									<?php endforeach ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>

	<div class="modal fade slide-up in" id="modalNewTypeApto" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content-wrapper">
				<div class="modal-content">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="position: absolute; top: 0px; right: 10px; z-index: 100;">
			            <i class="pg-close fs-20" ></i>
			        </button>
					<div class="panel-body">
						<div class="panel panel-default" style="margin-top: 15px;">
							<div class="panel-heading">
								<div class="panel-title col-md-12">Tipo de  Apartamento
								</div>
							</div>
							<div class="panel-body">
								<div class="col-md-6">
									<form role="form"  action="{{ url('/admin/apartamentos/create-type') }}" method="post">
										<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
										<div class="input-group transparent">
											<span class="input-group-addon">
												<i class="fa fa-user"></i>
											</span>
											<input type="text" class="form-control" name="name" placeholder="nombre" required="" aria-required="true" aria-invalid="false">
										</div>
										<br>
										<div class="input-group">
											<button class="btn btn-complete" type="submit">Guardar</button>
										</div>
									</form>
								</div>
								<div class="col-md-6">
									<?php foreach ($types as $type): ?>
										<?php echo $type->name ?><br>
									<?php endforeach ?>
								</div>
							</div>
						</div>
					</div> 
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>

	<div class="modal fade slide-up in" id="modalNewApto" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content-wrapper">
				<div class="modal-content">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="position: absolute; top: 0px; right: 10px; z-index: 100;">
			            <i class="pg-close fs-20" ></i>
			        </button>
					<div class="panel-body">
						<div class="panel panel-default" style="margin-top: 15px;">
							<div class="panel-heading">
								<div class="panel-title col-md-12">
									Agregar Apartamento
								</div>
							</div>
							<form role="form"  action="{{ url('/admin/apartamentos/create') }}" method="post">
								<div class="panel-body">
									<div class="col-md-12">
										<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
										<div>
											<div class="input-group transparent">
												<span class="input-group-addon">
													<i class="fa fa-user"></i>
												</span>
												<input type="text" class="form-control" name="name" placeholder="Nick" required="" aria-required="true" aria-invalid="false">
											</div>
											<br>
											<div class="input-group transparent">
												<span class="input-group-addon">
													<i class="pg-home"></i>
												</span>
												<input type="text" class="form-control" name="nameRoom" placeholder="Piso" required="" aria-required="true" aria-invalid="false">
											</div>
											<br>
											<div class="input-group transparent">
												<div class="col-md-6">
													<span class="input-group-addon">
														<i class="pg-minus_circle"></i>
													</span>
													<input type="text" class="form-control" name="minOcu" placeholder="Minima ocupacion" required="" aria-required="true" aria-invalid="false">
												</div>
												<div class="col-md-6">
													<span class="input-group-addon">
														<i class="pg-plus_circle"></i>
													</span>
													<input type="text" class="form-control" name="maxOcu" placeholder="Maxima ocupacion" required="" aria-required="true" aria-invalid="false">
												</div>
											</div>
											<br>
											<div class="input-group transparent" style="width: 45%">

											</div>
											<br>
											<div class="input-group">
												<span class="input-group-addon">
													Propietario
												</span>
												<select class="form-control minimal" name="owner">
													<option></option>
													<?php foreach (\App\User::whereIn('role',['admin', 'subadmin', 'propietario'])->get() as $owner): ?>
														<option value="<?php echo $owner->id ?>"><?php echo $owner->name ?></option>
													<?php endforeach ?>
												</select>
											</div>
											<br>
											<div class="input-group">
												<span class="input-group-addon">
													Tipo de apartamento
												</span>
												<select class="form-control minimal" name="type">
													<option></option>
													<?php foreach ($types as $type): ?>
														<option value="<?php echo $type->id ?>"><?php echo $type->name ?></option>
													<?php endforeach ?>
												</select>
											</div>
											<br>
											<div class="input-group">
												<span class="input-group-addon">
													Tamaño de apartamento
												</span>
												<select class="form-control minimal" name="sizeRoom">
													<option></option>
													<?php foreach ($sizes as $size): ?>
														<option value="<?php echo $size->id ?>"><?php echo $size->name ?></option>
													<?php endforeach ?>
												</select>
											</div>
											<br>
											<div class="input-group">
												<label class="inline">Lujo</label>
												<input type="checkbox" name="luxury" data-init-plugin="switchery" data-size="small" data-color="primary" checked="checked" />
											</div>   
										</div>
										<br>
										<div class="input-group">
											<button class="btn btn-complete" type="submit">Guardar</button>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>

	<div class="modal fade slide-up in" id="modalPercentApto" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content-wrapper">						
				<div class="modal-content">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="position: absolute; top: 0px; right: 10px; z-index: 100;">
			            <i class="pg-close fs-20" ></i>
			        </button>
					<div class="percent-body">
						
					</div>
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	
	<form role="form">
	    <div class="form-group form-group-default required" style="display: none">
	        <label class="highlight">Message</label>
	        <input type="text" hidden="" class="form-control notification-message" placeholder="Type your message here" value="This notification looks so perfect!" required>
	    </div>
	    <button class="btn btn-success show-notification hidden" id="boton">Show</button>
	</form>
	

@endsection

@section('scripts')

<script src="/assets/js/notifications.js" type="text/javascript"></script>

<script type="text/javascript">

	$(document).ready(function() {

		$('.btn-emiling').click(function(event) {
			var id = $(this).attr('data-id');
			$('.modal-content.emailing').empty().load('/admin/apartamentos/email/'+id);
		});

		$('.percentage').change(function(event) {

			
			var id = $(this).attr('data-id');
			var tipo = $(this).attr('name');
			var percent = $(this).val();

			$.get('/admin/apartamentos/update-Percent', {  id: id, tipo: tipo, percent: percent}, function(data) {
				$('.notification-message').val(data);
				$("#boton").click();
	            setTimeout(function(){ 
	                $('.alert-info .close').trigger('click');
	                 }, 1500); 

			});

		});

		$('.aptos').click(function(event) {
			var id = $(this).attr('data-id');

			$.get('/admin/rooms/getUpdateForm', {id: id}, function(data) {
				$('.contentUpdateForm').empty().append(data)
			});
		});

	});
</script>
@endsection