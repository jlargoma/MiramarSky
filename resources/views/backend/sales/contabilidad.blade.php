<?php   use \Carbon\Carbon;  
        setlocale(LC_TIME, "ES"); 
        setlocale(LC_TIME, "es_ES"); 
?>
@extends('layouts.admin-master')

@section('title') Contabilidad  @endsection

@section('externalScripts') 
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.js"></script>

    <style>

    	.table-room tr td{
    		padding: 8px 10px!important;
    	}
    </style>
@endsection

@section('content')
<div class="container-fluid padding-5 sm-padding-10">
	<div class="row bg-white"></div>
	<div class="col-md-12 col-xs-12 push-20">
		
		@include('backend.sales._button-contabiliad')
	
	</div>
	<div class="col-md-6 col-md-offset-3">
		<h2 class="text-center">
			Contabilidad
		</h2>
	</div>

	<div class="col-md-3">
		<select id="fecha" class="form-control minimal">
		     <?php $fecha = $date->copy()->SubYear(2); ?>
		     <?php if ($fecha->copy()->format('Y') < 2015): ?>
		         <?php $fecha = new Carbon('first day of September 2015'); ?>
		     <?php endif ?>
		 
		     <?php for ($i=1; $i <= 3; $i++): ?>                           
		         <option value="<?php echo $fecha->copy()->format('Y'); ?>" {{ $date->copy()->format('Y') == $fecha->format('Y') ? 'selected' : '' }}>
		             <?php echo $fecha->copy()->format('Y')."-".$fecha->copy()->addYear()->format('Y'); ?> 
		         </option>
		         <?php $fecha->addYear(); ?>
		     <?php endfor; ?>
		 </select>  
	</div>
	<div style="clear: both;"></div>
	<div class="col-md-2">
	   <div>
	       <canvas id="barChart" style="width: 100%; height: 250px;"></canvas>
	   </div>
	</div>
    <div class="col-md-2">
    	<?php if ($date->copy()->format('n') >= 9): ?>
    	    <?php echo $date->copy()->format('Y') ?>-<?php echo $date->copy()->addYear()->format('Y') ?>
    	<?php else: ?>
    	    <?php echo $date->copy()->subYear()->format('Y') ?>-<?php echo $date->copy()->format('Y') ?>
    	<?php endif ?>

    	<table class="table table-hover demo-table-search table-responsive table-room" >
			<?php foreach ($rooms as $room): ?>
				<tr>
					<td><?php echo $room->name ?></td>
					<td><?php echo number_format(($totalRoom[$room->id]/$totalAño * 100),2,',','.')." %" ?>
                    </td>
				</tr>
			<?php endforeach ?>
		</table>

    </div>
	
	<div class="col-md-2">
	   <div>
	       <canvas id="barChart2" style="width: 100%; height: 250px;"></canvas>
	   </div>
	</div>
	
	<div class="col-md-3 pull-right">
	    <div class="row">
	        <div class="col-md-12">
	            <div class="widget-8 panel no-border bg-success no-margin widget-loader-bar">
	                <div class="container-xs-height full-height">
	                    <div class="row-xs-height">
	                        <div class="col-xs-height col-top">
	                            <div class="panel-heading top-left top-right">
	                                <div class="panel-title text-black hint-text">
	                                    <span class="font-montserrat fs-11 all-caps">Ingresos de la temporada 
	                                        <?php if ($date->copy()->format('n') >= 9): ?>
	                                            <?php echo $date->copy()->format('Y') ?>-<?php echo $date->copy()->addYear()->format('Y') ?>
	                                        <?php else: ?>
	                                            <?php echo $date->copy()->subYear()->format('Y') ?>-<?php echo $date->copy()->format('Y') ?>
	                                        <?php endif ?>
	                                    </span>
	                                </div>                                    
	                            </div>
	                        </div>
	                    </div>

	                    <div class="row-xs-height ">
	                        <div class="col-xs-height col-top relative">
	                            <div class="row">
	                                <div class="col-sm-6">
	                                    <div class="p-l-20">
	                                        <h3 class="no-margin p-b-5 text-white">
	                                            <?php if ($date->copy()->format('n') >= 9): ?>
	                                                <?php echo number_format($arrayTotales[$date->copy()->format('Y')],2,',','.') ?> €
	                                            <?php else: ?>
	                                                <?php echo number_format($arrayTotales[$date->copy()->subYear()->format('Y')],2,',','.') ?> €
	                                            <?php endif ?>                                            
	                                        </h3>
	                                        <p class="small hint-text m-t-5">
	                                            <span class="label  font-montserrat m-r-5">60%</span>Higher
	                                        </p>
	                                    </div>
	                                </div>
	                                <div class="col-sm-6">
	                                </div>
	                            </div>

	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>
		</div>
		<div class="row">
	        <div class="col-md-12">
	            <div class="widget-9 panel no-border bg-primary no-margin widget-loader-bar">
	                <div class="container-xs-height full-height">
	                    <div class="row-xs-height">
	                        <div class="col-xs-height col-top">
	                            <div class="panel-heading  top-left top-right">
	                                <div class="panel-title text-black">
	                                    <span class="font-montserrat fs-11 all-caps">Comparativa de la temporada 
	                                        <?php if ($date->copy()->format('n') >= 9): ?>
	                                            <?php echo $date->copy()->subYear()->format('Y') ?>-<?php echo $date->copy()->format('Y') ?>
	                                        <?php else: ?>
	                                            <?php echo $date->copy()->subYear(2)->format('Y') ?>-<?php echo $date->copy()->subYear()->format('Y') ?>
	                                        <?php endif ?>
	                                    </span>
	                                </div>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="row-xs-height">
	                        <div class="col-xs-height col-top">
	                            <div class="p-l-20 p-t-15">
	                                <h3 class="no-margin p-b-5 text-white">                                                
	                                        <?php if ($date->copy()->format('n') >= 9): ?>
	                                            <?php if (isset($arrayTotales[$date->copy()->subYear()->format('Y')])): ?>
	                                                <?php echo number_format($arrayTotales[$date->copy()->subYear()->format('Y')],2,',','.') ?> €
	                                            <?php else: ?>
	                                                No Hay reservas este año
	                                            <?php endif ?>
	                                        <?php else: ?>
	                                            <?php if (isset($arrayTotales[$date->copy()->subYear(2)->format('Y')])): ?>
	                                                <?php echo number_format($arrayTotales[$date->copy()->subYear(2)->format('Y')],2,',','.') ?> €
	                                            <?php else: ?>
	                                                No Hay reservas este año
	                                            <?php endif ?>
	                                        <?php endif ?> 
	                                    
	                                </h3>
	                                <span class="small hint-text">
	                                    <?php $totalcomparativa = 0; ?>
	                                    <?php if ($date->copy()->format('n') >= 9): ?>
	                                        <?php if (isset($arrayTotales[$date->copy()->format('Y')]) && isset($arrayTotales[$date->copy()->subYear()->format('Y')]) ) : ?>
	                                            <?php $totalcomparativa = number_format(($arrayTotales[$date->copy()->format('Y')]-$arrayTotales[$date->copy()->subYear()->format('Y')])/$arrayTotales[$date->copy()->subYear()->format('Y')] *100,2) ?>
	                                            <?php echo $totalcomparativa ?>% 
	                                            <?php if ($totalcomparativa > 100): ?>
	                                                <i class="fa fa-arrow-up text-success fa-2x"></i>
	                                            <?php else: ?>
	                                                <i class="fa fa-arrow-down text-danger fa-2x"></i>
	                                            <?php endif ?>   
	                                        <?php else: ?>
	                                            No Hay reservas este año
	                                        <?php endif ?>
	                                    <?php elseif (isset($arrayTotales[$date->copy()->subYear()->format('Y')]) && isset($arrayTotales[$date->copy()->subYear(2)->format('Y')]) ) : ?>
	                                        <?php $totalcomparativa = number_format(($arrayTotales[$date->copy()->subYear()->format('Y')]-$arrayTotales[$date->copy()->subYear(2)->format('Y')])/$arrayTotales[$date->copy()->subYear(2)->format('Y')] *100,2) ?>
	                                        <?php echo $totalcomparativa ?>% 
	                                        <?php if ($totalcomparativa > 100): ?>
	                                            <i class="fa fa-arrow-up text-success fa-2x"></i>
	                                        <?php else: ?>
	                                            <i class="fa fa-arrow-down text-danger fa-2x"></i>
	                                        <?php endif ?>   
	                                    <?php endif ?>
	                                </span>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="row-xs-height">
	                        <div class="col-xs-height col-bottom">
	                            <div class="progress progress-small m-b-20">

	                            <div class="progress-bar progress-bar-white" style="width:<?php echo $totalcomparativa ?>%"></div>

	                            </div>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
	<div style="clear: both;"></div>  
	
	<?php if ($date->copy()->format('n') >= 9): ?>
	    <?php $fecha= $date->copy()->format('Y')."-".$date->copy()->addYear()->format('Y') ?>
	<?php else: ?>
	    <?php $fecha = $date->copy()->subYear()->format('Y')."-".$date->copy()->format('Y') ?>
	<?php endif ?>

	<div class="row">
		<div class="row">
		    <div class="col-md-12">
		        <table class="table  table-condensed table-striped" style="margin-top: 0;">
					<thead>
						<th class="text-center bg-complete text-white">Apto</th>
						<th class="text-center bg-complete text-white">Nov/Dic</th>
						<th class="text-center bg-complete text-white">Ene</th>
						<th class="text-center bg-complete text-white">Feb</th>
						<th class="text-center bg-complete text-white">Mar</th>
						<th class="text-center bg-complete text-white">Abr/May</th>
						<th class="text-center bg-complete text-white">Total</th>
					</thead>
					<tbody>

						<?php foreach ($rooms as $room): ?>
							<tr>
								<?php $total[$room->id] = 0 ?>
								<th class="text-center p-t-5 p-b-5"><?php echo $room->name ?></th>
								<td class="text-center p-t-5 p-b-5">
									<?php if(isset($arrayPrices[$fecha][12][$room->id]["total_price"])){
											echo number_format($arrayPrices[$fecha][12][$room->id]["total_price"],2,',','.')."€";
											$total[$room->id] += $arrayPrices[$fecha][12][$room->id]["total_price"];
										}else{
											echo  "---";
										}   
									;?>												
								</td>
								<td class="text-center p-t-5 p-b-5">
									<?php if(isset($arrayPrices[$fecha][1][$room->id]["total_price"])){
											echo number_format($arrayPrices[$fecha][1][$room->id]["total_price"],2,',','.')."€";
											$total[$room->id] += $arrayPrices[$fecha][1][$room->id]["total_price"];
										}else{
											echo  "---";
										}   
									;?>										
								</td>
								<td class="text-center p-t-5 p-b-5">
									<?php if(isset($arrayPrices[$fecha][2][$room->id]["total_price"])){
											echo number_format($arrayPrices[$fecha][2][$room->id]["total_price"],2,',','.')."€";
											$total[$room->id] += $arrayPrices[$fecha][2][$room->id]["total_price"];
										}else{
											echo  "---";
										}   
									;?>								
								</td>
								<td class="text-center p-t-5 p-b-5">
									<?php if(isset($arrayPrices[$fecha][3][$room->id]["total_price"])){
											echo number_format($arrayPrices[$fecha][3][$room->id]["total_price"],2,',','.')."€";
											$total[$room->id] += $arrayPrices[$fecha][3][$room->id]["total_price"];
										}else{
											echo  "---";
										}   
									;?>
								</td>
								<td class="text-center p-t-5 p-b-5">
									<?php if(isset($arrayPrices[$fecha][4][$room->id]["total_price"])){
											echo number_format($arrayPrices[$fecha][4][$room->id]["total_price"],2,',','.')."€";
											$total[$room->id] += $arrayPrices[$fecha][4][$room->id]["total_price"];
										}else{
											echo  "---";
										}   
									;?>								
								</td>
								<td class="text-center p-t-5 p-b-5">
									<?php echo number_format($total[$room->id],2,',','.')."€" ?>
								</td>
							</tr>
						<?php endforeach ?>
						<tr>
							<th colspan="6" class="text-right bg-complete text-white">TOTAL</th>
							<td class="text-center"><?php echo number_format(array_sum($total),2,',','.'); ?></td>
						</tr>
					</tbody>
		        </table>
