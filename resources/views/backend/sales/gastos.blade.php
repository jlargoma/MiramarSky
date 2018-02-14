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
		<button id="addCashBox" class="btn btn-success pull-left" data-toggle="modal" data-target="#modal-cashbox" style="border-radius: 100%;padding: 6px 10px 2px;"> 
			<i class="fa fa-plus fa-3x"></i>
		</button>
	</div>

	<div class="row bg-white push-30">
		<a href="https://admin.evolutio.fit/admin/cashbox"></a><table class="table table-bordered table-striped table-header-bg no-footer">
			<thead>
				<tr>
					<th class="text-center">#</th>
					<th class="text-center">Fecha</th>
					<th class="text-center">Concepto</th>
					<th class="text-center type" style="width: 250px;">Tipo</th>
					<th class="text-center">Debe</th>
					<th class="text-center">Haber</th>
					<th class="text-center">Saldo</th>
					<th class="text-center">Comentario</th>
				</tr>
			</thead>	
			<tbody>
				<tr>
					<td class="text-center">
					1							</td>
					<td class="text-center">
						<b>02-01-2018</b>
						<input type="hidden" id="date-484" value="2018-01-02">
					</td>
					<td class="text-center">
						<input type="text" class="form-control selectAddGasto" id="concept-484" value="SALDO INICIAL" data-idcashbox="484">
					</td>

					<td class="text-center">
						<select class="js-select2 form-control selectAddGasto" id="typePayment-484" style="width: 100%;" data-placeholder="Seleccione un tipo" required="" data-idcashbox="484">
							<option></option>
							<optgroup label="REPARTO DIVIDENDO">
								<option value="DIVIDENDO JORGE">DIVIDENDO JORGE</option>
								<option value="DIVIDENDO VICTOR">DIVIDENDO VICTOR</option>
								<option value="DIVIDENDO BELTRAN">DIVIDENDO BELTRAN</option>
							</optgroup>
							<optgroup label="APORTACION SOCIOS">
								<option value="APORT. JORGE">APORT. JORGE</option>
								<option value="APORT. VICTOR">APORT. VICTOR</option>
								<option value="APORT. BELTRAN">APORT. BELTRAN</option>
							</optgroup>
							<optgroup label="INGRESO">
								<option value="INGRESO" selected="">INGRESO CLIENTES</option>
							</optgroup>
							<optgroup label="INVERSION">
								<option value="INVERSION">INVERSION</option>
							</optgroup>
							<optgroup label="TRASPASO">
								<option value="TRASPASO">TRASPASO</option>
							</optgroup>
							<optgroup label="GASTO">
								<option value="MOBILIARIO">MOBILIARIO</option>
								<option value="SERVICIOS PROFESIONALES INDEPENDIENTES">SERVICIOS PROFESIONALES INDEPENDIENTES</option>
								<option value="VARIOS">VARIOS</option>
								<option value="EQUIPAMIENTO DEPORTIVO">EQUIPAMIENTO DEPORTIVO</option>
								<option value="IMPUESTOS">IMPUESTOS</option>
								<option value="SUMINISTROS">SUMINISTROS</option>
								<option value="GASTOS BANCARIOS">GASTOS BANCARIOS</option>
								<option value="PUBLICIDAD">PUBLICIDAD</option>
								<option value="REPARACION Y CONSERVACION">REPARACION Y CONSERVACION</option>
								<option value="ALQUILER NAVE">ALQUILER NAVE</option>
								<option value="SEGUROS SOCIALES">SEGUROS SOCIALES</option>
								<option value="NOMINAS">NOMINAS</option>
								<option value="TARJETA VISA">TARJETA VISA</option>
								<option value="MATERIAL OFICINA">MATERIAL OFICINA</option>
								<option value="MENSAJERIA">MENSAJERIA</option>
								<option value="PRODUCTOS VENDING">PRODUCTOS VENDING</option>
								<option value="LIMPIEZA">LIMPIEZA</option>
								<option value="INTERNET">INTERNET</option>
								<option value="RENTING EQUIPAMIENTO DEPORTIVO">RENTING EQUIPAMIENTO DEPORTIVO</option>
								<option value="COMISONES COMERCIALES">COMISONES COMERCIALES</option>
							</optgroup>
						</select>
					</td>

					<td class="text-center">

					</td>
					<td class="text-center">
						<b class="text-success">+1.793,89 €</b>
						<input type="hidden" class="form-control" id="import-484" value="1793.89">

					</td>
					<td class="text-center">
						<b>1.793,89 €</b>
					</td>

					<td class="text-center">
						<input type="text" class="form-control selectAddGasto" data-idcashbox="484" id="comment-484" value="SALDO INICIAL">
					</td>
				</tr>
			</tbody>			
		</table>
	</div>

</div>


<div class="modal fade" id="modal-cashbox" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-dialog-popout">
		<div class="modal-content">
			<div class="block block-themed block-transparent remove-margin-b">
				<div class="block-header bg-primary-dark">
					<ul class="block-options">
						<li>
							<button data-dismiss="modal" type="button"><i class="fa fa-times 2x"></i></button>
						</li>
					</ul>
				</div>
				<div class="row block-content" id="contentListCashBox">

				</div>
			</div>
		</div>
	</div>
</div>

@endsection	


@section('scripts')


@endsection