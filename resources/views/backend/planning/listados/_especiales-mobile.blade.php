<?php use \Carbon\Carbon; ?>

<table class="table table-hover dataTable no-footer">
	<thead>
		<th class="Bloqueado text-white text-center">Nombre</th>
		<th class="Bloqueado text-white text-center" style="min-width:35px">In</th>
		<th class="Bloqueado text-white text-center" style="min-width:35px ">Out</th>
		<th class="Bloqueado text-white text-center">Pax</th>
		<th class="Bloqueado text-white text-center">Tel</th>
		<th class="Bloqueado text-white text-center" style="min-width:100px">Apart</th>
		<th class="Bloqueado text-white text-center"><i class="fa fa-moon-o"></i></th>
		<th class="Bloqueado text-white text-center" style="min-width:50px">PVP</th>
		<th class="Bloqueado text-white text-center" style="min-width:100px">Estado</th>
	</thead>
	<tbody>
		<?php foreach ($arrayBooks["especiales"] as $especial): ?>
			<tr>
				<td class="text-center sm-p-t-10 sm-p-b-10"><?php echo $especial->customer->name ?></td>
				<td class="text-center sm-p-t-10 sm-p-b-10"><?php echo Carbon::CreateFromFormat('Y-m-d',$especial->start)->format('d-M') ?></td>
				<td class="text-center sm-p-t-10 sm-p-b-10"><?php echo Carbon::CreateFromFormat('Y-m-d',$especial->finish)->format('d-M') ?></td>
				<td class="text-center sm-p-t-10 sm-p-b-10"><?php echo $especial->pax ?></td>
				<td class="text-center sm-p-t-10 sm-p-b-10"><a href="tel:<?php echo $especial->customer->phone ?>"><i class="fa fa-phone"></i></a></td>
				<td class="text-center sm-p-t-10 sm-p-b-10"><?php echo $especial->room->name ?></td>
				<td class="text-center sm-p-t-10 sm-p-b-10"><?php echo $especial->nigths ?></td>
				<td class="text-center sm-p-t-10 sm-p-b-10"><?php echo $especial->total_price ?> €</td>
				<td class="text-center sm-p-t-10 sm-p-b-10 sm-p-l-10 sm-p-r-10">
					<select class="status form-control" data-id="<?php echo $book->id ?>" >
						<?php for ($i=1; $i < 9; $i++): ?> 
							<?php if ($i == $especial->type_book): ?>
								<option selected value="<?php echo $i ?>"  data-id="<?php echo $especial->id ?>"><?php echo $especial->getStatus($i) ?></option>
							<?php else: ?>
								<option value="<?php echo $i ?>"><?php echo $especial->getStatus($i) ?></option>
							<?php endif ?>                                          

						<?php endfor; ?>
					</select>
				</td>
			</tr>
		<?php endforeach ?>
	</tbody>
</table>