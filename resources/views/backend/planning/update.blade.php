@extends('layouts.admin-master')

@section('title') Administrador de reservas MiramarSKI @endsection

@section('externalScripts') 
<link href="/assets/plugins/jquery-datatable/media/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="/assets/plugins/jquery-datatable/extensions/FixedColumns/css/dataTables.fixedColumns.min.css" rel="stylesheet" type="text/css" />
<link href="/assets/plugins/datatables-responsive/css/datatables.responsive.css" rel="stylesheet" type="text/css" media="screen" />

<link href="/assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" type="text/css" media="screen">
<link href="/assets/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" media="screen">
<link href="/assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" media="screen">

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.js"></script>

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
        <div class="col-md-7">

            <div class="col-md-9">  
                <div class="panel">
                    <form role="form"  action="{{ url('/admin/reservas/saveUpdate') }}/<?php echo $book->id ?>" method="post" >
                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
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
                                    Nombre: <input class="form-control cliente" type="text" name="name" value="<?php echo $book->customer->name ?>" data-id="<?php echo $book->customer->id ?>">
                                </div>
                                <div class="col-md-4">
                                    Email: <input class="form-control cliente" type="email" name="email" value="<?php echo $book->customer->email ?>" data-id="<?php echo $book->customer->id ?>">  
                                </div>
                                <div class="col-md-2">
                                    Telefono: <input class="form-control cliente" type="number" name="phone" value="<?php echo $book->customer->phone ?>" data-id="<?php echo $book->customer->id ?>"> 
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
                                <div class="input-group col-md-12">
                                    <div class="col-md-4">
                                        <label>Entrada</label>
                                        <div class="input-prepend input-group">
                                          <span class="add-on input-group-addon"><i
                                                        class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                                          <input type="text" style="width: 100%" name="reservation" id="daterangepicker" class="form-control" value="<?php echo Carbon::CreateFromFormat('Y-m-d',$book->start)->format('d/m/Y').'- '.Carbon::CreateFromFormat('Y-m-d',$book->finish)->format('d/m/Y') ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Noches</label>
                                        <input type="text" class="form-control nigths" name="nigths" value="<?php echo $book->nigths ?>" style="width: 100%">
                                    </div> 
                                    <div class="col-md-2">
                                        <label>Pax</label>
                                        <input  type="text" class="form-control full-width pax" name="pax" style="width: 100%" value="<?php echo $book->pax ?>">
                                            
                                    </div>
                                    <div class="col-md-2">
                                        <label>Apartamento</label>
                                        <select class="form-control full-width newroom" data-init-plugin="select2" name="newroom" id="newroom">
                                            <?php foreach ($rooms as $room): ?>
                                                <option value="<?php echo $room->id ?>" {{ $room->id == $book->room_id ? 'selected' : '' }}><?php echo $room->name ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Parking</label>
                                        <select class=" form-control parking"  name="parking">
                                            <?php for ($i=1; $i <= 4 ; $i++): ?>
                                                <option value="<?php echo $i ?>" {{ $book->type_park == $i ? 'selected' : '' }}><?php echo $book->getParking($i) ?></option>
                                            <?php endfor;?>
                                        </select>
                                    </div>
                                    
                                </div>
                                <div class="input-group col-md-12">
                                    <div class="col-md-2">
                                        <label>Extras</label>
                                        <select class="full-width select2-hidden-accessible" data-init-plugin="select2" multiple="" name="extras[]" tabindex="-1" aria-hidden="true">
                                            <?php foreach ($extras as $extra): ?>
                                                <option value="<?php echo $extra->id ?>"><?php echo $extra->name ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2">                                                        
                                        <label>Cost Agencia</label>
                                        <input type="text" class="agencia form-control" name="agencia" value="<?php echo $book->PVPAgencia ?>">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Agencia</label>
                                        <select class=" form-control full-width agency" data-init-plugin="select2" name="agency">
                                            <?php for ($i=0; $i <= 2 ; $i++): ?>
                                                    <option value="<?php echo $i ?>" {{ $book->agency == $i ? 'selected' : '' }}><?php echo $book->getAgency($i) ?></option>
                                            <?php endfor;?>
                                        </select>
                                    </div>
                                    
                                    
                                    <div class="col-md-2">
                                        <label>Sup. Lujo</label>
                                        <select class=" form-control full-width type_luxury" data-init-plugin="select2" name="type_luxury">
                                            <?php for ($i=1; $i <= 4 ; $i++): ?>
                                                <option value="<?php echo $i ?>" {{ $book->type_luxury == $i ? 'selected' : '' }}><?php echo $book->getSupLujo($i) ?></option>
                                            <?php endfor;?>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="button" class="recalcular btn btn-primary" value="recalcular" style="margin-top: 25px;">
                                    </div>
                                </div>
                                <br>
                                <div class="input-group col-md-12">
                                    <div class="col-md-6">
                                        <label>Comentarios Usuario</label>
                                        <textarea class="form-control" name="comments" rows="5" style="width: 80%">
                                        </textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Comentarios reserva</label>
                                        <textarea class="form-control book_comments" name="book_comments" rows="5" style="width: 80%">
                                        </textarea>
                                    </div>
                                </div> 
                                <div class="input-group col-md-12">
                                    
                                </div> 
                                <br>
                                <div class="input-group col-md-12 text-center">
                                    <button class="btn btn-complete" type="submit" style="width: 50%;min-height: 50px">Guardar</button>
                                </div>                        
                        </div>
                        
                        

                    
                </div>
            </div>

            <div class="col-md-3">
                <div class="col-md-12" style="padding: 0px">
                    <div class="panel-heading">
                        <div class="panel-title col-md-12">Cotizacion
                        </div>
                    </div>
                    <table>
                        <tbody>
                            <tr class="text-white" style="background-color: #0c685f">
                                <th style="padding-left: 5px">PVP</th>
                                <th style="padding-right: 5px;padding-left: 5px">
                                    <input type="text" class="form-control total m-t-10 m-b-10 text-white" name="total" value="<?php echo $book->total_price ?>" style="width: 100%;background-color: #0c685f;border:none;font-weight: bold">
                                </th>
                            </tr>
                            <tr class=" text-white m-t-5" style="background-color: #99D9EA">
                                <th style="padding-left: 5px">COSTE</th>
                                <th style="padding-right: 5px;padding-left: 5px">
                                    <input type="text" class="form-control cost m-t-10 m-b-10 text-white" name="cost" value="<?php echo $book->cost_total ?>" style="width: 100%;color: black;background: #99D9EA;border:none;font-weight: bold">
                                </th>
                            </tr>
                            <tr class="text-white m-t-5" style="background-color: #ff7f27">
                                <th style="padding-left: 5px">BENº</th>
                                <th style="padding-right: 5px;padding-left: 5px">
                                    <div class="col-md-7 p-r-0 p-l-0">
                                        <input type="text" class="form-control beneficio m-t-10 m-b-10 text-white" name="beneficio" value="<?php echo $book->total_ben ?>" style="width: 100%;color: black;background: #ff7f27;border:none;font-weight: bold">
                                    </div>
                                    <div class="col-md-2 m-t-5"><div class="m-t-10 m-l-10 beneficio-text">0%</div></div>
                                    
                                </th>
                                
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            </form> 


        
        </div>
        <div class="col-md-5">
            <div class="panel">
                <div>
                    <div class="panel-heading">
                        <div class="panel-title col-md-12">Cobros
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="col-md-12 text-center">
                            <table class="table table-hover demo-table-search table-responsive-block" >
                                <thead>
                                    <tr>
                                        <th class ="text-center bg-success text-white" style="width:25%">fecha</th>
                                        <th class ="text-center bg-success text-white" style="width:25%">importe</th>
                                        <th class ="text-center bg-success text-white" style="width:30%">Tipo</th>                           
                                        <th class ="text-center bg-success text-white" style="width:20%">comentario</th>
                                        <th class ="text-center bg-success text-white" style="width:20%">Eliminar</th>

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
                                                <td>
                                                    <a href="{{ url('/admin/reservas/deleteCobro/')}}/<?php echo $payment->id ?>" class="btn btn-tag btn-danger" type="button" data-toggle="tooltip" title="" data-original-title="Eliminar Cobro" onclick="return confirm('¿Quieres Eliminar el obro?');">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                </td>
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
                                                        <?php for ($i=0; $i < 4 ; $i++): ?>
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
                                                <td>
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
                                        <td></td>
                                        <?php if ($total < $book->total_price): ?>
                                            <td class="text-center" ><p style="color:red;font-weight: bold;"><?php echo $total-$book->total_price ?>€</p></td>
                                            <td class="text-center" colspan="2">Pendiente de pago</td>
                                        <?php elseif($total > $book->total_price): ?>
                                            <td class="text-center" ><?php echo $total-$book->total_price ?>€</td>
                                            <td class="text-center" colspan="2">Sobrante</td>
                                        <?php else: ?>
                                            <td class="text-center" colspan="4">Al corriente de pago</td>
                                        <?php endif ?>
                                        
                                    </tr>
                                </tbody>
                            </table>
                            <input type="button" name="cobrar" class="btn btn-success  m-t-10 cobrar" value="Cobrar" data-id="<?php echo $book->id ?>" style="width: 50%;min-height: 50px">                            
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="/assets/plugins/jquery-datatable/media/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="/assets/plugins/jquery-datatable/extensions/TableTools/js/dataTables.tableTools.min.js" type="text/javascript"></script>
<script src="/assets/plugins/jquery-datatable/media/js/dataTables.bootstrap.js" type="text/javascript"></script>
<script src="/assets/plugins/jquery-datatable/extensions/Bootstrap/jquery-datatable-bootstrap.js" type="text/javascript"></script>
<script type="text/javascript" src="/assets/plugins/datatables-responsive/js/datatables.responsive.js"></script>
<script type="text/javascript" src="/assets/plugins/datatables-responsive/js/lodash.min.js"></script>

