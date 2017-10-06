@extends('layouts.admin-master')

@section('title') Administrador de reservas MiramarSKI @endsection

@section('externalScripts') 
<link href="/assets/plugins/jquery-datatable/media/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="/assets/plugins/datatables-responsive/css/datatables.responsive.css" rel="stylesheet" type="text/css" media="screen" />
<link href="/assets/css/font-icons.css" rel="stylesheet" type="text/css" />

<script src="/assets/plugins/summernote/css/summernote.css"></script>

<link href="/assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" type="text/css" media="screen">

<link rel="stylesheet" href="{{ asset('/frontend/css/components/daterangepicker.css')}}" type="text/css" />
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.js"></script>
<style>
hr.cliente {border: 0; height: 4px; margin-top: 20px;background:black; text-align: center;}
hr.cliente:after {content:"Datos del Cliente"; position: relative; top: -12px; display: inline-block; width: 150px; height: 24px; padding: 0;border: 2px solid black; border-radius: 24px; background: black; color: white; font-size: 12px; line-height: 24px; }

hr.reserva {border: 0; height: 4px; margin-top: 20px;background:black; text-align: center;}
hr.reserva:after {content:"Datos de la Reserva"; position: relative; top: -12px; display: inline-block; width: 160px; height: 24px; padding: 0;border: 2px solid black; border-radius: 24px; background: black; color: white; font-size: 12px; line-height: 24px; }

hr.cobro {border: 0; height: 4px; margin-top: 20px;background:black; text-align: center;}
hr.cobro:after {content:"Datos de Cobros"; position: relative; top: -12px; display: inline-block; width: 160px; height: 24px; padding: 0;border: 2px solid black; border-radius: 24px; background: black; color: white; font-size: 12px; line-height: 24px; }


.panel-group .panel-heading .panel-title > a.collapsed:after {
    content: "";
    color: rgba(98, 98, 98, 0.7);
}
.panel-group .panel-heading .panel-title > a:after {
    font-family: 'FontAwesome';
    content: "";
    position: absolute;
    right: 13px;
    top: 36%;
    color: #626262;
}

</style>
@endsection

@section('content')
<?php use \Carbon\Carbon; ?>
<div class="row push-20" style="background-color: rgba(0,0,81,0.1)">
    <div class="col-xs-9 padding-10">
        <p>
            <?php echo "<b>".strtoupper($book->customer->name)."</b>" ?> creada el 
            <?php $fecha = Carbon::createFromFormat('Y-m-d H:i:s' ,$book->created_at);?>
            <?php echo $fecha->copy()->format('d-m-Y')." Hora:".$fecha->copy()->format('H:m')?><br> 
            Creado por <?php echo "<b>".strtoupper($book->user->name)."</b>" ?>
        </p>
    </div>
    <div class="col-xs-3 padding-10">
        <a href="{{ url('/admin/reservas')}}" class=" m-b-10" style="min-width: 10px!important;padding: 25px">
            <img src="{{ asset('/img/miramarski/iconos/close.png') }}" style="width: 20px" />
        </a>
    </div>

    <div class="col-md-6">
        <div class="col-lg-6 content-alert-success" style="display: none;">
            <div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true" style="right: 0">×</button>
                <h3 class="font-w300 push-15">Perfecto</h3>
                <p><a class="alert-link" href="javascript:void(0)">Actualizado</a> correctamente!</p>
            </div>
        </div>
        
        <div class="col-lg-6 content-alert-error2" style="display: none;">
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true" style="right: 0">×</button>
                <h3 class="font-w300 push-15">Error</h3>
                <p><a class="alert-link" href="javascript:void(0)">Ya hay una reserva para ese apartamento</a>!</p>
            </div>
        </div>
        

        <div class="col-lg-6 content-alert-error1" style="display: none;">
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true" style="right: 0">×</button>
                <h3 class="font-w300 push-15">Error</h3>
                <p><a class="alert-link" href="javascript:void(0)">Algo paso al intentar cambiar el estado</a>!</p>
            </div>
        </div>
    </div>

    <div class="col-xs-12 push-0">
        <div class="col-xs-2">
            <h3><a href="{{ url('/admin/pdf/pdf-reserva') }}/<?php echo $book->id ?>"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a></h3>
        </div>
        <div class="col-xs-8" style="padding: 15px 0;">
            <select class="status form-control minimal" data-id="<?php echo $book->id ?>">
                <?php for ($i=1; $i < 9; $i++): ?> 
                    <?php if ($i == $book->type_book): ?>
                        <option selected value="<?php echo $i ?>"  data-id="<?php echo $book->id ?>"><?php echo $book->getStatus($i) ?></option>
                    <?php else: ?>
                        <option value="<?php echo $i ?>"><?php echo $book->getStatus($i) ?></option>
                    <?php endif ?>                                          

                <?php endfor; ?>
            </select>
        </div>
        <div class="col-xs-2">
            <h3><a href="tel:<?php echo $book->customer->phone ?>"><i class="fa fa-phone"></i></a></h3>
        </div>
    </div>
