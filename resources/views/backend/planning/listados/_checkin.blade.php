<?php   use \Carbon\Carbon;  
        setlocale(LC_TIME, "ES"); 
        setlocale(LC_TIME, "es_ES"); 
?>
<?php $startWeek = Carbon::now()->startOfWeek(); ?>
<?php $endWeek = Carbon::now()->endOfWeek(); ?>
<?php if(!$mobile->isMobile() ): ?>
    <div class="tab-pane" id="tabPagadas">
        <div class="row  table-responsive">
            <table class="table <?php if (Auth::user()->role != "limpieza"): ?>table-condensed<?php endif ?> table-striped table-data"  data-type="confirmadas" style="margin-top: 0;">
                <thead>
                    <tr class ="text-center bg-success text-white">
                      <th class="th-bookings th-2" >&nbsp;</th> 
                        <th class="th-bookings th-name">Cliente</th>
                        <th class="th-bookings">Telefono</th>
                        <th class="th-bookings th-2">Pax</th>
                        <th class="th-bookings">Apart</th>
                        <th class="th-bookings th-2">  <i class="fa fa-moon-o"></i> </th>
                        <th class="th-bookings th-2"> <i class="fa fa-clock-o"></i></th>
                        <th class="th-bookings  th-4">   IN     </th>
                        <th class="th-bookings th-4">   OUT      </th>
                        <th class="th-bookings th-3 hiddenOnlyRiad">FF</th>
                        <th class="th-bookings th-6">   Precio      </th>
                        <?php if (Auth::user()->role != "limpieza"): ?>
                            <th class="th-bookings th-6">   a      </th>
                        <?php endif ?>
                        <th class="th-bookings th-2">&nbsp;</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php $count = 0 ?>
                    <?php foreach ($books as $book): ?>
                        <?php if ( $book->start >= $startWeek->copy()->format('Y-m-d') && $book->start <= $endWeek->copy()->format('Y-m-d')): ?>

                           <?php if ( $book->start <= Carbon::now()->copy()->subDay()->format('Y-m-d') ): ?>
                               <?php $class = "blurred-line" ?>
                           <?php else: ?>
                               <?php $class = "" ?>
                           <?php endif ?>

                       <?php else: ?>
                           
                           <?php if ( $book->start <= Carbon::now()->copy()->subDay()->format('Y-m-d') ): ?>
                               <?php $class = "blurred-line" ?>
                           <?php else: ?>
                               <?php $class = "lined"; $count++ ?>
                           <?php endif ?>
                       <?php endif ?>

                        <tr class="<?php if($count <= 1){echo $class;} ?>">
                            <?php 
                                $dateStart = Carbon::createFromFormat('Y-m-d', $book->start);
                                $now = Carbon::now();
                            ?>
                            <td class="text-center">
                                <?php if ( $payment[$book->id] == 0): ?>
                                    <?php if ( $now->diffInDays($dateStart) <= 15 ):?>
                                        <span class=" label label-danger alertDay heart text-white">
                                            <i class="fa fa-bell"></i>
                                        </span>
                                    <?php elseif($now->diffInDays($dateStart) <= 7):?>
                                        <span class=" label label-danger alertDay heart text-white">
                                            <i class="fa fa-bell"></i>
                                        </span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <?php 
                                    $percent = 0;
                                    if ($book->total_price > 0)
                                      $percent = 100 / ( $book->total_price / $payment[$book->id] ); 
                                    ?>
                                    <?php if ( $percent <= 25 ): ?>

                                        <?php if ( $now->diffInDays($dateStart) <= 15 ):?>
                                            <span class=" label label-danger alertDay heart text-white">
                                                <i class="fa fa-bell"></i>
                                            </span>
                                        <?php elseif($now->diffInDays($dateStart) <= 7):?>
                                            <span class=" label label-danger alertDay heart text-white">
                                                <i class="fa fa-bell"></i>
                                            </span>
                                        <?php endif; ?>
                                        
                                    <?php endif ?>
                                    

                                <?php endif ?>



                                <?php if ($book->agency != 0): ?>
                                    <img style="width: 15px;margin: 0 auto;" src="/pages/<?php echo strtolower($book->getAgency($book->agency)) ?>.png" align="center" />
                                <?php endif ?>
                                @if($book->is_fastpayment == 1 || $book->type_book == 99 )
                                <img style="width: 15px;margin: 0 auto;" src="/pages/fastpayment.png" align="center"/>
                                @endif
                            </td>
                            <td class="text-center" style="padding: 10px !important">
                                <?php if (isset($payment[$book->id])): ?>
                                    <a class="update-book" data-id="<?php echo $book->id ?>"  title="<?php echo $book->customer['name'] ?> - <?php echo $book->customer['email'] ?>"  href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>" style="color: red"><?php echo $book->customer['name']  ?></a>
                                <?php else: ?>
                                    <a class="update-book" data-id="<?php echo $book->id ?>"  title="<?php echo $book->customer['name'] ?> - <?php echo $book->customer['email'] ?>"  href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>" ><?php echo $book->customer['name']  ?></a>
                                <?php endif ?>
                                <?php if (Auth::user()->role != "limpieza"): ?>
                                <?php if (!empty($book->comment) || !empty($book->book_comments)): ?>
                                    <?php 
                                        $textComment = "";
                                        if (!empty($book->comment)) {
                                            $textComment .= "<b>COMENTARIOS DEL CLIENTE</b>:"."<br>"." ".$book->comment."<br>";
                                        }
                                        if (!empty($book->book_comments)) {
                                            $textComment .= "<b>COMENTARIOS DE LA RESERVA</b>:"."<br>"." ".$book->book_comments;
                                        }
                                    ?>
                                    <span class="icons-comment" data-class-content="content-comment-<?php echo $book->id?>">
                                        <i class="fa fa-commenting" style="color: #000;" aria-hidden="true"></i>
                                    </span>
                                    <div class="comment-floating content-comment-<?php echo $book->id?>" style="display: none;"><p class="text-left"><?php echo $textComment ?></p></div>
                            <?php endif ?>
                                <?php endif ?>
                            </td>
                            <td class="text-center">
                                <?php if ($book->customer->phone != 0 && $book->customer->phone != "" ): ?>
                                    <a href="tel:<?php echo $book->customer->phone ?>"><?php echo $book->customer->phone ?>
                                <?php else: ?>
                                    <input type="text" class="only-numbers customer-phone" data-id="<?php echo $book->customer->id ?>"/>
                                <?php endif ?>
                            </td>
                            <td class ="text-center" >
                                <?php if ($book->real_pax > 6): ?>
                                    <?php echo $book->real_pax ?><i class="fa fa-exclamation" aria-hidden="true" style="color: red"></i>
                                <?php else: ?>
                                    <?php echo $book->pax ?>
                                <?php endif ?>
                                    
                            </td>
                            <td class ="text-center">
                               @include('backend.planning.listados._select-rooms', ['rooms'=>$rooms,'bookID' => $book->id,'select'=>$book->room_id])
                            </td>
                            <td class ="text-center"><?php echo $book->nigths ?></td>
                            <td class="text-center sm-p-t-10 sm-p-b-10">
                                
                                <select id="schedule" class="<?php if(!$mobile->isMobile() ): ?>form-control minimal<?php endif; ?> <?php if ($book->schedule < 17 && $book->schedule > 0): ?>alerta-horarios<?php endif ?>" data-type="in" data-id="<?php echo $book->id ?>" <?php if (Auth::user()->role == "limpieza"): ?>disabled<?php
                                endif ?>>
                                    <option>-- Sin asignar --</option>
                                    <?php for ($i = 0; $i < 24; $i++): ?>
                                        <option value="<?php echo $i ?>" <?php if($i == $book->schedule) { echo 'selected';}?>>
                                            <?php if ($i < 10): ?>
                                                <?php if ($i == 0): ?>
                                                    --
                                                <?php else: ?>
                                                    0<?php echo $i ?>
                                                <?php endif ?>
                                                
                                            <?php else: ?>
                                                <?php echo $i ?>
                                            <?php endif ?>
                                        </option>
                                    <?php endfor ?>
                                </select>
                            </td>

                            <?php $start = Carbon::createFromFormat('Y-m-d',$book->start); ?>
                            <td class ="text-center" data-order="<?php echo strtotime($start->copy()->format('Y-m-d'))?>"  style="width:20%!important">
                                <b><?php echo $start->formatLocalized('%d %b'); ?></b>
                            </td>

                            <?php $finish = Carbon::createFromFormat('Y-m-d',$book->finish);?>
                            <td class ="text-center" data-order="<?php echo strtotime($finish->copy()->format('Y-m-d'))?>"  style="width: 20%!important">
                                <b><?php echo $finish->formatLocalized('%d %b'); ?></b>
                            </td>
                            
                            <td class="text-center hiddenOnlyRiad ">
                              <a data-booking="<?php echo $book->id; ?>" class="openFF showFF_resume" title="Ir a Forfaits" >
                                <?php
                                $ff_status = $book->get_ff_status();
                                if ($ff_status['icon']) {
                                  echo '<img src="' . $ff_status['icon'] . '" style="max-width:30px;" alt="' . $ff_status['name'] . '"/>';
                                }
                                ?>
                                <div class="FF_resume tooltiptext"></div>
                                </a>
                            </td>
                           
                            <td class ="text-center">
                                <?php if (Auth::user()->role != "limpieza"): ?>
                                    <div class="col-md-6 col-xs-12 not-padding">
                                    <?php echo round($book->total_price)."€" ?><br>
                                        <?php if (isset($payment[$book->id])): ?>
                                        <p style="color: <?php if ($book->total_price == $payment[$book->id]):?>#008000<?php else: ?>red<?php endif ?>;">
                                                <?php echo $payment[$book->id] ?> €
                                            </p>
                                        <?php else: ?>
                                    <?php endif ?>
                                </div>

                                    <?php if (isset($payment[$book->id])): ?>
                                    <?php if ($payment[$book->id] == 0): ?>
                                    <div class="col-md-5 col-xs-12 not-padding bg-success">
                                        <b style="color: red;font-weight: bold">0%</b>
                                        </div>
                                    <?php else:?>
                                    <div class="col-md-5  col-xs-12 not-padding">
	                              <?php 
                                      if (isset($payment[$book->id]) && $payment[$book->id]>0 && $book->total_price>0)
                                        $total = number_format(100/($book->total_price/$payment[$book->id]),0);
                                      else $total = 0;
                                      ?>
                                        <p class="text-white m-t-10">
                                                <b style="color: <?php if ($total == 100):?>#008000<?php else: ?>red<?php endif ?>;font-weight: bold"><?php echo $total.'%' ?></b>
                                            </p>
                                        </div>

                                    <?php endif; ?>
                                    <?php else: ?>
                                    <div class="col-md-5 col-xs-12 not-padding bg-success">
                                        <b style="color: red;font-weight: bold">0%</b>
                                        </div>
                                    <?php endif ?>
                                <?php else: ?>
                                    <?php echo round($book->total_price)."€" ?>
                                <?php endif ?>
                            </td>

                            <?php if (Auth::user()->role != "limpieza"): ?>
                            <td class="text-center sm-p-t-10 sm-p-b-10">

                               
                                <?php if ($book->send == 1): ?>
                                    <button data-id="<?php echo $book->id ?>" class="btn btn-xs btn-default sendSecondPay" type="button" data-toggle="tooltip" title="" data-original-title="Enviar recordatorio segundo pago" data-sended="1">
                                        <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                    </button> 
                                <?php else: ?>
                                    <button data-id="<?php echo $book->id ?>" class="btn btn-xs btn-primary sendSecondPay" type="button" data-toggle="tooltip" title="" data-original-title="Enviar recordatorio segundo pago" data-sended="0">
                                        <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                    </button> 
                                <?php endif ?>
                                 <?php
                                $partee = $book->partee();
                                if ($partee):
                                  echo $partee->print_status($book->id,$book->start,$book->pax);
                                 endif;
                                 echo $book->getFianza();
                                ?>
                            </td>
                            <?php endif ?>
                            <td class="text-center">
                                <?php if ($book->promociones> 0 ): ?>
                                    <span class="icons-comment" data-class-content="content-commentOwned-<?php echo $book->id?>">
                                        <img src="/pages/oferta.png" style="width: 40px;">
                                    </span>
                                    <div class="comment-floating content-commentOwned-<?php echo $book->id?>" style="display: none;"><p class="text-left"><?php echo $book->book_owned_comments ?></p></div>
                                    
                                <?php endif ?>
                            </td>
                            
                            
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>  
        </div>
    </div>
