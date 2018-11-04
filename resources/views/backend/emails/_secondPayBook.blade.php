<?php 
	use \Carbon\Carbon;
	setlocale(LC_TIME, "ES"); 
    setlocale(LC_TIME, "es_ES"); 
?>
<?php
	$totalPayment = 0;
	$payments = \App\Payments::where('book_id', $book->id)->get();
	if ( count($payments) > 0) {

		foreach ($payments as $key => $pay) {
			$totalPayment += $pay->import;
		}

	}
	$percent = round(($totalPayment/$book->total_price) * 100);
?>
	Hola , te enviamos este email para recordate que tienes que realizarnos el pago del <?php echo 100 - $percent ?>% restante de tu reserva :<br><br>

	<b>Nombre:</b> <?php echo strtoupper($book->customer->name) ?><br><br>

	<b>Fecha entrada:</b> <?php echo Carbon::CreateFromFormat('Y-m-d',$book->start)->formatLocalized('%d %b') ?><br><br>

	<b>Fecha salida:</b> <?php echo Carbon::CreateFromFormat('Y-m-d',$book->finish)->formatLocalized('%d %b') ?><br><br>

	<b>Noches:</b> <?php echo $book->nigths ?><br><br>

	<b>Ocupantes:</b> <?php echo $book->pax ?><br><br>

	<b>Apartamento: </b> <?php echo $book->room->sizeRooms->name ?> // <?php echo ($book->type_luxury == 1)? "Lujo" : "Estandar" ?><br><br>

	<b>Total reserva:</b> <?php echo number_format($book->total_price,2,',','.') ?>€<br><br>
	

	<b>-------------------------</b><br>
	<b>Cobrado: </b><?php echo number_format($totalPayment,2,',','.') ?>€<br>
	<b>-------------------------</b><br>
	<h2 style="color:red"><b>Pendiente: </b><?php echo number_format(($book->total_price - $totalPayment),2,',','.') ?>€</h2><br>
	<b>-------------------------</b><br>
	Para realizar el pago del restante <?php echo 100 - $percent ?>% haz clic en el siguiente link <br><br>

	<a target="_blank" href="https://miramarski.com/reservas/stripe/pagos/<?php echo base64_encode($book->id) ?>/<?php echo base64_encode(number_format(($totalPayment),2,',','.')) ?>">
        https://miramarski.com/reservas/stripe/pagos/<?php echo base64_encode($book->id) ?>/<?php echo base64_encode(number_format(($totalPayment),2,',','.')) ?>
    </a>

	<br><br>
	Si no se recibe el pago 15 días antes de tu entrada, se podrá porcedera cancelar la misma<br><br>
	Consulta nuestras condiciones de contratación <a href="https://www.apartamentosierranevada.net/condiciones-generales">aquí</a><br><br>

	Muchas Gracias !!!.<br><br>

	Un cordial saludo.<br><br>

	<hr style="width: 100%">

	<h2><b><u>Condiciones generales de Alquiler</u></b></h2>
	Para realizar una reserva se debe de abonar el 50% del importe total.<br>
	El segundo pago con el 50% restante, se realizará 15 días antes de la entrada.<br><br>

	<b>Hora de Entrada: La entrega de llaves la realizamos en el propio edifico entre las 17.30 a 19.30 Horas</b><br><br>
	La entrega de llaves fuera de horario puede llevar gastos por el tiempo de espera.<br><br>

	10€ Si llegas entre 20:00 h de las 22.00<br><br>

	20€ Si llegas más tarde de de las 22.00 h<br><br>

	No se entregan llaves a partir de las 00.00 sin previo aviso (el día anterior a la entrada)<br><br>

	El cargo se le abonan directamente en metálico a la persona que te entrega las llaves.<br><br>

	Nos sabe muy mal tener que cobrarte este recargo, Esperamos que entiendas que es solo para compensar el tiempo de espera de esta persona.<br><br>

	<b>Hora de Salida: La vivienda deberá ser desocupada antes de las 11,59 a.m.</b> (de lo contrario se podrá cobrará una noche más de alquiler apartamento según tarifa apartamento y ocupación.<br><br>

	La plaza de garaje debe quedar libre a esta hora o bien pagar la estancia de un nuevo día. (según tarifa 20€ / día.)<br><br>

	<b>Fianza:</b> El día de llegada se pedirá una tarjeta para la fianza por importe de 300€, no se captura saldo, tan solo se hace una “foto” que desaparecerá a la entrega de llaves, una vez revisada la vivienda.<br><br>

	<b>Nº de personas:</b> El apartamento no podrá ser habitado por más personas de las camas que dispone y/o de las que han sido contratadas.<br><br>

	<b>No se admiten animales.</b><br><br>

	<b>Sabanas y Toallas están incluidas</b><br><br>

	En el caso de NO cumplir con lo establecido no se podrá ocupar la vivienda.<br><br>

	<b>Consulta nuestras condiciones de contratación <a href="{{ url('/condiciones-generales') }}">aquí</a></b>

	<hr style="width: 100%">

	<h2><b><u>Servicios Adicionales</u></b></h2>

	Te ofrecemos sin coste añadido y con un descuento especial que hemos pactado con el proveedor para vosotros:<br><br>

	*<b>Descuentos en forfait</b><br>

	*<b>Descuentos en Clases de esquí</b><br>

	*<b>Descuentos en Alquiler de material</b><br>

	Para solicitar alguno de estos servicios solo es necesario que rellenes un formulario pinchando <a href="{{ url('/forfait') }}">aquí</a>

	Para tu comodidad <b>te llevamos el forfait a tu apartamento</b>, no tienes que esperar colas<br>

	<hr style="width: 100%"><br>
	Gracias por confiarnos tus vacaciones, haremos todo lo posible para que pases unos días agradables.<br>
	<a href="www.apartamentosierranevada.net">www.apartamentosierranevada.net</a>
</body>