</div>
<div class=" col-xs-12 center text-center">
    <div class="row">
        <!-- DATOS DE LA RESERVA -->
        <div class="row">
            <form role="form"  action="{{ url('/admin/reservas/saveUpdate') }}/<?php echo $book->id ?>" method="post" >
                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                <input type="hidden" name="customer_id" value="<?php echo $book->customer->id; ?>">
                <!-- DATOS DEL CLIENTE -->
                <div class="col-xs-12 bg-white padding-block">
                    <div class="col-xs-12 bg-black push-20">
                        <h4 class="text-center white">
                            DATOS DEL CLIENTE
                        </h4>
                    </div>
                    
                    <div class="col-xs-6 push-10">
                        <label for="name">Nombre</label> 
                        <input class="form-control cliente" type="text" name="name" value="<?php echo $book->customer->name ?>" data-id="<?php echo $book->customer->id ?>">
                    </div>
                   
                    <div class="col-xs-6 push-10">
                        <label for="phone">Telefono</label> 
                        <input class="form-control cliente" type="number" name="phone" value="<?php echo $book->customer->phone ?>" data-id="<?php echo $book->customer->id ?>"> 
                    </div>  
                     <div class="col-xs-12 push-10">
                        <label for="email">Email</label> 
                        <input class="form-control cliente" type="email" name="email" value="<?php echo $book->customer->email ?>" data-id="<?php echo $book->customer->id ?>">  
                    </div>
                </div>
                <!-- DATOS DE LA RESERVA -->
                <div class="col-xs-12 bg-white padding-block">
                    <div class="col-xs-12 bg-black push-20">
                        <h4 class="text-center white">
                            DATOS DE LA RESERVA
                        </h4>
                    </div>
                    <div class="col-xs-12 push-10">
                        <label>Entrada</label>

                        <?php $start = Carbon::createFromFormat('Y-m-d', $book->start); ?>
                        <?php $finish = Carbon::createFromFormat('Y-m-d', $book->finish); ?>

                        <input type="text" class="form-control daterange1" id="fechas" name="fechas" required="" style="cursor: pointer; text-align: center; backface-visibility: hidden;min-height: 28px;" value="<?php echo $start->format('d M, y') ?> - <?php echo $finish->format('d M, y') ?>" readonly="">

                    </div>
                    <div class="col-xs-3 push-10">
                        <label>Noches</label>
                        <input type="text" class="form-control nigths" name="nigths" style="width: 100%" disabled value="<?php echo $book->nigths ?>">
                        <input type="hidden" class="form-control nigths" name="nigths" style="width: 100%" >
                    </div> 
                    <div class="col-xs-3 push-10">
                        <label>Pax</label>
                        <select class=" form-control pax minimal"  name="pax">
                            <?php for ($i=1; $i <= 10 ; $i++): ?>
                                <option value="<?php echo $i ?>" <?php echo ($i == $book->pax)?"selected":""; ?>>
                                    <?php echo $i ?>
                                </option>
                            <?php endfor;?>
                        </select>

                    </div>
                    <div class="col-xs-6 push-10">
                        <label>Apartamento</label>
                        <select class="form-control minimal newroom" name="newroom" id="newroom">
                            <?php foreach ($rooms as $room): ?>
                                <option value="<?php echo $room->id ?>" {{ $room->id == $book->room_id ? 'selected' : '' }}><?php echo $room->name ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="col-xs-6 push-10">
                        <label>Parking</label>
                        <select class=" form-control parking minimal"  name="parking">
                            <?php for ($i=1; $i <= 4 ; $i++): ?>
                                <option value="<?php echo $i ?>" {{ $book->type_park == $i ? 'selected' : '' }}><?php echo $book->getParking($i) ?></option>
                            <?php endfor;?>
                        </select>
                    </div>
                    <div class="col-xs-6 push-10">
                        <label>Sup. Lujo</label>
                        <select class=" form-control full-width type_luxury minimal" data-init-plugin="select" name="type_luxury">
                            <?php for ($i=1; $i <= 4 ; $i++): ?>
                                <option value="<?php echo $i ?>" {{ $book->type_luxury == $i ? 'selected' : '' }}><?php echo $book->getSupLujo($i) ?></option>
                            <?php endfor;?>
                        </select>
                    </div>
                    <div class="col-md-3 col-xs-6">
                        <label>Agencia</label>
                        <select class="form-control agency minimal"  name="agency">
                            <?php for ($i=0; $i <= 2 ; $i++): ?>
                                <option value="<?php echo $i ?>" {{ $book->agency == $i ? 'selected' : '' }}><?php echo $book->getAgency($i) ?></option>
                            <?php endfor;?>
                        </select>
                    </div> 
                    <div class="col-md-3 col-xs-6">                                                        
                        <label>Cost Agencia</label>
                        <input type="number" class="agencia form-control" name="agencia" value="<?php echo $book->PVPAgencia ?>">
                    </div>
                </div>

                <div class="col-xs-12 bg-white">
                    <div class="col-xs-12 not-padding">
                        <?php if ($book->pax < $book->room->minOcu): ?>
                            <p class="personas-antiguo" style="color: red">
                                
                                    Van menos personas que la ocupacion minima del apartamento.
                                
                            </p>
                        <?php else: ?>

                         <p class="personas-antiguo" style="color: red">
                        </p>
                        <?php endif ?>
                    </div>
                    <div class="col-xs-12 not-padding">
                        <div class="col-md-4 col-xs-4 text-center" style="background-color: #0c685f;">
                            <label class="font-w800 text-white" for="">TOTAL</label>
                            <input type="number" class="form-control total m-t-10 m-b-10 white" name="total" value="<?php echo $book->total_price ?>">
                        </div>
                        <?php if (Auth::user()->role == 'admin'): ?>
                            <div class="col-md-4 col-xs-4 text-center" style="background: #99D9EA;">
                                <label class="font-w800 text-white" for="">COSTE</label>
                                <input type="text" class="form-control cost m-t-10 m-b-10 white" name="cost" value="<?php echo $book->cost_total ?>">
                            </div>
                            <div class="col-md-4 col-xs-4 text-center not-padding" style="background: #ff7f27;">
                                <label class="font-w800 text-white" for="">BENEFICIO</label>
                                <input type="text" class="form-control text-left beneficio m-t-10 m-b-10 white" name="beneficio" value="<?php echo $book->total_ben ?>" style="width: 80%; float: left;">
                                <div class="beneficio-text font-w400 font-s18 white" style="width: 20%; float: left;padding: 25px 0; padding-right: 5px;"><?php echo number_format($book->inc_percent,0)."%" ?></div>
                            </div>
                    </div>
                    <div class="col-xs-12 not-padding">
                        <p class="precio-antiguo">
                            El precio asignado es <?php echo $book->total_price ?>
                        </p>
                    </div>
                    <?php else: ?>
                        </div>
                    <?php endif ?> 
                </div>


                
                <div class="col-xs-12 bg-white padding-block">
                    <div class="col-md-6 col-xs-12">
                        <label>Comentarios Cliente </label>
                        <textarea class="form-control" name="comments" rows="5" ><?php echo $book->comment ?></textarea>
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <label>Comentarios Internos</label>
                        <textarea class="form-control book_comments" name="book_comments" rows="5" ><?php echo $book->book_comments ?></textarea>
                    </div>
                </div>
                <div class="row push-40 bg-white padding-block">
                    <div class="col-xs-12 text-center">
                        <button class="btn btn-complete font-s24 font-w400 padding-block" type="submit" style="min-height: 50px;width: 100%;">Guardar</button>
                    </div>  
                </div>
            </form>
        </div>
        <div class="row">
            <div class="col-xs-12 bg-black push-0">
                <h4 class="text-center white">
                    COBROS
                </h4>
            </div>
            <div class="col-xs-12 not-padding">
                <div class="col-xs-4 bg-success text-white text-center" style="min-height: 50px">
                    <span class="font-s18">Total:</span><br>
                    <span class="font-w600 font-s18"><?php echo number_format($book->total_price,2,',','.') ?> €</span>
                </div>
                <div class="col-xs-4 bg-primary text-white text-center" style="min-height: 50px">
                    <span class="font-s18">Cobrado:</span><br>
                    <span class="font-w600 font-s18"><?php echo number_format($totalpayment,2,',','.') ?> €</span>
                </div>
                <div class="col-xs-4 bg-danger text-white text-center" style="min-height: 50px">
                    <span class="font-s18">Pendiente:</span><br>
                    <!-- si esta pendiente nada,.si esta de mas +X -->
                    <span class="font-w600 font-s18"><?php echo ($book->total_price-$totalpayment) >= 0 ? "" : "+";echo number_format($totalpayment-$book->total_price,2,',','.') ?> €</span>
                </div>
            </div>
            <div class="col-md-12 table-responsive not-padding ">
                <table class="table  table-responsive table-striped" style="margin-top: 0;">
                    <thead>
                        <tr>
                            <th class ="text-center" style="min-width: 100px">fecha</th>
                            <th class ="text-center" style="min-width: 100px">importe</th>
                            <th class ="text-center" style="min-width: 200px">Tipo</th>
                            <th class ="text-center" style="min-width: 100px">comentario</th>
                            <th class ="text-center" style="width:20%">Eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $total = 0; ?>
                           
                            <?php foreach ($payments as $payment): ?>
                                <tr>
                                    <td class ="text-center">
                                        <?php 
                                            $fecha = new Carbon($payment->datePayment);
                                            echo $fecha->format('d-m-Y') 
                                        ?>
                                    </td>
                                    <td class ="text-center">
                                        <?php echo $payment->import." €" ?>
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
                                <td class ="text-center" style="padding: 20px 0px 0px 0px;">
                                    <div class="input-daterange input-group" id="datepicker-range" style="width: 100%">
                                        <input type="text" class="input-sm form-control fecha-cobro" name="start" data-date-format="dd-mm-yyyy" value="<?php $hoy = Carbon::now() ;echo $hoy->format('d/m/Y') ?>" style="min-height: 35px">
                                    </div>
                                </td>
                                <td class ="text-center">
                                <input class="importe form-control" type="text" name="importe"  style="width: 100%;text-align: center;">
                                </td>
                                
                                <td class="text-center">
                                    <select class="type_payment form-control minimal" name="type_payment"  tabindex="-1" aria-hidden="true">
                                        <?php for ($i=0; $i < 3 ; $i++): ?>
                                           <option value="<?php echo $i ?>"><?php echo $book->getTypeCobro($i) ?></option>
                                    
                                        <?php endfor ;?>
                                    </select>
                                </td>
                                <td class ="text-center"> 
                                <input class="comment form-control" type="text" name="comment"  style="width: 100%;text-align: center;min-height: 35px">
                                </td>
                                
                            </tr>
                    </tbody>
                </table>
            </div>  
            <div class="col-xs-12 text-center push-40">
                <input type="button" name="cobrar" class="btn btn-success  m-t-10 cobrar" value="Cobrar" data-id="<?php echo $book->id ?>" style="width: 50%;min-height: 50px"> 
            </div>                          
        </div>
    </div>

    <form role="form">
      <div class="form-group form-group-default required" style="display: none">
        <label class="highlight">Message</label>
        <input type="text" hidden="" class="form-control notification-message" placeholder="Type your message here" value="This notification looks so perfect!" required>
      </div>
      <button class="btn btn-success show-notification hidden" id="boton">Show</button>
    </form>


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
    
    <script src="/assets/js/notifications.js" type="text/javascript"></script>
    <script src="/assets/js/scripts.js" type="text/javascript"></script>

    <script src="/assets/plugins/summernote/js/summernote.js"></script>
    
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

             $('.status').change(function(event) {



                 $('.content-alert-success').hide();
                 $('.content-alert-error1').hide();
                 $('.content-alert-error2').hide();
                 // content-alert-success
                 // content-alert-error1
                 // content-alert-error2
                 var status = $(this).val();
                 var id     = $(this).attr('data-id');
                 var clase  = $(this).attr('class');

                 if (status == 5) {
                     $('#contentEmailing').empty().load('/admin/reservas/ansbyemail/'+id);  
                     $('#btnEmailing').trigger('click');

                        
                 }else{
                     $.get('/admin/reservas/changeStatusBook/'+id, { status:status }, function(data) {
                         if (data == 1) {
                             $('.content-alert-success').show();
                         } else if (data == 0){
                             $('.content-alert-error1').show();
                         } else{
                             $('.content-alert-error2').show();
                         }
                     }); 
                }

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
                $.get('/admin/reservas/changeBook/'+id, {status:status,room: room}, function(data) {
                    window.location.reload();
                });
            });


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

            $('.daterange1, #newroom, .pax, .parking, .agencia, .type_luxury').click(function(event){ 

                $('.status').attr("disabled", "disabled");
                $('.notification-message').val("Guarda antes de cambiar el estado");
                document.getElementById("boton").click();
                setTimeout(function(){ 
                    $('.pgn-wrapper .pgn .alert .close').trigger('click');
                     }, 3000);
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
                        $('.personas-antiguo').empty();
                        $('.personas-antiguo').append('Van menos personas que el minimo, se le cobrara el minimo de la habitacion que son :'+data);
                    }else{
                        $('.pax').removeAttr('style');
                        $('.personas-antiguo').empty();
                    }
                });
                $.get('/admin/reservas/getPricePark', {park: park, noches: diffDays}).success(function( data ) {
                    pricePark = data;
                    $.get('/admin/reservas/getPriceLujoAdmin', {lujo: lujo}).success(function( data ) {
                        priceLujo = data;

                        $.get('/admin/reservas/getPriceBook', {start: start, finish: finish, pax: pax, room: room, park: park}).success(function( data ) {
                            price = data;
                            
                            price = (parseFloat(price) + parseFloat(pricePark) + parseFloat(priceLujo));

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

                                        var precio = $('.total').val();
                                        $('.precio-antiguo').empty;
                                        $('.precio-antiguo').text('El precio introducido es '+precio+' y el precio real es '+price);

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

                var precio = $('.total').val();
                $('.precio-antiguo').empty;
                $('.precio-antiguo').text('El precio introducido es '+precio);

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
                    $('.notification-message').val(data);
                    document.getElementById("boton").click();
                    setTimeout(function(){ 
                        $('.pgn-wrapper .pgn .alert .close').trigger('click');
                         }, 3000);

                });
            });

            $('.parking').change(function(event){ 
                var commentBook = $('.book_comments').val();

                $('.book_comments').empty();
                var res = commentBook.replace("Parking: Si\n","");
                res = res.replace("Parking: No\n","");
                res = res.replace("Parking: Gratis\n","");
                res = res.replace("Parking: 50 %\n","");
                
                $('.book_comments').text( $.trim(res+'Parking: '+ $('option:selected', this).text())+"\n");
            });

            $('.type_luxury').change(function(event){ 
                var commentBook = $('.book_comments').val();
                $('.book_comments').empty();
                var res = commentBook.replace("Suplemento de lujo: Si\n","");
                res = res.replace("Suplemento de lujo: No\n","");
                res = res.replace("Suplemento de lujo: Gratis\n","");
                res = res.replace("Suplemento de lujo: 50 %\n","");
                $('.book_comments').text( $.trim(res+'Suplemento de lujo: '+ $('option:selected', this).text())+"\n");
            });


        });

    </script>
    @endsection