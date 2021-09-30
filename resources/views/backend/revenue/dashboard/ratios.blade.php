<?php
//--BEGIN: Resumen -------//
$total = $tPaxs = $tCost = $vProp = $tnigth = 0;

$disp = \App\Rooms::avail();
//$night = rvas
if ($books) {
  foreach ($books as $b) {
    $total += $b->pvp;
    $tnigth++;
  }
}
//---------------------------------------------------------------------//
//--BEGIN: RATIOS -------//
/**
 * Auxiliar functions to view
 */
function prADR($v){
  return $v['n']>0 ? moneda($v['p']/$v['n']) : moneda($v['p']);
}
function prOcup($disp,$days,$v){
  $ocup  = $disp*$days;
  $night =  $v['n'];
  return ($night > 0) ? round(($night / $ocup)*100) : 0;
}
//--END: RATIOS -------//
$lstMonthsDays = [];
foreach ($lstMonths as $k2=>$v2){
   $aux = intVal(explode('.', $k2)[1]);
   $lstMonthsDays[$k2] =$mDays[$aux];
}
?>
<div class="row">
  <div class="col-md-8">
    <div class="table-responsive">
    <table class="table table-summary" style="max-width:940px">
      <td>Total: {{moneda($total)}}</td>
    </table>
</div>
<div class="box">
  <div class="ratios table-responsive">
    <table class="table">
      <tr class="thead">
        <th class="static" style="background-color: #fafafa;height: 36px;"></th>
        <td class="first-col"></td>
        <th>Total</th>
        @foreach ($lstMonths as $k2=>$v2)
        <th>{{$v2}}</th>
        @endforeach
      </tr>
      <tr>
        <th class="static">Ventas</th>
        <td class="first-col"></td>
        <td>{{moneda($aRatios[0]['p'])}}</td>
        @foreach ($lstMonthsDays as $k2=>$v2)
        <td>{{moneda($aRatios[$k2]['p'])}}</td>
        @endforeach
      </tr>
      <tr>
        <th class="static">Noches</th>
        <td class="first-col"></td>
        <td>{{$aRatios[0]['n']}}</td>
        @foreach ($lstMonths as $k2=>$v2)
        <td>{{$aRatios[$k2]['n']}}</td>
        @endforeach
      </tr>
      <tr>
        <th class="static">Ocupación</th>
        <td class="first-col"></td>
        <td>{{prOcup($disp,$mDays[0],$aRatios[0])}}%</td>
        @foreach ($lstMonthsDays as $k2=>$v2)
        <td>{{prOcup($disp,$v2,$aRatios[$k2])}}%</td>
        @endforeach
      </tr>
      <tr>
        <th class="static">ADR</th>
        <td class="first-col"></td>
        <td>{{prADR($aRatios[0])}}</td>
        @foreach ($lstMonths as $k2=>$v2)
        <td>{{prADR($aRatios[$k2])}}</td>
        @endforeach
      </tr>
      <tr>
        <th class="static">REV PAV</th>
        <td class="first-col"></td>
        <td>--</td>
        @foreach ($lstMonths as $k2=>$v2)
        <td>--</td>
        @endforeach
      </tr>
      <tr>
        <th class="static">GOpPar</th>
        <td class="first-col"></td>
        <td>-</td>
        @foreach ($lstMonths as $k2=>$v2)
        <td>-</td>
        @endforeach
      </tr>
    </table>
  </div>
</div>
</div>


  <div class="col-md-4">
    <div>
      <h3>Indicaciones de Ocupación</h3>
      @include('backend.revenue.dashboard._tableSummaryBoxes')
    </div>

   
    <div class="dispPKI">
      <h5></h5>
      <?php
      $ocup = $disp * $yDays;
      $perc = ($tnigth > 0) ? $tnigth / $ocup : 0;
      ?>
      @include('backend.blocks.arcChar',['perc'=>$perc]);
      <div style="margin-top: -16px;">Ocupación</div>
    </div>

  </div>
</div>