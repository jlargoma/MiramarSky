<?php   use \Carbon\Carbon;  
        setlocale(LC_TIME, "ES"); 
        setlocale(LC_TIME, "es_ES");  
        $total = 0;
?>
<div class="row">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="position: absolute; top: 0px; right: 10px; z-index: 100">
        <i class="pg-close fs-20" style="color: #000!important;"></i>
    </button>
</div>
<div class="col-md-12 not-padding content-last-books">
    <div class="alert alert-info fade in alert-dismissable" style="background-color: #daeffd!important;">
        <!-- <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a> -->
        <!-- <strong>Info!</strong> This alert box indicates a neutral informative change or action. -->
        <h4 class="text-center">Últimas Pagadas 
          <span style="color: #000;font-weight: 600;"><span id="totalPayment"></span>€</span>
        </h4>
        <div class="table-responsive">
        <table class="table" >
            <tbody>
                <?php foreach ($books as $key => $book): ?>
                    <tr>
                        <td class ="text-left static" style="width: 130px;color: black;overflow-x: scroll;    padding: 9px !important; ">  
                           <?php if ( $book->agency != 0): ?>
                              <img src="/pages/<?php echo strtolower($book->getAgency($book->agency)) ?>.png" class="img-agency" />
                            <?php endif ?>
                            <a class="update-book" data-id="<?php echo $book->id ?>"  title="Editar Reserva"  href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>">
                                <?php echo $book->customer->name ?>
                            </a> 
                            
                        </td>
                        <td class="text-center first-col" style="padding-left: 130px!important">   
                            <?php echo substr($book->room->nameRoom,0,5) ?>       
                        </th>
                        <td class="text-center" style="color: black;padding: 5px 10px!important">   
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
                        <td class="text-center" style="color: black;padding: 5px!important;">
                             <b><?php echo  number_format($book->total_price,0,'','.') ?>€</b>
                        </td>
                        <td class="text-center" style="color: black;">  

                            <?php $payments = \App\Payments::where('book_id', $book->id)->get(); ?>
                            <?php $paymentBook = 0; ?>
                            <?php $fromStripe = false; ?>
                            <?php if ( count($payments) > 0): ?>
                                <?php foreach ($payments as $key => $payment): ?>
                                    <?php $paymentBook += $payment->import; ?>
                                    <?php if (preg_match('/stripe/i', $payment->comment)): ?>
                                        <?php $fromStripe = true; ?>
                                    <?php endif ?>
                                    
                                <?php endforeach ?>
                            <?php endif ?>
                            <?php echo  $paymentBook." €";
                            $total += $paymentBook;?> <br>
                            <b><?php echo  ($book->total_price>0) ? round(($paymentBook/$book->total_price)*100): 0;?>%</b>
                        </td>
                        <td class="text-center">
                            <?php if ($fromStripe): ?>
                                <a target="_blank" href="https://dashboard.stripe.com/payments"><img src="/img/stripe-icon.jpg" style="width: 20px;"></a>
                            <?php endif ?> 
                        </td>
                    </tr>
                    <?php if($key == 4) break; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>
</div> 
<script>

jQuery('#totalPayment').text(<?php echo number_format($total,0,'','.') ;?>);
</script>