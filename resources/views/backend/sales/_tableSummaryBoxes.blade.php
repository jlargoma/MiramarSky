<style type="text/css">
  .bordered{
    padding: 15px;
    border:1px solid #e8e8e8;
    background: white;
  }
</style>

<div class="row">
  <div class="col-md-3 col-lg-2">
    <div class="col-xs-6 bordered">
      <label>Total Reservas</label>
      <h3 class="text-black font-w400 text-center"><?php echo count($books) ?></h3>
    </div>
    <div class="col-xs-6 bordered">
      <label>Nº Inquilinos</label>
      <h3 class="text-black font-w400 text-center"><?php echo $data['num-pax'] ?></h3>
    </div>
  </div>
  <div class="col-md-9 col-lg-7">
    <div class="col-md-3 col-xs-12 bordered">
      <label>Total vnts temp</label>
      <h3 class="text-black font-w400 text-center"><?php echo number_format($totales['total'], 0, ',', '.'); ?>€</h3>
    </div>
    <div class="col-md-2 col-xs-6 bordered">
      <label>Ing neto reservas</label>
      <h3 class="text-black font-w400 text-center"><?php echo number_format($totales['beneficio'], 0, ',', '.'); ?>€</h3>
    </div>
    <div class="col-md-2 col-xs-6 bordered">
      <label>% benef reservas</label>

      <?php
      $totoalDiv = ($totales["total"] == 0) ? 1 : $totales["total"];
      $benef = round(($totales["beneficio"] / $totoalDiv ) * 100, 2);
      ?>
      <h3 class="text-black font-w400 text-center"><?php echo $benef ?>%</h3>
    </div>
    <div class="col-md-2 col-xs-6 bordered">
      <label>Venta propia</label>
      <h3 class="text-black font-w400 text-center"><?php echo round($data['propios']) ?>%</h3>
    </div>
    <div class="col-md-2 col-xs-6 bordered">
      <label>Venta agencia</label>
      <h3 class="text-black font-w400 text-center"><?php echo round($data['agencia']) ?>%</h3>
    </div>
  </div>
  <div class="col-lg-3 col-md-12">
    <div class="col-xs-4 bordered">
      <label>Estancia media</label>
      <h3 class="text-black font-w400 text-center"><?php echo round($data['estancia-media']) ?></h3>
    </div>
    <div class="col-xs-4 bordered">
      <label>Total Noches</label>
      <h3 class="text-black font-w400 text-center"><?php echo $data['days-ocupation'] + $data['dias-propios'] ?></h3>
    </div>
    <div class="col-xs-4 bordered">
      <label>Dias totales temp</label>
      <h3 class="text-black font-w400 text-center">
        <input class="form-control text-black font-w400 text-center seasonDays" value="<?php echo $data['total-days-season'] ?>" style="border: none; font-size: 32px;margin: 10px 0;color:red!important"/>
      </h3>
    </div>
  </div>
</div>