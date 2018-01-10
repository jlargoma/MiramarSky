<?php 
	use \App\Classes\Mobile;
	$mobile = new Mobile();  
?>
@extends('layouts.admin-master')

@section('title') Administrador de reservas MiramarSKI @endsection

@section('externalScripts') 

    <link href="/assets/plugins/jquery-datatable/media/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/plugins/jquery-datatable/extensions/FixedColumns/css/dataTables.fixedColumns.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/plugins/datatables-responsive/css/datatables.responsive.css" rel="stylesheet" type="text/css" media="screen" />
    </script>
    <script type="text/javascript" src="/assets/js/canvasjs/canvasjs.min.js"></script>
    <style>

      td{      
        padding: 10px 5px!important;
      }

      .table.tableRooms tbody tr td {
      	padding: 10px 12px!important;
      }
      .costeApto{
        background-color: rgba(200,200,200,0.5)!important;
        font-weight: bold;
      }
      
      .pendiente{
        background-color: rgba(200,200,200,0.5)!important;
        font-weight: bold;
      }

      td[class$="bordes"] {border-left: 1px solid black;border-right: 1px solid black;}
      
      .coste{
        background-color: rgba(200,200,200,0.5)!important;
      }
      
      .red{
        color: red;
      }
      .blue{
        color: blue;
      }

		.modal-big{
			    width: 75%;
		}

		@media screen and (max-width: 767px){
			.modal-big{
				    width: 100%;
			}
		}
    </style>
    
@endsection

<?php setlocale(LC_TIME, "ES"); ?>
<?php setlocale(LC_TIME, "es_ES");?>  
@section('content')

