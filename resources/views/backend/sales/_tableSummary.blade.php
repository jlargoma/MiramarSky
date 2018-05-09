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
        <div class="col-md-11 col-xs-12">
            <h2 class="text-left">
                Indicadores de ocupación
            </h2>
        </div>
        <div class="col-md-1 col-xs-12 pull-right">
            <label style="width: 100%; float:left;">Benef critico</label>
            <input class="form-control text-black font-w400 text-center percentBenef" value="<?php echo $percentBenef ?>" style="border: none; font-size: 32px;margin: 10px 0;color:red!important; width: 70%; float:left;"/> <span class="font-w800" style="font-size: 32px">%</span>
        </div>
        <div style="clear: both;"></div>
        @include('backend.sales._tableSummaryBoxes', ['totales' => $totales, 'books' => $books, 'data' => $data, 'temporada' => $temporada])
    </div>
    <div class="col-xs-12">
        <div class="row push-10">
            <table>
                <thead>
                <th class ="text-center text-white" rowspan="2" colspan="6" style="width: 30%; padding: 5px 5px 0">
                    <h2 class="text-center font-w800">
                        Resumen liquidación
                    </h2>
                </th>
                <th class ="text-center bg-complete text-white" style="width: 5%; padding: 5px 5px 0">Ventas</th>
                <th class ="text-center bg-complete text-white" style="width: 5%; padding: 5px 5px 0">Banc.<br> Jorg</th>
                <th class ="text-center bg-complete text-white" style="width: 5%; padding: 5px 5px 0">Banc.<br> Jaime</th>
                <th class ="text-center bg-complete text-white" style="width: 5%; padding: 5px 5px 0">Cash <br>Jorge</th>
                <th class ="text-center bg-complete text-white" style="width: 5%; padding: 5px 5px 0">Cash<br>Jaime</th>
                <th class ="text-center bg-complete text-white" style="width: 5%; padding: 5px 5px 0">Pend</th>
                <th class ="text-center bg-complete text-white" style="width: 5%; padding: 5px 5px 0">Ing<br> Neto</th>
                <th class ="text-center bg-complete text-white" style="width: 5%; padding: 5px 5px 0">%Benef</th>
                <th class ="text-center bg-complete text-white" style="width: 5%; padding: 5px 5px 0">Cost.<br> Total</th>
                <th class ="text-center bg-complete text-white" style="width: 5%; padding: 5px 5px 0">Cost.<br> Apto</th>
                <th class ="text-center bg-complete text-white" style="width: 5%; padding: 5px 5px 0">Park</th>
                <th class ="text-center bg-complete text-white" style="width: 5%; padding: 5px 5px 0">Sup.<br> Lujo</th>
                <th class ="text-center bg-complete text-white" style="width: 5%; padding: 5px 5px 0">Limp</th>
                <th class ="text-center bg-complete text-white" style="width: 5%; padding: 5px 5px 0">Agencia</th>
                <th class ="text-center bg-complete text-white" style="width: 3%; padding: 5px 5px 0">Extras</th>
                <th class ="text-center bg-complete text-white" style="width: 3%; padding: 5px 5px 0">Stripe</th>
                <th class ="text-center bg-complete text-white" style="width: 5%; padding: 5px 5px 0">Benef<br>Jorg</th>
                <th class ="text-center bg-complete text-white" style="width: 5%; padding: 5px 5px 0">Benef<br>Jaim</th>
                </thead>
                <tr>
                    <td class="text-center" colspan="6" >
                    </td>
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
                        <span class="text-danger">
                            <b>
                                {{ $totales['pendiente'] ? number_format($totales["pendiente"],0,',','.') . ' €' : '----' }}
                            </b>
                        </span>
                    </td>
                    <td class ="text-center beneficio" style="border-left:1px solid black;">
                        <b><?php echo number_format($totales["beneficio"],0,',','.') ?>€</b>
                    </td>
                    <td class ="text-center beneficio" style="border-left:1px solid black;">
				        <?php $totoalDiv = ($totales["total"] == 0)?1:$totales["total"]; ?>
				        <?php echo number_format( ( $totales["beneficio"] / $totoalDiv )* 100 ,2 ,',','.') ?>%
                    </td>
                    <td class ="text-center coste" style="border-left:1px solid black;">
                    	<?php $total_cost = 	$totales["costeApto"]+$totales["costePark"]+ $totales["costeLujo"]+ $totales["costeLimp"]+ $totales["costeAgencia"]+ $totales["obs"]+ $totales["stripe"]; ?>
                        <b><?php echo number_format($total_cost,0,',','.') ?>€</b>
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
        <div class="col-md-12 col-xs-12" style="padding-right: 0;">
            <table class="table table-striped" id="tableOrderable">
                <thead >
                    <th class ="text-center bg-complete text-white sorting_disabled" style="width: 7% !important; font-size:
                    10px!important">Nombre</th>
                    <th class ="text-center bg-complete text-white" style="width: 3% !important;font-size:10px!important">&nbsp;&nbsp;&nbsp;Tipo&nbsp;&nbsp;&nbsp;</th>
                    <th class ="text-center bg-complete text-white" style="width: 2% !important;font-size:10px!important">Pax</th>
                    <th class ="text-center bg-complete text-white" style="width: 1% !important;font-size:10px!important">Apto</th>
                    <th class ="text-center bg-complete text-white" style="width: 10% !important;font-size:10px!important">IN - OUT</th>
                    <th class ="text-center bg-complete text-white" style="width: 2% !important;font-size:10px!important"><i class="fa fa-moon"></i></th>
                    <th class ="text-center bg-complete text-white" style="width: 5% !important;font-size:10px!important">Ventas</th>
                    <th class ="text-center bg-complete text-white" style="width: 5% !important;font-size:10px!important">Banco <br> Jorg</th>
                    <th class ="text-center bg-complete text-white" style="width: 5% !important;font-size:10px!important">Banco <br> Jaime</th>
                    <th class ="text-center bg-complete text-white" style="width: 5% !important;font-size:10px!important">Cash <br> Jorge</th>
                    <th class ="text-center bg-complete text-white" style="width: 5% !important;font-size:10px!important">Cash <br> Jaime</th>
                    <th class ="text-center bg-complete text-white" style="width: 5% !important;font-size:10px!important">Pend</th>
                    <th class ="text-center bg-complete text-white" style="width: 5% !important;font-size:10px!important">Ingreso <br>  Neto</th>
                    <th class ="text-center bg-complete text-white" style="width: 5% !important;font-size:10px!important">%Benef</th>
                    <th class ="text-center bg-complete text-white" style="width: 5% !important;font-size:10px!important">Coste <br> Total</th>
                    <th class ="text-center bg-complete text-white" style="width: 5% !important;font-size:10px!important">Coste <br> Apto</th>
                    <th class ="text-center bg-complete text-white" style="width: 5% !important;font-size:10px!important">Park</th>
                    <th class ="text-center bg-complete text-white" style="width: 5% !important;font-size:10px!important">Sup. Lujo</th>
                    <th class ="text-center bg-complete text-white" style="width: 5% !important;font-size:10px!important">Limp</th>
                    <th class ="text-center bg-complete text-white" style="width: 5% !important;font-size:10px!important">Agencia</th>
                    <th class ="text-center bg-complete text-white" style="width: 5% !important;font-size:10px!important">Extras</th>
                    <th class ="text-center bg-complete text-white" style="width: 5% !important;font-size:10px!important">Stripe</th>
                    <th class ="text-center bg-complete text-white" style="width: 5% !important;font-size:10px!important">Benef Jorge</th>
                    <th class ="text-center bg-complete text-white" style="width: 5% !important;font-size:10px!important">Benef Jaime</th>
                </thead>
                <tbody >
                    <!-- Totales -->

                    <?php foreach ($books as $book): ?>
                        <tr >
                            <td class="text-center">
                                <span style="display: none;"><?php echo strtotime($book->start);?></span>
                                <div class="col-xs-2">
                                    <?php if ($book->agency != 0): ?>
                                        <img style="width: 20px;margin: 0 auto;position: absolute; left: 0px;" src="/pages/<?php  echo strtolower($book->getAgency($book->agency)) ?>.png" align="center" />
	                               <?php endif ?>

                                </div>
                                <div class="col-xs-8">
                                    <a class="update-book" data-id="<?php echo $book->id ?>"  title="Editar Reserva"  href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>">
                                        <?php  echo $book->customer->name ?>
                                    </a>
                                </div>
                                <div class="col-xs-2">
                                    <?php if (!empty($book->book_owned_comments) && $book->promociones != 0 ): ?>
                                        <img src="/pages/oferta.png" style="width: 40px;" title="<?php echo $book->book_owned_comments ?>">
                                    <?php endif ?>
                                </div>
                            </td>
                            <td class="text-center">
                                <!-- type -->
                                <b>
                                <?php
                                    switch ($book->type_book){
                                        case 2:
                                        	echo "C";
                                        	break;
	                                    case 7:
                                            echo "P";
                                            break;
	                                    case 8:
		                                    echo "A";
		                                    break;
                                    }
                                ?>
                                </b>
                            </td>
                            <td class="text-center">
                                <!-- pax -->

                                <?php echo $book->pax ?>
                            </td>
                            <td class="text-center">
                                <!-- apto -->

                                <?php echo $book->room->nameRoom ?>
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
                            <td class="text-center coste pagos pendiente red <?php if($book->pending > 0){ echo
                            'alert-limp'; }?>" style="border-left: 1px solid black;" >

                                {{ $book->pending > 0 ? number_format( $book->pending,0,',','.') . ' €' : '----' }}

                            </td>
                            <td class="text-center beneficio bi" style="border-left: 1px solid black;">
                                <?php $profit = $book->profit?>
	                            <?php $cost_total = $book->cost_apto + $book->cost_park + $book->cost_lujo + $book->cost_limp + $book->PVPAgencia + $book->stripeCost + $book->extraCost;?>
	                            <?php $total_price = $book->total_price?>
	                            <?php $inc_percent = 0?>
	                            <?php
                                    if($book->room->luxury == 0 && $book->cost_lujo > 0) {
	                            	    $profit     = $book->profit - $book->cost_lujo;
	                                    $cost_total = $book->cost_total - $book->cost_lujo;
	                                    $total_price = ( $book->total_price - $book->sup_lujo );
                                    }
                                    if ($total_price != 0)
                                        $inc_percent = ($profit/ $total_price )*100;
                                ?>
                                <?php echo number_format($profit,0,',','.') ?> €</b>
                            </td>
                            <?php if(round($book->inc_percent) < $percentBenef): ?>
                                <?php $classDanger = "background-color: #f8d053!important; color:black!important;" ?>
                            <?php elseif(round($book->inc_percent) == 0): ?>
                                <?php $classDanger = "background-color: #f8d053!important; color:black!important;" ?>
                            <?php else: ?>
                                <?php $classDanger = "" ?>
                            <?php endif; ?>
                            <td class="text-center beneficio bf " style="border-left: 1px solid black; <?php echo $classDanger ?>">

                                <?php if ( $inc_percent > 0): ?>
                                    <?php echo number_format($inc_percent,0)." %" ?>
                                <?php else: ?>
                                    ----
                                <?php endif ?>

                            </td>
                            <td class="text-center coste bi " style="border-left: 1px solid black;">
								
                                {{ $cost_total > 0 ? number_format(( $cost_total + $book->stripeCost),0,',','.') . ' €' :
                                '----' }}
                            </td>
                            <td class="text-center coste" style="border-left: 1px solid black;">
                                <?php if ( $book->cost_apto > 0): ?>
                                    <?php //echo number_format($book->cost_apto,0,',','.')?> 
                                    <input class="updateCostApto " type="number" value="<?php echo round($book->cost_apto); ?>" data-idBook="<?php echo $book->id; ?>"/>
                                <?php else: ?>
                                    ----
                                <?php endif ?>

                            </td>
                            <td class="text-center coste" style="border-left: 1px solid black;">
                                <?php if ( $book->cost_park > 0): ?>
                                    <?php //echo number_format($book->cost_park,0,',','.')?>
                                    <input class="updateCostPark " type="number" value="<?php echo round($book->cost_park); ?>" data-idBook="<?php echo $book->id; ?>"/>
                                <?php else: ?>
                                    ----
                                <?php endif ?>

                            </td>
                            <td class="text-center coste"  style="border-left: 1px solid black;">
                                <?php if ($book->room->luxury == 1): ?>

                                    <?php if ( $book->cost_lujo > 0): ?>
                                        <?php echo number_format($book->cost_lujo,0,',','.')?> €
                                    <?php else: ?>
                                        ----
                                    <?php endif ?>
                                <?php else: ?>
                                    ----
                                <?php endif;?>

                            </td>
                            <td class="text-center coste <?php if($book->cost_limp == 0){ echo 'alert-limp'; }?>" style="border-left: 1px solid black;">
                                <input class="updateLimp <?php if($book->cost_limp == 0){ echo 'alert-limp'; }?>" type="number" step="0.01" value="<?php echo $book->cost_limp; ?>" data-idBook="<?php echo $book->id; ?>"/>
                            </td>
                            <td class="text-center coste " style="border-left: 1px solid black;">
                                <?php if ( $book->PVPAgencia > 0): ?>
                                    <?php echo number_format($book->PVPAgencia,0,',','.') ?>€
                                <?php else: ?>
                                    ----
                                <?php endif ?>

                            </td>
                            <td class="text-center coste <?php if($book->extraCost == 0){ echo 'alert-limp'; }?>" style="border-left: 1px solid black;">
                                <input class="updateExtraCost <?php if($book->extraCost == 0){ echo 'alert-limp'; }?>" type="number" value="<?php echo round($book->extraCost); ?>" data-idBook="<?php echo $book->id; ?>"/>
                            </td>
                            <td class="text-center coste bf" style="border-left: 1px solid black;">
                                <span data-toggle="tooltip" data-placement="top" data-original-title="{{ number_format($book->stripeCostRaw, 2,',','.') }} €">
                                    <?php if ($book->stripeCost > 0): ?>
                                        <?php echo number_format($book->stripeCost, 2,',','.') ?>€
                                    <?php else: ?>
                                        ----
                                    <?php endif ?>
                                </span>
                            </td>
                            <td class="text-center coste" style="border-left: 1px solid black;">
                                {{ $book->getJorgeProfit() }} €
                            </td>

                            <td class="text-center coste" style="border-left: 1px solid black;">
                                {{ $book->getJaimeProfit() }} €
                            </td>
                        </tr>
                    <?php endforeach ?>

                </tbody>
            </table>
        </div>
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
            <div class="row">
                <div class="table-responsive" style="border: none!important">
                    <table class="table push-30">
                        <thead>
                        	<th class ="text-center text-white" rowspan="2" colspan="5" style="width: 24%; padding: 5px 5px 0">
		                        <h2 class="text-center font-w800">
		                            Resumen liquidación
		                        </h2>
		                    </th>
                            <th class ="text-center bg-complete text-white" style="width: 5%">Ventas</th>
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
                        </thead>
                        <tr>
                        	<td class="text-center" colspan="5" >
                    		</td>
                            <td class="text-center" style="border-left:1px solid black;">
                                <b><?php echo number_format($totales["total"],0,',','.') ?> €</b>
                            </td>
                            <td class ="text-center" style="border-left:1px solid black;">
                                <?php if ($totales["bancoJorge"] == 0): ?>
                                    ----
                                <?php else: ?>
                                    <?php echo number_format($totales["bancoJorge"],0,',','.') ?> €
                                <?php endif ?>
                            </td>
                            <td class ="text-center" style="border-left:1px solid black;">
                                <?php if ($totales["bancoJaime"] == 0): ?>
                                    ----
                                <?php else: ?>
                                    <?php echo number_format($totales["bancoJaime"],0,',','.') ?> €
                                <?php endif ?>
                            </td>
                            <td class ="text-center" style="border-left:1px solid black;">
                                <?php if ($totales["jorge"] == 0): ?>
                                    ----
                                <?php else: ?>
                                    <?php echo number_format($totales["jorge"],0,',','.') ?> €
                                <?php endif ?>
                            </td>
                            <td class ="text-center" style="border-left:1px solid black;">
                                <?php if ($totales["jaime"] == 0): ?>
                                    ----
                                <?php else: ?>
                                    <?php echo number_format($totales["jaime"],0,',','.') ?> €
                                <?php endif ?>
                            </td>
                            <td class ="text-center" style="border-left:1px solid black;">
                                <span class="text-danger">
                                    <b>
                                        {{ $totales['pendiente'] ? number_format($totales["pendiente"],0,',','.') . ' €' : '----' }}
                                    </b>
                                </span>
                            </td>
                            <td class ="text-center beneficio" style="border-left:1px solid black;">
                                <b><?php echo number_format($totales["beneficio"],0,',','.') ?>€</b>                            </td>
                            <td class ="text-center beneficio" style="border-left:1px solid black;">
                                <?php $totoalDiv = ($totales["total"] == 0)?1:$totales["total"]; ?>
                                <?php echo number_format( ( $totales["beneficio"] / $totoalDiv )* 100 ,2 ,',','.') ?>%                            </td>
                            <td class ="text-center coste" style="border-left:1px solid black;">
                                <b><?php echo number_format($totales['coste'],0,',','.') ?>€</b>
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
                        <tr>
		                    <td class="text-center" colspan="23" style="height: 35px;">

		                    </td>
		                </tr>
                        <thead >
                            <th class ="text-center bg-complete text-white" style="width: 7%">Nombre</th>
                            <th class ="text-center bg-complete text-white" style="width: 3%">Pax</th>
                            <th class ="text-center bg-complete text-white" style="width: 5%">Apto</th>
                            <th class ="text-center bg-complete text-white" style="width: 7%">IN - OUT</th>
                            <th class ="text-center bg-complete text-white" style="width: 2%"><i class="fa fa-moon-o"></i></th>
                            <th class ="text-center bg-complete text-white" style="width: 5%">Ventas</th>
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
                                        <div class="col-xs-2 not-padding">
                                            <?php if ($book->agency != 0): ?>
                                                <img style="width: 20px;margin: 0 auto;" src="/pages/<?php echo strtolower($book->getAgency($book->agency)) ?>.png" align="center" />
                                            <?php endif ?>
                                        </div>
                                        <div class="col-xs-8">
                                            <a class="update-book" data-id="<?php echo $book->id ?>"  title="Editar Reserva"  href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>">
                                                <?php  echo $book->customer['name'] ?>
                                            </a>
                                        </div>
                                        <div class="col-xs-2 not-padding">
                                            <?php if (!empty($book->book_owned_comments)): ?>
                                                <span class="icons-comment" data-class-content="content-commentOwned-<?php echo $book->id?>">
                                                    <img src="/pages/oferta.png" style="width: 40px;">
                                                </span>
                                                <div class="comment-floating content-commentOwned-<?php echo $book->id?>" style="display: none;"><p class="text-left"><?php echo $book->book_owned_comments ?></p></div>

                                            <?php endif ?>
                                        </div>

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
                                    <td class="text-center coste pagos pendiente red <?php if($book->pending > 0){ echo
	                                'alert-limp'; }?>" style="border-left: 1px solid black;" >

                                        {{ $book->pending > 0 ? number_format( $book->pending,0,',','.') . ' €' : '----' }}

                                    </td>
                                    <td class="text-center beneficio bi" style="border-left: 1px solid black;"><b>
                                        <?php if($book->room->luxury == 0 && $book->cost_limp > 0) {$book->profit -=
			                                    $book->cost_limp; } ?>
                                        <?php echo number_format($book->profit,0,',','.') ?> €</b>
                                    </td>
                                    <?php if(round($book->inc_percent) < $percentBenef): ?>
                                        <?php $classDanger = "background-color: #f55753!important; color:white!important;" ?>
                                    <?php elseif(round($book->inc_percent) == 0): ?>
                                        <?php $classDanger = "background-color: #f8d053!important; color:black!important;" ?>
                                    <?php else: ?>
                                        <?php $classDanger = "" ?>
                                    <?php endif; ?>
                                    <td class="text-center beneficio bf " style="border-left: 1px solid black; <?php echo $classDanger ?>">
                                        <?php if ( $book->inc_percent > 0): ?>
                                            <?php echo number_format($book->inc_percent,0)." %" ?>
                                        <?php else: ?>
                                            ----
                                        <?php endif ?>

                                    </td>
                                    <td class="text-center coste bi ">
                                        <b>{{ $book->cost_total > 0 ? number_format(( $book->cost_total + $book->stripeCost),0,',','.') . ' €' :
                                '----' }}</b>
                                    </td>
                                    <td class="text-center coste">
                                         <input class="updateCostApto " type="number" value="<?php echo round($book->cost_apto); ?>" data-idBook="<?php echo $book->id; ?>"/>
                                    </td>
                                    <td class="text-center coste">
                                        <input class="updateCostPark " type="number" value="<?php echo round($book->cost_park); ?>" data-idBook="<?php echo $book->id; ?>"/>
                                    </td>
                                    <td class="text-center coste" >
                                        <?php echo number_format($book->cost_lujo,0,',','.')?> €
                                    </td>
                                    <td class="text-center coste">
                                        <input class="form-control updateLimp" type="number" step="0.01" value="<?php echo $book->cost_limp; ?>" data-idBook="<?php echo $book->id; ?>"/>
                                    </td>
                                    <td class="text-center coste bf">
                                        <?php echo number_format($book->PVPAgencia,0,',','.') ?>€
                                    </td>
                                    <td class="text-center coste <?php if($book->extraCost == 0){ echo 'alert-limp'; }?>" style="border-left: 1px solid black;">
                                        <input class="updateExtraCost <?php if($book->extraCost == 0){ echo 'alert-limp'; }?>" type="number" value="<?php echo round($book->extraCost); ?>" data-idBook="<?php echo $book->id; ?>"/>
                                    </td>
                                    <td class="text-center coste bf" style="border-left: 1px solid black;">
                                       <span data-toggle="tooltip" data-placement="top" data-original-title="{{ number_format($book->stripeCostRaw, 2,',','.') }} €">
                                            <?php if ($book->stripeCost > 0): ?>
                                               <?php echo number_format($book->stripeCost, 2,',','.') ?>€
                                            <?php else: ?>
                                                ----
                                            <?php endif ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <?php echo number_format($book->getJorgeProfit(),0,',','.') ?>€
                                    </td>
                                    <td class="text-center">
                                        <?php echo number_format($book->getJaimeProfit(),0,',','.') ?>€
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

