<style type="text/css">
    .input-group{
        width: 100%;
    }
</style>
<?php use \Carbon\Carbon; ?>
<?php $pagoProp = 0; ?>
<div class="col-xs-12">
    <h2> <?php echo $room->nameRoom." de (".$room->user->name ?>)</h2>
</div>
<div class="row">

    <div class="col-xs-12">
        <!-- START PANEL -->
        
        <div class="row">

            
            <div class="col-md-12 col-xs-12">
                <div class="col-md-12 col-xs-12">

                    <div class="col-md-7 col-xs-12">
                        
                        <h3 class="tex-center">Resumen de pagos</h3>
                        <?php $array = [0 =>"Tarjeta visa", 1 =>"Cash", 2 =>"Cash", 3=>"Banco", 4=>"Banco"] ?>
                            
                        <div class="row">
                            <table class="table table-hover " >
                                <thead >
                                    <th class ="text-center bg-complete text-white">Fecha</th>
                                    <th class ="text-center bg-complete text-white">Importe</th>
                                    <th class ="text-center bg-complete text-white">Metodo</th>
                                    <th class ="text-center bg-complete text-white">Concepto</th>
                                </thead>
                                <tbody>
                                <?php if (count($payments) > 0): ?>
                                    <?php foreach ($payments as $payment): ?>
                                        <tr id="payment-<?php echo $payment->id ?>">
                                            
                                            <td class="text-center">
                                                <?php $fecha = Carbon::createFromFormat('Y-m-d',$payment->date) ?>
                                                <?php echo $fecha->format('d-m-Y') ?>
                                            </td>
                                            <?php
                                                $divisor = 0;
                                                if(preg_match('/,/', $payment->PayFor)){
                                                    $aux = explode(',', $payment->PayFor);
                                                    for ($i = 0; $i < count($aux); $i++){
                                                        if ( !empty($aux[$i]) ){
                                                            $divisor ++;
                                                        }
                                                    }

                                                }else{
                                                    $divisor = 1;
                                                }
                                                $expense = $payment->import / $divisor;
                                            ?>
                                            <td class="text-center">
                                                <?php echo number_format($expense,2,',','.') ?>€
                                            </td>
                                            
                                            <td class="text-center">
                                                <?php if(isset($array[$payment->typePayment])) echo $array[$payment->typePayment]; else echo $payment->typePayment;?>
                                            </td>
                                            <td class="text-center">
                                                {{ $payment->concept }}
                                                @if (! empty($payment->comment))
                                                    <span data-toggle="tooltip" data-placement="top" title="{{ $payment->comment }}"><i class="fa fa-comment"></i></button>
                                                @endif
                                            </td>
                                        </tr>
                                        <?php $pagoProp += $expense; ?>
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

                                    <th class ="text-center bg-success text-white" ><?php echo number_format($pagoProp,2,',','.') ?>€</th>

                                    <th class ="text-center bg-danger text-white" style=""><?php echo number_format
                                        ($total - $pagoProp,2,',','.') ?>€</th>


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
                </div>
                
            </div>
        </div>
    </div>
    <div class="col-xs-12">
        <input type="hidden" name="room_id" value="{{$room->id}}"/>
        @include('backend.sales.gastos._formGastosApto')
    </div>
</div>
{{-- @TODO remove this, something is causing a conflict with the custom.js --}}
<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    
        $('.roomEspecifica').not('[data-idRoom="'+$('input[name="room_id"]').val()+'"]').hide();
        room_id_button = $('.roomEspecifica[data-idRoom="'+$('input[name="room_id"]').val()+'"]');
        room_id_button.click();
        room_id_button.unbind('click');
    });
</script>

