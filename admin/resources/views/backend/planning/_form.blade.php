@extends('layouts.admin-master')

@section('title') Administrador de reservas MiramarSKI @endsection

@section('externalScripts') 

    <link href="assets/plugins/jquery-datatable/media/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/plugins/jquery-datatable/extensions/FixedColumns/css/dataTables.fixedColumns.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/plugins/datatables-responsive/css/datatables.responsive.css" rel="stylesheet" type="text/css" media="screen" />

    <link href="assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" type="text/css" media="screen">
    <link href="assets/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" media="screen">
    <link href="assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" media="screen">

@endsection
    
@section('content')
<?php use \Carbon\Carbon; ?>
<div class="container-fixed-lg">
    <div>
        <div style="width: 100%">
            <!-- START PANEL -->
            <div class="panel panel-default">
                <form role="form"  action="{{ url('reservas/saveUpdate') }}" method="post">
                    
                    <!-- Seccion Cliente -->
                    <div class="panel-heading">
                        <div class="panel-title">
                            Crear Cliente
                        </div>
                    </div>

                    <div class="panel-body">

                        <div class="input-group col-md-12">
                            <div class="col-md-4">
                                Nombre: <input class="form-control" type="text" name="name" value="<?php echo $book->customer->name ?>">
                            </div>
                            <div class="col-md-4">
                                Email: <input class="form-control" type="email" name="email" value="<?php echo $book->customer->email ?>">  
                            </div>
                            <div class="col-md-4">
                                Telefono: <input class="form-control" type="number" name="phone" value="<?php echo $book->customer->phone ?>"> 
                            </div>  
                            <div style="clear: both;"></div>
                        </div>                                            
                    </div>

                    <!-- Seccion Reserva -->
                    <div class="panel-heading">
                        <div class="panel-title">
                            Crear reserva
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
                                    <label>Noches</label>
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
                                <div class="col-md-2" style="height: 100%">
                                    <input type="button" name="recalcular" class="recalcular form-control btn btn-complete active" value="Recalcular">
                                    
                                </div>
                            </div>
                            <div class="input-group col-md-12">
                                <div class="col-md-3">
                                    <label>Extras</label>
                                    <input type="text" value="<?php echo $book->extra ?>">
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
            <!-- END PANEL -->
        </div>
            <!-- END PANEL -->      
    </div>
</div>
@endsection

@section('scripts')
   <script src="assets/plugins/jquery-datatable/media/js/jquery.dataTables.min.js" type="text/javascript"></script>
   <script src="assets/plugins/jquery-datatable/extensions/TableTools/js/dataTables.tableTools.min.js" type="text/javascript"></script>
   <script src="assets/plugins/jquery-datatable/media/js/dataTables.bootstrap.js" type="text/javascript"></script>
   <script src="assets/plugins/jquery-datatable/extensions/Bootstrap/jquery-datatable-bootstrap.js" type="text/javascript"></script>
   <script type="text/javascript" src="assets/plugins/datatables-responsive/js/datatables.responsive.js"></script>
   <script type="text/javascript" src="assets/plugins/datatables-responsive/js/lodash.min.js"></script>

   <script src="assets/plugins/bootstrap3-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
   <script type="text/javascript" src="assets/plugins/jquery-autonumeric/autoNumeric.js"></script>
   <script type="text/javascript" src="assets/plugins/dropzone/dropzone.min.js"></script>
   <script type="text/javascript" src="assets/plugins/bootstrap-tag/bootstrap-tagsinput.min.js"></script>
   <script type="text/javascript" src="assets/plugins/jquery-inputmask/jquery.inputmask.min.js"></script>
   <script src="assets/plugins/bootstrap-form-wizard/js/jquery.bootstrap.wizard.min.js" type="text/javascript"></script>
   <script src="assets/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
   <script src="assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
   <script src="assets/plugins/summernote/js/summernote.min.js" type="text/javascript"></script>
   <script src="assets/plugins/moment/moment.min.js"></script>
   <script src="assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
   <script src="assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
   <script src="assets/plugins/bootstrap-typehead/typeahead.bundle.min.js"></script>
   <script src="assets/plugins/bootstrap-typehead/typeahead.jquery.min.js"></script>
   <script src="assets/plugins/handlebars/handlebars-v4.0.5.js"></script>

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
                
                $.get('apartamentos/getPaxPerRooms/'+room).success(function( data ){
                    if (pax < data) {
                        $('.pax').attr('style' , 'background-color:red');
                    }else{
                        $('.pax').removeAttr('style');
                    }
                });

                $.get('reservas/getPriceBook', {start: start, finish: finish, pax: pax, room: room, park: park}).success(function( data ) {
                    $('.total').empty();
                    $('.total').val(data);
                    price = data;
                        $.get('reservas/getCostBook', {start: start, finish: finish, pax: pax, room: room, park: park}).success(function( data ) {
                        $('.cost').empty();
                        $('.cost').val(data);  
                        cost = data;
                        beneficio = price - cost;
                        $('.beneficio').empty;
                        $('.beneficio').val(beneficio);
                        
                    });
                });

                
            });

            
        });

    </script>
@endsection