<?php   use \Carbon\Carbon;  
        setlocale(LC_TIME, "ES"); 
        setlocale(LC_TIME, "es_ES"); 
$startWeek = Carbon::now()->startOfWeek();
$endWeek = Carbon::now()->endOfWeek(); 
$isMobile = $mobile->isMobile();
$uRole = getUsrRole();
$lstSafetyBox = \App\BookSafetyBox::$keys_name; 
?>
<div class="tab-pane" id="tabPagadas">
    <div class="table-responsive">
        <table class="table table-data table-striped"  data-type="confirmadas" style="margin-top: 0;">
            <thead>
                <tr class ="text-center bg-success text-white">
                    <th class="th-bookings th-name" style="min-width: 180px !important;">Cliente</th>
                    <th class="th-bookings"> 
                      @if($isMobile) <i class="fa fa-phone"></i> @else Telefono @endif
                    </th>
                    <th class="th-bookings th-2">Pax</th>
                    <th class="th-bookings" style="min-width:136px !important;">Apart</th>
                    <th class="th-bookings th-3">  <i class="fa fa-moon"></i> </th>
                    <th class="th-bookings th-3"> <i class="fa fa-clock"></i></th>
                    <th class="th-bookings th-4">IN</th>
                    <th class="th-bookings th-4">OUT</th>
                    <th class="th-bookings th-4"><i class="fa fa-lock"></i></th>
                    <th class="th-bookings th-3 hiddenOnlyRiad">FF</th>
                    <th class="th-bookings th-6" style="min-width:110px !important;">   Precio      </th>
                    <th class="th-bookings th-6">   a      </th>
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
                   <?php //$class .= ' '.ucwords($book->getStatus($book->type_book)) ?>
                    <tr class="{{$class}}" data-id="{{$book->id}}" >
                        <?php 
                            $dateStart = Carbon::createFromFormat('Y-m-d', $book->start);
                            $now = Carbon::now();
                        ?>
                         <td class="fix-col td-b1">
                          <div class="fix-col-data">
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
                                <img src="/pages/<?php echo strtolower($book->getAgency($book->agency)) ?>.png" class="img-agency"/>
                            <?php endif ?>
                            <?php if (isset($payment[$book->id])): ?>
                                <a class="update-book" data-id="<?php echo $book->id ?>"  title="<?php echo $book->customer['name'] ?> - <?php echo $book->customer['email'] ?>"  href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>" style="color: red"><?php echo $book->customer['name']  ?></a>
                            <?php else: ?>
                                <a class="update-book" data-id="<?php echo $book->id ?>"  title="<?php echo $book->customer['name'] ?> - <?php echo $book->customer['email'] ?>"  href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>" ><?php echo $book->customer['name']  ?></a>
                            <?php endif ?>
                          </div>
                        </td>
                        @if($isMobile)
                          <td class="text-center">
                            <?php if ($book->customer->phone != 0 && $book->customer->phone != ""): ?>
                              <a href="tel:<?php echo $book->customer->phone ?>">
                                <i class="fa fa-phone"></i>
                              </a>
                            <?php endif ?>
                          @else
                          <td class="text-center">
                            <?php if ($book->customer->phone != 0 && $book->customer->phone != ""): ?>
                              <a href="tel:<?php echo $book->customer->phone ?>"><?php echo $book->customer->phone ?></a>
                            <?php else: ?>
                              <input type="text" class="only-numbers customer-phone" data-id="<?php echo $book->customer->id ?>"/>
                            <?php endif ?>
                          @endif
                            <?php if ($uRole != "limpieza" && (!empty($book->comment) || !empty($book->book_comments))): ?>
                          
                            <div data-booking="<?php echo $book->id; ?>" class="showBookComm" >
                              <i class="far fa-comment-dots" style="color: #000;" aria-hidden="true"></i>
                              <div class="BookComm tooltiptext"></div>
                            </div>
                            <?php endif ?>
                          </td>
                        <td class ="text-center mobil-pad-x3" >
                            <?php if ($book->real_pax > 6): ?>
                                <?php echo $book->real_pax ?><i class="fa fa-exclamation" aria-hidden="true" style="color: red"></i>
                            <?php else: ?>
                                <?php echo $book->pax ?>
                            <?php endif ?>

                        </td>
                        <td class ="text-center">
                          <?php 
                          if ($book->room){
                            $room = $book->room;
                            ?>
                          <button type="button" class="btn changeRoom" data-c="{{$room->id}}">
                          <?php echo substr($room->nameRoom . " - " . $room->name, 0, 15);?>
                          </button>  
                            <?php
                          }
                          ?>
                        </td>
                        <td class ="text-center"><?php echo $book->nigths ?></td>
                        <td class="text-center sm-p-t-10 sm-p-b-10">

                            <select name="schedule" class="schedule <?php if(!$mobile->isMobile() ): ?>form-control minimal<?php endif; ?> <?php if ($book->schedule < 17 && $book->schedule > 0): ?>alerta-horarios<?php endif ?>" data-type="in" data-id="<?php echo $book->id ?>" <?php if ($uRole == "limpieza"): ?>disabled<?php
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
                        <td class="td-date mobil-pad-x3" data-order="{{$book->start}}">
                          <?php echo dateMin($book->start) ?>
                        </td>
                        <td class="td-date mobil-pad-x3" data-order="{{$book->finish}}">
                          <?php echo dateMin($book->finish) ?>
                        </td>
                        <?php 
                          $SafetyBox = $book->SafetyBox();
                          $hasSafetyBox = 0;
                          $safetyBoxClass = 'fa-lock';
                          $titSafetyBox = 'Asignar Buzón';
                          if ($SafetyBox && !$SafetyBox->deleted){
                            $hasSafetyBox = 1;
                            $safetyBoxClass = 'fa-unlock';
                            $titSafetyBox = isset($lstSafetyBox[$SafetyBox->key]) ? $lstSafetyBox[$SafetyBox->key] : '';
                          }
                          ?>
                        <td class="td-date mobil-pad-x3" data-order="{{$hasSafetyBox}}">
                          <i class="fa {{$safetyBoxClass}} openSafetyBox" data-id="{{$book->id}}" title="{{$titSafetyBox}}"></i>
                        </td>
                        <td class="text-center hiddenOnlyRiad ">
                          <a data-booking="<?php echo $book->id; ?>" class="openFF showFF_resume" >
                            <?php
                            $ff_status = $book->get_ff_status();
                            if ($ff_status['icon']) {
                              echo '<img src="' . $ff_status['icon'] . '" style="max-width:30px;" alt="' . $ff_status['name'] . '"/>';
                            }
                            ?>
                            <div class="FF_resume tooltiptext"></div>
                            </a>
                        </td>

                        <td>
                            <?php 
                            if ($uRole != "limpieza"):
                              echo $book->showPricePlanning($payment);
                            endif;
                            ?>
                        </td>

                        <td class="text-center sm-p-t-10 sm-p-b-10">
                            <?php if ($book->send == 1): ?>
                          <button data-id="<?php echo $book->id ?>" class="btn btn-xs btn-default sendSecondPay" type="button" data-toggle="tooltip" title="" data-original-title="Enviar recordatorio segundo pago" data-sended="1" style="margin-top: -11px;">
                                    <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                </button> 
                            <?php else: ?>
                                <button data-id="<?php echo $book->id ?>" class="btn btn-xs btn-primary sendSecondPay" type="button" data-toggle="tooltip" title="" data-original-title="Enviar recordatorio segundo pago" data-sended="0" style="margin-top: -11px;">
                                    <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                </button> 
                            <?php endif ?>
                        </td>
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
