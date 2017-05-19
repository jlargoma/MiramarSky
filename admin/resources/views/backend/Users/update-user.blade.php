
<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/bootstrap-datepicker/bootstrap-datepicker3.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/select2/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/select2/select2-bootstrap.min.css') }}">

<div class="row">
    <div class="col-md-12 push-30">
        <div class="col-md-12">
		    <div class="row">
		        <div class="block bg-white" style="padding: 20px;">
		        	<div class="col-xs-12 col-md-12 push-20">
		        		<h3 class="text-center">
		        			Formulario para añadir Usuario
		        		</h3>
		        	</div>
		        	<div class="clear"></div>
		        	<form class="form-horizontal" action="{{ url('usuarios/saveupdate') }}" method="post">
		        		<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
		        		<input type="hidden" name="id" value="<?php echo $user->id; ?>">
		                <div class="col-md-12 col-xs-12 push-20">
		                    <div class="col-md-6  push-20">
		                        <div class="form-material">
		                            <input class="form-control" type="text" id="name" name="name" required value="<?php echo $user->name?>">
		                            <label for="nombre">Nombre del Usuario</label>
		                        </div>
		                    </div>
		                    <div class="col-md-6  push-20">
		                        <div class="form-material">
		                            <input class="form-control" type="email" id="email" name="email" required value="<?php echo $user->email?>">
		                            <label for="email">Email del Usuario</label>
		                        </div>
		                    </div>
		                </div>
		                <div class="col-md-12 col-xs-12 push-20">
		                	<div class="col-md-4  push-20">
		                        <div class="form-material">
		                        		<select class="js-select2 form-control" id="role" name="role" style="width: 100%;" data-placeholder="Tipo de usuario..." required>
			                            	<option value="Admin">Admin</option>
			                            	<option value="Jaime">Jaime</option>
			                            	<option value="Limpieza">Limpieza</option>
			                            	<option value="Agente">Agente</option>
			                            	<option value="Propietario">Propietario</option>
		                            	</select>
		                            	<label for="role">Tipo de usuario</label>
		                            
		                        </div>
		                    </div>
		                    <div class="col-md-6  push-20">
		                        <div class="form-material">
		                            <input class="form-control" type="password" id="password" name="password" required>
		                            <label for="password">Contraseña del Usuario</label>
		                        </div>
		                    </div>
		                </div>
		                <div class="col-md-12 col-xs-12 push-20 text-center">
							<button class="btn btn-success" type="submit">
	        					<i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar
	        				</button>
						</div>
		        	</form>
		        </div>
		    </div> 
        </div>
    </div>
</div>



<script src="{{asset('/admin-css/assets/js/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('/admin-css/assets/js/plugins/select2/select2.full.min.js')}}"></script>
<script>
    jQuery(function () {
        App.initHelpers(['datepicker', 'select2','summernote','ckeditor']);
    });
</script>