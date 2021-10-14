<div class="row">
  <div class="col-md-9">
    <div class=" table-responsive" >
      <table class="table tIngrMes">
        <thead>
          <tr>
            <th class="static">Apto</th>
            <th class="first-col"></th>
            <th >total<br/>{{moneda($t_all_rooms)}}</th>
            <th >%</th>
            @foreach($lstMonths as $k => $month)
            <th class="text-center bg-complete text-white">
              {{getMonthsSpanish($month['m'])}}<br/>
              <?php
              if (isset($t_room_month[$k]) && $t_room_month[$k] > 1) {
                echo number_format($t_room_month[$k], 0, ',', '.') . '€';
              } else {
                echo '--';
              }
              ?>
            </th>
            @endforeach
        <tbody>
        

          <!-- BEGIN: channels-->                               
          @foreach($chRooms as $ch => $data2)
          <tr class="text-center contab-ch ">
            <td class="text-left static">  
              <i class="fas fa-plus-circle toggle-contab" data-id="{{$ch}}"></i>{{$channels[$ch]}}
            </td>
            <th class="text-center first-col"></th>
            <th class="text-center ">  
              {{moneda($data2['months'][0])}}
            </th>
            <th class="text-center">
              <?php
              $percent = 0;
              if ($data2['months'] > 1 && $t_all_rooms>0)
                $percent = ($data2['months'][0] * 100 / $t_all_rooms);
              ?>
              {{round($percent)}}%
            </th>
            @foreach($lstMonths as $k => $month)
            <th class="text-center">
              <?php
              $k_month = $month['m'];
              if (isset($data2['months'][$k]) && $data2['months'][$k] > 1) {
                echo moneda($data2['months'][$k]);
              } else {
                echo '--';
              }
              ?>
            </th>
            @endforeach
          </tr>
          <!-- BEGIN: ROOMS-->   
          @foreach($data2['rooms'] as $roomID => $name)
          <tr class="text-center contab-room contab-room-{{$ch}}  tr-close">
            <td class="text-left static">{!!$name!!}</td>
            <th class="text-center first-col"></th>
            <th class="text-center ">  
              <?php
              $totalRoom = 0;
              if (isset($t_rooms[$roomID]) && $t_rooms[$roomID] > 1) {
                $totalRoom = $t_rooms[$roomID];
                echo moneda($totalRoom);
              } else {
                echo '--';
              }
              ?>
            </th>
            <td class="text-center">
              <?php
              $percent = 0;
              if ($t_all_rooms>0) $percent = ($totalRoom / $t_all_rooms) * 100;
              echo round($percent) . '%';
              ?>
            </td>
            @foreach($lstMonths as $k => $month)
            <th class="text-center">
              <?php
              $k_month = $month['m'];
              if (isset($sales_rooms[$roomID]) && isset($sales_rooms[$roomID][$k]) && $sales_rooms[$roomID][$k] > 1) {
                echo moneda($sales_rooms[$roomID][$k]);
              } else {
                echo '--';
              }
              ?>
            </th>
            @endforeach
          </tr>
          @endforeach
          <!-- END: ROOMS-->                      
          @endforeach
          <!-- END: channels-->                      

        </tbody>
      </table>
    </div>
    <small><b>Nota:</b> Los ingresos por edificio ya incluyen los Extras asociados</small>
    
    
    <?php 
    $trimestre = [[],[],[],[]];
    $trimestreText = ['1er','2do','3er','4to'];
    $count = 0;
    foreach($lstMonths as $k => $month){
      $aux = ($count/3);
      if (!isset($trimestre[$aux])) $trimestre[$aux] = [];
      $trimestre[$aux][] = $k;
      $count++;
    }
    ?>
    <div class=" table-responsive" >
      <table class="table tableTrimestres">
          <tr>
            <th>VENTAS TRIMESTRES</th>
            <th>TOTAL <br/>{{moneda($t_all_rooms)}}</th>
            <?php
            foreach ($trimestre as $t=>$meses):
              $tAux = 0;
              foreach ($meses as $m):
                if (isset($t_room_month[$m]) && $t_room_month[$m] > 1) {
                  $tAux += $t_room_month[$m];
                }
              endforeach
              ?>
                <th>{{$trimestreText[$t]}} TRIM. <br/>{{moneda($tAux)}}</th>
              <?php
            endforeach
              ?>
          </tr>
      </table>
    </div>
  </div>
  <div class="col-md-3">
    <h3>Ingresos Anual</h3>
    <canvas id="ingrChar" style="width: 100%; height: 250px;"></canvas>
    <h3>Ingresos Por Tipo</h3>
    <canvas id="ingrCharSite" style="width: 100%; height: 250px;"></canvas>
  </div>
</div>
<?php $count=0;
$ingrMonths = $ingrSite = array();
?>
<script type="text/javascript">
  /* GRAFICA INGRESOS */
  var data = {
  labels: [@foreach($lstMonths as $month) "{{getMonthsSpanish($month['m'])}}", @endforeach],
      datasets: [
          {
            <?php $count++; ?>
            borderColor: '{{printColor($count)}}',
            label: "aa",
            borderWidth: 1,
            data: [
              <?php
              foreach($lstMonths as $k => $v){
                if (isset($t_room_month[$k]) && $t_room_month[$k] > 1) {
                  echo round($t_room_month[$k]).',';
                } else {
                  echo '0,';
                }
              }
              ?>
            ],
          },
      ]
  };
  
            
            
            
  var ingrChar = new Chart('ingrChar', {
    type: 'line',
    data: data,
    options: {
    legend: {
        display: false
    },
    tooltips: {
        callbacks: {
           label: function(tooltipItem) {
                  return tooltipItem.yLabel;
           }
        }
    }
}
  });
  <?php $count=0;?>
  /* GRAFICA INGRESOS */
  var data = {
  labels: [@foreach($lstMonths as $month) "{{getMonthsSpanish($month['m'])}}", @endforeach],
      datasets: [
        
        @foreach($chRooms as $ch => $data2)
          {
            
            <?php $count++; ?>
            borderColor: '{{printColor($count)}}',
            label: "{{$channels[$ch]}}",
            borderWidth: 1,
            data: [
            <?php
            foreach($lstMonths as $k => $month){
              $k_month = $month['m'];
              if (isset($data2['months'][$k]) && $data2['months'][$k] > 1) {
                echo round($data2['months'][$k]).',';
              } else {
                echo '0,';
              }
            }
              ?>
                        
            ],
          },
        @endforeach
      ]
  };
  
  
  
  
  
  
  
  var ingrChar = new Chart('ingrCharSite', {
    type: 'line',
    data: data,
  });
</script>
<style>
  .tIngrMes thead th,
  .tableTrimestres th{
    color: #FFF !important;
    background-color: #48b0f7;
    text-align: center;
  }
  .table.tableTrimestres tr th{
    font-size: 22px;
  }
  </style>