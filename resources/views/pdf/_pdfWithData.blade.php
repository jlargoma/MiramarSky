<?php

use \Carbon\Carbon; ?>
<style>
  .page-break {
    page-break-after: always;
  }
</style>
<?php for ($i = 0; $i <= 1; $i++): ?>
  <h2 style="font-weight: 800; color: red; text-align: center; font-family: 'Verdana'; font-size: 20px;">Documento Check In</h2>
  <p style="color: black; font-family: 'Verdana';margin-bottom: 0;font-size: 11px; text-align: justify;">
    <b>Nombre: <?php echo ucfirst($data['book']->customer->name) ?></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    @if(trim($data['book']->customer->DNI) != '')
    <b>DNI: {{$data['book']->customer->DNI}} </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    @else
    <b>DNI: _______________ </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    @endif
    <b>Dirección: <?php echo ($data['book']->customer->address) ? $data['book']->customer->address : "_________________________"; ?></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <b>Teléfono: <?php echo ($data['book']->customer->phone) ? $data['book']->customer->phone : "___________"; ?></b>
    <br>------------------------------------------------------------------------------------------------------<br> 
    <b>Fecha reserva: 
      <?php
      echo Carbon::createFromFormat('Y-m-d', $data['book']->start)->formatLocalized('%d %b') . " - " . Carbon::createFromFormat('Y-m-d', $data['book']->finish)->formatLocalized('%d %b') . " " . Carbon::createFromFormat('Y-m-d', $data['book']->finish)->format('Y')
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
  </p>
  <h2 style="font-weight: 800; color: red; text-align: center; font-family: 'Verdana'; font-size: 18px;">
    Condiciones Alquiler Apartamentos Miramar Ski
  </h2>

  <p style="color: black; font-family: 'Verdana';margin-bottom: 30px;font-size: 12px; text-align: justify;">
    <b>Hora de Entrada:</b> Desde las 17,30h a 19,00h en el caso de llegar más tarde les dejaremos las llaves en una caja de seguridad y les mandaremos instrucciones de cómo acceder a su apartamento.
<br><br>
    <b>Hora de Salida:</b> La vivienda deberá ser desocupada antes de las 11,59h a.m (de lo contrario se podrá cobrará una noche más de alquiler apartamento según tarifa apartamento y ocupación. La plaza de garaje debe quedar libre a esta hora o bien pagar la estancia de un nuevo día.
<br><br>
    <b>Fianza:</b> Según nuestras condiciones aceptadas, <b>No realizamos ningún cargo en tu tarjeta, tan solo nos has dado una  preautorización por si se produjeran desperfectos y 24 horas después de tu check out está preautorización desaparecerá.</b> Establecemos el importe máximo de la fianza en 300€ pero en el caso de que se vaya a realizar algún cargo por desperfectos siempre tendrás la opción de revisarlo y ver las pruebas aportadas.
<br><br>
    <b>Pago de la reserva:</b> El apartamento debe estar completamente abonado a la entrega de llaves. En el caso de no cumplir con lo establecido no se podrá ocupar la vivienda. 
<br><br>    
    <b>Periodo del alquiler:</b> Por el motivo que sea si la persona que alquila decide marcharse antes del periodo contratado no tiene derecho a devolución del importe de los días no disfrutados.
<br><br>    
    <b>Meteorología y estado de pistas:</b> Las condiciones del alquiler de la vivienda son completamente ajenas a las condiciones meteorológicas, al estado de las carreteras, al estado de las pistas de esquí, falta de nieve o incluso al cierre de la estación por lo que tampoco se podrá reclamar devolución por estos motivos. 
<br><br>    
    <b>Nº de personas:</b> El apartamento no podrá ser habitado por más personas de las camas que dispone. 
<br><br>    
    <b>No se admiten animales:</b> ningún tipo de animales de compañía ni mascotas. 
<br><br>    
    <b>Sabanas y Toallas están incluidas en todas las reservas.</b>
<br><br>    
    <b>Se ruega guardar silencio en los apartamentos y en las zonas comunes a partir de las 23 hrs</b>, por respeto al sueño y a la tranquilidad de los demás inquilinos y propietarios del edificio Miramarski. 
<br><br>
    <b>Checkout : El alojamiento se deberá entregar antes de las 12:00 con : </b><br><br>
    <b>Si algunos de estos requisitos no se cumplen podría conllevar la perdida de la fianza, total o parcialmente: </b>
    •	Estado de limpieza aceptable.<br>
    •	Vajilla limpia y recogida.<br>
    •	Muebles de cama en la misma posición que se entregaron.<br>
    •	<b>Sin basuras en el apartamento.</b><br>
    •	Nevera vacía (sin comida sobrante).<br>
    •	Edredones doblados en los armarios.<br>
    <br>
    <b>Devolución de llaves:</b> las puedes dejar en la cocina de tu apartamento <br>
    <b>(Cuidado: primero necesitas el mando a distancia para sacar que sacar el coche del parking) </b>
    Confirmo que se me ha informado y acepto las condiciones del alquiler de la vivienda que se detallan en este documento.<br/>

    @if(trim($data['book']->customer->accepted_hiring_policies) != '')
    <br><b style="color:red">ACEPTADO: <?php echo date_policies($data['book']->customer->accepted_hiring_policies); ?></b>
    @endif
  </p>
  <span style="position: absolute; bottom: 0px; left: 0px;">
    Firmado: <b><?php echo ucfirst($data['book']->customer->name) ?></b>
  </span>

  <span style="position: absolute; bottom: 0px; right: 0px;">
    Firmado: <b>Jaime Díaz Fernández</b>
  </span>
  <div class="page-break"></div>
<?php endfor; ?>