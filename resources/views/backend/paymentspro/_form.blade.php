    <link href="/assets/plugins/pace/pace-theme-flash.css" rel="stylesheet" type="text/css" />
    <link href="/assets/plugins/bootstrap3-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/plugins/bootstrap-tag/bootstrap-tagsinput.css" rel="stylesheet" type="text/css" />
    <link href="/assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" type="text/css" media="screen">
    <link href="/assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" media="screen">
    <script type="text/javascript" src="https://canvasjs.com/assets/script/canvasjs.min.js"></script> 

<style type="text/css">
    .input-group{
        width: 100%;
    }
</style>
<?php use \Carbon\Carbon; ?>
<div class="row">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="position: absolute; top: 15px; right: 15px; z-index: 9999"><i class="fa fa-close fa-2x"></i></button>
    <div class="col-xs-12">
        <!-- START PANEL -->
        <h2> <?php echo $room->nameRoom." de (".$room->user->name ?>)</h2>
        <div class="row">
            <div class="col-md-3 col-xs-12" style="border-right: 1px solid black">
                <div class="col-md-12">
                    <div class="panel-heading">
                        <div class="panel-title">
                           Realizar pago
                        </div>
                    </div>
                    <div class="panel-body">
                        <form role="form"  action="{{ url('admin/pagos-propietarios/create') }}" method="post">
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                        <input type="hidden" name="id" value="<?php echo $room->id ?>">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-eur"></i>
                                </span>
                                    <input type="number" class="form-control" name="import" placeholder="Importe" required="" aria-required="true" aria-invalid="false">
                            </div>
                                <br>
                            
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-euro"></i>
                                </span>
                                    <select class="full-width" data-init-plugin="select2" name="type" style="    min-height: 30px;">
                                        <?php for ($i=1; $i <= 4 ; $i++): ?>
                                            
                                            <option value="<?php echo $i ?>"><?php echo $typePayment->getPaymentType($i) ?></option>
                                        <?php endfor; ?>
                                    </select>
                            </div>
                            <br>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="pg-comment"></i>
                                </span>
                                    <input type="text" placeholder="Comentario" id="comment" name="comment" class="form-control">
                            </div>
                            <br>
                            <div class="input-group">
                                <button class="btn btn-complete" type="submit">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>              
            </div>

            <div class="col-md-9 col-xs-12">
                <div class="col-md-8 col-xs-12">
                    
                    <h3 class="tex-center">Resumen de pagos</h3>
                        
                    <div class="row">
                        <table class="table table-hover " >
                            <thead >
                                <th class ="text-center bg-complete text-white"></th>
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
                                           <button class="btn btn-danger btn-xs deletePayment" type="button" data-id="<?php echo $payment->id ?>">
                                               <i class="fa fa-close"></i>
                                           </button>
                                        </td>
                                        <td class="text-center">
                                            <?php $fecha = Carbon::createFromFormat('Y-m-d',$payment->datePayment) ?>
                                            <?php echo $fecha->format('d-m-Y') ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo $payment->import ?>
                                        </td>
                                        
                                        <td class="text-center">
                                            <?php echo $payment->getPaymentType($payment->type) ?>
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

                <div class="col-md-4 col-xs-12">
                    <h3 class="tex-center">Grafica de pagos</h3>
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <th class="text-center bg-complete text-white">Pagado</th>
                                    <th class="text-center bg-complete text-white">Pendiente</th>
                                </thead>
                                <thead >
                                    <?php if ($deuda != 0): ?>
                                        <th class ="text-center bg-success " style="width:<?php echo $deuda."%" ?>!important"><?php echo number_format($pagado,2,',','.') ?></th>
                                    <?php else: ?>
                                        <th class ="text-center bg-success " style=""><?php echo number_format($pagado,2,',','.') ?></th>
                                    <?php endif ?>
                                    <?php if ($debt == 0): ?>
                                    <?php else: ?>
                                        <th class ="text-center bg-danger text-black" style="width:<?php echo 100-$deuda."%" ?>!important"><?php echo number_format($debt,2,',','.') ?></th>

                                    <?php endif ?>
                                    
                                </thead>
                            </table>
                        </div>
                    </div>
                    <br>
                    <div class="panel-body">
                        <table class="table table-hover demo-table-search table-responsive-block" id="tableWithSearch" >
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
                <div style="clear: both;"></div> 
                <!-- <div>
                    <h2>Falta por pagar <?php echo number_format($debt,2,',','.') ?> €</h2>
                </div> -->  
                
            </div>
        </div>
    </div>
</div>


<script src="/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>

<script src="/assets/plugins/moment/moment.min.js"></script>
<script src="/assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="/assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
<script type="text/javascript">
    $('.deletePayment').click(function(event) {
        var id = $(this).attr('data-id');
        var line = "#payment-"+id;

        $.get('/admin/paymentspro/delete/'+id, function(data) {
            if (data == "ok") {
                $(line).hide();
            } else {
                alert(data);
            }
        });
    });
</script>


