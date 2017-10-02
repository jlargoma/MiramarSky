@extends('layouts.admin-master')

@section('title') Administrador de reservas MiramarSKI @endsection

@section('externalScripts') 
<link href="/assets/plugins/jquery-datatable/media/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="/assets/plugins/datatables-responsive/css/datatables.responsive.css" rel="stylesheet" type="text/css" media="screen" />
<link href="/assets/css/font-icons.css" rel="stylesheet" type="text/css" />

<script src="/assets/plugins/summernote/css/summernote.css"></script>

<link href="/assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" type="text/css" media="screen">

<link rel="stylesheet" href="{{ asset('/frontend/css/components/daterangepicker.css')}}" type="text/css" />
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.js"></script>
    <!--[if lte IE 9]>
  <link href="/assets/plugins/codrops-dialogFx/dialog.ie.css" rel="stylesheet" type="text/css" media="screen" />
  <![endif]-->
  @endsection

  <?php setlocale(LC_TIME, "ES"); setlocale(LC_TIME, "es_ES"); use \Carbon\Carbon;?>

  <style type="text/css">

  	/* Estados */
  	.Reservado-table{
        background-color: #295d9b !important;
        color: black;
    }
    .Reservado{
        background-color: rgba(0,100,255,0.2)  !important;
        color: black;
    }
  	.Pagada-la-señal{
  		background-color: green  !important;
  		color: black;
  	}
  	.Bloqueado{
  		background-color: orange !important;
  		color: black;
  	}
  	.SubComunidad{
  		background-color: rgba(138,125,190,1) !important;
  		color: black;
  	}
  	/* Estados */

  	.botones{
  		padding-top: 0px!important;
  		padding-bottom: 0px!important;
  	}
  	td{
  		margin: 0px;
  		padding: 0px!important;
  		vertical-align: middle!important;
  	}
  	a {
  		color: black;
  		cursor: pointer;
  	}
  	.table thead tr th { padding-top: 1px!important;padding-bottom: 1px!important;}
  	.S, .D{
  		background-color: rgba(0,0,0,0.2);
  		color: red;
  	}

  	.active>a{
  		color: white!important;
  	}
  	.bg-info-light{
  		background-color: white!important
  	}
  	.bg-info-light>li>a{
  		color: black!important;
  	}
  	.active.res{
  		background-color: #295d9b !important; 
  	}
  	.active.bloq{
  		background-color: orange !important; 
  	}
  	.active.pag{
  		background-color: green !important; 
  	}
  	.res,.bloq,.pag{
  		background-color: white;
  	}
  	
  	.row{margin: -2px!important;}
  	.rev.nav-tabs-simple > li a, .rev.nav-tabs-simple > li a:hover, .rev.nav-tabs-simple > li a:focus {padding:5px!important; }

  	/* Bordes de seccion */          
  	.active.resv > a,.active.cob > a{color: blue!important;font-weight: bold }
    .fechas >li >a {
      color: white!important;
    }
    .fechas > li.active{background-color: rgb(81,81,81);}
  	/* Bordes de seccion */
    .fechas >li {
        background-color: #B0B5B9;
        border-color: #B0B5B9;
    }
    .daterangepicker{
      top: 59%!important;
    }
  </style>

  @section('content')

  <div class="container-fluid container-fixed-lg">
  	<div class="row">
  		<div class="panel" style="margin-bottom: 0px!important">
  			<ul class="nav nav-tabs nav-tabs-simple bg-info-light " role="tablist" data-init-reponsive-tabs="collapse">
  				<li class="resv  active"  style="width: 25%;margin-left: 10px;margin-right: 10px;">
  					<a href="#reservas" data-toggle="tab" role="tab" style="font-size: 15px!important;padding-left: 2px;padding-right: 2px"> RESERVAS </a>
  				</li>
  				<li class="cob text-center" style="width: 30%;margin-left: 10px;margin-right: 10px;">
  					<a href="#cobros" data-toggle="tab" role="tab" style="font-size: 15px!important;padding-left: 2px;padding-right: 2px"> RECEPCION </a>
  				</li>
  				<li class="calend text-center" style="width: 17%">
  					<a href="#calendario"> <i class="fa fa-calendar " aria-hidden="true" style="font-size: 24px!important;padding-left: 2px;padding-right: 2px"></i> </a>
  				</li>
  				<li class="newBook text-center" style="background-color: white;width: 13%">
  					<button class="btn btn-success btn-cons m-b-10 m-t-15" type="button" data-toggle="modal" data-target="#modalNewBook" style="min-width: 10px!important;width: 50px!important">
                <i class="fa fa-plus-square" aria-hidden="true"></i>
            </button>
  				</li>
  			</ul>
  		</div>
  		<div class="tab-content ">
  			<div class="tab-pane active" id="reservas">
  				<div class="row column-seperation ">
  					<div class="panel resv">
  						<ul class="nav nav-tabs nav-tabs-simple bg-info-light rev" role="tablist" data-init-reponsive-tabs="collapse">
  							<li class="active resv" >
  								<a href="#tabPendientes" data-toggle="tab" role="tab" style="font-size: 11px;">Pendientes 
  									<span class="badge"><?php echo count($arrayBooks["nuevas"]) ?></span>
  								</a>
  							</li>
  							<li class="resv">
  								<a href="#tabEspeciales" data-toggle="tab" role="tab" style="font-size: 11px;">Especiales
  									<span class="badge"><?php echo count($arrayBooks["especiales"]) ?></span>
  								</a>
  							</li>
  							<li class="resv">
  								<a href="#tabPagadas" data-toggle="tab" role="tab" style="font-size: 11px;">Confirmadas 
  									<span class="badge"><?php echo count($arrayBooks["pagadas"]) ?></span>
  								</a>
  							</li>
  						</ul>
  					</div>
  					<div class="tab-content ">
  						<div class="tab-pane active table-responsive" id="tabPendientes">
                <div class="container column-seperation ">
  						    @include('backend.planning.listados._pendientes-mobile')
                </div>
  						</div>
  						<div class="tab-pane table-responsive" id="tabEspeciales">
  							<div class="container column-seperation ">
									@include('backend.planning.listados._especiales-mobile')
  							</div>
  						</div>
  						<div class="tab-pane table-responsive " id="tabPagadas">
  							<div class="container column-seperation ">.
                  @include('backend.planning.listados._pagadas-mobile')									
  							</div>
  						</div>
  					</div>
  				</div>
  			</div>

  			<div class="tab-pane" id="cobros">
  				<div class="row column-seperation">
  					<div class="panel in-out">
  						<ul class="nav nav-tabs nav-tabs-simple bg-info-light rev" role="tablist" data-init-reponsive-tabs="collapse">
  							<li class="active in text-center cob" style="width: 50%">
  								<a href="#tabIn" data-toggle="tab" role="tab" style="font-size: 11px;">CHECK IN
  								</a>
  							</li>
  							<li class="out text-center cob"  style="width: 50%">
  								<a href="#tabOut" data-toggle="tab" role="tab" style="font-size: 11px;">CHECK OUT
  								</a>
  							</li>
  						</ul>
  					</div>
  					<div class="tab-content">
  						<div class="tab-pane active table-responsive" id="tabIn">
  							<table class="table table-striped dataTable no-footer">
  								<thead>
  									<th class="bg-success text-white text-center">Nombre</th>
  									<th class="bg-success text-white text-center">In</th>
  									<th class="bg-success text-white text-center">Out</th>
  									<th class="bg-success text-white text-center"><i class="fa fa-clock-o" aria-hidden="true"></i> In</th>
  									<th class="bg-success text-white text-center">Apto</th>
  									<th class="bg-success text-white text-center">Pendiente</th>
  								</thead>
  								<tbody>
  									<?php foreach ($proxIn as $book): ?>
  										<tr>
  											<td class="text-center sm-p-t-10 sm-p-b-10">
  												<a class="cobro" data-id="<?php echo $book->id ?>" data-toggle="modal" data-target="#myModal">
  													<?php echo substr($book->customer->name,0,10) ?>
  												</a>
  											</td>
  											<td class="text-center sm-p-t-10 sm-p-b-10"><?php echo Carbon::CreateFromFormat('Y-m-d',$book->start)->formatLocalized('%d-%b') ?></td>
  											<td class="text-center sm-p-t-10 sm-p-b-10"><?php echo Carbon::CreateFromFormat('Y-m-d',$book->finish)->formatLocalized('%d-%b') ?></td>
  											<td class="text-center sm-p-t-10 sm-p-b-10">Hora</td>
  											<td class="text-center sm-p-t-10 sm-p-b-10">Apto</td>
  											<td class="text-center sm-p-t-10 sm-p-b-10">
  												<?php if (isset($payment[$book->id])): ?>
  													<p style="{{ $book->total_price - $payment[$book->id] > 0 ? 'color:red' : '' }}"><?php echo number_format($book->total_price - $payment[$book->id],2,',','.') ?> €</p>
  												<?php else: ?>
  													<p style="color:red"><?php echo number_format($book->total_price,2,',','.') ?> €<p>
  												<?php endif ?>
  											</td>
  										</tr>
  									<?php endforeach ?>
  								</tbody>
  							</table>
  						</div>
  						<div class="tab-pane table-responsive" id="tabOut">
  							<table class="table table-striped dataTable no-footer">
  								<thead>
  									<th class="bg-success text-white text-center">Nombre</th>
  									<th class="bg-success text-white text-center">In</th>
  									<th class="bg-success text-white text-center">Out</th>
  									<th class="bg-success text-white text-center"><i class="fa fa-clock-o" aria-hidden="true"></i> Out</th>
  									<th class="bg-success text-white text-center">Apto</th>
  									<th class="bg-success text-white text-center">Pendiente</th>
  								</thead>
  								<tbody>
  									<?php foreach ($proxOut as $book): ?>
  										<tr>
  											<td class="text-center sm-p-t-10 sm-p-b-10">
  												<a class="cobro" data-id="<?php echo $book->id ?>" data-toggle="modal" data-target="#myModal">
  													<?php echo substr($book->customer->name,0,10) ?>
  												</a>
  											</td>
  											<td class="text-center sm-p-t-10 sm-p-b-10"><?php echo Carbon::CreateFromFormat('Y-m-d',$book->start)->formatLocalized('%d-%b') ?></td>
  											<td class="text-center sm-p-t-10 sm-p-b-10"><?php echo Carbon::CreateFromFormat('Y-m-d',$book->finish)->formatLocalized('%d-%b') ?></td>

  											<td class="text-center sm-p-t-10 sm-p-b-10">
  												<?php if (isset($payment[$book->id])): ?>
  													<?php echo number_format($book->total_price - $payment[$book->id],2,',','.') ?> €
  												<?php else: ?>
  													<?php echo number_format($book->total_price,2,',','.') ?> €
  												<?php endif ?>
  											</td>
  										</tr>
  									<?php endforeach ?>
  								</tbody>
  							</table>
  						</div>
  					</div>
  				</div>
  			</div>

  			<div class="tab-pane " id="tabNueva">
				<div class="row column-seperation ">
					<div class="row">
				    @include('backend.planning.listados._nueva-mobile')   
					</div>
				</div>
  			</div>
  		</div>

  		<!-- Calendario -->
    		<div id="calendario" style="border-top: 5px solid black">
    			<div class="container">
    				<div class="panel">
    					<ul class="nav nav-tabs nav-tabs-simple bg-info-light fechas" role="tablist" data-init-reponsive-tabs="collapse">
    						<?php $dateAux = $inicio->copy(); ?>
    						<?php for ($i=1; $i <= 5 ; $i++) :?>
    							<li <?php if($i == 1 ){ echo "class='active'";} ?> style="width:20%!important">
    								<a href="#tab<?php echo $i?>" data-toggle="tab" role="tab" style="padding:5px;font-size: 17px;">
    									<?php echo ucfirst($dateAux->copy()->formatLocalized('%b'))?>
    								</a>
    							</li>
    							<?php $dateAux->addMonth(); ?>
    						<?php endfor; ?>
    					</ul>
    					<div class="tab-content">

    						<?php for ($z=1; $z <= 5; $z++):?>
    							<div class="table-responsive tab-pane <?php if($z == 1){ echo 'active';} ?>" id="tab<?php echo $z ?>" style="padding-bottom: 10px">
    								<div class="row">
    									<div class="col-md-12">
    										<table class="fc-border-separate" style="width: 100%">
    											<thead>
    												<tr>
    													<td class="text-center" colspan="<?php echo $arrayMonths[$inicio->copy()->format('n')]+1 ?>">
    														<?php echo  ucfirst($inicio->copy()->formatLocalized('%B %Y'))?>
    													</td> 
    												</tr>
    												<tr>
    													<td rowspan="2" style="width: 1%!important"></td>
    													<?php for ($i=1; $i <= $arrayMonths[$inicio->copy()->format('n')] ; $i++): ?> 
    														<td style='border:1px solid black;width: 3%;font-size: 10px;min-width: 12px' class="text-center">
    															<?php echo $i?> 
    														</td> 
    													<?php endfor; ?>
    												</tr>
    												<tr>

    													<?php for ($i=1; $i <= $arrayMonths[$inicio->copy()->format('n')] ; $i++): ?> 
    														<td style='border:1px solid black;width: 3%;font-size: 10px' class="text-center <?php echo $days[$inicio->copy()->format('n')][$i]?>">
    															<?php echo $days[$inicio->copy()->format('n')][$i]?> 
    														</td> 
    													<?php endfor; ?> 
    												</tr>
    											</thead>
    											<tbody>

    												<?php foreach ($roomscalendar as $room): ?>
    													<tr>
    														<?php $date = $inicio->startOfMonth() ?>
    														<td class="text-center"><b><?php echo substr($room->nameRoom, 0,5)?></b></td>

    														<?php for ($i=01; $i <= $arrayMonths[$inicio->copy()->format('n')] ; $i++): ?> 

    															<?php if (isset($arrayReservas[$room->id][$inicio->copy()->format('Y')][$inicio->copy()->format('n')][$i])): ?>
    																<?php if ($arrayReservas[$room->id][$inicio->copy()->format('Y')][$inicio->copy()->format('n')][$i]->start == $inicio->copy()->format('Y-m-d')): ?>
    																	<td style='border:1px solid grey;width: 3%'>
    																		<div style="width: 50%;float: left;">
    																			&nbsp;
    																		</div>
    																		<div class="<?php echo $book->getStatus($arrayReservas[$room->id][$inicio->copy()->format('Y')][$inicio->copy()->format('n')][$i]->type_book) ?> start" style="width: 50%;float: left;">
    																			&nbsp;
    																		</div>

    																	</td>    
    																<?php elseif($arrayReservas[$room->id][$inicio->copy()->format('Y')][$inicio->copy()->format('n')][$i]->finish == $inicio->copy()->format('Y-m-d')): ?>
    																	<td style='border:1px solid grey;width: 3%'>
    																		<div class="<?php echo $book->getStatus($arrayReservas[$room->id][$inicio->copy()->format('Y')][$inicio->copy()->format('n')][$i]->type_book) ?> end" style="width: 50%;float: left;">
    																			&nbsp;
    																		</div>
    																		<div style="width: 50%;float: left;">
    																			&nbsp;
    																		</div>


    																	</td>
    																<?php else: ?>

    																	<td style='border:1px solid grey;width: 3%' title="<?php echo $arrayReservas[$room->id][$inicio->copy()->format('Y')][$inicio->copy()->format('n')][$i]->customer['name'] ?>" class="<?php echo $book->getStatus($arrayReservas[$room->id][$inicio->copy()->format('Y')][$inicio->copy()->format('n')][$i]->type_book) ?>">

    																		<a href="{{url ('/admin/reservas/update')}}/<?php echo $arrayReservas[$room->id][$inicio->copy()->format('Y')][$inicio->copy()->format('n')][$i]->id ?>">
    																			<div style="width: 100%;height: 100%">
    																				&nbsp;
    																			</div>
    																		</a>

    																	</td>

    																<?php endif ?>
    															<?php else: ?>
    																<td class="<?php echo $days[$inicio->copy()->format('n')][$i]?>" style='border:1px solid grey;width: 3%'>

    																</td>
    															<?php endif; ?>
    															<?php if ($inicio->copy()->format('d') != $arrayMonths[$inicio->copy()->format('n')]): ?>
    																<?php $date = $inicio->addDay(); ?>
    															<?php else: ?>
    																<?php $date = $inicio->startOfMonth() ?>
    															<?php endif ?>

    														<?php endfor; ?> 
    													</tr>

    												<?php endforeach; ?>
    											</tbody>
    										</table>
    										<?php $date = $date->addMonth(); ?>
    									</div>
    								</div>
    							</div>
    						<?php endfor; ?>

    					</div>
    				</div> 
    			</div>
    		</div>
  		<!-- Calendario -->

  	</div>
  </div>

  <!-- Modal de cobros -->
  <div class="modal fade slide-up disable-scroll in" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
  	<div class="modal-dialog modal-md">
  		<div class="modal-content-wrapper">
  			<div class="modal-content">
  				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-50" style="font-size: 35px"></i>
  				</button>
  				<div class="container-xs-height full-height">
  					<div class="row-xs-height">
  						<div class="modal-body col-xs-height col-middle text-center p-0">

  						</div>
  					</div>
  				</div>
  			</div>
  		</div>
  		<!-- /.modal-content -->
  	</div>
  	<!-- /.modal-dialog -->
  </div>
  <!-- Modal de Cobros -->

  <div class="modal fade slide-up in" id="modalNewBook" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg">
          <div class="modal-content-wrapper">
              <div class="modal-content">
                  @include('backend.planning.listados._nueva-mobile')
              </div>
          </div>
          <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
  </div>


  @endsection

  @section('scripts')


  <!-- END OVERLAY -->
  <!-- BEGIN VENDOR JS -->
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script src="/assets/plugins/jquery-datatable/media/js/jquery.dataTables.min.js" type="text/javascript"></script>
  <script src="/assets/plugins/jquery-datatable/extensions/TableTools/js/dataTables.tableTools.min.js" type="text/javascript"></script>
  <script src="/assets/plugins/jquery-datatable/media/js/dataTables.bootstrap.js" type="text/javascript"></script>
  <script src="/assets/plugins/jquery-datatable/extensions/Bootstrap/jquery-datatable-bootstrap.js" type="text/javascript"></script>
  <script type="text/javascript" src="/assets/plugins/datatables-responsive/js/datatables.responsive.js"></script>
  <script type="text/javascript" src="/assets/plugins/datatables-responsive/js/lodash.min.js"></script>
  <script type="text/javascript" src="/assets/js/canvasjs.min.js"></script>
      

  <script type="text/javascript" src="{{asset('/frontend/js/components/moment.js')}}"></script>
  <script type="text/javascript" src="{{asset('/frontend/js/components/daterangepicker.js')}}"></script>


  <script src="/assets/plugins/bootstrap3-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
  <script type="text/javascript" src="/assets/plugins/jquery-autonumeric/autoNumeric.js"></script>
  <script type="text/javascript" src="/assets/plugins/bootstrap-tag/bootstrap-tagsinput.min.js"></script>
  <script type="text/javascript" src="/assets/plugins/jquery-inputmask/jquery.inputmask.min.js"></script>
  <script src="/assets/plugins/bootstrap-form-wizard/js/jquery.bootstrap.wizard.min.js" type="text/javascript"></script>
  <script src="/assets/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
  <script src="/assets/plugins/summernote/js/summernote.min.js" type="text/javascript"></script>
  <script src="/assets/plugins/moment/moment.min.js"></script>
  <script src="/assets/plugins/bootstrap-typehead/typeahead.bundle.min.js"></script>
  <script src="/assets/plugins/bootstrap-typehead/typeahead.jquery.min.js"></script>
  <script src="/assets/plugins/handlebars/handlebars-v4.0.5.js"></script>

  <script src="/assets/plugins/summernote/js/summernote.js"></script>
  <script type="text/javascript" src="//assets/plugins/jquery-autonumeric/autoNumeric.js"></script>

  <!-- END PAGE LEVEL JS -->

  <script type="text/javascript">
      $(document).ready(function() {          

          $('.cob , .newBook').click(function(event) {
            $('#calendario').hide();
          });

          $('.resv , .calend').click(function(event) {
            $('#calendario').show();
          });


          $('.status,.room').change(function(event) {
              var id = $(this).attr('data-id');
              var clase = $(this).attr('class');
              
              if (clase == 'status form-control') {
                  var status = $(this).val();
                  var room = "";
              }else if(clase == 'room'){
                  var room = $(this).val();
                  var status = "";
              }
              $.get('/admin/reservas/changeBook/'+id, {status:status,room: room}, function(data) {
                  window.location.reload();
              });
          });

          // Mdoal
          $('.cobro').click(function(event) {
            var id = $(this).attr('data-id');
            $.get('/admin/reservas/cobrar/'+id, function(data) {
              $('.modal-body').empty().append(data);
            });
          });
          // Modal
          
          $('#fecha').change(function(event) {
              
              var year = $(this).val();
              window.location = '/admin/reservas/'+year;
          });          
      });
      
  </script>

@endsection