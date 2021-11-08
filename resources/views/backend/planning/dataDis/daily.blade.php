<h4>Consumo en KWats de {{$nRoom}}: {{$day}}</h4>
<canvas id="barDailyDataDis" style="width: 100%; height: 350px;"></canvas>


<script type="text/javascript">
  $(document).ready(function () {
      /* GRAFICA Room/Consumo */
      var data = {
          labels: [
            <?php
              foreach ($hours as $h):
                echo '"' . $h . '",';
              endforeach;
            ?>
          ],
          datasets: [
                {
                    borderWidth: 1,
                    data: [
                    <?php
                    foreach ($data as $item):
                      echo $item . ',';
                    endforeach;
                    ?>
                    ],
                },
          ]
      };
      var barBalance = new Chart('barDailyDataDis', {
          type: 'line',
          data: data,
          options: {
              title: {display: false},
              legend: {display: false},
              
              scales: {
                yAxes: [{
                  stacked: true,
                  ticks: {
                     min: 0,
                     stepSize: 1,
                 }
              }]
            }
          }
      });
  });
</script>

