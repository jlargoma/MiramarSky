

<style type="text/css">
    .input-group{
        width: 100%;
    }
</style>
<?php use \Carbon\Carbon; ?>
<div class="col-xs-12">
    <h2> <?php echo $room->nameRoom." de (".$room->user->name ?>)</h2>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="position: absolute; top: 15px; right: 15px; z-index: 9999"><i class="fa fa-close fa-2x"></i></button>
</div>
<div class="row">

    <div class="col-xs-12">
        <!-- START PANEL -->
        
        <div class="row">

            
            <div class="col-md-12 col-xs-12">
                <div class="col-md-12 col-xs-12">
                    <div class="col-md-5 col-xs-12">
                        <h3 class="tex-center">Grafica de pagos</h3>
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <th class="text-center bg-complete text-white">Generado</th>
                                        <th class="text-center bg-success text-white">Pagado</th>
                                        <th class="text-center bg-danger text-white">Pendiente</th>
                                    </thead>
                                    <thead >
                                        <th class ="text-center bg-complete text-white"><?php echo number_format($total,2,',','.') ?>€</th>

                                        <th class ="text-center bg-success text-white" ><?php echo number_format($pagado,2,',','.') ?>€</th>
                             
                                        <th class ="text-center bg-danger text-white" style=""><?php echo number_format($total - $pagado,2,',','.') ?>€</th>
                                       
                                        
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <table class="table table-striped" >
                                <thead >
                                    <tr>
                                        <th class ="text-center bg-complete text-white">Metalico</th>
                                        <th class="text-center "><?php echo number_format($metalico,2,',','.') ?></th>
                                    </tr>
                                    <tr>
                                        <th class ="text-center bg-complete text-white">Banco</th>
                                        <th class="text-center "><?php echo number_format($banco,2,',','.') ?></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-7 col-xs-12">
                        
                        <h3 class="tex-center">Resumen de pagos</h3>
                        <?php $array = [0 =>"Metalico Jorge", 1 =>"Metalico Jaime",2 =>"Banco Jorge",3=>"Banco Jaime"] ?>
                            
                        <div class="row">
                            <table class="table table-hover " >
                                <thead >
                                    <th class ="text-center bg-complete text-white">Fecha</th>
                                    <th class ="text-center bg-complete text-white">Importe</th>
                                    <th class ="text-center bg-complete text-white">Metodo</th>
                                    <th class ="text-center bg-complete text-white">Comentario</th>                                        
                                </thead>
                                <tbody>
                                <?php if (count($payments) > 0): ?>
                                    <?php foreach ($payments as $payment): ?>
                                        <tr id="payment-<?php echo $payment->id ?>">
                                            
                                            <td class="text-center">
                                                <?php $fecha = Carbon::createFromFormat('Y-m-d',$payment->date) ?>
                                                <?php echo $fecha->format('d-m-Y') ?>
                                            </td>
                                            <td class="text-center">
                                                <?php echo $payment->import ?>
                                            </td>
                                            
                                            <td class="text-center">
                                                <?php echo $array[$payment->typePayment] ?>
                                            </td>
                                            <td class="text-center">
                                                <?php echo $payment->comment ?>
                                            </td>
                                        </tr>
                                    <?php endforeach ?>
                                <?php else: ?>
                                    <tr>
                                        <td class="text-center" colspan="3">No hay Pagos para este apartamento</td>
                                    </tr>
                                <?php endif ?>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>




