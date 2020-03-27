<div class="row">
  <div class="col-md-8 col-xs-12">
    <h3>Resumen Ingresos / Mes</h3>
    <div class=" table-responsive">
      <table class="table table-resumen">
        <thead>
          <tr class="resume-head">
            <th class="static">Concepto</th>
            <th class="static-2">Total</th>
            <?php $first = TRUE; ?>
            @foreach($lstMonths as $k => $month)
            <th @if($first) class="first-col" @endif>{{getMonthsSpanish($month['m'])}}</th>
            <?php $first = FALSE; ?>
            @endforeach
          </tr>
        </thead>
        <tbody>
          <tr>
           
            <td class="static">Aptos</td>
            <td class="static-2" > {{moneda( $t_all_rooms,false)}}</td>
            <?php $first = TRUE; ?>
            @foreach($lstMonths as $k => $month)
            <td class="nowrap @if($first) first-col @endif">
              <?php
              if (isset($t_room_month[$month['m']]) && $t_room_month[$month['m']]>1){
                echo moneda($t_room_month[$month['m']],false);
              } else {
                echo '--';
              }
              $first = FALSE;
              ?>
            </td>
            @endforeach
          
          </tr>
          
          
          @foreach($ingrMonths as $k=>$item)
          <tr>
            <td class="static">{{$ingrType[$k]}}</td>
            <td class="static-2" >{{moneda($item[0],false)}}</td>
            <?php $first = TRUE; ?>
            @foreach($lstMonths as $month=>$val)
            <td class="nowrap @if($first) first-col @endif">{{moneda($item[$val['m']],false)}}</td>
            <?php $first = FALSE; ?>
            @endforeach
          </tr>
          @endforeach

        </tbody>
      </table>
    </div>
    </div>
 <div class="col-md-4 col-xs-12">
    <div class="pieChart">
      <canvas id="chart_2"></canvas>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    new Chart(document.getElementById("chart_2"), {
      type: 'pie',
      data: {
        labels: [
        <?php
        foreach($ingrMonths as $k=>$item) echo "'$ingrType[$k]',";
        ?>
        ],
        datasets: [{
            backgroundColor: ['#536180','#598EFF','#859BCC','#2F4980','#A6C2FF'],
            data: [
               <?php
        foreach($ingrMonths as $k=>$item) echo $item[0].',';
        ?>
            ]
          }]
      },
      options: {
        title: {display: false},
        legend: {display: false},
      }
    });
  });
</script>