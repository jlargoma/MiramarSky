<?php   
    use \Carbon\Carbon;  
    setlocale(LC_TIME, "ES"); 
    setlocale(LC_TIME, "es_ES"); 
?>
<style type="text/css">
    .btn-fechas-calendar{
        background-color: #899098;
        color: white;
        border-radius: 0px!important;
        text-transform: uppercase;
    }
    .fixed-td{
        position: absolute;
        background-color: #fff;
        left: -20px;
        border: 1px solid #d8d8d8;
        padding: 0px 5px!important;
        border-right: 0px;
    }
    .line-divide{
        border-top: 2px solid #000;
    }
    @media screen and (max-width: 767px){
        .fixed-td{
            left: 0px;
        }
        .btn-fechas-calendar{
            font-size: 11px;
            float: left;
            width: 73px;
        }
        .btn-fechas-calendar[data-month="5"]{
            display: none;
        }
    }
</style>
    <div class="panel">
        <div class="row">
          <?php $dateAux = $inicio->copy(); ?>
          <?php for ($i=1; $i <= 9 ; $i++) :?>
				
              	<button <?php if($i == 4){ echo 'id="btn-active"';} ?> class='btn btn-sm btn-default btn-fechas-calendar <?php if($i < 4){ echo 'hidden-xs'; }?>' data-month="<?php echo $dateAux->copy()->format('n') ?>">
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
                                <?php foreach ($roomscalendar as $key => $roomX): ?>
									<?php $inicio = $inicioAux->copy() ?>

                                    <?php 
                                        $luxAux  = $roomX->luxury;
                                        $typeAux = $roomX->sizeApto;
                                    ?>
                                    <tr >
                                        
                                        <td class="text-center fixed-td">
                                            <b style="cursor: pointer;" data-placement="right" title="" data-toggle="tooltip" data-original-title="<?php echo $roomX->name ?>">
                                                <?php echo substr($roomX->nameRoom, 0,5)?>   
                                            </b>
                                        </td>
                                        <?php foreach ($arrayMonths as $key => $daysMonth): ?>
                                            <?php for ($i=01; $i <= $daysMonth  ; $i++): ?> 
                                                <!-- Si existe la reserva para ese dia -->
                                                <?php if (isset($arrayReservas[$roomX->id][$inicio->copy()->format('Y')][$key][$i])): ?>

                                                    <?php $calendars = $arrayReservas[$roomX->id][$inicio->copy()->format('Y')][$key][$i] ?>
                                                    <!-- Si hay una reserva que sale y una que entra  -->
                                                    <?php if (count($calendars) > 1): ?>
                                                        
                                                        <td style='border:1px solid grey;width: 24px; height: 20px;'>
                                                            <?php for ($x = 0; $x < count($calendars); $x++): ?>

                                                                <?php if($calendars[$x]->finish == $inicio->copy()->format('Y-m-d') && $calendars[$x]->type_book != 5): ?>
                                                                    <a 
                                                                        href="{{url ('/admin/reservas/update')}}/<?php echo $calendars[$x]->id ?>" 
                                                                        <?php $titulo =  $calendars[$x]->customer['name'] ?>
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
                                                                        href="{{url ('/admin/reservas/update')}}/<?php echo $calendars[$x]->id ?>" 
                                                                        <?php $titulo =  $calendars[$x]->customer['name'] ?>
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
                                                                            href="{{url ('/admin/reservas/update')}}/<?php echo $calendars[$x]->id ?>" 
                                                                            <?php $titulo =  $calendars[$x]->customer['name'] ?>
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
                                                                <?php $titulo =  $calendars[0]->customer['name'] ?>

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
                                                                <?php $titulo =  $calendars[0]->customer['name'] ?>

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
                                                            <?php $titulo =  $calendars[0]->customer['name'] ?>
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
                                                                    <a href="{{url ('/admin/reservas/update')}}/<?php echo $calendars[0]->id ?>">
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
