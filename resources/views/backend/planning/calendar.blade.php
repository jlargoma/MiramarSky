<?php
use \Carbon\Carbon;
setlocale(LC_TIME, "ES");
setlocale(LC_TIME, "es_ES");
?>
<div class="col-md-12 col-xs-12">
    <div class="panel panel-mobile">
        <div class="row">
            <?php $dateAux = $inicio->copy(); ?>
            <?php for ($i=1; $i <= 9 ; $i++) :?>

            <button <?php if($dateAux->copy()->format('n') == date('n')){ echo 'id="btn-active"';} ?> class='btn btn-sm btn-default btn-fechas-calendar <?php if($i < 4){ echo 'hidden-xs'; }?>' data-month="<?php echo $dateAux->copy()->format('n') ?>">
                <?php echo ucfirst($dateAux->copy()->formatLocalized('%b %y'))?>
            </button>
            <?php $dateAux->addMonth(); ?>
            <?php endfor; ?>
        </div>
        <?php $inicioAux = $inicio->copy(); ?>
        <div class="tab-content" style="padding: 0px 5px;">
            <div class="tab-pane active" id="tab1">
                <div class="row">
                    <div class="table-responsive content-calendar">
                        <table class="fc-border-separate calendar-table" style="width: 100%">
                            <thead>
                            <tr>
                                <td  style="width: 1%!important"></td>
                                <?php foreach ($arrayMonths as $key => $daysMonth): ?>
                                <?php $monthX =  Carbon::createFromFormat('m' , str_pad($key, 2, "0", STR_PAD_LEFT))->formatLocalized('%B');?>
                                <td id="month-<?php echo $key ?>" colspan="<?php echo $daysMonth ?>" class="text-center months" style="border-right: 1px solid black;border-left: 1px solid black;padding: 5px 10px;">
                                    <?php if ($key != 2): ?>
                                    <span class="font-w600 pull-left" style="padding: 5px;"> <?php echo $monthX ?> </span>
                                    <span class="font-w600" style="padding: 5px;"> <?php echo $monthX ?> </span>
                                    <span class="font-w600 pull-right" style="padding: 5px;"> <?php echo $monthX ?> </span>
                                    <?php else: ?>
                                    <span class="font-w600 pull-left" style="padding: 5px;"> febrero </span>
                                    <span class="font-w600" style="padding: 5px;"> febrero </span>
                                    <span class="font-w600 pull-right" style="padding: 5px;"> febrero </span>
                                    <?php endif ?>
                                </td>
                                <?php endforeach ?>
                            </tr>
                            <tr>
                                <td rowspan="2" style="width: 1%!important"></td>
                                <?php foreach ($arrayMonths as $key => $daysMonth): ?>
                                <?php for ($i=1; $i <= $daysMonth ; $i++): ?>
                                <td style='border:1px solid black;width: 24px; height: 20px;font-size: 10px;padding: 5px!important' class="text-center min-w25">
                                    <?php echo $i ?>
                                </td>
                                <?php endfor; ?>
                                <?php endforeach ?>
                            </tr>
                            <tr>
                                <?php foreach ($arrayMonths as $key => $daysMonth): ?>
                                <?php for ($i=1; $i <= $daysMonth ; $i++): ?>
                                <td style='border:1px solid black;width: 24px; height: 20px;font-size: 10px;padding: 5px!important' class="text-center <?php echo $days[$key][$i]?> min-w25">
                                    <?php echo $days[$key][$i] ?>
                                </td>
                                <?php endfor; ?>
                                <?php endforeach ?>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $luxAux = 1;
                            $typeAux = 2;
                            ?>
                            <?php foreach ($roomscalendar as $key => $room): ?>
                            <?php $inicio = $inicioAux->copy() ?>

                            <?php if ($room->luxury != $luxAux || $room->sizeApto != $typeAux): ?>
                                        <?php $line = "line-divide"; ?>
                                    <?php else: ?>
                                        <?php $line = ""; ?>
                                    <?php endif ?>
                            <?php
                            $luxAux  = $room->luxury;
                            $typeAux = $room->sizeApto;
                            ?>
                            <tr class="<?php echo $line ?>">

                                <td class="text-center fixed-td">
                                    <button class="font-w800 btn btn-xs getImages" type="button" data-toggle="modal" data-target="#modalRoomImages" style="z-index: 99; border: none; background-color: white; color:black;padding: 0;" data-id="<?php echo $room->id; ?>">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                    <b style="cursor: pointer;" data-placement="right" title="" data-toggle="tooltip" data-original-title="<?php echo $room->name ?>">
                                        <?php echo substr($room->nameRoom, 0,5)?>
                                    </b>

                                </td>
                            <?php foreach ($arrayMonths as $key => $daysMonth): ?>
                            <?php for ($i=01; $i <= $daysMonth  ; $i++): ?>
                            <!-- Si existe la reserva para ese dia -->
                            <?php if (isset($arrayReservas[$room->id][$inicio->copy()->format('Y')][$key][$i])): ?>

                            <?php $calendars = $arrayReservas[$room->id][$inicio->copy()->format('Y')][$key][$i] ?>
                            <!-- Si hay una reserva que sale y una que entra  -->
                                <?php if (count($calendars) > 1): ?>

                                <td style='border:1px solid grey;width: 24px; height: 20px;'>
                                    <?php for ($x = 0; $x < count($calendars); $x++): ?>

                                    <?php if($calendars[$x]->finish == $inicio->copy()->format('Y-m-d') && $calendars[$x]->type_book != 5): ?>
                                    <a
                                        <?php if ( Auth::user()->role != "agente"): ?>
                                        href="{{url ('/admin/reservas/update')}}/<?php echo $calendars[$x]->id ?>"
                                        <?php endif ?>

                                        <?php //getAgency
                                        $agency = ($calendars[$x]->agency != 0)?"Agencia: ".$calendars[$x]->getAgency($calendars[$x]->agency):"";
                                        if (Auth::user()->role != "agente")
                                        {
                                            $titulo =
                                                $calendars[$x]->customer['name'].'&#10'.
                                                'Pax-real '.$calendars[$x]->real_pax.'&#10;'.Carbon::createFromFormat('Y-m-d',$calendars[$x]->start)->formatLocalized('%d %b').' - '.Carbon::createFromFormat('Y-m-d',$calendars[$x]->finish)->formatLocalized('%d %b').'&#10;'.
                                                'PVP:'.$calendars[$x]->total_price.'&#10'.
                                                $agency;
                                        } else
                                        {
                                            $titulo =
                                                $calendars[$x]->customer['name'].'&#10'.
                                                'Pax-real '.$calendars[$x]->real_pax.'&#10;'.Carbon::createFromFormat('Y-m-d',$calendars[$x]->start)->formatLocalized('%d %b').' - '.Carbon::createFromFormat('Y-m-d',$calendars[$x]->finish)->formatLocalized('%d %b').'&#10;'.$agency;
                                        }

                                        ?>
                                        title="<?php echo $titulo ?>"
                                    >
                                        <?php $class = $calendars[$x]->getStatus($calendars[$x]->type_book) ?>
                                        <?php if ($class == "Contestado(EMAIL)"): ?>
                                                                                 <?php $class = "contestado-email" ?>
                                                                            <?php endif ?>

                                        <div class="<?php echo $class ;?> end" style="width: 45%;float: left;">
                                            &nbsp;
                                        </div>
                                    </a>
                                    <?php elseif ($calendars[$x]->start == $inicio->copy()->format('Y-m-d') && $calendars[$x]->type_book != 5 ): ?>

                                    <a
                                        <?php if ( Auth::user()->role != "agente"): ?>href="{{url ('/admin/reservas/update')}}/<?php echo $calendars[$x]->id ?>"  <?php endif ?>

                                    <?php
                                    $agency = ($calendars[$x]->agency != 0)?"Agencia: ".$calendars[$x]->getAgency($calendars[$x]->agency):"";
                                    if (Auth::user()->role != "agente")
                                    {
                                        $titulo =
                                            $calendars[$x]->customer['name'].'&#10'.
                                            'Pax-real '.$calendars[$x]->real_pax.'&#10;'.Carbon::createFromFormat('Y-m-d',$calendars[$x]->start)->formatLocalized('%d %b').' - '.Carbon::createFromFormat('Y-m-d',$calendars[$x]->finish)->formatLocalized('%d %b').'&#10;'.
                                            'PVP:'.$calendars[$x]->total_price.'&#10'.
                                            $agency;
                                    } else
                                    {
                                        $titulo =
                                            $calendars[$x]->customer['name'].'&#10'.
                                            'Pax-real '.$calendars[$x]->real_pax.'&#10;'.Carbon::createFromFormat('Y-m-d',$calendars[$x]->start)->formatLocalized('%d %b').' - '.Carbon::createFromFormat('Y-m-d',$calendars[$x]->finish)->formatLocalized('%d %b').'&#10;'.$agency;
                                    }
                                    ?>
                                    title="<?php echo $titulo ?>"
                                    >
                                        <?php if ($calendars[$x]->getStatus($calendars[$x]->type_book) != "Booking"): ?>
                                        <?php $class = $calendars[$x]->getStatus($calendars[$x]->type_book) ?>
                                        <?php if ($class == "Contestado(EMAIL)"): ?>
                                                                                 <?php $class = "contestado-email" ?>
                                                                            <?php endif ?>

                                        <div class="<?php echo $class ;?> start" style="width: 45%;float: right;">
                                            &nbsp;
                                        </div>
                                        <?php endif ?>

                                    </a>


                                    <?php else: ?>
                                    <?php if ($calendars[$x]->type_book != 9 && $calendars[$x]->type_book != 5): ?>
                                    <a
                                        <?php if ( Auth::user()->role != "agente"): ?>href="{{url ('/admin/reservas/update')}}/<?php echo $calendars[$x]->id ?>" <?php endif ?>
                                    <?php
                                    $agency = ($calendars[$x]->agency != 0)?"Agencia: ".$calendars[$x]->getAgency($calendars[$x]->agency):"";
                                    if (Auth::user()->role != "agente")
                                    {
                                        $titulo =
                                            $calendars[$x]->customer['name'].'&#10'.
                                            'Pax-real '.$calendars[$x]->real_pax.'&#10;'.Carbon::createFromFormat('Y-m-d',$calendars[$x]->start)->formatLocalized('%d %b').' - '.Carbon::createFromFormat('Y-m-d',$calendars[$x]->finish)->formatLocalized('%d %b').'&#10;'.
                                            'PVP:'.$calendars[$x]->total_price.'&#10'.
                                            $agency;
                                    } else
                                    {
                                        $titulo =
                                            $calendars[$x]->customer['name'].'&#10'.
                                            'Pax-real '.$calendars[$x]->real_pax.'&#10;'.Carbon::createFromFormat('Y-m-d',$calendars[$x]->start)->formatLocalized('%d %b').' - '.Carbon::createFromFormat('Y-m-d',$calendars[$x]->finish)->formatLocalized('%d %b').'&#10;'.$agency;
                                    }
                                    ?>
                                    title="<?php echo $titulo ?>"
                                    >
                                        <?php $class = $calendars[$x]->getStatus($calendars[$x]->type_book) ?>
                                        <?php if ($class == "Contestado(EMAIL)"): ?>
                                                                                 <?php $class = "contestado-email" ?>
                                                                            <?php endif ?>

                                        <div class="<?php echo $class ;?>" style="width: 100%;float: left;">
                                            &nbsp;
                                        </div>
                                    </a>
                                    <?php endif ?>
                                    <?php endif ?>
                                    <?php endfor ?>

                                </td>

                                <!-- Si no hay dos reservas el mismo dia  -->
                                <?php else: ?>
                                <?php $calendars[0] = ($calendars[0])?$calendars[0]:$calendars?>

                                <?php if ($calendars[0]->start == $inicio->copy()->format('Y-m-d')): ?>
                                <td
                                    <?php
                                    $agency = ($calendars[0]->agency != 0)?"Agencia: ".$calendars[0]->getAgency($calendars[0]->agency):"";

                                    if (Auth::user()->role != "agente")
                                    {
                                        $titulo =
                                            $calendars[0]->customer['name'].'&#10'.
                                            'Pax-real '.$calendars[0]->real_pax.'&#10;'.
                                            ''.Carbon::createFromFormat('Y-m-d',$calendars[0]->start)->formatLocalized('%d %b').' - '.Carbon::createFromFormat('Y-m-d',$calendars[0]->finish)->formatLocalized('%d %b').'&#10;'.
                                            'PVP:'.$calendars[0]->total_price.'&#10'.$agency;
                                    } else
                                    {
                                        $titulo =
                                            $calendars[0]->customer['name'].'&#10'.
                                            'Pax-real '.$calendars[0]->real_pax.'&#10;'.
                                            ''.Carbon::createFromFormat('Y-m-d',$calendars[0]->start)->formatLocalized('%d %b').' - '.Carbon::createFromFormat('Y-m-d',$calendars[0]->finish)->formatLocalized('%d %b').'&#10;'.$agency;
                                    }

                                    ?>

                                    title="<?php echo $titulo ?>"
                                    style='border:1px solid grey;width: 24px; height: 20px;'>

                                    <?php $class = $calendars[0]->getStatus($calendars[0]->type_book) ?>
                                    <?php if ($class == "Contestado(EMAIL)"): ?>
                                                                     <?php $class = "contestado-email" ?>
                                                                <?php endif ?>

                                    <div class="<?php echo $class ;?> start" style="width: 45%;float: right;">
                                        &nbsp;
                                    </div>

                                </td>
                                <?php elseif($calendars[0]->finish == $inicio->copy()->format('Y-m-d')): ?>
                                <td
                                    <?php
                                    $agency = ($calendars[0]->agency != 0)?"Agencia: ".$calendars[0]->getAgency($calendars[0]->agency):"";

                                    if (Auth::user()->role != "agente")
                                    {
                                        $titulo =
                                            $calendars[0]->customer['name'].'&#10'.
                                            'Pax-real '.$calendars[0]->real_pax.'&#10;'.
                                            ''.Carbon::createFromFormat('Y-m-d',$calendars[0]->start)->formatLocalized('%d %b').' - '.Carbon::createFromFormat('Y-m-d',$calendars[0]->finish)->formatLocalized('%d %b').'&#10;'.
                                            'PVP:'.$calendars[0]->total_price.'&#10'.$agency;
                                    } else
                                    {
                                        $titulo =
                                            $calendars[0]->customer['name'].'&#10'.
                                            'Pax-real '.$calendars[0]->real_pax.'&#10;'.
                                            ''.Carbon::createFromFormat('Y-m-d',$calendars[0]->start)->formatLocalized('%d %b').' - '.Carbon::createFromFormat('Y-m-d',$calendars[0]->finish)->formatLocalized('%d %b').'&#10;'.$agency;
                                    }
                                    ?>

                                    title="<?php echo $titulo ?>"
                                    style='border:1px solid grey;width: 24px; height: 20px;'>

                                    <?php $class = $calendars[0]->getStatus($calendars[0]->type_book) ?>
                                    <?php if ($class == "Contestado(EMAIL)"): ?>
                                                                     <?php $class = "contestado-email" ?>
                                                                <?php endif ?>

                                    <div class="<?php echo $class ;?> end" style="width: 45%;float: left;">
                                        &nbsp;
                                    </div>


                                </td>
                                <?php else: ?>

                                <td
                                        style='border:1px solid grey;width: 24px; height: 20px;'
                                        <?php
                                        $agency = ($calendars[0]->agency != 0)?"Agencia: ".$calendars[0]->getAgency($calendars[0]->agency):"";
                                        if (Auth::user()->role != "agente")
                                        {
                                            $titulo =
                                                $calendars[0]->customer['name'].'&#10'.
                                                'Pax-real '.$calendars[0]->real_pax.'&#10;'.
                                                ''.Carbon::createFromFormat('Y-m-d',$calendars[0]->start)->formatLocalized('%d %b').' - '.Carbon::createFromFormat('Y-m-d',$calendars[0]->finish)->formatLocalized('%d %b').'&#10;'.
                                                'PVP:'.$calendars[0]->total_price.'&#10'.$agency;
                                        } else
                                        {
                                            $titulo =
                                                $calendars[0]->customer['name'].'&#10'.
                                                'Pax-real '.$calendars[0]->real_pax.'&#10;'.
                                                ''.Carbon::createFromFormat('Y-m-d',$calendars[0]->start)->formatLocalized('%d %b').' - '.Carbon::createFromFormat('Y-m-d',$calendars[0]->finish)->formatLocalized('%d %b').'&#10;'.$agency;
                                        }
                                        ?>
                                        title="<?php echo $titulo ?> "
                                        <?php $class = $calendars[0]->getStatus($calendars[0]->type_book) ?>
                                        <?php if ($class == "Contestado(EMAIL)"): ?>
                                                                     <?php $class = "contestado-email" ?>
                                                                <?php endif ?>

                                        class="<?php echo $class ;?>"
                                >
                                    <?php if ($calendars[0]->type_book == 9): ?>
                                    <div style="width: 100%;height: 100%">
                                        &nbsp;
                                    </div>
                                    <?php else: ?>
                                    <a <?php if ( Auth::user()->role != "agente"): ?>href="{{url ('/admin/reservas/update')}}/<?php echo $calendars[0]->id ?>"<?php endif ?>>
                                        <div style="width: 100%;height: 100%">
                                            &nbsp;
                                        </div>
                                    </a>
                                    <?php endif ?>


                                </td>

                                <?php endif ?>
                                <?php endif ?>
                            <!-- Si no existe nada para ese dia -->
                                <?php else: ?>

                                <td class="asdas<?php echo $days[$key][$i]?>" style='border:1px solid grey;width: 24px; height: 20px;'>

                                </td>

                                <?php endif; ?>


                                <?php $inicio = $inicio->addDay(); ?>

                                <?php endfor; ?>
                                <?php endforeach ?>
                            </tr>

                            <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php $inicio = $inicio->addMonth(); ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
    <script type="text/javascript">

      $('.btn-fechas-calendar').click(function(event) {
        event.preventDefault();
        $('.btn-fechas-calendar').css({
          'background-color': '#899098',
          'color': '#fff'
        });
        $(this).css({
          'background-color': '#10cfbd',
          'color': '#fff'
        });
        var target = $(this).attr('data-month');
        var targetPosition = $('.content-calendar #month-'+target).position();
        // alert("Left: "+targetPosition.left+ ", right: "+targetPosition.right);
        $('.content-calendar').animate({ scrollLeft: "+="+targetPosition.left+"px" }, "slow");
      });


      $('#btn-active').trigger('click');

      // Ver imagenes por piso

      $('.getImages').click(function(event) {
        var idRoom = $(this).attr('data-id');
        $.get('/admin/rooms/api/getImagesRoom/'+idRoom, function(data) {
          $('#modalRoomImages .modal-content').empty().append(data);
        });
      });
    </script>