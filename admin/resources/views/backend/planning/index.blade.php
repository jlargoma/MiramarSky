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
    <style type="text/css">
        .Reservado{
            background-color: #0DAD9E !important;
        }
        .Pagada-la-señal{
            background-color: #F77975  !important;
        }
        .Bloqueado{
            background-color: #F9D975 !important;
        }
        .SubComunidad{
            background-color: #8A7DBE !important;
        }
    </style>
<div class="container-fluid padding-10 sm-padding-10">
    <div class="row">
        
        <div class="col-md-12 col-xs-12 m-b-10 ">
            <button class="btn btn-tag btn-success create-book btn-cons m-b-10" data-toggle="modal" data-target="#myModal" type="button"><i class="pg-plus"></i></span>
            </button>
        </div>

        <div class="col-md-6">
                <div class="alert alert-info visible-xs m-r-5 m-l-5" role="alert">
                    <button class="close" data-dismiss="alert"></button>
                    <strong>Info: </strong> On mobile the tab will be come a Accorian by using data-init-reponsive-tabs="collapse"
                </div>
                <div class="panel">
                    <ul class="nav nav-tabs nav-tabs-simple" role="tablist" data-init-reponsive-tabs="collapse">
                        <li><a href="#tabNueva" data-toggle="tab" role="tab">Nueva</a>
                        </li>
                        <li class="active"><a href="#tabPendientes" data-toggle="tab" role="tab">Pendientes <?php echo $countnews ?></a>
                        </li>
                        <li><a href="#tabEspeciales" data-toggle="tab" role="tab">Especiales</a>
                        </li>
                        <li><a href="#tabPagadas" data-toggle="tab" role="tab">Pagadas</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane " id="tabNueva">
                            <div class="row">
                                <div class="col-md-12">
                                    <form role="form"  action="{{ url('reservas/getPriceBook') }}" method="post">

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

                                                            <input id="start" type="text" class="input-sm form-control" name="start" data-date-format="dd-mm-yyyy">
                                                            <span class="input-group-addon">to</span>
                                                            <input id="finish" type="text" class="input-sm form-control" name="finish" data-date-format="dd-mm-yyyy">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <label>Noches</label>
                                                        <input type="text" class="form-control noches" name="noches" value="" disabled style="width: 100%">
                                                    </div> 
                                                    <div class="col-md-1">
                                                        <label>Pax</label>
                                                        <input  type="text" class="form-control full-width pax" name="pax" style="width: 100%">
                                                            
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label>Pax</label>
                                                        <select class="form-control full-width newroom" data-init-plugin="select2" name="newroom" id="newroom">
                                                            <?php foreach ($rooms as $room): ?>
                                                                <option value="<?php echo $room->id ?>"><?php echo $room->name ?></option>
                                                            <?php endforeach ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label>Park</label>
                                                        <select class=" form-control full-width parking" data-init-plugin="select2" name="parking">
                                                            <?php for ($i=1; $i <= 4 ; $i++): ?>
                                                                <option value="<?php echo $i ?>"><?php echo $book->getParking($i) ?></option>
                                                            <?php endfor;?>
                                                        </select>
                                                    </div>    
                                                </div>
                                                <div class="input-group col-md-12">
                                                    <div class="col-md-3">
                                                        <label>Total</label>
                                                        <input type="text" class="form-control total" name="total" value="" disabled style="width: 100%">
                                                    </div> 
                                                    <div class="col-md-3">
                                                        <label>Coste</label>
                                                        <input type="text" class="form-control cost" name="cost" value="" disabled style="width: 100%">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Beneficio</label>
                                                        <input type="text" class="form-control beneficio" name="beneficio" value="" disabled style="width: 100%">
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="input-group col-md-12">
                                                    <label>Comentarios</label>
                                                    <textarea class="form-control" name="book_comments" style="width: 100%">
                                                        
                                                    </textarea>
                                                </div>                         
                                        </div>

                                        <!-- Seccion Cliente -->
                                        <div class="panel-heading">
                                            <div class="panel-title">
                                                Crear Cliente
                                            </div>
                                        </div>

                                        <div class="panel-body">

                                            <div class="input-group col-md-12">
                                                <div class="col-md-4">
                                                    Nombre: <input class="form-control" type="text" name="name">
                                                </div>
                                                <div class="col-md-4">
                                                    Email: <input class="form-control" type="email" name="email">  
                                                </div>
                                                <div class="col-md-4">
                                                    Telefono: <input class="form-control" type="number" name="phone"> 
                                                </div>  
                                                <div style="clear: both;"></div>
                                                <br>
                                                <div class="input-group col-md-12">
                                                    <button class="btn btn-complete" type="submit">Guardar</button>
                                                </div> 
                                            </div>                                            
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane active" id="tabPendientes">
                            <div class="row column-seperation">
                                <div class="pull-left">
                                    <div class="col-xs-12 ">
                                        <input type="text" id="search-table" class="form-control pull-right" placeholder="Buscar">
                                    </div>
                                </div>
                        
                                <div class="clearfix"></div>
    
                                <table class="table table-hover demo-table-search table-responsive-block" id="tableWithSearch" >
                                    <thead>
                                        <tr>
                                            <th class ="text-center bg-complete text-white" style="width:1%"></th>
                                            <th class ="text-center bg-complete text-white" style="width:10%">  Cliente     </th>
                                            <th class ="text-center bg-complete text-white" style="width:5%">   Pax    </th>
                                            <th class ="text-center bg-complete text-white" style="width:5%">   Apart       </th>
                                            <th class ="text-center bg-complete text-white" style="width:15%">  Entrada     </th>
                                            <th class ="text-center bg-complete text-white" style="width:15%">  Salida      </th>
                                            <th class ="text-center bg-complete text-white">                    Noches      </th>
                                            <th class ="text-center bg-complete text-white">                    Precio      </th>
                                            <th class ="text-center bg-complete text-white">                    Estado      </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($newbooks as $book): ?>
                                        <tr>
                                            <td class="<?php echo $book->getStatus($book->type_book) ?>"></td>
                                            <td class ="text-center"><?php echo $book->Customer->name ?></td>
                                            <td class ="text-center"><?php echo $book->pax ?></td>
                                            <td class ="text-center">
                                                <select class="room" class="form-control" data-id="<?php echo $book->id ?>" >
                                                    
                                                    <?php foreach ($rooms as $room): ?>
                                                        <?php if ($room->id == $book->room_id): ?>
                                                            <option selected value="<?php echo $book->room_id ?>" data-id="<?php echo $room->name ?>">
                                                                <?php echo $room->name ?><span>
                                                            </option>
                                                        <?php else:?>
                                                            <option value="<?php echo $room->id ?>"><?php echo $room->name ?></option>
                                                        <?php endif ?>
                                                    <?php endforeach ?>
                                                </select>
                                            </td>
                                            <td class ="text-center">
                                                <?php
                                                    $start = Carbon::createFromFormat('Y-m-d',$book->start);
                                                    echo $start->format('d-m-Y');
                                                ?>
                                            </td>
                                            <td class ="text-center">
                                                <?php
                                                    $finish = Carbon::createFromFormat('Y-m-d',$book->finish);
                                                    echo $finish->format('d-m-Y');
                                                ?>
                                            </td>
                                            <td class ="text-center"><?php echo $book->nigths ?></td>
                                            <td class ="text-center"><?php echo $book->total_price."€" ?></td>
                                            <td class ="text-center">
                                                <select class="status" class="form-control" data-id="<?php echo $book->id ?>" >
                                                    <?php for ($i=1; $i < 9; $i++): ?> 
                                                        <?php if ($i == $book->type_book): ?>
                                                            <option selected value="<?php echo $i ?>"  data-id="aaaa"><?php echo $book->getStatus($i) ?></option>
                                                        <?php else: ?>
                                                            <option value="<?php echo $i ?>"><?php echo $book->getStatus($i) ?></option>
                                                        <?php endif ?>                                          
                                                         
                                                    <?php endfor; ?>
                                                </select>
                                            </td>
                                            
                                        </tr>
                                    <?php endforeach ?>
                                    </tbody>
                                </table>  
                            </div>
                        </div>

                        <div class="tab-pane " id="tabEspeciales">
                            <div class="row">
                                <div class="pull-left">
                                    <div class="col-xs-12 ">
                                        <input type="text" id="search-table" class="form-control pull-right" placeholder="Buscar">
                                    </div>
                                </div>
                                
                                <div class="clearfix"></div>

                                <div class="col-md-12">
                                    <table class="table table-hover demo-table-search table-responsive-block" id="tableWithSearch" >
                                    <thead>
                                        <tr>
                                            <th class ="text-center bg-complete text-white" style="width:1%"></th>
                                            <th class ="text-center bg-complete text-white" style="width:10%">  Cliente     </th>
                                            <th class ="text-center bg-complete text-white" style="width:5%">   Pax    </th>
                                            <th class ="text-center bg-complete text-white" style="width:5%">   Apart       </th>
                                            <th class ="text-center bg-complete text-white" style="width:15%">  Entrada     </th>
                                            <th class ="text-center bg-complete text-white" style="width:15%">  Salida      </th>
                                            <th class ="text-center bg-complete text-white">                    Noches      </th>
                                            <th class ="text-center bg-complete text-white">                    Precio      </th>
                                            <th class ="text-center bg-complete text-white">                    Estado      </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($specialbooks as $book): ?>
                                        <tr>
                                            <td class="<?php echo $book->getStatus($book->type_book) ?>"></td>
                                            <td class ="text-center"><?php echo $book->Customer->name ?></td>
                                            <td class ="text-center"><?php echo $book->pax ?></td>
                                            <td class ="text-center">
                                                <select class="room" class="form-control" data-id="<?php echo $book->id ?>" >
                                                    
                                                    <?php foreach ($rooms as $room): ?>
                                                        <?php if ($room->id == $book->room_id): ?>
                                                            <option selected value="<?php echo $book->room_id ?>" data-id="<?php echo $room->name ?>">
                                                                <?php echo $room->name ?><span>
                                                            </option>
                                                        <?php else:?>
                                                            <option value="<?php echo $room->id ?>"><?php echo $room->name ?></option>
                                                        <?php endif ?>
                                                    <?php endforeach ?>
                                                </select>
                                            </td>
                                            <td class ="text-center">
                                                <?php
                                                    $start = Carbon::createFromFormat('Y-m-d',$book->start);
                                                    echo $start->format('d-m-Y');
                                                ?>
                                            </td>
                                            <td class ="text-center">
                                                <?php
                                                    $finish = Carbon::createFromFormat('Y-m-d',$book->finish);
                                                    echo $finish->format('d-m-Y');
                                                ?>
                                            </td>
                                            <td class ="text-center"><?php echo $book->nigths ?></td>
                                            <td class ="text-center"><?php echo $book->total_price."€" ?></td>
                                            <td class ="text-center">
                                                <select class="status" class="form-control" data-id="<?php echo $book->id ?>" >
                                                    <?php for ($i=1; $i < 9; $i++): ?> 
                                                        <?php if ($i == $book->type_book): ?>
                                                            <option selected value="<?php echo $i ?>"  data-id="aaaa"><?php echo $book->getStatus($i) ?></option>
                                                        <?php else: ?>
                                                            <option value="<?php echo $i ?>"><?php echo $book->getStatus($i) ?></option>
                                                        <?php endif ?>                                          
                                                         
                                                    <?php endfor; ?>
                                                </select>
                                            </td>
                                            
                                        </tr>
                                    <?php endforeach ?>
                                    </tbody>
                                </table> 
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="tabPagadas">
                            <div class="row">
                                <div class="pull-left">
                                    <div class="col-xs-12 ">
                                        <input type="text" id="search-table" class="form-control pull-right" placeholder="Buscar">
                                    </div>
                                </div>
                                
                                <div class="clearfix"></div>

                                <table class="table table-hover demo-table-search table-responsive-block" id="tableWithSearch" >
                                    <thead>
                                        <tr>
                                            <th class ="text-center bg-complete text-white" style="width:1%"></th>
                                            <th class ="text-center bg-complete text-white" style="width:10%">  Cliente     </th>
                                            <th class ="text-center bg-complete text-white" style="width:5%">   Pax    </th>
                                            <th class ="text-center bg-complete text-white" style="width:5%">   Apart       </th>
                                            <th class ="text-center bg-complete text-white" style="width:15%">  Entrada     </th>
                                            <th class ="text-center bg-complete text-white" style="width:15%">  Salida      </th>
                                            <th class ="text-center bg-complete text-white">                    Noches      </th>
                                            <th class ="text-center bg-complete text-white">                    Precio      </th>
                                            <th class ="text-center bg-complete text-white">                    Estado      </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($paidbooks as $book): ?>
                                        <tr>
                                            <td class="<?php echo $book->getStatus($book->type_book) ?>"></td>
                                            <td class ="text-center"><?php echo $book->Customer->name ?></td>
                                            <td class ="text-center"><?php echo $book->pax ?></td>
                                            <td class ="text-center">
                                                <select class="room" class="form-control" data-id="<?php echo $book->id ?>" >
                                                    
                                                    <?php foreach ($rooms as $room): ?>
                                                        <?php if ($room->id == $book->room_id): ?>
                                                            <option selected value="<?php echo $book->room_id ?>" data-id="<?php echo $room->name ?>">
                                                                <?php echo $room->name ?><span>
                                                            </option>
                                                        <?php else:?>
                                                            <option value="<?php echo $room->id ?>"><?php echo $room->name ?></option>
                                                        <?php endif ?>
                                                    <?php endforeach ?>
                                                </select>
                                            </td>
                                            <td class ="text-center">
                                                <?php
                                                    $start = Carbon::createFromFormat('Y-m-d',$book->start);
                                                    echo $start->format('d-m-Y');
                                                ?>
                                            </td>
                                            <td class ="text-center">
                                                <?php
                                                    $finish = Carbon::createFromFormat('Y-m-d',$book->finish);
                                                    echo $finish->format('d-m-Y');
                                                ?>
                                            </td>
                                            <td class ="text-center"><?php echo $book->nigths ?></td>
                                            <td class ="text-center"><?php echo $book->total_price."€" ?></td>
                                            <td class ="text-center">
                                                <select class="status" class="form-control" data-id="<?php echo $book->id ?>" >
                                                    <?php for ($i=1; $i < 9; $i++): ?> 
                                                        <?php if ($i == $book->type_book): ?>
                                                            <option selected value="<?php echo $i ?>"  data-id="aaaa"><?php echo $book->getStatus($i) ?></option>
                                                        <?php else: ?>
                                                            <option value="<?php echo $i ?>"><?php echo $book->getStatus($i) ?></option>
                                                        <?php endif ?>                                          
                                                         
                                                    <?php endfor; ?>
                                                </select>
                                            </td>
                                            
                                        </tr>
                                    <?php endforeach ?>
                                    </tbody>
                                </table>   
                            </div>
                        </div>

                    </div>
                </div>
        </div>

        <div class="col-md-6 col-xs-12">
            <div id="leyenda-reservas" style="margin-top:30px;float:left;margin-left:5%">
                <div>
                    <div style="float:left;width:20px;height:20px;background-color:white;border: 1px solid #333;margin-left:10px;"></div>
                    <div style="float:left;margin-left:5px">Libre</div>

                    <div style="float:left;width:20px;height:20px;background-color:#0DAD9E;border: 1px solid #333;margin-left:10px;"></div>
                    <div style="float:left;margin-left:5px">Reservado</div>

                    <div style="float:left;width:20px;height:20px;background-color:#F77975;border: 1px solid #333;margin-left:10px;"></div>
                    <div style="float:left;margin-left:5px">Pagada la señal</div>

                    <div style="float:left;width:20px;height:20px;background-color:#F9D975;border: 1px solid #333;margin-left:10px;"></div>
                    <div style="float:left;margin-left:5px">Bloqueado</div>
                    <div style="float:left;width:20px;height:20px;background-color:#8A7DBE;border: 1px solid #333;margin-left:10px;"></div>
                    <div style="float:left;margin-left:5px">Subcomunidad</div>
                </div>
            </div>
            @include('backend.planning.calendar')
        </div>

    </div>
