
<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/bootstrap-datepicker/bootstrap-datepicker3.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/select2/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/select2/select2-bootstrap.min.css') }}">
<style type="text/css">
	.input-group{
		width: 100%;
	}
</style>


<div class="container-fixed-lg">
    <div>
        <div style="width: 100%">
            <!-- START PANEL -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title">Actualizar usuario
                    </div>
                </div>
                <div class="panel-body">
                    <form role="form"  action="{{ url('usuarios/saveupdate') }}" method="post">
                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                    <input type="hidden" name="id" value="<?php echo $user->id ?>">
                        <div class="input-group transparent">
                        	<label>Nombre</label>
                            <input type="text" class="form-control" name="name" placeholder="Nombre" required="" aria-required="true" aria-invalid="false" value="<?php echo $user->name?>">

                        </div>
                            <br>
                        <div class="input-group">
                            <label>Correo</label>
                            <input type="email" class="form-control" name="email" placeholder="Email" required="" aria-required="true" aria-invalid="false" value="<?php echo $user->email?>">
                        </div>    
                            <br>
                        <div class="input-group">
                            <label>Telefono</label>
                            <input type="number" class="form-control" name="phone" placeholder="Telefono" required="" aria-required="true" aria-invalid="false" value="<?php echo $user->phone ?>">
                        </div>
                            <br>
                        <div class="input-group">
                        	<label>Cargo</label>
                            <select class="full-width" data-init-plugin="select2" name="role">
                                <option value="<?php echo $user->role ?>" default><?php echo $user->role ?></option>
                                <option value="Admin">Admin</option>
                                <option value="Jaime">Jaime</option>
                                <option value="Limpieza">Limpieza</option>
                                <option value="Agente">Agente</option>
                                <option value="Propietario">Propietario</option>
                            </select>
                        </div>
                            <br>
                        <div class="input-group">
							<label>Contraseña</label>
                            <input type="password" class="form-control" name="password"  required="" aria-required="true" aria-invalid="false" value="<?php echo $user->password ?>">
                        </div>
                            <br>
                        <div class="input-group">
                            <button class="btn btn-complete" type="submit">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- END PANEL -->
        </div>
            <!-- END PANEL -->      
    </div>
</div>


<script src="{{asset('/admin-css/assets/js/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('/admin-css/assets/js/plugins/select2/select2.full.min.js')}}"></script>
<script>
    jQuery(function () {
        App.initHelpers(['datepicker', 'select2','summernote','ckeditor']);
    });
</script>