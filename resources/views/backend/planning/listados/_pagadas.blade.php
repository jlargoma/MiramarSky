<?php use \Carbon\Carbon;  setlocale(LC_TIME, "ES"); setlocale(LC_TIME, "es_ES"); ?>
<?php
$isMobile = $mobile->isMobile();
$classTH = 'text-center text-white ';
if($type == 'confirmadas'):
  $classTH .= ' Pagada-la-señal';
else:
  $classTH .= ' blocked-ical';
endif
?>
<div class="tab-pane" id="tabPagadas">
    <div class="table-responsive">
        <table class="table table-data table-striped"  data-type="confirmadas">
            <thead>
                <tr>
                  <th class ="{{$classTH}}" style="min-width: 130px;">   Cliente     </th>
                  <th class ="{{$classTH}}" style="width: 10%">
                    @if($isMobile) <i class="fa fa-phone"></i> @else Telefono @endif
                  </th>
                    <th class ="{{$classTH}}" style="width: 25px">   Pax         </th>
                    <th class ="{{$classTH}}" style="width: 30px"> </th>
                    <th class ="{{$classTH}}" style="width: 100px">   Apart       </th>
                    <th class ="{{$classTH}}" style="width: 25px">  <i class="fa fa-moon-o"></i> </th>
                    <th class ="{{$classTH}}" style="width: 80px">   IN     </th>
                    <th class ="{{$classTH}}" style="width: 80px">   OUT      </th>
                    <th class ="{{$classTH}}" style="min-width: 120px;">   Precio      </th>
                    <th class ="{{$classTH}}" style="width:25px">   &nbsp;      </th>
                    @if(Auth::user()->role != "agente" )
                    <th class ="{{$classTH}}" style="width: 10%">   Estado      </th>
                    @endif
                </tr>
            </thead>
            <tbody>
                <?php foreach ($books as $book): ?>
                    <tr>
                      <td class="text-left" style="padding: 10px 5px!important">
                            <?php if ($book->agency != 0): ?>
                                <img style="width: 18px;margin: 0 auto;" src="/pages/<?php echo strtolower($book->getAgency($book->agency)) ?>.png" align="center" />
                            <?php endif;?>
                            @if($book->is_fastpayment || $book->type_book == 99 )
                              <img style="width: 18px;margin: 0 auto;" src="/pages/fastpayment.png" align="center"/>
                            @endif
                        
                                    <?php if (isset($payment[$book->id])): ?>
                            <a class="update-book" data-id="<?php echo $book->id ?>"
                               title="<?php echo $book->customer['name'] ?> - <?php echo $book->customer['email'] ?>"
                                href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>"
                               style="color: red"><?php echo $book->customer['name']  ?></a>
                                    <?php else: ?>
                            <a class="update-book" data-id="<?php echo $book->id ?>"
                               title="<?php echo $book->customer['name'] ?> - <?php echo $book->customer['email'] ?>"
                              href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>"
                            >
                                            <?php echo $book->customer['name']  ?></a>
                                    <?php endif ?>
                                   
                        </td>

                        @if($isMobile)
                          <td class="text-center ">
                            <?php if ($book->customer->phone != 0 && $book->customer->phone != ""): ?>
                              <a href="tel:<?php echo $book->customer->phone ?>">
                                <i class="fa fa-phone"></i>
                              </a>
                            <?php endif ?>
                          </td>
                          @else
                          <td class="text-center">
                            <?php if ($book->customer->phone != 0 && $book->customer->phone != ""): ?>
                              <a href="tel:<?php echo $book->customer->phone ?>"><?php echo $book->customer->phone ?></a>
                            <?php else: ?>
                              <input type="text" class="only-numbers customer-phone" data-id="<?php echo $book->customer->id ?>"/>
                            <?php endif ?>
                          </td>
                          @endif
                        <td class ="text-center" >
                            <?php if ($book->real_pax > 6): ?>
                                <?php echo $book->real_pax ?><i class="fa fa-exclamation" aria-hidden="true" style="color: red"></i>
                            <?php else: ?>
                                <?php echo $book->pax ?>
                            <?php endif ?>

                        </td>
                        <td class ="text-center" style="position:relative">
                                    <?php if ($book->hasSendPicture()): ?>
                            <button class="font-w800 btn btn-xs getImagesCustomer" type="button" data-toggle="modal" data-target="#modalRoomImages" style="border: none; background-color:transparent!important; color: lightgray; padding: 0;"
                                    data-id="<?php echo $book->room->id ?>"
                                    data-idCustomer="<?php echo $book->id ?>"
                                    onclick="return confirm('¿Quieres reenviar las imagenes');">
                                <i class="fa fa-eye"></i>
                            </button>
                                    <?php else: ?>
                            <button class="font-w800 btn btn-xs getImagesCustomer" type="button" data-toggle="modal" data-target="#modalRoomImages" style="border: none; background-color: transparent!important; color:black; padding: 0;"
                                    data-id="<?php echo $book->room->id ?>"
                                    data-idCustomer="<?php echo $book->id ?>"
                            >
                                <i class="fa fa-eye"></i>
                            </button>
                                    <?php endif ?>

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
                        </td>
                        <td class ="text-center">
                          @include('backend.planning.listados._select-rooms', ['rooms'=>$rooms,'bookID' => $book->id,'select'=>$book->room_id])
                        </td>
                        <td class ="text-center"><?php echo $book->nigths ?></td>
                            <?php $start = Carbon::createFromFormat('Y-m-d',$book->start); ?>
                        <td class ="text-center" data-order="<?php echo $start->copy()->format('Y-m-d')?>"  style="width:
            20%!important">
                                    <?php echo $start->formatLocalized('%d %b'); ?>
                        </td>
                            <?php $finish = Carbon::createFromFormat('Y-m-d',$book->finish);?>
                        <td class ="text-center" data-order="<?php echo $finish->copy()->format('Y-m-d')?>"  style="width: 20%!important">
                                    <?php echo $finish->formatLocalized('%d %b'); ?>
                        </td>

                        <td class="text-center">
                                <div class="col-xs-6">
                                    <?php echo round($book->total_price) . "€" ?><br>
                                    <?php if (isset($payment[$book->id])): ?>
                                        <?php echo "<p style='color:red'>" . $payment[$book->id] . "</p>" ?>
                                    <?php else: ?>
                                    <?php endif ?>
                                </div>

                                <?php if (isset($payment[$book->id])): ?>
                                    <?php if ($payment[$book->id] == 0): ?>
                                    <div class="col-xs-6 bg-success m-t-10">
                                        <b style="color: red;font-weight: bold">0%</b>
                                    </div>
                                    <?php else:?>
                                    <div class="col-xs-6">
                                        <p class="text-white m-t-10">
                                          <b  style="color: red;font-weight: bold">
                                            <?php 
                                            if (isset($payment[$book->id]) && $payment[$book->id]>0 && $book->total_price>0)
                                              echo number_format(100 / ($book->total_price / $payment[$book->id]), 0) . '%';
                                            else echo '0%';
                                            ?>
                                          </b>
                                        </p>
                                    </div>

                                    <?php endif; ?>
                                <?php else: ?>
                                    <div class="col-xs-5 bg-success">
                                        <b style="color: red;font-weight: bold">0%</b>
                                    </div>
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
                        @if(Auth::user()->role != "agente" )
                        <td class="text-center">
                            <select class="status form-control minimal" data-id="<?php echo $book->id ?>" style="width: 95%">
                                            <?php

                                    $status = [ 1 => 1, 2 => 2 ];
                                                if (!in_array($book->type_book, $status))
                                                        $status[] = $book->type_book;

                                            ?>
                                            <?php if ( Auth::user()->role != "agente" && in_array($book->type_book, $status))
                                            : ?>
                                    <?php for ($i = 1; $i <= 12; $i++): ?>
                                        <?php if ($i == 5 && $book->customer->email == ""): ?><?php else: ?>
                                        <option
                                            <?php echo $i == ($book->type_book) ? "selected" : ""; ?> <?php echo ($i == 1 || $i == 5) ? "style='font-weight:bold'" : "" ?> value="<?php echo $i ?>"
                                            data-id="<?php echo
                                            $book->id ?>">
                                            <?php echo $book->getStatus($i) ?>
                                        </option>
                                        <?php endif ?>
                                    <?php endfor; ?>
                                            <?php else: ?>
                                    <?php for ($i = 1; $i <= count($status); $i++): ?>
                                        <?php if ($i == 5 && $book->customer->email == ""): ?> <?php else: ?>
                                        <option <?php echo $status[$i] == ($book->type_book) ? "selected" : ""; ?> <?php
                                                echo ($status[$i] == 1 || $status[$i] == 5) ? "style='font-weight:bold'" : "" ?> value="<?php echo $status[$i] ?>"
                                                data-id="<?php echo
                                                $book->id ?>">
                                            <?php echo $book->getStatus($status[$i]) ?>
                                        </option>
                                        <?php endif ?>
                                    <?php endfor; ?>
                                            <?php endif ?>
                            </select>
                        </td>
                        @endif
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>
