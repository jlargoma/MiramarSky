@extends('layouts.admin-master')

@section('title') Estadísticas  @endsection

@section('externalScripts') 
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.js"></script>


@endsection

@section('content')

<style>
	.table-ingresos , .table-ingresos >tbody> tr > td{
		background-color: #92B6E2!important;
		margin: 0px ;
		padding: 5px 8px;
	}
	.table-cobros , .table-cobros >tbody> tr > td{
		background-color: #38C8A7!important;
		margin: 0px ;
		padding: 5px 8px;
	}
	.tr-cobros:hover{
		background-color: #2ca085!important;
	}
	.tr-cobros:hover td {
		background-color: #2ca085!important;
	}
	.fa-arrow-up{
		color: green;
	}
	.fa-arrow-down{
		color: red;
	}
	.bg-complete-grey{
		background-color: #92B6E2!important;
	}
</style>
<div class="container-fluid padding-5 sm-padding-10">
	<div class="row bg-white"></div>
	<div class="col-md-12 col-xs-12 push-20">
		
		@include('backend.sales._button-contabiliad')
	
	</div>
	<div class="col-md-12">
		<h2 class="text-center">
			Estadisticas
		</h2>
	</div>
	<div class="col-md-2">
	   <div>
	       <canvas id="barChart" style="width: 100%; height: 250px;"></canvas>
	   </div>
	</div>

	<?php if ($date->copy()->format('n') >= 9): ?>
	    <?php $fecha = $date->copy()->format('Y')."-".$date->copy()->addYear()->format('Y') ?>
	<?php else: ?>
	    <?php $fecha= $date->copy()->subYear()->format('Y')."-".$date->copy()->format('Y') ?>
	<?php endif ?>

	<div class="col-md-2">
		
		<table class="table table-hover demo-table-search table-responsive table-ingresos" style="background-color: #92B6E2">
			<thead style="background-color: #92B6E2">
				<th colspan="2" class="text-white text-center"> Ingresos Temporada</th>
			</thead>
			<tbody style="background-color: #92B6E2">
				<tr>
					<td class="text-white">Ventas Temporada</td>
					<td class="text-white">
						<?php if ($date->copy()->format('n') >= 9): ?>
						    <?php echo number_format($arrayTotales[$date->copy()->format('Y')],2,',','.') ?> €
						<?php else: ?>
						    <?php echo number_format($arrayTotales[$date->copy()->subYear()->format('Y')],2,',','.') ?> €
						<?php endif ?>  
					</td>
				</tr>
				<tr>
					<td class="text-white">Cobrado Temporada</td>
					<td class="text-white"><?php echo (isset($arrayCobro[$fecha])) ? number_format($arrayCobro[$fecha],2,',','.') : "---" ?> </td>
				</tr>
				<tr>
					<td class="text-white">Pendiente Cobro</td>
					<td class="text-white">
						<?php if ($date->copy()->format('n') >= 9): ?>
						    <?php echo (isset($arrayCobro[$fecha])) ? number_format($arrayTotales[$date->copy()->format('Y')]-$arrayCobro[$fecha],2,',','.'): "---" ?> €
						<?php else: ?>
						    <?php echo (isset($arrayCobro[$fecha])) ? number_format($arrayTotales[$date->copy()->subYear()->format('Y')]-$arrayCobro[$fecha],2,',','.'): "---" ?> €
						<?php endif ?> 
					</td>
				</tr>
			</tbody>
		</table>

	   	<div>
	       	<canvas id="pieIng" style="width: 100%; height: 250px;"></canvas>
	   	</div>
	</div>

	<div class="col-md-2">

		<table class="table table-hover demo-table-search table-responsive table-cobros" style="background-color: #38C8A7">
			<thead style="background-color: #38C8A7">
				<th colspan="2" class="text-white text-center">Cobros Temporada</th>
			</thead>
			<tbody style="background-color: #38C8A7">
				<tr class="tr-cobros">
					<td class="text-white">Metalico</td>
					<td class="text-white">
						<?php echo (isset($arrayMetodo["metalico"][$fecha]))? number_format($arrayMetodo["metalico"][$fecha],2,',','.') : "---"  ?>  
					</td>
				</tr>
				<tr class="tr-cobros">
					<td class="text-white">Banco</td>
					<td class="text-white">
						<?php echo (isset($arrayMetodo["banco"][$fecha]))? number_format($arrayMetodo["banco"][$fecha],2,',','.') : "---"  ?>  
					 </td>
				</tr>
				<tr class="tr-cobros">
					<th class="text-white">TOTAL COBRADO</th>
					<th class="text-white"><?php echo (isset($arrayCobro[$fecha])) ? number_format($arrayCobro[$fecha],2,',','.') : "---" ?> 
					</th>
				</tr>
			</tbody>
		</table>

		<div>
		    <canvas id="pieCobros" style="width: 100%; height: 250px;"></canvas>
		</div>
	</div>

	<div class="col-md-6 pull-right">
	    <div class="row">
		<!--<div class="col-md-12">
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
	   	-->
	        <div class="col-md-12">
	        	<table class="table table-hover demo-table-search table-responsive " >
