<?php setlocale(LC_TIME, "ES"); ?>
<?php setlocale(LC_TIME, "es_ES"); ?>
<?php use \Carbon\Carbon; ?>

@extends('layouts.admin-master')

@section('title') Cuenta de perdidas y ganancias @endsection


@section('content')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.js"></script>
<style type="text/css">
		#main-container{
			padding-top: 10px!important
		}
</style>
<div class="bg-white push-10">
	<section class="content content-full">
		<div class="row">
			<div class="col-xs-12 col-md-6">
				<div class="col-xs-12 col-md-6">
					<canvas id="barChart" style="width: 100%; height: 250px;"></canvas>
				</div>
				<div class="col-xs-12 col-md-6">
					<canvas id="barChartClient" style="width: 100%; height: 250px;"></canvas>
				</div>
			</div>
			<div class="col-xs-12 col-md-6">
				<div class="col-xs-12 col-md-4 not-padding">
					<div class="col-xs-12 not-padding">
						<div class="block block-link-hover3 text-center push-0">
							
							<div class="block-content block-content-full bg-success">
								<div class="h1 font-w700 text-white"> XXXXXX<span class="h2 text-white-op">€</span></div>
								<div class="h5 text-white-op text-uppercase push-5-t">Ingresos anuales</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-md-4 not-padding">
					<div class="col-xs-12 not-padding">
						<div class="block block-link-hover3 text-center push-0">
							<div class="block-content block-content-full bg-danger">
								<div class="h1 font-w700 text-white"> XXXXX<span class="h2 text-white-op">€</span></div>
								<div class="h5 text-white-op text-uppercase push-5-t">Gastos anuales</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-md-4 not-padding">
					<div class="col-xs-12 not-padding">
						<div class="block block-link-hover3 text-center push-0">
							<div class="block-content block-content-full bg-primary">
								<div class="h1 font-w700 text-white"> 
									XXXXXXX 
								</div>
								<div class="h5 text-white-op text-uppercase push-5-t">Resultado</div>
							</div>
						</div>
					</div>
				</div>
				<div class="clearfix"></div>

				<?php for ($i=1; $i <= 3 ; $i++): ?>
					<div class="col-xs-12 col-md-4 not-padding push-0">
						<div class="block block-link-hover3 text-centerpush-0">
						
							<div class="row block-content  bg-gray-light" style="padding: 10px 5px;">
							
									<div class="col-md-6 not-padding">
										<div class="col-md-12">
											Clientes <br>
											<span class="font-w700 font-s18">XXXXXXX</span>/<span class="font-w700 font-s18">XXXXXX</span>
											
										</div>
									</div>
									<div class="col-md-6 not-padding">
										<div class="col-md-12 text-center  h1 text-black-op text-uppercase">
											<span class="font-w700">XXXXXXXX</span>
												<i class="fa fa-arrow-up text-success"></i>
										</div>
									</div>
									<div class="col-md-12 not-padding">
										<div class="col-md-5">Cuota/mes</div> 
										<div class="col-md-7 text-center">
											<span class="font-w700 font-s18 ">
												XXXXXXx
													<i class="fa fa-arrow-down text-danger"></i>
												
											</span>
										</div>
									</div>

							</div>
						</div>
					</div>
				<?php endfor; ?>
			</div>
		</div>
	</section>
</div>
<div class="bg-white">
	<section class="content content-full">
		<div class="row">
			<div class="col-xs-9 push-20">
				<h2 class="font-w300 text-center"><b>CUENTA DE PERDIDAS Y GANACIAS DE XXXXXXX</b></h2>
			</div>
			<div class="col-xs-3 col-md-3 push-30">
				<div class="col-md-4 pull-right">
					XXXXXXXXXX
				</div>
			</div>
			<div class="col-xs-12">
				<!-- col-md-6  -->
				
				<table class="js-table-sections table table-hover table-bordered table-striped table-header-bg">
					<thead>
						<tr>
							<th class="text-center" style="width: 30px;"></th>
							<th class="text-center"></th>
							<?php for($i = 1 ; $i <= 12; $i++): ?>
								<th class="text-center">XXXXXXXX</th>
							<?php endfor; ?>
							<th class="text-center">Total</th>
						</tr>
					</thead >
					<tbody class="js-table-sections-header">
						<tr>
						 	<td class="text-center" style="color: #fff; background-color: #46c37b;">
                                <i class="fa fa-angle-right"></i>
                            </td>
							<td class="text-center" style="color: #fff; background-color:#46c37b; border-bottom-color:#46c37b;">
								<b>INGRESOS</b>
							</td>
							<?php for($i = 1 ; $i <= 12; $i++): ?>
								<td class="text-center" style="color: #fff; background-color:#46c37b; border-bottom-color:#46c37b;">
								
									<b>
										XXXXXXXX
									</b>
								</td>
							<?php endfor; ?>
							<td class="text-center" style="color: #fff; background-color: #46c37b; border-bottom-color:#46c37b;">
								<b>
									XXXXXXXX
								</b>
							</td>
						</tr>	
					</tbody>
					<tbody>			
						
					</tbody>		
						<!-- FIN INGRESOS -->
						<!-- GASTOS -->
					<tbody class="js-table-sections-header">
						<tr>
							<td class="text-center" style="color: #fff; background-color: #a94442;">
							    <i class="fa fa-angle-right"></i>
							</td>
							<td class="text-center" style="color: #fff; background-color: #a94442; border-bottom-color: #a94442;">
								<b>GASTOS</b>
							</td>
							<?php for($i = 1 ; $i <= 12; $i++): ?>
								<td class="text-center" style="color: #fff; background-color: #a94442; border-bottom-color: #a94442;">
									
									<b>
										xxxxxxx
									</b>
									
								</td>
							<?php endfor; ?>
							<td class="text-center" style="color: #fff; background-color: #a94442; border-bottom-color: #a94442;">
								<b>
									xxxxxxx
								</b>
							</td>
						</tr>
					</tbody>
					<tbody>
					</tbody>
					<tbody class="js-table-sections-header">
						<!-- FIN GASTOS -->
						<tr >
							<td class="text-center"  style="color: #fff; background-color: #5c90d2;">
							    <i class="fa fa-angle-right"></i>
							</td>

							<td class="text-center" style="color: #fff; background-color: #5c90d2; border-bottom-color: #5c90d2; font-size: 18px;">
								<b>BENEFICIO CONTABLE</b>
							</td>
							<?php for($i = 1 ; $i <= 12; $i++): ?>
								<td class="text-center" style="color: #fff; background-color: #5c90d2; border-bottom-color: #5c90d2; font-size: 18px;">
									<b>
										xxxxxxx
									</b>
								</td>
							<?php endfor; ?>
							<td class="text-center" style="color: #fff; background-color: #5c90d2; border-bottom-color: #5c90d2; font-size: 18px;">
								<b>xxxxxx €</b>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</section>
</div>
@endsection
@section('scripts') 
	<script>
        jQuery(function () {
            // Init page helpers (Table Tools helper)
            App.initHelpers('table-tools');
        });
    </script>


@endsection