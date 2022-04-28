<div class="t-ff">
    <div class="table-responsive">
        <table class="table table-data-ff">
            <thead>

                <tr class="text-center bg-complete text-white">
                    <th class="th-bookings th-name">Cliente</th>
                    <th class="th-bookings">
                        @if($is_mobile)
                        <i class="fa fa-phone fa-2x"></i>
                        @else
                        Telefono
                        @endif
                    </th>
                    <th class="th-bookings th-2">Pax</th>
                    <th class="th-bookings">Apart</th>
                    <th class="th-bookings th-2"><i class="fa fa-moon-o"></i> </th>
                    <th class="th-bookings th-2">Qty Ord</th>
                    <th class="th-bookings th-4">IN</th>
                    <th class="th-bookings th-4">OUT</th>
                    <th class="th-bookings th-6">
                        TOTAL<br /><?php echo number_format($totals['totalPrice'], 0, ',', '.') ?> €</th>
                    <th class="th-bookings th-6">
                        FF<br /><?php echo number_format($totals['forfaits'], 0, ',', '.') ?> €</th>
                    <th class="th-bookings th-6">
                        ALQ<br /><?php echo number_format($totals['material'], 0, ',', '.') ?> €</th>
                    <th class="th-bookings th-6">
                        CLASS<br /><?php echo number_format($totals['class'], 0, ',', '.') ?> €</th>
                    <th class="th-bookings th-6">
                        OTROS<br /><?php echo number_format($totals['quick_order'], 0, ',', '.') ?> €</th>
                    <th class="th-bookings th-6">
                        COBRADO<br /><?php echo number_format($totals['totalPayment'], 0, ',', '.') ?> €</th>
                    <th class="th-bookings th-2">FF</th>
                    <th class="th-bookings th-2" title="Reservas hechas en Forfait Express">FFExpress</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($orders as $order) :
                    $book = $order['book'];
                    $style = $book->type_book == 0 ? 'style="color:red;"' : '';
                ?>
                    <tr>
                        <?php if ($book) : ?>
                            <td class="fix-col td-b1">
                                <div class="fix-col-data">
                                    <?php if ($book->agency != 0) : ?>
                                        <img src="/pages/<?php echo strtolower($book->getAgency($book->agency)) ?>.png" class="img-agency" />
                                    <?php endif ?>
                                    <div class="th-name" <?php echo $style; ?>>
                                        <?php if (isset($payment[$book->id])) : ?>
                                            <a class="update-book" data-id="<?php echo $book->id ?>" title="<?php echo $book->customer['name'] ?> - <?php echo $book->customer['email'] ?>" href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>" style="color: red"><?php echo $book->customer['name']  ?></a>
                                        <?php else : ?>
                                            <a class="update-book" data-id="<?php echo $book->id ?>" title="<?php echo $book->customer['name'] ?> - <?php echo $book->customer['email'] ?>" href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>"><?php echo $book->customer['name']  ?></a>
                                        <?php endif ?>
                                        <br />{{$book->getStatus($book->type_book)}}
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <?php if ($book->customer->phone != 0 && $book->customer->phone != "") : ?>
                                    <a href="tel:<?php echo $book->customer->phone ?>">
                                        @if($is_mobile)
                                        <i class="fa fa-phone fa-2x"></i>
                                        @else
                                        <?php echo $book->customer->phone ?>
                                        @endif
                                    </a>
                                <?php endif ?>
                                <?php if ($oRole != "limpieza" && (!empty($book->comment) || !empty($book->book_comments))) : ?>
                                    <div data-booking="<?php echo $book->id; ?>" class="showBookComm">
                                        <i class="far fa-comment-dots" style="color: #000;" aria-hidden="true"></i>
                                        <div class="BookComm tooltiptext"></div>
                                    </div>
                                <?php endif ?>
                            </td>
                            <td class="text-center">
                                <?php if ($book->real_pax > 6) : ?>
                                    <?php echo $book->real_pax ?><i class="fa fa-exclamation" aria-hidden="true" style="color: red"></i>
                                <?php else : ?>
                                    <?php echo $book->pax ?>
                                <?php endif ?>

                            </td>
                            <td class="text-center">
                                <?php foreach ($rooms as $room) : ?>
                                    <?php if ($room->id == $book->room_id) : ?>
                                        <?php echo substr($room->nameRoom . " - " . $room->name, 0, 15)  ?>
                                    <?php endif ?>
                                <?php endforeach ?>
                            </td>
                            <td class="text-center"><?php echo $book->nigths ?></td>
                            <td class="text-center sm-p-t-10 sm-p-b-10">
                                {{$order['qty']}}
                            </td>
                            <td class="td-date" data-order="{{$book->start}}">
                                <?php echo dateMin($book->start) ?>
                            </td>
                            <td class="td-date" data-order="{{$book->finish}}">
                                <?php echo dateMin($book->finish) ?>
                            </td>
                        <?php else : ?>
                            <td class="fix-col td-b1">
                                <div class="fix-col-data">

                                    <?php echo $order['name'] . '<br>' . $order['email']; ?>
                                </div>
                            </td>
                            <td class="text-center">
                                <?php if ($order['phone'] != 0 &&  $order['phone'] != "") : ?>
                                    <a href="tel:<?php echo $order['phone'] ?>">
                                        @if($is_mobile)
                                        <i class="fa fa-phone fa-2x"></i>
                                        @else
                                        <?php echo $order['phone'] ?>
                                        @endif
                                    </a>
                                <?php endif ?>
                            </td>
                            <td class="text-center"> - </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-default changeBook" data-id="<?php echo $order['id']; ?>">
                                    Asignar Reserva
                                </button>
                            </td>
                            <td class="text-center"> - </td>
                            <td class="text-center"> - </td>
                            <td class="text-center"> - </td>
                            <td class="text-center"> - </td>
                        <?php endif ?>

                        <td class="text-center"><b><?php echo number_format($order['totalPrice'], 0, ',', '.') ?> €</b></td>
                        <td class="text-center"><?php echo number_format($order['forfaits'], 0, ',', '.') ?> €</td>
                        <td class="text-center"><?php echo number_format($order['material'], 0, ',', '.') ?> €</td>
                        <td class="text-center"><?php echo number_format($order['class'], 0, ',', '.') ?> €</td>
                        <td class="text-center"><?php echo number_format($order['quick_order'], 0, ',', '.') ?> €</td>
                        <td class="text-center">
                            <div class="col-md-6">
                                <?php echo number_format($order['totalPayment'], 0, ',', '.') ?> €
                                <?php
                                $porcent = 0;
                                $color = 'style="color: red;"';
                                if ($order['totalPrice'] > 0) {
                                    if ($order['totalPayment'] > 0) {
                                        $porcent = ceil(($order['totalPayment'] / $order['totalPrice']) * 100);
                                        if ($porcent >= 100) {
                                            $color = 'style="color: #008000;"';
                                            $porcent = 100;
                                        }
                                    }
                                } else {
                                    $color = 'style="color: #008000;"';
                                    $porcent = 100;
                                }

                                //               text-danger
                                ?>
                                <p <?php echo $color; ?>><?php echo number_format($order['totalToPay'], 0, ',', '.');  ?>€</p>
                            </div>

                            <div class="col-md-6"><span <?php echo $color; ?>><?php echo $porcent; ?>%</span></div>

                        </td>
                        <td class="text-center">
                            <a data-booking="<?php echo $order['id']; ?>" class="openFF showFF_resume" title="Ir a Forfaits">
                                <?php
                                $ff_status = $order['status'];

                                if ($ff_status['icon']) {
                                    echo '<img src="' . $ff_status['icon'] . '" style="max-width:30px;" alt="' . $ff_status['name'] . '"/>';
                                } else {
                                    echo '<img src="/img/miramarski/ski_icon_status_transparent.png" style="max-width:30px;" alt="Externo"/>';
                                }

                                ?>
                                <div class="FF_resume tooltiptext"></div>
                            </a>

                        </td>
                        <td class="text-center">
                            <?php echo $order['ff_sent'] . '/' . $order['ff_item_total']; ?>
                        </td>
                    </tr>
                <?php
                endforeach;
                ?>
            </tbody>
        </table>
    </div>
</div>