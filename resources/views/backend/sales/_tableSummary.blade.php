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
                        {{moneda($totales["banco"])}}
                      </b>
                    </th>
                    <th class ="text-center bg-complete text-white" style="width: 5% !important;font-size:10px!important">CAJA<br/>
                      <b>
                       {{moneda($totales["caja"])}}
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
                      AMENITIES<br/><b><?php echo ($totales["obs"] > 0) ?  number_format($totales["obs"],0,',','.').'€' : '--'; ?></b>
                    </th>
                    <th class ="text-center bg-complete text-white" style="width: 5% !important;font-size:10px!important">
                      TPV<br/><b>{{moneda($total_stripeCost,false)}}</b>
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
                                        
                            <td class="text-center" data-order="{{$book->start}}">{{convertDateToShow_text($book->start)}} - {{convertDateToShow_text($book->finish)}}</td>

                            <td class="text-center">
                                <?php echo $book->nigths ?>
                            </td>
                            <td class="text-center coste" style="border-left: 1px solid black;">
                                <input class="updatePVP" type="number" step="0.01" value="<?php echo round($book->total_price);?>" data-idBook="<?php echo $book->id; ?>"/>
                            </td>
                            <td class="text-center coste banco" style="border-left: 1px solid black;">
                              {{moneda($books_payments[$book->id]['banco'],false)}}
                            </td>
                            <td class="text-center coste caja" style="border-left: 1px solid black;">
                               {{moneda($books_payments[$book->id]['caja'],false)}}
                            </td>
                            <td class="text-center coste pagos pendiente red <?php if($book->pending > 0){ echo
                            'alert-limp'; }?>" style="border-left: 1px solid black;" >

                                {{ $book->pending > 0 ? number_format( $book->pending,0,',','.') . ' €' : '----' }}

                            </td>
                            <td class="text-center beneficio bi" style="border-left: 1px solid black;">
                               {{moneda($book->total_ben)}}
                            </td>
                            <?php if(round($book->inc_percent) <= $percentBenef && round($book->inc_percent) > 0): ?>
                                <?php $classDanger = "background-color: #f8d053!important; color:black!important;" ?>
                            <?php elseif(round($book->inc_percent) <= 0): ?>
                                <?php $classDanger = "background-color: red!important; color:white!important;" ?>
                            <?php else: ?>
                                <?php $classDanger = "" ?>
                            <?php endif; ?>
                            <td class="text-center beneficio bf " style="border-left: 1px solid black; <?php echo $classDanger ?>">
	                            <?php echo $book->inc_percent."%" ?>
                            </td>
                            <td class="text-center coste bi " style="border-left: 1px solid black;">
                                {{$book->cost_total}}
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
                                <span data-toggle="tooltip" data-placement="top" data-original-title=" {{moneda($stripeCost[$book->id],false)}}">
                                  {{moneda($stripeCost[$book->id],false)}}
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
        scrollX: true,
        scrollY: false,
        scrollCollapse: true,
        paging:  true,
        pageLength: 30,
        pagingType: "full_numbers",
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
                  if (data != 'OK') alert('Ups, no se ha guardado su cambio');
		});
 	});

  	$('.updateExtraCost').change(function(){
    	var id = $(this).attr('data-idBook');
    	var extraCost = $(this).val();
    	$.get( "/admin/sales/updateExtraCost/"+id+"/"+extraCost).done(function( data ) {
          if (data != 'OK') alert('Ups, no se ha guardado su cambio');
        });
    });


	$('.updateCostApto').change(function(){
    	var id = $(this).attr('data-idBook');
    	var costApto = $(this).val();
    	$.get( "/admin/sales/updateCostApto/"+id+"/"+costApto).done(function( data ) {
          if (data != 'OK') alert('Ups, no se ha guardado su cambio');
        });

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