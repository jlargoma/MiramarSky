@extends('layouts.admin-master')

@section('title') Liquidacion @endsection

@section('externalScripts') 
	
	<link href="/assets/plugins/jquery-datatable/media/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
	<link href="/assets/plugins/jquery-datatable/extensions/FixedColumns/css/dataTables.fixedColumns.min.css" rel="stylesheet" type="text/css" />
	<link href="/assets/plugins/datatables-responsive/css/datatables.responsive.css" rel="stylesheet" type="text/css" media="screen" />
<style>
	.table>thead>tr>th {
		padding:0px!important;
	}
	th{
		/*font-size: 15px!important;*/
	}
	td{
		font-size: 11px!important;
		padding: 10px 5px!important;
	}
	.pagos{
		background-color: rgba(255,255,255,0.5)!important;
	}
	.beneficio{
		background-color: rgba(153,188,231,0.4)!important;
	}
	td[class$="bi"] {border-left: 1px solid black;}
	td[class$="bf"] {border-right: 1px solid black;}
	
	.coste{
		background-color: rgba(200,200,200,0.5)!important;
	}
	th.text-center.bg-complete.text-white{
		padding: 10px 5px;
		font-weight: 300;
		font-size: 12px!important;
		text-transform: capitalize!important;
	}
	
	.red{
		color: red;
	}
	.blue{
		color: blue;
	}
	
</style>
@endsection

@section('content')


<div class="container-fluid padding-5 sm-padding-10">

    <div class="row push-20">
    	<div class="col-md-3 col-md-offset-4 text-center">
    		<h2>Liquidación por reservas <?php echo $temporada->copy()->format('Y')."-".$temporada->copy()->AddYear()->format('Y') ?> </h2>
    	</div>
    	<div class="col-md-1" style="padding: 10px 0;">
			<select id="date" class="form-control minimal">
				<?php $fecha = $temporada->copy()->SubYear(2); ?>
				<?php if ($fecha->copy()->format('Y') < 2015): ?>
					<?php $fecha = new Carbon('first day of September 2015'); ?>
				<?php else: ?>
					
				<?php endif ?>
			
                <?php for ($i=1; $i <= 4; $i++): ?>                           
                    <option value="<?php echo $fecha->copy()->format('Y'); ?>" {{ $temporada->copy()->format('Y') == $fecha->copy()->format('Y') ? 'selected' : '' }}>
                        <?php echo $fecha->copy()->format('Y')."-".$fecha->copy()->addYear()->format('Y'); ?> 
                    </option>
                    <?php $fecha->addYear(); ?>
                <?php endfor; ?>
            </select>
    	</div>
    </div>

	<div class="row">
		<div class="col-md-12">
			<div class="col-md-6 push-20">
				<h2 class="text-left">
					Buscar por:
				</h2>
				<div class="col-md-6">
					<label>Nombre del cliente:</label>
					<input id="nameCustomer" type="text" name="searchName" class="searchabled form-control" placeholder="nombre del cliente" />
				</div>
				<div class="col-md-3">
					<label>APTO:</label>
					<select class="form-control searchSelect minimal" name="searchByRoom">
						<option value="all">Todos</option>
						<?php foreach (\App\Rooms::all() as $key => $room): ?>
							<option value="<?php echo $room->id ?>">
								<?php echo $room->name ?>
							</option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
		</div>
		<div class="liquidationSummary">
    		@include('backend.sales._tableSummary', ['totales' => $totales, 'books' => $books])
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

	  			if ($(pendCobro).text() == '0,00 €') {
	  				$(pendCobro).addClass("blue");
	  			}else{
	  				$(pendCobro).addClass("red");
	  			};
			}
		}

	$(document).ready(function() {
		
			
		colorPendienteCobro();
		$('.dataTables_paginate').click(function(event) {
			colorPendienteCobro();
		});
		$('#date').change(function(event) {
			var year = $(this).val();
			window.location = '/admin/liquidacion/'+year;
		});


		$('.searchabled').keyup(function(event) {
			var searchString = $(this).val();
			var searchRoom = $('.searchSelect').val();
			var year = '<?php echo $temporada->copy()->format('Y')?>';

			$.get('/admin/liquidation/searchByName', { searchString: searchString,  year: year, searchRoom: searchRoom, }, function(data) {

				$('.liquidationSummary').empty();
				$('.liquidationSummary').append(data);

			});
		});

		$('.searchSelect').change(function(event) {
			var searchRoom = $(this).val();
			var searchString = $('.searchabled').val();
			var year = '<?php echo $temporada->copy()->format('Y')?>';

			$.get('/admin/liquidation/searchByRoom', { searchRoom: searchRoom, searchString: searchString,  year: year }, function(data) {

				$('.liquidationSummary').empty();
				$('.liquidationSummary').append(data);

			});
		});


	});
</script>
@endsection