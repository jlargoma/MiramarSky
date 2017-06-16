@extends('layouts.admin-master')

@section('title') Administrador de reservas MiramarSKI @endsection

@section('externalScripts') 


@endsection
    
@section('content')
<?php use \Carbon\Carbon; ?>
<div class="container-fluid padding-10 sm-padding-10">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <h2>Actualizar reserva  de <?php echo "<b>".$book->customer->name."</b>" ?> creada el <?php echo $book->created_at ?></h2>
        </div>
        <div class="col-md-8">
            <div class="panel">
                <form role="form"  action="{{ url('reservas/saveUpdate') }}/<?php echo $book->id ?>" method="post" >
                            
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
                            <div class="col-md-4">
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
                                <div class="col-md-4">
                                    <label>Entrada</label>
                                    <div class="input-daterange input-group" id="datepicker-range">

                                        <input id="start" type="text" class="input-sm form-control" name="start" data-date-format="dd-mm-yyyy" value="<?php $start = Carbon::createFromFormat('Y-m-d',$book->start) ;echo $start->format('d/m/Y') ?>">
                                        <span class="input-group-addon">Hasta</span>
                                        <input id="finish" type="text" class="input-sm form-control" name="finish" data-date-format="dd-mm-yyyy" value="<?php $finish = Carbon::createFromFormat('Y-m-d',$book->finish) ;echo $finish->format('d/m/Y') ?>">
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <label>importe</label>
                                    <input type="text" class="form-control nigths" name="nigths" style="width: 100%" value="<?php echo $book->nigths ?>">
                                </div> 
                                <div class="col-md-1">
                                    <label>Pax</label>
                                    <input  type="text" class="form-control full-width pax" name="pax" style="width: 100%" value="<?php echo $book->pax ?>">
                                        
                                </div>
                                <div class="col-md-2">
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
                                <div class="col-md-2">
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
                                <div class="col-md-5">
                                    <label>Comentarios Usuario</label>
                                    <textarea class="form-control" name="comments" style="width: 100%" ><?php echo $book->comment ?>
                                    </textarea>
                                </div>
                                <div class="col-md-5">
                                    <label>Comentarios reserva</label>
                                    <textarea class="form-control" name="book_comments" style="width: 100%"><?php echo $book->book_comments ?> 
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
                                        <th class ="text-center bg-complete text-white" style="width:25%">fecha</th>
                                        <th class ="text-center bg-complete text-white" style="width:25%">importe</th>
                                        <th class ="text-center bg-complete text-white" style="width:25%">comentario</th>
                                        <th class ="text-center bg-complete text-white" style="width:25%">Tipo</th>                           
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
                                                    <input class="editable payment-<?php echo $payment->id?>" type="text" name="cost" data-id="<?php echo $payment->id ?>" value="<?php echo $payment->import ?>" style="width: 50%;text-align: center;border-style: none none solid">€
                                                </td>
                                                <td class ="text-center"><?php echo $payment->comment ?></td>
                                                <td class ="text-center"><?php echo $payment->getType($payment->type) ?> </td>
                                            </tr>
                                            <?php $total = $total + $payment->import ?>
                                        <?php endforeach ?>
                                        <?php if ($total < $book->total_price): ?>
                                            <tr>
                                                <td class ="text-center">
                                                    <div class="input-daterange input-group" id="datepicker-range">
                                                        <input type="text" class="input-sm form-control fecha-cobro" name="start" data-date-format="dd-mm-yyyy" value="<?php $hoy = Carbon::now() ;echo $hoy->format('d/m/Y') ?>">
                                                    </div>
                                                </td>
                                                <td class ="text-center">
                                                    <input class="importe" type="text" name="importe"  style="width: 100%;text-align: center;border-style: none none solid">
                                                </td>
                                                <td class ="text-center"> 
                                                    <input class="comment" type="text" name="comment"  style="width: 100%;text-align: center;border-style: none none solid">
                                                    </td>
                                            </tr>
                                        <?php else: ?>

                                        <?php endif ?>
                                    <?php else: ?>
                                        <tr>
                                            <td class ="text-center">
                                                <div class="input-daterange input-group" id="datepicker-range">
                                                    <input type="text" class="input-sm form-control fecha-cobro" name="start" data-date-format="dd-mm-yyyy" value="<?php $hoy = Carbon::now() ;echo $hoy->format('d/m/Y') ?>">
                                                </div>
                                            </td>
                                            <td class ="text-center">
                                                <input class="importe" type="text" name="importe"  style="width: 100%;text-align: center;border-style: none none solid">
                                            </td>
                                            <td class ="text-center"> 
                                                <input class="comment" type="text" name="comment"  style="width: 100%;text-align: center;border-style: none none solid">
                                                </td>
                                        </tr>
                                    <?php endif ?>
                                    <tr>
                                        <?php if ($total < $book->total_price): ?>
                                            <td class="text-center" colspan="2">Falta</td>
                                            <td class="text-center" ><?php echo $total-$book->total_price ?>€</td>
                                        <?php else: ?>
                                            <td class="text-center" colspan="2">Sobran</td>
                                            <td class="text-center" ><?php echo $total-$book->total_price ?>€</td>
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
                $.get('/admin/public/apartamentos/getPaxPerRooms/'+room).success(function( data ){
                    if (pax < data) {
                        $('.pax').attr('style' , 'background-color:red');
                    }else{
                        $('.pax').removeAttr('style');
                    }
                });
                $.get('/admin/public/reservas/getPricePark', {park: park, noches: diferencia}).success(function( data ) {
                    pricePark = data;
                    $.get('/admin/public/reservas/getPriceBook', {start: start, finish: finish, pax: pax, room: room, park: park}).success(function( data ) {
                        price = data;
                        price = (parseFloat(price) + parseFloat(pricePark));
                        $('.total').empty();
                        $('.total').val(price);
                            $.get('/admin/public/reservas/getCostPark', {park: park, noches: diferencia}).success(function( data ) {
                                costPark = data;
                                    $.get('/admin/public/reservas/getCostBook', {start: start, finish: finish, pax: pax, room: room, park: park}).success(function( data ) {
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
                $('.beneficio').val(beneficio);
            });
            
            $('.cobrar').click(function(event){ 
                var id = $(this).attr('data-id');
                var date = $('.fecha-cobro').val();
                var importe = $('.importe').val();
                var comment = $('.comment').val();
                $.get('/admin/public/pagos/create', {id: id, date: date, importe: importe, comment: comment}).success(function( data ) {
                    alert(data);
                    location.reload();
                });
            });

            $('.editable').change(function(event) {
                var id = $(this).attr('data-id');               
                var importe = $(this).val();
                console.log(id);
                $.get('/admin/public/pagos/update', {  id: id, importe: importe}, function(data) {
                    window.location.reload();
                });

            });
        });

    </script>
@endsection