<?php

use \Carbon\Carbon; ?>
<table>
  <thead>
<tr>  
  <th style="text-align: center; font-weight: 800;">Nombre</th>
  <th style="text-align: center; font-weight: 800;">Tipo</th>
  <th style="text-align: center; font-weight: 800;">Pax</th>
  <th style="text-align: center; font-weight: 800;">Apto</th>
  <th style="text-align: center; font-weight: 800;">IN - OUT</th>
  <th style="text-align: center; font-weight: 800;">Noches</th>
  <th style="text-align: center; font-weight: 800;">Ventas</th>
  <th style="text-align: center; font-weight: 800;">BANCO</th>
  <th style="text-align: center; font-weight: 800;">CAJA</th>
  <th style="text-align: center; font-weight: 800;">Pendiente</th>
  <th style="text-align: center; font-weight: 800;">Ingreso Neto</th>
  <th style="text-align: center; font-weight: 800;">%Benef</th>
  <th style="text-align: center; font-weight: 800;">Coste Total</th>
  <th style="text-align: center; font-weight: 800;">Coste Apto</th>
  <th style="text-align: center; font-weight: 800;">Park</th>
  <th style="text-align: center; font-weight: 800;">Sup. Lujo</th>
  <th style="text-align: center; font-weight: 800;">Limp</th>
  <th style="text-align: center; font-weight: 800;">Agencia</th>
  <th style="text-align: center; font-weight: 800;">AMENITIES</th>
  <th style="text-align: center; font-weight: 800;">TPV</th>
</tr>
</thead>
  <tbody >
<?php foreach ($books as $book): ?>
      <tr >
        <td ><?php echo $book->customer->name ?></td>
        <td>  
          <!-- type -->
          <b>
            <?php
            switch ($book->type_book) {
              case 2:
                echo "C";
                break;
              case 7:
                echo "P";
                break;
              case 8:
                echo "A";
                break;
            }
            ?>
          </b>
        </td>
        <td><?php echo $book->pax ?></td>
        <td><?php echo $book->room->nameRoom ?></td>
        <td>{{convertDateToShow_text($book->start)}} - {{convertDateToShow_text($book->finish)}}</td>
        <td><?php echo $book->nigths ?></td>
        <td>{{moneda($book->total_price)}}</td>
        <td>{{moneda($books_payments[$book->id]['banco'],false)}}</td>
        <td>{{moneda($books_payments[$book->id]['caja'],false)}}</td>
        <td>{{moneda($book->pending)}}</td>
        <td>{{moneda($book->total_ben)}}</td>
        <td><?php echo $book->inc_percent . "%" ?></td>
        <td>{{$book->cost_total}}</td>
        <td>{{moneda($book->cost_apto)}}</td>
        <td>{{moneda($book->cost_park)}}</td>
        <td>{{moneda($book->get_costLujo())}}</td>
        <td>{{moneda($book->cost_limp)}}</td>
        <td>{{moneda($book->PVPAgencia)}}</td>
        <td>{{moneda($book->extraCost)}}</td>
        <td>{{moneda($stripeCost[$book->id],false)}}</td>
      </tr>
<?php endforeach ?>

  </tbody>
</table>