<?php else: ?>
<div class="table-responsive" style="border: none!important">
    <table class="table table-striped table-data" style="margin-top: 0;">
        <thead>
            <th class="bg-success text-white text-center" >Nom</th>
            <th class="bg-success text-white text-center" style="min-width:50px">Apto</th>
            <th class="bg-success text-white text-center" style="max-width:50px">T</th>
            <th class="bg-success text-white text-center" style="min-width:55px">PVP</th>
            <th class="bg-success text-white text-center" style="min-width:30px ">Hor</th>
            <th class="bg-success text-white text-center" style="min-width:50px">In</th>
            <th class="bg-success text-white text-center" style="min-width:50px ">Out2</th>
            <th class="bg-success text-white text-center hiddenOnlyRiad" style="min-width:50px">FF</th>
            <th class="bg-success text-white text-center">Pax</th>
            <th class="bg-success text-white text-center"><i class="fa fa-moon-o"></i></th>
            <th class="bg-success text-white text-center">a</th>
        </thead>
        <tbody>

            <?php $count = 0 ?>
            <?php foreach ($books as $book): ?>
                <?php 
                    $dateStart = Carbon::createFromFormat('Y-m-d', $book->start);
                    $now = Carbon::now();
                ?>
                <?php if ( $book->start >= $startWeek->copy()->format('Y-m-d') && $book->start <= $endWeek->copy()->format('Y-m-d')): ?>

                    <?php if ( $book->start <= Carbon::now()->copy()->subDay()->format('Y-m-d') ): ?>
                        <?php $class = "blurred-line" ?>
                    <?php else: ?>
                        <?php $class = "" ?>
                    <?php endif ?>

                <?php else: ?>
                    
                    <?php if ( $book->start <= Carbon::now()->copy()->subDay()->format('Y-m-d') ): ?>
                        <?php $class = "blurred-line" ?>
                    <?php else: ?>
                        <?php $class = "lined"; $count++ ?>
                    <?php endif ?>
                <?php endif ?>

                <tr class="<?php if($count <= 1){echo $class;} ?> <?php echo ucwords($book->getStatus($book->type_book)) ;?>">

                    <td class="text-left" style="padding: 5px !important;">
                        <a href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>">
                            <?php echo str_pad(substr($book->customer->name, 0, 10), 10, " "); ?> 
                        </a><br>
                        
                        <?php if ( $payment[$book->id] == 0): ?>
                            <?php if ( $now->diffInDays($dateStart) <= 15 ):?>
                                <span class=" lertDay heart text-danger">
                                    <i class="fa fa-bell"></i>
                                </span>
                            <?php elseif($now->diffInDays($dateStart) <= 7):?>
                                <span class=" lertDay heart text-danger">
                                    <i class="fa fa-bell"></i>
                                </span>
                            <?php endif; ?>
                        <?php else: ?>
                            <?php 
                            if (isset($payment[$book->id]) && $payment[$book->id]>0 && $book->total_price>0)
                              $percent = 100 / ( $book->total_price / $payment[$book->id] ); 
                            else $percent = 0;
                            ?>
                            <?php if ( $percent <= 25 ): ?>

                                <?php if ( $now->diffInDays($dateStart) <= 15 ):?>
                                    <span class="alertDay heart text-danger">
                                        <i class="fa fa-bell"></i>
                                    </span>
                                <?php elseif($now->diffInDays($dateStart) <= 7):?>
                                    <span class="alertDay heart text-danger">
                                        <i class="fa fa-bell"></i>
                                    </span>
                                <?php endif; ?>
                                
                            <?php endif ?>
                        <?php endif ?>  
                        
                        <?php if ($book->agency != 0): ?>
                            <img style="width: 20px;margin: 0 auto;" src="/pages/<?php echo strtolower($book->getAgency($book->agency)) ?>.png" align="center" />
                        <?php endif ?>
                        @if($book->is_fastpayment == 1 || $book->type_book == 99 )
                        <img style="width: 30px;margin: 0 auto;" src="/pages/fastpayment.png" align="center"/>
                        @endif
                    </td>
                    <td class="text-center">
                        <b><?php echo substr($book->room->nameRoom, 0, 4)  ?></b>
                    </td>
                    <td class="text-center">
                        <?php if ($book->customer->phone != 0 && $book->customer->phone != "" ): ?>
                             <a href="tel:<?php echo $book->customer->phone ?>"><i class="fa fa-phone"></i></a>
                        <?php else: ?>
                            <input type="text" class="only-numbers customer-phone" data-id="<?php echo $book->customer->id ?>"/>
                        <?php endif ?>
                       
                    </td>
                    <td class="text-center">
                       <div class="col-md-6">
                           <?php echo round($book->total_price)."€" ?><br>
                           <?php if (isset($payment[$book->id])): ?>
                               <?php echo "<p style='color:red'>".number_format($payment[$book->id],0,',','.')."€</p>" ?>
                           <?php else: ?>
                           <?php endif ?>
                       </div>
                    </td>
                    <td class="text-center sm-p-t-10 sm-p-b-10">
                        <select id="schedule" style="width: 80%;" class="<?php if(!$mobile->isMobile() ): ?>form-control minimal<?php endif; ?> <?php if ($book->schedule < 17 && $book->schedule > 0): ?>alerta-horarios<?php endif ?>" data-type="in" data-id="<?php echo $book->id ?>">
                            <option>-- Sin asignar --</option>
                            <?php for ($i = 0; $i < 24; $i++): ?>
                                <option value="<?php echo $i ?>" <?php if($i == $book->schedule) { echo 'selected';}?>>
                                    <?php if ($i < 10): ?>
                                        <?php if ($i == 0): ?>
                                            --
                                        <?php else: ?>
                                            0<?php echo $i ?>
                                        <?php endif ?>
                                        
                                    <?php else: ?>
                                        <?php echo $i ?>
                                    <?php endif ?>
                                </option>
                            <?php endfor ?>
                        </select>
                    </td>
	                <?php $start = Carbon::createFromFormat('Y-m-d',$book->start); ?>
                    <td class ="text-center" data-order="<?php echo strtotime($start->copy()->format('Y-m-d'))?>"  style="width:
                20%!important">
		                <?php echo $start->formatLocalized('%d %b'); ?>
                    </td>
	                <?php $finish = Carbon::createFromFormat('Y-m-d',$book->finish);?>
                    <td class ="text-center" data-order="<?php echo strtotime($finish->copy()->format('Y-m-d'))?>"  style="width: 20%!important">
		                <?php echo $finish->formatLocalized('%d %b'); ?>
                    </td>
                    
                    <td class="text-center hiddenOnlyRiad ">
                      <a data-booking="<?php echo $book->id; ?>" class="openFF showFF_resume" title="Ir a Forfaits" >
                     <?php
                     $ff_status = $book->get_ff_status();
                     if ($ff_status['icon']) {
                       echo '<img src="' . $ff_status['icon'] . '" style="max-width:30px;" alt="' . $ff_status['name'] . '"/>';
                     }
                     ?>
                      <div class="FF_resume tooltiptext"></div>
                     </a>  
                    </td>
                    
                    <td class ="text-center" >
                        <?php if ($book->real_pax > 6 ): ?>
                            <?php echo $book->real_pax ?><i class="fa fa-exclamation" aria-hidden="true" style="color: red"></i>
                        <?php elseif($book->pax != $book->real_pax): ?>
                            <?php echo $book->real_pax ?><i class="fa fa-exclamation-circle" aria-hidden="true" style="color: red"></i>
                        <?php else: ?>
                            <?php echo $book->pax ?>
                        <?php endif ?>
                            
                    </td>
                    
                    
                    <td class="text-center"><?php echo $book->nigths ?></td>
                    
                    
                    <td class="text-center">
                        <?php if (!empty($book->book_owned_comments) && $book->promociones != 0 ): ?>
                            <span class="icons-comment" data-class-content="content-commentOwned-<?php echo $book->id?>">
                                <img src="/pages/oferta.png" style="width: 40px;">
                            </span>
                            <div class="comment-floating content-commentOwned-<?php echo $book->id?>" style="display: none;"><p class="text-left"><?php echo $book->book_owned_comments ?></p></div>
                            <br>
                        <?php endif ?>
                            <?php
                                $partee = $book->partee();
                                if ($partee):
                                  echo $partee->print_status($book->id,$book->start,$book->pax);
                                 endif;
                                 echo $book->getFianza();
                                ?>
                        <?php if ($book->send == 1): ?>
                            <button data-id="<?php echo $book->id ?>" class="btn btn-xs btn-default sendSecondPay" type="button" data-toggle="   tooltip" title="" data-original-title="Enviar recordatorio segundo pago" data-sended="1">
                               <i class="fa fa-paper-plane" aria-hidden="true"></i>
                            </button> 
                        <?php else: ?>
                            <button data-id="<?php echo $book->id ?>" class="btn btn-xs btn-primary sendSecondPay" type="button" data-toggle="tooltip" title="" data-original-title="Enviar recordatorio segundo pago" data-sended="0">
                               <i class="fa fa-paper-plane" aria-hidden="true"></i>
                            </button> 
                        <?php endif ?>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>
<?php endif; ?>