R@extends('layouts.admin-master')

@section('title') Administrador de reservas MiramarSKI @endsection

@section('externalScripts') 
<link href="/assets/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" media="screen" />
<link rel="stylesheet" href="/assets/plugins/select2/js/select2.css">

@endsection
    
@section('content')
<?php use \Carbon\Carbon; ?>
<div class="container-fluid padding-10 sm-padding-10">
    <div class="row">
        <div class="col-md-12 col-xs-12 ">
            <h2><?php echo "<b>".$book->customer->name."</b>" ?> 
                creada el 
                <?php 
                    $fecha = Carbon::createFromFormat('Y-m-d H:i:s' ,$book->created_at);
                    echo $fecha->format('d-m-Y'); 
                ?>
            </h2>
        </div>
        <div class="col-md-8">

            <div class="col-md-8">  
                <div class="panel">
                    <form role="form"  action="{{ url('/admin/reservas/saveUpdate') }}/<?php echo $book->id ?>" method="post" >
                                
                        <!-- Seccion Cliente -->
                        <div class="panel-heading">
                            <div class="panel-title">
                                Cliente
                            </div>
                        </div>
                    
                        <div class="panel-body">

                            <div class="input-group col-md-12">
                                <input class="form-control" type="hidden"  name="customer_id" value="<?php echo $book->customer->id ?>">
                                <div class="col-md-4">
                                    Nombre: <input class="form-control" type="text" name="name" value="<?php echo $book->customer->name ?>" disabled>
                                </div>
                                <div class="col-md-4">
                                    Email: <input class="form-control" type="email" name="email" value="<?php echo $book->customer->email ?>" disabled>  
                                </div>
                                <div class="col-md-2">
                                    Telefono: <input class="form-control" type="number" name="phone" value="<?php echo $book->customer->phone ?>" disabled> 
                                </div>  
                                <div style="clear: both;"></div>
                            </div>                                            
                        </div>

                        <!-- Seccion Reserva -->
                        <div class="panel-heading">
                            <div class="panel-title">
                                Reserva
                            </div>
                        </div>

                        <div class="panel-body">
                            
                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                                <div class="input-group col-md-12">
                                    <div class="col-md-5">
                                        <label>Entrada</label>
                                        <div class="input-daterange input-group" id="datepicker-range">

                                            <input id="start" type="text" class="input-sm form-control" name="start" data-date-format="dd-mm-yyyy" value="<?php $start = Carbon::createFromFormat('Y-m-d',$book->start) ;echo $start->format('d/m/Y') ?>">
                                            <span class="input-group-addon">Hasta</span>
                                            <input id="finish" type="text" class="input-sm form-control" name="finish" data-date-format="dd-mm-yyyy" value="<?php $finish = Carbon::createFromFormat('Y-m-d',$book->finish) ;echo $finish->format('d/m/Y') ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-1" style="width: 9%!important">
                                        <label>noches</label>
                                        <input type="text" class="form-control nigths" name="nigths" style="width: 100%" value="<?php echo $book->nigths ?>">
                                    </div> 
                                    <div class="col-md-1" style="width: 9%!important">
                                        <label>Pax</label>
                                        <input  type="text" class="form-control full-width pax" name="pax" style="width: 100%" value="<?php echo $book->pax ?>">
                                            
                                    </div>
                                    <div class="col-md-3">
                                        <label>Apartamento</label>
                                        <select class="form-control full-width newroom" data-init-plugin="select2" name="newroom" id="newroom">
                                            <?php foreach ($rooms as $room): ?>
                                                <?php if ($room->id == $book->room_id): ?>
                                                    <option value="<?php echo $room->id ?>" selected><?php echo $room->name ?></option>
                                                <?php else: ?>
                                                    <option value="<?php echo $room->id ?>"><?php echo $room->name ?></option>
                                                <?php endif ?>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                    <div class="col-md-1" style="width: 15%!important">
                                        <label>Park</label>
                                        <select class=" form-control full-width parking" data-init-plugin="select2" name="parking">
                                            <?php for ($i=1; $i <= 4 ; $i++): ?>
                                                <?php if ($i == $book->type_park): ?>
                                                    <option value="<?php echo $i ?>" selected><?php echo $book->getParking($i) ?></option>
                                                <?php else: ?>
                                                    <option value="<?php echo $i ?>"><?php echo $book->getParking($i) ?></option>
                                                <?php endif ?>
                                            <?php endfor;?>
                                        </select>
                                    </div>
                                    <?php if (count($payments) > 0): ?>
                                    
                                    <?php else: ?>
                                        <div class="col-md-2" style="height: 100%">
                                            <input type="button" name="recalcular" class="recalcular form-control btn btn-complete active" value="Recalcular">
                                            
                                        </div>
                                    <?php endif ?>
                                    
                                </div>
                                <div class="input-group col-md-12">
                                    <div class="col-md-3">
                                        <label>Extras</label>
                                        <input type="text" class="form-control extra" name="extra" value="<?php echo $book->extra ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Total</label>
                                        <input type="text" class="form-control total" name="total" value="<?php echo $book->total_price ?>" style="width: 100%">
                                    </div> 
                                    <div class="col-md-3">
                                        <label>Coste</label>
                                        <input type="text" class="form-control cost" name="cost" value="<?php echo $book->cost_total ?>" disabled style="width: 100%">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Beneficio</label>
                                        <input type="text" class="form-control beneficio" name="beneficio" value="<?php echo $book->total_ben ?>" disabled style="width: 100%">
                                    </div>
                                </div>
                                <br>
                                <div class="input-group col-md-12">
                                    <div class="col-md-6">
                                        <label>Comentarios Cliente</label>
                                        <textarea class="form-control" name="comments" style="width: 100%;height: 100px" ><?php echo $book->comment ?>
                                        </textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Comentarios Internos</label>
                                        <textarea class="form-control" name="book_comments" style="width: 100%;height: 100px"><?php echo $book->book_comments ?> 
                                        </textarea>
                                    </div>
                                </div> 
                                <div class="input-group col-md-12">
                                    
                                </div> 
                                <br>
                                <div class="input-group col-md-12">
                                    <button class="btn btn-complete" type="submit">Guardar</button>
                                </div>                        
                        </div>
                    
                    </form> 
                </div>
            </div>

            <div class="col-md-4">
                <div class="panel">
                    <div class="col-md-6 m-b-10">
                        <div class="widget-8 panel no-border bg-success no-margin widget-loader-bar" style="height: 100px!important">
                            <div class="container-xs-height full-height">
                                <div class="row-xs-height">
                                    <div class="col-xs-height col-top">
                                        <div class="panel-heading top-left top-right text-center">
                                            <div class="panel-title text-black hint-text ">
                                                <span class="font-montserrat fs-11 all-caps">
                                                    PVP
                                                </span>
                                            </div>                                    
                                        </div>
                                    </div>
                                </div>
                                <div class="row-xs-height ">
                                    <div class="col-xs-height col-top relative">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="p-l-20 text-center">
                                                    <h3 class="no-margin p-b-5 text-white">
                                                        <?php echo $book->total_price ?> €                                           
                                                    </h3>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 m-b-10">
                        <div class="widget-8 panel no-border bg-success no-margin widget-loader-bar" style="height: 100px!important">
                            <div class="container-xs-height full-height">
                                <div class="row-xs-height">
                                    <div class="col-xs-height col-top">
                                        <div class="panel-heading top-left top-right text-center">
                                            <div class="panel-title text-black hint-text ">
                                                <span class="font-montserrat fs-11 all-caps">
                                                    Coste
                                                </span>
                                            </div>                                    
                                        </div>
                                    </div>
                                </div>
                                <div class="row-xs-height ">
                                    <div class="col-xs-height col-top relative">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="p-l-20 text-center">
                                                    <h3 class="no-margin p-b-5 text-white">
                                                        <?php echo $book->cost_total ?> €                                          
                                                    </h3>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 m-b-10">
                        <div class="widget-8 panel no-border bg-success no-margin widget-loader-bar" style="height: 100px!important">
                            <div class="container-xs-height full-height">
                                <div class="row-xs-height">
                                    <div class="col-xs-height col-top">
                                        <div class="panel-heading top-left top-right text-center">
                                            <div class="panel-title text-black hint-text ">
                                                <span class="font-montserrat fs-11 all-caps">
                                                    Beneficio
                                                </span>
                                            </div>                                    
                                        </div>
                                    </div>
                                </div>
                                <div class="row-xs-height ">
                                    <div class="col-xs-height col-top relative">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="p-l-20 text-center">
                                                    <h3 class="no-margin p-b-5 text-white beneficio">
                                                        <?php echo $book->total_ben ?> €                                          
                                                    </h3>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>


        
        </div>
        <div class="col-md-4">
            <div class="panel">
                <div>
                    <div class="panel-heading">
                        <div class="panel-title col-md-12">Cobros
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="col-md-12">
                            <table class="table table-hover demo-table-search table-responsive-block" id="tableWithSearch" >
                                <thead>
                                    <tr>
                                        <th class ="text-center bg-complete text-white" style="width:35%">fecha</th>
                                        <th class ="text-center bg-complete text-white" style="width:30%">importe</th>
                                        <th class ="text-center bg-complete text-white" style="width:25%">Tipo</th>                           
                                        <th class ="text-center bg-complete text-white" style="width:15%">comentario</th>
                                    </tr>
                                </thead>
                                <tbody><?php $total = 0; ?>
                                    <?php if (count($payments)>0): ?>
                                        
                                        <?php foreach ($payments as $payment): ?>
                                            <tr>
                                                <td class ="text-center">
                                                    <?php 
                                                        $fecha = new Carbon($payment->datePayment);
                                                        echo $fecha->format('d-m-Y') 
                                                    ?>
                                                </td>
                                                <td class ="text-center">
                                                    <input class="editable payment-<?php echo $payment->id?>" type="text" name="cost" data-id="<?php echo $payment->id ?>" value="<?php echo $payment->import ?>" style="width: 50%;text-align: center;border-style: none none ">€
                                                </td>
                                                <td class ="text-center"><?php echo $typecobro->getTypeCobro($payment->type) ?> </td>

                                                <td class ="text-center"><?php echo $payment->comment ?></td>
                                            </tr>
                                            <?php $total = $total + $payment->import ?>
                                        <?php endforeach ?>
                                            <tr>
                                                <td class ="text-center">
                                                    <div class="input-daterange input-group" id="datepicker-range">
                                                        <input type="text" class="input-sm form-control fecha-cobro" name="start" data-date-format="dd-mm-yyyy" value="<?php $hoy = Carbon::now() ;echo $hoy->format('d/m/Y') ?>">
                                                    </div>
                                                </td>
                                                <td class ="text-center">
                                                    <input class="importe" type="text" name="importe"  style="width: 100%;text-align: center;border-style: none none ">
                                                </td>
                                                <td class="text-center">
                                                    <select class="full-width select2-hidden-accessible type_payment" data-init-plugin="select2" name="type_payment"  tabindex="-1" aria-hidden="true">
                                                        <?php for ($i=0; $i < 3 ; $i++): ?>
                                                            <?php if (Auth::user()->id == 39 && $i == 2): ?>
                                                                <option value="<?php echo $i ?>" selected><?php echo $book->getTypeCobro($i) ?></option>
                                                            <?php elseif (Auth::user()->id == 28 && $i == 1):?>
                                                                <option value="<?php echo $i ?>" selected><?php echo $book->getTypeCobro($i) ?></option>
                                                            <?php else: ?>
                                                                <option value="<?php echo $i ?>"><?php echo $book->getTypeCobro($i) ?></option>
                                                            <?php endif ?>
                                                           
                                                
                                                        <?php endfor ;?>
                                                    </select>
                                                </td>
                                                <td class ="text-center"> 
                                                    <input class="comment" type="text" name="comment"  style="width: 100%;text-align: center;border-style: none">
                                                </td>
                                                
                                            </tr>
                                    <?php else: ?>
                                        <tr>
                                            <td class ="text-center">
                                                <div class="input-daterange input-group" id="datepicker-range">
                                                    <input type="text" class="input-sm form-control fecha-cobro" name="start" data-date-format="dd-mm-yyyy" value="<?php $hoy = Carbon::now() ;echo $hoy->format('d/m/Y') ?>">
                                                </div>
                                            </td>
                                            <td class ="text-center">
                                                <input class="importe" type="text" name="importe"  style="width: 100%;text-align: center;border-style: none">
                                            </td>
                                            <td class="text-center">
                                                <select class="full-width select2-hidden-accessible type_payment" data-init-plugin="select2" name="type_payment"  tabindex="-1" aria-hidden="true">
                                                    <?php for ($i=0; $i < 3 ; $i++): ?>
                                                       <option value="<?php echo $i ?>"><?php echo $book->getTypeCobro($i) ?></option>
                                                
                                                    <?php endfor ;?>
                                                </select>
                                            </td>
                                            <td class ="text-center"> 
                                                <input class="comment" type="text" name="comment"  style="width: 100%;text-align: center;border-style: none">
                                            </td>
                                            
                                        </tr>
                                    <?php endif ?>
                                    <tr>
                                        <?php if ($total < $book->total_price): ?>
                                            <td class="text-center" colspan="3">Pendiente de pago</td>
                                            <td class="text-center" ><?php echo $total-$book->total_price ?>€</td>
                                        <?php elseif($total > $book->total_price): ?>
                                            <td class="text-center" colspan="3">Sobrante</td>
                                            <td class="text-center" ><?php echo $total-$book->total_price ?>€</td>
                                        <?php else: ?>
                                            <td class="text-center" colspan="4">Al corriente de pago</td>
                                        <?php endif ?>
                                        
                                    </tr>
                                </tbody>
                            </table>

                            <input type="button" name="cobrar" class="cobrar form-control btn btn-complete active" value="Cobrar" data-id="<?php echo $book->id ?>">
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="/assets/plugins/select2/select2.full.min.js"></script>
<script type="text/javascript">
        $(document).ready(function() {          

            var start  = 0;
            var finish = 0;
            var diferencia = 0;
            var price = 0;
            var cost = 0;


            $('#start').change(function(event) {
                start= $(this).val();
                var info = start.split('/');
                start = info[1] + '/' + info[0] + '/' + info[2];
                if (finish != 0) {
                    diferencia = Math.floor((  Date.parse(finish)- Date.parse(start) ) / 86400000);
                    $('.nigths').empty();
                    $('.nigths').html(diferencia);
                }
            });

            $('#finish').change(function(event) {
                finish= $(this).val();
                var info = finish.split('/');
                finish = info[1] + '/' + info[0] + '/' + info[2];           
                if (start != 0) {
                    diferencia = Math.floor((  Date.parse(finish)- Date.parse(start) ) / 86400000);
                    $('.nigths').empty();
                    $('.nigths').val(diferencia);
                }
            });

            $('.recalcular').click(function(event){ 

                var room = $('#newroom').val();
                var pax = $('.pax').val();
                var park = $('.parking').val();
                var beneficio = 0;
                var costPark = 0;
                var pricePark = 0;
                var diferencia = 0;
                start= $('#start').val();
                var info = start.split('/');
                start = info[1] + '/' + info[0] + '/' + info[2];

                finish= $('#finish').val();
                var info = finish.split('/');
                finish = info[1] + '/' + info[0] + '/' + info[2]; 

                diferencia = Math.floor((  Date.parse(finish)- Date.parse(start) ) / 86400000);       
                $.get('/admin/apartamentos/getPaxPerRooms/'+room).success(function( data ){
                    if (pax < data) {
                        $('.pax').attr('style' , 'background-color:red');
                    }else{
                        $('.pax').removeAttr('style');
                    }
                });
                $.get('/admin/reservas/getPricePark', {park: park, noches: diferencia}).success(function( data ) {
                    pricePark = data;
                    $.get('/admin/reservas/getPriceBook', {start: start, finish: finish, pax: pax, room: room, park: park}).success(function( data ) {
                        price = data;
                        price = (parseFloat(price) + parseFloat(pricePark));
                        $('.total').empty();
                        $('.total').val(price);
                            $.get('/admin/reservas/getCostPark', {park: park, noches: diferencia}).success(function( data ) {
                                costPark = data;
                                    $.get('/admin/reservas/getCostBook', {start: start, finish: finish, pax: pax, room: room, park: park}).success(function( data ) {
                                        cost = data;
                                        cost = (parseFloat(cost) + parseFloat(costPark));
                                        $('.cost').empty();
                                        $('.cost').val(cost);
                                        beneficio = price - cost;
                                        $('.beneficio').empty;
                                        $('.beneficio').val(beneficio);
                                    });
                            });
                    });
                });
                
            });

            $('.total').change(function(event) {
                var price = $(this).val();
                var cost = $('.cost').val();
                var beneficio = (parseFloat(price) - parseFloat(cost));
                console.log(beneficio);
                $('.beneficio').empty;
                $('.beneficio').text(beneficio+"€");
            });
            
            $('.cobrar').click(function(event){ 
                var id = $(this).attr('data-id');
                var date = $('.fecha-cobro').val();
                var importe = $('.importe').val();
                var comment = $('.comment').val();
                var type = $('.type_payment').val();
                console.log(type);
                if (importe == 0) {
                   
                }else{
                    $.get('/admin/pagos/create', {id: id, date: date, importe: importe, comment: comment, type: type}).success(function( data ) {
                        alert(data);
                        window.location.reload();
                    });
                }
                
            });

            $('.editable').change(function(event) {
                var id = $(this).attr('data-id');               
                var importe = $(this).val();
                console.log(id);
                $.get('/admin/pagos/update', {  id: id, importe: importe}, function(data) {
                    window.location.reload();
                });

            });
        });

    </script>
@endsection