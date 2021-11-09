<div class="col-md-2 col-xs-6">
  <table class="table table-hover table-striped table-ingresos" style="background-color: #92B6E2">
    <thead class="bg-complete" style="background: #d3e8f7">
    <th colspan="2" class="text-black text-center"> Ingresos Forfaits</th>
    </thead>
    <tbody>
      <tr>
        <td class="" style="padding: 5px 8px!important; background-color: #d3e8f7!important;"><b>VENTAS TEMPORADA</b></td>
        <td class=" text-center" style="padding: 5px 8px!important; background-color: #d3e8f7!important;">
          <b><?php echo number_format(round($ffData['total']), 0, ',', '.') ?> €</b>
        </td>
      </tr>
      <tr style="background-color: #38C8A7;">
        <td class="text-white" style="padding: 5px 8px!important;background-color: #38C8A7!important;">
          Cobrado Temporada
        </td>
        <td class="text-white text-center" style="padding: 5px 8px!important;background-color: #38C8A7!important;">
          <?php echo number_format(round($ffData['pay']), 0, ',', '.') ?> € 
        </td>
      </tr>
      <tr style="background-color: #ef6464;">
        <td class="text-white" style="padding: 5px 8px!important;background-color: #ef6464!important;">Pendiente Cobro</td>
        <td class="text-white text-center" style="padding: 5px 8px!important;background-color: #ef6464!important;">
          <?php echo number_format(round($ffData['to_pay']), 0, ',', '.') ?> €
        </td>
      </tr>
    </tbody>
  </table>
  <div >
    <canvas id="pieIngFF" style="width: 100%; height: 250px;"></canvas>
  </div>
</div>
<div class="col-md-2 col-xs-6">
  
  
  <div class="row contabilidad">
    <div class="sumary bordered mobil_1">
      <label>Cobrado Temporada</label>
      <h4 class="text-black font-w400 text-center">{{moneda($ffData['pay'])}}</h4>
    </div>
    <div class="sumary bordered mobil_1">
      <label>Vendido Temporada</label>
      <h4 class="text-black font-w400 text-center">{{moneda($ffData['total'])}}</h4>
    </div>
    <div class="sumary bordered mobil_1">
      <label>Total de Ordenes</label>
      <h4 class="text-black font-w400 text-center">{{$ffData['q']}}</h4>
    </div>
    <div class="sumary bordered mobil_1">
      <label>Promedio por Orden</label>
      <h4 class="text-black font-w400 text-center">
        <?php
        $promedio = 0;
        if ($ffData['q'] > 0) {
          $promedio = round($ffData['total']) / $ffData['q'];
        }
        echo moneda($promedio)
        ?>
        </h4>
    </div>
  </div>
</div>