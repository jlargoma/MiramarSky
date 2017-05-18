
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
		        			Formulario para añadir Reserva
		        		</h3>
		        	</div>
		        	<div class="clear"></div>
		        	<div class="col-xs-12 col-md-12 push-20">
		        		<h3 class="text-center">
		        			Cliente:
		        		</h3>
		        	</div>
		        	<form class="form-horizontal" action="{{ url('/admin/planning/create') }}" method="post">
		        		<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
		                <div class="col-md-12 col-xs-12 push-20">
		                    <div class="col-md-4  col-xs-6 push-20">
		                        <div class="form-material">
		                            <input class="form-control" type="text" id="name" name="name" required>
		                            <label for="name">Nombre del Cliente</label>
		                        </div>
		                    </div>
		                    <div class="col-md-4  col-xs-6 push-20">
		                        <div class="form-material">
		                        <input class="form-control" type="email" id="email" name="email" required>
		                            <label for="email">Email</label>
		                        </div>
		                    </div>
		                    <div class="col-md-4  col-xs-6 push-20 ">
		                        <div class="form-material">
		                        <input class="form-control" type="number" id="phone" name="phone" required>
		                            <label for="phone">Telefono</label>
		                        </div>
		                    </div>
		                    <div class="col-md-4  col-xs-6 push-20 ">
		                        <div class="form-material">
		                        <input class="form-control" type="text-area" id="comment" name="comment" required>
		                            <label for="comment">Comentario</label>
		                        </div>
		                    </div>
		                </div>
			        	<div class="col-xs-12 col-md-12 push-20">
			        		<h3 class="text-center">
			        			Reserva:
			        		</h3>
			        	</div>
		                <div class="col-md-12 col-xs-12">
    	                    <div class="col-md-6  push-20">
    	                    	<div class="input-daterange input-group" data-date-format="dd-mm-yyyy">
    								<input class="form-control" type="text" id="start" name="start" placeholder="Desde">
    									<span class="input-group-addon"><i class="fa fa-chevron-right"></i></span>
    								<input class="form-control" type="text" id="finish" name="finish" placeholder="Hasta">
    							</div>
    	                    </div>
		                    <div class="col-md-2  push-20">
		                        <div class="form-material">
		                        <input class="form-control" type="number" name="pax" id="pax" required>
	                            	<label for="pax">Personas</label>
		                        </div>
		                    </div>
		                    <div class="col-md-4  push-20">
		                        <div class="form-material">		                     
		                            <select class="js-select2 form-control" id="room" name="room" style="width: 100%;" required>
		                            	<?php foreach ($rooms as $room): ?>
		                            		<option value="<?php echo $room->id ?>"><?php echo $room->name ?></option>
		                            	<?php endforeach ?>
	                            	</select>
		                            <label for="room">Tamaño</label>
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