<script src="/assets/plugins/jquery-datatable/media/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="/assets/plugins/jquery-datatable/extensions/TableTools/js/dataTables.tableTools.min.js" type="text/javascript"></script>
<script src="/assets/plugins/jquery-datatable/media/js/dataTables.bootstrap.js" type="text/javascript"></script>
<script src="/assets/plugins/jquery-datatable/extensions/Bootstrap/jquery-datatable-bootstrap.js" type="text/javascript"></script>
<script type="text/javascript" src="/assets/plugins/datatables-responsive/js/datatables.responsive.js"></script>
<script type="text/javascript" src="/assets/plugins/datatables-responsive/js/lodash.min.js"></script>
<script type="text/javascript" src="/assets/js/datatables.js"></script>
<script src="/assets/js/scripts.js" type="text/javascript"></script>
<script>

	$('#tableOrderable').dataTable({
	"searching": false,
	"ordering": true,
	"paging":   false,
	"columnDefs": [
	                {
	                    "targets": [0,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23], // column or
	                  // columns numbers
	                    "orderable": false,  // set orderable for selected columns
	                }
	            ],

	});

  	$('.updateLimp').change(function(){
		var id = $(this).attr('data-idBook');
		var limp = $(this).val();
		$.get( "/admin/sales/updateLimpBook/"+id+"/"+limp).done(function( data ) {

		});
 	});

  	$('.updateExtraCost').change(function(){
    	var id = $(this).attr('data-idBook');
    	var extraCost = $(this).val();
    	$.get( "/admin/sales/updateExtraCost/"+id+"/"+extraCost).done(function( data ) {console.log(data)});
    });


	$('.updateCostApto').change(function(){
    	var id = $(this).attr('data-idBook');
    	var costApto = $(this).val();
    	$.get( "/admin/sales/updateCostApto/"+id+"/"+costApto).done(function( data ) {console.log(data)});

    });

	$('.updateCostPark').change(function(){
    	var id = $(this).attr('data-idBook');
    	var costPark = $(this).val();
    	$.get( "/admin/sales/updateCostPark/"+id+"/"+costPark).done(function( data ) {console.log(data)});

    });
        
</script>