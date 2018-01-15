<?php 
    use \Carbon\Carbon; 
    use \App\Classes\Mobile; 
    setlocale(LC_TIME, "ES");
    setlocale(LC_TIME, "es_ES");
    $mobile = new Mobile();
?>
<?php if ( !$mobile->isMobile() ): ?>
    <style type="text/css">
        @media screen and (min-width: 998px){
            .summary-text{ width: 22%; }
        }
    </style>
    <div class="col-md-12 pull-right push-20">
        <h2 class="text-left">
            Indicadores de ocupación
        </h2>
        @include('backend.sales._tableSummaryBoxes', ['totales' => $totales, 'books' => $books, 'data' => $data])
    </div>
    <div class="col-xs-12">
        <div class="col-md-3 col-xs-12 summary-text">
            <h2 class="text-center font-w800">
                Resumen liquidación
            </h2>
        </div>
        <div class="col-md-9 col-xs-12" style="width: 78%; padding-right: 0;">
            <table class="table">
                <tr>
                    <th class ="text-center bg-complete text-white" style="width: 5%; padding: 5px 5px 0">PVP</th>
                    <th class ="text-center bg-complete text-white" style="width: 5%; padding: 5px 5px 0">Banc. Jorg</th>
                    <th class ="text-center bg-complete text-white" style="width: 5%; padding: 5px 5px 0">Banc. Jaime</th>
                    <th class ="text-center bg-complete text-white" style="width: 5%; padding: 5px 5px 0">Cash Jorge</th>
                    <th class ="text-center bg-complete text-white" style="width: 5%; padding: 5px 5px 0">Cash Jaime</th>
                    <th class ="text-center bg-complete text-white" style="width: 4%; padding: 5px 5px 0">Pend</th>
                    <th class ="text-center bg-complete text-white" style="width: 5%; padding: 5px 5px 0">Ing Neto</th>
                    <th class ="text-center bg-complete text-white" style="width: 5%; padding: 5px 5px 0">%Benef</th>
                    <th class ="text-center bg-complete text-white" style="width: 4%; padding: 5px 5px 0">Cost. Total</th>
                    <th class ="text-center bg-complete text-white" style="width: 5%; padding: 5px 5px 0">Cost. Apto</th>
                    <th class ="text-center bg-complete text-white" style="width: 5%; padding: 5px 5px 0">Park</th>
                    <th class ="text-center bg-complete text-white" style="width: 5%; padding: 5px 5px 0">Sup. Lujo</th>
                    <th class ="text-center bg-complete text-white" style="width: 5%; padding: 5px 5px 0">Limp</th>
                    <th class ="text-center bg-complete text-white" style="width: 5%; padding: 5px 5px 0">Agencia</th>
                    <th class ="text-center bg-complete text-white" style="width: 3%; padding: 5px 5px 0">Extras</th>
                    <th class ="text-center bg-complete text-white" style="width: 2%; padding: 5px 5px 0">Stripe</th>
                    <th class ="text-center bg-complete text-white" style="width: 2%; padding: 5px 5px 0">Benef<br>Jorg</th>
                    <th class ="text-center bg-complete text-white" style="width: 2%; padding: 5px 5px 0">Benef<br>Jaim</th>

                <tr>
                    <td class="text-center coste" style="border-left:1px solid black;">
                        <b><?php echo number_format($totales["total"],0,',','.') ?> €</b>       
                    </td>
                    <td class ="text-center coste" style="border-left:1px solid black;">
                        <?php if ($totales["bancoJorge"] == 0): ?>
                            ----
                        <?php else: ?>
                            <?php echo number_format($totales["bancoJorge"],0,',','.') ?> €
                        <?php endif ?>     
                    </td>
                    <td class ="text-center coste" style="border-left:1px solid black;">
                        <?php if ($totales["bancoJaime"] == 0): ?>
                            ----
                         <?php else: ?>
                            <?php echo number_format($totales["bancoJaime"],0,',','.') ?> €
                         <?php endif ?> 
                    </td>
                    <td class ="text-center coste" style="border-left:1px solid black;">
                        <?php if ($totales["jorge"] == 0): ?>
                           ---- 
                        <?php else: ?>
                            <?php echo number_format($totales["jorge"],0,',','.') ?> €    
                        <?php endif ?>
                        
                    </td>
                    <td class ="text-center coste" style="border-left:1px solid black;">
                        <?php if ($totales["jaime"] == 0): ?>
                            ----
                        <?php else: ?>
                            <?php echo number_format($totales["jaime"],0,',','.') ?> €    
                        <?php endif ?>
                        
                    </td>
                    <td class ="text-center coste" style="border-left:1px solid black;">
                        <?php if ($totales["pendiente"] == 0): ?>
                            ----
                        <?php else: ?>
                            <span class="text-danger"><b><?php echo number_format($totales["pendiente"],0,',','.') ?> €</b></span>
                        <?php endif ?>
                        
                    </td>
                    <td class ="text-center beneficio" style="border-left:1px solid black;">
                        <b><?php echo number_format($totales["beneficio"],0,',','.') ?>€</b>
                    </td>
                    <td class ="text-center beneficio" style="border-left:1px solid black;">
                        <?php echo number_format( ( $totales["beneficio"] / $totales["total"] )* 100 ,2 ,',','.') ?>%
                    </td>
                    <td class ="text-center coste" style="border-left:1px solid black;">
                        <b><?php echo number_format(($totales["coste"] + $totales["stripe"]),0,',','.') ?>€</b>
                    </td>
                    <td class ="text-center coste" style="border-left:1px solid black;">
                        <?php echo number_format($totales["costeApto"],0,',','.') ?>€
                    </td>
                    <td class ="text-center coste" style="border-left:1px solid black;">
                        <?php echo number_format($totales["costePark"],0,',','.') ?>€
                    </td>
                    <td class ="text-center coste" style="border-left:1px solid black;">
                        <?php echo number_format($totales["costeLujo"],0,',','.') ?>€
                    </td>
                    <td class ="text-center coste" style="border-left:1px solid black;">
                        <?php echo number_format($totales["costeLimp"],0,',','.') ?>€
                    </td>
                    <td class ="text-center coste" style="border-left:1px solid black;">
                        <?php echo number_format($totales["costeAgencia"],0,',','.') ?>€
                    </td>
                    <td class ="text-center coste" style="border-left:1px solid black;">
                        <?php if ($totales["obs"] > 0): ?>
                            <?php echo number_format($totales["obs"],0,',','.') ?>€
                        <?php else: ?>
                            --
                        <?php endif ?>
                       
                    </td>
                    <td class ="text-center coste" style="border-left:1px solid black;">
                        <?php echo number_format($totales["stripe"],0,',','.') ?>€
                    </td>
                    <td class ="text-center coste" style="border-left:1px solid black;">
                        <?php echo number_format($totales["benJorge"],0,',','.') ?>€
                    </td>
                    <td class ="text-center coste" style="border-left:1px solid black;">
                        <?php echo number_format($totales["benJaime"],0,',','.') ?>€
                    </td>
                </tr>
            </table>
        </div>
        <table class="table table-hover table-responsive">
            <thead >
                <th class ="text-center bg-complete text-white" style="width: 7%">Nombre</th>
                <th class ="text-center bg-complete text-white" style="width: 3%">Pax</th>
                <th class ="text-center bg-complete text-white" style="width: 5%">Apto</th>
                <th class ="text-center bg-complete text-white" style="width: 7%">IN - OUT</th>
                <th class ="text-center bg-complete text-white" style="width: 2%"><i class="fa fa-moon-o"></i></th>
                <th class ="text-center bg-complete text-white" style="width: 5%">PVP</th>
                <th class ="text-center bg-complete text-white" style="width: 5%">Banco <br> Jorg</th>
                <th class ="text-center bg-complete text-white" style="width: 5%">Banco <br> Jaime</th>
                <th class ="text-center bg-complete text-white" style="width: 5%">Cash <br> Jorge</th>
                <th class ="text-center bg-complete text-white" style="width: 5%">Cash <br> Jaime</th>
                <th class ="text-center bg-complete text-white" style="width: 5%">Pend</th>
                <th class ="text-center bg-complete text-white" style="width: 5%">Ingreso <br> Neto</th>
                <th class ="text-center bg-complete text-white" style="width: 5%">%Benef</th>
                <th class ="text-center bg-complete text-white" style="width: 5%">Coste <br> Total</th>
                <th class ="text-center bg-complete text-white" style="width: 5%">Coste <br> Apto</th>
                <th class ="text-center bg-complete text-white" style="width: 5%">Park</th>
                <th class ="text-center bg-complete text-white" style="width: 5%">Sup. Lujo</th>
                <th class ="text-center bg-complete text-white" style="width: 5%">Limp</th>
                <th class ="text-center bg-complete text-white" style="width: 5%">Agencia</th>
                <th class ="text-center bg-complete text-white" style="width: 3%">Extras</th>
                <th class ="text-center bg-complete text-white" style="width: 3%">Stripe</th>
                <th class ="text-center bg-complete text-white" style="width: 5%">Benef Jorge</th>
                <th class ="text-center bg-complete text-white" style="width: 5%">Benef Jaime</th>
            </thead>
            <tbody >
                <!-- Totales -->
                
                <?php foreach ($books as $book): ?>
                    <tr >
                        <td class="text-center"> 
                            <!-- PVP -->
                            <a class="update-book" data-id="<?php echo $book->id ?>"  title="Editar Reserva"  href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>">
                                <?php  echo $book->customer['name'] ?>      
                            </a>
                        </td>
                        <td class="text-center">
                            <!-- pax -->

                            <?php echo $book->pax ?>        
                        </td>
                        <td class="text-center">
                            <!-- apto -->

                            <?php echo $book->room->name ?>     
                        </td>
                        <td class="text-center">
                            <?php 
                                $start = Carbon::createFromFormat('Y-m-d',$book->start);
                                echo $start->formatLocalized('%d %b');
                            ?> - 
                            <?php 
                                $finish = Carbon::createFromFormat('Y-m-d',$book->finish);
                                echo $finish->formatLocalized('%d %b');
                            ?>
                        </td>
                        <td class="text-center">
                            <?php echo $book->nigths ?>     
                        </td>
                        <td class="text-center coste" style="border-left: 1px solid black;">
                            <?php if ($book->total_price > 0): ?>
                                <b><?php echo number_format($book->total_price,0,',','.') ?> €</b>      
                            <?php else: ?>
                                <b>----</b> 
                            <?php endif ?>
                            
                        </td>

                        <td class="text-center coste" style="border-left: 1px solid black;">
                            <?php if ( $book->getPayment(2) > 0): ?>
                                <?php echo number_format($book->getPayment(2),0,',','.'); ?> €    
                            <?php else: ?>
                                <b>----</b>
                            <?php endif ?>
                            
                        </td>
                        <td class="text-center coste"  style="border-left: 1px solid black;">
                            <?php if ( $book->getPayment(3) > 0): ?>
                                <?php echo number_format($book->getPayment(3),0,',','.'); ?> €    
                            <?php else: ?>
                                <b>----</b>
                            <?php endif ?>
                            
                        </td>
                        <td class="text-center coste" style="border-left: 1px solid black;">
                            <?php if ( $book->getPayment(0) > 0): ?>
                                <?php echo number_format($book->getPayment(0),0,',','.'); ?> €    
                            <?php else: ?>
                                <b>----</b>
                            <?php endif ?>
                            
                        </td>
                        <td class="text-center coste pagos" style="border-left: 1px solid black;">
                            <?php if ( $book->getPayment(1) > 0): ?>
                                <?php echo number_format($book->getPayment(1),0,',','.'); ?> €    
                            <?php else: ?>
                                <b>----</b>
                            <?php endif ?>
                            
                        </td>
                        <td class="text-center coste pagos pendiente" style="border-left: 1px solid black;">
                            <?php $sumPayme = $book->getPayment(0) + $book->getPayment(1) + $book->getPayment(2) + $book->getPayment(3); ?>
                            <?php $pend = $book->total_price -  $sumPayme?>
                            <?php if ( ($pend) == 0 ): ?>
                                <b>----</b>
                            <?php else: ?>
                                <?php echo number_format($pend,0,',','.')." €"; ?>
                            <?php endif ?>
                            
                        </td>
                        <td class="text-center beneficio bi" style="border-left: 1px solid black;">
                            <?php if ( $book->total_ben > 0): ?>
                                <b><?php echo number_format($book->total_ben,0,',','.') ?> €</b>    
                            <?php else: ?>
                               <b>----</b>
                            <?php endif ?>
                            
                        </td>
                        <td class="text-center beneficio bf" style="border-left: 1px solid black;">
                            <?php if ( $book->inc_percent > 0): ?>
                                <?php echo number_format($book->inc_percent,0)." %" ?>    
                            <?php else: ?>
                                ----
                            <?php endif ?>
                            
                        </td>
                        <td class="text-center coste bi " style="border-left: 1px solid black;">
                            <?php 
                                $totalStripep = 0;
                                $stripePayment = \App\Payments::where('book_id', $book->id)->where('comment', 'LIKE', '%stripe%')->get() 
                            ?>
                            <?php foreach ($stripePayment as $key => $stripe): ?>
                                <?php $totalStripep +=  $stripe->import; ?>
                            <?php endforeach ?>
                            <?php $totalStripep = (((1.4 * $totalStripep)/100)+0.25) ?>
                            <?php $book->cost_total += $totalStripep?>
                            <?php if ( $book->cost_total > 0): ?>
                                <b><?php echo number_format( $book->cost_total,0,',','.')?> €</b>    
                            <?php else: ?>
                               <b>----</b>
                            <?php endif ?>
                            
                        </td>
                        <td class="text-center coste" style="border-left: 1px solid black;">
                            <?php if ( $book->cost_apto > 0): ?>
                                <?php echo number_format($book->cost_apto,0,',','.')?> €    
                            <?php else: ?>
                                ----
                            <?php endif ?>
                            
                        </td>
                        <td class="text-center coste" style="border-left: 1px solid black;">
                            <?php if ( $book->cost_park > 0): ?>
                                <?php echo number_format($book->cost_park,0,',','.')?> €    
                            <?php else: ?>
                                ----
                            <?php endif ?>
                            
                        </td>
                        <td class="text-center coste"  style="border-left: 1px solid black;">
                            <?php if ( $book->cost_lujo > 0): ?>
                                <?php echo number_format($book->cost_lujo,0,',','.')?> €    
                            <?php else: ?>
                                ----
                            <?php endif ?>
                            
                        </td>
                        <td class="text-center coste" style="border-left: 1px solid black;"> 
                            <?php if ( $book->cost_limp > 0): ?>
                                <?php echo number_format($book->cost_limp,0,',','.') ?>€    
                            <?php else: ?>
                                ----
                            <?php endif ?>
                            
                        </td>
                        <td class="text-center coste " style="border-left: 1px solid black;"> 
                            <?php if ( $book->PVPAgencia > 0): ?>
                                <?php echo number_format($book->PVPAgencia,0,',','.') ?>€    
                            <?php else: ?>
                                ----
                            <?php endif ?>
                            
                        </td>
                        <td class="text-center coste" style="border-left: 1px solid black;">
                            <?php if ( $book->extraCost > 0): ?>
                                <?php echo number_format($book->extraCost,0,',','.') ?>€    
                            <?php else: ?>
                                --
                            <?php endif ?>
                        </td>
                        <td class="text-center coste bf" style="border-left: 1px solid black;">
                            <?php 
                                $totalStripep = 0;
                                $stripePayment = \App\Payments::where('book_id', $book->id)->where('comment', 'LIKE', '%stripe%')->get() 
                            ?>
                            <?php if (count($stripePayment) > 0): ?>

                                <?php foreach ($stripePayment as $key => $stripe): ?>
                                    <?php $totalStripep +=  $stripe->import; ?>
                                <?php endforeach ?>

                                <?php if ($totalStripep > 0): ?>
                                    
                                    <?php echo number_format((((1.4 * $totalStripep)/100)+0.25), 2,',','.') ?>€

                                <?php else: ?>
                                    ----
                                <?php endif ?>


                            <?php else: ?>
                                ----
                            <?php endif ?>

                        </td>
                        <td class="text-center coste" style="border-left: 1px solid black;">
                            <?php echo number_format($book->ben_jorge,0,',','.') ?>€
                        </td>
                        
                        <td class="text-center coste" style="border-left: 1px solid black;">
                            <?php echo number_format($book->ben_jaime,0,',','.') ?>€
                        </td>
                    </tr>
                <?php endforeach ?>
                
            </tbody>
        </table>

    </div>
