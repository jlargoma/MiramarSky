<?php

use \Carbon\Carbon;

setlocale(LC_TIME, "ES");
setlocale(LC_TIME, "es_ES");
$uRole = Auth::user()->role;
$appName = env('APP_APPLICATION');
?>

@if($totalSales !== null)
<div class="clearfix totalCalendar" >
  Ventas de {{getMonthsSpanish($currentM,false)}} : {{moneda($totalSales)}}
  <a href="/admin/revenue/DASHBOARD" title="revenue" class="btn btn-primary">REVENUE</a>
</div>
@endif

<div class="col-md-12 col-xs-12">
  <div class="panel panel-mobile">
   @include('backend.planning.calendar.tabs')
<?php $inicioAux = $startAux->copy(); ?>
    <div class="tab-content" style="padding: 0px 5px;">
      <div class="tab-pane active" id="tab1">
        <div class="row">
            <div class="table-responsive contentCalendar">
            <table class="fc-border-separate calendar-table " style="width: 100%">
                  @include('backend.planning.calendar.months')
              <tbody>
                <?php 
                $luxAux = 1;
                $typeAux = 2; 
                $currentAux = null; 
                $posicion = 0;
                $arrayLine = ['10G','9R','3M','CHLT','U7','10I','V8'];
                $line = '';
//                $arrayLine = [7,17,18,24,30,31,32,33];
                ?>
                <?php foreach ($roomscalendar as $key => $room): ?>
                  <?php $inicio = $inicioAux->copy() ?>

                  <?php 
                  
                  $posicion++;
                  $luxAux = $room->luxury;
                  $typeAux = $room->sizeApto;
                  $currentAux = $room->channel_group;
                  ?>
                  <tr class="<?php echo $line ?>">

                    <td class="text-center fixed-td">
                      <button class="font-w800 btn btn-xs getImages" type="button" data-toggle="modal" data-target="#modalRoomImages" style="z-index: 99; border: none; background-color: white; color:black;padding: 0;" data-id="<?php echo $room->id; ?>">
                        <i class="fa fa-eye"></i>
                      </button>
                      <b style="cursor: pointer;" data-placement="right" title="" data-toggle="tooltip" data-original-title="<?php echo $room->name ?>" data-kk="{{$room->nameRoom}}">
                    <?php echo substr($room->nameRoom, 0, 5) ?>
                      </b>

                    </td>
                    <td style='width: 20px;'>&nbsp;</td>
                    <?php foreach ($arrayMonths as $key => $daysMonth): ?>
                        
                      <?php for ($i = 01; $i <= $daysMonth; $i++): ?>
                        <?php 
                        $year =  $inicio->copy()->format('Y');
                        ?>
                        <!-- Si existe la reserva para ese dia -->
                        <?php if (isset($arrayReservas[$room->id][$year][$key][$i])): ?>

                          <?php $calendars = $arrayReservas[$room->id][$year][$key][$i] ?>
                          <!-- Si hay una reserva que sale y una que entra  -->
                          <?php if (count($calendars) > 1): ?>
                            <td class="ev-doble">
                              @include('backend.planning.calendar._calendarEventDouble', ['calendars' => $calendars,'inicio'=>$inicio ])
                            </td>
                          <?php else: ?>
                              @include('backend.planning.calendar._calendarEvent', ['calendars' => $calendars,'inicio'=>$inicio ])
                          <?php endif ?>
                          <?php else: ?>
                          <!-- Si no existe nada para ese dia -->
                          <td class="<?php echo $days[$key][$i] ?> no-event">
                          </td>

                        <?php endif; ?>
                        <?php $inicio = $inicio->addDay(); ?>

                    <?php endfor; ?>
                  <?php endforeach ?>
                  </tr>

<?php 
$line = in_array($room->nameRoom, $arrayLine) ? "line-divide " : '';
endforeach; ?>
              </tbody>
              @include('backend.planning.calendar.months_footer')
            </table>

          </div>
        </div>
      </div>

    </div>
  </div>
</div>
