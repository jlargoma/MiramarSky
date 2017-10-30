<?php   
    use \Carbon\Carbon;  
    use App\Classes\Mobile;
    setlocale(LC_TIME, "ES"); 
    setlocale(LC_TIME, "es_ES");

    if (!isset($mobile)) {

        $mobile = new Mobile();
     } 

?>
<?php if (count($notifications) > 0): ?>
    <?php if (!$mobile->isMobile() ): ?>
        <table class="table table-condensed" style="margin-top: 0;">
            <tbody>
            <?php foreach ($notifications as $key => $notify): ?>
                <?php if ($notify->book->type_book != 3 || $notify->book->type_book != 5 || $notify->book->type_book != 6 ): ?>
                <tr>
                    <td class="text-center" style="width: 30px; padding: 5px 0!important">
                        <?php if ($notify->book->agency != 0): ?>
                            <img src="/pages/booking.png" style="width: 20px;"/>
                        <?php else: ?>

                        <?php endif ?>
                    </td>
                    <td class="text-center" style="padding: 5px 0!important">
                        <?php echo $notify->book->customer->name; ?></td>
                    <td class="text-center" style="padding: 5px 0!important">
                        <?php
                            $start = Carbon::createFromFormat('Y-m-d',$notify->book->start);
                            echo $start->formatLocalized('%d %b');
                        ?> - 
                        <?php
                            $finish = Carbon::createFromFormat('Y-m-d',$notify->book->finish);
                            echo $finish->formatLocalized('%d %b');
                        ?>
                    </td>
                    <td class="text-center" style="padding: 5px 0!important">
                        <b><?php echo $notify->book->room->nameRoom;?> </b>                                               
                    </td>
                    <td class="text-center" style="padding: 5px 0!important">
                        <b><?php echo $notify->book->getStatus($notify->book->type_book)?> </b>                                               
                    </td>
                    <td class="text-center" style="padding: 5px 0!important">
                        <b><?php echo number_format($notify->book->total_price,2, ',', ".") ?> €</b>                                               
                    </td>
                    <td class="text-center" style="padding: 5px 0!important">
                        <button class="btn btn-default btn-delete-alert" data-link="/admin/delete/nofify/<?php echo $notify->id ?>"><i class="fa fa-trash-o"></i></button>
                    </td>
                </tr>
                <?php endif ?>
            <?php endforeach ?>
            </tbody>
        </table>
    <?php else: ?>
        <table class="table" style="margin-top: 0;">
            <tbody>
            <?php foreach ($notifications as $key => $notify): ?>
                <?php if ($notify->book->type_book != 3 || $notify->book->type_book != 5 || $notify->book->type_book != 6 ): ?>
                <tr>
                    <td class="text-center" style="padding: 0 5px!important">
                        <?php echo $notify->book->customer->name; ?></td>
                    <td class="text-center" style="padding: 0 5px!important">
                        <?php
                            $start = Carbon::createFromFormat('Y-m-d',$notify->book->start);
                            echo $start->formatLocalized('%d %b');
                        ?> 
                        <?php
                            $finish = Carbon::createFromFormat('Y-m-d',$notify->book->finish);
                            echo $finish->formatLocalized('%d %b');
                        ?>
                    </td>
                    <td class="text-center" style="padding: 0 5px!important">
                        <b><?php echo $notify->book->room->name?> </b>                                               
                    </td>
                    <td class="text-center" style="padding: 0 5px!important">
                        <b><?php echo substr($notify->book->getStatus($notify->book->type_book), 0, -5)?> </b>                                               
                    </td>
                    <td class="text-center" style="padding: 0 5px!important">
                        <button class="btn btn-default btn-delete-alert" data-link="/admin/delete/nofify/<?php echo $notify->id ?>"><i class="fa fa-trash-o"></i></button>
                    </td>
                </tr>
                <?php endif ?>
            <?php endforeach ?>
            </tbody>
        </table>
    <?php endif ?>
<?php else: ?>
    <h2 class="text-center"> No hay alertas</h2>
<?php endif ?>