<!-- 		        <table class="table table-hover demo-table-search table-responsive " >
		            <thead>
		                <th class="text-center bg-complete text-white" colspan="7">Ingresos de la temporada <?php //echo $inicio->copy()->format('Y') ?>-<?php //echo $inicio->copy()->addYear()->format('Y') ?></th>
		            </thead>
		            <thead>
		                <th class="text-center bg-complete text-white">&nbsp;</th>
		                <th class="text-center bg-complete text-white">Nov/Dic</th>
		                <th class="text-center bg-complete text-white">Ene</th>
		                <th class="text-center bg-complete text-white">Feb</th>
		                <th class="text-center bg-complete text-white">Mar</th>
		                <th class="text-center bg-complete text-white">Abr/May</th>
		                <th class="text-center bg-complete text-white">Total&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
		            </thead>
		            <tbody>
		                <tr>
		                    <th class="text-center p-t-5 p-b-5">Ventas</th>
		                    <td class="text-center p-t-5 p-b-5"><?php //echo number_format($ventas["Ventas"][12],2,',','.')?></td>
		                    <td class="text-center p-t-5 p-b-5"><?php //echo number_format($ventas["Ventas"][1],2,',','.')?></td>
		                    <td class="text-center p-t-5 p-b-5"><?php //echo number_format($ventas["Ventas"][2],2,',','.')?></td>
		                    <td class="text-center p-t-5 p-b-5"><?php //echo number_format($ventas["Ventas"][3],2,',','.')?></td>
		                    <td class="text-center p-t-5 p-b-5"><?php //echo number_format($ventas["Ventas"][4],2,',','.')?></td>
		                    <td class="text-center p-t-5 p-b-5"><?php //echo number_format(array_sum($ventas["Ventas"]),2,',','.')?></td>
		                </tr>
		                <tr>
		                    <th class="text-center p-t-5 p-b-5">Benº</th>
		                    <td class="text-center p-t-5 p-b-5"><?php //echo number_format($ventas["Ben"][12],2,',','.')?></td>
		                    <td class="text-center p-t-5 p-b-5"><?php //echo number_format($ventas["Ben"][1],2,',','.')?></td>
		                    <td class="text-center p-t-5 p-b-5"><?php //echo number_format($ventas["Ben"][2],2,',','.')?></td>
		                    <td class="text-center p-t-5 p-b-5"><?php //echo number_format($ventas["Ben"][3],2,',','.')?></td>
		                    <td class="text-center p-t-5 p-b-5"><?php //echo number_format($ventas["Ben"][4],2,',','.')?></td>
		                    <td class="text-center p-t-5 p-b-5"><?php //echo number_format(array_sum($ventas["Ben"]),2,',','.')?></td>
		                </tr>
		                <thead>
		                    <th colspan="7" class="ingresos_temp">Ingresos de la temporada <?php //echo $inicio->copy()->subYear()->format('Y') ?>-<?php //echo $inicio->copy()->format('Y') ?> </th>
		                </thead>
		                <tr>
		                    <th class="text-center ingresos_temp text-white"></th>
		                    <th class="text-center ingresos_temp text-white">Nov/Dic</th>
		                    <th class="text-center ingresos_temp text-white">Ene</th>
		                    <th class="text-center ingresos_temp text-white">Feb</th>
		                    <th class="text-center ingresos_temp text-white">Mar</th>
		                    <th class="text-center ingresos_temp text-white">Abr/May</th>
		                    <th class="text-center ingresos_temp text-white">Total&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
		                </tr>
		                <tr>
		                    <th class="text-center">Ventas</th>
		                    <td class="text-center"><?php //echo number_format($ventasOld["Ventas"][12],2,',','.') ?></td>
		                    <td class="text-center"><?php //echo number_format($ventasOld["Ventas"][1],2,',','.') ?></td>
		                    <td class="text-center"><?php //echo number_format($ventasOld["Ventas"][2],2,',','.') ?></td>
		                    <td class="text-center"><?php //echo number_format($ventasOld["Ventas"][3],2,',','.') ?></td>
		                    <td class="text-center"><?php //echo number_format($ventasOld["Ventas"][4],2,',','.') ?></td>
		                    <td class="text-center"><?php //echo number_format(array_sum($ventasOld["Ventas"]),2,',','.') ?></td>
		                </tr>
		                <tr>
		                    <th class="text-center">Benº</th>
		                    <td class="text-center"><?php //echo number_format($ventasOld["Ben"][12],2,',','.') ?></td>
		                    <td class="text-center"><?php //echo number_format($ventasOld["Ben"][1],2,',','.') ?></td>
		                    <td class="text-center"><?php //echo number_format($ventasOld["Ben"][2],2,',','.') ?></td>
		                    <td class="text-center"><?php //echo number_format($ventasOld["Ben"][3],2,',','.') ?></td>
		                    <td class="text-center"><?php //echo number_format($ventasOld["Ben"][4],2,',','.') ?></td>
		                    <td class="text-center"><?php //echo number_format(array_sum($ventasOld["Ben"]),2,',','.') ?></td>
		                </tr>
		                <thead>
		                    <th colspan="7" class="comparativa bg-primary text-center text-white" >Comparativa de la temporada <?php //echo $inicio->copy()->subYear()->format('Y') ?>-<?php //echo $inicio->copy()->format('Y') ?> </th>
		                </thead>
		                <tr>
		                    <th class="text-center  bg-primary text-white"></th>
		                    <th class="text-center  bg-primary text-white">Nov/Dic</th>
		                    <th class="text-center  bg-primary text-white">Ene</th>
		                    <th class="text-center  bg-primary text-white">Feb</th>
		                    <th class="text-center  bg-primary text-white">Mar</th>
		                    <th class="text-center  bg-primary text-white">Abr/May</th>
		                    <th class="text-center  bg-primary text-white">Total&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
		                </tr>
		                <tr>
		                    <th class="text-center">Comp. Ventas</th>
		                    <td class="text-center">
		                        <?php //echo number_format($ventas["Ventas"][12]-$ventasOld["Ventas"][12],2,',','.');
		                            //echo ($ventas["Ventas"][12]-$ventasOld["Ventas"][12] > 0) ? "<i class='fa fa-arrow-up' aria-hidden='true'></i>" : "<i class='fa fa-arrow-down' aria-hidden='true'></i>";
		                             ?>
		                    </td>
		                    <td class="text-center">
		                        <?php //echo number_format($ventas["Ventas"][1]-$ventasOld["Ventas"][1],2,',','.');
		                            //echo ($ventas["Ventas"][1]-$ventasOld["Ventas"][1] > 0) ? "<i class='fa fa-arrow-up' aria-hidden='true'></i>" : "<i class='fa fa-arrow-down' aria-hidden='true'></i>";
		                             ?>
		                    </td>
		                    <td class="text-center">
		                        <?php //echo number_format($ventas["Ventas"][2]-$ventasOld["Ventas"][2],2,',','.');
		                            //echo ($ventas["Ventas"][2]-$ventasOld["Ventas"][2] > 0) ? "<i class='fa fa-arrow-up' aria-hidden='true'></i>" : "<i class='fa fa-arrow-down' aria-hidden='true'></i>";
		                             ?>
		                    </td>
		                    <td class="text-center">
		                        <?php //echo number_format($ventas["Ventas"][3]-$ventasOld["Ventas"][3],2,',','.');
		                            //echo ($ventas["Ventas"][3]-$ventasOld["Ventas"][3] > 0) ? "<i class='fa fa-arrow-up' aria-hidden='true'></i>" : "<i class='fa fa-arrow-down' aria-hidden='true'></i>";
		                             ?>
		                    </td>
		                    <td class="text-center">
		                        <?php //echo number_format($ventas["Ventas"][4]-$ventasOld["Ventas"][4],2,',','.');
		                            //echo ($ventas["Ventas"][4]-$ventasOld["Ventas"][4] > 0) ? "<i class='fa fa-arrow-up' aria-hidden='true'></i>" : "<i class='fa fa-arrow-down' aria-hidden='true'></i>";
		                             ?>
		                    </td>
		                    <td class="text-center">
		                        <?php //echo number_format(array_sum($ventas["Ventas"])-array_sum($ventasOld["Ventas"]),2,',','.');
		                            //echo (array_sum($ventas["Ventas"])-array_sum($ventasOld["Ventas"]) > 0) ? "<i class='fa fa-arrow-up' aria-hidden='true'></i>" : "<i class='fa fa-arrow-down' aria-hidden='true'></i>";
		                             ?>
		                    </td>
		                </tr>
		                <tr>
		                    <th class="text-center">Comp. Benº</th>
		                    <td class="text-center">
		                        <?php //echo number_format($ventas["Ben"][12]-$ventasOld["Ben"][12],2,',','.');
		                            //echo ($ventas["Ben"][12]-$ventasOld["Ben"][12] > 0) ? "<i class='fa fa-arrow-up' aria-hidden='true'></i>" : "<i class='fa fa-arrow-down' aria-hidden='true'></i>";
		                             ?>
		                    </td>
		                    <td class="text-center">
		                        <?php //echo number_format($ventas["Ben"][1]-$ventasOld["Ben"][1],2,',','.');
		                            //echo ($ventas["Ben"][1]-$ventasOld["Ben"][1] > 0) ? "<i class='fa fa-arrow-up' aria-hidden='true'></i>" : "<i class='fa fa-arrow-down' aria-hidden='true'></i>";
		                             ?>
		                    </td>
		                    <td class="text-center">
		                        <?php //echo number_format($ventas["Ben"][2]-$ventasOld["Ben"][2],2,',','.');
		                            //echo ($ventas["Ben"][2]-$ventasOld["Ben"][2] > 0) ? "<i class='fa fa-arrow-up' aria-hidden='true'></i>" : "<i class='fa fa-arrow-down' aria-hidden='true'></i>";
		                             ?>
		                    </td>
		                    <td class="text-center">
		                        <?php //echo number_format($ventas["Ben"][3]-$ventasOld["Ben"][3],2,',','.');
		                            //echo ($ventas["Ben"][3]-$ventasOld["Ben"][3] > 0) ? "<i class='fa fa-arrow-up' aria-hidden='true'></i>" : "<i class='fa fa-arrow-down' aria-hidden='true'></i>";
		                             ?>
		                    </td>
		                    <td class="text-center">
		                        <?php //echo number_format($ventas["Ben"][4]-$ventasOld["Ben"][4],2,',','.');
		                            //echo ($ventas["Ben"][4]-$ventasOld["Ben"][4] > 0) ? "<i class='fa fa-arrow-up' aria-hidden='true'></i>" : "<i class='fa fa-arrow-down' aria-hidden='true'></i>";
		                             ?>
		                    </td>
		                    <td class="text-center">
		                        <?php //echo number_format(array_sum($ventas["Ben"])-array_sum($ventasOld["Ben"]),2,',','.');
		                            //echo (array_sum($ventas["Ben"])-array_sum($ventasOld["Ben"]) > 0) ? "<i class='fa fa-arrow-up' aria-hidden='true'></i>" : "<i class='fa fa-arrow-down' aria-hidden='true'></i>";
		                             ?>
		                    </td>
		                </tr>
		            </tbody>
		        </table> -->
		    </div>
		    <div class="col-md-12">

    	        <table class="table  table-condensed table-striped" style="margin-top: 0;">
    				<thead>
    					<th class="text-center bg-complete text-white">Apto</th>
    					<th class="text-center bg-complete text-white">Nov</th>
    					<th class="text-center bg-complete text-white">Dic</th>
    					<th class="text-center bg-complete text-white">Ene</th>
    					<th class="text-center bg-complete text-white">Feb</th>
    					<th class="text-center bg-complete text-white">Mar</th>
    					<th class="text-center bg-complete text-white">Abr</th>
    					<th class="text-center bg-complete text-white">May</th>
    					<th class="text-center bg-complete text-white">Total</th>
    				</thead>
    				<tbody>
    					<tr>
    						<?php 
    							$totalBanco = 0;
    							$totalBancoMes = 0;
    						 ?>

    						<td >Banco</td>
    						<td><?php echo (isset($arrayCobro["banco"][11])) ? number_format($arrayCobro["banco"][11],2,',','.')."€" : "---"; ?></td>
    						<td><?php echo (isset($arrayCobro["banco"][12])) ? number_format($arrayCobro["banco"][12],2,',','.')."€" : "---"; ?></td>
    						<td ><?php echo (isset($arrayCobro["banco"][1])) ? number_format($arrayCobro["banco"][1],2,',','.')."€" : "---"; ?></td>
    						<td ><?php echo (isset($arrayCobro["banco"][2])) ? number_format($arrayCobro["banco"][2],2,',','.')."€" : "---"; ?></td>
    						<td ><?php echo (isset($arrayCobro["banco"][3])) ? number_format($arrayCobro["banco"][3],2,',','.')."€" : "---"; ?></td>
    						<td ><?php echo (isset($arrayCobro["banco"][4])) ? number_format($arrayCobro["banco"][4],2,',','.')."€" : "---"; ?></td>
    						<td ><?php echo (isset($arrayCobro["banco"][5])) ? number_format($arrayCobro["banco"][5],2,',','.')."€" : "---"; ?></td>
    						<td ><?php echo number_format(array_sum($arrayCobro["banco"]),2,',','.')."€" ?></td>
    					</tr>
    					<tr>
    						<td >Metalico</td>
    						<td><?php echo (isset($arrayCobro["metalico"][11])) ? number_format($arrayCobro["metalico"][11],2,',','.')."€" : "---"; ?></td>
    						<td><?php echo (isset($arrayCobro["metalico"][12])) ? number_format($arrayCobro["metalico"][12],2,',','.')."€" : "---"; ?></td>
    						<td ><?php echo (isset($arrayCobro["metalico"][1])) ? number_format($arrayCobro["metalico"][1],2,',','.')."€" : "---"; ?></td>
    						<td ><?php echo (isset($arrayCobro["metalico"][2])) ? number_format($arrayCobro["metalico"][2],2,',','.')."€" : "---"; ?></td>
    						<td ><?php echo (isset($arrayCobro["metalico"][3])) ? number_format($arrayCobro["metalico"][3],2,',','.')."€" : "---"; ?></td>
    						<td ><?php echo (isset($arrayCobro["metalico"][4])) ? number_format($arrayCobro["metalico"][4],2,',','.')."€" : "---"; ?></td>
    						<td ><?php echo (isset($arrayCobro["metalico"][5])) ? number_format($arrayCobro["metalico"][5],2,',','.')."€" : "---"; ?></td>
    						<td ><?php echo number_format(array_sum($arrayCobro["metalico"]),2,',','.')."€" ?></td>
    					</tr>
						<tr>
							<th class="text-right bg-complete text-white">Totales</th>
							<td>
								<?php 	(isset($arrayCobro["metalico"][11])) ? $total[11] = $arrayCobro["metalico"][11]: $total[11] = 0; 
										(isset($arrayCobro["banco"][11])) ? $total[11] += $arrayCobro["banco"][11] : 0 ;
										echo number_format($total[11],2,',','.')."€";
								?>
							</td>
							<td>
								<?php 	(isset($arrayCobro["metalico"][12])) ? $total[12] = $arrayCobro["metalico"][12]: $total[12] = 0; 
										(isset($arrayCobro["banco"][12])) ? $total[12] += $arrayCobro["banco"][12] : 0 ;
										echo number_format($total[12],2,',','.')."€";
								?>
							</td>
							<td>
								<?php 	(isset($arrayCobro["metalico"][1])) ? $total[1] = $arrayCobro["metalico"][1]: $total[1] = 0; 
										(isset($arrayCobro["banco"][1])) ? $total[1] += $arrayCobro["banco"][1] : 0 ;
										echo number_format($total[1],2,',','.')."€";
								?>
							</td>
							<td>
								<?php 	(isset($arrayCobro["metalico"][2])) ? $total[2] = $arrayCobro["metalico"][2]: $total[2] = 0; 
										(isset($arrayCobro["banco"][2])) ? $total[2] += $arrayCobro["banco"][2] : 0 ;
										echo number_format($total[2],2,',','.')."€";
								?>
							</td>
							<td>
								<?php 	(isset($arrayCobro["metalico"][3])) ? $total[3] = $arrayCobro["metalico"][3]: $total[3] = 0; 
										(isset($arrayCobro["banco"][3])) ? $total[3] += $arrayCobro["banco"][3] : 0 ;
										echo number_format($total[3],2,',','.')."€";
								?>
							</td>
							<td>
								<?php 	(isset($arrayCobro["metalico"][4])) ? $total[4] = $arrayCobro["metalico"][4]: $total[4] = 0; 
										(isset($arrayCobro["banco"][4])) ? $total[4] += $arrayCobro["banco"][4] : 0 ;
										echo number_format($total[4],2,',','.')."€";
								?>
							</td>
							<td>
								<?php 	(isset($arrayCobro["metalico"][5])) ? $total[5] = $arrayCobro["metalico"][5]: $total[5] = 0; 
										(isset($arrayCobro["banco"][5])) ? $total[5] += $arrayCobro["banco"][5] : 0 ;
										echo number_format($total[5],2,',','.')."€";
								?>
							</td>
							<td>
								<?php echo number_format($total[11]+$total[12]+$total[1]+$total[2]+$total[3]+$total[4]+$total[5],2,',','.')."€" ?>
							</td>
						</tr>
    				</tbody>
    			</table>	
		    </div>

		    <div class="col-md-12">
    	        <table class="table  table-condensed table-striped" style="margin-top: 0;">
    				<thead>
    				</thead>
    			</table>
		    </div>
		</div>
	</div>  
