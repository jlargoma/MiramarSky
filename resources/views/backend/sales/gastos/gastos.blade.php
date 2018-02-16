<?php   use \Carbon\Carbon;  
setlocale(LC_TIME, "ES"); 
setlocale(LC_TIME, "es_ES"); 
?>
@extends('layouts.admin-master')

@section('title') Gastos  @endsection

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
				Gastos
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
		<div class="col-md-6 col-xs-12 push-20">

			@include('backend.sales._button-contabiliad')

		</div>
		<div class="col-md-6 col-xs-12 push-20">
			<div class="col-md-12 col-xs-12 push-20">
				<div class="col-md-2 col-xs-2">
					<button class="btn btn-md btn-complete" data-toggle="modal" data-target="#expencesByRoom">
						Hoja de gastos
					</button>
				</div>
			</div>
		</div>
	</div>
	<div class="row bg-white push-30">
		<div class="col-md-4">
			<div class="col-md-4 bordered">
				<div class="card-title text-black hint-text">
					PVP
				</div>
				<div class="p-l-20">
					<h3 class="text-black font-w400 text-center">1251</h3>
				</div>
			</div>
			<div class="col-md-4 bordered">
				<div class="card-title text-black hint-text">
					Banc Jorge
				</div>
				<div class="p-l-20">
					<input class="form-control text-black font-w400 text-center seasonDays" value="120" style="border: none; font-size: 32px;margin: 10px 0;color:red!important">
				</div>
			</div>
			<div class="col-md-4 bordered">
				<div class="card-title text-black hint-text">
					Banc Jaime
				</div>
				<div class="p-l-20">
					<h3 class="text-black font-w400 text-center">333</h3>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="col-md-4 bordered">
				<div class="card-title text-black hint-text">
					Cash Jorg
				</div>
				<div class="p-l-20">
					<h3 class="text-black font-w400 text-center">1251</h3>
				</div>
			</div>
			<div class="col-md-4 bordered">
				<div class="card-title text-black hint-text">
					Cash Jaime
				</div>
				<div class="p-l-20">
					<input class="form-control text-black font-w400 text-center seasonDays" value="120" style="border: none; font-size: 32px;margin: 10px 0;color:red!important">
				</div>
			</div>
			<div class="col-md-4 bordered">
				<div class="card-title text-black hint-text">
					Pend
				</div>
				<div class="p-l-20">
					<h3 class="text-black font-w400 text-center">333</h3>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="col-md-4 bordered">
				<div class="card-title text-black hint-text">
					Ingreso Neto
				</div>
				<div class="p-l-20">
					<h3 class="text-black font-w400 text-center">1251</h3>
				</div>
			</div>
			<div class="col-md-4 bordered">
				<div class="card-title text-black hint-text">
					% benef
				</div>
				<div class="p-l-20">
					<input class="form-control text-black font-w400 text-center seasonDays" value="120" style="border: none; font-size: 32px;margin: 10px 0;color:red!important">
				</div>
			</div>
			<div class="col-md-4 bordered">
				<div class="card-title text-black hint-text">
					Coste Total
				</div>
				<div class="p-l-20">
					<h3 class="text-black font-w400 text-center">333</h3>
				</div>
			</div>
		</div>
	</div>

	<div class="row bg-white push-30">


		@include('backend.sales.gastos._formGastos')


	</div>

	<div class="row bg-white push-30" id="contentTableExpenses">
		@include('backend.sales.gastos._tableExpenses', ['gastos' => $gastos])
	</div>

</div>

<div class="modal fade slide-up disable-scroll in" id="expencesByRoom" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-big" style="width: 85%;">
		<div class="modal-content-wrapper">
			<div class="modal-content">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-close fa-2x"></i></button>
				<div class="container-xs-height full-height">
					<div class="row-xs-height">
						<div class="modal-body contentExpencesByRoom">
							@include('backend.sales.gastos._expensesByRoom', ['gastos' => $gastos, 'room' => 'all'])
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
	
	<script type="text/javascript">
		$(document).ready(function() {
			$('.deleteExpense').click(function(event) {
				var id = $(this).attr('data-id');
				var year = $('#fecha').val();
				$.get('/admin/gastos/delete/'+id, function(data) {
					
					if (data == "ok") {
						$('#contentTableExpenses').empty().load('/admin/gastos/getTableGastos/'+year)
					}

				});
			});
		});
	</script>

@endsection