<?php   use \Carbon\Carbon;  
setlocale(LC_TIME, "ES"); 
setlocale(LC_TIME, "es_ES"); 
?>
<script src="/assets/js/notifications.js" type="text/javascript"></script>
<table class="table table-bordered table-striped table-header-bg no-footer">
	<thead>
		<tr>
			<th class="text-center bg-complete text-white">#</th>
			<th class="text-center bg-complete text-white">Fecha</th>
			<th class="text-center bg-complete text-white">Concepto</th>
			<th class="text-center bg-complete text-white type" style="width: 250px;">Tipo</th>
			<th class="text-center bg-complete text-white type" style="width: 250px;">Método de pago</th>
			<th class="text-center bg-complete text-white">Importe</th>
			<th class="text-center bg-complete text-white">Pisos</th>
			<th class="text-center bg-complete text-white">Comentario</th>
		</tr>
	</thead>	
	<tbody>
		<?php $array = [0 =>"Metalico Jorge", 1 =>"Metalico Jaime",2 =>"Banco Jorge",3=>"Banco Jaime"] ?>
		<?php foreach ($gastos as $key => $gasto): ?>
			
		
			<tr>
				<td class="text-center">
					<button  class="btn btn-xs btn-danger deleteExpense" data-id="{{ $gasto->id }}" onclick="return confirm('¿Quieres Eliminar el gasto?');">
	                    <i class="fa fa-trash"></i>
	                </button>
				</td>
				<td class="text-center">
					<b><?php echo Carbon::createFromFormat('Y-m-d', $gasto->date)->formatLocalized('%d %b %Y') ?></b>
					<input type="hidden" id="date-<?php echo $gasto->id ?>" value="<?php echo $gasto->date ?>">
				</td>
				<td class="text-center">
					<input type="text" class="form-control editedExpensed" id="concept-<?php echo $gasto->id ?>" value="<?php echo $gasto->concept ?>" data-id="<?php echo $gasto->id ?>">
				</td>

				<td class="text-center">
					
					<select class="js-select2 form-control editedExpensed" id="type-<?php echo $gasto->id ?>" name="type" style="width: 100%;" data-placeholder="Seleccione un tipo" required data-id="<?php echo $gasto->id ?>">
		                <option></option>
		                <option <?php if($gasto->type == 'PAGO PROPIETARIO'){ echo "selected"; } ?> value="PAGO PROPIETARIO">
		                	PAGO PROPIETARIO
		                </option>
		                <option <?php if($gasto->type == 'MOBILIARIO'){ echo "selected"; } ?> value="MOBILIARIO">
		                	MOBILIARIO
		                </option>
		                <option <?php if($gasto->type == 'SERVICIOS PROFESIONALES INDEPENDIENTES'){ echo "selected"; } ?> value="SERVICIOS PROFESIONALES INDEPENDIENTES">
		                	SERVICIOS PROFESIONALES INDEPENDIENTES
		                </option>
		                <option <?php if($gasto->type == 'VARIOS'){ echo "selected"; } ?> value="VARIOS">
		                	VARIOS
		                </option>
		                <option <?php if($gasto->type == 'EQUIPAMIENTO DEPORTIVO'){ echo "selected"; } ?> value="EQUIPAMIENTO DEPORTIVO">
		                	EQUIPAMIENTO DEPORTIVO
		                </option>
		                <option <?php if($gasto->type == 'IMPUESTOS'){ echo "selected"; } ?> value="IMPUESTOS">
		                	IMPUESTOS
		                </option>
		                <option <?php if($gasto->type == 'SUMINISTROS'){ echo "selected"; } ?> value="SUMINISTROS">
		                	SUMINISTROS
		                </option>
		                <option <?php if($gasto->type == 'GASTOS BANCARIOS'){ echo "selected"; } ?> value="GASTOS BANCARIOS">
		                	GASTOS BANCARIOS
		                </option>
		                <option <?php if($gasto->type == 'PUBLICIDAD'){ echo "selected"; } ?> value="PUBLICIDAD">
		                	PUBLICIDAD
		                </option>
		                <option <?php if($gasto->type == 'REPARACION Y CONSERVACION'){ echo "selected"; } ?> value="REPARACION Y CONSERVACION">
		                	REPARACION Y CONSERVACION
		                </option>
		                <option <?php if($gasto->type == 'ALQUILER NAVE'){ echo "selected"; } ?> value="ALQUILER NAVE">
		                	ALQUILER NAVE
		                </option>
		                <option <?php if($gasto->type == 'SEGUROS SOCIALES'){ echo "selected"; } ?> value="SEGUROS SOCIALES">
		                	SEGUROS SOCIALES
		                </option>
		                <option <?php if($gasto->type == 'NOMINAS'){ echo "selected"; } ?> value="NOMINAS">
		                	NOMINAS
		                </option>
		                <option <?php if($gasto->type == 'TARJETA VISA'){ echo "selected"; } ?> value="TARJETA VISA">
		                	TARJETA VISA
		                </option>
		                <option <?php if($gasto->type == 'MATERIAL OFICINA'){ echo "selected"; } ?> value="MATERIAL OFICINA">
		                	MATERIAL OFICINA
		                </option>
		                <option <?php if($gasto->type == 'MENSAJERIA'){ echo "selected"; } ?> value="MENSAJERIA">
		                	MENSAJERIA
		                </option>
		                <option <?php if($gasto->type == 'PRODUCTOS VENDING'){ echo "selected"; } ?> value="PRODUCTOS VENDING">
		                	PRODUCTOS VENDING
		                </option>
		                <option <?php if($gasto->type == 'LIMPIEZA'){ echo "selected"; } ?> value="LIMPIEZA">
		                	LIMPIEZA
		                </option>
		                <option <?php if($gasto->type == 'INTERNET'){ echo "selected"; } ?> value="INTERNET">
		                	INTERNET
		                </option>
		                <option <?php if($gasto->type == 'RENTING EQUIPAMIENTO DEPORTIVO'){ echo "selected"; } ?> value="RENTING EQUIPAMIENTO DEPORTIVO">
		                	RENTING EQUIPAMIENTO DEPORTIVO
		                </option>
		                <option <?php if($gasto->type == 'COMISONES COMERCIALES'){ echo "selected"; } ?> value="COMISONES COMERCIALES">
		                	COMISONES COMERCIALES
		                </option>
		            </select>
				</td>

				<td class="text-center">
					<select class="js-select2 form-control editedExpensed" id="type_payment-<?php echo $gasto->id ?>" name="type_payment" style="width: 100%;" data-placeholder="Seleccione una" required data-id="<?php echo $gasto->id ?>">
			                <option></option>
			                <option value="0" <?php if($gasto->typePayment == '0'){ echo "selected"; } ?>> Tarjeta visa </option>
			                <option value="1" <?php if($gasto->typePayment == '1'){ echo "selected"; } ?>> Cash Jaime </option>
			                <option value="2" <?php if($gasto->typePayment == '2'){ echo "selected"; } ?>> Cash Jorge </option>
			                <option value="3" <?php if($gasto->typePayment == '3'){ echo "selected"; } ?>> Banco Jorge</option>
			            </select>
				</td>
				<td class="text-center">
					<b><?php echo $gasto->import ?> €</b>
				</td>
				<td class="text-center">
					<?php if ($gasto->PayFor != "" ): ?>
						<?php $roomsIds = explode(',', $gasto->PayFor) ?>
						<?php for ($i=0; $i < count($roomsIds); $i++): ?>
							<?php if ($roomsIds[$i] != ""): ?>
								<?php $room = \App\Rooms::find($roomsIds[$i]) ?>
								<?php echo $room->nameRoom; ?>,  
							<?php endif ?>
						<?php endfor; ?>
					<?php else: ?>
						Todos
					<?php endif ?>
				</td>

				<td class="text-center">
					<input type="text" class="form-control editedExpensed"  id="comment-<?php echo $gasto->id ?>" value="<?php echo $gasto->comment ?>" data-id="<?php echo $gasto->id ?>">
				</td>
			</tr>
		<?php endforeach ?>
	</tbody>			
