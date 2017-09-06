@extends('layouts.admin-master')

@section('title') Liquidacion de Apartamentos @endsection

@section('externalScripts') 
	
	<link href="assets/plugins/jquery-datatable/media/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
	<link href="assets/plugins/jquery-datatable/extensions/FixedColumns/css/dataTables.fixedColumns.min.css" rel="stylesheet" type="text/css" />
	<link href="assets/plugins/datatables-responsive/css/datatables.responsive.css" rel="stylesheet" type="text/css" media="screen" />

@endsection

@section('headerButtoms')
	@include('layouts/headerbuttoms')
@endsection

@section('content')
<?php use \Carbon\Carbon; ?>

<div class="container-fluid padding-5 sm-padding-10">

	<div class="col-md-12 text-center"> 
		<h2>
			Liquidacion de Apartamentos <?php echo $temporada->copy()->format('Y')."-".$temporada->copy()->AddYear()->format('Y') ?>
			<select id="date" >
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
		</h2>
	</div>
    <div class="row">
        <div class="col-md-6">
			<div class="pull-left">
			        <div class="col-xs-12 ">
			            <input type="text" id="search-tableLiquidacion" class="form-control pull-right" placeholder="Buscar">
			        </div>
			    </div>
			
			    <div class="clearfix"></div>
		    <div class="tab-pane active" id="tabPrices">
		        <table class="table table-hover demo-table-search table-responsive" id="tableWithSearchLiquidacion" >
		        	<thead>
		        		<th class ="text-center bg-complete text-white">Apto</th>
		        		<th class ="text-center bg-complete text-white">Noches</th>
		        		<th class ="text-center bg-complete text-white">PVP</th>
		        		<th class ="text-center bg-complete text-white">Pendiente</th>
		        		<th class ="text-center bg-complete text-white">Ingresos</th>
		        		<th class ="text-center bg-complete text-white">%Ben</th>
		        		<th class ="text-center bg-complete text-white">Costes</th>
		        	</thead>
		        	<tbody>
		        		<?php foreach ($rooms as $room): ?>
		        			<?php if (isset($apartamentos["noches"][$room->id])): ?>
		        				<tr>
		        					<td class="text-center"><?php echo $room->name ?></td>
		        					<td class="text-center"><?php echo $apartamentos["noches"][$room->id]?></td>
		        					<td class="text-center"><?php echo number_format($apartamentos["pvp"][$room->id],2,',','.')?>€</td>
		        					<td class="text-center">
		        						<?php if (isset($pendientes[$room->id])): ?>
		        							<?php echo number_format($pendientes[$room->id],2,',','.') ?>€
		        						<?php else: ?>
		        							-----
		        						<?php endif ?>
		        					</td>
		        					<td class="text-center"><?php echo number_format($apartamentos["ingresos"][$room->id],2,',','.')?>€</td>
		        					<td class="text-center"><?php echo number_format($apartamentos["ingresos"][$room->id]/$apartamentos["pvp"][$room->id]*100,2,',','.')?>%</td>
		        					<td class="text-center"><?php echo number_format($apartamentos["costes"][$room->id],2,',','.')?>€</td>
		        				</tr>
		        			<?php endif ?>
		        			
		        		<?php endforeach ?>
		        	</tbody>
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
		
		$('#date').change(function(event) {
			var year = $(this).val();
			window.location = '/admin/liquidacion-apartamentos/'+year;
		});

		colorPendienteCobro();
	});
</script>
@endsection