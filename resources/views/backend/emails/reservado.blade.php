<?php use \Carbon\Carbon; setlocale(LC_TIME, "ES"); setlocale(LC_TIME, "es_ES"); ?>
Hola "<?php echo $book->customer->name ?>" hemos bloqueado parcialmente un apartamento en respuesta a tu solicitud:<br/><br/>

<br>

<b>Nombre: <?php echo $book->customer->name ?> .<br><br>
Teléfono: <?php echo $book->customer->phone ?>.<br><br>
Email: <?php echo $book->customer->email ?>.<br><br>
Apartamento: <?php echo $book->room->sizeRooms->name ?> // <?php echo ($book->type_luxury == 1)? "Lujo" : "Estandar" ?><br><br>
Nº: <?php echo $book->pax ?> Pers <br><br>
Fechas: <?php echo Carbon::createFromFormat('Y-m-d',$book->start)->format('d-M') ?> - <?php echo Carbon::createFromFormat('Y-m-d',$book->finish)->format('d-M') ?> <br><br>
Noches: <?php echo $book->nigths ?>  <br><br>
<?php if ($book->type_luxury != 2): ?>
	Sup. Lujo: <?php echo number_format($book->sup_lujo,2,',','.') ?> €<br><br>
<?php endif ?>
<?php if ($book->type_park != 2): ?>
	Parking: <?php echo number_format($book->sup_park,2,',','.'); ?> €<br><br>
<?php endif ?>
Precio total: <?php echo number_format($book->total_price,2,',','.') ?> € <br><br></b>
El precio te incluye todo, piscina climatizada, gimnasio, taquilla guarda esquíes <?php if ($book->type_park != 2): ?>y parking cubierto <?php endif ?>  . <br>
<br>
En todas nuestra reservas están incluidas las Sábanas y toallas. <br>
<br>

<hr/>

<h2><u>Pago de la reserva</u></h2>
<br>
<b>Dispones de un plazo de 24 horas para realizar el pago de la señal </b> <b style="color: red">25% del total = <?php echo number_format(($book->total_price*0.25),2,',','.') ?> €</b>  a estos datos bancarios: 
<h2>Titular:  <span style='color:red'>ISDE SL</span><h2>
<h2>Concepto: <span style='color:red'>Señal MiramarSKi - <?php echo $book->customer->name ?></span><h2>
<h2>Ordenante: <span style='color:red'><?php echo $book->customer->name ?></span></h2>
<h2>Datos Bancarios: <span style='color:red'>La Caixa</span></h2>
<h2>IBAN: <span style='color:red'>ES68 2100 1875 0502 0022 5878</span></h2>
<h2>BIC(SWIFT): <span style='color:red'>CAIXESBBXXX</span></h1>    

<b><strong>Una vez recibamos el pago de la señal, el apartamento quedará bloquedo y tu recibiras un email con la confirmación.</strong><br>

Consulta nuestras condiciones de contratación <a href='http://www.apartamentosierranevada.net/condiciones-generales.html'>aquí</a></b><br><br>

<hr>


<h2><b><u>Condiciones generales de Alquiler</u></b></h2><br>

<b>*Hora de Entrada: Desde las 17,00h a 19,00h.<u> Si vas a llegar más tarde tienes que avisarnos y podrías tener un cargo adicional por las horas de espera. </u>Consulta nuestras condiciones de contratación <a href='http://www.apartamentosierranevada.net/condiciones-generales.html'>aquí</a></b><br><br>

<b>*Hora de Salida: La vivienda debe estar desocupada antes de las 12,00 a.m.</b><br>

<b>*Fianza: </b>Además del precio del alquiler <b> el día de llegada se pedirá una fianza por el importe de 300€ a la entrega de llaves</b> para garantizar el buen uso de la vivienda. <br>
La fianza se devolverá a la entregada de llaves, una vez revisada la vivienda <br><br><br>

<b>*2º Pago:</b> 15 días antes de la entrada se deberá <b>abonar otro 25%</b> <br><br>

<b>*El resto del pago (50%) se realizará en metálico</b> a la entrega de llaves <b> + la fianza de 300 €.</b><br> <br>

* Nº de personas: El apartamento no podrá ser habitado por más personas de las camas que dispone.<br><br>

<b>*No se admiten animales.</b><br><br>

<b>*Sabanas y Toallas están incluidas</b><br><br>

En el caso de NO cumplir con lo establecido no se podrá ocupar la vivienda. Puedes consultar todas las condiciones generales de alquiler <a href='http://www.apartamentosierranevada.net/condiciones-generales.html'>aquí</a><br><br>

<hr>
<h2><u><b>Servicios adicionales</b></u></h2><br>

Te ofrecemos sin coste añadido y con un descuento especial que hemos pactado con el proveedor para vosotros:<br><br>

<b>*Descuentos en  forfait <br>
*Descuentos en tus clases y cursillos de esquí<br>
*Descuentos en Alquiler de material</b><br><br>

Para solicitar alguno de estos servicios solo es necesario que rellenes un formulario <a href="https:\\apartamentosierranevada.net/forfait">pinchando aquí</a><br><br>

<hr>

<h2><b><u>Otros servicios</u></b></h2><br>

Para tu comodidad <b>te llevamos el forfait a tu apartamento,</b> no tienes que esperar colas
Te <b> facilitamos un supermercado</b> que te lo lleva a tu casa,  <a href="https:\\apartamentosierranevada.net/forfait">pinchando aquí</a><br><br>

<hr><br>

Gracias por confiarnos tus vacaciones, haremos todo lo posible para que pases unos días agradables. <br>
<a href="https://www.apartamentosierranevada.net">www.apartamentosierranevada.net</a>
