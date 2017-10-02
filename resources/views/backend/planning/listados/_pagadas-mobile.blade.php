<?php   use \Carbon\Carbon;  
        setlocale(LC_TIME, "ES"); 
        setlocale(LC_TIME, "es_ES"); 
?>

<table class="table  table-responsive table-striped" style="margin-top: 0;">
	<thead>
		<th class="Pagada-la-señal text-white text-center">Nombre</th>
		<th class="Pagada-la-señal text-white text-center" style="min-width:50px">In</th>
		<th class="Pagada-la-señal text-white text-center" style="min-width:50px ">Out</th>
		<th class="Pagada-la-señal text-white text-center">Pax</th>
		<th class="Pagada-la-señal text-white text-center">Tel</th>
		<th class="Pagada-la-señal text-white text-center" style="min-width:100px">Apart</th>
		<th class="Pagada-la-señal text-white text-center"><i class="fa fa-moon-o"></i></th>
		<th class="Pagada-la-señal text-white text-center" style="min-width:65px">PVP</th>
		<th class="Pagada-la-señal text-white text-center" style="min-width:200px">Estado</th>
	</thead>
	<tbody>
		<?php foreach ($arrayBooks["pagadas"] as $pagada): ?>
			<tr class="<?php echo ucwords($book->getStatus($pagada->type_book)) ;?>">
				<td class="text-center sm-p-t-10 sm-p-b-10"><a href="{{url ('/admin/reservas/update')}}/<?php echo $pagada->id ?>"><?php echo $pagada->customer->name ?></a></td>
				<td class="text-center sm-p-t-10 sm-p-b-10"><?php echo Carbon::CreateFromFormat('Y-m-d',$pagada->start)->formatLocalized('%d %b') ?></td>
				<td class="text-center sm-p-t-10 sm-p-b-10"><?php echo Carbon::CreateFromFormat('Y-m-d',$pagada->finish)->formatLocalized('%d %b') ?></td>
				<td class="text-center sm-p-t-10 sm-p-b-10"><?php echo $pagada->pax ?></td>
				<td class="text-center sm-p-t-10 sm-p-b-10"><a href="tel:<?php echo $pagada->customer->phone ?>"><i class="fa fa-phone"></i></a></td>
				<td class="text-center sm-p-t-10 sm-p-b-10">
					<select class="room form-control minimal" data-id="<?php echo $pagada->id ?>"  >
					    
					    <?php foreach ($rooms as $room): ?>
					        <?php if ($room->id == $pagada->room_id): ?>
					            <option selected value="<?php echo $pagada->room_id ?>" data-id="<?php echo $room->name ?>">
					                <?php echo substr($room->name,0,5) ?>
					            </option>
					        <?php else:?>
					            <option value="<?php echo $room->id ?>"><?php echo substr($room->name,0,5) ?></option>
					        <?php endif ?>
					    <?php endforeach ?>

					</select>
				</td>
				<td class="text-center sm-p-t-10 sm-p-b-10"><?php echo $pagada->nigths ?></td>
				<td class="text-center sm-p-t-10 sm-p-b-10">
					<?php echo $pagada->total_price ?> €<br>
					<?php if (isset($payment[$book->id])): ?>
						<?php echo "<p style='color:red'>".$payment[$book->id]."</p>" ?>
					<?php else: ?>
					<?php endif ?>
				</td>
				<td class="text-center sm-p-t-10 sm-p-b-10 sm-p-l-10 sm-p-r-10">
					<select class="status form-control minimal" data-id="<?php echo $pagada->id ?>">

					    <?php for ($i=1; $i < 9; $i++): ?> 
					        <option <?php echo $i == ($pagada->type_book) ? "selected" : ""; ?> 
					                <?php echo ($i  == 1 || $i == 5) ? "style='font-weight:bold'" : "" ?>
					                value="<?php echo $i ?>"  data-id="<?php echo $pagada->id ?>">
					                <?php echo $pagada->getStatus($i) ?></option>                                    
					         
					    <?php endfor; ?>
					</select>
				</td>
			</tr>
		<?php endforeach ?>
	</tbody>
</table>