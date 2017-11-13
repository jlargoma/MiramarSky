<?php   use \Carbon\Carbon;  
        setlocale(LC_TIME, "ES"); 
        setlocale(LC_TIME, "es_ES"); 
?>
<?php if (!$mobile->isMobile() ): ?>
<div class="col-md-12 not-padding content-last-books" style="display:none;">
    <div class="alert alert-info fade in alert-dismissable" style="background-color: #daeffd!important;">
        <!-- <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a> -->
        <!-- <strong>Info!</strong> This alert box indicates a neutral informative change or action. -->
        <h4 class="text-center">Últimas confirmadas</h4>
        <table class="table table-condensed" style="margin-top: 0;">
            <tbody>
                <?php foreach ($arrayBooks["pagadas"] as $key => $book): ?>
                    <tr>
                         <td class="text-center" style="width: 30px; padding: 5px 0!important">
                            <?php if ($book->agency != 0): ?>
                                <img src="/pages/booking.png" style="width: 20px;"/>
                            <?php else: ?>

                            <?php endif ?>
                        </td>
                        <td class ="text-center" style="color: black;padding: 5px!important;">  
                            <a class="update-book" data-id="<?php echo $book->id ?>"  title="Editar Reserva"  href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>">
                                <?php echo $book->customer->name ?>
                            </a> 
                            
                        </td>
                        <td class="text-center" style="color: black;">   
                            <?php echo $book->room->nameRoom ?> - <?php echo substr($book->room->name,0,5) ?>  
                        </th>
                        <td class="text-center" style="color: black;">   
                            <b>
                                <?php
                                    $start = Carbon::createFromFormat('Y-m-d',$book->start);
                                    echo $start->formatLocalized('%d %b');
                                ?>        
                            </b> - 
                            <b>
                                <?php
                                    $finish = Carbon::createFromFormat('Y-m-d',$book->finish);
                                    echo $finish->formatLocalized('%d %b');
                                ?>        
                            </b>           
                        </td>
                        <td class="text-center" style="width: 17%!important; color: black;padding: 5px!important;">
                            
                            <?php if (isset($payment[$book->id])): ?>
                                <b><?php echo $book->total_price ?>€</b>
                            <?php else: ?>
                                -----
                            <?php endif ?>
                        </td>
                         <td class="text-center" style="color: black;">  
                            <?php if (isset($payment[$book->id])): ?>
                                <?php echo  $payment[$book->id]." €" ?>
                            <?php else: ?>
                                -----&nbsp;&nbsp;
                            <?php endif ?> 
                            <?php $paymentBook = \App\Payments::where('book_id', $book->id)->get(); ?>
                            <?php 
                                $fromStripe = false;
                                foreach ($paymentBook as $pay) {
                                    if (preg_match('/stripe/i', $pay->comment)) {
                                        $fromStripe = true;
                                    }
                                }
                            ?>
                            <?php if ($fromStripe): ?>
                                <a target="_blank" href="https://dashboard.stripe.com/payments"><img src="/img/stripe-icon.jpg" style="width: 20px;"></a>
                            <?php else: ?>
                                &nbsp;
                            <?php endif ?>      
                        </th>
                    </tr>
                    <?php if($key == 4) break; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div> 

<?php else: ?>

    <div class="col-md-12 not-padding content-last-books" style="display:none;">
        <div class="alert alert-info fade in alert-dismissable" style="background-color: #daeffd!important;">
            <!-- <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a> -->
            <!-- <strong>Info!</strong> This alert box indicates a neutral informative change or action. -->
            <h4 class="text-center">Últimas confirmadas</h4>
            <table class="table table-condensed" style="margin-top: 0;">
                <tbody>
                    <?php foreach ($arrayBooks["pagadas"] as $key => $book): ?>
                        <tr>
                            <td class ="text-center" style="color: black;padding: 5px!important;">  
                                <a class="update-book" data-id="<?php echo $book->id ?>"  title="Editar Reserva"  href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>">
                                    <?php echo $book->customer->name ?>
                                </a> 
                                
                            </td>
                            <td class="text-center" style="color: black;">   
                                <?php echo substr($book->room->name,0,5) ?>       
                            </th>
                            <td class="text-center" style="color: black;">   
                                <b>
                                    <?php
                                        $start = Carbon::createFromFormat('Y-m-d',$book->start);
                                        echo $start->formatLocalized('%d %b');
                                    ?>        
                                </b> - 
                                <b>
                                    <?php
                                        $finish = Carbon::createFromFormat('Y-m-d',$book->finish);
                                        echo $finish->formatLocalized('%d %b');
                                    ?>        
                                </b>           
                            </td>
                            <td class="text-center" style="width: 17%!important; color: black;padding: 5px!important;">
                                
                                <?php if (isset($payment[$book->id])): ?>
                                    <?php echo  $payment[$book->id]." €" ?>
                                <?php else: ?>
                                    -----
                                <?php endif ?>
                            </td>
                             <td class="text-center" style="color: black;">  
                                <?php $paymentBook = \App\Payments::where('book_id', $book->id)->get(); ?>
                                <?php 
                                    $fromStripe = false;
                                    foreach ($paymentBook as $pay) {
                                        if (preg_match('/stripe/i', $pay->comment)) {
                                            $fromStripe = true;
                                        }
                                    }
                                ?>
                                <?php if ($fromStripe): ?>
                                    <a target="_blank" href="https://dashboard.stripe.com/payments"><img src="/img/stripe-icon.jpg" style="width: 20px;"></a>
                                <?php else: ?>
                                    &nbsp;
                                <?php endif ?>      
                            </th>
                        </tr>
                        <?php if($key == 4) break; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div> 
<?php endif ?>