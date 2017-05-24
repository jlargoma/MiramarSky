@extends('layouts.admin-master')

@section('title') Administrador de reservas MiramarSKI @endsection

@section('externalScripts') 

    <link href="assets/plugins/jquery-datatable/media/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/plugins/jquery-datatable/extensions/FixedColumns/css/dataTables.fixedColumns.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/plugins/datatables-responsive/css/datatables.responsive.css" rel="stylesheet" type="text/css" media="screen" />

@endsection
    
@section('content')
<?php use \Carbon\Carbon; ?>
    <style type="text/css">
        .Reservado td{
            background-color: lightGreen !important;
        }
    </style>
<div class="container-fluid padding-10 sm-padding-10">
    <div class="row">
        <div class="col-md-6 col-xs-12">
            <div class="col-md-12 col-xs-12" style="width: 100%">
                <div class="text-center pendientes" id="hide"><h3>Reservas pendientes</h3></div>
                    <div class="reservatable">
                        <div class="pull-right">
                          <div class="col-xs-12 ">
                            <input type="text" id="search-table" class="form-control pull-right" placeholder="Buscar">
                          </div>
                        </div>
                        <div class="clearfix"></div>
                        <table class="table table-hover demo-table-search table-responsive-block reservatable" id="tableWithSearch" >
                            <thead>
                                <tr>

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
                            <?php foreach ($books as $book): ?>
                                <tr class="<?php echo $book->getStatus($book->type_book) ?>">
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
        <div class="col-md-6 col-xs-12">
            @include('backend.planning.calendar')
        </div>
    </div>
</div>
    <div class="modal fade" id="modal-book" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="block block-themed block-transparent remove-margin-b">
                    <div class="block-header bg-primary-dark">
                        <ul class="block-options">
                            <li>
                                <button data-dismiss="modal" type="button"><i class="si si-close"></i></button>
                            </li>
                        </ul>
                    </div>
                    <div class="row block-content" id="content-book">

                    </div>
                </div>
            </div>
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

    <script type="text/javascript">
        $(document).ready(function() {
                // $("#hide").click(function(){
                //     $(".reservatable").hide();
                // });
                // $("#show").click(function(){
                //     $(".reservatable").show();
                // });
                var hidePassBook = 1;
                $('.reservatable').show();
                $('#hide').click(function(){
                    if(hidePassBook)
                    {
                        hidePassBook = 0;
                        $('.reservatable').show();
                    }
                    else
                    {
                        hidePassBook = 1;
                       $('.reservatable').hide(); 
                    }
                });

            //Añadir una nueva habitacion
            $('.new-book').click(function(event) {
                $.get('/admin/planning/new', function(data) {
                    $('#content-book').empty().append(data);
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
                    /*window.location.reload();*/
                });
            });


            
        });

    </script>
@endsection