<div class="container-fluid padding-25 sm-padding-10">
    <div class="row">
        <div class="col-md-2 col-md-offset-4 col-xs-12 text-center">
            <h2 class="font-w300">Pagos a <span class="font-w800">propietarios</span> </h2>
        </div>
        <div class="col-md-1 col-xs-12">
        	<select id="fechas" class="form-control minimal" style="margin: 15px 0;">
        	    <?php $fecha = $date->copy()->subYear(); ?>
        	    <?php for ($i=1; $i <= 3; $i++): ?>
        	      	<?php echo $date->copy()->format('Y') ?>
        	      	<?php echo $fecha->copy()->format('Y') ?>
        	        <option value="<?php echo $fecha->copy()->format('Y'); ?>" {{ $date->copy()->format('Y') == $fecha->format('Y') ? 'selected' : '' }}>
        	            <?php echo $fecha->copy()->format('Y')."-".$fecha->copy()->addYear()->format('Y'); ?> 
        	        </option>
        	        <?php $fecha->addYear(); ?>
        	    <?php endfor; ?>
        	</select>
        </div>
    </div>
    <?php if (!$mobile->isMobile()): ?>
	    <div class="row">
	    	
	    	<div class="col-md-8 col-xs-12 push-0">
	    		<div class="col-md-9 col-xs-12 pull-right not-padding" style="width: 76.6%">
	    			<table class="table table-hover" >
	    				<thead>
	    					<tr>
	    						<th class ="text-center bg-complete text-white">
	    							PVP    
	    						</th>
	    						<th class ="text-center bg-complete text-white">
	    							C. Total.   
	    						</th>
	    						<th class ="text-center bg-complete text-white">
	    							C. Prop.   
	    						</th>
	    						<th class ="text-center bg-complete text-white">
	    							C. Apto.   
	    						</th>
	    						<th class ="text-center bg-complete text-white">
	    							C. Park   
	    						</th>
	    						<th class ="text-center bg-complete text-white">
	    							C. Lujo   
	    						</th>
	    						<th class ="text-center bg-complete text-white">
	    							C. Agen   
	    						</th>
	    						<th class ="text-center bg-complete text-white">
	    							C. Limp.   
	    						</th>
	    						<th class ="text-center bg-complete text-white">
	    							Benef.    
	    						</th>
	    						<th class ="text-center bg-complete text-white">
	    							%Ben.    
	    						</th>
	    						<th class ="text-center bg-complete text-white">
	    							Pagado    
	    						</th>
	    						<th class ="text-center bg-complete text-white">
	    							Pend.
	    						</th>
	    					</tr>
	    				</thead>
	    				<tbody>
	    					<tr> 
	    						<td class="text-center" style="padding: 8px;">
	    							<?php echo number_format($summary['totalPVP'],0,',','.') ?>€
	    						</td>
	    						<td class="text-center costeApto bordes">
	    							<b><?php  echo number_format($summary['totalCost'],0,',','.') ?>€</b>
	    						</td>
	    						<td class="text-center costeApto bordes">
	    							<?php $costeProp =  $summary['totalApto'] + $summary['totalParking'] + $summary['totalLujo']?>
	    							<b><?php  echo number_format( $costeProp ,0,',','.') ?>€</b>
	    						</td>
	    						<td class="text-center">
	    							<b><?php  echo number_format($summary['totalApto'],0,',','.') ?>€</b>
	    						</td>
	    						<td class="text-center" style="padding: 8px;">
	    							<?php  echo number_format($summary['totalParking'],0,',','.') ?>€
	    						</td>
	    						<td class="text-center" style="padding: 8px;">
	    							<?php  echo number_format($summary['totalLujo'],0,',','.') ?>€
	    						</td>
	    						<td class="text-center" style="padding: 8px;">
	    							<?php  echo number_format($summary['totalAgencia'],0,',','.') ?>€
	    						</td>
	    						<td class="text-center" style="padding: 8px;">
	    							<?php  echo number_format($summary['totalLimp'],0,',','.') ?>€
	    						</td>
	    						<td class="text-center" style="padding: 8px;">
	    							<?php $beneficio = $summary['totalPVP'] - $summary['totalCost']; ?>
	    							<?php if ($beneficio > 0): ?>
	    								<span class="text-success font-w800"><?php echo number_format( $beneficio,0,',','.') ?>€</span>
	    							<?php else: ?>
	    								<span class="text-danger font-w800"><?php echo number_format( $beneficio,0,',','.') ?>€</span>
	    							<?php endif ?>
	    							
	    						</td>
	    						<td class="text-center" style="padding: 8px;">
	    							<?php $benPercentage = ($beneficio/$summary['totalPVP'])*100;?>
	    							<?php  echo number_format($benPercentage,0,',','.') ?>%
	    						</td>
	    						<td class="text-center" style="padding: 8px;">
	    							<?php  echo number_format($summary['pagos'],0,',','.') ?> €
	    						</td>
	    						<td class="text-center pendiente bordes" style="padding: 8px;">
	    							<?php $pendiente = $summary['totalCost'] - $summary['pagos'];?>
	    							<span class="text-danger font-w800"><b><?php echo number_format($pendiente,0,',','.') ?>€</b></span>
	    						</td>
	    					</tr>
	    				</tbody>
	    			</table>
	    		</div>

	    		<div class="col-md-12 col-xs-12 pull-right not-padding">
		    		<table class="table tableRooms">

		    			<thead>
		    				<tr>
			    				<th class ="text-center bg-complete text-white" style="padding: 10px 5px; width: 8%">
			    					Prop.
			    				</th>
			    				<th class ="text-center bg-complete text-white" style="padding: 10px 5px; width: 1%">&nbsp;</th>
			    				<th class ="text-center bg-complete text-white" style="padding: 10px 5px; width: 3%">
			    					Tipo 
			    				</th>
			    				<th class ="text-center bg-complete text-white" style="padding: 10px 5px; width: " >
			    					PVP  
			    				</th>
			    				<th class ="text-center bg-complete text-white" style="padding: 10px 5px; width: ">
									C. Total.   
								</th>
								<th class ="text-center bg-complete text-white" style="padding: 10px 5px; width: ">
									C. Prop.   
								</th>
			    				<th class ="text-center bg-complete text-white" style="padding: 10px 5px; width: ">
									C. Apto.   
								</th>
								<th class ="text-center bg-complete text-white" style="padding: 10px 5px; width: ">
									C. Park   
								</th>
								<th class ="text-center bg-complete text-white" style="padding: 10px 5px; width: ">
									C. Lujo   
								</th>
								<th class ="text-center bg-complete text-white" style="padding: 10px 5px; width: ">
									C. Agen   
								</th>
								<th class ="text-center bg-complete text-white" style="padding: 10px 5px; width: ">
									C. Limp.   
								</th>
			    				<th class ="text-center bg-complete text-white" style="padding: 10px 5px; width: ">
			    					Benef
			    				</th>
			    				<th class ="text-center bg-complete text-white" style="padding: 10px 5px; width: ">
			    					% Ben 
			    				</th>
			    				<th class ="text-center bg-complete text-white" style="padding: 10px 5px; width: ">
			    					Pagado  
			    				</th>
			    				<th class ="text-center bg-complete text-white" style="padding: 10px 5px; width: ">
			    					Pendiente   
			    				</th>
		    				</tr>
				        </thead>
					        <tbody>
					        	<?php foreach ($rooms as $room): ?>
					        		<?php $pendiente = $data[$room->id]['totales']['totalCost'] - $data[$room->id]['pagos'] ?>
					        		<tr>
					        			<td class="text-left"  style="padding: 10px 5px ;">
					        				<a class="update-payments" data-debt="<?php echo $pendiente ?>" data-month="<?php echo $date->copy()->format('Y') ?>" data-id="<?php echo $room->id ?>" data-toggle="modal" data-target="#payments" title="Añadir pago" style="cursor: pointer">
					        					<?php echo ucfirst($room->user->name) ?> (<?php echo $room->nameRoom ?>)
					        				</a>
					        			</td>
					        			<td class="text-center"  style="padding: 10px 5px ;">
					        				<button class="btn btn-default btn-sm bookByRoom" data-id="<?php echo $room->id ?>" data-toggle="modal" data-target="#bookByRoom" style="cursor: pointer" title="Reservas de <?php echo $room->nameRoom?>">
					        					<i class="fa fa-address-book" aria-hidden="true"></i>
					        				</button>
					        			</td>
					        			<td class="text-center"  style="padding: 10px 5px ;">
					        				<?php echo $room->typeAptos->name ?>		
					        			</td>
					        			<td class="text-center"  style="padding: 10px 5px ;">
					        				<?php if (isset($data[$room->id]['totales']['totalPVP'])): ?>
					        					<?php echo number_format($data[$room->id]['totales']['totalPVP'],0,',','.'); ?>€
					        				<?php else: ?>
					        					-----
					        				<?php endif ?>
					        			</td>
					        			
					        			<td class="text-center  costeApto bordes"  style="padding: 10px 5px ;">

					        				<?php if (isset($data[$room->id]['totales']['totalCost'])): ?>
					        					<?php echo number_format($data[$room->id]['totales']['totalCost'],0,',','.'); ?>€
					        				<?php else: ?>
					        					-----
					        				<?php endif ?>
					        			</td>
					        			<td class="text-center  costeApto bordes"  style="padding: 10px 5px ;">
											<?php $costPropTot =  $data[$room->id]['totales']['totalApto']+$data[$room->id]['totales']['totalParking']+$data[$room->id]['totales']['totalLujo']?>
					        				<?php if ( $costPropTot  != 0): ?>
					        					<?php echo number_format($costPropTot ,0,',','.'); ?>€
					        				<?php else: ?>
					        					-----
					        				<?php endif ?>
					        			</td>

					        			<td class="text-center"  style="padding: 10px 5px ;">
					        				<?php if (isset($data[$room->id]['totales']['totalApto'])): ?>
					        					<?php echo number_format($data[$room->id]['totales']['totalApto'],0,',','.'); ?>€
					        				<?php else: ?>
					        					-----
					        				<?php endif ?>
					        			</td>

					        			<td class="text-center"  style="padding: 10px 5px ;">
					        				<?php if (isset($data[$room->id]['totales']['totalParking'])): ?>
					        					<?php echo number_format($data[$room->id]['totales']['totalParking'],0,',','.'); ?>€
					        				<?php else: ?>
					        					-----
					        				<?php endif ?>
					        			</td>

					        			<td class="text-center"  style="padding: 10px 5px ;">
					        				<?php if (isset($data[$room->id]['totales']['totalLujo'])): ?>
					        					<?php echo number_format($data[$room->id]['totales']['totalLujo'],0,',','.'); ?>€
					        				<?php else: ?>
					        					-----
					        				<?php endif ?>
					        			</td>

					        			<td class="text-center"  style="padding: 10px 5px ;">
					        				<?php if (isset($data[$room->id]['totales']['totalAgencia'])): ?>
					        					<?php echo number_format($data[$room->id]['totales']['totalAgencia'],0,',','.'); ?>€
					        				<?php else: ?>
					        					-----
					        				<?php endif ?>
					        			</td>

					        			<td class="text-center"  style="padding: 10px 5px ;">
					        				<?php if (isset($data[$room->id]['totales']['totalLimp'])): ?>
					        					<?php echo number_format($data[$room->id]['totales']['totalLimp'],0,',','.'); ?>€
					        				<?php else: ?>
					        					-----
					        				<?php endif ?>
					        			</td>

					        			<td class="text-center   "   style="padding: 10px 5px ;">
					        				<?php 
					        					$benefRoom = $data[$room->id]['totales']['totalPVP'] - $data[$room->id]['totales']['totalCost'] 
					        				?>
					        				<?php if ($benefRoom > 0): ?>
					        					<span class="text-success font-w800"><?php echo number_format($benefRoom,0,',','.') ?>€</span>
					        				<?php elseif($benefRoom == 0): ?>
					        					-----
					        				<?php elseif($benefRoom < 0): ?>
					        					<span class="text-danger font-w800"><?php echo number_format($benefRoom,0,',','.') ?>€</span>
					        				<?php endif ?>
					        			</td>

					        			<td class="text-center"  style="padding: 10px 5px ;">
					        				<?php 
					        				$divisor = ($data[$room->id]['totales']['totalPVP'] == 0)?1:$data[$room->id]['totales']['totalPVP'];
					        					$benefPercentageRoom = ( $benefRoom / $divisor  ) *100;
					        				?>
					        				<?php if ($benefPercentageRoom > 0): ?>
					        					<span class="text-success font-w800"><?php echo number_format($benefPercentageRoom,0,',','.') ?>%</span>
					        				<?php elseif($benefPercentageRoom == 0): ?>
					        					-----
					        				<?php elseif($benefPercentageRoom < 0): ?>
					        					<span class="text-danger font-w800"><?php echo number_format($benefPercentageRoom,0,',','.') ?>%</span>
					        				<?php endif ?>
					        			</td>

					        			<td class="text-center"  style="padding: 10px 5px ;">
					        				<?php if ( $data[$room->id]['pagos'] != 0): ?>
					        					<?php echo number_format($data[$room->id]['pagos'],0,',','.')?>€
					        				<?php else: ?>
					        					-----
					        				<?php endif ?>
					        			</td>

					        			<td class="text-center pendiente bordes"  style="padding: 10px 5px ;">
					        				
					        				<?php if ($pendiente <= 0): ?>
					        					<span class="text-success font-w800"><?php echo number_format($pendiente,0,',','.') ?>€</span>
					        				<?php else: ?>
					        					<span class="text-danger font-w800"><?php echo number_format($pendiente,0,',','.') ?>€</span>
					        				<?php endif ?>
					        			</td>
					            </tr>

					        <?php endforeach ?>
					    </tbody>

					</table>
	    		</div>
	    	</div>
	    	<div class="col-md-5 col-xs-12">
	    	
	    	</div>
	    </div>
    <?php else: ?>
	    <div class="row">
	    	
	    	<div class="table-responsive" style="border: none!important">
    			<table class="table table-hover push-20" >
    				<thead>
    					<tr>
    						<th class ="text-center bg-complete text-white">
    							PVP    
    						</th>
    						<th class ="text-center bg-complete text-white">
    							C. Total.   
    						</th>
    						<th class ="text-center bg-complete text-white">
    							C. Prop.   
    						</th>
    						<th class ="text-center bg-complete text-white">
    							C. Apto.   
    						</th>
    						<th class ="text-center bg-complete text-white">
    							C. Park   
    						</th>
    						<th class ="text-center bg-complete text-white">
    							C. Lujo   
    						</th>
    						<th class ="text-center bg-complete text-white">
    							C. Agen   
    						</th>
    						<th class ="text-center bg-complete text-white">
    							C. Limp.   
    						</th>
    						<th class ="text-center bg-complete text-white">
    							Benef.    
    						</th>
    						<th class ="text-center bg-complete text-white">
    							%Ben.    
    						</th>
    						<th class ="text-center bg-complete text-white">
    							Pagado    
    						</th>
    						<th class ="text-center bg-complete text-white">
    							Pend.
    						</th>
    					</tr>
    				</thead>
    				<tbody>
    					<tr> 
    						<td class="text-center" style="padding: 8px;">
    							<?php echo number_format($summary['totalPVP'],0,',','.') ?>€
    						</td>
    						<td class="text-center costeApto bordes">
    							<b><?php  echo number_format($summary['totalCost'],0,',','.') ?>€</b>
    						</td>
    						<td class="text-center costeApto bordes">
    							<?php $costeProp =  $summary['totalApto'] + $summary['totalParking'] + $summary['totalLujo']?>
    							<b><?php  echo number_format( $costeProp ,0,',','.') ?>€</b>
    						</td>
    						<td class="text-center">
    							<b><?php  echo number_format($summary['totalApto'],0,',','.') ?>€</b>
    						</td>
    						<td class="text-center" style="padding: 8px;">
    							<?php  echo number_format($summary['totalParking'],0,',','.') ?>€
    						</td>
    						<td class="text-center" style="padding: 8px;">
    							<?php  echo number_format($summary['totalLujo'],0,',','.') ?>€
    						</td>
    						<td class="text-center" style="padding: 8px;">
    							<?php  echo number_format($summary['totalAgencia'],0,',','.') ?>€
    						</td>
    						<td class="text-center" style="padding: 8px;">
    							<?php  echo number_format($summary['totalLimp'],0,',','.') ?>€
    						</td>
    						<td class="text-center" style="padding: 8px;">
    							<?php $beneficio = $summary['totalPVP'] - $summary['totalCost']; ?>
    							<?php if ($beneficio > 0): ?>
    								<span class="text-success font-w800"><?php echo number_format( $beneficio,0,',','.') ?>€</span>
    							<?php else: ?>
    								<span class="text-danger font-w800"><?php echo number_format( $beneficio,0,',','.') ?>€</span>
    							<?php endif ?>
    							
    						</td>
    						<td class="text-center" style="padding: 8px;">
    							<?php $benPercentage = ($beneficio/$summary['totalPVP'])*100;?>
    							<?php  echo number_format($benPercentage,0,',','.') ?>%
    						</td>
    						<td class="text-center" style="padding: 8px;">
    							<?php  echo number_format($summary['pagos'],0,',','.') ?> €
    						</td>
    						<td class="text-center pendiente bordes" style="padding: 8px;">
    							<?php $pendiente = $summary['totalCost'] - $summary['pagos'];?>
    							<span class="text-danger font-w800"><b><?php echo number_format($pendiente,0,',','.') ?>€</b></span>
    						</td>
    					</tr>
    				</tbody>
    			</table>
    		</div>

    		<div class="table-responsive push-20" style="border: none!important">
	    		<table class="table tableRooms push-20">

	    			<thead>
	    				<tr>
		    				<th class ="text-center bg-complete text-white" style="padding: 10px 5px; width: 8%">
		    					Prop.
		    				</th>
		    				<th class ="text-center bg-complete text-white" style="padding: 10px 5px; width: 1%">&nbsp;</th>
		    				<th class ="text-center bg-complete text-white" style="padding: 10px 5px; width: 3%">
		    					Tipo 
		    				</th>
		    				<th class ="text-center bg-complete text-white" style="padding: 10px 5px; width: " >
		    					PVP  
		    				</th>
		    				<th class ="text-center bg-complete text-white" style="padding: 10px 5px; width: ">
								C. Total.   
							</th>
							<th class ="text-center bg-complete text-white" style="padding: 10px 5px; width: ">
									C. Prop.   
								</th>
		    				<th class ="text-center bg-complete text-white" style="padding: 10px 5px; width: ">
								C. Apto.   
							</th>
							<th class ="text-center bg-complete text-white" style="padding: 10px 5px; width: ">
								C. Park   
							</th>
							<th class ="text-center bg-complete text-white" style="padding: 10px 5px; width: ">
								C. Lujo   
							</th>
							<th class ="text-center bg-complete text-white" style="padding: 10px 5px; width: ">
								C. Agen   
							</th>
							<th class ="text-center bg-complete text-white" style="padding: 10px 5px; width: ">
								C. Limp.   
							</th>
		    				<th class ="text-center bg-complete text-white" style="padding: 10px 5px; width: ">
		    					Benef
		    				</th>
		    				<th class ="text-center bg-complete text-white" style="padding: 10px 5px; width: ">
		    					% Ben 
		    				</th>
		    				<th class ="text-center bg-complete text-white" style="padding: 10px 5px; width: ">
		    					Pagado  
		    				</th>
		    				<th class ="text-center bg-complete text-white" style="padding: 10px 5px; width: ">
		    					Pendiente   
		    				</th>
	    				</tr>
			        </thead>
				        <tbody>
				        	<?php foreach ($rooms as $room): ?>
				        		<?php $pendiente = $data[$room->id]['totales']['totalCost'] - $data[$room->id]['pagos'] ?>
				        		<tr>
				        			<td class="text-left"  style="padding: 10px 5px ;">
				        				<a class="update-payments" data-debt="<?php echo $pendiente ?>" data-month="<?php echo $date->copy()->format('Y') ?>" data-id="<?php echo $room->id ?>" data-toggle="modal" data-target="#payments" title="Añadir pago" style="cursor: pointer">
				        					<?php echo ucfirst(substr($room->user->name, 0, 6)) ?> (<?php echo substr($room->nameRoom, 0, 6) ?>)
				        				</a>
				        			</td>
				        			<td class="text-center"  style="padding: 10px 5px ;">
				        				<button class="btn btn-default btn-sm bookByRoom" data-id="<?php echo $room->id ?>" data-toggle="modal" data-target="#bookByRoom" style="cursor: pointer" title="Reservas de <?php echo $room->nameRoom?>">
				        					<i class="fa fa-address-book" aria-hidden="true"></i>
				        				</button>
				        			</td>
				        			<td class="text-center"  style="padding: 10px 5px ;">
				        				<?php echo $room->typeAptos->name ?>		
				        			</td>
				        			<td class="text-center"  style="padding: 10px 5px ;">
				        				<?php if (isset($data[$room->id]['totales']['totalPVP'])): ?>
				        					<?php echo number_format($data[$room->id]['totales']['totalPVP'],0,',','.'); ?>€
				        				<?php else: ?>
				        					-----
				        				<?php endif ?>
				        			</td>
				        			
				        			<td class="text-center  costeApto bordes"  style="padding: 10px 5px ;">

				        				<?php if (isset($data[$room->id]['totales']['totalCost'])): ?>
				        					<?php echo number_format($data[$room->id]['totales']['totalCost'],0,',','.'); ?>€
				        				<?php else: ?>
				        					-----
				        				<?php endif ?>
				        			</td>

    			        			<td class="text-center  costeApto bordes"  style="padding: 10px 5px ;">
    									<?php $costPropTot =  $data[$room->id]['totales']['totalApto']+$data[$room->id]['totales']['totalParking']+$data[$room->id]['totales']['totalLujo']?>
    			        				<?php if ( $costPropTot  != 0): ?>
    			        					<?php echo number_format($costPropTot ,0,',','.'); ?>€
    			        				<?php else: ?>
    			        					-----
    			        				<?php endif ?>
    			        			</td>

				        			<td class="text-center"  style="padding: 10px 5px ;">
				        				<?php if (isset($data[$room->id]['totales']['totalApto'])): ?>
				        					<?php echo number_format($data[$room->id]['totales']['totalApto'],0,',','.'); ?>€
				        				<?php else: ?>
				        					-----
				        				<?php endif ?>
				        			</td>

				        			<td class="text-center"  style="padding: 10px 5px ;">
				        				<?php if (isset($data[$room->id]['totales']['totalParking'])): ?>
				        					<?php echo number_format($data[$room->id]['totales']['totalParking'],0,',','.'); ?>€
				        				<?php else: ?>
				        					-----
				        				<?php endif ?>
				        			</td>

				        			<td class="text-center"  style="padding: 10px 5px ;">
				        				<?php if (isset($data[$room->id]['totales']['totalLujo'])): ?>
				        					<?php echo number_format($data[$room->id]['totales']['totalLujo'],0,',','.'); ?>€
				        				<?php else: ?>
				        					-----
				        				<?php endif ?>
				        			</td>

				        			<td class="text-center"  style="padding: 10px 5px ;">
				        				<?php if (isset($data[$room->id]['totales']['totalAgencia'])): ?>
				        					<?php echo number_format($data[$room->id]['totales']['totalAgencia'],0,',','.'); ?>€
				        				<?php else: ?>
				        					-----
				        				<?php endif ?>
				        			</td>

				        			<td class="text-center"  style="padding: 10px 5px ;">
				        				<?php if (isset($data[$room->id]['totales']['totalLimp'])): ?>
				        					<?php echo number_format($data[$room->id]['totales']['totalLimp'],0,',','.'); ?>€
				        				<?php else: ?>
				        					-----
				        				<?php endif ?>
				        			</td>

				        			<td class="text-center   "   style="padding: 10px 5px ;">
				        				<?php 
				        					$benefRoom = $data[$room->id]['totales']['totalPVP'] - $data[$room->id]['totales']['totalCost'] 
				        				?>
				        				<?php if ($benefRoom > 0): ?>
				        					<span class="text-success font-w800"><?php echo number_format($benefRoom,0,',','.') ?>€</span>
				        				<?php elseif($benefRoom == 0): ?>
				        					-----
				        				<?php elseif($benefRoom < 0): ?>
				        					<span class="text-danger font-w800"><?php echo number_format($benefRoom,0,',','.') ?>€</span>
				        				<?php endif ?>
				        			</td>

				        			<td class="text-center"  style="padding: 10px 5px ;">
				        				<?php 
				        				$divisor = ($data[$room->id]['totales']['totalPVP'] == 0)?1:$data[$room->id]['totales']['totalPVP'];
				        					$benefPercentageRoom = ( $benefRoom / $divisor  ) *100;
				        				?>
				        				<?php if ($benefPercentageRoom > 0): ?>
				        					<span class="text-success font-w800"><?php echo number_format($benefPercentageRoom,0,',','.') ?>%</span>
				        				<?php elseif($benefPercentageRoom == 0): ?>
				        					-----
				        				<?php elseif($benefPercentageRoom < 0): ?>
				        					<span class="text-danger font-w800"><?php echo number_format($benefPercentageRoom,0,',','.') ?>%</span>
				        				<?php endif ?>
				        			</td>

				        			<td class="text-center"  style="padding: 10px 5px ;">
				        				<?php if ( $data[$room->id]['pagos'] != 0): ?>
				        					<?php echo number_format($data[$room->id]['pagos'],0,',','.')?>€
				        				<?php else: ?>
				        					-----
				        				<?php endif ?>
				        			</td>

				        			<td class="text-center pendiente bordes"  style="padding: 10px 5px ;">
				        				
				        				<?php if ($pendiente <= 0): ?>
				        					<span class="text-success font-w800"><?php echo number_format($pendiente,0,',','.') ?>€</span>
				        				<?php else: ?>
				        					<span class="text-danger font-w800"><?php echo number_format($pendiente,0,',','.') ?>€</span>
				        				<?php endif ?>
				        			</td>
				            </tr>

				        <?php endforeach ?>
				    </tbody>

				</table>
    		</div>
	    	<div class="col-xs-12">
	    	
	    	</div>
	    </div>
    <?php endif ?>
