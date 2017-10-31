<?php use \Carbon\Carbon; ?>
<h2 style='text-align:center;'>Condiciones Alquiler Apartamentos Miramar Ski</h2>
<hr></hr>

<br><br>
Nombre: <?php echo $data['book']->customer->name ?><br>

DNI: <?php echo $data['book']->customer->dni ?><br>

Telefono: <?php echo $data['book']->customer->phone ?>
<br><br>

<hr></hr>

<br>
<b>Fecha entrada:</b><?php echo $start = Carbon::createFromFormat('Y-m-d',$data['book']->start)->format('d-m-Y') ?> <br /><br /> 
<b>Fecha salida:</b><?php echo $finish = Carbon::createFromFormat('Y-m-d',$data['book']->finish)->format('d-m-Y') ?> <br /><br />  
<b>Noches:</b><?php echo Carbon::createFromFormat('Y-m-d', $data['book']->start)->diffInDays(Carbon::createFromFormat('Y-m-d', $data['book']->finish)) ?> <br /><br />
<b>Ocupantes:</b> <?php echo $data['book']->pax ?><br /><br /> 
<b>Apartamento: </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Nº:<?php echo $data['book']->room->name ?><br><br>  
<b>Parking: </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; PLAZA Nº:________________<br><br>  
<b>Taquilla guarda esquíes</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; TAQUILLA Nº:________________<br><br>

<hr></hr>
<br><br>
<b>Total Reserva: <?php echo $data['book']->total_price ?> </b>&euro;<br><br>
<b>Cobrado: <?php echo $data['book']->total_price - $data['pendiente'] ?> </b>&euro;<br><br>

<hr></hr>

<br><br>
<b>Pendiente de abono: <?php echo $data['pendiente'] ?>&euro;</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Forma de Pago:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<span style="border:1px black solid; position fixed">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;&nbsp;Metálico&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<span style="border:1px black solid; position fixed">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;&nbsp;Tarjeta
<br><br>

<hr></hr>

<br><br>
<b>Fianza apartamento 300 &euro;</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Forma de Pago:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<span style="border:1px black solid; position fixed">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;&nbsp;Metálico&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<span style="border:1px black solid; position fixed">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;&nbsp;Tarjeta
<br><br>

<hr></hr>

<br>
En sierra nevada a <?php echo Carbon::now()->format('d-M-Y') ?>
<br><br><br><br><br><br><br>
<b>Fdo. </b>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<b>Fdo. Jaime Díaz Fernández.</b><br><br>
<b>La salida se realizará  antes de las 12,OO</b> de la mañana de lo contrario se podrá cobrar un día (140,00€) por apartamento.<br><br>

<b>Para la devolución de Fianza</b> llamar al teléfono de <b>Manolo: 678728196 </b>


