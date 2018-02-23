<?php   use \Carbon\Carbon;  
setlocale(LC_TIME, "ES"); 
setlocale(LC_TIME, "es_ES"); 
?>
@extends('layouts.admin-master')

@section('title') Ingresos  @endsection

@section('externalScripts') 
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.js"></script>

<style type="text/css">
.bordered{
padding: 15px;
border:1px solid #e8e8e8;
background: white;
}
.form-control{
		border: 1px solid rgba(0, 0, 0, 0.07)!important;
	}
</style>

@endsection

@section('content')
<div class="container padding-5 sm-padding-10">
<div class="row bg-white">
	<div class="col-md-12 col-xs-12">

		<div class="col-md-3 col-md-offset-3 col-xs-12">
			<h2 class="text-center">
				Ingresos
			</h2>
		</div>
		<div class="col-md-2 col-xs-12 sm-padding-10" style="padding: 10px">
			<select id="fecha" class="form-control minimal">
				<?php $fecha = $inicio->copy()->SubYears(2); ?>
				<?php if ($fecha->copy()->format('Y') < 2015): ?>
					<?php $fecha = new Carbon('first day of September 2015'); ?>
				<?php endif ?>

				<?php for ($i=1; $i <= 3; $i++): ?>                           
					<option value="<?php echo $fecha->copy()->format('Y'); ?>" 
						<?php if (  $fecha->copy()->format('Y') == date('Y') || 
							$fecha->copy()->addYear()->format('Y') == date('Y') 
							){ echo "selected"; }?> >
							<?php echo $fecha->copy()->format('Y')."-".$fecha->copy()->addYear()->format('Y'); ?> 
						</option>
						<?php $fecha->addYear(); ?>
					<?php endfor; ?>
				</select>    
			</div>
		</div>
	</div>
</div>
<div class="container-fluid">
	<div class="row bg-white push-30">
		<div class="col-lg-6 col-md-10 col-xs-12 push-20">

			@include('backend.sales._button-contabiliad')

		</div>
		
	</div>
	
	<div class="row bg-white push-30">

		<div class="col-xs-12 col-md-12 push-30" >
			@include('backend.sales.ingresos._formIngreso')
		</div>
		<div class="col-xs-12 col-md-12">
			
			<div class="row table-responsive" style="border: 0px!important">
            <table class="table table-striped " style="margin-top: 0;">
    			<thead>
    				<tr>
    					<th class="text-center bg-complete text-white">AREA DE NEGOCIO</th>
                        <th class="text-center bg-complete text-white">total</th>
                        <th class="text-center bg-complete text-white"> % </th>
    					<?php $months = $inicio->copy(); ?>
    					<?php for ($i=1; $i <= 12 ; $i++): ?>
    						<th class="text-center bg-complete text-white">&nbsp;<?php echo $months->formatLocalized('%b') ?>&nbsp;</th>
    						<?php $months->addMonth() ?>
    					<?php endfor; ?>
    				</tr>
    			</thead>
    			<tbody>
    				<?php $totalIngresosYear = 0; ?>
					<?php foreach ($incomes as $key => $income): ?>
						<?php $totalIngresosYear += array_sum($income); ?>
					<?php endforeach ?>
						<?php $totalIngresosYear += $arrayTotales['totales']; ?>

					<tr>
						<td class="text-center" style="padding: 12px 20px!important">
							<b>VENTAS TEMPORADA</b>
						</td>
                       
                        <td class="text-center">
                            <b><?php echo  number_format($arrayTotales['totales'], 0,',','.'); ?>€</b>
                        </td>
                        <td class="text-center">
                        	<?php $percent = ($arrayTotales['totales'] / $totalIngresosYear) *100; ?>
                            <b><?php echo  number_format( $percent, 2, '.', ',') ?>%</b>
                        </td>
						
						<?php $monthsRooms = $inicio->copy(); ?>
    					<?php for ($i=1; $i <= 12 ; $i++): ?>
    						<td class="text-center" style="padding: 12px 20px!important">
    							<b><?php echo  number_format($arrayTotales['meses'][$monthsRooms->copy()->format('n')], 0,',','.'); ?>€</b>
    						</td>
    						<?php $monthsRooms->addMonth() ?>
    					<?php endfor; ?>
						
					</tr>
					<?php foreach ($incomes as $key => $income): ?>
						<tr>
							<td class="text-center" style="padding: 12px 20px!important">
								<b><?php echo substr($key, 0, 15) ?>...</b>
							</td>
	                       
	                        <td class="text-center">
	                        	<?php if (array_sum($income) > 0): ?>
	                            	<b><?php echo number_format( array_sum($income) , 0,',','.') ?> €</b>
	                        	<?php else: ?>
    								<b>----</b>
    							<?php endif ?>
	                        </td>
	                        <td class="text-center">
	                           <?php $percent = (array_sum($income) / $totalIngresosYear) *100; ?>
                            	<b><?php echo  number_format( $percent, 2, '.', ',') ?> %</b>
	                        </td>
							
							<?php $monthsRooms = $inicio->copy(); ?>
	    					<?php for ($i=1; $i <= 12 ; $i++): ?>
	    						<td class="text-center" style="padding: 12px 20px!important">
	    							<?php if ($income[$monthsRooms->copy()->format('n')] > 0): ?>
	    								<b><?php echo number_format( array_sum($income) , 0,',','.') ?> €</b>
	    							<?php else: ?>
	    								<b>----</b>
	    							<?php endif ?>
	    							
	    						</td>
	    						<?php $monthsRooms->addMonth() ?>
	    					<?php endfor; ?>
							
						</tr>
					<?php endforeach ?>
					
    			</tbody>
            </table>

			</div>
		</div>


	</div>

	<div class="row bg-white push-30" id="contentTableExpenses">
		
	</div>

</div>

@endsection	


@section('scripts')
	
	<script type="text/javascript">
		$(document).ready(function() {
			$('#fecha').change(function(event) {
	    
			    var year = $(this).val();
			    window.location = '/admin/ingresos/'+year;

			});
		});	
	</script>
	

@endsection