<?php
//--BEGIN: Resumen -------//
$disp = \App\Rooms::avail();
$tDays = $mDays[0];

//---------------------------------------------------------------------//
//--BEGIN: RATIOS -------//
/**
 * Auxiliar functions to view
 */
function prADR_3($v, $k1, $k2) {
  return $v[$k1] > 0 ? moneda($v[$k2] / $v[$k1]) : moneda($v[$k2]);
}

function prOcup_3($disp, $days, $night) {
  $ocup = $disp * $days;
  return ($night > 0) ? round(($night / $ocup) * 100) : 0;
}

//--END: RATIOS -------//

$AUXsummay = $comparativaAnual[$year];

$lstMonthsDays = [0 => $tDays];
foreach ($lstMonths as $k2 => $v2) {
  $aux = intVal(explode('.', $k2)[1]);
  $lstMonthsDays[$k2] = $mDays[$aux];
}

$totalesComp = ['vtas' => 0, 'nigths' => 0, 'pvp' => 0];
//dd($comparativaAnual);
foreach ($comparativaAnual as $k => $v) {
  $totalesComp['vtas'] += $v['pvp'];
  $totalesComp['nigths'] += $v['nigths'];
  $totalesComp['pvp'] += $v['pvp'];
}
?>

<div class="row" id="comparativaAnual">
  <div class="col-md-8">
    <div class="table-responsive">
      <table class="table table-summary" style="max-width:940px">
        <td>Total: {{moneda($AUXsummay['pvp'])}}</td>
      </table>
    </div>
    <div class="box">
      <div class="ratio_comps_comp table-responsive">
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
            @foreach ($lstMonths as $k2=>$v2)
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
            <td>{{prOcup_3($disp,$lstMonthsDays[0],$aRatios[0]['n'])}}%</td>
            @foreach ($lstMonths as $k2=>$v2)
            <td>{{prOcup_3($disp,$lstMonthsDays[$k2],$aRatios[$k2]['n'])}}%</td>
            @endforeach
          </tr>
          <tr>
            <th class="static">ADR</th>
            <td class="first-col"></td>
            <td>{{prADR_3($aRatios[0],'n','p')}}</td>
            @foreach ($lstMonths as $k2=>$v2)
            <td>{{prADR_3($aRatios[$k2],'n','p')}}</td>
            @endforeach
          </tr>
          <tr>
            <th class="static">ADR LAB</th>
            <td class="first-col"></td>
            <td>{{prADR_3($aRatios[0],'c_s','t_s')}}</td>
            @foreach ($lstMonths as $k2=>$v2)
            <td>{{prADR_3($aRatios[$k2],'c_s','t_s')}}</td>
            @endforeach
          </tr>
          <tr>
            <th class="static">ADR FIN</th>
            <td class="first-col"></td>
            <td>{{prADR_3($aRatios[0],'c_f','t_f')}}</td>
            @foreach ($lstMonths as $k2=>$v2)
            <td>{{prADR_3($aRatios[$k2],'c_f','t_f')}}</td>
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
    <canvas id="barRatioComp" width="500" height="150"></canvas>

  </div>


  <div class="col-md-4">
    <div>
      <h3>Indicaciones de Ocupación</h3>
      @include('backend.revenue.dashboard._tableSummaryBoxes')
    </div>

    <div class="dispPKI">
      <h5>s</h5>
      <?php
      $ocup = $disp * $yDays;
      $perc = ($aRatios[0]['n'] > 0) ? $aRatios[0]['n'] / $ocup : 0;
      ?>
      @include('backend.blocks.arcChar',['perc'=>$perc]);
      <div style="margin-top: -16px;">Ocupación</div>
    </div>

  </div>
</div>

