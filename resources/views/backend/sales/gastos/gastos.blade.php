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
			@include('backend.years._selector')
			</div>
		</div>
	</div>
</div>
<div class="container-fluid">
	<div class="row bg-white push-30">
		<div class="col-md-10 col-xs-12 push-20">
			@include('backend.sales._button-contabiliad')
		</div>
		<div class="col-md-2 col-xs-12 push-20 text-center">
                    <button class="btn btn-md btn-complete" data-toggle="modal" data-target="#expencesByRoom">
                            Hoja de gastos
                    </button>
		</div>
	</div>
	

	<div class="row bg-white push-30">


		@include('backend.sales.gastos._formGastos')


	</div>

	<div class="row bg-white push-30 table-responsive" id="contentTableExpenses" style="border: 0px!important">
	@include('backend.sales.gastos._tableExpenses', ['gastos' => $gastos,'totalMonthLimpieza' => $totalMonthLimpieza,'dateMonthLimpieza' => $dateMonthLimpieza])
	</div>

</div>

<div class="modal fade slide-up disable-scroll in" id="expencesByRoom" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-big" >
		<div class="modal-content-wrapper">
			<div class="modal-content">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-close fa-2x"></i></button>
				<div class="container-xs-height full-height">
					<div class="row-xs-height">
						<div class="modal-body contentExpencesByRoom">
							@include('backend.sales.gastos._expensesByRoom', ['gastos' => $gastos, 'room' => 'all',
							'year' => $year])
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
				$.get('/admin/gastos/delete/'+id, function(data) {
					
					if (data == "ok") {
						location.reload();
						//$('#contentTableExpenses').empty().load('/admin/gastos/getTableGastos')
					}

				});
			});
		});
	</script>

@endsection