</div>
@endsection

@section('scripts')
<script src="/assets/plugins/jquery-datatable/media/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="/assets/plugins/jquery-datatable/extensions/TableTools/js/dataTables.tableTools.min.js" type="text/javascript"></script>
<script src="/assets/plugins/jquery-datatable/media/js/dataTables.bootstrap.js" type="text/javascript"></script>
<script src="/assets/plugins/jquery-datatable/extensions/Bootstrap/jquery-datatable-bootstrap.js" type="text/javascript"></script>
<script type="text/javascript" src="/assets/plugins/datatables-responsive/js/datatables.responsive.js"></script>
<script type="text/javascript" src="/assets/plugins/datatables-responsive/js/lodash.min.js"></script>

<script src="/assets/plugins/bootstrap3-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<script type="text/javascript" src="/assets/plugins/jquery-autonumeric/autoNumeric.js"></script>
<script type="text/javascript" src="/assets/plugins/dropzone/dropzone.min.js"></script>
<script type="text/javascript" src="/assets/plugins/bootstrap-tag/bootstrap-tagsinput.min.js"></script>
<script type="text/javascript" src="/assets/plugins/jquery-inputmask/jquery.inputmask.min.js"></script>
<script src="/assets/plugins/bootstrap-form-wizard/js/jquery.bootstrap.wizard.min.js" type="text/javascript"></script>
<script src="/assets/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="/assets/plugins/summernote/js/summernote.min.js" type="text/javascript"></script>
<script src="/assets/plugins/moment/moment.min.js"></script>
<script src="/assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="/assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
<script src="/assets/plugins/bootstrap-typehead/typeahead.bundle.min.js"></script>
<script src="/assets/plugins/bootstrap-typehead/typeahead.jquery.min.js"></script>
<script src="/assets/plugins/handlebars/handlebars-v4.0.5.js"></script>
<script type="text/javascript">

	$('#fecha').change(function(event) {
	    
	    var year = $(this).val();
	    window.location = '/admin/contabilidad/'+year;

	});

    var data = {
        labels: [
                    <?php foreach ($arrayTotales as $key => $value): ?>
                        <?php echo "'".$key."'," ?>
                    <?php endforeach ?>
                ],
        datasets: [
            {
                label: "Ingresos por Año",
                backgroundColor: [
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(54, 162, 235, 1)',
                ],
                borderWidth: 1,
                data: [
                        <?php foreach ($arrayTotales as $key => $value): ?>
                            <?php echo "'".$value."'," ?>
                        <?php endforeach ?>
                        ],
            }
        ]
    };

    var myBarChart = new Chart('barChart', {
        type: 'line',
        data: data,
    });

    var myBarChart = new Chart('barChart2', {
        type: 'bar',
        data: data,
    });
</script>

@endsection