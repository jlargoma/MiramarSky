<?php   use \Carbon\Carbon;  
        setlocale(LC_TIME, "ES"); 
        setlocale(LC_TIME, "es_ES"); 
?>
<?php $startWeek = Carbon::now()->startOfWeek(); ?>
<?php $endWeek = Carbon::now()->endOfWeek(); ?>
<?php if(!$mobile->isMobile() ): ?>
    <div class="tab-pane" id="tabPagadas">
        <div class="row">
            <table class="table <?php if (Auth::user()->role != "limpieza"): ?>table-condensed<?php endif ?> table-striped table-data"  data-type="confirmadas" style="margin-top: 0;">
                <thead>
                    <tr>
                        <th class ="text-center bg-success text-white" style="width: 4%!important">&nbsp;</th> 
                        <th class ="text-center bg-success text-white" style="width: 12%!important">   Cliente     </th>
                        <th class ="text-center bg-success text-white" style="width: 10%!important">   Telefono     </th>
                        <th class ="text-center bg-success text-white" style="width: 5%!important">   Pax         </th>
                        <th class ="text-center bg-success text-white" style="width: 12%!important">   Apart       </th>
                        <th class ="text-center bg-success text-white" style="width: 9%!important">  <i class="fa fa-moon-o"></i> </th>
                         <th class="bg-success text-white text-center" style="width: 10%!important">
                            <i class="fa fa-clock-o" aria-hidden="true"></i> Hora
                        </th>
                        <th class ="text-center bg-success text-white" style="width: 7%!important">   IN     </th>
                        <th class ="text-center bg-success text-white" style="width: 7%!important">   OUT      </th>
                        
                        <th class="text-center bg-success text-white" style="width: 5%!important">FF</th>
                       
                        <th class ="text-center bg-success text-white" style="width: 12%!important">   Precio      </th>
                        
                        <th class ="text-center bg-success text-white" style="width: 4%!important">&nbsp;</th>
                        <?php if (Auth::user()->role != "limpieza"): ?>
                            <th class ="text-center bg-success text-white" style="width: 4%!important">   a      </th>
                        <?php endif ?>
                        
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
                                    <?php $percent = 100 / ( $book->total_price / $payment[$book->id] ); ?>
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
                                    <img style="width: 20px;margin: 0 auto;" src="/pages/<?php echo strtolower($book->getAgency($book->agency)) ?>.png" align="center" />
                                <?php endif ?>
                            </td>
                            <td class="text-center" style="padding: 10px 15px!important">
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
                                <select class="room form-control minimal" data-id="<?php echo $book->id ?>" <?php if (Auth::user()->role == "limpieza"): ?>disabled<?php endif ?>>
                            
                                    <?php foreach ($rooms as $room): ?>
                                        <?php if ($room->id == $book->room_id): ?>
                                            <option selected value="<?php echo $book->room_id ?>" data-id="<?php echo $room->name ?>">
                                               <?php echo substr($room->nameRoom." - ".$room->name, 0, 15)  ?>
                                            </option>
                                        <?php else:?>
                                            <option value="<?php echo $room->id ?>"><?php echo substr($room->nameRoom." - ".$room->name, 0, 15)  ?></option>
                                        <?php endif ?>
                                    <?php endforeach ?>

                                </select>
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
                            
                            <td class="text-center">
                                <a href="/admin/reservas/ff_status_popup/<?php echo $book->id; ?>" onclick="window.open(this.href, 'Reserva - FF','left=400,top=20,width=1200,height=900,toolbar=0,resizable=0'); return false;" >
                                    <?php
                                        if($book->ff_status == 0){
                                            echo '<img src="'.asset('/img/miramarski/ski_icon_status_transparent.png').'" style="max-width:30px;"/>';
                                        }elseif($book->ff_status == 1){
                                            echo '<img src="'.asset('/img/miramarski/ski_icon_status_grey.png').'" style="max-width:30px;"/>';
                                        }elseif($book->ff_status == 2){
                                            echo '<img src="'.asset('/img/miramarski/ski_icon_status_red.png').'" style="max-width:30px;"/>';
                                        }elseif($book->ff_status == 3){
                                            echo '<img src="'.asset('/img/miramarski/ski_icon_status_green.png').'" style="max-width:30px;"/>';
                                        }
                                    ?>
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
	                                        <?php $total = number_format(100/($book->total_price/$payment[$book->id]),0);?>
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
                            <td class="text-center">
                                <?php if (!empty($book->book_owned_comments) && $book->promociones != 0 ): ?>
                                    <span class="icons-comment" data-class-content="content-commentOwned-<?php echo $book->id?>">
                                        <img src="/pages/oferta.png" style="width: 40px;">
                                    </span>
                                    <div class="comment-floating content-commentOwned-<?php echo $book->id?>" style="display: none;"><p class="text-left"><?php echo $book->book_owned_comments ?></p></div>
                                    
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
                                
                            </td>
                            <?php endif ?>
                            
                            
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
            <th class="bg-success text-white text-center" style="min-width:50px">FF</th>
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
                            <?php $percent = 100 / ( $book->total_price / $payment[$book->id] ); ?>
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
                    
                    <td class="text-center">
                        <a href="/admin/reservas/ff_status_popup/<?php echo $book->id; ?>" onclick="window.open(this.href, 'Reserva - FF','left=400,top=20,width=1200,height=900,toolbar=0,resizable=0'); return false;" >
                            <?php
                                if($book->ff_status == 0){
                                    echo '<img src="'.asset('/img/miramarski/ski_icon_status_transparent.png').'" style="max-width:30px;"/>';
                                }elseif($book->ff_status == 1){
                                    echo '<img src="'.asset('/img/miramarski/ski_icon_status_grey.png').'" style="max-width:30px;"/>';
                                }elseif($book->ff_status == 2){
                                    echo '<img src="'.asset('/img/miramarski/ski_icon_status_red.png').'" style="max-width:30px;"/>';
                                }elseif($book->ff_status == 3){
                                    echo '<img src="'.asset('/img/miramarski/ski_icon_status_green.png').'" style="max-width:30px;"/>';
                                }
                            ?>
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