<?php   use \Carbon\Carbon;
setlocale(LC_TIME, "ES");
setlocale(LC_TIME, "es_ES");

$today =   Carbon::now()->formatLocalized('%d %b %Y');
?>
<script src="/assets/js/notifications.js" type="text/javascript"></script>
<table class="table table-bordered table-striped table-header-bg no-footer">
	<thead>
		<tr>

			<th class="text-center bg-complete text-white">Fecha</th>
			<th class="text-center bg-complete text-white">Concepto</th>
			<th class="text-center bg-complete text-white type" style="width: 250px;">Tipo</th>
			<th class="text-center bg-complete text-white type" style="width: 250px;">Método de pago</th>
			<th class="text-center bg-complete text-white">Importe</th>
			<th class="text-center bg-complete text-white">Pisos</th>
			<th class="text-center bg-complete text-white">Comentario</th>
			<th class="text-center bg-complete text-white">#</th>
		</tr>
	</thead>
	<tbody>
		<?php $array = [0 =>"Metalico",2 =>"Banco"] ?>
		<tr>

			<td class="text-center" style="padding: 8px 5px!important">
				Acutalizado a <?php echo $today ?>
			</td>
			<td class="text-center" style="padding: 8px 5px!important">
				COMISION TPV
			</td>
			<td class="text-center" style="padding: 8px 5px!important">
				COMISION
			</td>

			<td class="text-center" style="padding: 8px 5px!important">
				BANCO

			</td>
			<td class="text-center" style="padding: 8px 5px!important">
				<b><?php echo number_format($totalStripep, 0, ',', '.'); ?> €</b>
			</td>
			<td class="text-center" style="padding: 8px 5px!important">
				GENERICO
			</td>

			<td class="text-center" style="padding: 8px 5px!important">
				PAGOS A STRIPE
			</td>
			<td class="text-center" style="padding: 8px 5px!important">

			</td>
		</tr>
		<tr>

			<td class="text-center" style="padding: 8px 5px!important">
				Acutalizado a <?php echo $today ?>
			</td>
			<td class="text-center" style="padding: 8px 5px!important">
				COMISION AGENCIAS
			</td>
			<td class="text-center" style="padding: 8px 5px!important">
				COMISION
			</td>

			<td class="text-center" style="padding: 8px 5px!important">
				BANCO

			</td>
			<td class="text-center" style="padding: 8px 5px!important">
				<b><?php echo number_format($comisionBooking, 0, ',', '.'); ?> €</b>
			</td>
			<td class="text-center" style="padding: 8px 5px!important">
				GENERICO
			</td>

			<td class="text-center" style="padding: 8px 5px!important">
				PAGO A LAS DISTINTAS AGENCIAS DE RESERVAS
			</td>
			<td class="text-center" style="padding: 8px 5px!important">

			</td>
		</tr>
		<tr>

			<td class="text-center" style="padding: 8px 5px!important">
				Acutalizado a <?php echo $today ?>
			</td>
			<td class="text-center" style="padding: 8px 5px!important">
				OBSEQUIOS DE BIENVENIDA
			</td>
			<td class="text-center" style="padding: 8px 5px!important">
				VARIOS
			</td>

			<td class="text-center" style="padding: 8px 5px!important">
				BANCO 

			</td>
			<td class="text-center" style="padding: 8px 5px!important">
				<b><?php echo number_format($obsequios, 0, ',', '.'); ?> €</b>
			</td>
			<td class="text-center" style="padding: 8px 5px!important">
				GENERICO
			</td>

			<td class="text-center" style="padding: 8px 5px!important">
				PAGO DE LOS OBSEQUIOS DE LA TEMPORADA
			</td>
			<td class="text-center" style="padding: 8px 5px!important">

			</td>
		</tr>
		<tr>

			<td class="text-center" style="padding: 8px 5px!important">
				Acutalizado a <?php echo $today ?>
			</td>
			<td class="text-center" style="padding: 8px 5px!important">
				LIMPIEZA MENSUAL
			</td>
			<td class="text-center" style="padding: 8px 5px!important">
				LIMPIEZA
			</td>

			<td class="text-center" style="padding: 8px 5px!important">
				BANCO

			</td>
			<td class="text-center" style="padding: 8px 5px!important">
				<b><?php echo number_format($totalMonthLimpieza, 0, ',', '.'); ?> €</b>
			</td>
			<td class="text-center" style="padding: 8px 5px!important">
				GENERICO
			</td>

			<td class="text-center" style="padding: 8px 5px!important">
				PAGO DE LAS LIMPIEZA MENSUALES DE LA TEMPORADA
			</td>
			<td class="text-center" style="padding: 8px 5px!important">

			</td>
		</tr>
		<?php foreach ($gastos as $key => $gasto): ?>
                <?php           
                  if ($gasto->concept == 'LIMPIEZA MENSUAL') {
                    continue;
                  }
                ?>

			<tr>

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
		                <option <?php if($gasto->type == 'SERVICIOS PROF INDEPENDIENTES'){ echo "selected"; } ?> value="SERVICIOS PROF INDEPENDIENTES"> SERVICIOS PROF INDEPENDIENTES</option>
		                <option <?php if($gasto->type == 'VARIOS'){ echo "selected"; } ?> value="VARIOS"> VARIOS</option>
		                <option <?php if($gasto->type == 'REGALO'){ echo "selected"; } ?> value="REGALO BIENVENIDA"> REGALO BIENVENIDA</option>
		                <option <?php if($gasto->type == 'LAVANDERIA'){ echo "selected"; } ?> value="LAVANDERIA"> LAVANDERIA</option>
		                <option <?php if($gasto->type == 'LIMPIEZA'){ echo "selected"; } ?> value="LIMPIEZA"> LIMPIEZA</option>
		                <option <?php if($gasto->type == 'EQUIPAMIENTO VIVIENDA'){ echo "selected"; } ?> value="EQUIPAMIENTO VIVIENDA"> EQUIPAMIENTO VIVIENDA</option>
		                <option <?php if($gasto->type == 'DECORACION'){ echo "selected"; } ?> value="DECORACION"> DECORACION</option>
		                <option <?php if($gasto->type == 'MENAJE'){ echo "selected"; } ?> value="MENAJE"> MENAJE</option>
		                <option <?php if($gasto->type == 'SABANAS Y TOALLAS'){ echo "selected"; } ?> value="SABANAS Y TOALLAS"> SABANAS Y TOALLAS</option>
		                <option <?php if($gasto->type == 'IMPUESTOS'){ echo "selected"; } ?> value="IMPUESTOS"> IMPUESTOS</option>
		                <option <?php if($gasto->type == 'GASTOS'){ echo "selected"; } ?> value="GASTOS BANCARIOS"> GASTOS BANCARIOS</option>
		                <option <?php if($gasto->type == 'MARKETING Y PUBLICIDAD'){ echo "selected"; } ?> value="MARKETING Y PUBLICIDAD"> MARKETING Y PUBLICIDAD</option>
		                <option <?php if($gasto->type == 'REPARACION Y CONSERVACION'){ echo "selected"; } ?> value="REPARACION Y CONSERVACION"> REPARACION Y CONSERVACION</option>
		                <option <?php if($gasto->type == 'SUELDOS Y SALARIOS'){ echo "selected"; } ?> value="SUELDOS Y SALARIOS"> SUELDOS Y SALARIOS</option>
		                <option <?php if($gasto->type == 'SEG SOCIALES'){ echo "selected"; } ?> value="SEG SOCIALES"> SEG SOCIALES</option>
		                <option <?php if($gasto->type == 'MENSAJERIA'){ echo "selected"; } ?> value="MENSAJERIA"> MENSAJERIA</option>
		                <option <?php if($gasto->type == 'COMISIONES'){ echo "selected"; } ?> value="COMISIONES COMERCIALES"> COMISIONES COMERCIALES</option>
		            </select>
				</td>

				<td class="text-center">
					<select class="js-select2 form-control editedExpensed" id="type_payment-<?php echo $gasto->id ?>" name="type_payment" style="width: 100%;" data-placeholder="Seleccione una" required data-id="<?php echo $gasto->id ?>">
			                <option></option>
			                <option value="0" <?php if($gasto->typePayment == '0'){ echo "selected"; } ?>> Tarjeta visa </option>
                                        <option value="1" <?php if($gasto->typePayment == '1'){ echo "selected"; } ?> disabled=""> Cash Jaime </option>
			                <option value="2" <?php if($gasto->typePayment == '2'){ echo "selected"; } ?> > Cash </option>
                                        <option value="3" <?php if($gasto->typePayment == '3'){ echo "selected"; } ?>> Banco</option>
                                        <option value="4" <?php if($gasto->typePayment == '4'){ echo "selected"; } ?> disabled=""> Banco Jaime</option>
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
						GENERICO
					<?php endif ?>
				</td>

				<td class="text-center">
					<input type="text" class="form-control editedExpensed"  id="comment-<?php echo $gasto->id ?>" value="<?php echo $gasto->comment ?>" data-id="<?php echo $gasto->id ?>">
				</td>
				<td class="text-center">
					<button  class="btn btn-xs btn-danger deleteExpense" data-id="{{ $gasto->id }}" onclick="return confirm('¿Quieres Eliminar el gasto?');">
	                    <i class="fa fa-trash"></i>
	                </button>
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