</div>


<div class="modal fade slide-up disable-scroll in" id="payments" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content-wrapper">
			<div class="modal-content contentPayments" style="max-height: 650px; overflow-y: auto;">

			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>


<div class="modal fade slide-up disable-scroll in" id="bookByRoom" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-big">
		<div class="modal-content-wrapper">
			<div class="modal-content">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-close fa-2x"></i></button>
				<div class="container-xs-height full-height">
					<div class="row-xs-height">
						<div class="modal-body contentBookRoom">

						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
@endsection

@section('scripts')

	<script src="/assets/plugins/jquery-datatable/media/js/jquery.dataTables.min.js" type="text/javascript"></script>
	<script src="/assets/plugins/jquery-datatable/extensions/TableTools/js/dataTables.tableTools.min.js" type="text/javascript"></script>
	<script src="/assets/plugins/jquery-datatable/media/js/dataTables.bootstrap.js" type="text/javascript"></script>
	<script src="/assets/plugins/jquery-datatable/extensions/Bootstrap/jquery-datatable-bootstrap.js" type="text/javascript"></script>
	<script type="text/javascript" src="/assets/plugins/datatables-responsive/js/datatables.responsive.js"></script>



	<script type="text/javascript">
		$(document).ready(function() {

			$('.update-payments').click(function(event) {
				var debt = $(this).attr('data-debt');
				var id   = $(this).attr('data-id');
				var month = $(this).attr('data-month');
				$.get('/admin/pagos-propietarios/update/'+id+'/'+month,{ debt: debt}, function(data) {
					$('.contentPayments').empty().append(data);
				});
			});

			$('#fechas').change(function(event) {

				var month = $(this).val();
				window.location = '/admin/pagos-propietarios/'+month;
			});


		});
		window.onload = function () {
			var chart = new CanvasJS.Chart("chartContainer",
			{
				title:{
					text: "Grafico  de pagos a propietarios"
				},
				axisX: {
					labelAngle: -90,
					labelFontSize: 15,
				},
				axisY: {
					title: "Porcentaje",
					labelFontSize: 15,
				},
				dataPointWidth: 25,
				data: [
				{
					type: "stackedColumn100",
					legendText: "Pagado",
					showInLegend: "true",
					indexLabel: "{y}",
					indexLabelOrientation: "vertical",
					indexLabelFontColor: "black",
					color: "Green",
					bevelEnabled: true,

					dataPoints: [
					<?php foreach ($rooms as $room): ?>
					<?php if (isset($data[$room->id]['pagos'])): ?>
					{  y: <?php echo $data[$room->id]['pagos'] ?> , label: "<?php echo $room->nameRoom ?>"},
					<?php else: ?>
					{  y: 0 , label: "<?php echo $room->nameRoom ?>"},
					<?php endif ?>

					<?php endforeach ?>

					]
				},  
				{
					indexLabel: "#total",
					legendText: "Deuda",
					showInLegend: "true",
					indexLabelPlacement: "outside", 
					indexLabelOrientation: "vertical",
					indexLabelFontColor: "black",
					type: "stackedColumn100",
					color:"LightCoral ",
					dataPoints: [
					<?php foreach ($rooms as $room): ?>
					<?php $pendiente = $data[$room->id]['totales']['totalCost'] - $data[$room->id]['pagos'] ?>
					<?php if (isset($pendiente)): ?>
					{  y: <?php echo $pendiente ?> , label: "<?php echo $room->nameRoom ?>"},
					<?php else: ?>
					{  y: 0 , label: "<?php echo $room->nameRoom ?>"},
					<?php endif ?>
					<?php endforeach ?>
					]
				}
				]
			});

			chart.render();
		}


		$('button.bookByRoom').click(function(event) {
			event.preventDefault();
			var year = $('#fechas').val();
			var idRoom = $(this).attr('data-id');
			$.get('/admin/paymentspro/getBooksByRoom/'+idRoom,{ idRoom: idRoom, year: year}, function(data) {
				$('.contentBookRoom').empty().append(data);
			});
			
		});
	</script>
@endsection