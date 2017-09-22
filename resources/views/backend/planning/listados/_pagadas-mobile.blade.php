<?php use \Carbon\Carbon; ?>
<table class="table table-hover dataTable no-footer">
	<thead>
		<th class="Pagada-la-señal text-white text-center">Nombre</th>
		<th class="Pagada-la-señal text-white text-center" style="min-width:35px">In</th>
		<th class="Pagada-la-señal text-white text-center" style="min-width:35px ">Out</th>
		<th class="Pagada-la-señal text-white text-center">Pax</th>
		<th class="Pagada-la-señal text-white text-center">Tel</th>
		<th class="Pagada-la-señal text-white text-center" style="min-width:100px">Apart</th>
		<th class="Pagada-la-señal text-white text-center"><i class="fa fa-moon-o"></i></th>
		<th class="Pagada-la-señal text-white text-center" style="min-width:50px">PVP</th>
		<th class="Pagada-la-señal text-white text-center" style="min-width:100px">Estado</th>
	</thead>
	<tbody>
		<?php foreach ($arrayBooks["pagadas"] as $pagada): ?>
			<tr>
				<td class="text-center sm-p-t-10 sm-p-b-10"><a href="{{url ('/admin/reservas/update')}}/<?php echo $pagada->id ?>"><?php echo $pagada->customer->name ?></a></td>
				<td class="text-center sm-p-t-10 sm-p-b-10"><?php echo Carbon::CreateFromFormat('Y-m-d',$pagada->start)->format('d-M') ?></td>
				<td class="text-center sm-p-t-10 sm-p-b-10"><?php echo Carbon::CreateFromFormat('Y-m-d',$pagada->finish)->format('d-M') ?></td>
				<td class="text-center sm-p-t-10 sm-p-b-10"><?php echo $pagada->pax ?></td>
				<td class="text-center sm-p-t-10 sm-p-b-10"><a href="tel:<?php echo $pagada->customer->phone ?>"><i class="fa fa-phone"></i></a></td>
				<td class="text-center sm-p-t-10 sm-p-b-10"><?php echo $pagada->room->name ?></td>
				<td class="text-center sm-p-t-10 sm-p-b-10"><?php echo $pagada->nigths ?></td>
				<td class="text-center sm-p-t-10 sm-p-b-10">
					<?php echo $pagada->total_price ?> €<br>
					<?php if (isset($payment[$book->id])): ?>
						<?php echo "<p style='color:red'>".$payment[$book->id]."</p>" ?>
					<?php else: ?>
					<?php endif ?>
				</td>
				<td class="text-center sm-p-t-10 sm-p-b-10 sm-p-l-10 sm-p-r-10">
					<select class="status form-control" data-id="<?php echo $pagada->id ?>" >
						<?php for ($i=1; $i < 9; $i++): ?> 
							<?php if ($i == $pagada->type_book): ?>
								<option selected value="<?php echo $i ?>"  data-id="<?php echo $pagada->id ?>"><?php echo $pagada->getStatus($i) ?></option>
							<?php else: ?>
								<option value="<?php echo $i ?>"><?php echo $pagada->getStatus($i) ?></option>
							<?php endif ?>                                          
							
						<?php endfor; ?>
					</select>
				</td>
			</tr>
		<?php endforeach ?>
	</tbody>
</table>