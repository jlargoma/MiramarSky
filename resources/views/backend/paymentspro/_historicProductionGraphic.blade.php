<?php   use \Carbon\Carbon;
setlocale(LC_TIME, "ES");
setlocale(LC_TIME, "es_ES");
?>

<!DOCTYPE html>
<html>
    <head>
        <?php 
            use App\Classes\Mobile; 
            $mobile = new Mobile();
        ?>
    </head>
    <body class="fixed-header   windows desktop pace-done sidebar-visible menu-pin" style="padding-top:0px!important">

      @if(count($info_years))
       <div class="col-md-12 col-xs-12 table-responsive" style="padding-right: 0;">
        <table class="table table-striped" id="tableOrderable">
          <thead >
              <th class ="text-center bg-complete text-white">Apto</th>
              @foreach($info_years as $item)
              <th class ="text-center bg-complete text-white">Temp. {{$item['y']}} - {{$item['y']+1}}</th>
              @endforeach
          </thead>
          <tbody >
            <tr>
              <td class ="text-center">{{$oRoom->name}} ({{$oRoom->nameRoom}})</td>
              @foreach($info_years as $item)
              <th class ="text-center"><?php echo number_format($item['val'] ,0,'','.'); ?>€</th>
              @endforeach
            </tr>
          </tbody>
        </table>
       </div>
      @endif
      
        <div class="col-md-12 col-xs-12">
            <div>
                <?php $dataChartMonths = \App\Rooms::getCostPropByMonth($year->year,$room_id) ?>
            </div>
        </div>
        <div class="col-md-12 col-xs-12">
            <div>
                <?php $dataChartYear = \App\Rooms::getCostPropByMonth(($year->year - 1) ,$room_id) ?>
                <?php $dataChartPrevYear = \App\Rooms::getCostPropByMonth(($year->year - 2),$room_id) ?>

                <canvas id="barChartTemp" style="max-height: 350px;"></canvas>
            </div>
        </div>

        <script type="text/javascript">

            new Chart(document.getElementById("barChartTemp"), {
                type: 'line',
                data: {
                  labels: [
                    <?php foreach ($dataChartMonths as $key => $value): ?>
                        <?php echo "'" . $key . "'," ?>
                    <?php endforeach ?>
                  ],
                  datasets: [{
                    data: [
                     <?php foreach ($dataChartMonths as $key => $value): ?>
                        <?php echo "'" . round($value) . "'," ?>
                      <?php endforeach ?>
                    ],
                    label: '<?php echo $year->year ?>-<?php echo $year->year + 1 ?>',
                    borderColor: "rgba(54, 162, 235, 1)",
                    fill: false
                  },
                    {
                      data: [
                       <?php foreach ($dataChartYear as $key => $value): ?>
                        <?php echo "'" . round($value) . "'," ?>
                      <?php endforeach ?>
                      ],
                       <?php $aux = ($year->year) ?>
                        label: '<?php echo $aux-1 ?>-<?php echo $aux  ?>',
                      borderColor: "rgba(104, 255, 0, 1)",
                      fill: false
                    },
                    {
                      data: [
                       <?php foreach ($dataChartPrevYear as $key => $value): ?>
                        <?php echo "'" . round($value) . "'," ?>
                      <?php endforeach ?>
                      ],
                       <?php $aux =  ($year->year - 1) ?>
                        label: '<?php echo $aux-1 ?>-<?php echo $aux ?>',
                      borderColor: "rgba(232, 142, 132, 1)",
                      fill: false
                    }
                  ]
                },
                options: {
                  title: {
                    display: false,
                    text: ''
                  }
                }
            });

        </script>

   </body>
</html>