<?php   use \Carbon\Carbon;
setlocale(LC_TIME, "ES");
setlocale(LC_TIME, "es_ES");
?>
<div class="row bg-white">
	<div class="container">
		<div class="col-md-4 col-md-offset-1 col-xs-12">
			<h2 class="text-center">HOJA DE GASTOS</h2>
		</div>
		<div class="col-md-2 col-xs-12" style="padding: 10px;">
			<?php if ( $room == 'all'): ?>
				<select class="form-control full-width minimal selectorRoom">
					<option <?php if( $room == 'all'){ echo "selected";}?> value="all"> TODAS </option>
	                <?php foreach (\App\Rooms::where('state', 1)->orderBy('order', 'ASC')->get() as $roomX): ?>
	                    <option value="<?php echo $roomX->id ?>" {{ $roomX->id == $room ? 'selected' : '' }} >
	                        <?php echo $roomX->nameRoom  ?>
	                    </option>
	                <?php endforeach ?>
	            </select>
			<?php else: ?>
				<select class="form-control full-width minimal selectorRoom">
					<option <?php if( $room == 'all'){ echo "selected";}?> value="all"> TODAS </option>
	                <?php foreach (\App\Rooms::where('state', 1)->orderBy('order', 'ASC')->get() as $roomX): ?>
	                    <option value="<?php echo $roomX->id ?>" {{ $roomX->id == $room->id ? 'selected' : '' }} >
	                        <?php echo $roomX->nameRoom  ?>
	                    </option>
	                <?php endforeach ?>
	            </select>
			<?php endif ?>


		</div>
	</div>

</div>
<div class="col-md-12 col-xs-12 bg-white">
    <div class="col-md-8 col-xs-12 not-padding">

        <h3 class="tex-center">listado de gastos</h3>

        <div class="row table-responsive push-20" style="overflow-y: auto; max-height: 450px; border: 0px;">
            <table class="table table-striped">
                <thead >
                    <th class ="text-center bg-complete text-white">Fecha</th>
                    <th class ="text-center bg-complete text-white">Concepto</th>
                    <th class ="text-center bg-complete text-white">Importe</th>
                    <th class ="text-center bg-complete text-white">Piso</th>
                    <th class ="text-center bg-complete text-white">Comentario</th>
                </thead>
                <tbody>
            	<?php $array = [0 =>"Metalico Jorge", 1 =>"Metalico Jaime",2 =>"Banco Jorge",3=>"Banco Jaime"] ?>
                <?php if (count($gastos) > 0): ?>
                    <?php foreach ($gastos as $gasto): ?>
                        <tr>
                            <td class="text-center" style="padding: 5px 8px !important">
                                <?php $fecha = Carbon::createFromFormat('Y-m-d',$gasto->date) ?>
                                <?php echo $fecha->formatLocalized('%d %b %y') ?>&nbsp;&nbsp;
                            </td>
                            <td class="text-center" style="padding: 5px 8px !important">
                                <?php echo $gasto->concept ?>
                            </td>

                            <?php
                                $divisor = 0;
                                if($room != "all"){
                                    if(preg_match('/,/', $gasto->PayFor)){
                                        $aux = explode(',', $gasto->PayFor);
                                        for ($i = 0; $i < count($aux); $i++){
                                            if ( !empty($aux[$i]) ){
                                                $divisor ++;
                                            }
                                        }

                                    }
                                }else{
                                    $divisor == 1;
                                }

                                if($divisor == 0){$divisor = 1;}
                            ?>
                            <td class="text-center">
                                <?php echo number_format(($gasto->import / $divisor),2,',','.') ?>€
                            </td>
                            <td class="text-center" style="padding: 5px 8px !important">
                            	<?php if ($gasto->PayFor != NULL ): ?>
                        			<?php $aux = explode(',', $gasto->PayFor) ?>
                        			<?php if(count($aux) > 1): ?>
                        			    <?php
                                            for ($i = 0; $i < count($aux); $i++){
                                                if (!empty($aux[$i])){
                                                    echo \App\Rooms::find($aux[$i])->nameRoom." ";
                                                }
                                            }
                                        ?>
                        			<?php else: ?>
                        			    <?php echo \App\Rooms::find($aux[0])->nameRoom ?>
                        			<?php endif ?>


                            	<?php else: ?>
                            		TODOS
                            	<?php endif ?>

                            </td>
                            <td class="text-center" style="padding: 5px 8px !important">
                                <?php echo $gasto->comment ?>
                            </td>
                        </tr>
                    <?php endforeach ?>
                <?php else: ?>
                    <tr>
                        <td class="text-center" colspan="3">No hay Gastos</td>
                    </tr>
                <?php endif ?>

                </tbody>
            </table>
        </div>
    </div>
    <?php if ($room == "all"): ?>
    	<?php $data = \App\Http\Controllers\LiquidacionController::getSalesByYearByRoomGeneral("","all") ?>
    <?php else: ?>
    	<?php $data = \App\Http\Controllers\LiquidacionController::getSalesByYearByRoomGeneral("", $room->id) ?>
    <?php endif ?>

    <div class="col-md-4 col-xs-12">
        <div class="row">
            <h3 class="tex-center">Grafica de pagos</h3>
            <table class="table table-hover">
                <thead>
                    <th class="text-center bg-complete text-white">Generado</th>
                    <th class="text-center bg-success text-white">Pagado</th>
                    <th class="text-center bg-danger text-white">Pendiente</th>
                </thead>
                <thead >
                    <th class ="text-center bg-complete text-white"><?php echo number_format($data['total'],2,',','.') ?>€</th>

                    <th class ="text-center bg-success text-white" ><?php echo number_format($data['pagado'],2,',','.') ?>€</th>

                    <th class ="text-center bg-danger text-white" style=""><?php echo number_format($data['total'] - $data['pagado'],2,',','.') ?>€</th>


                </thead>
            </table>
        </div>
        <div class="row">
            <div class="col-md-8 col-xs-12">
                <table class="table table-striped">
                    <thead >
                        <tr>
                            <th class ="text-center bg-success text-white">Metalico</th>
                            <th class="text-center" style="color: #000;"><?php echo number_format($data['metalico'] ,2,',','.') ?>€</th>
                        </tr>
                        <tr>
                            <th class ="text-center bg-success text-white">Banco</th>
                            <th class="text-center" style="color: #000;"><?php echo number_format($data['banco'] ,2,',','.') ?>€</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

    </div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$('.selectorRoom').change(function(event) {
			$.get('/admin/gastos/getHojaGastosByRoom/'+$('#fecha').val()+'/'+$(this).val(), function(data) {

				$('.contentExpencesByRoom').empty().append(data);
			});
		});
	});
</script>