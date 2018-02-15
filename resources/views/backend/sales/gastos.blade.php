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
		<table class="table table-bordered table-striped table-header-bg no-footer">
			<thead>
				<tr>
					<th class="text-center">#</th>
					<th class="text-center">Fecha</th>
					<th class="text-center">Concepto</th>
					<th class="text-center type" style="width: 250px;">Tipo</th>
					<th class="text-center type" style="width: 250px;">Método de pago</th>
					<th class="text-center">Importe</th>
					<th class="text-center">Pisos</th>
					<th class="text-center">Comentario</th>
				</tr>
			</thead>	
			<tbody>
				<?php $array = [0 =>"Metalico Jorge", 1 =>"Metalico Jaime",2 =>"Banco Jorge",3=>"Banco Jaime"] ?>
				<?php foreach ($gastos as $key => $gasto): ?>
					
				
					<tr>
						<td class="text-center"></td>
						<td class="text-center">
							<b><?php echo $gasto->date ?></b>
							<input type="hidden" id="date-484" value="<?php echo $gasto->date ?>">
						</td>
						<td class="text-center">
							<input type="text" class="form-control selectAddGasto" id="concept-<?php echo $gasto->id ?>" value="<?php echo $gasto->concept ?>" data-idGasto="<?php echo $gasto->id ?>">
						</td>

						<td class="text-center">
							<?php echo $gasto->type ?>
						</td>

						<td class="text-center">
							<?php echo $array[$gasto->typePayment]; ?>
						</td>
						<td class="text-center">
							<b><?php echo $gasto->import ?> €</b>
						</td>
						<td class="text-center">
							<?php if ($gasto->PayFor != "" ): ?>
								<?php $roomsIds = explode(',', $gasto->PayFor) ?>
								<?php for ($i=0; $i < count($roomsIds); $i++): ?>
									<?php if ($roomsIds[$i] != ""): ?>
										<?php $room = \App\Rooms::find($roomsIds[$i]) ?>
										<?php echo $room->nameRoom; ?>,  
									<?php endif ?>
								<?php endfor; ?>
							<?php else: ?>
								Todos
							<?php endif ?>
						</td>

						<td class="text-center">
							<input type="text" class="form-control selectAddGasto" data-idGasto="484" id="comment-484" value="<?php echo $gasto->comment ?>">
						</td>
					</tr>
				<?php endforeach ?>
			</tbody>			
		</table>
	</div>

</div>
<div class="modal fade slide-up in" id="modal-cashbox" tabindex="-1" role="dialog" aria-hidden="true" >
            <div class="modal-dialog modal-lg" style="">

                <div class="modal-content-wrapper">
                    
                    <div class="modal-content">
                        @include('backend.sales._formGastos')
                    </div>
                </div>
            </div>
        </div>

<div class="modal fade" id="" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-dialog-popout">
		<div class="modal-content">
			<div class="block block-themed block-transparent remove-margin-b">
				<div class="block-header">
					<ul class="block-options">
						<li>
							
						</li>
					</ul>
				</div>
				<div class="row block-content" id="contentListCashBox">
					@
				</div>
			</div>
		</div>
	</div>
</div>

@endsection	


@section('scripts')


@endsection