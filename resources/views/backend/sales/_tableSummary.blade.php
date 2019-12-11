<?php
    use \Carbon\Carbon;
    use \App\Classes\Mobile;
    setlocale(LC_TIME, "ES");
    setlocale(LC_TIME, "es_ES");
    $mobile = new Mobile();
    $isMobile = $mobile->isMobile()
?>
<style type="text/css">
   <?php if ( !$isMobile ): ?>
    @media screen and (min-width: 998px){
        .summary-text{ width: 22%; }
    }
    .fix-col{
      min-width: 150px;
    }
    <?php else:?>
    th, td { white-space: nowrap; }
    .fix-col{
      width:120px;overflow-x: scroll;
    }
    table.dataTable{
      margin:0px !important
    }
    <?php endif;?>
    .liquidationSummary {
      width: 98%;
      padding: 0;
      margin: 10px auto;
    }
    input.form-control.percentBenef {
      font-size: 2em;
      color: red !important;
      border: none;
      width: 4em;
      float: right;
    }

    .paginate_button {
      padding: 7px;
      margin: 2px;
      border: 1px solid #c7c7c7;
  }
</style>

    
    <div class="row">
      <div class="col-md-8">
        <h2 class="text-left"> Indicadores de ocupación</h2>
      </div>
      <div class="col-md-4 text-right">
        <label style="width: 100%; padding-right: 4em;">Benef critico</label>
        <span style="float: right;font-size: 2em;">%</span>
        <input class="form-control font-w400 text-center percentBenef" value="<?php echo $percentBenef ?>" /> 
      </div>
    </div>
      
      @include('backend.sales._tableSummaryBoxes', ['totales' => $totales, 'books' => $books, 'data' => $data, 'year'=> $year])
      
      <div >
        <div class="row push-10">
           <h2 class="text-left font-w800">
              Resumen liquidación
          </h2>
        </div>
      <div class="table-responsive" >
            <table class="table " id="tableOrderable">
             <thead >
                <th class="text-center bg-complete text-white sorting_disabled fix-col">Nombre</th>
                    <th class ="text-center bg-complete text-white" style="width: 3% !important;font-size:10px!important">&nbsp;&nbsp;&nbsp;Tipo&nbsp;&nbsp;&nbsp;</th>
                    <th class ="text-center bg-complete text-white" style="width: 2% !important;font-size:10px!important">Pax</th>
                    <th class ="text-center bg-complete text-white" style="width: 1% !important;font-size:10px!important">Apto</th>
                    <th class ="text-center bg-complete text-white" style="width: 10% !important;font-size:10px!important">IN - OUT</th>
                    <th class ="text-center bg-complete text-white" style="width: 2% !important;font-size:10px!important"><i class="fa fa-moon"></i></th>
                    <th class ="text-center bg-complete text-white" style="width: 5% !important;font-size:10px!important">
                      Ventas <br/><b><?php echo number_format($totales["total"],0,',','.') ?> €</b>
                    </th>
                    <th class ="text-center bg-complete text-white" style="width: 5% !important;font-size:10px!important">
                      BANCO<br/>
                      <b>
                        <?php $aux = $totales["bancoJorge"] + $totales["bancoJaime"]; ?>
			<?php if ($aux == 0): ?>
                        ----
                        <?php else: ?>
                        <?php echo number_format($aux,0,',','.') ?> €
                        <?php endif ?>
                      </b>
                    </th>
                    <th class ="text-center bg-complete text-white" style="width: 5% !important;font-size:10px!important">CAJA<br/>
                      <b>
                        <?php $aux = $totales["jorge"] + $totales["jaime"]; ?>
			<?php if ($aux == 0): ?>
                        ----
                        <?php else: ?>
                        <?php echo number_format($aux,0,',','.') ?> €
                        <?php endif ?>
                      </b>
                    </th>
                    <th class ="text-center bg-complete text-white" style="width: 5% !important;font-size:10px!important">
                      Pend<br/><b>{{ $totales['pendiente'] ? number_format($totales["pendiente"],0,',','.') . ' €' : '----' }}</b>
                    </th>
                    <th class ="text-center bg-complete text-white" style="width: 5% !important;font-size:10px!important">
                      Ingreso Neto<br>
                      <b><?php echo number_format($totales["beneficio"],0,',','.') ?>€</b>
                    </th>
                    <th class ="text-center bg-complete text-white" style="width: 5% !important;font-size:10px!important">
                      %Benef<br/>
                      <b><?php 
                        $totoalDiv = ($totales["total"] == 0)?1:$totales["total"];
                        echo number_format( ( $totales["beneficio"] / $totoalDiv )* 100 ,2 ,',','.') 
                      ?>%</b>
                    </th>
                    <th class ="text-center bg-complete text-white" style="width: 5% !important;font-size:10px!important">
                      Coste Total<br/>
                      <b><?php 
                      $total_cost = 	$totales["costeApto"]+$totales["costePark"]+ $totales["costeLujo"]+ $totales["costeLimp"]+ $totales["costeAgencia"]+ $totales["obs"]+ $totales["stripe"];
                      echo number_format($total_cost,0,',','.');
                      ?>€</b>
                    </th>
                    <th class ="text-center bg-complete text-white" style="width: 5% !important;font-size:10px!important">
                      Coste Apto<br/><b><?php echo number_format($totales["costeApto"],0,',','.') ?>€</b>
                    </th>
                    <th class ="text-center bg-complete text-white" style="width: 5% !important;font-size:10px!important">
                      Park<br/><b><?php echo number_format($totales["costePark"],0,',','.') ?>€</b>
                    </th>
                    <th class ="text-center bg-complete text-white" style="width: 5% !important;font-size:10px!important">
                      Sup. Lujo<br/><b> <?php echo number_format($totales["costeLujo"],0,',','.') ?>€</b>
                    </th>
                    <th class ="text-center bg-complete text-white" style="width: 5% !important;font-size:10px!important">
                      Limp<br/><b><?php echo number_format($totales["costeLimp"],0,',','.') ?>€</b>
                    </th>
                    <th class ="text-center bg-complete text-white" style="width: 5% !important;font-size:10px!important">
                      Agencia<br/><b><?php echo number_format($totales["costeAgencia"],0,',','.') ?>€</b>
                    </th>
                    <th class ="text-center bg-complete text-white" style="width: 5% !important;font-size:10px!important">
                      Extras<br/><b><?php echo ($totales["obs"] > 0) ?  number_format($totales["obs"],0,',','.').'€' : '--'; ?></b>
                    </th>
                    <th class ="text-center bg-complete text-white" style="width: 5% !important;font-size:10px!important">
                      TPV<br/><b><?php echo number_format($totales["stripe"],0,',','.') ?>€</b>
                    </th>
                </thead>
                <tbody >
                    <!-- Totales -->

                    <?php foreach ($books as $book): ?>
                        <tr >
                          <td class ="text-left">  
                            <div class=" fix-col">
                          <?php if ($book->agency != 0): ?>
                          <img src="/pages/<?php echo strtolower($book->getAgency($book->agency)) ?>.png" class="img-agency" />
                          <?php endif ?>
                          <?php if (!empty($book->book_owned_comments) && $book->promociones != 0 ): ?>
                              <img src="/pages/oferta.png" class="img-oferta" title="<?php echo $book->book_owned_comments ?>">
                          <?php endif ?>
                            
                          <a class="update-book" data-id="<?php echo $book->id ?>"  title="Editar Reserva"  href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>">
                            <?php  echo $book->customer->name ?>
                          </a>
                                        </div>
                            </td>
                            <td class ="text-center">  
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
                                <input class="updatePVP" type="number" step="0.01" value="<?php echo round($book->total_price);?>" data-idBook="<?php echo $book->id; ?>"/>
                            </td>
                            <td class="text-center coste banco" style="border-left: 1px solid black;">
                              <?php $aux = $book->getPayment(2) + $book->getPayment(3); ?>
                              <?php echo ($aux == 0)? '---' : number_format($aux,0,',','.').' €'; ?>
                            </td>
                            <td class="text-center coste caja" style="border-left: 1px solid black;">
                              <?php $aux = $book->getPayment(0) + $book->getPayment(1); ?>
                              <?php echo ($aux == 0)? '---' : number_format($aux,0,',','.').' €'; ?>
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
                            <?php if(round($book->inc_percent) <= $percentBenef && round($book->inc_percent) > 0): ?>
                                <?php $classDanger = "background-color: #f8d053!important; color:black!important;" ?>
                            <?php elseif(round($book->inc_percent) <= 0): ?>
                                <?php $classDanger = "background-color: red!important; color:white!important;" ?>
                            <?php else: ?>
                                <?php $classDanger = "" ?>
                            <?php endif; ?>
                            <td class="text-center beneficio bf " style="border-left: 1px solid black; <?php echo $classDanger ?>">
	                            <?php echo number_format($inc_percent,0)."%" ?>
                            </td>
                            <td class="text-center coste bi " style="border-left: 1px solid black;">
                                {{$cost_total}}
                            </td>
                            <td class="text-center coste" style="border-left: 1px solid black;">
                                <input class="updateCostApto" type="number" value="<?php echo round($book->cost_apto); ?>" data-idBook="<?php echo $book->id; ?>"/>
                            </td>
                            <td class="text-center coste" style="border-left: 1px solid black;">
                                <input class="updateCostPark" type="number" value="<?php echo round($book->cost_park); ?>" data-idBook="<?php echo $book->id; ?>"/>
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
                        </tr>
                    <?php endforeach ?>

                </tbody>
            </table>
        </div>
    </div>
      
      
      
      
      
      
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.20/datatables.min.js"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/3.3.0/js/dataTables.fixedColumns.min.js"></script>



<script src="/assets/js/scripts.js" type="text/javascript"></script>
<script>

$(document).ready(function() {
    @if($isMobile)
      $('#tableOrderable').dataTable({
	searching: true,
	ordering:true,
	paging:  false,
        scrollX: true,
        scrollY: false,
        scrollCollapse: true,
        paging:  true,
        pageLength: 30,
        pagingType: "full_numbers"
         fixedColumns:   {
            leftColumns: 1
          }
        });
    @else
      $('#tableOrderable').dataTable({
	searching: true,
	ordering:true,
	paging:  false,
        });
    @endif
} );
	


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

    $('.updateCostTotal').change(function(){
    	var id = $(this).attr('data-idBook');
    	var costTotal = $(this).val();
    	$.get( "/admin/sales/updateCostTotal/"+id+"/"+costTotal).done(function( data ) {console.log(data)});

    });
    $('.updatePVP').change(function(){
      var id = $(this).attr('data-idBook');
      var pvp = $(this).val();
      $.get( "/admin/sales/updatePVP/"+id+"/"+pvp).done(function( data ) {console.log(data)});

    });

</script>