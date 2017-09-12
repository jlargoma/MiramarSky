@extends('layouts.admin-master')

@section('title') Administrador de reservas MiramarSKI @endsection

@section('externalScripts') 
<link href="/assets/plugins/jquery-datatable/media/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="/assets/plugins/jquery-datatable/extensions/FixedColumns/css/dataTables.fixedColumns.min.css" rel="stylesheet" type="text/css" />
<link href="/assets/plugins/datatables-responsive/css/datatables.responsive.css" rel="stylesheet" type="text/css" media="screen" />

<link href="/assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" type="text/css" media="screen">
<link href="/assets/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" media="screen">
<link href="/assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" media="screen">

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.js"></script>
    <!--[if lte IE 9]>
  <link href="/assets/plugins/codrops-dialogFx/dialog.ie.css" rel="stylesheet" type="text/css" media="screen" />
  <![endif]-->
  @endsection

  <?php setlocale(LC_TIME, "ES"); setlocale(LC_TIME, "es_ES"); use \Carbon\Carbon;?>

  <style type="text/css">

  	/* Estados */
  	.Reservado{
  		background-color: green !important;
  		color: black;
  	}
  	.Pagada-la-señal{
  		background-color: red  !important;
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
  		background-color: green !important; 
  	}
  	.active.bloq{
  		background-color: orange !important; 
  	}
  	.active.pag{
  		background-color: red !important; 
  	}
  	.res,.bloq,.pag{
  		background-color: white;
  	}
  	
  	.row{margin: -2px!important;}
  	.rev.nav-tabs-simple > li a, .rev.nav-tabs-simple > li a:hover, .rev.nav-tabs-simple > li a:focus {padding:5px!important; }

  	/* Bordes de seccion */          
  	.active.resv > a,.active.cob > a{color: blue!important;font-weight: bold }
    
    .fechas > li.active{background-color: red;}
  	/* Bordes de seccion */
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
  				<li class="text-center" style="width: 17%">
  					<a href="#calendario"> <i class="fa fa-calendar " aria-hidden="true" style="font-size: 24px!important;padding-left: 2px;padding-right: 2px"></i> </a>
  				</li>
  				<li class=" text-center" style="background-color: white;width: 13%">
  					<a href="#tabNueva" data-toggle="tab" role="tab" style="padding-left: 2px;padding-right: 2px">
  						<i class="fa fa-plus-circle " style="color:green;font-size: 24!important" aria-hidden="true"></i>
  					</a>
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
  								<table class="table table-hover dataTable no-footer">
  									<thead>
  										<th class="Reservado text-white text-center">Nombre</th>
  										<th class="Reservado text-white text-center" style="min-width:35px">In</th>
  										<th class="Reservado text-white text-center" style="min-width:35px ">Out</th>
  										<th class="Reservado text-white text-center">Pax</th>
  										<th class="Reservado text-white text-center">Tel</th>
  										<th class="Reservado text-white text-center" style="min-width:100px">Apart</th>
  										<th class="Reservado text-white text-center"><i class="fa fa-moon-o"></i></th>
  										<th class="Reservado text-white text-center" style="min-width:50px">PVP</th>
  										<th class="Reservado text-white text-center" style="min-width:100px">Estado</th>
  									</thead>
  									<tbody>
  										<?php foreach ($arrayBooks["nuevas"] as $nueva): ?>
  											<tr>
  												<td class="text-center sm-p-t-10 sm-p-b-10">
                            <a href="{{url ('/admin/reservas/update')}}/<?php echo $nueva->id ?>"><?php echo $nueva->customer->name ?></a>
                          </td>
  												<td class="text-center sm-p-t-10 sm-p-b-10"><?php echo Carbon::CreateFromFormat('Y-m-d',$nueva->start)->format('d-M') ?></td>
  												<td class="text-center sm-p-t-10 sm-p-b-10"><?php echo Carbon::CreateFromFormat('Y-m-d',$nueva->finish)->format('d-M') ?></td>
  												<td class="text-center sm-p-t-10 sm-p-b-10"><?php echo $nueva->pax ?></td>
  												<td class="text-center sm-p-t-10 sm-p-b-10"><a href="tel:<?php echo $nueva->customer->phone ?>"><i class="fa fa-phone"></i></a></td>
  												<td class="text-center sm-p-t-10 sm-p-b-10"><?php echo $nueva->room->name ?></td>
  												<td class="text-center sm-p-t-10 sm-p-b-10"><?php echo $nueva->nigths ?></td>
  												<td class="text-center sm-p-t-10 sm-p-b-10"><?php echo $nueva->total_price ?> €</td>
  												<td class="text-center sm-p-t-10 sm-p-b-10 sm-p-l-10 sm-p-r-10">
                            <select class="status form-control" data-id="<?php echo $book->id ?>" >
                              <?php for ($i=1; $i < 9; $i++): ?> 
                                  <?php if ($i == $book->type_book): ?>
                                      <option selected value="<?php echo $i ?>"  data-id="<?php echo $book->id ?>"><?php echo $book->getStatus($i) ?></option>
                                  <?php else: ?>
                                      <option value="<?php echo $i ?>"><?php echo $book->getStatus($i) ?></option>
                                  <?php endif ?>                                          
                                   
                              <?php endfor; ?>
                            </select>
                          </td>
  											</tr>
  										<?php endforeach ?>
  									</tbody>
  								</table>
  							</div>
  						</div>
  						<div class="tab-pane table-responsive" id="tabEspeciales">
  							<div class="container column-seperation ">
  								<div>
  									<table class="table table-hover dataTable no-footer">
  										<thead>
  											<th class="Bloqueado text-white text-center">Nombre</th>
  											<th class="Bloqueado text-white text-center" style="min-width:35px">In</th>
  											<th class="Bloqueado text-white text-center" style="min-width:35px ">Out</th>
  											<th class="Bloqueado text-white text-center">Pax</th>
  											<th class="Bloqueado text-white text-center">Tel</th>
  											<th class="Bloqueado text-white text-center" style="min-width:100px">Apart</th>
  											<th class="Bloqueado text-white text-center"><i class="fa fa-moon-o"></i></th>
  											<th class="Bloqueado text-white text-center" style="min-width:50px">PVP</th>
  											<th class="Bloqueado text-white text-center" style="min-width:100px">Estado</th>
  										</thead>
  										<tbody>
  											<?php foreach ($arrayBooks["especiales"] as $especial): ?>
  												<tr>
  													<td class="text-center sm-p-t-10 sm-p-b-10"><?php echo $especial->customer->name ?></td>
  													<td class="text-center sm-p-t-10 sm-p-b-10"><?php echo Carbon::CreateFromFormat('Y-m-d',$especial->start)->format('d-M') ?></td>
  													<td class="text-center sm-p-t-10 sm-p-b-10"><?php echo Carbon::CreateFromFormat('Y-m-d',$especial->finish)->format('d-M') ?></td>
  													<td class="text-center sm-p-t-10 sm-p-b-10"><?php echo $especial->pax ?></td>
  													<td class="text-center sm-p-t-10 sm-p-b-10"><a href="tel:<?php echo $especial->customer->phone ?>"><i class="fa fa-phone"></i></a></td>
  													<td class="text-center sm-p-t-10 sm-p-b-10"><?php echo $especial->room->name ?></td>
  													<td class="text-center sm-p-t-10 sm-p-b-10"><?php echo $especial->nigths ?></td>
  													<td class="text-center sm-p-t-10 sm-p-b-10"><?php echo $especial->total_price ?> €</td>
		                        <td class="text-center sm-p-t-10 sm-p-b-10 sm-p-l-10 sm-p-r-10">
                              <select class="status form-control" data-id="<?php echo $book->id ?>" >
                                <?php for ($i=1; $i < 9; $i++): ?> 
                                    <?php if ($i == $book->type_book): ?>
                                        <option selected value="<?php echo $i ?>"  data-id="<?php echo $book->id ?>"><?php echo $book->getStatus($i) ?></option>
                                    <?php else: ?>
                                        <option value="<?php echo $i ?>"><?php echo $book->getStatus($i) ?></option>
                                    <?php endif ?>                                          
                                     
                                <?php endfor; ?>
                              </select>
                            </td>
  												</tr>
  											<?php endforeach ?>
  										</tbody>
  									</table>
  								</div>
  							</div>
  						</div>
  						<div class="tab-pane table-responsive " id="tabPagadas">
  							<div class="container column-seperation ">
  								<div>
  									<table class="table table-hover dataTable no-footer">
  										<thead>
  											<th class="Pagada-la-señal text-white text-center">Nombre</th>
  											<th class="Pagada-la-señal text-white text-center" style="min-width:35px">In</th>
  											<th class="Pagada-la-señal text-white text-center" style="min-width:35px ">Out</th>
  											<th class="Pagada-la-señal text-white text-center">Pax</th>
  											<th class="Pagada-la-señal text-white text-center">Tel</th>
  											<th class="Pagada-la-señal text-white text-center" style="min-width:100px">Apart</th>
  											<th class="Pagada-la-señal text-white text-center"><i class="fa fa-moon-o"></i></th>
  											<th class="Pagada-la-señal text-white text-center" style="min-width:50px">PVP</th>
  											<th class="Pagada-la-señal text-white text-center" style="min-width:100px">Estado</th>
  										</thead>
  										<tbody>
  											<?php foreach ($arrayBooks["pagadas"] as $pagada): ?>
  												<tr>
  													<td class="text-center sm-p-t-10 sm-p-b-10"><a href="{{url ('/admin/reservas/update')}}/<?php echo $pagada->id ?>"><?php echo $pagada->customer->name ?></a></td>
  													<td class="text-center sm-p-t-10 sm-p-b-10"><?php echo Carbon::CreateFromFormat('Y-m-d',$pagada->start)->format('d-M') ?></td>
  													<td class="text-center sm-p-t-10 sm-p-b-10"><?php echo Carbon::CreateFromFormat('Y-m-d',$pagada->finish)->format('d-M') ?></td>
  													<td class="text-center sm-p-t-10 sm-p-b-10"><?php echo $pagada->pax ?></td>
  													<td class="text-center sm-p-t-10 sm-p-b-10"><a href="tel:<?php echo $pagada->customer->phone ?>"><i class="fa fa-phone"></i></a></td>
  													<td class="text-center sm-p-t-10 sm-p-b-10"><?php echo $pagada->room->name ?></td>
  													<td class="text-center sm-p-t-10 sm-p-b-10"><?php echo $pagada->nigths ?></td>
  													<td class="text-center sm-p-t-10 sm-p-b-10">
  														<?php echo $pagada->total_price ?> €<br>
  														<?php if (isset($payment[$book->id])): ?>
  															<?php echo "<p style='color:red'>".$payment[$book->id]."</p>" ?>
  														<?php else: ?>
  														<?php endif ?>
  													</td>
		                        <td class="text-center sm-p-t-10 sm-p-b-10 sm-p-l-10 sm-p-r-10">
                              <select class="status form-control" data-id="<?php echo $book->id ?>" >
                                <?php for ($i=1; $i < 9; $i++): ?> 
                                    <?php if ($i == $book->type_book): ?>
                                        <option selected value="<?php echo $i ?>"  data-id="<?php echo $book->id ?>"><?php echo $book->getStatus($i) ?></option>
                                    <?php else: ?>
                                        <option value="<?php echo $i ?>"><?php echo $book->getStatus($i) ?></option>
                                    <?php endif ?>                                          
                                     
                                <?php endfor; ?>
                              </select>
                            </td>
  												</tr>
  											<?php endforeach ?>
  										</tbody>
  									</table>
  								</div>
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
					    <div class="col-md-12 push-30">
					        <div class="col-md-12">
							    <div class="row">
							        <div class="container-fluid padding-10 sm-padding-10" style="background-color: rgba(0,0,255,0.1)">
							        	<div class="col-md-12 col-xs-12 text-center">
							        	    <p style="font-size: 14px">
							        	        <br>
							        	        Reserva nueva 
							        	        <!-- Desplegable -->
							        	    </p>
							        	</div>
							        	<div class="clear"></div>
							        	
							        	<div class="col-xs-12 sm-p-l-0 sm-p-r-0">
							        	    <div class="panel">
		        	    			        	<form class="form-horizontal" action="{{ url('/admin/reservas/create') }}" method="post">
		        	    			        		<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
		        	    			        		<div class="col-xs-12 col-md-12 push-20">
		        	    			        			<p class="text-center">Cliente</p>
		        	    			        		</div>
	        	    			                <div style="padding: 0px 0px 0px 0px;">
	        	    			                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
	        	    			                    <div class="input-group col-xs-12">
	        	    			                        <div class="col-xs-6">
	        	    			                           <input class="form-control" type="text" name="name" value="" placeholder="Nombre">
	        	    			                        </div>
	        	    			                        <div class="col-xs-6">
	        	    			                            <input class="form-control" type="number" name="phone" value="" placeholder="Telefono"> 
	        	    			                        </div>
	        	    			                        <br><br>
	        	    			                        <div class="col-xs-12">
	        	    			                            <input class="form-control" type="email" name="email" value="" placeholder="Email">  
	        	    			                        </div>
	        	    			                         
	        	    			                        <div style="clear: both;"></div>
	        	    			                    </div>                                            
	        	    			                </div>
            													<br>
            													<div class="col-xs-12 col-md-12 push-20">
            														<p class="text-center">Reserva</p>
            													</div>
      	    			                		<div style="padding: 0px 0px 0px 0px;">
  	    			                		        
    			                		            <div class="col-md-4">
                                              <label>Entrada</label>
                                              <div class="input-prepend input-group">
                                                <span class="add-on input-group-addon"><i
                                                              class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                                                <input type="text" style="width: 100%" name="reservation" id="daterangepicker" class="form-control" value="" />
                                              </div>
                                          </div>

    			                		            <br>

    			                		            <div class="col-xs-3 " >
    			                		                <label class="sm-pull-left"><i class="fa fa-moon-o"></i></label>
    			                		                <input type="text" class="nigths sm-pull-right" name="nigths" id="nigths" style="width: 60%" style="border:none">
    			                		            </div> 

    			                		            <div class="col-xs-3 sm-padding-0">
                                              <div class="col-xs-3 sm-padding-0">
                                                <label class="sm-pull-left"><i class="fa fa-user"></i></label>
                                              </div>
    			                		                <div class="col-xs-8 sm-padding-0">
                                                <input  type="text" class="form-control full-width pax sm-pull-right" name="pax"> 
                                              </div>		                		                   
    			                		            </div>
                                          
    			                		            <div class="col-xs-6 sm-padding-0">
                                            <div class="col-xs-3">
                                               <label ><i class="fa fa-home" aria-hidden="true"></i></label>
                                            </div>
  			                		                <div class="col-xs-8 sm-padding-0">
                                              <select class="form-control full-width newroom" data-init-plugin="select2" name="newroom" id="newroom" >
                                                <?php foreach ($rooms as $room): ?>
                                                    <option value="<?php echo $room->id ?>"><?php echo $room->name ?></option>
                                                <?php endforeach ?>
                                            </select>
                                            </div>
    			                		            </div>

                                          <div class="clear-both"></div><br>

    			                		            <div class="col-xs-5 col-xs-offset-1 sm-padding-0 sm-no-margin">
  			                		                <div class="col-xs-3">
                                              <label >P</label>      
                                            </div>
                                            <div class="col-xs-8 sm-padding-0">
                                              <select class=" form-control full-width parking" data-init-plugin="select2" name="parking">
                                                <?php for ($i=1; $i <= 4 ; $i++): ?>
                                                    <option value="<?php echo $i ?>"><?php echo $book->getParking($i) ?></option>
                                                <?php endfor;?>
                                            </select>
                                            </div>
    			                		            </div>

    			                		            <div class="col-xs-7">
                                            <div class="col-xs-3">
                                              <label><i class="fa fa-star"></i></label>
                                            </div>
  			                		                <div class="col-xs-8 sm-no-padding sm-no-margin">
                                              <select class=" form-control full-width type_luxury" data-init-plugin="select2" name="type_luxury">
                                                <?php for ($i=1; $i <= 4 ; $i++): ?>
                                                    <option value="<?php echo $i ?>"><?php echo $book->getSupLujo($i) ?></option>
                                                <?php endfor;?>
                                            </select>
                                            </div>
    			                		            </div>

    			                		            <div class="clear-both"></div>

    			                		            <div class="col-xs-5 col-xs-offset-1">                                                        
    			                		                <label>Cost Agencia</label>
    			                		                <input type="text" class="agencia form-control pvpAgencia" name="agencia" value="0">
    			                		            </div>

    			                		            <div class="col-xs-5">
    			                		                <label>Agencia</label>
    			                		                <select class=" form-control full-width agency" data-init-plugin="select2" name="agency">
                                                      <option value="0"></option>
    			                		                    <?php for ($i=1; $i <= 2 ; $i++): ?>
    			                		                        <option value="<?php echo $i ?>"><?php echo $book->getAgency($i) ?></option>
    			                		                    <?php endfor;?>
    			                		                </select>
    			                		            </div>
  	    			                		        
    			                		            <div class="col-xs-5 col-xs-offset-1">
    			                		                <label>Extras</label>
    			                		                <select class="full-width select2-hidden-accessible extras" data-init-plugin="select2" multiple="" name="extras[]" tabindex="-1" aria-hidden="true">
    			                		                    <?php foreach ($extras as $extra): ?>
    			                		                        <option value="<?php echo $extra->id ?>"><?php echo $extra->name ?></option>
    			                		                    <?php endforeach ?>
    			                		                </select>
    			                		            </div>

    			                		            <div class="col-xs-5">
    			                		                <label>PVP Extras</label>
    			                		                <input type="text" class="pvp-exctra form-control" name="pvp-extra" disabled>
    			                		            </div>

    			                		            <div class="col-xs-4 m-t-10 p-b-10 text-white" style="background-color: #0c685f">
    			                		                <label>PVP</label>
    			                		                <input type="text" class="form-control total text-white" name="total" value="" style="font-weight: bold;width: 100%;border:none;background: #0c685f">
    			                		            </div> 

    			                		            <div class="col-xs-4 m-t-10 p-b-10 text-white" style="background-color: #99D9EA">
    			                		                <label>COSTE</label>
    			                		                <input type="text" class="form-control cost text-white" name="cost" value="" disabled style="font-weight: bold;width: 100%;border:none;background: #99D9EA">
    			                		            </div>

    			                		            <div class="col-xs-4 m-t-10 p-b-10 text-white" style="background-color: #ff7f27">
    			                		                <label>BENº</label>
    			                		                <input type="text" class="form-control beneficio text-white" name="beneficio" value="" disabled style="font-weight: bold;width: 100%;border:none;background: #ff7f27">
    			                		            </div>
                                          
  	    			                		    </div>
  	    			                		        <br><br>
  	    			                		        <div class="input-group col-md-12">

    			                		                <div class="col-xs-12">
    			                		                    <label>Comentarios Usuario</label>
    			                		                    <textarea class="form-control" name="comments" style="width: 100%" rows="4">
    			                		                    </textarea>
    			                		                </div>
  	    			                		            
  	    			                		            <!-- Añadir boton para escribir comentario interno -->

    			                		                <div class="col-xs-12">
    			                		                    <label>Comentarios Internos</label>
    			                		                    <textarea class="form-control" name="book_comments" style="width: 100%" rows="4">
    			                		                    </textarea>
    			                		                </div>
  	    			                		        </div> 
  	    			                		        <div class="input-group col-md-12">
  	    			                		            
  	    			                		        </div> 
  	    			                		        <br>
  	    			                		        <div class="input-group col-xs-12 text-center">
  	    			                		            <button class="form-control btn btn-complete active" type="submit" style="width: 90%;margin-left: 5%"><p style="font-size: 22px">Guardar</p></button>
  	    			                		        </div>   
  	    			                		        <br>                    
      	    			                		</div>
		        	    			        	</form>
							        	    </div>
							        	</div>
							        	
							        </div>
							    </div> 
					        </div>
					    </div>
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

  @endsection

  @section('scripts')


  <!-- END OVERLAY -->
  <!-- BEGIN VENDOR JS -->
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
  <!-- END PAGE LEVEL JS -->

  <script type="text/javascript">

      $(document).ready(function() {          

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
          // 
          var start  = 0;
          var finish = 0;
          var noches = 0;
          var price = 0;
          var cost = 0;

          $('.pax').click(function(event) {
              var fechas = $('#daterangepicker').val();
              var info = fechas.split('-');
              var inicio = info[0];
              var final = info[1];
              console.log(inicio);
              var start = new Date(inicio.substring(3,5) + '/' + inicio.substring(0,2) + '/' + inicio.substring(6,10));
              var finish = new Date(final.substring(4,6)+ '/' +  final.substring(1,3)+ '/' + final.substring(7,11));
              var timeDiff = Math.abs(finish.getTime() - start.getTime());
              var noches = Math.ceil(timeDiff / (1000 * 3600 * 24)); 
              $('.nigths').val(noches);

          });

          $('#newroom, .pax, .parking, .agencia, .type_luxury').change(function(event){ 

              var room = $('#newroom').val();
              var pax = $('.pax').val();
              var park = $('.parking').val();
              var lujo = $('.type_luxury').val();
              var beneficio = 0;
              var costPark = 0;
              var pricePark = 0;
              var costLujo = 0;
              var priceLujo = 0;
              var agencia = 0;
              var beneficio_ = 0;

              var fechas = $('#daterangepicker').val();
              var info = fechas.split('-');
              var inicio = info[0];
              var final = info[1];
              console.log(inicio);
              var start = new Date(inicio.substring(3,5) + '/' + inicio.substring(0,2) + '/' + inicio.substring(6,10));
              var finish = new Date(final.substring(4,6)+ '/' +  final.substring(1,3)+ '/' + final.substring(7,11));
              var timeDiff = Math.abs(finish.getTime() - start.getTime());
              var noches = Math.ceil(timeDiff / (1000 * 3600 * 24)); 
              start = inicio.substring(3,5) + '/' + inicio.substring(0,2) + '/' + inicio.substring(6,10);
              finish = final.substring(4,6)+ '/' +  final.substring(1,3)+ '/' + final.substring(7,11);
             

              $.get('/admin/apartamentos/getPaxPerRooms/'+room).success(function( data ){

                  if (pax < data) {
                      $('.pax').attr('style' , 'background-color:red');
                      $('.book_comments').empty();
                      $('.book_comments').append('Van menos personas que el minimo, se le cobrara el minimo de la habitacion que son :'+data);
                  }else{
                      $('.book_comments').empty();
                      $('.pax').removeAttr('style');
                  }
              });

              $.get('/admin/reservas/getPricePark', {park: park, noches: noches}).success(function( data ) {
                  pricePark = data;
                  $.get('/admin/reservas/getPriceLujoAdmin', {lujo: lujo}).success(function( data ) {
                      priceLujo = data;

                      $.get('/admin/reservas/getPriceBook', {start: start, finish: finish, pax: pax, room: room, park: park}).success(function( data ) {
                          price = data;
                          
                          price = (parseFloat(price) + parseFloat(pricePark) + parseFloat(priceLujo));
                          $('.total').empty();
                          $('.total').val(price);
                              $.get('/admin/reservas/getCostPark', {park: park, noches: noches}).success(function( data ) {
                                  costPark = data;
                                  $.get('/admin/reservas/getCostLujoAdmin', {lujo: lujo}).success(function( data ) {
                                      costLujo = data;
                                      $.get('/admin/reservas/getCostBook', {start: start, finish: finish, pax: pax, room: room, park: park}).success(function( data ) {
                                          cost = data;
                                          agencia = $('.agencia').val();
                                          if (agencia == "") {
                                              agencia = 0;
                                          }
                                          cost = (parseFloat(cost) + parseFloat(costPark) + parseFloat(agencia) + parseFloat(costLujo));
                                          $('.cost').empty();
                                          $('.cost').val(cost);
                                          beneficio = price - cost;
                                          $('.beneficio').empty;
                                          $('.beneficio').val(beneficio);
                                          beneficio_ = (beneficio / price)*100
                                          $('.beneficio-text').empty;
                                          $('.beneficio-text').html(beneficio_.toFixed(0)+"%")

                                      });
                                  });
                              });
                      });
                  });
              });  
          });
          
          $('.total').change(function(event) {
              var price = $(this).val();
              var cost = $('.cost').val();
              var beneficio = (parseFloat(price) - parseFloat(cost));
              console.log(beneficio);
              $('.beneficio').empty;
              $('.beneficio').val(beneficio);
          });

          $('#fecha').change(function(event) {
              
              var year = $(this).val();
              window.location = '/admin/reservas/'+year;
          });


      });
  </script>

@endsection