<?php else: ?>
    <div class="row">
        <div class="col-xs-12 push-20">
            <h2 class="text-left">
                Indicadores de ocupación
            </h2>
            @include('backend.sales._tableSummaryBoxes', ['totales' => $totales, 'books' => $books, 'data' => $data])
        </div>
        <div class="col-xs-12">
            <h2 class="text-center font-w800">
                Resumen liquidación
            </h2>
            <div class="row">
                <div class="table-responsive" style="border: none!important">
                    <table class="table push-30">
                        <tr>
                            <th class ="text-center bg-complete text-white" style="width: 5%">PVP</th>
                            <th class ="text-center bg-complete text-white" style="width: 5%">Banc. Jorg</th>
                            <th class ="text-center bg-complete text-white" style="width: 5%">Banc. Jaime</th>
                            <th class ="text-center bg-complete text-white" style="width: 5%">Cash Jorge</th>
                            <th class ="text-center bg-complete text-white" style="width: 5%">Cash Jaime</th>
                            <th class ="text-center bg-complete text-white" style="width: 4%">Pend</th>
                            <th class ="text-center bg-complete text-white" style="width: 5%">Ing Neto</th>
                            <th class ="text-center bg-complete text-white" style="width: 5%">%Benef</th>
                            <th class ="text-center bg-complete text-white" style="width: 4%">Cost. Total</th>
                            <th class ="text-center bg-complete text-white" style="width: 5%">Cost. Apto</th>
                            <th class ="text-center bg-complete text-white" style="width: 5%">Park</th>
                            <th class ="text-center bg-complete text-white" style="width: 5%">Sup. Lujo</th>
                            <th class ="text-center bg-complete text-white" style="width: 5%">Limp</th>
                            <th class ="text-center bg-complete text-white" style="width: 5%">Agencia</th>
                            <th class ="text-center bg-complete text-white" style="width: 1%">Extras</th>
                            <th class ="text-center bg-complete text-white" style="width: 1%">Stripe</th>
                            <th class ="text-center bg-complete text-white" style="width: 1%">Benef  Jorg</th>
                            <th class ="text-center bg-complete text-white" style="width: 1%">Benef  Jaim</th>

                        <tr>
                            <td class="text-center" style="border-left:1px solid black;">
                                <b><?php echo number_format($totales["total"],0,',','.') ?> €</b>       
                            </td>
                            <td class ="text-center" style="border-left:1px solid black;">
                                <?php echo number_format($totales["bancoJorge"],0,',','.') ?> €     
                            </td>
                            <td class ="text-center" style="border-left:1px solid black;">
                                <?php echo number_format($totales["bancoJaime"],0,',','.') ?> € 
                            </td>
                            <td class ="text-center" style="border-left:1px solid black;">
                                <?php echo number_format($totales["jorge"],0,',','.') ?> €  
                            </td>
                            <td class ="text-center" style="border-left:1px solid black;">
                                <?php echo number_format($totales["jaime"],0,',','.') ?> €  
                            </td>
                            <td class ="text-center" style="border-left:1px solid black;">
                                <span class="text-danger"><b><?php echo number_format($totales["pendiente"],0,',','.') ?> €</b></span>
                            </td>
                            <td class ="text-center beneficio" style="border-left:1px solid black;">
                                <b><?php echo number_format($totales["beneficio"],0,',','.') ?>€</b>
                            </td>
                            <td class ="text-center beneficio" style="border-left:1px solid black;">
                                <?php echo number_format( ( $totales["beneficio"] / $totales["total"] )* 100 ,2 ,',','.') ?>%
                            </td>
                            <td class ="text-center coste" style="border-left:1px solid black;">
                                <?php $coste = $totales["coste"] + $totales["stripe"]?>
                                <b><?php echo number_format($coste,0,',','.') ?>€</b>
                            </td>
                            <td class ="text-center coste" style="border-left:1px solid black;">
                                <?php echo number_format($totales["costeApto"],0,',','.') ?>€
                            </td>
                            <td class ="text-center coste" style="border-left:1px solid black;">
                                <?php echo number_format($totales["costePark"],0,',','.') ?>€
                            </td>
                            <td class ="text-center coste" style="border-left:1px solid black;">
                                <?php echo number_format($totales["costeLujo"],0,',','.') ?>€
                            </td>
                            <td class ="text-center coste" style="border-left:1px solid black;">
                                <?php echo number_format($totales["costeLimp"],0,',','.') ?>€
                            </td>
                            <td class ="text-center coste" style="border-left:1px solid black;">
                                <?php echo number_format($totales["costeAgencia"],0,',','.') ?>€
                            </td>
                            <td class ="text-center coste" style="border-left:1px solid black;">
                                <?php if ($totales["obs"] > 0): ?>
                                    <?php echo number_format($totales["obs"],0,',','.') ?>€
                                <?php else: ?>
                                    --
                                <?php endif ?>
                               
                            </td>
                            <td class ="text-center coste" style="border-left:1px solid black;">
                                <?php echo number_format($totales["stripe"],0,',','.') ?>€
                            </td>
                            <td class ="text-center" style="border-left:1px solid black;">
                                <?php echo number_format($totales["benJorge"],0,',','.') ?>€
                            </td>
                            <td class ="text-center" style="border-left:1px solid black;">
                                <?php echo number_format($totales["benJaime"],0,',','.') ?>€
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="table-responsive" style="border: none!important">
                    <table class="table table-hover table-responsive">
                        <thead >
                            <th class ="text-center bg-complete text-white" style="width: 7%">Nombre</th>
                            <th class ="text-center bg-complete text-white" style="width: 3%">Pax</th>
                            <th class ="text-center bg-complete text-white" style="width: 5%">Apto</th>
                            <th class ="text-center bg-complete text-white" style="width: 7%">IN - OUT</th>
                            <th class ="text-center bg-complete text-white" style="width: 2%"><i class="fa fa-moon-o"></i></th>
                            <th class ="text-center bg-complete text-white" style="width: 5%">PVP</th>
                            <th class ="text-center bg-complete text-white" style="width: 5%">Banco <br> Jorg</th>
                            <th class ="text-center bg-complete text-white" style="width: 5%">Banco <br> Jaime</th>
                            <th class ="text-center bg-complete text-white" style="width: 5%">Cash <br> Jorge</th>
                            <th class ="text-center bg-complete text-white" style="width: 5%">Cash <br> Jaime</th>
                            <th class ="text-center bg-complete text-white" style="width: 5%">Pend</th>
                            <th class ="text-center bg-complete text-white" style="width: 5%">Ingreso <br> Neto</th>
                            <th class ="text-center bg-complete text-white" style="width: 5%">%Benef</th>
                            <th class ="text-center bg-complete text-white" style="width: 5%">Coste <br> Total</th>
                            <th class ="text-center bg-complete text-white" style="width: 5%">Coste <br> Apto</th>
                            <th class ="text-center bg-complete text-white" style="width: 5%">Park</th>
                            <th class ="text-center bg-complete text-white" style="width: 5%">Sup. Lujo</th>
                            <th class ="text-center bg-complete text-white" style="width: 5%">Limp</th>
                            <th class ="text-center bg-complete text-white" style="width: 5%">Agencia</th>
                            <th class ="text-center bg-complete text-white" style="width: 1%">Extras</th>
                            <th class ="text-center bg-complete text-white" style="width: 1%">Stripe</th>
                            <th class ="text-center bg-complete text-white" style="width: 5%">Ben <br> Jorge</th>
                            <th class ="text-center bg-complete text-white" style="width: 5%">Ben <br> Jaime</th>
                        </thead>
                        <tbody >
                            <!-- Totales -->
                            
                            <?php foreach ($books as $book): ?>
                                <tr >
                                    <td class="text-center">
                                        <a class="update-book" data-id="<?php echo $book->id ?>"  title="Editar Reserva"  href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>">
                                            <?php  echo $book->customer['name'] ?>      
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $book->pax ?>        
                                    </td>
                                    <td class="text-center">
                                        <?php echo $book->room->name ?>     
                                    </td>
                                    <td class="text-center">
                                        <?php 
                                            $start = Carbon::createFromFormat('Y-m-d',$book->start);
                                            echo $start->formatLocalized('%d %b');
                                        ?> - 
                                        <?php 
                                            $finish = Carbon::createFromFormat('Y-m-d',$book->finish);
                                            echo $finish->formatLocalized('%d %b');
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $book->nigths ?>     
                                    </td>
                                    <td class="text-center">
                                        <b><?php echo number_format($book->total_price,0,',','.') ?> €</b>      
                                    </td>

                                    <td class="text-center pagos bi">
                                        <?php echo number_format($book->getPayment(2),0,',','.'); ?> €
                                    </td>
                                    <td class="text-center pagos bi">
                                        <?php echo number_format($book->getPayment(3),0,',','.'); ?> €
                                    </td>
                                    <td class="text-center pagos">
                                        <?php echo number_format($book->getPayment(0),0,',','.'); ?> €
                                    </td>
                                    <td class="text-center pagos">
                                        <?php echo number_format($book->getPayment(1),0,',','.'); ?> €
                                    </td>
                                    <td class="text-center pagos pendiente">
                                        
                                        <?php $sumPayme = $book->getPayment(0) + $book->getPayment(1) + $book->getPayment(2) + $book->getPayment(3); ?>
                                        <?php $pend = $book->total_price -  $sumPayme?>
                                        <?php if ( ($pend) == 0 ): ?>
                                            <b>----</b>
                                        <?php else: ?>
                                            <?php echo number_format($pend,0,',','.')." €"; ?>
                                        <?php endif ?>

                                    </td>
                                    <td class="text-center beneficio bi" style="border-left: 1px solid black;"><b>
                                        <?php echo number_format($book->total_ben,0,',','.') ?> €</b>
                                    </td><td class="text-center beneficio bf">
                                        <?php echo number_format($book->inc_percent,0)." %" ?>
                                    </td>
                                    <td class="text-center coste bi ">
                                        <?php 
                                            $totalStripep = 0;
                                            $stripePayment = \App\Payments::where('book_id', $book->id)->where('comment', 'LIKE', '%stripe%')->get() 
                                        ?>
                                        <?php $totalStripep = (((1.4 * $totalStripep)/100)+0.25) ?>
                                        <?php foreach ($stripePayment as $key => $stripe): ?>
                                            <?php $totalStripep +=  $stripe->import; ?>
                                        <?php endforeach ?>
                                        <b><?php echo number_format( ($book->cost_total + $totalStripep),0,',','.')?> €</b>
                                    </td>
                                    <td class="text-center coste">
                                        <?php echo number_format($book->cost_apto,0,',','.')?> €
                                    </td>
                                    <td class="text-center coste">
                                        <?php echo number_format($book->cost_park,0,',','.')?> €
                                    </td>
                                    <td class="text-center coste" >
                                        <?php echo number_format($book->cost_lujo,0,',','.')?> €
                                    </td>
                                    <td class="text-center coste">      
                                        <?php echo number_format($book->sup_limp,0,',','.') ?>€
                                    </td>
                                    <td class="text-center coste bf">   
                                        <?php echo number_format($book->PVPAgencia,0,',','.') ?>€
                                    </td>
                                    <td class ="text-center coste" style="border-left:1px solid black;">
                                    <td class="text-center coste" style="border-left: 1px solid black;">
                                        <?php if ( $book->extraCost > 0): ?>
                                            <?php echo number_format($book->extraCost,0,',','.') ?>€    
                                        <?php else: ?>
                                            --
                                        <?php endif ?>
                                    </td>
                                    <td class="text-center coste bf" style="border-left: 1px solid black;">
                                    
                                        <?php foreach ($stripePayment as $key => $stripe): ?>
                                            <?php $totalStripep +=  $stripe->import; ?>
                                        <?php endforeach ?>

                                        <?php if ($totalStripep > 0): ?>
                                            
                                            <?php echo number_format((((1.4 * $totalStripep)/100)+0.25), 2,',','.') ?>€

                                        <?php else: ?>
                                            ----
                                        <?php endif ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo number_format($book->ben_jorge,0,',','.') ?>
                                    </td>
                                    
                                    
                                    <td class="text-center">
                                        <?php echo number_format($book->ben_jaime,0,',','.') ?>
                                    </td>
                                    
                                </tr>
                            <?php endforeach ?>
                            
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
<?php endif ?>