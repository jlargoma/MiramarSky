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

      .daterangepicker{
        top: 59%!important;
      }

</style>
@endsection

@section('content')
<?php use \Carbon\Carbon; ?>
<div class="container-fluid padding-10 sm-padding-10" style="background-color: rgba(0,0,81,0.1)">
    <div class="row">

        <div class="col-xs-12 m-b-20">

            <h3>
                <div class="col-xs-2">
                    <a href="{{ url('/admin/pdf/pdf-reserva') }}/<?php echo $book->id ?>"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
                </div>
                <div class="col-xs-8">
                    <select class="status form-control" data-id="<?php echo $book->id ?>">
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
                    <a href="tel:<?php echo $book->customer->phone ?>"><i class="fa fa-phone"></i></a>
                </div>
                 
            </h3>
        </div>
        <hr>
        <div class="col-xs-12 ">
            <div class="panel">
                <form role="form"  action="{{ url('/admin/reservas/saveUpdate') }}/<?php echo $book->id ?>" method="post" >
                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="false">
                        <div class="panel panel-default">

                            <div class="panel-heading" role="tab" id="Cliente">
                                <h4 class="panel-title">
                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#acordeonCliente" aria-expanded="false" aria-controls="acordeonCliente">
                                        <hr class="cliente">
                                    </a>
                                </h4>
                            </div>

                            <div class="panel-heading" role="tab" id="Reserva">
                                <h4 class="panel-title">
                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#acordeonReserva" aria-expanded="false" aria-controls="acordeonReserva">
                                        <hr class="reserva">
                                    </a>
                                </h4>
                            </div>
                            
                            <div class="panel-heading" role="tab" id="Reserva">
                                <h4 class="panel-title">
                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#acordeonCobro" aria-expanded="false" aria-controls="acordeonCobro">
                                        <hr class="cobro">
                                    </a>
                                </h4>
                            </div>

                            <div id="acordeonCliente" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo" aria-expanded="false" style="height: 0px;">
                                <div class="panel-body">
                                    <div class="panel panel-default">                                
                                        <div class="panel-body m-t-10" style="padding: 0px 0px 0px 0px;">
                                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                            <div class="input-group col-xs-12">
                                                <input class="form-control" type="hidden"  name="customer_id" value="<?php echo $book->customer->id ?>">
                                                <div class="col-xs-6">
                                                   <input class="form-control cliente" type="text" name="name" value="<?php echo $book->customer->name ?>" data-id="<?php echo $book->customer->id ?>">
                                                </div>
                                                <div class="col-xs-6">
                                                    <input class="form-control cliente" type="number" name="phone" value="<?php echo $book->customer->phone ?>" data-id="<?php echo $book->customer->id ?>"> 
                                                </div>
                                                <br><br>
                                                <div class="col-xs-12">
                                                    <input class="form-control cliente" type="email" name="email" value="<?php echo $book->customer->email ?>" data-id="<?php echo $book->customer->id ?>">    
                                                </div>
                                                 
                                                <div style="clear: both;"></div>
                                            </div>                                            
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="acordeonReserva" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo" aria-expanded="false" style="height: 0px;">
                                <div class="panel-body">
                                    <div class="panel panel-default">                                                                        
                                        <div class="panel-body" style="padding: 0px 0px 0px 0px;">
                                                <div class="input-group col-md-12">
                                                    <div class="col-xs-12 m-t-20">
                                                        <div class="input-prepend input-group col-xs-12">
                                                          <input type="text" class="form-control daterange1" id="fechas" name="fechas" required="" style="cursor: pointer; text-align: center; backface-visibility: hidden;min-height: 28px;   " readonly="">
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-3 m-t-10">
                                                        <label><i class="fa fa-moon-o"></i></label>
                                                        <input type="text" class="form-control nigths" name="nigths" value="" style="width: 100%;display:none">
                                                        <input type="text" class="form-control nigths" name="noches" value="<?php echo $book->nigths ?>" disabled style="width: 100%">
                                                    </div> 
                                                    <div class="col-xs-3 m-t-10">
                                                        <label><i class="fa fa-user"></i></label>
                                                        <input  type="text" class="form-control full-width pax" name="pax" style="width: 100%" value="<?php echo $book->pax ?>">
                                                            
                                                    </div>
                                                    <div class="col-xs-6 m-t-10">
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
                                                    <div class="col-xs-6 m-t-10">
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
                                                    <div class="col-xs-6 m-t-10">
                                                        <label><i class="fa fa-star"></i><i class="fa fa-star"></i></label>
                                                        <select class=" form-control full-width parking" data-init-plugin="select2" name="parking">
                                                            <?php for ($i=1; $i <= 4 ; $i++): ?>
                                                                <?php if ($i == $book->type_luxury): ?>
                                                                    <option value="<?php echo $i ?>" selected><?php echo $book->getParking($i) ?></option>
                                                                <?php else: ?>
                                                                    <option value="<?php echo $i ?>"><?php echo $book->getParking($i) ?></option>
                                                                <?php endif ?>
                                                            <?php endfor;?>
                                                        </select>
                                                    </div>
                                                </div>
                                                    <div class="col-xs-6 m-t-10">                                                        
                                                        <label>Cost Agencia</label>
                                                        <input type="text" class="agencia form-control pvpAgencia" name="agencia" value="0">
                                                    </div>

                                                    <div class="col-xs-6 m-t-10">
                                                        <label>Agencia</label>
                                                        <select class=" form-control full-width agency" data-init-plugin="select2" name="agency">
                                                      <option value="0"></option>
                                                            <?php for ($i=1; $i <= 2 ; $i++): ?>
                                                                <option value="<?php echo $i ?>" {{ $book->agency == $i ? 'selected' : '' }}><?php echo $book->getAgency($i) ?></option>
                                                            <?php endfor;?>
                                                        </select>
                                                    </div>
                                                <div style="clear: both;"></div>
                                                <div class="col-xs-4 m-t-10 p-b-10 text-white" style="background-color: #0c685f">
                                                    <label>PVP</label>
                                                    <input type="text" class="form-control total text-white" name="total" value="<?php echo $book->total_price ?>" style="font-weight: bold;width: 100%;border:none;background: #0c685f">
                                                </div> 

                                                <div class="col-xs-4 m-t-10 p-b-10 text-white" style="background-color: #99D9EA">
                                                    <label>COSTE</label>
                                                    <input type="text" class="form-control cost text-white" name="cost" value="<?php echo $book->cost_total ?>" disabled style="font-weight: bold;width: 100%;border:none;background: #99D9EA">
                                                </div>

                                                <div class="col-xs-4 m-t-10 p-b-10 text-white" style="background-color: #ff7f27">
                                                    <label>BENº</label>
                                                    <input type="text" class="form-control beneficio text-white" name="beneficio" value="<?php echo $book->total_ben ?>" disabled style="font-weight: bold;width: 100%;border:none;background: #ff7f27">
                                                </div>
                                                <br>
                                                <div class="input-group col-xs-12 m-t-10">
                                                    <?php if ($book->comment == ""): ?>
                                                    <?php else: ?>
                                                        <div class="col-xs-12">
                                                            <label>Comentarios Cliente</label>
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
                                    </div>
                                </div>
                            </div>

                            <div id="acordeonCobro" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo" aria-expanded="false" style="height: 0px;">
                                <div class="panel-body">
                                    <div class="panel panel-default">                                                                        
                                        <div class="panel-heading m-b-20">
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
                                        <div class="panel-body ">
                                            <div class="col-md-12 table-responsive p-b-20">
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

                    <!-- Seccion Reserva -->
                       
                    <!-- Seccion Reserva -->
                </form> 
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
        });

    </script>
@endsection