<script src="/assets/plugins/bootstrap3-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<script type="text/javascript" src="/assets/plugins/jquery-autonumeric/autoNumeric.js"></script>
<script type="text/javascript" src="/assets/plugins/dropzone/dropzone.min.js"></script>
<script type="text/javascript" src="/assets/plugins/bootstrap-tag/bootstrap-tagsinput.min.js"></script>
<script type="text/javascript" src="/assets/plugins/jquery-inputmask/jquery.inputmask.min.js"></script>
<script src="/assets/plugins/bootstrap-form-wizard/js/jquery.bootstrap.wizard.min.js" type="text/javascript"></script>
<script src="/assets/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="/assets/plugins/summernote/js/summernote.min.js" type="text/javascript"></script>
<script src="/assets/plugins/moment/moment.min.js"></script>
<script src="/assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="/assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
<script src="/assets/plugins/bootstrap-typehead/typeahead.bundle.min.js"></script>
<script src="/assets/plugins/bootstrap-typehead/typeahead.jquery.min.js"></script>
<script src="/assets/plugins/handlebars/handlebars-v4.0.5.js"></script>
<script type="text/javascript">
        $(document).ready(function() {          

            var start  = 0;
            var finish = 0;
            var diferencia = 0;
            var price = 0;
            var cost = 0;

            $('.pax').click(function(event) {
                var fechas = $('#daterangepicker').val();
                var info = fechas.split('-');
                var inicio = info[0];
                var final = info[1];
                console.log(inicio);
                var start = new Date(inicio.substring(3,5) + '/' + inicio.substring(0,2) + '/' + inicio.substring(6,10));
                var finish = new Date(final.substring(4,6)+ '/' +  final.substring(1,3)+ '/' + final.substring(7,11));
                var timeDiff = Math.abs(finish.getTime() - start.getTime());
                var noches = Math.ceil(timeDiff / (1000 * 3600 * 24)); 
                $('.nigths').val(noches);

            });

            $('.recalcular').click(function(event){ 

                var room = $('#newroom').val();
                var pax = $('.pax').val();
                var park = $('.parking').val();
                var lujo = $('.type_luxury').val();
                var beneficio = 0;
                var costPark = 0;
                var pricePark = 0;
                var diferencia = 0;

                var fechas = $('#daterangepicker').val();
                var info = fechas.split('-');
                var inicio = info[0];
                var final = info[1];
                console.log(inicio);
                var start = new Date(inicio.substring(3,5) + '/' + inicio.substring(0,2) + '/' + inicio.substring(6,10));
                var finish = new Date(final.substring(4,6)+ '/' +  final.substring(1,3)+ '/' + final.substring(7,11));
                var timeDiff = Math.abs(finish.getTime() - start.getTime());
                var noches = Math.ceil(timeDiff / (1000 * 3600 * 24)); 
                start = inicio.substring(3,5) + '/' + inicio.substring(0,2) + '/' + inicio.substring(6,10);
                finish = final.substring(4,6)+ '/' +  final.substring(1,3)+ '/' + final.substring(7,11); 

                $.get('/admin/apartamentos/getPaxPerRooms/'+room).success(function( data ){
                    if (pax < data) {
                        $('.pax').attr('style' , 'background-color:red');
                    }else{
                        $('.pax').removeAttr('style');
                    }
                });
                $.get('/admin/reservas/getPricePark', {park: park, noches: noches}).success(function( data ) {
                    pricePark = data;
                    $.get('/admin/reservas/getPriceLujoAdmin', {lujo: lujo}).success(function( data ) {
                        priceLujo = data;

                        $.get('/admin/reservas/getPriceBook', {start: start, finish: finish, pax: pax, room: room, park: park}).success(function( data ) {
                            price = data;
                            
                            price = (parseFloat(price) + parseFloat(pricePark) + parseFloat(priceLujo));
                            $('.total').empty();
                            $('.total').val(price);
                                $.get('/admin/reservas/getCostPark', {park: park, noches: noches}).success(function( data ) {
                                    costPark = data;
                                    $.get('/admin/reservas/getCostLujoAdmin', {lujo: lujo}).success(function( data ) {
                                        costLujo = data;
                                        $.get('/admin/reservas/getCostBook', {start: start, finish: finish, pax: pax, room: room, park: park}).success(function( data ) {
                                            cost = data;
                                            agencia = $('.agencia').val();
                                            if (agencia == "") {
                                                agencia = 0;
                                            }
                                            cost = (parseFloat(cost) + parseFloat(costPark) + parseFloat(agencia) + parseFloat(costLujo));
                                            $('.cost').empty();
                                            $('.cost').val(cost);
                                            beneficio = price - cost;
                                            $('.beneficio').empty;
                                            $('.beneficio').val(beneficio);
                                            beneficio_ = (beneficio / price)*100
                                            $('.beneficio-text').empty;
                                            $('.beneficio-text').html(beneficio_.toFixed(0)+"%")

                                        });
                                    });
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
                if (importe == 0) {
                   
                }else{
                    $.get('/admin/pagos/create', {id: id, date: date, importe: importe, comment: comment, type: type}).success(function( data ) {
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

            $('.cliente').change(function(event) {
                var id = $(this).attr('data-id');;
                var name = $('[name=name]').val();
                var email = $('[name=email]').val();
                var phone = $('[name=phone]').val();
                $.get('/admin/clientes/save', { id: id,  name: name, email: email,phone: phone}, function(data) {
                });
            });
        });

    </script>
@endsection