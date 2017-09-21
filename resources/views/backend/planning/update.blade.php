@extends('layouts.admin-master')

@section('title') Administrador de reservas MiramarSKI @endsection

@section('externalScripts') 
<link href="/assets/plugins/jquery-datatable/media/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="/assets/plugins/datatables-responsive/css/datatables.responsive.css" rel="stylesheet" type="text/css" media="screen" />
<link href="/assets/css/font-icons.css" rel="stylesheet" type="text/css" />

<link href="/assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" type="text/css" media="screen">

<link rel="stylesheet" href="{{ asset('/frontend/css/components/daterangepicker.css')}}" type="text/css" />
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.js"></script>

@endsection

@section('content')
<?php use \Carbon\Carbon; ?>
<div class="container-fluid padding-10 sm-padding-10">
 <div class="row">
     <div class="col-md-12 col-xs-12 center text-center push-30">
        <div class="col-md-6 col-md-offset-3">
            <h4>
                <?php echo "<b>".strtoupper($book->customer->name)."</b>" ?> creada el 
                <?php $fecha = Carbon::createFromFormat('Y-m-d H:i:s' ,$book->created_at);?>
                <?php echo $fecha->copy()->format('d-m-Y')." Hora:".$fecha->copy()->format('H:m')?>
            </h4>   
            <hr style="color: black;">
            <h5>Creado por <?php echo "<b>".strtoupper($book->user->name)."</b>" ?></h5>
        </div>
    </div>
    <div class="col-md-12 col-xs-12 center text-center">
        <div class="col-md-6 col-xs-12">
            <!-- DATOS DE LA RESERVA -->
            <div class="row">
                <form role="form"  action="{{ url('/admin/reservas/saveUpdate') }}/<?php echo $book->id ?>" method="post" >
                    <!-- DATOS DEL CLIENTE -->
                    <div class="col-xs-12 push-40 bg-white padding-block">
                        <div class="col-xs-12 bg-black push-20">
                            <h4 class="text-center white">
                                DATOS DEL CLIENTE
                            </h4>
                        </div>

                        <div class="col-md-4">
                            <label for="name">Nombre</label> 
                            <input class="form-control cliente" type="text" name="name" value="<?php echo $book->customer->name ?>" data-id="<?php echo $book->customer->id ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="email">Email</label> 
                            <input class="form-control cliente" type="email" name="email" value="<?php echo $book->customer->email ?>" data-id="<?php echo $book->customer->id ?>">  
                        </div>
                        <div class="col-md-4">
                            <label for="phone">Telefono</label> 
                            <input class="form-control cliente" type="text" name="phone" value="<?php echo $book->customer->phone ?>" data-id="<?php echo $book->customer->id ?>"> 
                        </div>  
                    </div>
                    <!-- DATOS DE LA RESERVA -->
                    <div class="col-xs-12 bg-white padding-block">
                        <div class="col-xs-12 bg-black push-20">
                            <h4 class="text-center white">
                                DATOS DE LA RESERVA
                            </h4>
                        </div>
                        <div class="col-md-4">
                            <label>Entrada</label>
                            <div class="input-prepend input-group">
                              <input type="text" class="form-control daterange1" id="fechas" name="fechas" required="" style="cursor: pointer; text-align: center; backface-visibility: hidden;min-height: 28px;" readonly="">
                          </div>
                      </div>
                      <div class="col-md-1 p-l-0">
                        <label>Noches</label>
                        <input type="text" class="form-control nigths" name="nigths" value="<?php echo $book->nigths ?>" style="width: 100%">
                    </div> 
                    <div class="col-md-1 p-l-0">
                        <label>Pax</label>
                        <input  type="text" class="form-control full-width pax" name="pax" style="width: 100%" value="<?php echo $book->pax ?>">
                        
                    </div>
                    <div class="col-md-3">
                        <label>Apartamento</label>
                        <select class="form-control full-width newroom" data-init-plugin="select2" name="newroom" id="newroom">
                            <?php foreach ($rooms as $room): ?>
                                <option value="<?php echo $room->id ?>" {{ $room->id == $book->room_id ? 'selected' : '' }}><?php echo $room->name ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="col-md-1 p-l-0 p-r-0">
                        <label>Parking</label>
                        <select class=" form-control parking minimal"  name="parking">
                            <?php for ($i=1; $i <= 4 ; $i++): ?>
                                <option value="<?php echo $i ?>" {{ $book->type_park == $i ? 'selected' : '' }}><?php echo $book->getParking($i) ?></option>
                            <?php endfor;?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Sup. Lujo</label>
                        <select class=" form-control full-width type_luxury minimal" data-init-plugin="select" name="type_luxury">
                            <?php for ($i=1; $i <= 4 ; $i++): ?>
                                <option value="<?php echo $i ?>" {{ $book->type_luxury == $i ? 'selected' : '' }}><?php echo $book->getSupLujo($i) ?></option>
                            <?php endfor;?>
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 bg-white padding-block">
                    <div class="col-md-3 col-xs-12">                                                        
                        <label>Cost Agencia</label>
                        <input type="text" class="agencia form-control" name="agencia" value="<?php echo $book->PVPAgencia ?>">
                    </div>
                    <div class="col-md-3 col-xs-12">
                        <label>Agencia</label>
                        <select class="form-control full-width agency minimal" data-init-plugin="select2" name="agency">
                            <?php for ($i=0; $i <= 2 ; $i++): ?>
                                <option value="<?php echo $i ?>" {{ $book->agency == $i ? 'selected' : '' }}><?php echo $book->getAgency($i) ?></option>
                            <?php endfor;?>
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 bg-white padding-block">
                    <div class="col-md-6 col-xs-12">
                        <label>Comentarios Cliente </label>
                        <textarea class="form-control" name="comments" rows="5" >
                        </textarea>
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <label>Comentarios Internos</label>
                        <textarea class="form-control book_comments" name="book_comments" rows="5" >
                        </textarea>
                    </div>
                </div>
                <div class="row push-40 bg-white padding-block">
                    <div class="col-md-4 col-md-offset-4 text-center">
                        <button class="btn btn-complete font-s24 font-w400 padding-block" type="submit" style="min-height: 50px;width: 100%;">Guardar</button>
                    </div>  
                </div>
            </form>
        </div>
        
    </div>
    <div class="col-md-6 col-xs-12 padding-block">
        <!-- COBROS -->
        <div class="col-md-4 col-xs-12">
            <div class="col-xs-12 bg-black push-20">
                <h4 class="text-center white">
                    DATOS DE LA RESERVA
                </h4>
            </div>
            <div class="col-xs-12">
                <select class="status form-control minimal" data-id="<?php echo $book->id ?>" >

                    <?php for ($i=1; $i < 9; $i++): ?> 
                        <option <?php echo $i == ($book->type_book) ? "selected" : ""; ?> 
                            <?php echo ($i  == 1 || $i == 5) ? "style='font-weight:bold'" : "" ?>
                            value="<?php echo $i ?>"  data-id="<?php echo $book->id ?>">
                            <?php echo $book->getStatus($i) ?></option>                                    
                         
                    <?php endfor; ?>
                </select>
            </div>
        </div>
        <div class="col-md-8">
            <div class="col-xs-12 bg-black">
                <h4 class="text-center white">
                    COTIZACIÓN
                </h4>
            </div>
            <div class="col-md-4 col-xs-12 text-center" style="background-color: #0c685f;">
                <label class="font-w800 text-white" for="">TOTAL</label>
                <input type="text" class="form-control total m-t-10 m-b-10 white" name="total" value="<?php echo $book->total_price ?>">
            </div>
            <div class="col-md-4 col-xs-12 text-center" style="background: #99D9EA;">
                <label class="font-w800 text-white" for="">COSTE</label>
                <input type="text" class="form-control cost m-t-10 m-b-10 white" name="cost" value="<?php echo $book->cost_total ?>">
            </div>
            <div class="col-md-4 col-xs-12 text-center not-padding" style="background: #ff7f27;">
                <label class="font-w800 text-white" for="">BENEFICIO</label>
                <input type="text" class="form-control text-left beneficio m-t-10 m-b-10 white" name="beneficio" value="<?php echo $book->total_ben ?>" style="width: 80%; float: left;">
                <div class="beneficio-text font-w400 font-s18 white" style="width: 20%; float: left;padding: 25px 0; padding-right: 5px;"><?php echo number_format($book->inc_percent,0)."%" ?></div>
            </div>
        </div>

        
        <div class="col-md-12 padding-block">
            <div class="col-xs-12 bg-black push-0">
                <h4 class="text-center white">
                    COBROS
                </h4>
            </div>
            <table class="table table-hover demo-table-search table-responsive-block" style="margin-top: 0;">
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
                                <td class ="text-center p-t-25">
                                    <?php 
                                        $fecha = new Carbon($payment->datePayment);
                                        echo $fecha->format('d-m-Y') 
                                    ?>
                                </td>
                                <td class ="text-center">
                                    <input class="editable payment-<?php echo $payment->id?> m-t-5" type="text" name="cost" data-id="<?php echo $payment->id ?>" value="<?php echo $payment->import ?>" style="width: 50%;text-align: center;border-style: none none ">€
                                </td>
                                <td class ="text-center p-t-25"><?php echo $typecobro->getTypeCobro($payment->type) ?> </td>

                                <td class ="text-center p-t-25"><?php echo $payment->comment ?></td>
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
                                    <input class="importe m-t-5" type="text" name="importe"  style="width: 100%;text-align: center;border-style: none none ">
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
                                <input class="importe m-t-5" type="text" name="importe"  style="width: 100%;text-align: center;border-style: none">
                            </td>
                            <td class="text-center">
                                <select class="full-width select2-hidden-accessible type_payment" data-init-plugin="select2" name="type_payment"  tabindex="-1" aria-hidden="true">
                                    <?php for ($i=0; $i < 3 ; $i++): ?>
                                       <option value="<?php echo $i ?>"><?php echo $book->getTypeCobro($i) ?></option>
                                
                                    <?php endfor ;?>
                                </select>
                            </td>
                            <td class ="text-center"> 
                                <input class="comment m-t-5" type="text" name="comment"  style="width: 100%;text-align: center;border-style: none">
                            </td>
                            
                        </tr>
                    <?php endif ?>
                    <tr>
                        <td></td>
                        <?php if ($total < $book->total_price): ?>
                            <td class="text-center" ><p style="color:red;font-weight: bold;font-size:15px"><?php echo $total-$book->total_price ?>€</p></td>
                            <td class="text-left" colspan="2"><p style="color:red;font-weight: bold;font-size:15px">Pendiente de pago</p></td>
                        <?php elseif($total > $book->total_price): ?>
                            <td class="text-center" ><p style="color:black;font-weight: bold;font-size:15px"><?php echo $total-$book->total_price ?>€</p></td>
                            <td class="text-left" colspan="2">Sobrante</td>
                        <?php else: ?>
                            <td class="text-center" ><p style="color:black;font-weight: bold;font-size:15px">0€</p></td>
                            <td class="text-left" colspan="2">Al corriente de pago</td>
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

