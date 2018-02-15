<link href="/assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" type="text/css" media="screen">
<link rel="stylesheet" href="{{ asset('/frontend/css/components/daterangepicker.css')}}" type="text/css" />
<style type="text/css">
	.roomEspecifica{
		padding: 15px;
		border: 2px solid black;
		margin-bottom: 5px;
		cursor: pointer;
	}
	.roomEspecifica.selected{
		padding: 15px;
		border: 2px solid black;
		background-color: rgba(179,221,234,0.62);
		cursor: pointer;
	}
</style>
<div class="row">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="position: absolute; top: 0px; right: 10px; z-index: 100">
        <i class="fa fa-times fa-2x" style="color: #000!important;"></i>
    </button>
</div>
<div class="col-xs-12 bg-white">
	<div class="row">
		<div class="col-xs-12 push-20">
			<h2 class="text-center">Nuevo Gasto</h2>
		</div>
		<div class="col-xs-12">
			<form action="{{ url('/admin/gastos/create') }}" method="post" id="formAddGasto">
				<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
				<div class="col-xs-12">
					<div class="col-xs-12 col-md-3 push-20">
						<label for="date">fecha</label>
						 <div id="datepicker-component" class="input-group date col-xs-12">
                      		<input type="text" class="form-control" name="fecha" id="fecha">
                      		<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    	</div>
						
					</div>
					<div class=" col-xs-12 col-md-3 push-20">
						<div class="col-xs-12 col-md-12 push-20">
							<label for="concept">Concepto</label>
							<input  type="text" class="form-control" name="concept" id="concept" />
						</div>
					</div>

					<div class="col-xs-12 col-md-3 push-20">
						<label for="type">Tipo de Gasto</label>
						<select class="js-select2 form-control" id="type" name="type" style="width: 100%;" data-placeholder="Seleccione un tipo" required >
			                <option></option>
			                <option value="MOBILIARIO">MOBILIARIO</option>
			                <option value="SERVICIOS PROFESIONALES INDEPENDIENTES">SERVICIOS PROFESIONALES INDEPENDIENTES</option>
			                <option value="VARIOS">VARIOS</option>
			                <option value="EQUIPAMIENTO DEPORTIVO">EQUIPAMIENTO DEPORTIVO</option>
			                <option value="IMPUESTOS">IMPUESTOS</option>
			                <option value="SUMINISTROS">SUMINISTROS</option>
			                <option value="GASTOS BANCARIOS">GASTOS BANCARIOS</option>
			                <option value="PUBLICIDAD">PUBLICIDAD</option>
			                <option value="REPARACION Y CONSERVACION">REPARACION Y CONSERVACION</option>
			                <option value="ALQUILER NAVE">ALQUILER NAVE</option>
			                <option value="SEGUROS SOCIALES">SEGUROS SOCIALES</option>
			                <option value="NOMINAS">NOMINAS</option>
			                <option value="TARJETA VISA">TARJETA VISA</option>
			                <option value="MATERIAL OFICINA">MATERIAL OFICINA</option>
			                <option value="MENSAJERIA">MENSAJERIA</option>
			                <option value="PRODUCTOS VENDING">PRODUCTOS VENDING</option>
			                <option value="LIMPIEZA">LIMPIEZA</option>
			                <option value="INTERNET">INTERNET</option>
			                <option value="RENTING EQUIPAMIENTO DEPORTIVO">RENTING EQUIPAMIENTO DEPORTIVO</option>
			                <option value="COMISONES COMERCIALES">COMISONES COMERCIALES</option>
			            </select>
					</div>
					<div class="col-xs-12 col-md-3 push-20">
						<label for="type">Imputacion</label>
						<select class="js-select2 form-control" id="type_payFor" name="type_payFor" style="width: 100%;" data-placeholder="Seleccione un tipo" required >
			                <option value="0">Generíco</option>
			                <option value="1">Especifico</option>
						</select>
					</div>

					
				</div>
				<div class="col-xs-12 push-20">
					<div class="col-xs-12 col-md-2 push-20">
						<label for="import">Importe</label>
						<input  type="number" step="0.01" name="import" id="import" class="form-control" value="0" />
					</div>
					<div class="col-xs-12 col-md-3 push-20">
						<label for="pay_for">Metodo de pago</label>
						<select class="js-select2 form-control" id="type_payment" name="type_payment" style="width: 100%;" data-placeholder="Seleccione una" required>
			                <option></option>
			                <option value="0"> Tarjeta visa </option>
			                <option value="1"> Cash Jaime </option>
			                <option value="2"> Cash Jorge </option>
			                <option value="3"> Banco Jorge</option>
			            </select>

					</div>
					<div class="col-xs-12 col-md-7 push-20" id="contentRooms" style="display: none;">
						<?php foreach (\App\Rooms::where('state', 1)->orderBy('order','ASC')->get() as $key => $room): ?>
							<div class="col-md-2 col-xs-12 roomEspecifica text-center" data-idRoom="<?php echo $room->id; ?>" data-selected="0">
								<?php echo substr($room->nameRoom, -2); ?>
							</div>
						<?php endforeach ?>
					</div>

					
				</div>
				<div class="col-xs-12 content-notifications" style="display: none;">
					<h4 class="text-center">
						Gasto especifico para: <span class="font-w800 totalRooms">0</span><br>
						Se asignará un gasto a cada propietario de: <span class="font-w800 notifiations"></span> €
					</h4>
					
				</div>
				<div class="col-xs-12">
					<div class=" col-xs-12 form-group push-20">
						<label for="comment">Observaciones</label>
						<textarea class="form-control" name="comment" id="comment"></textarea>
					</div>
				</div>

				<div class=" col-xs-12 form-group text-center push-20">
					<button class="btn btn-lg btn-success">Añadir</button>
				</div>
			</form>
		</div>
	</div>
