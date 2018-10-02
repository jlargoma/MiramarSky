<div class="col-md-12">
    <div class="col-md-12 push-20">
        <h2 style="letter-spacing: -1px; text-transform: uppercase">Listado de propietarios para transferencias</h2>
    </div>
    <div class="col-md-12">
        <form action="{{ url('/admin/stripe-connect/send-transfers') }}" method="post">
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            <?php foreach ($owneds as $owned): ?>
                <div class="col-md-12 col-xs-12 push-20" style="border-bottom: 2px #000 dashed; padding: 10px 0">
                    <div class="col-md-3 col-xs-12">
                        <input type="hidden" name="users[<?php echo $owned->id?>][id]" value="<?php echo $owned->id?>">

	                    <h4 class="text-center" style="margin: 0">
                            <span class="font-w800">PROPIETARIO</span>:<br> <?php echo strtoupper($owned->name); ?>
                        </h4>
                    </div>
                    <div class="col-md-3 col-xs-12">
                        <label for="users[<?php echo $owned->id?>][concept]">Concepto</label>
                        <input type="text" class="form-control" required name="users[<?php echo $owned->id?>][concept]" >
                    </div>
                    <div class="col-md-3 col-xs-12">
                        <label for="users[<?php echo $owned->id?>][import]">Importe</label>
                        <input type="number" step='0.01' required class="form-control" name="users[<?php echo
                        $owned->id?>][import]" >
                    </div>
                    <div class="col-md-3 col-xs-12">
                        <label for="users[<?php echo $owned->id?>][room_id]">Apto</label>
                        <select class="form-control full-width minimal" name="users[<?php echo $owned->id?>][room_id]" required>
		                    <?php foreach ($roomsOwned[$owned->id] as $room): ?>
                            <option value="<?php echo $room->id ?>">
			                    <?php echo substr($room->nameRoom." - ".$room->name, 0,12)  ?>
                            </option>
		                    <?php endforeach ?>
                        </select>
                    </div>
                </div>
            <?php endforeach; ?>
            <div class="col-md-12 col-xs-12 push-20">
                <button class="btn btn-primary" type="submit">
                   Enviar Transferencias
                </button>
            </div>
        </form>
    </div>
</div>