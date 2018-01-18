<?php use \Carbon\Carbon;  setlocale(LC_TIME, "ES"); setlocale(LC_TIME, "es_ES"); ?>

Hola <b><?php echo $book->customer->name ?>, </b>hemos recibido tu pago en concepto de señal, <b><u>tu reserva está confirmada</u></b>.
<br>
<br>
Nombre: <b><?php echo $book->customer->name ?></b> .<br>
<u>Teléfono</u>: <b><a href="tel:<?php echo $book->customer->phone ?>"><?php echo $book->customer->phone ?></a></b>.<br>
Email: <b><?php echo $book->customer->email ?></b>.<br>
Apartamento: <b><?php echo $book->room->sizeRooms->name ?> // <?php echo ($book->type_luxury == 1)? "Lujo" : "Estandar" ?></b><br>
Nº: <b><?php echo $book->pax ?> Pers </b><br>
Fechas: <b><?php echo Carbon::createFromFormat('Y-m-d',$book->start)->format('d-M') ?> - <?php echo Carbon::createFromFormat('Y-m-d',$book->finish)->format('d-M') ?></b> <br>
Noches: <b><?php echo $book->nigths ?> </b> <br>
<?php if ($book->type_luxury != 2): ?>
	Sup. Lujo: <b><?php echo number_format($book->sup_lujo,2,',','.') ?> €</b><br>
<?php endif ?>
<br>
<b>Precio total: <?php echo number_format($book->total_price,2,',','.') ?> € </b><br>
<br>
El precio te incluye todo, piscina climatizada, gimnasio, taquilla guarda esquíes <?php if ($book->type_park != 2): ?>y parking cubierto <?php endif ?>  . <br>
<br>
En todas nuestra reservas están incluidas las Sábanas y toallas. <br>
<br>
<hr style="width: 100%">

<h2><b><u>Condiciones generales de Alquiler</u></b></h2><br>
<b>*Hora de Entrada: Desde las 17,00h a 19,00h. <u>Si vas a llegar más tarde tienes que avisarnos y podrías tener un cargo adicional por las horas de espera.</u> Consultar condiciones </b><a href="{{ url('/condiciones-generales') }}">aquí</a>.<br>
<br>
<b>*Hora de Salida: La vivienda debe estar desocupada antes de las 12,00 a.m.</b><br>
<br>
<b>*Fianza:</b> Además del precio del alquiler <b>el día de llegada se pedirá una fianza por el importe de 300€ a la entrega de llaves</b> para garantizar el buen uso de la vivienda. <br>
La fianza se devolverá a la entregada de llaves, una vez revisada la vivienda .<br>
<br>
<b>*2º Pago:</b> 15 días antes de la entrada se deberá <b>abonar otro 25%</b> .<br>
<br>
<b>*El resto del pago (50%) se realizará en metálico a la entrega de llaves + la fianza de 300 €.</b> <br>
* Nº de personas: El apartamento no podrá ser habitado por más personas de las camas que dispone.<br>
<br>
<b>*No se admiten animales.</b><br>
<br>
<b>*Sabanas y Toallas están incluidas.</b><br>
<br>
En el caso de NO cumplir con lo establecido no se podrá ocupar la vivienda. Puedes consultar todas las condiciones generales de alquiler <a href="{{ url('/condiciones-generales') }}">aquí</a>.<br>
<br>
<hr style="width: 100%">

<h2><u><b>Servicios adicionales</b></u></h2><br>

Te ofrecemos sin coste añadido y con un descuento especial que hemos pactado con el proveedor para vosotros:<br>
<br>
<b>*Descuentos en  forfait .<br>
*Descuentos en tus clases y cursillos de esquí.<br>
*Descuentos en Alquiler de material.<br></b>
<br>
Para solicitar alguno de estos servicios solo es necesario que rellenes un formulario <a href="https://www.apartamentosierranevada.net/forfait/">pinchando aquí</a>.<br>
<br>
<hr style="width: 100%">

<h2><b><u>Otros servicios</u></b></h2>

Para tu comodidad <b>te llevamos el forfait a tu apartamento,</b> no tienes que esperar colas.<br>
Te <b>facilitamos un supermercado</b> que te lo lleva a tu casa,  <a href="https://www.apartamentosierranevada.net/forfait/">pinchando aquí</a>.<br>

<hr style="width: 100%">

<h3>Gracias por confiarnos tus vacaciones, haremos todo lo posible para que pases unos días agradables. </h3>

<a href="www.apartamentosierranevada.net">www.apartamentosierranevada.net</a>
