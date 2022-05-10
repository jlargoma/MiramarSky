<div class="row ">
  <?php 
  $oldResult = 0;
  $totalPVP = 0; 
  $toGrafJs = [];
  $arrayColors = ['bg-info', 'bg-complete', 'bg-primary',];
  $year = $year->year-2;
  
  $totalPVP = \App\BookDay::getPvpByYear($year-1);
  $totalExpense = \App\Expenses::getTotalByYear($year-1); 
  $oldResult = $totalPVP-$totalExpense;
    
  for ($i = 0; $i < 3; $i++):
    $yearAux = $year+$i;
    $totalPVP = \App\BookDay::getPvpByYear($yearAux);
    $totalExpense = \App\Expenses::getTotalByYear($yearAux); 
    $otherIngr = \App\Incomes::getIncomesYear($yearAux);
    $totalFF = App\Models\Forfaits\Forfaits::getTotalByYear($yearAux);
    $totalIVA = App\ProcessedData::getTotalIVA($yearAux);
    $result = $totalPVP+$otherIngr+$totalFF-$totalExpense-$totalIVA;
    $toGrafJs[$yearAux] = $totalPVP+$otherIngr+$totalFF;
    ?>
    <div class="col-md-4 col-xs-6 m-b-10">

      <div class="widget-12 no-border <?php echo $arrayColors[$i] ?> widget-loader-bar">
        <div class="full-height d-flex flex-column">
          <div style="width: 94%;margin: 2px auto;">
            <h4 class="no-margin p-b-5 text-white ">
              Temp  <b>{{$yearAux}}</b>
            </h4>
            <div class="row">
              <div class="col-xs-10 text-white font-s24">
                  <div><?php echo moneda($totalPVP); ?> <small>RVAS</small></div>
                  <div>+<?php echo moneda($otherIngr); ?> <small>EXTR</small></div>
                  <div>+<?php echo moneda($totalFF); ?> <small>FF</small></div>
                  <div>- <?php echo moneda($totalExpense); ?> <small>GTOS</small></div>
                  <div>- <?php echo moneda($totalIVA); ?> <small>IVA</small></div>
                  <div style="border-bottom: 1px solid;"> </div>
                  <div class="mt-1em"><?php echo moneda($result); ?> <small>Bº</small></div>
              </div>
              <div class="col-xs-2">
                <span style="font-size: 14px;">
                  <?php if ($result > $oldResult): ?>
                    <i class="fa fa-arrow-up text-success" style="font-size: 20px;"></i>
                  <?php else: ?>
                    <i class="fa fa-arrow-down text-danger" style="font-size: 20px;"></i>
                  <?php endif ?>
              </span>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>

<?php 
$oldResult = $result;
endfor; 
?>

</div>
<script type="text/javascript">
  /*----------------------------------------------------------------------*/
  var dataCharContabilidad = {
      labels: [
        <?php
          foreach($toGrafJs as $k=>$v){
            $auxY = $k-2000;
            echo "'".$auxY.'-'.($auxY+1)."',";
          }
        ?>
      ],
      datasets: [
          {
              label: "Total Ingr. Temp.",
              backgroundColor: 'rgba(54, 162, 235, 0.2)',
              borderColor: 'rgba(54, 162, 235, 1)',
              borderWidth: 1,
              data: [
                <?php
                foreach($toGrafJs as $k=>$v) echo "'" . ceil($v) . "',";
                ?>
              ],
          }
      ]
  };
  
  </script>