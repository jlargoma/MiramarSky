<?php
	use \Carbon\Carbon;
	setlocale(LC_TIME, "ES");
	setlocale(LC_TIME, "es_ES");
?>
@extends('layouts.admin-master')

@section('title') Administrador de reservas MiramarSKI @endsection
@section('content')

<div class="container container-fluid">
	<div class="row" >
	  	<h2>Resumen</h2>
  		<table class="table table-bordered table-hover  no-footer" id="basicTable" role="grid" >
			<tr>
				<th class ="text-center bg-complete text-white">ING. PROP</th>
				<th class ="text-center bg-complete text-white">Apto</th>
				<th class ="text-center bg-complete text-white">Park</th>
				<?php if ($room->luxury == 1): ?>
					<th class ="text-center bg-complete text-white">Sup.Lujo</th>
				<?php endif ?>
			</tr>
			<tr>
				<td class="text-center total">
					<?php if ($total > 0): ?>
						<?php echo number_format($total,0,',','.'); ?>€
					<?php else: ?>
						--- €
					<?php endif ?>												
				</td>
				<td class="text-center">
					<?php if ($apto > 0): ?>
						<?php echo number_format($apto,0,',','.'); ?>€
					<?php else: ?>
						--- €
					<?php endif ?>
				</td>
				<td class="text-center">
					<?php if ($park > 0): ?>
						<?php echo number_format($park,0,',','.'); ?>€
					<?php else: ?>
						--- €
					<?php endif ?>
				</td>
				<?php if ($room->luxury == 1): ?>
					<td class="text-center">
						<?php if ($lujo > 0): ?>
							<?php echo number_format($lujo,0,',','.'); ?>€
						<?php else: ?>
							--- €
						<?php endif ?>
					</td>
				<?php else: ?>
				<?php endif ?>
			</tr>
		</table>
	</div>
	<div class="row" >
	  <h2 class="text-center font-w800">Listado de reservas</h2>
	  <table class="table table-bordered table-hover  no-footer">
		  <thead>
			  <th class ="text-center bg-complete text-white">Cliente</th>
			  <th class ="text-center bg-complete text-white">Pers</th>
			  <th class ="text-center bg-complete text-white">IN</th>
			  <th class ="text-center bg-complete text-white">OUT</th>
			  <th class ="text-center bg-complete text-white">ING. PROP</th>
			  <th class ="text-center bg-complete text-white">Apto</th>
			  <th class ="text-center bg-complete text-white">Park.</th>
			  <?php if ($room->luxury == 1): ?>
				  <th class ="text-center bg-complete text-white" >Sup.Lujo</th>
			  <?php endif ?>
			  <th class ="text-center bg-complete text-white">Oferta</th>
		  </thead>
		  <tbody>
			  <?php foreach ($books as $book): ?>
				  <tr>
					  <td class="text-center" data-id="<?php echo $book->id; ?>"><?php echo ucfirst(strtolower($book->customer->name)) ?> </td>
					  <td class="text-center" style="text-align: center"><?php echo $book->pax ?> </td>
					  <td class="text-center" style="text-align: center">
						  <?php
							  $start = Carbon::CreateFromFormat('Y-m-d',$book->start);
							  echo $start->formatLocalized('%d-%b');
						  ?>
					  </td>
					  <td class="text-center" style="text-align: center">
						  <?php
							  $finish = Carbon::CreateFromFormat('Y-m-d',$book->finish);
							  echo $finish->formatLocalized('%d-%b');
						  ?>
					  </td>
					  <td class="text-center total" style="text-align: center">
						  <?php if ($book->type_book != 7 && $book->type_book != 8 ): ?>
							  <?php $cost = ($book->cost_apto + $book->cost_park + $book->cost_lujo) ?>
							  <?php if ($cost > 0 ): ?>
								  <?php echo number_format($cost,0,',','.') ?>€
							  <?php else: ?>
								  ---€
							  <?php endif ?>
						  <?php else: ?>
							  ---€
						  <?php endif ?>

					  </td>
					  <td class="text-center"  style="text-align: center">

						  <?php if ($book->type_book != 7 && $book->type_book != 8 ): ?>
							  <?php if ($book->cost_apto > 0 ): ?>
								  <?php echo number_format($book->cost_apto,0,',','.') ?>€
							  <?php else: ?>
								  ---€
							  <?php endif ?>
						  <?php else: ?>
							  ---€
						  <?php endif ?>

					  </td>
					  <td class="text-center" style="text-align: center">
						  <?php if ($book->type_book != 7 && $book->type_book != 8 ): ?>
							  <?php if ($book->cost_park > 0 ): ?>
								  <?php echo number_format($book->cost_park,0,',','.') ?>€
							  <?php else: ?>
								  ---€
							  <?php endif ?>
						  <?php else: ?>
							  ---€
						  <?php endif ?>
					  </td>
					  <?php if ($room->luxury == 1): ?>
						  <td class="text-center" style="text-align: center">
							  <?php if ($book->type_book != 7 && $book->type_book != 8 ): ?>
								  <?php $auxLuxury = $book->cost_lujo ?>
								  <?php if ($auxLuxury > 0): ?>
									  <?php echo $auxLuxury ?>€
								  <?php else: ?>
									  ---€
								  <?php endif ?>
							  <?php else: ?>
								  ---€
							  <?php endif ?>
						  </td>
					  <?php endif ?>
					  <td class="text-center" style="text-align: center">
						  <?php if (!empty($book->book_owned_comments) && $book->promociones != 0 ): ?>
							 <span style="color:red">OFERTA</span>
						  <?php endif ?>
					  </td>
				  </tr>
			  <?php endforeach ?>
		  </tbody>
	  </table>
	</div>
	<div class="row" >
	  <?php $sumPagos = 0; ?>
	  <?php if (count($pagos)> 0): ?>
		<div class="col-xs-12 " style="width: 100%;">
		  <div class="col-md-3 not-padding" style="width: 25%; float: left;">
			  <div class="col-xs-12  bg-complete push-0">
				  <h5 class="text-left white">
					  Fecha de pago
				  </h5>
			  </div>
		  </div>

		  <div class="col-md-3 not-padding" style="width: 25%; float: left;">
			  <div class="col-xs-12   bg-complete push-0">
				  <h5 class="text-left white">
					  Concepto
				  </h5>
			  </div>
		  </div>
		  <div class="col-md-3 not-padding" style="width: 25%; float: left;">
			  <div class="col-xs-12  bg-complete push-0">
				  <h5 class="text-left white">
					  Importe
				  </h5>
			  </div>
		  </div>
		  <div class="col-md-3 not-padding" style="width: 25%; float: left;">
			  <div class="col-xs-12   bg-complete push-0">
				  <h5 class="text-left white">
					  Pendiente
				  </h5>
			  </div>
		  </div>
	    </div>

		<?php foreach ($pagos as $pago): ?>
		  <?php $sumPagos += $pago->import ?>
		  <div class="col-xs-12 push-0" style="width: 100%;">
			  <div class="col-md-3 not-padding" style="width: 25%; float: left;" >
				  <div class="col-xs-12 push-0">
					  <h5 class="text-left"><?php echo Carbon::createFromFormat('Y-m-d',$pago->date)->format('d-m-Y')?></h5>
				  </div>
			  </div>
			  <div class="col-md-3 not-padding" style="width: 25%; float: left;" >
				  <div class="col-xs-12 push-0">
					  <h5 class="text-left"><?php echo $pago->concept ?></h5>
				  </div>
			  </div>
			  <div class="col-md-3 not-padding" style="width: 25%; float: left;" >
				  <div class="col-xs-12 push-0">
					  <?php
						  $divisor = 0;
						  if(preg_match('/,/', $pago->PayFor)){
							  $aux = explode(',', $pago->PayFor);
							  for ($i = 0; $i < count($aux); $i++){
								  if ( !empty($aux[$i]) ){
									  $divisor ++;
								  }
							  }

						  }else{
							  $divisor = 1;
						  }
						  $expense = $pago->import / $divisor;
					  ?>
					<h5 class="text-center"><?php echo number_format($expense,2,',','.') ?>€</h5>
					  <?php $pagototalProp += $expense;?>
				  </div>
			  </div>

			  <div class="col-md-3 not-padding" style="width: 25%; float: left;" >
				  <div class="col-xs-12 push-0" style="">
					  <h5 class="text-left text-danger"><?php echo number_format($total - $pagototalProp,2,',','.'); ?>€</h5>
				  </div>
			  </div>
		  </div>
	    <?php endforeach ?>

	  <?php else: ?>
		<div class="col-xs-12 text-center">
			  Aun no hay pagos realizados
	    </div>
	  <?php endif ?>
	</div>
	<div class="row" style="width: 100%;">
	  <div class="col-md-4 bg-complete push-20" style="width: 33.33%; float: left;" >
		  <div class="col-md-6">
			  <h5 class="text-center white">GENERADO</h5>
		  </div>
		  <div class="col-md-6 text-center text-white">
			  <h5 class="text-center white"><strong><?php echo number_format($total,2,',','.'); ?>€</strong></h5>
		  </div>
	  </div>
	  <div class="col-md-4 bg-success push-20" style="width: 33.33%; float: left;" >
		  <div class="col-md-6">
			  <h5 class="text-center white">PAGADO</h5>
		  </div>
		  <div class="col-md-6 text-center text-white">
			  <h5 class="text-center white"><strong><?php echo number_format($pagototalProp,2,',','.'); ?>€</strong></h5>
		  </div>
	  </div>
	  <div class="col-md-4 bg-danger push-20" style="width: 33.33%; float: left;" >
		  <div class="col-md-6">
			  <h5 class="text-center white">PENDIENTE</h5>
		  </div>
		  <div class="col-md-6text-center text-white">
			  <h5 class="text-center white"><strong><?php echo number_format(($total - $pagototalProp),2,',','.'); ?>€</strong></h5>
		  </div>
	  </div>
	</div>
</div>
@endsection
