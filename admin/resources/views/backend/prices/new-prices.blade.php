
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
		        			Formulario para añadir Precio
		        		</h3>
		        	</div>
		        	<div class="clear"></div>
		        	<form class="form-horizontal" action="{{ url('/admin/precios/create') }}" method="post">
		        		<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
		                <div class="col-md-3 col-xs-12 col-md-offset-4 push-20">
		                    <div class="col-md-12 col-xs-12 push-20">
		                        <div class="form-material">
		                            <input class="form-control" type="number" id="occupation" name="occupation" required>
		                            <label for="occupation" class="text-center">Ocupación</label>
		                        </div>
		                    </div>
		                </div>		               		              
		                <div class="col-md-12 col-xs-12 push-20">
		                	<div class="col-md-12  push-20 text-center">
		                		<h3>Precios de temporada</h3>
		                	</div>
		                	<div class="col-md-3  push-20 col-md-offset-1">
		                        <div class="form-material">
		                            <input class="form-control" type="text" id="T-Alta" name="priceHigh" required>
	                            	<label for="T-Alta">Temp. Alta</label>
		                        </div>
		                    </div>
		                    <div class="col-md-3  push-20">
		                        <div class="form-material">
		                            <input class="form-control" type="text" id="T-Media" name="priceMed" required>
		                            <label for="T-Media">Temp. Media</label>
		                        </div>
		                    </div>
		                    <div class="col-md-3  push-20">
		                        <div class="form-material">
		                            <input class="form-control" type="text" id="T-Baja" name="priceLow" required>
		                            <label for="T-Baja">Temp. Baja</label>
		                        </div>
		                    </div>
		                </div>
		                <div class="col-md-12 col-xs-12 push-20">
		                	<div class="col-md-12  push-20 text-center">
		                		<h3>Precios de Coste temporada</h3>
		                	</div>
		                	<div class="col-md-4  push-20 ">
		                        <div class="form-material">
		                            <input class="form-control" type="text" id="C-Alta" name="costHigh" required>
	                            	<label for="C-Alta">Cost. Temp. Alta</label>
		                        </div>
		                    </div>
		                    <div class="col-md-4  push-20">
		                        <div class="form-material">
		                            <input class="form-control" type="text" id="C-Media" name="costMed" required>
		                            <label for="C-Media">Cost. Temp. Media</label>
		                        </div>
		                    </div>
		                    <div class="col-md-4  push-20">
		                        <div class="form-material">
		                            <input class="form-control" type="text" id="C-Baja" name="costLow" required>
		                            <label for="C-Baja">Cost.  Temp. Baja</label>
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