</table>
<script type="text/javascript">
	$(document).ready(function() {
		$('.editedExpensed').change(function(event) {
			var id = $(this).attr('data-id');
			var date = $('#date-'+id).val();
			var concept = $('#concept-'+id).val();
			var type = $('#type-'+id).val();
			var typePayment = $('#type_payment-'+id).val();
			var comment = $('#comment-'+ id).val();



			$.get( '/admin/gastos/update/'+ id , {date: date, concept: concept, type: type, typePayment: typePayment, comment: comment}, function(data) {

			   	// var year = $('#fecha').val();
				// $('#contentTableExpenses').empty().load('/admin/gastos/getTableGastos/'+year);
				if (data == 'OK') {
					$.notify({
	                    title: '<strong>CAMBIADO</strong>, ',
	                    icon: 'glyphicon glyphicon-star',
	                    message: 'Fila actializada correctamente'
	                },{
	                    type: 'success',
	                    animate: {
	                        enter: 'animated fadeInUp',
	                        exit: 'animated fadeOutRight'
	                    },
	                    placement: {
	                        from: "top",
	                        align: "left"
	                    },
	                    allow_dismiss: true,
	                    offset: 80,
	                    spacing: 10,
	                    z_index: 1031,
	                    delay: 1000,
	                    timer: 1500,
	                }); 
				}
				

			});
		});
	});
</script>