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
            
        <div class="row push-20" style="overflow-y: auto; max-height: 500px;">
            <table class="table table-condensed  table-striped">
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
                            
                            <td class="text-center" style="padding: 5px 8px !important">
                                <b><?php echo $gasto->import ?>€</b>
                            </td>
                            <td class="text-center" style="padding: 5px 8px !important">
                            	<?php if ($gasto->PayFor != NULL ): ?>
                        			<?php $aux = explode(',', $gasto->PayFor) ?>
                    				<?php echo \App\Rooms::find($aux[0])->nameRoom ?>
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
    	<?php $data = \App\Http\Controllers\LiquidacionController::getSalesByYearByRoom("","all") ?>
    <?php else: ?>
    	<?php $data = \App\Http\Controllers\LiquidacionController::getSalesByYearByRoom("", $room->id) ?>
    <?php endif ?>
	
    <div class="col-md-4 col-xs-12">
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