<!-- 	        	    <thead>
	        	        <th class="text-center bg-complete text-white" colspan="7">Ingresos de la temporada <?php echo $inicio->copy()->format('Y') ?>-<?php echo $inicio->copy()->addYear()->format('Y') ?></th>
	        	    </thead> -->
	        	    <thead>
	        	        <th class="text-center bg-complete text-white"><?php echo $inicio->copy()->format('Y') ?>-<?php echo $inicio->copy()->addYear()->format('Y') ?></th>
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
	        	            <td class="text-center p-t-5 p-b-5"><?php echo number_format($ventas[$inicio->copy()->format('Y')][12]["ventas"],0,',','.')?></td>
	        	            <td class="text-center p-t-5 p-b-5"><?php echo number_format($ventas[$inicio->copy()->format('Y')][1]["ventas"],0,',','.')?></td>
	        	            <td class="text-center p-t-5 p-b-5"><?php echo number_format($ventas[$inicio->copy()->format('Y')][2]["ventas"],0,',','.')?></td>
	        	            <td class="text-center p-t-5 p-b-5"><?php echo number_format($ventas[$inicio->copy()->format('Y')][3]["ventas"],0,',','.')?></td>
	        	            <td class="text-center p-t-5 p-b-5"><?php echo number_format($ventas[$inicio->copy()->format('Y')][4]["ventas"],0,',','.')?></td>
	        	            <td class="text-center p-t-5 p-b-5"><?php //echo number_format(array_sum($ventas["Ventas"]),2,',','.')?></td>
	        	        </tr>
	        	        <tr>
	        	            <th class="text-center p-t-5 p-b-5">Benº</th>
	        	            <td class="text-center p-t-5 p-b-5"><?php echo number_format($ventas[$inicio->copy()->format('Y')][12]["beneficio"],0,',','.')?></td>
	        	            <td class="text-center p-t-5 p-b-5"><?php echo number_format($ventas[$inicio->copy()->format('Y')][1]["beneficio"],0,',','.')?></td>
	        	            <td class="text-center p-t-5 p-b-5"><?php echo number_format($ventas[$inicio->copy()->format('Y')][2]["beneficio"],0,',','.')?></td>
	        	            <td class="text-center p-t-5 p-b-5"><?php echo number_format($ventas[$inicio->copy()->format('Y')][3]["beneficio"],0,',','.')?></td>
	        	            <td class="text-center p-t-5 p-b-5"><?php echo number_format($ventas[$inicio->copy()->format('Y')][4]["beneficio"],0,',','.')?></td>
	        	            <td class="text-center p-t-5 p-b-5"><?php //echo number_format(array_sum($ventas["Ventas"]),2,',','.')?></td>
	        	        </tr>
	        	    </tbody>