</div>
<script src="/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="/assets/plugins/moment/moment.min.js"></script>

<script type="text/javascript">
	$('#datepicker-range, #datepicker-component, #datepicker-component2').datepicker();
	$(document).ready(function() {
		$('#type_payFor').change(function(event) {
			if ($(this).val() == 1) {
				$('#contentRooms').show();
			}else{
				$('#contentRooms').hide();
			}
		});

		$('.roomEspecifica').click(function(event) {
			if ($(this).attr('data-selected') == 0) {

				$(this).attr('data-selected', 1);
				$(this).addClass('selected');

			}else{

				$(this).attr('data-selected', 0);
				$(this).removeClass('selected');

			}
			var count = 0;
			$('.roomEspecifica').each(function() {
				if ($(this).attr('data-selected') == 1) {
					count++;
				}
			});

			$('.totalRooms').empty().append(count);
			var gastoDividido = $('#import').val() / count;
			$('.notifiations').empty().append(gastoDividido.toFixed(2));



			if (count > 0) {
				$('.content-notifications').show();
			}else{
				$('.content-notifications').hide();
			}
		});


		$('#formAddGasto').submit(function(event) {
		    event.preventDefault();
		    var url        = $('#formAddGasto').attr('action');

		    var _token = $('input[name="_token"]').val();
		    var fecha = $('input[name="fecha"]').val();
		    var concept = $('#concept').val();
		    var type = $('#type').val();
		    var type_payFor = $('#type_payFor').val();
		    var importe = $('#import').val();
		    var type_payment = $('#type_payment').val();
		    var comment = $('#comment').val();
		    var stringRooms = '';
		    if ( type_payFor == 1 ) {

		     	$('.roomEspecifica').each(function() {
		     		if ($(this).attr('data-selected') == 1) {
		     			stringRooms = stringRooms+$(this).attr('data-idRoom')+",";
		     		}
		     	});
		    } 


		    $.post( url , { _token: _token, fecha: fecha, concept: concept, type: type, type_payFor: type_payFor, importe: importe, type_payment: type_payment, comment: comment, stringRooms: stringRooms }, function(data) {

		    		if (data == 'OK') {
		    			location.reload();
		    		}

		    });
		    

		}); 
	});
</script>