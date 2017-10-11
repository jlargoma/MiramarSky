@extends('layouts.admin-master')

@section('title') Seccion Propietarios @endsection

@section('externalScripts') 

    <link href="/assets/plugins/jquery-datatable/media/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/plugins/jquery-datatable/extensions/FixedColumns/css/dataTables.fixedColumns.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/plugins/datatables-responsive/css/datatables.responsive.css" rel="stylesheet" type="text/css" media="screen" />

    <link rel="stylesheet" href="{{ asset('/frontend/css/components/daterangepicker.css')}}" type="text/css" />
@endsection
     
@section('content')

<?php use \Carbon\Carbon;  setlocale(LC_TIME, "ES"); setlocale(LC_TIME, "es_ES"); ?>

<style type="text/css">

	.S, .D{
	    background-color: rgba(0,0,0,0.2)!important;
	    color: red!important;
	}
	.total{
		border-right: 2px solid black !important;
		border-left: 2px solid black !important;
		font-weight: bold;
		color: black;
		background-color: rgba(0,100,255,0.2) !important;
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
	.table-hover > tr > td{
		padding: 3px!important;
	}
    a {
        color: black;
        cursor: pointer;
    }
    .btn-success2{
    	background-color: rgb(70, 195, 123)!important; 
    	font-size: 20px !important; 
    	border: rgb(70, 195, 123) !important; 
    	box-shadow: rgba(70, 195, 123, 0.5) 0px 0px 3px 2px !important; 
    	display: inline-block;
    	color: white!important;
    }

    .bloq-cont{
    	padding: 30px;
    	border: 2px solid #999999;
    	-moz-border-radius: 6px;
    	-webkit-border-radius: 6px;
    	border-radius: 6px;
    	box-shadow: inset 1px 1px 0 white, 1px 1px 0 white;
    	background: #f7f7f7;
    	margin-top: 15px;
    }
    .btn-danger2{
    	display:none;font-size: 20px !important;
    	background-color: rgb(228, 22, 22)!important;
    	border: rgb(201, 53, 53) !important;
    	box-shadow: 0px 0px 3px 2px rgba(228, 22, 22, 0.5)!important;"
    	color: white!important;
    }
</style>

<div class="container-fluid padding-10 sm-padding-10">
    <div class="row">
    	<div class="col-md-12 text-center">
    		<div class="col-md-4 m-t-20">
    			<div class="col-md-3">
    				<a class="btn btn-danger btn text-white" href="{{ url('admin/propietario') }}/<?php echo $room->nameRoom."/operativa" ?>">Opertaiva</a>
    			</div>
    			<div class="col-md-3">
    				<a class="btn btn-danger btn text-white" href="{{ url('admin/propietario') }}/<?php echo $room->nameRoom."/tarifas" ?>">Tarifas</a>
    			</div>
    			<div class="col-md-3">
    				<a class="btn btn-danger btn text-white" href="{{ url('admin/propietario') }}/<?php echo $room->nameRoom."/descuentos" ?>">Descuentos</a>
    			</div>
    			<div class="col-md-3">
    			    <a class="btn btn-danger btn text-white" href="{{ url('admin/propietario') }}/<?php echo $room->nameRoom."/fiscalidad" ?>">Fiscalidad</a>
    			</div>
    		</div>
    	    <div class="col-md-6">
    	    	<h2><b>Planning de reservas</b>  Fechas:
    	            
    	            
    	            <select id="fecha" >
    	                <?php $fecha = $date->copy()->SubYear(); ?>
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
    	        </h2>
    	    </div>        
    	</div>
		<div class="text-center"><h1 class="text-complete"><?php echo strtoupper($room->nameRoom) ?></h1></div>
		<div class="col-md-3">
			<a id="cal-bloq" class="btn btn-success2 btn text-white" >Bloquear fechas</a>
			<a id="cal-bloq2" class="btn btn-danger2 btn text-white" >cerrar</a>
			<a id="cal-pago" class="btn btn-success2 btn text-white" >Liquidacion</a>
			<a id="cal-pago2" class="btn btn-danger2 btn text-white" >cerrar</a>
		</div>

		<div style="clear: both;"></div>
		<br>
		<div class="col-md-3">
			<div id="container-bloq" style="display:none;">
				<div class="col-md-12 padding-10 bloq-cont">

						<input type="text" class="form-control daterange1" id="fechas" name="fechas" required="" style="cursor: pointer; text-align: center;min-height: 28px;" readonly="">
						<div class="input-group col-md-12 padding-10 text-center">
						    <button class="btn btn-complete bloquear" data-id="<?php echo $room->id ?>">Guardar</button>
						</div> 
				    
				</div>
			</div>
		</div>
		<div style="clear: both;"></div>
		<br>

		<div class="col-md-6">
			<div id="container-pago" style="display:none;">
				<div class="col-md-12 padding-10 liq-cont">
					<table class="table table-hover  no-footer" id="basicTable" role="grid">
						<thead>
							
							<th class="bg-complete text-white text-center">Pagos</th>
							<th class="bg-complete text-white text-center">Facturado</th>
							<th class="bg-complete text-white text-center">Pagado</th>
							<th class="bg-complete text-white text-center">Pendiente</th>
						</thead>
						<tbody>
							<tr>
								<?php if (count($pagos)> 0): ?>
									
									<td style="padding: 0;border-left: 1px solid black;border-right: 1px solid black">
									<?php foreach ($pagos as $pago): ?>

									
										<table style="width: 100%">
											<tr>
												<td style="border:none"><?php echo Carbon::createFromFormat('Y-m-d',$pago->datePayment)->format('d-m-Y')?></td>
												<td style="border:none"><?php echo $pago->comment ?></td>
												<td style="border:none"><?php echo number_format($pago->import,2,',','.') ?>€</td>
											</tr>
										</table>							
									
										
										
									<?php endforeach ?>
									</td>
									<td class="text-center" style="padding-top: 20px!important;vertical-align: middle;"><?php echo number_format($total,2,',','.'); ?>€</td>
									<td class="text-center" style="padding-top: 20px!important;vertical-align: middle;">
										<?php echo number_format($pagototal,2,',','.') ?>€
									</td>

									<td class="text-center" style="padding-top: 20px!important;vertical-align: middle;">
										<?php echo number_format($total-$pagototal,2,',','.'); ?>€
									</td>
								<?php else: ?>
									<td class="text-center" colspan="4">Aun no hay pagos realizados</td>
								<?php endif ?>
								
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		
		<div style="clear: both;"></div>
		<br>
		<?php if (count($room) > 0): ?>
			<div class="col-md-4 col-md-offset-2">
				<table class="table table-hover  no-footer" id="basicTable" role="grid">
					<tr>
						<th class ="text-center bg-complete text-white" rowspan="2" style="vertical-align: middle;min-width: 140px;">RESUMEN</th>
						<th class ="text-center bg-complete text-white">ING. PROP</th>
						<th class ="text-center bg-complete text-white">Apto</th>
						<th class ="text-center bg-complete text-white">Park</th>
						<?php if ($room->luxury == 1): ?>
							<th class ="text-center bg-complete text-white">Sup.Lujo</th>
						<?php else: ?>
						<?php endif ?>
					</tr>
						<tr>
							<td class="text-center total"><?php echo number_format($total,2,',','.'); ?>€</td>
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
						<th class ="text-center bg-complete text-white">ING. PROP</th>
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
				<div class="col-md-12 col-xs-12">
					<div class="panel">
						<ul class="nav nav-tabs nav-tabs-simple bg-info-light fechas" role="tablist" data-init-reponsive-tabs="collapse">
							<?php $dateAux = $date->copy(); ?>
							<?php for ($i=1; $i <= 9 ; $i++) :?>
								<li <?php if($i == 4 ){ echo "class='active'";} ?>>
									<a href="#tab<?php echo $i?>" data-toggle="tab" role="tab" style="padding:10px">
										<?php echo ucfirst($dateAux->copy()->formatLocalized('%b %y'))?>
									</a>
								</li>
								<?php $dateAux->addMonth(); ?>
							<?php endfor; ?>
						</ul>
						<div class="tab-content">
							<?php for ($z=1; $z <= 9; $z++):?>
								<div class="tab-pane <?php if($z == 4){ echo 'active';} ?>" id="tab<?php echo $z ?>">
									<div class="row">
										<div class="col-md-12">
											<table class="fc-border-separate" style="width: 100%">
												<thead>
													<tr >
														<td class="text-center" colspan="<?php echo $arrayMonths[$date->copy()->format('n')]+1 ?>">
															<?php echo  ucfirst($date->copy()->formatLocalized('%B %Y'))?>
														</td> 
													</tr>
													<tr>
														<td rowspan="2" style="width: 1%!important"></td>
														<?php for ($i=1; $i <= $arrayMonths[$date->copy()->format('n')] ; $i++): ?> 
															<td style='border:1px solid black;width: 3%;font-size: 10px' class="text-center">
																<?php echo $i?> 
															</td> 
														<?php endfor; ?>
													</tr>
													<tr>

														<?php for ($i=1; $i <= $arrayMonths[$date->copy()->format('n')] ; $i++): ?> 
															<td style='border:1px solid black;width: 3%;font-size: 10px' class="text-center <?php echo $days[$date->copy()->format('n')][$i]?>">
																<?php echo $days[$date->copy()->format('n')][$i]?> 
															</td> 
														<?php endfor; ?> 
													</tr>
												</thead>
												<tbody>
													<tr>
														<?php $date = $date->startOfMonth() ?>
														<td class="text-center">
															<b title="<?php echo $room->name ?>"><?php echo substr($room->nameRoom, 0,5)?></b>
														</td>

														<?php for ($i=01; $i <= $arrayMonths[$date->copy()->format('n')] ; $i++): ?> 
															<!-- Si existe la reserva para ese dia -->
															<?php if (isset($reservas[$room->id][$date->copy()->format('Y')][$date->copy()->format('n')][$i])): ?>
										
																<?php $calendars = $reservas[$room->id][$date->copy()->format('Y')][$date->copy()->format('n')][$i] ?>
																	<?php if ($calendars->start == $date->copy()->format('Y-m-d')): ?>
																		<td style='border:1px solid grey;width: 3%'>

																			<div class="<?php echo $book->getStatus($calendars->type_book) ?> start" style="width: 100%;float: left;">
																				&nbsp;
																			</div>

																		</td>    
																	<?php elseif($calendars->finish == $date->copy()->format('Y-m-d')): ?>
																		<td style='border:1px solid grey;width: 3%'>
																			<div class="<?php echo $book->getStatus($calendars->type_book) ?> end" style="width: 100%;float: left;">
																				&nbsp;
																			</div>


																		</td>
																	<?php else: ?>

																		<td 
																		style='border:1px solid grey;width: 3%' 
																		title="
																		<?php echo $calendars->customer['name'] ?> 

																		<?php echo 'PVP:'.$calendars->total_price ?>
																		<?php if (isset($payment[$calendars->id])): ?>
																			<?php echo 'PEND:'.($calendars->total_price - $payment[$calendars->id])?>
																		<?php else: ?>
																		<?php endif ?>" 
																		class="<?php echo $book->getStatus($calendars->type_book) ?>"
																		>
																			<?php if ($calendars->type_book == 9): ?>
																				<div style="width: 100%;height: 100%">
																					&nbsp;
																				</div>
																			<?php else: ?>
																				<a href="{{url ('/admin/reservas/update')}}/<?php echo $calendars->id ?>">
																					<div style="width: 100%;height: 100%">
																						&nbsp;
																					</div>
																				</a>
																			<?php endif ?>


																		</td>

																	<?php endif ?>
															<!-- Si no existe nada para ese dia -->
															<?php else: ?>
															
																<td class="<?php echo $days[$date->copy()->format('n')][$i]?>" style='border:1px solid grey;width: 3%'>

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

									</div>
								</div>
								<?php $date = $date->addMonth(); ?>
							<?php endfor ?>
						</div>
					</div>

				</div>
			</div>
		<?php else: ?>
			<div class="col-md-12">
				
				<div class="text-center"><h1 class="text-complete">NO TIENES UNA HABITACION ASOCIADA</h1></div>

			</div>
		<?php endif ?>
	</div>
</div>

<form role="form">
    <div class="form-group form-group-default required" style="display: none">
        <label class="highlight">Message</label>
        <input type="text" hidden="" class="form-control notification-message" placeholder="Type your message here" value="This notification looks so perfect!" required>
    </div>
    <button class="btn btn-success show-notification hidden" id="boton">Show</button>
</form>


@endsection

@section('scripts')
	
	<script src="/assets/plugins/jquery-datatable/media/js/jquery.dataTables.min.js" type="text/javascript"></script>
	<script src="/assets/plugins/jquery-datatable/extensions/TableTools/js/dataTables.tableTools.min.js" type="text/javascript"></script>
	<script src="/assets/plugins/jquery-datatable/media/js/dataTables.bootstrap.js" type="text/javascript"></script>
	<script src="/assets/plugins/jquery-datatable/extensions/Bootstrap/jquery-datatable-bootstrap.js" type="text/javascript"></script>
	<script type="text/javascript" src="/assets/plugins/datatables-responsive/js/datatables.responsive.js"></script>
   	<script type="text/javascript" src="/assets/plugins/datatables-responsive/js/lodash.min.js"></script>
	<script src="/assets/plugins/moment/moment.min.js"></script>
	
	<script type="text/javascript" src="{{asset('/frontend/js/components/moment.js')}}"></script>
	<script type="text/javascript" src="{{asset('/frontend/js/components/daterangepicker.js')}}"></script>
	
	<script src="/assets/js/notifications.js" type="text/javascript"></script>

	<script type="text/javascript">

		$(function() {
		  $(".daterange1").daterangepicker({
		    "buttonClasses": "button button-rounded button-mini nomargin",
		    "applyClass": "button-color",
		    "cancelClass": "button-light",            
		    "startDate": '01 Dec, 17',
		    locale: {
		        format: 'DD MMM, YY',
		        "applyLabel": "Aplicar",
		          "cancelLabel": "Cancelar",
		          "fromLabel": "From",
		          "toLabel": "To",
		          "customRangeLabel": "Custom",
		          "daysOfWeek": [
		              "Do",
		              "Lu",
		              "Mar",
		              "Mi",
		              "Ju",
		              "Vi",
		              "Sa"
		          ],
		          "monthNames": [
		              "Enero",
		              "Febrero",
		              "Marzo",
		              "Abril",
		              "Mayo",
		              "Junio",
		              "Julio",
		              "Agosto",
		              "Septiembre",
		              "Octubre",
		              "Noviembre",
		              "Diciembre"
		          ],
		          "firstDay": 1,
		      },
		      
		  });
		});



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
			$('#fecha').change(function(event) {
			    
			    var year = $(this).val();
			    window.location = '/admin/propietario/'+year;
			});

			$("#cal-bloq").on( "click", function() {
				$("#cal-bloq").hide();
				$("#cal-pago2").hide();				
				$("#cal-pago").show();				
				$("#cal-bloq2").show();
				$('#container-bloq').show('swing');
				$('#container-pago').hide('linear');				
				$('#container-bloq').addClass('showed');
			});
			$("#cal-bloq2").on( "click", function() {
				$("#cal-bloq").show();
				$("#cal-pago2").hide();				
				$("#cal-pago").show();				
				$("#cal-bloq2").hide();
				$('#container-bloq').hide('linear');
				$('#container-bloq').removeClass('showed');
			});

			$("#cal-pago").on( "click", function() {
				$("#cal-bloq").show();
				$("#cal-pago2").show();				
				$("#cal-pago").hide();				
				$("#cal-bloq2").hide();
				$('#container-pago').show('swing');
				$('#container-bloq').hide('linear');

				$('#container-pago').addClass('showed');
			});
			$("#cal-pago2").on( "click", function() {
				$("#cal-bloq").show();
				$("#cal-pago2").hide();				
				$("#cal-pago").show();				
				$("#cal-bloq2").hide();
				$('#container-pago').hide('linear');
				$('#container-pago').removeClass('showed');
			});

			$('.bloquear').click(function(event) {
				
				var id = $(this).attr('data-id');
				var fechas = $('.daterange1').val();

				$.get('/admin/propietario/bloquear', {room: id, fechas: fechas}).success(function( data ) {

					$('.notification-message').val(data);
					document.getElementById("boton").click();
					if (data == "Reserva Guardada") {
						setTimeout('document.location.reload()',1000);
					}else{
                          
                    } 
				});
			});

		});
		
	</script>

@endsection