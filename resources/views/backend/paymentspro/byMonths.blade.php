<?php 
use \Carbon\Carbon;
use \App\Classes\Mobile;
setlocale(LC_TIME, "ES");
setlocale(LC_TIME, "es_ES");
$mobile = new Mobile();
$isMobile = $mobile->isMobile();
?>
<style>
  
  @media only screen and (min-width: 991px){
    .table-resumen td.static, .table-resumen th.static {
      position: relative;
      overflow: hidden;
      }
    .table-resumen td.first-col, .table-resumen th.first-col {
      padding-left: 0 !important;
    }
  }
  
  .table-resumen td, .table-resumen th {
    height: 3em;
  }
  .table-resumen th.static {
    height: 6em;
    width: 8em;
    text-align: center;
    margin-top: 1em;
  }
</style>
<div class="row table-responsive" style="border: 0px!important">
  <table class="table table-resumen">
    <thead>
      <tr class="resume-head">
        <th class="static" >Apto</th>
        <th class="first-col">C. PROP.
        <?php echo number_format($t_all_rooms, 0, ',', '.'); ?>€
        </th>
        <th>%</th>
        @foreach($lstMonths as $k => $month)
        <th >
          {{getMonthsSpanish($month['m'])}} {{$month['y']}}<br/>
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
      @foreach($lstRooms as $roomID => $name)
      <tr>
        <td class="static">{!!$name!!}</td>
        <td class="first-col">
            <?php
            $totalRoom = 0;
            if (isset($t_rooms[$roomID]) && $t_rooms[$roomID] > 1) {
              $totalRoom = $t_rooms[$roomID];
              echo number_format($totalRoom, 0, ',', '.') . '€';
            } else {
              echo '--';
            }
            ?>
        </th>
        <td>
          <?php
          $percent = ($totalRoom / $t_all_rooms) * 100;
          echo number_format($percent, 0, ',', '.');
          ?>%
        </td>
        @foreach($lstMonths as $k => $month)
        <th >
          <?php
          if (isset($roomLst[$roomID]) && isset($roomLst[$roomID][$k]) && $roomLst[$roomID][$k] > 1) {
            echo number_format($roomLst[$roomID][$k], 0, ',', '.') . '€';
          } else {
            echo '--';
          }
          ?>
        </th>
        @endforeach
      </tr>
      @endforeach
    </tbody>
    </tr>
    </thead>
  </table>
</div>
