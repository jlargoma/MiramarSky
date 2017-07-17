    <link href="/assets/plugins/pace/pace-theme-flash.css" rel="stylesheet" type="text/css" />
    <link href="/assets/plugins/bootstrapv3/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/plugins/bootstrap3-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/plugins/bootstrap-tag/bootstrap-tagsinput.css" rel="stylesheet" type="text/css" />
    <link href="/assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" type="text/css" media="screen">
    <link href="/assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" media="screen">


<style type="text/css">
    .input-group{
        width: 100%;
    }
</style>
<?php use \Carbon\Carbon; ?>
<div class="container-fixed-lg">
    <div>
        <div style="width: 100%">
            <!-- START PANEL -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title">
                        <h2> <?php echo $room->nameRoom." de ".$room->user->name ?></h2>
                    </div>
                </div>
                <div class="panel-body">

                    <div class="col-md-3" style="border-right: 1px solid black">
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
                                            <input type="text" class="form-control" name="import" placeholder="Importe" required="" aria-required="true" aria-invalid="false">
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
                                        <span class="input-group-addon">
                                            <i class="fa fa-euro"></i>
                                        </span>
                                            <select class="full-width" data-init-plugin="select2" name="type" style="    min-height: 30px;">
                                                <option value="1"><?php echo $typePayment->getPaymentType(1) ?></option>
                                                <option value="2"><?php echo $typePayment->getPaymentType(2) ?></option>
                                                <option value="3"><?php echo $typePayment->getPaymentType(3) ?></option>
                                            </select>
                                    </div>
                                    <br>
                                    <div class="input-group">
                                        <button class="btn btn-complete" type="submit">Guardar</button>
                                    </div>
                                </form>
                            </div>
                        </div>              
                    </div>

                    <div class="col-md-9">
                        <div class="col-md-8">
                            <div class="panel-heading">
                                <div class="panel-title">
                                   Resumen de pagos
                                </div>
                            </div>
                            <div class="panel-body">
                                <table class="table table-hover demo-table-search table-responsive-block" id="tableWithSearch" >
                                    <thead >
                                        <th class ="text-center bg-complete text-white">Fecha</th>
                                        <th class ="text-center bg-complete text-white">Importe</th>
                                        <th class ="text-center bg-complete text-white">Comentario</th>
                                        <th class ="text-center bg-complete text-white">Metodo</th>
                                    </thead>
                                    <tbody>
                                    <?php if (count($payments) > 0): ?>
                                        <?php foreach ($payments as $payment): ?>
                                            <tr>
                                                <td class="text-center">
                                                    <?php $fecha = Carbon::createFromFormat('Y-m-d',$payment->datePayment) ?>
                                                    <?php echo $fecha->format('d-m-Y') ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php echo $payment->import ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php echo $payment->comment ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php echo $typePayment->getPaymentType($payment->type) ?>
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

                        <div class="col-md-4">
                            <div class="panel-heading">
                                <div class="panel-title">
                                   Grafica de pagos
                                </div>
                            </div>
                            <div class="panel-body">
                                <table class="table table-hover demo-table-search table-responsive-block" id="tableWithSearch" >
                                    <thead >
                                        <th class ="text-center bg-success " style="width:<?php echo $deuda."%" ?>!important"><?php echo number_format($total - $debt,2,',','.') ?></th>
                                        <?php if ($debt == 0): ?>
                                        <?php else: ?>
                                            <th class ="text-center bg-danger text-black" style="width:<?php echo 100-$deuda."%" ?>!important"><?php echo number_format($debt,2,',','.') ?></th>
                                        <?php endif ?>
                                        
                                    </thead>
                                </table>
                            </div>
                            <br>
                            <div class="panel-body">
                                <table class="table table-hover demo-table-search table-responsive-block" id="tableWithSearch" >
                                    <thead >
                                        <tr>
                                            <th class ="text-center bg-complete text-white">Metalico</th>
                                            <th class="text-center "><?php echo $metalico ?></th>
                                        </tr>
                                        <tr>
                                            <th class ="text-center bg-complete text-white">Banco</th>
                                            <th class="text-center "><?php echo $banco ?></th>
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
            <!-- END PANEL -->
        </div>
            <!-- END PANEL -->      
    </div>
</div>


<script src="/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>

<script src="/assets/plugins/moment/moment.min.js"></script>
<script src="/assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="/assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>