@endsection

@section('scripts')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script src="/assets/plugins/jquery-datatable/media/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="/assets/plugins/jquery-datatable/extensions/TableTools/js/dataTables.tableTools.min.js" type="text/javascript"></script>
<script src="/assets/plugins/jquery-datatable/media/js/dataTables.bootstrap.js" type="text/javascript"></script>
<script src="/assets/plugins/jquery-datatable/extensions/Bootstrap/jquery-datatable-bootstrap.js" type="text/javascript"></script>
<script type="text/javascript" src="/assets/plugins/datatables-responsive/js/datatables.responsive.js"></script>
<script type="text/javascript" src="/assets/plugins/datatables-responsive/js/lodash.min.js"></script>
<script type="text/javascript" src="/assets/js/canvasjs.min.js"></script>


<script type="text/javascript" src="{{asset('/frontend/js/components/moment.js')}}"></script>
<script type="text/javascript" src="{{asset('/frontend/js/components/daterangepicker.js')}}"></script>


<script src="/assets/plugins/bootstrap3-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<script type="text/javascript" src="/assets/plugins/jquery-autonumeric/autoNumeric.js"></script>
<script type="text/javascript" src="/assets/plugins/bootstrap-tag/bootstrap-tagsinput.min.js"></script>
<script type="text/javascript" src="/assets/plugins/jquery-inputmask/jquery.inputmask.min.js"></script>
<script src="/assets/plugins/bootstrap-form-wizard/js/jquery.bootstrap.wizard.min.js" type="text/javascript"></script>
<script src="/assets/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="/assets/plugins/summernote/js/summernote.min.js" type="text/javascript"></script>
<script src="/assets/plugins/moment/moment.min.js"></script>
<script src="/assets/plugins/bootstrap-typehead/typeahead.bundle.min.js"></script>
<script src="/assets/plugins/bootstrap-typehead/typeahead.jquery.min.js"></script>
<script src="/assets/plugins/handlebars/handlebars-v4.0.5.js"></script>