<!--         	        <thead>
        	            <th class="text-center bg-complete-grey text-white" colspan="7">Ingresos de la temporada <?php echo $inicio->copy()->subYear()->format('Y') ?>-<?php echo $inicio->copy()->format('Y') ?></th>
        	        </thead> -->
        	        <thead>
        	            <th class="text-center bg-complete-grey text-white"><?php echo $inicio->copy()->subYear()->format('Y') ?>-<?php echo $inicio->copy()->format('Y') ?></th>
        	            <th class="text-center bg-complete-grey text-white">Nov/Dic</th>
        	            <th class="text-center bg-complete-grey text-white">Ene</th>
        	            <th class="text-center bg-complete-grey text-white">Feb</th>
        	            <th class="text-center bg-complete-grey text-white">Mar</th>
        	            <th class="text-center bg-complete-grey text-white">Abr/May</th>
        	            <th class="text-center bg-complete-grey text-white">Total&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
        	        </thead>
        	        <tbody>
	        	        <tr>
	        	            <th class="text-center p-t-5 p-b-5">Ventas</th>
	        	            <td class="text-center p-t-5 p-b-5"><?php echo number_format($ventas[$inicio->copy()->subYear()->format('Y')][12]["ventas"],0,',','.')?></td>
	        	            <td class="text-center p-t-5 p-b-5"><?php echo number_format($ventas[$inicio->copy()->subYear()->format('Y')][1]["ventas"],0,',','.')?></td>
	        	            <td class="text-center p-t-5 p-b-5"><?php echo number_format($ventas[$inicio->copy()->subYear()->format('Y')][2]["ventas"],0,',','.')?></td>
	        	            <td class="text-center p-t-5 p-b-5"><?php echo number_format($ventas[$inicio->copy()->subYear()->format('Y')][3]["ventas"],0,',','.')?></td>
	        	            <td class="text-center p-t-5 p-b-5"><?php echo number_format($ventas[$inicio->copy()->subYear()->format('Y')][4]["ventas"],0,',','.')?></td>
	        	            <td class="text-center p-t-5 p-b-5"><?php //echo number_format(array_sum($ventas["Ventas"]),2,',','.')?></td>
	        	        </tr>
	        	        <tr>
	        	            <th class="text-center p-t-5 p-b-5">Benº</th>
	        	            <td class="text-center p-t-5 p-b-5"><?php echo number_format($ventas[$inicio->copy()->subYear()->format('Y')][12]["beneficio"],0,',','.')?></td>
	        	            <td class="text-center p-t-5 p-b-5"><?php echo number_format($ventas[$inicio->copy()->subYear()->format('Y')][1]["beneficio"],0,',','.')?></td>
	        	            <td class="text-center p-t-5 p-b-5"><?php echo number_format($ventas[$inicio->copy()->subYear()->format('Y')][2]["beneficio"],0,',','.')?></td>
	        	            <td class="text-center p-t-5 p-b-5"><?php echo number_format($ventas[$inicio->copy()->subYear()->format('Y')][3]["beneficio"],0,',','.')?></td>
	        	            <td class="text-center p-t-5 p-b-5"><?php echo number_format($ventas[$inicio->copy()->subYear()->format('Y')][4]["beneficio"],0,',','.')?></td>
	        	            <td class="text-center p-t-5 p-b-5"><?php //echo number_format(array_sum($ventas["Ventas"]),2,',','.')?></td>
	        	        </tr>
