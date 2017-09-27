Hola "<?php echo $book->customer->name ?>" hemos bloqueado parcialmente un apartamento en respuesta a tu solicitud:<br/><br/>

<hr/>
<h2>Tiene hasta mañana a las 12:00 am para realizar el pago de la señal(25% del total) = <?php echo (int)((int)$book->total_price * 0.25) ;?>  a estos datos bancarios:
<br>
<h2>Titular:  <span style='color:red'>Jorge Largo</span><h2>
<h2>Concepto: <span style='color:red'>Señal Reserva MiramarSKi</span><h2>
<h2>Ordenante: <span style='color:red'><?php echo $book->customer->name ?></span></h2>
<h2>Datos Bancarios: <span style='color:red'>La Caixa</span></h2>
<h2>IBAN: <span style='color:red'>ES19 2100 1875 0502 0021 0464</span></h2>
<h2>BIC(SWIFT): <span style='color:red'>CAIXESBBXXX</span></h1>    

<strong>Una vez recibamos el pago de la señal, el apartamento quedará bloquedo y tu recibiras un email con la confirmación.</strong>

Consulta nuestras condiciones de contratación <a href='http://www.apartamentosierranevada.net/condiciones-generales.html'>aquí</a>
<br/>Un cordial saludo.

Instrucciones de pago señal reserva: APARTAMENTO MIRAMAR SKI