
<style>
  .table-ingresos , .table-ingresos >tbody> tr > td{
    background-color: #92B6E2!important;
    margin: 0px ;
    padding: 5px 8px;
  }
  .table-cobros , .table-cobros >tbody> tr > td{
    background-color: #38C8A7!important;
    margin: 0px ;
    padding: 5px 8px;
  }
  .tr-cobros:hover{
    background-color: #2ca085!important;
  }
  .tr-cobros:hover td {
    background-color: #2ca085!important;
  }
  .fa-arrow-up{
    color: green;
  }
  .fa-arrow-down{
    color: red;
  }
  .bg-complete-grey{
    background-color: #92B6E2!important;
  }
  .bordered{
    padding: 15px;
    border:1px solid #e8e8e8;
    background: white;
  }
</style>

<?php 
//$dataStats = \App\http\Controllers\LiquidacionController::getSalesByYear(); 
$pending = ($vendido-$cobrado);
if ($pending<0) $pending = 0;
?>
<div class="col-lg-3 col-md-6 col-xs-12">

  <table class="table table-hover table-striped table-ingresos" style="background-color: #92B6E2">
    <thead class="bg-complete" style="background: #d3e8f7">
    <th colspan="2" class="text-black text-center"> Ingresos Temporada</th>
    </thead>
    <tbody>
      <tr>
        <td class="" style="padding: 5px 8px!important; background-color: #d3e8f7!important;"><b>VENTAS TEMPORADA</b></td>
        <td class=" text-center" style="padding: 5px 8px!important; background-color: #d3e8f7!important;">
          <b><?php echo number_format(round($vendido), 0, ',', '.') ?> €</b>
        </td>
      </tr>
      <tr style="background-color: #38C8A7;">
        <td class="text-white" style="padding: 5px 8px!important;background-color: #38C8A7!important;">
          Cobrado Temporada
        </td>
        <td class="text-white text-center" style="padding: 5px 8px!important;background-color: #38C8A7!important;">
          <?php echo number_format(round($cobrado), 0, ',', '.') ?> € 
        </td>
      </tr>
      <tr style="background-color: #8e5ea2;">
        <td class="text-white" style="padding: 5px 8px!important;background-color: #8e5ea2!important;">Pendiente Cobro</td>
        <td class="text-white text-center" style="padding: 5px 8px!important;background-color: #8e5ea2!important;">
          <?php echo number_format(round($pending), 0, ',', '.') ?> €
        </td>
      </tr>
    </tbody>
  </table>

  <div>
    <canvas id="pieIng" style="width: 100%; height: 250px;"></canvas>
  </div>
</div>

<div class="col-lg-3 col-md-6 col-xs-12">

  <table class="table table-hover table-striped table-cobros" style="background-color: #38C8A7">
    <thead style="background-color: #38C8A7">
    <th colspan="2" class="text-white text-center">Cobros Temporada</th>
    </thead>
    <tbody style="background-color: #38C8A7">
      <tr class="tr-cobros">
        <th class="text-white" style="padding: 5px 8px!important;background-color: #38C8A7!important;">TOTAL COBRADO</th>
        <th class="text-white text-center" style="padding: 5px 8px!important;background-color: #38C8A7!important;">
          <?php echo number_format(round($cobrado), 0, ',', '.') ?> €
        </th>
      </tr>
      <tr class="tr-cobros">
        <td class="text-white" style="padding: 5px 8px!important; background-color: #2ba840!important;">Metalico</td>
        <td class="text-white text-center" style="padding: 5px 8px!important; background-color: #2ba840!important;">
          <?php echo number_format(round($metalico), 0, ',', '.') ?> €
        </td>
      </tr>
      <tr class="tr-cobros">
        <td class="text-white" style="padding: 5px 8px!important;background-color: #2ca085!important;">Banco</td>
        <td class="text-white text-center" style="padding: 5px 8px!important;background-color: #2ca085!important;">
          <?php echo number_format(round($banco), 0, ',', '.') ?> €
        </td>
      </tr>

    </tbody>
  </table>
  <div>
    <canvas id="pieCobros" style="width: 100%; height: 250px;"></canvas>
  </div>
</div>

@if($ffData)
<div class="col-lg-6 col-md-6 col-xs-12">
    <div class="col-md-6 col-sm-12">
      <table class="table table-hover table-striped table-ingresos" style="background-color: #92B6E2">
        <thead class="bg-complete" style="background: #d3e8f7">
        <th colspan="2" class="text-black text-center"> Ingresos Temporada - Forfaits</th>
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
    
  <div class="col-md-6 col-sm-12" style="font-size:16px;">
       <div class="row bg-white push-30">
        <div class="col-md-6 bordered text-center">
          <b class="hint-text">Cobrado Temporada</b>
          <b ><?php echo number_format(round($ffData['pay']), 0, ',', '.') ?> €</b>
        </div>
        <div class="col-md-6 bordered text-center">
          <b class="hint-text bold">Vendido Temporada</b>
          <b ><?php echo number_format(round($ffData['total']), 0, ',', '.') ?> €</b>
        </div>
        <div class="col-md-6 bordered text-center">
          <b class="hint-text">Total de Ordenes</b>
          <div class="p-l-20">
            <b ><?php echo $ffData['q']; ?></b>
          </div>
        </div>
        <div class="col-md-6 bordered text-center">
          <b class="hint-text">Promedio por Orden</b>
            <b >
              <?php 
              $promedio = 0;
              if ($ffData['q']>0){
                $promedio = round($ffData['total'])/$ffData['q'];
              }
              echo number_format(round($promedio), 0, ',', '.')
              ?>
              €</b>
        </div>
      </div>
    </div>
    </div>
  
  @endif


<script type="text/javascript">

  new Chart(document.getElementById("pieIng"), {
    type: 'pie',
    data: {
      labels: ["Cobrado", "Pendiente", ],
      datasets: [{
          label: "Population (millions)",
          backgroundColor: ["#38C8A7", "#8e5ea2"],
          data: [

            //Comprobamos si existen cobros
<?php echo round($cobrado) ?>,
<?php echo round($pending) ?>,
          ]
        }]
    },
    options: {
      title: {
        display: false,
        text: 'Ingresos de la temporada'
      }
    }
  });
  
  new Chart(document.getElementById("pieCobros"), {
    type: 'pie',
    data: {
      labels: ["Metalico", "Banco", ],
      datasets: [{
          backgroundColor: ["#2ba840", "#2ca085"],
          data: [
            //Comprobamos si existen cobros
<?php echo round($metalico) ?>,
<?php echo round($banco) ?>,
          ]
        }]
    },
    options: {
      title: {
        display: false,
        text: ''
      }
    }
  });
  @if($ffData)
   new Chart(document.getElementById("pieIngFF"), {
        type: 'pie',
        data: {
          labels: ["Cobrado", "Pendiente", ],
          datasets: [{
              label: "Population (millions)",
              backgroundColor: ["#38C8A7", "#ef6464"],
              data: [
                //Comprobamos si existen cobros
              <?php echo round($ffData['pay']) ?>,
              <?php echo round($ffData['to_pay']) ?>,
              ]
            }]
        },
        options: {
          title: {
            display: false,
            text: 'Ingresos de la temporada'
          }
        }
      });
@endif
</script>