<!-- 	        	        <thead>
	        	            <th colspan="7" class="comparativa bg-primary text-center text-white" >Comparativa de la temporada <?php echo $inicio->copy()->subYear()->format('Y') ?>-<?php echo $inicio->copy()->format('Y') ?> </th>
	        	        </thead> -->
	        	        <tr>
	        	            <th class="text-center  bg-primary text-white"><?php echo $inicio->copy()->subYear()->format('Y') ?>-<?php echo $inicio->copy()->format('Y') ?></th>
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
	        	            	<?php echo number_format(($ventas[$inicio->copy()->format('Y')][12]["ventas"] - $ventas[$inicio->copy()->subYear()->format('Y')][12]["ventas"]),0,',','.')?>
	        	                <?php 
	        	                    echo ($ventas[$inicio->copy()->format('Y')][12]["ventas"] - $ventas[$inicio->copy()->subYear()->format('Y')][12]["ventas"] > 0) ? "<i class='fa fa-arrow-up' aria-hidden='true'></i>" : "<i class='fa fa-arrow-down' aria-hidden='true'></i>";
	        	                     ?>
	        	            </td>
	        	            <td class="text-center">
	        	            	<?php echo number_format(($ventas[$inicio->copy()->format('Y')][1]["ventas"] - $ventas[$inicio->copy()->subYear()->format('Y')][1]["ventas"]),0,',','.')?>
	        	                <?php 
	        	                    echo ($ventas[$inicio->copy()->format('Y')][1]["ventas"] - $ventas[$inicio->copy()->subYear()->format('Y')][1]["ventas"] > 0) ? "<i class='fa fa-arrow-up' aria-hidden='true'></i>" : "<i class='fa fa-arrow-down' aria-hidden='true'></i>";
	        	                     ?>
	        	            </td>
	        	            <td class="text-center">
	        	            	<?php echo number_format(($ventas[$inicio->copy()->format('Y')][2]["ventas"] - $ventas[$inicio->copy()->subYear()->format('Y')][2]["ventas"]),0,',','.')?>
	        	                <?php 
	        	                    echo ($ventas[$inicio->copy()->format('Y')][2]["ventas"] - $ventas[$inicio->copy()->subYear()->format('Y')][2]["ventas"] > 0) ? "<i class='fa fa-arrow-up' aria-hidden='true'></i>" : "<i class='fa fa-arrow-down' aria-hidden='true'></i>";
	        	                     ?>
	        	            </td>
	        	            <td class="text-center">
	        	            	<?php echo number_format(($ventas[$inicio->copy()->format('Y')][3]["ventas"] - $ventas[$inicio->copy()->subYear()->format('Y')][3]["ventas"]),0,',','.')?>
	        	                <?php 
	        	                    echo ($ventas[$inicio->copy()->format('Y')][3]["ventas"] - $ventas[$inicio->copy()->subYear()->format('Y')][3]["ventas"] > 0) ? "<i class='fa fa-arrow-up' aria-hidden='true'></i>" : "<i class='fa fa-arrow-down' aria-hidden='true'></i>";
	        	                     ?>
	        	            </td>
	        	            <td class="text-center">
	        	            	<?php echo number_format(($ventas[$inicio->copy()->format('Y')][4]["ventas"] - $ventas[$inicio->copy()->subYear()->format('Y')][4]["ventas"]),0,',','.')?>
	        	                <?php 
	        	                    echo ($ventas[$inicio->copy()->format('Y')][4]["ventas"] - $ventas[$inicio->copy()->subYear()->format('Y')][4]["ventas"] > 0) ? "<i class='fa fa-arrow-up' aria-hidden='true'></i>" : "<i class='fa fa-arrow-down' aria-hidden='true'></i>";
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
	        	            	<?php echo number_format(($ventas[$inicio->copy()->format('Y')][12]["beneficio"] - $ventas[$inicio->copy()->subYear()->format('Y')][12]["beneficio"]),0,',','.')?>
	        	                <?php 
	        	                    echo ($ventas[$inicio->copy()->format('Y')][12]["beneficio"] - $ventas[$inicio->copy()->subYear()->format('Y')][12]["beneficio"] > 0) ? "<i class='fa fa-arrow-up' aria-hidden='true'></i>" : "<i class='fa fa-arrow-down' aria-hidden='true'></i>";
	        	                     ?>
	        	            </td>
	        	            <td class="text-center">
	        	            	<?php echo number_format(($ventas[$inicio->copy()->format('Y')][1]["beneficio"] - $ventas[$inicio->copy()->subYear()->format('Y')][1]["beneficio"]),0,',','.')?>
	        	                <?php 
	        	                    echo ($ventas[$inicio->copy()->format('Y')][1]["beneficio"] - $ventas[$inicio->copy()->subYear()->format('Y')][1]["beneficio"] > 0) ? "<i class='fa fa-arrow-up' aria-hidden='true'></i>" : "<i class='fa fa-arrow-down' aria-hidden='true'></i>";
	        	                     ?>
	        	            </td>
	        	            <td class="text-center">
	        	            	<?php echo number_format(($ventas[$inicio->copy()->format('Y')][2]["beneficio"] - $ventas[$inicio->copy()->subYear()->format('Y')][2]["beneficio"]),0,',','.')?>
	        	                <?php 
	        	                    echo ($ventas[$inicio->copy()->format('Y')][2]["beneficio"] - $ventas[$inicio->copy()->subYear()->format('Y')][2]["beneficio"] > 0) ? "<i class='fa fa-arrow-up' aria-hidden='true'></i>" : "<i class='fa fa-arrow-down' aria-hidden='true'></i>";
	        	                     ?>
	        	            </td>
	        	            <td class="text-center">
	        	            	<?php echo number_format(($ventas[$inicio->copy()->format('Y')][3]["beneficio"] - $ventas[$inicio->copy()->subYear()->format('Y')][3]["beneficio"]),0,',','.')?>
	        	                <?php 
	        	                    echo ($ventas[$inicio->copy()->format('Y')][3]["beneficio"] - $ventas[$inicio->copy()->subYear()->format('Y')][3]["beneficio"] > 0) ? "<i class='fa fa-arrow-up' aria-hidden='true'></i>" : "<i class='fa fa-arrow-down' aria-hidden='true'></i>";
	        	                     ?>
	        	            </td>
	        	            <td class="text-center">
	        	            	<?php echo number_format(($ventas[$inicio->copy()->format('Y')][4]["beneficio"] - $ventas[$inicio->copy()->subYear()->format('Y')][4]["beneficio"]),0,',','.')?>
	        	                <?php 
	        	                    echo ($ventas[$inicio->copy()->format('Y')][4]["beneficio"] - $ventas[$inicio->copy()->subYear()->format('Y')][4]["beneficio"] > 0) ? "<i class='fa fa-arrow-up' aria-hidden='true'></i>" : "<i class='fa fa-arrow-down' aria-hidden='true'></i>";
	        	                     ?>
	        	            </td>
	        	            <td class="text-center">
	        	                <?php //echo number_format(array_sum($ventas["Ben"])-array_sum($ventasOld["Ben"]),2,',','.');
	        	                    //echo (array_sum($ventas["Ben"])-array_sum($ventasOld["Ben"]) > 0) ? "<i class='fa fa-arrow-up' aria-hidden='true'></i>" : "<i class='fa fa-arrow-down' aria-hidden='true'></i>";
	        	                     ?>
	        	            </td>
	        	        </tr>
	        	    </tbody>
	        	</table>
	        </div>
		</div>
		
	</div>  
