<?php use \Carbon\Carbon;  setlocale(LC_TIME, "ES"); setlocale(LC_TIME, "es_ES"); ?>
<div class="row reservas resumen blocks">
<h2 class="text-center push-10" style="font-size: 24px;"><b>Listado de Reservas</b></h2>
<div class="row table-responsive" style="border: none;">
  <table class="table table-hover no-footer" id="basicTable" role="grid" style="margin-bottom: 17px!important">
      <thead>
          <th style="width: 25%">Cliente</th>
          <th style="width: 5%">Pers</th>
          <th>Entrada</th>
          <th>Salida</th>
          <th>Tot. Ing</th>
          <th>Apto</th>
          <th>Parking</th>
          <th>Luz</th>
          <th>Sup.Lujo</th>
          <th style="width: 50px!important">   &nbsp;      </th>
      </thead>
      <tbody>
          <?php foreach ($books as $book): ?>
              <tr>
                  <td class="text-center"><?php echo ucfirst(strtolower($book->customer->name)) ?> </td>
                  <td class="text-center"><?php echo $book->pax ?> </td>
                  <td class="text-center">{{dateMin($book->start)}}</td>
                  <td class="text-center">{{dateMin($book->finish)}}</td>
                  <td class="text-center">{{moneda($book->get_costProp())}}</td>
                  <td class="text-center">{{moneda($book->cost_apto,false)}}</td>
                  <td class="text-center">{{moneda($book->cost_park,false)}} </td>
                  <td class="text-center">{{moneda($book->luz_cost,false)}} </td>
                  <td class="text-center">{{moneda($book->cost_lujo,false)}}</td>
                  <td class="text-center">
                      <?php if (!empty($book->book_owned_comments)): ?>
                          <img src="/pages/oferta.png" style="width: 40px;" title="<?php echo $book->book_owned_comments ?>">
                      <?php endif ?>
                  </td>
              </tr>
          <?php endforeach ?>
      </tbody>
  </table>
</div>
</div>