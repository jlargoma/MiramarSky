<?php
$total = 0;
$tSite = [];
$aPKI = ['pvp' => 0, 'rva' => 0, 'nigth' => 0, 'vProp' => 0, 'vAgen' => 0];
$disp = App\Rooms::avail();

if ($books) {
  foreach ($books as $b) {
    $aPKI['pvp'] += $b->pvp;
    if ($b->agency > 0)
      $aPKI['vAgen'] += $b->pvp;
    else
      $aPKI['vProp'] += $b->pvp;
  }
  $aPKI['nigth'] = $nights;
  $aPKI['rva'] = $rvas;
}
//dd($aPKI);
$k = 0;
?>
<div class="table-responsive">
  <table class="tableMonths" >
    <tr>
      <td data-k="0" class="sm <?php if ($month == 0) echo 'active' ?>" >AÑO</td>
      @foreach($lstMonths as $k=>$v)
      <td data-k="{{$k}}" class="sm <?php if ($month == $k) echo 'active' ?> ">{{$v}}</td>
      @endforeach
    </tr>
  </table>  
</div>
<div class="table-responsive">
  <table class="table table-summary" style="max-width:940px">
    <td>Total: {{moneda($aPKI['pvp'])}}</td>
  </table>
</div>

<div class="row">
  <div class="col-md-6">
    <div class="box ">
      <table class="table">
        <tr>
          <th>Mes</th>
          <th>Noches</th>
          <th>ADR LAB</th>
          <th>ADR FIND</th>
          <th>PVP RVAS</th>
        </tr>
        <tr>
          <td class="td-h1">
            <?php
            echo $month > 0 ? $lstMonths[$month] : 'Todos';
            $auxADR = $ADR_finde;
            ?>
          </td>
          <td class="td-h1">{{$aPKI['nigth']}}</td>
          <td class="td-h1"><?php echo ($auxADR['c_s'] > 0) ? moneda($auxADR['t_s'] / $auxADR['c_s']) : '-'; ?></td>
          <td class="td-h1"><?php echo ($auxADR['c_f'] > 0) ? moneda($auxADR['t_f'] / $auxADR['c_f']) : '-'; ?></td>
          <td class="td-h1">{{moneda($aPKI['pvp'])}}</td>
        </tr>
      </table>
    </div>
    <div class="box row">
      <div class="col-md-4 text-cont">
        <?php 
        $ffTotals = isset($forfaits['totals'][0]['t']) ? $forfaits['totals'][0]['t']: 0; 
        ?>
        <b>Total Ingresos Ordenes Forfaits:</b>
        <h4>{{moneda($ffTotals)}}</h4>
        <b>Promedio por Orden Forfaits:</b>
        <h4>
          <?php  echo ($forfaits['lst'][$month][3]>0) ? moneda($ffTotals/$forfaits['lst'][$month][3]) : '--'; ?>
        </h4>
        <b>Bº ESTIM <small>(15% x vtas de forfait)</small>:</b>
        <h4>
         <h4>{{moneda($ffTotals*0.15)}}</h4>
        </h4>
        
      </div>
      <div class="col-md-8">
        <canvas id="bar_forfatis"></canvas>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="box">
      <div class=" table-responsive">
        <table class="table">
          <th>KPI - Indicador Clave</th>
        </table>
      </div>
      <div >
        <table class="table">
          <tr>
            <th>Total Vnts <?php echo $month > 0 ? $lstMonths[$month] : 'Todos'; ?></th>
            <th>Total Reservas</th>
            <th>ADR LAB</th>
            <th>ADR FIND</th>
            <th>Ocupación</th>
          </tr>
          <tr>
<?php $auxADR = $ADR_finde; ?>
            <td  class="td-h1">{{moneda($aPKI['pvp'])}}</td>
            <td  class="td-h1">{{$aPKI['rva']}}</td>
            <td class="td-h1">
              <?php echo ($auxADR['c_s'] > 0) ? moneda($auxADR['t_s'] / $auxADR['c_s']) : '-'; ?></td>
            <td class="td-h1">
              <?php echo ($auxADR['c_f'] > 0) ? moneda($auxADR['t_f'] / $auxADR['c_f']) : '-'; ?></td>
            <td>
              <?php
              $ocup = $disp * $days;
              if ($aPKI['nigth'] > 0):
                $perc = $aPKI['nigth'] / $ocup;
                ?>
                @include('backend.blocks.arcChar',['perc'=>$perc]);
                <?php
              else: echo '-';
              endif;
              ?>
            </td>
          </tr>
        </table>
        <table class="table">
          <tr>
            <th>Total Noches</th>
            <th>Estancia media</th>
            <th>Vnt Propia</th>
            <th>Vnt Agencia</th>
          </tr>
          <tr>
            <td class="td-h1">{{$aPKI['nigth']}}</td>
            <td class="td-h1"> 
              <?php
              if ($aPKI['rva'] > 0)
                echo round($aPKI['nigth'] / $aPKI['rva']);
              else
                echo '-';
              ?>
            </td>
            <td class="td-h1">
              <?php
              $percent = ($aPKI['pvp'] > 0) ? round(($aPKI['vProp'] / $aPKI['pvp']) * 100) : 0;
              echo $percent . '%';
              ?>
            </td>
            <td class="td-h1">
              <?php
              $percent = ($aPKI['pvp'] > 0) ? 100 - $percent : 0;
              echo $percent . '%';
              ?>
            </td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  new Chart(document.getElementById("bar_forfatis"), {
    type: 'bar',
    data: {
      labels: ['No Gestionada','Cancelada','No Cobrada','Confirmada','Comprometida'],
      datasets: [{
          backgroundColor: [<?php 
            echo '"'.printColor(1).'",';
            echo '"'.printColor(2).'",';
            echo '"'.printColor(3).'",';
            echo '"'.printColor(4).'",';
            echo '"'.printColor(5).'"';
            ?>],
          data: [<?php foreach ($forfaits['lst'][$month] as $k=>$v)
              echo "'" . $v . "',"; ?>]
        }]
    },
    options: {
      title: {display: false},
      legend: {display: false},
//      legend: { position: 'bottom'}
    }
  });
</script>