<script type="text/javascript">
    $(function() {
      $(".daterange1").daterangepicker({
        "buttonClasses": "button button-rounded button-mini nomargin",
        "applyClass": "button-color",
        "cancelClass": "button-light",
        locale: {
            format: 'DD MMM, YY',
            "applyLabel": "Aplicar",
            "cancelLabel": "Cancelar",
            "fromLabel": "From",
            "toLabel": "To",
            "customRangeLabel": "Custom",
            "daysOfWeek": [
            "Do",
            "Lu",
            "Mar",
            "Mi",
            "Ju",
            "Vi",
            "Sa"
            ],
            "monthNames": [
            "Enero",
            "Febrero",
            "Marzo",
            "Abril",
            "Mayo",
            "Junio",
            "Julio",
            "Agosto",
            "Septiembre",
            "Octubre",
            "Noviembre",
            "Diciembre"
            ],
            "firstDay": 1,
        },
        
    });
  });

    $(document).ready(function() {          

        var start  = 0;
        var finish = 0;
        var diferencia = 0;
        var price = 0;
        var cost = 0;

        $('.daterange1').change(function(event) {
            var date = $(this).val();

            var arrayDates = date.split('-');

            var date1 = new Date(arrayDates[0]);
            var start = date1.getTime();
            console.log(date1.toLocaleDateString());
            var date2 = new Date(arrayDates[1]);
            var timeDiff = Math.abs(date2.getTime() - date1.getTime());
            var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24)); 
            $('.nigths').val(diffDays);

        });

        $('#newroom, .pax, .parking, .agencia, .type_luxury').click(function(event){ 

            var room = $('#newroom').val();
            var pax = $('.pax').val();
            var park = $('.parking').val();
            var lujo = $('.type_luxury').val();
            var beneficio = 0;
            var costPark = 0;
            var pricePark = 0;
            var diferencia = 0;

            var date = $('.daterange1').val();

            var arrayDates = date.split('-');
            var date1 = new Date(arrayDates[0]);
            var date2 = new Date(arrayDates[1]);
            var timeDiff = Math.abs(date2.getTime() - date1.getTime());
            var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24)); 
            
            var start = date1.toLocaleDateString();
            var finish = date2.toLocaleDateString();

            $.get('/admin/apartamentos/getPaxPerRooms/'+room).success(function( data ){
                if (pax < data) {
                    $('.pax').attr('style' , 'background-color:red');
                }else{
                    $('.pax').removeAttr('style');
                }
            });
            $.get('/admin/reservas/getPricePark', {park: park, noches: diffDays}).success(function( data ) {
                pricePark = data;
                $.get('/admin/reservas/getPriceLujoAdmin', {lujo: lujo}).success(function( data ) {
                    priceLujo = data;

                    $.get('/admin/reservas/getPriceBook', {start: start, finish: finish, pax: pax, room: room, park: park}).success(function( data ) {
                        price = data;
                        
                        price = (parseFloat(price) + parseFloat(pricePark) + parseFloat(priceLujo));
                        $('.total').empty();
                        $('.total').val(price);
                        $.get('/admin/reservas/getCostPark', {park: park, noches: diffDays}).success(function( data ) {
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
            $('.beneficio').empty;
            $('.beneficio').val(beneficio);
            var comentario = $('.book_comments').val();
            alert(comentario);
            $('.book_comments').empty();
            $('.book_comments').html(comentario + '\nEl PVP era '+<?php echo $book->total_price?> +' se vende en '+ price ) ; 
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
            if (status == 5) {
                $('#myModal').modal({
                    show: 'false'
                }); 
                $.get('/admin/reservas/ansbyemail/'+id, function(data) {
                 $('.modal-content').empty().append(data);
             });
            }else{
             $.get('/admin/reservas/changeBook/'+id, {status:status,room: room}, function(data) {
                 window.location.reload();
             }); 
         }
         
     });
    });

</script>
@endsection