<div class="row" id="comparativaAnuales" style="display:none;" >
  <div class="col-md-8">
    <div class="table-responsive">
      <table class="table table-summary" style="max-width:940px">
        <tr>
        @foreach($comparativaAnual as $k=>$v)
          <th>{{$k}}</th>
        @endforeach
        </tr>
        <tr>
        @foreach($comparativaAnual as $k=>$v)
          <td>{{moneda($v['pvp'])}}</td>
        @endforeach
        </tr>
      </table>
    </div>


    <div class="box">
      <div class="ratio_comps_comp table-responsive">
        <table class="table">
          <tr class="thead">
            <th class="static" style="background-color: #fafafa;height: 36px;"></th>
            <td class="first-col"></td>
            <th>Total</th>
            @foreach ($lstMonths as $k2=>$v2)
            <th>{{$v2}}</th>
            @endforeach
          </tr>
          @foreach($comparativaAnual as $k2=>$v2)
          <tr>
            <th class="static">Ventas {{$k2}}</th>
            <td class="first-col"></td>
            @foreach($v2['months'] as $k3=>$v3)
            <td>{{moneda($v3)}}</td>
            @endforeach
          </tr>
          @endforeach
        </table>
      </div>
    </div>

  </div>
  <div class="col-md-4">
    <h3>Indicaciones de Ocupación</h3>

    <div class="table-responsive">
      <table class="table">
        <tr class="thead">
          <td></td>
          <th>Total</th>
          @foreach($comparativaAnual as $k=>$v)
          <th>{{$k}}</th>
          @endforeach
        </tr>
        <tr>
          <th>Ventas</th>
          <td>{{moneda($totalesComp['vtas'])}}</td>
          @foreach($comparativaAnual as $k=>$v)
          <td>{{moneda($v['pvp'])}}</td>
          @endforeach
        </tr>
        <tr>
          <th>Noches</th>
          <td>{{$totalesComp['nigths']}}</td>
          @foreach($comparativaAnual as $k=>$v)
          <td>{{$v['nigths']}}</td>
          @endforeach
        </tr>
        <tr>
          <th>Ocupación</th>
          <td>{{prOcup_3($disp,$tDays,$totalesComp['nigths'])}}%</td>
          @foreach($comparativaAnual as $k=>$v)
          <td>{{prOcup_3($disp,$tDays,$v['nigths'])}}%</td>
          @endforeach
        </tr>
        <tr>
          <th>ADR</th>
          <td>{{prADR_3($totalesComp,'nigths','vtas')}}</td>
          @foreach($comparativaAnual as $k=>$v)
          <td>{{prADR_3($v,'nigths','pvp')}}</td>
          @endforeach
        </tr>
      </table>
    </div>
    <canvas id="barRatioCompYear" width="400" height="250"></canvas>
  </div>
</div>


<style>
  #comparativaAnuales .table tbody tr td{
    font-size: 12px;
    padding: 0 !important;
  }
  
  #comparativaAnual .dispPKI {
    width: 180px;
  }
</style>
<script type="text/javascript">

  $(document).ready(function () {

    
 /* GRAFICA */
 var data = {
      labels: [@foreach($lstMonths as $v_m) "{{$v_m}}", @endforeach],
      datasets: [
        {
          label: "Ingresos mensuales",
          borderColor:"{{printColor(1)}}",
          fill: false,
          data: [
            @foreach ($lstMonths as $k2=>$v2) {{round($aRatios[$k2]['p'])}}, @endforeach
            ],
        },
      ]
    };
    var barBalance = new Chart('barRatioComp', {
    type: 'line',
            data: data,
    });
    //----------------------------------------------------------------//
 /* GRAFICA */
    var data = {
      labels: [@foreach($lstMonths as $v_m) "{{$v_m}}", @endforeach],
      datasets: [
        @foreach($comparativaAnual as $k_r=>$v_r)
        {
          label: "{{$k_r}}",
          borderColor:"{{printColor($k_r)}}",
          fill: false,
          data: [
            @foreach ($v_r['months'] as $k2=>$v2) 
              @if($k2>0)
              {{round($v2)}}, 
              @endif
            @endforeach
            ],
        },
        @endforeach
      ]
    };
    var barBalance = new Chart('barRatioCompYear', {
    type: 'line',
            data: data,
    });


  });

</script>
