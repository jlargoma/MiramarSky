@extends('layouts.admin-master')

@section('title') Administrador de reservas MiramarSKI @endsection

@section('externalScripts') 

    <link href="/assets/plugins/jquery-datatable/media/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/plugins/jquery-datatable/extensions/FixedColumns/css/dataTables.fixedColumns.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/plugins/datatables-responsive/css/datatables.responsive.css" rel="stylesheet" type="text/css" media="screen" />

@endsection
    
@section('content')
<?php use \Carbon\Carbon; 
setlocale(LC_TIME, "ES");
setlocale(LC_TIME, "es_ES");
?>

	<style type="text/css">
		.total{
			border-right: 2px solid black !important;
			border-left: 2px solid black !important;
			font-weight: bold;
			color: black;
			background-color: grey!important;
		}

	    .Pagada-la-señal{
	        background-color: #F77975  !important;
	        color: black;
	    }
	    .Pagada-la-señal.start{
	        background: red; /* For browsers that do not support gradients */
	        background: -webkit-linear-gradient(left, yellow , red); /* For Safari 5.1 to 6.0 */
	        background: -o-linear-gradient(right, yellow, red); /* For Opera 11.1 to 12.0 */
	        background: -moz-linear-gradient(right, yellow, red); /* For Firefox 3.6 to 15 */
	        background: linear-gradient(to right, yellow , red); /* Standard syntax */
	    }
	    .Pagada-la-señal.end{
	        background: red; /* For browsers that do not support gradients */
	        background: -webkit-linear-gradient(left, red , yellow); /* For Safari 5.1 to 6.0 */
	        background: -o-linear-gradient(right, red, yellow); /* For Opera 11.1 to 12.0 */
	        background: -moz-linear-gradient(right, red, yellow); /* For Firefox 3.6 to 15 */
	        background: linear-gradient(to right, red , yellow); /* Standard syntax */
	    }
	    .Bloqueado{
	        background-color: #F9D975 !important;
	        color: black;
	    }
	    .Bloqueado.start{
	        background: red; /* For browsers that do not support gradients */
	        background: -webkit-linear-gradient(left, white, yellow); /* For Safari 5.1 to 6.0 */
	        background: -o-linear-gradient(right, white, yellow); /* For Opera 11.1 to 12.0 */
	        background: -moz-linear-gradient(right, white, yellow); /* For Firefox 3.6 to 15 */
	        background: linear-gradient(to right, white, yellow); /* Standard syntax */
	    }
	    .Bloqueado.end{
	        background: red; /* For browsers that do not support gradients */
	        background: -webkit-linear-gradient(left, yellow, white); /* For Safari 5.1 to 6.0 */
	        background: -o-linear-gradient(right, yellow, white); /* For Opera 11.1 to 12.0 */
	        background: -moz-linear-gradient(right, yellow, white); /* For Firefox 3.6 to 15 */
	        background: linear-gradient(to right, yellow, white); /* Standard syntax */
	    }
	    .SubComunidad{
	        background-color: #8A7DBE !important;
	        color: black;
	    }
	    .SubComunidad.start{
	        background: red; /* For browsers that do not support gradients */
	        background: -webkit-linear-gradient(left, white, purple); /* For Safari 5.1 to 6.0 */
	        background: -o-linear-gradient(right, white, purple); /* For Opera 11.1 to 12.0 */
	        background: -moz-linear-gradient(right, white, purple); /* For Firefox 3.6 to 15 */
	        background: linear-gradient(to right, white, purple); /* Standard syntax */
	    }
	    .SubComunidad.end{
	        background: red; /* For browsers that do not support gradients */
	        background: -webkit-linear-gradient(left, purple, white); /* For Safari 5.1 to 6.0 */
	        background: -o-linear-gradient(right, purple, white); /* For Opera 11.1 to 12.0 */
	        background: -moz-linear-gradient(right, purple, white); /* For Firefox 3.6 to 15 */
	        background: linear-gradient(to right, purple, white); /* Standard syntax */
	    }
	    .botones{
	        padding-top: 0px!important;
	        padding-bottom: 0px!important;
	    }
	    .nuevo{
	        background-color: lightgreen;
	        color: black;
	        border-radius: 11px;
	        width: 50px;
	    }
		td.text-center{
			padding: 3px!important;
		}
	    a {
	        color: black;
	        cursor: pointer;
	    }
	</style>
