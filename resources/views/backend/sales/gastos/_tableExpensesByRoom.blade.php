<?php   use \Carbon\Carbon;
setlocale(LC_TIME, "ES");
setlocale(LC_TIME, "es_ES");
?>
<div class="col-md-8 col-xs-12 not-padding">

        <h3 class="tex-center">listado de gastos</h3>

        <div class="table-responsive" >
            <table class="table table-striped">
                <thead>
                    <th class="text-center bg-complete text-white">Fecha</th>
                    <th class="text-center bg-complete text-white">Concepto</th>
                    <th class="text-center bg-complete text-white">Importe</th>
                    <th class="text-center bg-complete text-white">Piso</th>
                    <th class="text-center bg-complete text-white">Comentario</th>
                    <th class="text-center bg-complete text-white">accion</th>
                </thead>
                <tbody>
            	<?php $array = [0 => "Metalico",2 => "Banco"] ?>
	            <?php if (count( $gastos ) > 0): ?>
	            <?php foreach ($gastos as $gasto): ?>
	            <tr>
                    <td class="text-center" style="padding: 5px 8px !important">
                        <?php $fecha = Carbon::createFromFormat( 'Y-m-d' , $gasto->date ) ?>
	                    <?php echo $fecha->copy()->formatLocalized( '%d %b %y' ) ?>&nbsp;&nbsp;
                    </td>
                    <td class="text-center" style="padding: 5px 8px !important">
                        <?php echo $gasto->concept ?>
                    </td>

		            <?php
		            $divisor = 0;
		            if ( $room != "all" ) {
			            if ( preg_match( '/,/' , $gasto->PayFor ) ) {
				            $aux = explode( ',' , $gasto->PayFor );
				            for ( $i = 0 ; $i < count( $aux ) ; $i++ ) {
					            if ( !empty( $aux[ $i ] ) ) {
						            $divisor++;
					            }
				            }

			            }
		            } else {
			            $divisor == 1;
		            }

		            if ( $divisor == 0 ) {
			            $divisor = 1;
		            }
		            ?>
		            <td class="text-center">
                                <?php echo number_format( ($gasto->import / $divisor) , 2 , ',' , '.' ) ?>€
                            </td>
                            <td class="text-center" style="padding: 5px 8px !important">
                            	<?php if ($gasto->PayFor != NULL ): ?>
                        			<?php $aux = explode( ',' , $gasto->PayFor ) ?>
                        			<?php if ( count( $aux ) > 1 ): ?>
                        			    <?php
                                            for ( $i = 0 ; $i < count( $aux ) ; $i++ ) {
                                                if ( !empty( $aux[ $i ] ) ) {
                                                    echo \App\Rooms::find( $aux[ $i ] )->nameRoom . " ";
                                                }
                                            }
                                        ?>
                        			<?php else: ?>
                        			    <?php echo \App\Rooms::find( $aux[ 0 ] )->nameRoom ?>
                        			<?php endif ?>


                            	<?php else: ?>
	                            TODOS
	                            <?php endif ?>

                            </td>
                            <td class="text-center" style="padding: 5px 8px !important">
                                <?php echo $gasto->comment ?>
                            </td>
	                        <td class="text-center" style="padding: 5px 8px !important">
                                <button class="btn btn-xs btn-danger deleteExpenseByRoom" data-id="{{ $gasto->id }}"
                                        onclick="return confirm('¿Quieres Eliminar el gasto?');">
				                    <i class="fa fa-trash"></i>
				                </button>
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
<?php if ( $room == "all" ): ?>
    <?php $data = \App\Http\Controllers\LiquidacionController::getSalesByYearByRoomGeneral( "" , "all" ) ?>
<?php else: ?>
    <?php $data = \App\Http\Controllers\LiquidacionController::getSalesByYearByRoomGeneral( "" , $room->id ) ?>
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
            <thead>
                <th class="text-center bg-complete text-white"><?php echo number_format( $data[ 'total' ] , 2 , ',' , '.' ) ?>
	                €</th>

                <th class="text-center bg-success text-white"><?php echo number_format( $data[ 'pagado' ] , 2 , ',' , '.' ) ?>
	                €</th>

                <th class="text-center bg-danger text-white"
                    style=""><?php echo number_format( $data[ 'total' ] - $data[ 'pagado' ] , 2 , ',' , '.' ) ?>€</th>


            </thead>
        </table>
    </div>
    <div class="row">
        <div class="col-md-8 col-xs-12">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th class="text-center bg-success text-white">Metalico</th>
                        <th class="text-center"
                            style="color: #000;"><?php echo number_format( $data[ 'metalico' ] , 2 , ',' , '.' ) ?>
	                        €</th>
                    </tr>
                    <tr>
                        <th class="text-center bg-success text-white">Banco</th>
                        <th class="text-center"
                            style="color: #000;"><?php echo number_format( $data[ 'banco' ] , 2 , ',' , '.' ) ?>€</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

</div>