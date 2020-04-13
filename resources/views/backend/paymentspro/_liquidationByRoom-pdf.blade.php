@extends('layouts.admin-onlybody')

<?php

use \Carbon\Carbon;

setlocale(LC_TIME, "ES");
setlocale(LC_TIME, "es_ES");
?>
@section('content')
<style type="text/css">
  .total {
    font-weight: bold;
    color: black;
    background-color: rgba(0, 100, 255, 0.2) !important;
  }
  td{      
    padding: 10px 5px!important;
  }

  .table.tableRooms tbody tr td {
    padding: 10px 12px!important;
  }
  .costeApto{
    background-color: rgba(200,200,200,0.5)!important;
    font-weight: bold;
  }

  .pendiente{
    background-color: rgba(200,200,200,0.5)!important;
    font-weight: bold;
  }


  .coste{
    background-color: rgba(200,200,200,0.5)!important;
  }

  .red{
    color: red;
  }
  .blue{
    color: blue;
  }


  .btn-transparent{
    background: transparent;
    border: 0;
  }
  .btn-transparent:hover{
    color: #48b0f7;
    text-decoration: underline;
  }
  .seePropLiquidation{
    color: blue;
    cursor: pointer;
  }

</style>
<button style="z-index: 20;" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
    class="fa fa-close fa-2x"></i></button>
<div class="row">
    <?php if ($room != 'all'): ?>
    <h2 class="text-center font-w800" style="margin-top: 0;">
    <?php echo strtoupper($room->user->name) ?> (<?php echo $room->nameRoom ?>)
    </h2>
<?php else: ?>
    <h2 class="text-center font-w800" style="margin-top: 0;">
      TODOS LOS APTOS
    </h2>
<?php endif ?>

</div>

<div class="row">
  <div class="col-md-6">
    <h2 class="text-center font-w800">Listado de reservas</h2>
     <table class="table no-footer ">
            <thead>
              <th class="text-center bg-complete text-white">Cliente</th>
              <th class="text-center bg-complete text-white">Pers</th>
              <th class="text-center bg-complete text-white">IN</th>
              <th class="text-center bg-complete text-white">OUT</th>
              <th class="text-center bg-complete text-white">ING. PROP</th>
              <th class="text-center bg-complete text-white">Apto</th>
              <th class="text-center bg-complete text-white" >Park.</th>
              <?php if ($room != 'all'): ?>
                <?php if ($room->luxury == 1): ?>
                  <th class="text-center bg-complete text-white">Sup.Lujo</th>
                <?php endif ?>
              <?php endif ?>
              <th class="text-center bg-complete text-white">&nbsp;</th>
            </thead>
             <tbody>
              <?php foreach ($books as $book): ?>
                <tr>
                  <td class="text-left">{{ucfirst(strtolower($book->customer->name))}}</td>
                  <td class="text-center"><?php echo $book->pax ?> </td>
                  <td class="text-center">{{convertDateToShow_text($book->start,true)}}</td>
                  <td class="text-center">{{convertDateToShow_text($book->finish,true)}}</td>
                  <td class="text-center total">
                    <?php 
                    $cost = 0;
                    if ($book->type_book != 7 && $book->type_book != 8)
                      $cost = ($book->cost_apto + $book->cost_park + $book->cost_lujo) 
                    ?>
                    {{moneda($cost,false,2)}}
                  </td>
                  <td class="text-center">
                    <?php 
                    if ($book->type_book != 7 && $book->type_book != 8): ?>
                    {{moneda($book->cost_apto,false,2)}}
                    <?php else: ?>
                      ---€
                    <?php endif ?>
                  </td>
                  <td class="text-center" style="padding: 8px; ">
                    <?php if ($book->type_book != 7 && $book->type_book != 8): ?>
                     {{moneda($book->cost_park,false,2)}}
                    <?php else: ?>
                      ---€
                    <?php endif ?>
                  </td>
                  <?php 
                    if ($room != 'all'):
                      if ($room->luxury == 1 && $book->type_book != 7 && $book->type_book != 8):
                        if ($this->type_luxury == 1 || $this->type_luxury == 3 || $this->type_luxury == 4):
                          ?>
                          <td class="text-center">
                          {{moneda($book->cost_lujo,false,2)}}
                          </td>
                        <?php
                        endif;
                      endif;
                    endif;
                  ?>
                  <?php if (!empty($book->book_owned_comments) && $book->promociones != 0): ?>
                    <td class="text-center" style="padding: 8px; ">
                      <img src="/pages/oferta.png" style="width: 40px;">
                    </td>
                  <?php endif ?>
                </tr>
                <?php endforeach ?>
            </tbody>
            
     </table>
  </div>
  <div class="col-md-6">    
       <div class="row push-20" style="padding: 10px; background-color: rgba(16,207,189,0.5);">
        <h4 class="text-left " style="line-height: 1; letter-spacing: -1px;">
          Liquidación:<br>
          <span class="font-w800"><?php echo $startDate ?></span> -
          <span class="font-w800"><?php echo $finishDate ?></span>
        </h4>
        <table class="table table-bordered table-hover  no-footer" id="basicTable" role="grid">
          <tr>
            <th class="text-center bg-complete text-white">ING. PROP</th>
            <th class="text-center bg-complete text-white">Apto</th>
            <th class="text-center bg-complete text-white">Park</th>
            <?php if ($room != 'all'): ?>
              <?php if ($room->luxury == 1): ?>
                <th class="text-center bg-complete text-white">Sup.Lujo</th>
              <?php endif ?>
            <?php endif ?>
          </tr>
          <tr>
            <td class="text-center total">{{moneda($total,false)}}</td>
            <td class="text-center">{{moneda($apto,false)}}</td>
            <td class="text-center">{{moneda($park,false)}}</td>
            <?php if ($room != 'all' && $room->luxury == 1): ?>
                <td class="text-center">
                  {{moneda($lujo,false)}}
                </td>
            <?php endif ?>
          </tr>
        </table>
      </div>
  </div>
     
  
