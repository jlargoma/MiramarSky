<link href="/assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" type="text/css" media="screen">
<link rel="stylesheet" href="{{ asset('/frontend/css/components/daterangepicker.css')}}" type="text/css" />
<style type="text/css">
.roomEspecifica{
	/*padding: 15px;*/
	border: 2px solid black;
	margin-bottom: 5px;
	cursor: pointer;
}
.roomEspecifica.selected{
	/*padding: 15px;*/
	border: 2px solid black;
	background-color: rgba(179,221,234,0.62);
	cursor: pointer;
}

</style>
<div class="col-xs-12">
	<form action="{{ url('/admin/ingresos/create') }}" method="post" id="formAddIngreso" >
		<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
		<div class="col-xs-12 col-md-10">

			<div class="col-xs-12 col-md-1 push-10" style="padding: 0">
				<label for="date">fecha</label>
				<div id="datepicker-component" class="input-group date col-xs-12">
					<input type="text" class="form-control" name="fecha" id="fecha" value="<?php echo date('d/m/Y') ?>" style="font-size: 12px">
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				</div>

			</div>
			<div class=" col-xs-12 col-md-3 push-10">
				<div class="col-xs-12 col-md-12 push-10">
					<label for="concept">Concepto</label>
					<select class="js-select2 form-control" id="concept" name="concept" style="width: 100%;" data-placeholder="Seleccione un tipo" required>
		                <option value="INGRESOS EXTRAORDINARIOS">INGRESOS EXTRAORDINARIOS</option>
		                <option value="RAPPEL CLOSES"> RAPPEL CLOSES</option>
		                <option value="RAPPEL FORFAITS"> RAPPEL FORFAITS</option>
		                <option value="RAPPEL ALQUILER MATERIAL"> RAPPEL ALQUILER MATERIAL</option>
					</select>
				</div>
			</div>

			<div class="col-xs-12 col-md-1 push-10">
				<label for="import">Importe</label>
				<input  type="number" step="0.01" name="import" id="import" class="form-control"  />
			</div>
			<div class="col-md-2 form-group text-center push-10" style="padding: 20px;">
				<button class="btn btn-lg btn-success">Añadir</button>
			</div>
		</div>
	</form>
</div>

<script src="/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="/assets/plugins/moment/moment.min.js"></script>

<script type="text/javascript">
	$('#datepicker-range, #datepicker-component, #datepicker-component2').datepicker();
</script>