<!-- 	<div class="row">
		<div class="row">
		    <div>
		        <table class="table table-hover demo-table-search table-responsive " >
		            <thead>
		                <th class="text-center bg-complete text-white" colspan="7">Ingresos de la temporada <?php echo $inicio->copy()->format('Y') ?>-<?php echo $inicio->copy()->addYear()->format('Y') ?></th>
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
		                    <th colspan="7" class="ingresos_temp">Ingresos de la temporada <?php echo $inicio->copy()->subYear()->format('Y') ?>-<?php echo $inicio->copy()->format('Y') ?> </th>
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
		                    <th colspan="7" class="comparativa bg-primary text-center text-white" >Comparativa de la temporada <?php echo $inicio->copy()->subYear()->format('Y') ?>-<?php echo $inicio->copy()->format('Y') ?> </th>
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
		        </table>
		    </div>
		</div>
	</div>   -->
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
	nv.addGraph(function() {
        var chart = nv.models.multiBarChart()
          .transitionDuration(350)
          .reduceXTicks(true)   //If 'false', every single x-axis tick label will be rendered.
          .rotateLabels(0)      //Angle to rotate x-axis labels.
          .showControls(true)   //Allow user to switch between 'Grouped' and 'Stacked' mode.
          .groupSpacing(0.1)    //Distance between each group of bars.
        ;

        chart.xAxis
            .tickFormat(d3.format(',f'));

        chart.yAxis
            .tickFormat(d3.format(',.1f'));

        d3.select('#chart1 svg')
            .datum(exampleData())
            .call(chart);

        nv.utils.windowResize(chart.update);

        return chart;
    });

    //Generate some nice data.
    function exampleData() {
      return stream_layers(3,10+Math.random()*100,.1).map(function(data, i) {
        return {
          key: 'Stream #' + i,
          values: data
        };
      });
    }


    var data = {
        labels: [
                    <?php foreach ($años as $key => $value): ?>
                        <?php echo "'".$value."'," ?>
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

    new Chart(document.getElementById("pieIng"), {
        type: 'pie',
        data: {
         labels: ["Cobrado", "Pendiente",],
          datasets: [{
            label: "Population (millions)",
            backgroundColor: ["#3e95cd", "#8e5ea2"],
            data: [

            	//Comprobamos si existen cobros
            	<?php if (isset($arrayCobro[$fecha])): ?>
				    <?php echo $arrayCobro[$fecha]?>,
	            		<?php if ($date->copy()->format('n') >= 9): ?>
	    				    <?php echo $arrayTotales[$date->copy()->format('Y')]-$arrayCobro[$fecha] ?>
	    				<?php else: ?>
	    				    <?php echo $arrayTotales[$date->copy()->subYear()->format('Y')]-$arrayCobro[$fecha] ?> 
	    				<?php endif ?>  
				<?php else: ?>
	        		<?php if ($date->copy()->format('n') >= 9): ?>
					    <?php echo $arrayTotales[$date->copy()->format('Y')]-$arrayCobro[$fecha] ?>
					<?php else: ?>
					    <?php echo $arrayTotales[$date->copy()->subYear()->format('Y')]-$arrayCobro[$fecha] ?> 
					<?php endif ?>  
				<?php endif ?>]
          }]
        },
        options: {
          title: {
            display: true,
            text: 'Ingresos de la temporada <?php echo $fecha;?>'
          }
        }
    });

    new Chart(document.getElementById("pieCobros"), {
        type: 'pie',
        data: {
         labels: ["Metalico", "Banco",],
          datasets: [{
            backgroundColor: ["#38C8A7", "#2ca085"],
            data: [
            	//Comprobamos si existen cobros
            	<?php if (isset($arrayMetodo["metalico"][$fecha])): ?>
				    <?php echo $arrayMetodo["metalico"][$fecha]?>,
				<?php else: ?> 
					0,
				<?php endif ?>
            	<?php if (isset($arrayMetodo["banco"][$fecha])): ?>
				    <?php echo $arrayMetodo["banco"][$fecha]?>
				<?php else: ?> 
					0  
				<?php endif ?>]
          }]
        },
        options: {
          title: {
            display: true,
            text: 'Cobros de la temporada <?php echo $fecha;?>'
          }
        }
    });

</script>

@endsection