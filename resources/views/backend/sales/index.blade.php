<?php use \Carbon\Carbon; ?>
@extends('layouts.admin-master')

@section('title') Liquidacion @endsection

@section('externalScripts') 
	
	<link href="/assets/plugins/jquery-datatable/media/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
	<link href="/assets/plugins/jquery-datatable/extensions/FixedColumns/css/dataTables.fixedColumns.min.css" rel="stylesheet" type="text/css" />
	<link href="/assets/plugins/datatables-responsive/css/datatables.responsive.css" rel="stylesheet" type="text/css" media="screen" />
<style>
	.table>thead>tr>th {
		padding:3px 5px!important;
	}
	th{
		/*font-size: 15px!important;*/
	}
	td{
		font-size: 11px!important;
		padding: 5px 5px!important;
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
    	<div class="col-md-4 push-20">
    		<div class="col-md-8">
    			<label>Nombre del cliente:</label>
    			<input id="nameCustomer" type="text" name="searchName" class="searchabled form-control" placeholder="nombre del cliente" value="{{ old('searchName') }}"/>
    		</div>
    		<div class="col-md-4">
    			<label>APTO:</label>
    			<select class="form-control searchSelect minimal" name="searchByRoom" >
    				<option value="all">Todos</option>
    				<?php foreach (\App\Rooms::where('state', 1)->orderBy('order')->get() as $key => $room): ?>
    					<option value="<?php echo $room->id ?>">
    						<?php echo substr($room->nameRoom." - ".$room->name, 0, 8)  ?>
    					</option>
    				<?php endforeach ?>
    			</select>
    		</div>
    	</div>
    	<div class="col-md-3 text-center">
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
		
		
    	<div class="col-md-1 pull-right">
            <button class="btn btn-md btn-primary exportExcel">
                Exportar Excel
            </button>
		</div>
		<div class="col-md-1 pull-right">
				<button class="btn btn-md btn-danger orderPercentBenef">
					Ord benef critico
				</button>
			</div>
    </div>

	<div class="row">
		<div class="liquidationSummary">
    		@include('backend.sales._tableSummary', ['totales' => $totales, 'books' => $books, 'temporada' => $temporada])
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

		$('.seasonDays').change(function(event) {
            var numDays = $(this).val();
            $.get('/admin/update/seasonsDays/'+numDays,{numDays: numDays}, function(data) {
                alert(data);
                location.reload();
            });
        });

		$('.percentBenef').change(function(event) {
            var percentBenef = $(this).val();
            $.get('/admin/update/percentBenef/'+percentBenef,{percentBenef: percentBenef}, function(data) {
                alert(data);
                location.reload();
            });
		});
		
		$('.orderPercentBenef').click(function(){
			var searchRoom = $('.searchSelect').val();
			var searchString = $('.searchabled').val();
			var year = '<?php echo $temporada->copy()->format('Y')?>';
			$.get('/admin/liquidation/orderByBenefCritico', { searchRoom: searchRoom, searchString: searchString,  year: year }, function(data) {

				$('.liquidationSummary').empty();
				$('.liquidationSummary').append(data);

			});
		});


        $('.exportExcel').click(function(event) {
        	var searchString = $('.searchabled').val();
			var searchRoom = $('.searchSelect').val();
			var year = '<?php echo $temporada->copy()->format('Y')?>';

			window.open('/admin/liquidacion/export/excel?searchString='+searchString+'&year='+year+'&searchRoom='+searchRoom, '_blank' );
			

        });
        

	});
</script>
@endsection