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
            background-color: #0DAD9E !important;
        }
        .Pagada-la-señal td{
            background-color: #F77975  !important;
        }
        .Bloqueado td{
            background-color: #F9D975 !important;
        }
        .SubComunidad td{
            background-color: #8A7DBE !important;
        }
    </style>
<div class="container-fluid padding-10 sm-padding-10">
    <div class="row">
        <div class="col-md-12 col-xs-12 m-b-10 ">
            <button class="btn btn-tag btn-success create-book btn-cons m-b-10" data-toggle="modal" data-target="#myModal" type="button"><i class="pg-plus"></i></span>
            </button>
        </div>

        <div class="col-md-6 col-xs-12">
            <div class="sm-m-l-5 sm-m-r-5">
                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingOne">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne" class="collapsed ">
                                    Reservas pendientes <?php echo $countnews ?>
                                </a>
                            </h4>
                        </div>

                        <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne" aria-expanded="false" style="height: 0px;">

                            <div class="panel-body">
                                
                                <div class="pull-right">
                                  <div class="col-xs-12 ">
                                    <input type="text" id="search-table" class="form-control pull-right" placeholder="Buscar">
                                  </div>
                                </div>
                                
                                <div class="clearfix"></div>
                                
                                <table class="table table-hover demo-table-search table-responsive-block" id="tableWithSearch" >
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
                                    <?php foreach ($newbooks as $book): ?>
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

                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingTwo">
                            <h4 class="panel-title">
                                <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Reservas Antiguas <?php echo $countold ?>
                                </a>
                            </h4>
                        </div>
                        <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo" aria-expanded="false" style="height: 0px;">
                            <div class="panel-body">

                                <div class="pull-right">
                                  <div class="col-xs-12 ">
                                    <input type="text" id="search-table" class="form-control pull-right" placeholder="Buscar">
                                  </div>
                                </div>

                                <div class="clearfix"></div>

                                <table class="table table-hover demo-table-search table-responsive-block" id="tableWithSearch" >
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
                                    <?php foreach ($oldbooks as $book): ?>
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

                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingThree">
                            <h4 class="panel-title">
                                <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    Reservas Bloqueadas <?php echo $countbloq ?>
                                </a>
                            </h4>
                        </div>
                        <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree" aria-expanded="false" style="height: 0px;">
                            <div class="panel-body">

                                <div class="pull-right">
                                  <div class="col-xs-12 ">
                                    <input type="text" id="search-table" class="form-control pull-right" placeholder="Buscar">
                                  </div>
                                </div>

                                <div class="clearfix"></div>

                                <table class="table table-hover demo-table-search table-responsive-block" id="tableWithSearch" >
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
                                    <?php foreach ($bloqbooks as $book): ?>
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

                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingFour">
                            <h4 class="panel-title">
                                <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                    Reservas Subcomunidad <?php echo $countsub ?>
                                </a>
                            </h4>
                        </div>
                        <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour" aria-expanded="false" style="height: 0px;">
                            <div class="panel-body">

                                <div class="pull-right">
                                  <div class="col-xs-12 ">
                                    <input type="text" id="search-table" class="form-control pull-right" placeholder="Buscar">
                                  </div>
                                </div>
                                <div class="clearfix"></div>
                                <table class="table table-hover demo-table-search table-responsive-block" id="tableWithSearch" >
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
                                    <?php foreach ($subbooks as $book): ?>
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

                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingFive">
                            <h4 class="panel-title">
                                <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                    Siguientes reservas <?php echo $countprox ?>
                                </a>
                            </h4>
                        </div>
                        <div id="collapseFive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFive" aria-expanded="false" style="height: 0px;">
                            <div class="panel-body">

                                <div class="pull-right">
                                    <div class="col-xs-12 ">
                                        <input type="text" id="search-table" class="form-control pull-right" placeholder="Buscar">
                                    </div>
                                </div>
                                
                                <div class="clearfix"></div>

                                <table class="table table-hover demo-table-search table-responsive-block" id="tableWithSearch" >
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
                                    <?php foreach ($proxbooks as $book): ?>
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


            
        });

    </script>
@endsection