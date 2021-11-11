@include('backend.planning.dataDis.calendar.tabs')
<div class="row">
  <div class="col-md-7 col-xs-12" style="padding-right: 3em;">
    <div class="table-responsive contentCalendar">
      <table class="fc-border-separate calendar-table " style="width: 100%">
        @include('backend.planning.dataDis.calendar.months')
        <tbody>
          <?php
          $luxAux = 1;
          $typeAux = 2;
          $currentAux = null;
          $posicion = 0;
          $arrayLine = [];
          $yesterday = date('Y-m-d', strtotime('-1 days'));
          ?>
          <?php foreach ($oRooms as $room): ?>
            <?php
            $line = in_array($posicion, $arrayLine) ? "line-divide " : '';
            $posicion++;
            $luxAux = $room->luxury;
            $typeAux = $room->sizeApto;
            $currentAux = $room->channel_group;
            $lstEvents = $arrayReservas[$room->id];
            $lightbulb = '';
            if (isset($aRoomsElectricity[$room->id]) && isset($aRoomsElectricity[$room->id][$yesterday])) {
              if ($aRoomsElectricity[$room->id][$yesterday]>0){
                $lightbulb = '<i class="fas fa-lightbulb"></i>';
                if ($lstEvents && isset($lstEvents[$yesterday]))
                  if (count($lstEvents[$yesterday])>0) $lightbulb = '';
              }
            }
            ?>
            <tr class="<?php echo $line ?>">
              <td class="text-center fixed-td">
                  <?php echo $lightbulb; ?>
                <b data-placement="right" title="" data-toggle="tooltip" data-original-title="<?php echo $room->name ?>">
                  <?php echo substr($room->nameRoom, 0, 5) ?>
                </b>
              </td>
              <?php
              
              foreach ($lstEvents as $date => $events):
                $cEv = count($events);
                $dayKey = '';
                $consumption = 0;
                $consumClass = $consumVal = '';
                if (isset($aRoomsElectricity[$room->id]) && isset($aRoomsElectricity[$room->id][$date])) {
                  $consumption = $aRoomsElectricity[$room->id][$date];
                  $consumClass = ($consumption > 0) ? 'pwOn' : 'pwOff';
                  $consumVal = ($consumption > 0) ? '<b>' . $consumption . ' kWh</b>' : '';
                  $dayKey = ' data-rid="'.$room->id.'" data-day="'.$date.'" ';
                }
                ?>

                <?php if ($cEv > 1): ?>
                  <td class="ev-doble {{$consumClass}}" <?php echo $dayKey; ?>>
                    @include('backend.planning.dataDis.calendar._calendarEventDouble', ['calendars' => $events,'inicio'=>$date,'consumVal'=>$consumVal,'dayKey'=>$dayKey])
                  </td>
                <?php endif ?>
                <?php if ($cEv == 1): ?>
                  @include('backend.planning.dataDis.calendar._calendarEvent', ['calendars' => $events,'inicio'=>$date,'consumClass'=>$consumClass,'consumVal'=>$consumVal,'dayKey'=>$dayKey])
                <?php endif ?>
                <?php if ($cEv == 0): ?>
                  <td class="no-event td-calendar {{$consumClass}}" <?php echo $dayKey; ?>>
                    @if($consumVal != "")
                    <a href="#" class="tip ddd <?php echo $consumClass; ?>"  style="display:block;">
                      <div class="total">&nbsp;</div>
                      <span><?php echo $consumVal ?></span>
                    </a>
                    @endif
                  </td>
                <?php endif ?>


                <?php
              endforeach;
              ?>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  <div class="col-md-5 col-xs-12 boxDaily">
    <div class="datadis_day">
      <div class="alert alert-info mt-1em">Seleccione un día para continuar</div>
    </div>
  </div>
</div>
<br/>
<br/>
<br/>
<div class="monthlyCanvas">
  <div class="">
<canvas id="barBalance" style="width: 100%; height: 350px;"></canvas>
</div>
</div>

<script type="text/javascript">
  $(document).ready(function () {
      /* GRAFICA Room/Consumo */
      var data = {
          labels: [
            <?php
            foreach ($arrayMonths as $key => $daysMonth):
              for ($i = 1; $i <= $daysMonth; $i++):
                echo '"' . $i . ' ' . $aMonths[$key] . '",';
              endfor;
            endforeach
            ?>
          ],
          datasets: [
              <?php foreach ($oRooms as $k => $room): ?>
                {
                    label: "{{$room->nameRoom}}",
                    borderColor: "{{printColor($k)}}",
                    borderWidth: 1,
                    fill: false,
                    data: [
                    <?php
                    $lstEvents = $arrayReservas[$room->id];
                    foreach ($lstEvents as $date => $events):
                      $consumption = 0;
                      if (isset($aRoomsElectricity[$room->id]) && isset($aRoomsElectricity[$room->id][$date]))
                        $consumption = $aRoomsElectricity[$room->id][$date];

                      echo $consumption . ',';
                    endforeach;
                    ?>
                    ],
                },
              <?php endforeach; ?>
          ]
      };
      var barBalance = new Chart('barBalance', {
          type: 'line',
          data: data,
      });
  });
</script>
