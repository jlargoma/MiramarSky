<?php use \Carbon\Carbon; ?>
<style>
.page-break {
    page-break-after: always;
}
</style>
<?php for ($i=0; $i <= 1; $i++): ?>
	<h2 style="font-weight: 800; color: red; text-align: center; font-family: 'Verdana'; font-size: 20px;">Documento Check In</h2>
	<p style="color: black; font-family: 'Verdana';margin-bottom: 0;font-size: 11px; text-align: justify;">
		<b>Nombre: <?php echo ucfirst($data['book']->customer->name) ?></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<b>DNI: _______________ </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<b>Dirección: <?php echo ($data['book']->customer->address)?$data['book']->customer->address:"_________________________"; ?></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<b>Teléfono: <?php echo ($data['book']->customer->phone)?$data['book']->customer->phone:"___________"; ?></b>
		<br>------------------------------------------------------------------------------------------------------<br> 
		<b>Fecha reserva: 
			<?php 
				echo Carbon::createFromFormat('Y-m-d',$data['book']->start)->formatLocalized('%d %b')." - ". Carbon::createFromFormat('Y-m-d',$data['book']->finish)->formatLocalized('%d %b')." ".Carbon::createFromFormat('Y-m-d',$data['book']->finish)->format('Y')
			?>
		</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<b>Noches:</b> <?php echo Carbon::createFromFormat('Y-m-d', $data['book']->start)->diffInDays(Carbon::createFromFormat('Y-m-d', $data['book']->finish)) ?> <br>
		<b>Ocupantes:</b> <?php echo $data['book']->pax ?><br>
		<b>Apartamento: <?php echo $data['book']->room->nameRoom ?></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<b>Nº Plaza Parking:  <?php echo $data['book']->room->parking ?></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<b>Nº Taquilla Guarda esquíes: <?php echo $data['book']->room->locker ?></b> 
		<br>------------------------------------------------------------------------------------------------------<br> 
		<b>Total Reserva:</b> <?php echo $data['book']->total_price ?>€<br>
		<b>Cobrado:</b> <?php echo $data['pendiente'] ?>€<br>
		<b>Pendiente de abono:</b>  <?php echo $data['book']->total_price - $data['pendiente'] ?>€ 
		<br>------------------------------------------------------------------------------------------------------<br> 
		<span style="float:left;"><b>Fianza apartamento 300 €</b></span>
		<div style="width: 15px; height: 15px; border: 1px solid black; position: absolute; top: 180px; left: 200px;"></div>&nbsp;&nbsp;<span style="position: absolute; top: 180px; left: 100px;">Metálico </span>

		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<div style="width: 15px; height: 15px; border: 1px solid black;position: absolute; top: 180px; left: 300px;"></div>&nbsp;&nbsp;<span style="position: absolute; top: 180px; left: 200px;">Tarjeta</span>

	</p>
	<h2 style="font-weight: 800; color: red; text-align: center; font-family: 'Verdana'; font-size: 20px;">
		Condiciones Alquiler Apartamentos Miramar Ski
	</h2>
	       
	<p style="color: black; font-family: 'Verdana';margin-bottom: 50px;font-size: 12px; text-align: justify;">
		<b>Hora de Entrada</b>: Desde las <b>17,00h a 19,00h</b> en el caso de llegar más tarde avisarán por teléfono y se incrementara en el alquiler de 10€ por la demora en recogida de llaves. De 22,00h en adelante se cobrará 20€<br><br>

		<b>Hora de Salida</b>: La vivienda deberá  ser desocupada antes de las <b>11,59h a.m</b> (de lo contrario se podrá cobrará un noche más de alquiler apartamento según tarifa apartamento  y ocupación. La plaza de garaje debe quedar libre a esta hora o bien pagar la estancia de un nuevo día.(según tarifa 15€ / día.) <br><br>

		<b>Fianza</b>: Además del precio del alquiler el día de llegada <b>se pedirá una fianza por el importe de 300€</b> a la entrega de llaves para garantizar el buen uso de la vivienda. La fianza se devolverá a la entregada de llaves, una vez revisada la vivienda y descontados los gastos correspondientes a los desperfectos  (en el caso de que se produzcan.)<br><br>

		<b>Resto del Pago</b>:  El apartamento debe estar completamente abonado a la entrega de llaves. <b>En el caso de no cumplir con lo establecido no se podrá ocupar la vivienda.</b><br><br>

		<b>Periodo del alquiler</b>: Por el motivo que sea si la persona que alquila decide marcharse antes del periodo contratado no tiene derecho a devolución del importe de los días no disfrutados.<br><br>

		<b>Meteorología y estado de pistas</b>: Las condiciones del alquiler de la vivienda son completamente ajenas  a las condiciones meteorológicas, al estado de las carreteras, al estado de las pistas de esquí, falta de nieve o incluso al cierre de la estación por lo que tampoco se podrá reclamar devolución  por estos motivos.<br><br>

		<b>Nº de personas</b>: El apartamento no podrá ser habitado por más personas de las camas que dispone.<br><br>

		<b><u>No se admiten animales</u></b>: ningún tipo de animales de compañía ni mascotas.<br><br>

		<b>Sabanas y Toallas</b> están incluidas en todas las reservas.<br><br>

		<b>Se ruega guardar silencio en los apartamentos y en las zonas comunes a partir de las 23 hrs</b>, por respeto al sueño y a la tranquilidad de los demás inquilinos y propietarios del edificio Miramarski.<br><br>

		<b>Checkout : El alojamiento se deberá entregar antes de las 12:00 con : </b><br><br>

		•	Estado de limpieza aceptable.<br>
		•	Vajilla limpia y recogida.<br>
		•	Muebles de cama en la misma posición que se entregaron.<br>
		•	Sin basuras en el apartamento.<br>
		•	Nevera vacía (sin comida sobrante).<br>
		•	Edredones doblados en los armarios.<br>

		Si algunos de estos requisitos no se cumplen podría conllevar la perdida de la fianza, total o parcialmente.<br><br>
		<b><u>Para la devolución de Fianza llamar al teléfono de Manolo: 678728196</u></b><br><br>
		Confirmo que se me ha informado y acepto las condiciones del alquiler de la vivienda que se detallan en este documento.

		
	</p>
	<span style="position: absolute; bottom: 0px; left: 0px;">
	 	Firmado: <b><?php echo ucfirst($data['book']->customer->name) ?></b>
	</span>
	
	<span style="position: absolute; bottom: 0px; right: 0px;">
		Firmado: <b>Jaime Díaz Fernández</b>
	</span>
	<div class="page-break"></div>
<?php endfor; ?>