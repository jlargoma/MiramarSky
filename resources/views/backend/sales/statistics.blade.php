@extends('layouts.admin-master')

@section('title') Liquidacion @endsection

@section('externalScripts') 
	
	<link href="assets/plugins/jquery-datatable/media/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
	<link href="assets/plugins/jquery-datatable/extensions/FixedColumns/css/dataTables.fixedColumns.min.css" rel="stylesheet" type="text/css" />
	<link href="assets/plugins/datatables-responsive/css/datatables.responsive.css" rel="stylesheet" type="text/css" media="screen" />

@endsection

@section('content')
<?php use \Carbon\Carbon; ?>

<style>
	.table>thead>tr>th {
		padding:0px!important;
	}
	th{
		/*font-size: 15px!important;*/
	}
	td{
		font-size: 13px!important;
		padding: 10px 5px!important;
	}
	.pagos{
		background-color: rgba(255,255,255,0.5)!important;
	}

	td[class$="bi"] {border-left: 1px solid black;}
	td[class$="bf"] {border-right: 1px solid black;}
	
	.coste{
		background-color: rgba(200,200,200,0.5)!important;
	}
	th.text-center.bg-complete.text-white{
		padding: 10px 5px;
		font-weight: 300;
		font-size: 15px!important;
		text-transform: capitalize!important;
	}
</style>
<div class="container-fluid padding-5 sm-padding-10">


    <div class="row">
        <div class="col-md-12">
			<div class="tab-content">

			    <div class="tab-pane active" id="tabPrices">
			        <table class="table table-hover  table-responsive" >
			        	<thead >
			        		<th class ="text-center bg-complete text-white" >Nombre</th>
			        		<th class ="text-center bg-complete text-white">Pax</th>
			        		<th class ="text-center bg-complete text-white">Apto</th>
			        		<th class ="text-center bg-complete text-white">Entrada</th>
			        		<th class ="text-center bg-complete text-white">Salida</th>
			        		<th class ="text-center bg-complete text-white"><i class="fa fa-moon-o"></i></th>
			        		<th class ="text-center bg-complete text-white">PVP</th>
			        		<th class ="text-center bg-complete text-white">Cob <br> Banco</th>
			        		<th class ="text-center bg-complete text-white">Cob <br> Jorge</th>
			        		<th class ="text-center bg-complete text-white">Cob <br> Jaime</th>
			        		<th class ="text-center bg-complete text-white">Pend</th>
			        		<th class ="text-center bg-complete text-white">Ingreso <br> Total</th>
			        		<th class ="text-center bg-complete text-white">%Ben</th>
			        		<th class ="text-center bg-complete text-white">Coste <br> Total</th>
			        		<th class ="text-center bg-complete text-white">Coste <br> Apto</th>
			        		<th class ="text-center bg-complete text-white">Park</th>
			        		<th class ="text-center bg-complete text-white">Lujo</th>
			        		<th class ="text-center bg-complete text-white">Limp</th>
			        		<th class ="text-center bg-complete text-white">Agencia</th>
			        		<th class ="text-center bg-complete text-white">Ben <br> Jorge</th>
			        		<th class ="text-center bg-complete text-white">% <br> Jorge</th>
			        		<th class ="text-center bg-complete text-white">Ben <br> Jaime</th>
			        		<th class ="text-center bg-complete text-white">% <br> Jaime</th>
			        	</thead>
			        	<tbody >
			        		<?php foreach ($books as $book): ?>
			        			<tr >
				        			<td class="text-center">
										<a class="update-book" data-id="<?php echo $book->id ?>"  title="Editar Reserva"  href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>"><?php  echo $book->customer['name'] ?></a>
									</td>
				        			<td class="text-center"><?php echo $book->pax ?></td>
				        			<td class="text-center"><?php echo $book->room->name ?></td>
				        			<td class="text-center">
				        				<?php 
    										$start = Carbon::createFromFormat('Y-m-d',$book->start);
    										echo $start->format('d-m-Y');
    									?>
				        			</td>
				        			<td class="text-center">
				        				<?php 
    										$finish = Carbon::createFromFormat('Y-m-d',$book->finish);
    										echo $finish->format('d-m-Y');
    									?>
				        			</td>
				        			<td class="text-center"><?php echo $book->nigths ?></td>
				        			<td class="text-center"><?php echo number_format($book->total_price,2,',','.') ?></td>

				        			<td class="text-center pagos bi"><?php echo $book->getPayment(2)." €"; ?></td>
				        			<td class="text-center pagos"><?php echo $book->getPayment(0)." €"; ?></td>
				        			<td class="text-center pagos"><?php echo $book->getPayment(1)." €"; ?></td>

				        			<td class="text-center coste bi" style="border-left: 1px solid black;"><?php echo $book->total_ben ?></td>
				        			<td class="text-center coste"><?php echo number_format($book->inc_percent,0)." %" ?></td>
				        			<td class="text-center coste"><?php echo number_format($book->cost_total,2,',','.')." €" ?></td>
				        			<td class="text-center coste"><?php echo number_format($book->cost_apto,2,',','.')." €" ?></td>
				        			<td class="text-center coste"><?php echo number_format($book->cost_park,2,',','.')." €" ?></td>
				        			<td class="text-center coste" ><?php echo number_format($book->cost_lujo,2,',','.')." €" ?></td>
				        			<td class="text-center coste">0</td>
				        			<td class="text-center coste bf">0</td>
				        			<td class="text-center"><?php echo number_format($book->ben_jorge,2,',','.') ?></td>
				        			<td class="text-center"> echo</td>
				        			<td class="text-center"><?php echo number_format($book->ben_jaime,2,',','.') ?></td>
				        			<td class="text-center"></td>
				        		</tr>
			        		<?php endforeach ?>
			        		
			        	</tbody>
			        </table>
			    </div>
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
		var colorPendienteCobro = function(){
			var pendientes  = $('.pendiente');


			for(ind in pendientes){
	  			
	  			var pendCobro = pendientes[ind];

	  			if ($(pendCobro).text() == '0 €') {
	  				$(pendCobro).css('color', 'blue');
	  			};
			}
		}

	$(document).ready(function() {
		
			
		colorPendienteCobro();
	});
</script>
@endsection