<div class="container-fluid padding-10 sm-padding-10">
    <div class="row">
		<div class="text-center"><h1 class="text-complete"><?php echo strtoupper($user->name) ?></h1></div>
		<div class="col-md-3">
			<button class="bloq-fecha">Bloquear Fechas</button>
			<button class="liquidacion">Bloquear Fechas</button>
		</div>
		<div style="clear: both;"></div>
		<br>
		<div class="col-md-3">
			<div id="bloq" style="display: none">
				<div class="col-md-12 padding-10" style="border: 1px solid  black;border-radius: 30px">
					<form role="form"  action="{{ url('/admin/propietario/bloquear') }}" method="post">
						<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
						<div class="input-daterange input-group" id="datepicker-range">
						    <input id="start" type="text" class="input-sm form-control" name="start" data-date-format="dd-mm-yyyy">
						    <span class="input-group-addon">Hasta</span>
						    <input id="finish" type="text" class="input-sm form-control" name="finish" data-date-format="dd-mm-yyyy">
						</div>
						<div class="input-group col-md-12 padding-10 text-center">
						    <button class="btn btn-complete" type="submit">Guardar</button>
						</div> 

					</form>
				    
				</div>
			</div>
		</div>
		<div style="clear: both;"></div>
		<br>
		<div class="col-md-6">
			<div id="liquidacion" style="display: none">
				<div class="col-md-12 padding-10" style="border: 1px solid  black;border-radius: 30px">
					<table class="table table-hover  no-footer" id="basicTable" role="grid">
					</table>
				</div>
			</div>
		</div>
		
		<div style="clear: both;"></div>
		<br>
		<?php if (count($room) > 0): ?>
			<div class="col-md-2 col-md-offset-4">
				<table class="table table-hover  no-footer" id="basicTable" role="grid">
					<thead>
						<th class ="text-center bg-complete text-white">Ingresos</th>
						<th class ="text-center bg-complete text-white">Apto</th>
						<th class ="text-center bg-complete text-white">Park</th>
						<?php if ($room->luxury == 1): ?>
							<th class ="text-center bg-complete text-white">Sup.Lujo</th>
						<?php else: ?>
						<?php endif ?>
					</thead>
					<tbody>
						<tr>
							<td class="text-center"><?php echo number_format($total,2,',','.'); ?>€</td>
							<td class="text-center"><?php echo number_format($apto,2,',','.'); ?>€</td>
							<td class="text-center"><?php echo number_format($park,2,',','.'); ?>€</td>
							<?php if ($room->luxury == 1): ?>
								<td class="text-center"><?php echo number_format($lujo,2,',','.'); ?>€</td>
							<?php else: ?>
							<?php endif ?>
						</tr>
					</tbody>
				</table>
			</div>
			<div style="clear: both;"></div>
			<div class="col-md-6">
				<table class="table table-hover  no-footer " id="basicTable" role="grid" >
					
					<thead>
						<th class ="text-center bg-complete text-white" style="width: 25%">Cliente</th>
						<th class ="text-center bg-complete text-white" style="width: 5%">Personas</th>
						<th class ="text-center bg-complete text-white">Entrada</th>
						<th class ="text-center bg-complete text-white">Salida</th>
						<th class ="text-center bg-complete text-white">Ingresos</th>
						<th class ="text-center bg-complete text-white">Apto</th>
						<th class ="text-center bg-complete text-white">Parking</th>
						<?php if ($room->luxury == 1): ?>
							<th class ="text-center bg-complete text-white">Sup.Lujo</th>
						<?php else: ?>
						<?php endif ?>
							
						
					</thead>
					<tbody>
						<?php foreach ($books as $book): ?>
							<tr>
								<td class="text-center"><?php echo ucfirst(strtolower($book->Customer->name)) ?> </td>
								<td class="text-center"><?php echo $book->pax ?> </td>
								<td class="text-center">
									<?php 
										$start = Carbon::CreateFromFormat('Y-m-d',$book->start);
										echo $start->formatLocalized('%d-%b');
									?> 
								</td>
								<td class="text-center">
									<?php 
										$finish = Carbon::CreateFromFormat('Y-m-d',$book->finish);
										echo $finish->formatLocalized('%d-%b');
									?> 
								</td>
								<td class="text-center total"><?php echo number_format($book->cost_total,2,',','.') ?> €</td>
								<td class="text-center"><?php echo number_format($book->cost_apto,2,',','.') ?> €</td>
								<td class="text-center"><?php echo number_format($book->cost_park,2,',','.') ?> €</td>
								<?php if ($room->luxury == 1): ?>
									<td class="text-center"><?php echo $book->cost_lujo ?> €</td>
								<?php else: ?>
								<?php endif ?>
							</tr>
						<?php endforeach ?>
					</tbody>
				</table>
			</div>
			<div class="col-md-6">
				<div class="row">
			    	<?php for ($j=0; $j < 12; $j++): ?>
			    		<div class="col-md-12 padding-10">
					        <table class="fc-border-separate" style="border:1px solid black;width: 100%">
					           <thead>
					                <tr>
					                    <td colspan="<?php echo $arrayMonths[$date->copy()->format('n')]+1 ?>">
					                        <?php echo  ucfirst($date->copy()->formatLocalized('%B %Y'))?>
					                    </td> 
					                </tr>
					                <tr>
					                    <td style="width: 1%!important">Apto</td>
					                    <?php for ($i=1; $i <= $arrayMonths[$date->copy()->format('n')] ; $i++): ?> 
					                        <td style='border:1px solid black;width: 3%'>
					                            <?php echo $i?> 
					                        </td> 
					                     <?php endfor; ?> 
					                </tr>
					           </thead>
					           <tbody>
				                    <tr>
				                        <?php $date = $date->startOfMonth() ?>
				                        <td><?php echo substr($room->nameRoom, 0,5)." " ?>      </td>
				                            
				                        <?php for ($i=01; $i <= $arrayMonths[$date->copy()->format('n')] ; $i++): ?> 

				                                <?php if (isset($reservas[$room->id][$date->copy()->format('Y')][$date->copy()->format('n')][$i])): ?>
				                                    <?php if ($reservas[$room->id][$date->copy()->format('Y')][$date->copy()->format('n')][$i]->start == $date->copy()->format('Y-m-d')): ?>
				                                            <td style='border:1px solid black;width: 3%'>
				                                                <div style="width: 50%;float: left;">
				                                                    &nbsp;
				                                                </div>
				                                                <div class="<?php echo $book->getStatus($reservas[$room->id][$date->copy()->format('Y')][$date->copy()->format('n')][$i]->type_book) ?> start" style="width: 50%;float: left;">
				                                                    &nbsp;
				                                                </div>

				                                            </td>    
				                                    <?php elseif($reservas[$room->id][$date->copy()->format('Y')][$date->copy()->format('n')][$i]->finish == $date->copy()->format('Y-m-d')): ?>
				                                            <td style='border:1px solid black;width: 3%'>
				                                                <div class="<?php echo $book->getStatus($reservas[$room->id][$date->copy()->format('Y')][$date->copy()->format('n')][$i]->type_book) ?> end" style="width: 50%;float: left;">
				                                                    &nbsp;
				                                                </div>
				                                                <div style="width: 50%;float: left;">
				                                                    &nbsp;
				                                                </div>
				                                                

				                                            </td>
				                                    <?php else: ?>
				                                        
				                                            <td style='border:1px solid black;width: 3%' title="<?php echo $reservas[$room->id][$date->copy()->format('Y')][$date->copy()->format('n')][$i]->Customer->name ?>" class="<?php echo $book->getStatus($reservas[$room->id][$date->copy()->format('Y')][$date->copy()->format('n')][$i]->type_book) ?>">

				                                        </td>

				                                    <?php endif ?>
				                                <?php else: ?>
				                                    <td style='border:1px solid black;width: 3%'>
				                                        
				                                    </td>
				                                <?php endif; ?>
				                                <?php if ($date->copy()->format('d') != $arrayMonths[$date->copy()->format('n')]): ?>
				                                    <?php $date = $date->addDay(); ?>
				                                <?php else: ?>
				                                    <?php $date = $date->startOfMonth() ?>
				                                <?php endif ?>
				                            
				                        <?php endfor; ?> 
				                    </tr>

					           </tbody>
					        </table>
					    </div>
				        <?php $date = $date->addMonth(); ?>
			    	<?php endfor; ?>
				    
				</div>
			</div>
		<?php else: ?>
			<div class="col-md-12">
				
				<div class="text-center"><h1 class="text-complete">NO TIENES UNA HABITACION ASOCIADA</h1></div>

			</div>
		<?php endif ?>
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
	<script src="/assets/plugins/moment/moment.min.js"></script>

	<script type="text/javascript">

		$(document).ready(function() {
			
			$('.bloq-fecha').click(function(event) {
				
				var x = document.getElementById('bloq');
				    if (x.style.display === 'none') {
				        x.style.display = 'block';
				    } else {
				        x.style.display = 'none';
				    }
			});
			$('.liquidacion').click(function(event) {
				
				var x = document.getElementById('liquidacion');
				    if (x.style.display === 'none') {
				        x.style.display = 'block';
				    } else {
				        x.style.display = 'none';
				    }
			});
		});
		
	</script>

@endsection