</div>

<div class="modal fade slide-up disable-scroll in" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content-wrapper">
      <div class="modal-content">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-50"></i>
        </button>
        <div class="container-xs-height full-height">
          <div class="row-xs-height">
            <div class="modal-body col-xs-height col-middle text-center">

            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
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

            $('.create-book').click(function(event) {
                var id = $(this).attr('data-id');
                $.get('reservas/new', function(data) {
                    $('.modal-body').empty().append(data);
                });
            });

            /* $('#status, #room').change(function(event) { */
            $('.status, .room').change(function(event) {
                var id = $(this).attr('data-id');
                /* var room = $('#room').val();*/
                console.log($(this).attr('class'));
                var clase = $(this).attr('class');
                
                if (clase == "status") {
                   var status = $(this).val();
                }else{
                    var status = "";
                }if(clase == "room"){
                    var room = $(this).val();
                }else{
                    var room = "";
                }
                /* $.get('/admin/apartamentos/update/'+id, {  id: id, status:status, room:room }, function(data) { */
                $.get('reservas/changeBook/'+id, {status:status,room: room}, function(data) {
                    alert(data);
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
                    $('.noches').empty();
                    $('.noches').html(diferencia);
                }
            });

            $('#finish').change(function(event) {
                finish= $(this).val();
                var info = finish.split('/');
                finish = info[1] + '/' + info[0] + '/' + info[2];           
                if (start != 0) {
                    diferencia = Math.floor((  Date.parse(finish)- Date.parse(start) ) / 86400000);
                    $('.noches').empty();
                    $('.noches').val(diferencia);
                }
            });

            $('#newroom, .pax, .parking').change(function(event){ 

                var room = $('#newroom').val();
                var pax = $('.pax').val();
                var park = $('.parking').val();
                var beneficio = 0;

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