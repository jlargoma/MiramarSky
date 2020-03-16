<div class="row">
  <div class="col-md-8 col-xs-12">
    <h3>Resumen Ingresos / Mes</h3>
    <div class=" table-responsive">
      <table class="table table-resumen">
        <thead>
          <tr class="resume-head">
            <th class="static">Concepto</th>
            <th class="first-col nowrap">Total</th>
            @foreach($lstMonths as $k => $month)
            <th>{{getMonthsSpanish($month['m'])}}</th>
            @endforeach
          </tr>
        </thead>
        <tbody>
          <tr>
           
            <td class="static">Aptos</td>
            <td class="first-col nowrap" > {{moneda( $t_all_rooms,false)}}</td>
            @foreach($lstMonths as $k => $month)
            <td class="nowrap">
              <?php
              if (isset($t_room_month[$month['m']]) && $t_room_month[$month['m']]>1){
                echo moneda($t_room_month[$month['m']],false);
              } else {
                echo '--';
              }
              ?>
            </td>
            @endforeach
          
          </tr>
          
          
          @foreach($ingrMonths as $k=>$item)
          <tr>
            <td class="static">{{$ingrType[$k]}}</td>
            <td class="first-col" >{{moneda($item[0],false)}}</td>
            @foreach($lstMonths as $month=>$val)
            <td>{{moneda($item[$val['m']],false)}}</td>
            <?php $auxClass = ''; ?>
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