@extends('layouts.admin-master')

@section('title') Administrador de reservas MiramarSKI @endsection

@section('externalScripts') 
<link rel="stylesheet" href="/assets/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="/assets/plugins/select2/js/select2.css">

@endsection

@section('content')
<?php use \Carbon\Carbon; ?>
<div class="container-fluid padding-10 sm-padding-10" style="background-color: rgba(0,0,255,0.1)">
    <div class="row">
        <div class="col-md-12 col-xs-12 text-center">
            <p style="font-size: 14px">

                Estado :   <select class="status form-control" data-id="<?php echo $book->id ?>" style="width: 50%;float: right;">
                                        <?php for ($i=1; $i < 9; $i++): ?> 
                                            <?php if ($i == $book->type_book): ?>
                                                <option selected value="<?php echo $i ?>"  data-id="<?php echo $book->id ?>"><?php echo $book->getStatus($i) ?></option>
                                            <?php else: ?>
                                                <option value="<?php echo $i ?>"><?php echo $book->getStatus($i) ?></option>
                                            <?php endif ?>                                          
                                             
                                        <?php endfor; ?>
                                    </select>
                <!-- Desplegable -->
            </p>
          
        </div>
        <hr>
        <div class="col-xs-12">
            <div class="panel">
                <form role="form"  action="{{ url('/admin/reservas/saveUpdate') }}/<?php echo $book->id ?>" method="post" >
                            
                    <!-- Seccion Cliente -->

                    <div class="panel-body" style="padding: 0px 0px 0px 0px;">
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                        <div class="input-group col-xs-12">
                            <input class="form-control" type="hidden"  name="customer_id" value="<?php echo $book->customer->id ?>">
                            <div class="col-xs-6">
                               <input class="form-control" type="text" name="name" value="<?php echo $book->customer->name ?>" disabled>
                            </div>
                            <div class="col-xs-4">
                                <input class="form-control" type="number" name="phone" value="<?php echo $book->customer->phone ?>" disabled> 
                            </div> 
                            <div class="col-xs-1">
                                <a href="tel:<?php echo $book->customer->phone ?>" class="text-right"><i class="fa fa-phone fa-2x"></i></a>
                            </div>
                            <br><br>
                            <div class="col-xs-12">
                                <input class="form-control" type="email" name="email" value="<?php echo $book->customer->email ?>" disabled>  
                            </div>
                             
                            <div style="clear: both;"></div>
                        </div>                                            
                    </div>

                    <!-- Seccion Reserva -->
                    <br>
                    <div class="panel-body" style="padding: 0px 0px 0px 0px;">
                        
                        

                            <div class="input-group col-md-12">
                                <div class="col-md-4">
                                    <div class="input-daterange input-group" id="datepicker-range">

                                        <input id="start" type="text" class="input-sm form-control" name="start" data-date-format="dd-mm-yyyy" value="<?php $start = Carbon::createFromFormat('Y-m-d',$book->start) ;echo $start->format('d/m/Y') ?>">
                                        <span class="input-group-addon">Hasta</span>
                                        <input id="finish" type="text" class="input-sm form-control" name="finish" data-date-format="dd-mm-yyyy" value="<?php $finish = Carbon::createFromFormat('Y-m-d',$book->finish) ;echo $finish->format('d/m/Y') ?>">
                                    </div>
                                </div>
                                <div class="col-xs-3">
                                    <label><i class="fa fa-moon-o"></i></label>
                                    <input type="text" class="nigths" name="nigths" style="width: 100%" value="<?php echo $book->nigths ?>" disabled style="border:none">
                                </div> 
                                <div class="col-xs-3">
                                    <label><i class="fa fa-user"></i></label>
                                    <input  type="text" class="form-control full-width pax" name="pax" style="width: 100%" value="<?php echo $book->pax ?>">
                                        
                                </div>
                                <div class="col-xs-6">
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
                                <div class="col-xs-5">
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
                                <div class="col-xs-5">
                                    <label><i class="fa fa-star"></i><i class="fa fa-star"></i></label>
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
                            </div>
                            <div class="input-group col-md-12">
                                <div class="col-xs-12">
                                    <label>Extras</label>
                                    <input type="text" class="form-control extra" name="extra" value="<?php echo $book->extra ?>">
                                </div>
                                <hr/>
                                <div class="col-xs-4">
                                    <label>Total</label>
                                    <input type="text" class="form-control total" name="total" value="<?php echo $book->total_price ?>" style="width: 100%">
                                </div> 
                                <div class="col-xs-4">
                                    <label>Coste</label>
                                    <input type="text" class="form-control cost" name="cost" value="<?php echo $book->cost_total ?>" disabled style="width: 100%">
                                </div>
                                <div class="col-xs-3">
                                    <label>Beneficio</label>
                                    <input type="text" class="form-control beneficio" name="beneficio" value="<?php echo $book->total_ben ?>" disabled style="width: 100%">
                                </div>
                            </div>
                            <br>
                                <div class="col-md-2 text-center" style="height: 100%">
                                    <input type="button" name="recalcular" class="recalcular btn btn-complete active" value="Recalcular">
                                    
                                </div>
                            <br>
                            <div class="input-group col-md-12">
                                <?php if ($book->comment == ""): ?>
                                <?php else: ?>
                                    <div class="col-xs-12">
                                        <label>Comentarios Usuario</label>
                                        <textarea class="form-control" name="comments" style="width: 100%" rows="4"><?php echo $book->comment ?>
                                        </textarea>
                                    </div>
                                <?php endif ?>
                                
                                <!-- Añadir boton para escribir comentario interno -->

                                <?php if ($book->book_comments == ""): ?>
                                <?php else: ?>
                                    <div class="col-xs-12">
                                        <label>Comentarios Interna</label>
                                        <textarea class="form-control" name="comments" style="width: 100%" rows="4"><?php echo $book->book_comments ?>
                                        </textarea>
                                    </div>
                                <?php endif ?>
                            </div> 
                            <div class="input-group col-md-12">
                                
                            </div> 
                            <br>
                            <div class="input-group col-xs-12 text-center">
                                <button class="form-control btn btn-complete active" type="submit" style="width: 90%;margin-left: 5%"><p style="font-size: 22px">Guardar</p></button>
                            </div>   
                            <br>                    
                    </div>
                </form> 
            </div>
        </div>
        <div class="col-xs-12">
            <div class="panel">
                <div>
                    <div class="panel-heading ">
                        <div class="col-xs-4 bg-success text-white text-center">
                            Total:<br>
                            <?php echo number_format($book->total_price,2,',','.') ?>
                        </div>
                        <div class="col-xs-4 bg-success text-white text-center">
                            Cobrado:<br>
                            <?php echo number_format($totalpayment,2,',','.') ?>
                        </div>
                        <div class="col-xs-4 bg-success text-white text-center">
                            Pendiente:<br>
                            <!-- si esta pendiente nada,.si esta de mas +X -->
                            <?php echo ($book->total_price-$totalpayment) >= 0 ? "-" : "+";echo number_format($book->total_price-$totalpayment,2,',','.') ?>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="col-md-12 table-responsive">
                            <table class="table table-hover dataTable no-footer" >
                                <thead>
                                    <tr>
                                        <th class ="text-center" >fecha</th>
                                        <th class ="text-center" >importe</th>
                                        <th class ="text-center" >Tipo</th>
                                        <th class ="text-center" >comentario</th>
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
                                                <input class="editable payment-<?php echo $payment->id?> form-control" type="text" name="cost" data-id="<?php echo $payment->id ?>" value="<?php echo $payment->import ?>" style="width: 50%;text-align: center;">€
                                                </td>
                                                <td class ="text-center"><?php echo $payment->comment ?></td>
                                                <td class ="text-center"><?php echo $typecobro->getTypeCobro($payment->type) ?> </td>
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
                                                    <input class="importe form-control" type="text" name="importe"   style="width: 100%;text-align: center;">
                                                </td>
                                                
                                                <td class="text-center">
                                                    <select class="full-width select2-hidden-accessible type_payment" data-init-plugin="select2" name="type_payment"  tabindex="-1" aria-hidden="true">
                                                        <?php for ($i=0; $i < 3 ; $i++): ?>
                                                           <option value="<?php echo $i ?>"><?php echo $book->getTypeCobro($i) ?></option>
                            
                                                        <?php endfor ;?>
                                                    </select>
                                                </td>
                                                <td class ="text-center"> 
                                                <input class="comment" type="text" name="comment"  style="width: 100%;text-align: center;">
                                                </td>
                                            </tr>
                                        <?php else: ?>

                                        <?php endif ?>
                                    <?php else: ?>
                                        <tr>
                                            <td class ="text-center" style="padding: 25px 0px 0px 0px;">
                                                <div class="input-daterange input-group" id="datepicker-range" style="width: 100%">
                                                    <input type="text" class="input-sm form-control fecha-cobro" name="start" data-date-format="dd-mm-yyyy" value="<?php $hoy = Carbon::now() ;echo $hoy->format('d/m/Y') ?>">
                                                </div>
                                            </td>
                                            <td class ="text-center">
                                            <input class="importe form-control" type="text" name="importe"  style="width: 100%;text-align: center;">
                                            </td>
                                            
                                            <td class="text-center">
                                                <select class="full-width select2-hidden-accessible type_payment" data-init-plugin="select2" name="type_payment"  tabindex="-1" aria-hidden="true">
                                                    <?php for ($i=0; $i < 3 ; $i++): ?>
                                                       <option value="<?php echo $i ?>"><?php echo $book->getTypeCobro($i) ?></option>
                                                
                                                    <?php endfor ;?>
                                                </select>
                                            </td>
                                            <td class ="text-center"> 
                                            <input class="comment" type="text" name="comment"  style="width: 100%;text-align: center;">
                                            </td>
                                        </tr>
                                    <?php endif ?>
                                    <!-- <tr>
                                        <?php if ($total < $book->total_price): ?>
                                            <td class="text-center" colspan="2">Falta</td>
                                            <td class="text-center" ><?php echo $total-$book->total_price ?>€</td>
                                        <?php elseif($total > $book->total_price): ?>
                                            <td class="text-center" colspan="2">Sobran</td>
                                            <td class="text-center" ><?php echo $total-$book->total_price ?>€</td>
                                        <?php else: ?>
                                            <td class="text-center" colspan="4">Al corriente de pago</td>
                                        <?php endif ?>
                                        
                                    </tr> -->
                                </tbody>
                            </table>
                            <div class="col-xs-12 text-center">
                                <input type="button" name="cobrar" class="cobrar form-control  btn btn-success active" value="Cobrar" data-id="<?php echo $book->id ?>">
                            </div>
                            
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

            $('.status,.room').change(function(event) {
                var id = $(this).attr('data-id');
                var clase = $(this).attr('class');
                
                if (clase == 'status form-control') {
                    var status = $(this).val();
                    var room = "";
                }else if(clase == 'room'){
                    var room = $(this).val();
                    var status = "";
                }
                $.get('/admin/reservas/changeBook/'+id, {status:status,room: room}, function(data) {
                    window.location.reload();
                });
            });


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
                $('.beneficio').val(beneficio);
            });
            
            $('.cobrar').click(function(event){ 
                var id = $(this).attr('data-id');
                var date = $('.fecha-cobro').val();
                var importe = $('.importe').val();
                var comment = $('.comment').val();
                var type = $('.type_payment').val();
                console.log(type);
                $.get('/admin/pagos/create', {id: id, date: date, importe: importe, comment: comment, type: type}).success(function( data ) {
                    alert(data);
                    window.location.reload();
                });
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