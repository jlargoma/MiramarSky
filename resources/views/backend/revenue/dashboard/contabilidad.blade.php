<?php
$disp = \App\Rooms::avail();
$pending = ($ingr_vendido-$ingr_cobrado);
if ($pending<0) $pending = 0;
?>

<div class="row">
  <div class="col-md-4 col-xs-12">
    <div>
      <canvas id="barChartContabilidad1" style="width: 100%; height: 250px;"></canvas>
    </div>
    @include('backend.revenue.dashboard._by_season')
  </div>
  <div class="col-md-2 col-xs-6">

    <table class="table table-hover table-striped table-ingresos" style="background-color: #92B6E2">
      <thead class="bg-complete" style="background: #d3e8f7">
      <th colspan="2" class="text-black text-center"> Ingresos Reservas</th>
      </thead>
      <tbody>
        <tr>
          <td class="" style="padding: 5px 8px!important; background-color: #d3e8f7!important;"><b>VENTAS TEMPORADA</b></td>
          <td class=" text-center" style="padding: 5px 8px!important; background-color: #d3e8f7!important;">
            <b>{{moneda($ingr_vendido)}}</b>
          </td>
        </tr>
        <tr style="background-color: #38C8A7;">
          <td class="text-white" style="padding: 5px 8px!important;background-color: #38C8A7!important;">
            Cobrado Temporada
          </td>
          <td class="text-white text-center" style="padding: 5px 8px!important;background-color: #38C8A7!important;">
            <b>{{moneda($ingr_cobrado)}}</b>
          </td>
        </tr>
        <tr style="background-color: #8e5ea2;">
          <td class="text-white" style="padding: 5px 8px!important;background-color: #8e5ea2!important;">Pendiente Cobro</td>
          <td class="text-white text-center" style="padding: 5px 8px!important;background-color: #8e5ea2!important;">
            <b>{{moneda($pending)}}</b>
          </td>
        </tr>
      </tbody>
    </table>
    <div >
      <canvas id="pieIng" style="width: 100%; height: 250px;"></canvas>
    </div>
    <div class="col-md-12 col-xs-6">
      <div class="col-xs-6">
        <b>Metalico:</b><br/>{{moneda($ingr_metalico)}}
      </div>
      <div class="col-xs-6">
        <b>Banco:</b><br/>{{moneda($ingr_banco)}}
      </div>
    </div>
  </div>
  <div class="col-md-2 col-xs-6">
    <h4>Indicaciones de Ocupación</h4>
    @include('backend.revenue.dashboard.blocks._contabilidad_indicadores')
  </div>
  @include('backend.revenue.dashboard.blocks._contabilidad_ff')
</div>
<style>
  .t-r-ff .resume-head .static{
    height: 59px;
  }
  .t-r-ff tbody .static{
    height: 37px;
  }
</style>
<script type="text/javascript">
  /*----------------------------------------------------------------------*/
  var data = {
      labels: [
<?php
$auxY = $year->year - 3;
for ($i = 1; $i <= 4; $i++):
  echo "'$auxY',";
  $auxY++;
endfor;
?>
      ],
      datasets: [
          {
              label: "Ingresos por Temp",
              backgroundColor: 'rgba(54, 162, 235, 0.2)',
              borderColor: 'rgba(54, 162, 235, 1)',
              borderWidth: 1,
              data: [
<?php
$auxY = $year->year - 3;
for ($i = 1; $i <= 4; $i++):
  $totalYear = \App\BookDay::getPvpByYear($auxY);
  echo "'" . $totalYear . "',";
  $auxY++;
endfor;
?>
              ],
          }
      ]
  };



  var myBarChart = new Chart('barChartContabilidad1', {
      type: 'line',
      data: data,
  });

  /*----------------------------------------------------------------------*/
  
  new Chart(document.getElementById("pieIng"), {
    type: 'pie',
    data: {
      labels: ["Cobrado", "Pendiente", ],
      datasets: [{
          label: "Population (millions)",
          backgroundColor: ["#38C8A7", "#8e5ea2"],
          data: [

            //Comprobamos si existen cobros
<?php echo round($ingr_cobrado) ?>,
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
  /*----------------------------------------------------------------------*/
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
  /*----------------------------------------------------------------------*/
</script>

