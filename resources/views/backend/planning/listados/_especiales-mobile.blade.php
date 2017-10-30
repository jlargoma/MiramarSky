<?php   use \Carbon\Carbon;  
        setlocale(LC_TIME, "ES"); 
        setlocale(LC_TIME, "es_ES"); 
?>

<table class="table  table-responsive table-striped" style="margin-top: 0;">
	<thead>
		<th class="Reserva Propietario text-white text-center">Nombre</th>
		<th class="Reserva Propietario text-white text-center" style="min-width:50px">In</th>
		<th class="Reserva Propietario text-white text-center" style="min-width:50px ">Out</th>
		<th class="Reserva Propietario text-white text-center">Pax</th>
		<th class="Reserva Propietario text-white text-center">Tel</th>
		<th class="Reserva Propietario text-white text-center" style="min-width:100px">Apart</th>
		<th class="Reserva Propietario text-white text-center"><i class="fa fa-moon-o"></i></th>
		<th class="Reserva Propietario text-white text-center" style="min-width:65px">PVP</th>
		<th class="Reserva Propietario text-white text-center" style="min-width:200px">Estado</th>
	</thead>
	<tbody>
		<?php foreach ($arrayBooks["especiales"] as $especial): ?>
			<tr class="<?php echo ucwords($book->getStatus($especial->type_book)) ;?>">
				<td class="text-center sm-p-t-10 sm-p-b-10" title="<?php echo $especial->customer->name ?> - <?php echo $especial->customer->email ?>"><?php echo $especial->customer->name ?></td>
				<td class="text-center sm-p-t-10 sm-p-b-10"><?php echo Carbon::CreateFromFormat('Y-m-d',$especial->start)->formatLocalized('%d %b') ?></td>
				<td class="text-center sm-p-t-10 sm-p-b-10"><?php echo Carbon::CreateFromFormat('Y-m-d',$especial->finish)->formatLocalized('%d %b') ?></td>
				<td class ="text-center" >
				    <?php if ($especial->real_pax > 6 ): ?>
				        <?php echo $especial->real_pax ?><i class="fa fa-exclamation" aria-hidden="true" style="color: red"></i>
				    <?php else: ?>
				        <?php echo $especial->pax ?>
				    <?php endif ?>
				        
				</td>
				<td class="text-center sm-p-t-10 sm-p-b-10"><a href="tel:<?php echo $especial->customer->phone ?>"><i class="fa fa-phone"></i></a></td>
				<td class="text-center sm-p-t-10 sm-p-b-10">
					<select class="room form-control minimal" data-id="<?php echo $especial->id ?>"  >
					    
					    <?php foreach ($rooms as $room): ?>
					        <?php if ($room->id == $especial->room_id): ?>
					            <option selected value="<?php echo $especial->room_id ?>" data-id="<?php echo $room->name ?>">
					                <?php echo substr($room->name,0,5) ?>
					            </option>
					        <?php else:?>
					            <option value="<?php echo $room->id ?>"><?php echo substr($room->name,0,5) ?></option>
					        <?php endif ?>
					    <?php endforeach ?>

					</select>
				</td>
				<td class="text-center sm-p-t-10 sm-p-b-10"><?php echo $especial->nigths ?></td>
				<td class="text-center sm-p-t-10 sm-p-b-10"><?php echo $especial->total_price ?> €</td>
				<td class="text-center sm-p-t-10 sm-p-b-10 sm-p-l-10 sm-p-r-10">
					<select class="status form-control minimal" data-id="<?php echo $especial->id ?>">

					    <?php for ($i=1; $i < 9; $i++): ?> 
					        <?php if ($i == 5 && $especial->customer->email == ""): ?>
					        <?php else: ?>
					            <option <?php echo $i == ($especial->type_book) ? "selected" : ""; ?> 
					            <?php echo ($i  == 1 || $i == 5) ? "style='font-weight:bold'" : "" ?>
					            value="<?php echo $i ?>"  data-id="<?php echo $especial->id ?>">
					                <?php echo $especial->getStatus($i) ?>
					                
					            </option>   
					        <?php endif ?>
					                                         

					    <?php endfor; ?>
					</select>
				</td>
			</tr>
		<?php endforeach ?>
	</tbody>
</table>