</div>
<div class="row push-20">
  <div class="col-md-12 col-xs-12 resumen blocks">
    <div class="col-md-6 col-xs-12 ">
      <div class="row">
        <div class="col-md-12">
          <h2 class="text-center font-w800">Listado de reservas</h2>
        </div>
     
      </div>
    </div>
    <div class="col-md-6 col-xs-12 " >
     

      <div class="row">
        <h2 class="text-center font-w800">Gastos</h2>
      </div>
      <div class="row">
        <table class="table table-bordered no-footer">
          <?php $sumPagos = 0; ?>
          <?php if (count($pagos) > 0): ?>
            <?php foreach ($pagos as $pago): ?>
    <?php $sumPagos += $pago->import ?>
              <tr>
                <td class="text-center">
    <?php echo Carbon::createFromFormat('Y-m-d', $pago->date)->format('d-m-Y') ?>
                </td>
                <td class="text-center">
    <?php echo $pago->concept ?>
                </td>
                <td class="text-center">
                  <?php
                  $divisor = 0;
                  if (preg_match('/,/', $pago->PayFor)) {
                    $aux = explode(',', $pago->PayFor);
                    for ($i = 0; $i < count($aux); $i++) {
                      if (!empty($aux[$i])) {
                        $divisor++;
                      }
                    }
                  } else {
                    $divisor = 1;
                  }
                  $expense = $pago->import / $divisor;
                  ?>
                  <?php echo number_format($expense, 2, ',', '.') ?>€

                  <?php $pagototalProp += $expense; ?>
                </td>
                <td class="text-center"><?php echo number_format($total - $pagototalProp, 2, ',', '.'); ?></td>
              </tr>
            <?php endforeach ?>
          <?php else: ?>
            <tr>
              <td colspan="4" class="text-center">
                No hay pagos para este apartamento
              </td>
            </tr>
          <?php endif ?>
        </table>
        <table class="table table-bordered no-footer">
          <tr>
            <td class="text-center white" style="background-color: #48b0f7;">
              <h5 class="text-center white" style="margin-top: 0">GENERADO</h5>
              <strong><?php echo number_format($total, 2, ',', '.'); ?>€</strong>
            </td>
            <td class="text-center white" style="background-color: #10cfbd;">
              <h5 class="text-center white" style="margin-top: 0">PAGADO</h5>
              <strong><?php echo number_format($pagototalProp, 2, ',', '.'); ?>€</strong>
            </td>
            <td class="text-center white" style="background-color: #f55753;">
              <h5 class="text-center white" style="margin-top: 0">PENDIENTE</h5>
              <strong><?php echo number_format(($total - $pagototalProp), 2, ',', '.'); ?>€</strong>
            </td>
          </tr>
        </table>
      </div>
    </div>
  </div>

  @endsection