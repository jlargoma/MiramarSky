<?php
$t_forfaits = $t_equipos = $t_clases = $t_otros = 0;
?>
<div class="row">
  <div class="col-md-8 col-xs-12">
    <h3>Resumen Forfaits</h3>
    <div class=" table-responsive">
      <table class="table table-resumen">
        <thead>
          <tr class="resume-head">
            <th class="static">Concepto</th>
            <th class="first-col">Total</th>
            @foreach($months_ff as $item)
            <th>{{$item['name']}} {{$item['year']}}</th>
            <?php
            $t_forfaits += $item['data']['forfaits'];
            $t_equipos += $item['data']['equipos'];
            $t_clases += $item['data']['clases'];
            $t_otros += $item['data']['otros'];
            ?>
            @endforeach
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="static">Forfaits</td>
            <td class="first-col"><?php echo number_format($t_forfaits, 0, ',', '.') ?> €</td>
            @foreach($months_ff as $item)
            <td><?php echo number_format($item['data']['forfaits'], 0, ',', '.'); ?>€</td>
            @endforeach
          </tr>
          <tr>
            <td class="static">Materiales</td>
            <td class="first-col"><?php echo number_format($t_equipos, 0, ',', '.') ?> €</td>
            @foreach($months_ff as $item)
            <td><?php echo number_format($item['data']['equipos'], 0, ',', '.'); ?>€</td>
            @endforeach
          </tr>
          <tr>
            <td class="static">Clases</td>
            <td class="first-col"><?php echo number_format($t_clases, 0, ',', '.') ?> €</td>
            @foreach($months_ff as $item)
            <td><?php echo number_format($item['data']['clases'], 0, ',', '.'); ?>€</td>
            @endforeach
          </tr>
          <tr>
            <td class="static">Otros</td>
            <td class="first-col"><?php echo number_format($t_otros, 0, ',', '.') ?> €</td>
            @foreach($months_ff as $item)
            <td><?php echo number_format($item['data']['otros'], 0, ',', '.'); ?>€</td>
            @endforeach
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <div class="col-md-4 col-xs-12">
    <div class="pieChart">
      <canvas id="chart_3"></canvas>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    new Chart(document.getElementById("chart_3"), {
      type: 'pie',
      data: {
        labels: ['Forfaits','Materiales','Clases','Otro' ],
        datasets: [{
            backgroundColor: ['#536180','#598EFF','#859BCC','#2F4980','#A6C2FF'],
            data: ['{{round($t_forfaits)}}','{{round($t_equipos)}}','{{round($t_clases)}}','{{round($t_otros)}}']
          }]
      },
      options: {
        title: {display: false},
        legend: {display: false},
      }
    });
  });
</script>