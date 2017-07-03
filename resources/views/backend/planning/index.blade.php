@extends('layouts.admin-master')

@section('title') Administrador de reservas MiramarSKI @endsection

@section('externalScripts') 

    <link href="/assets/plugins/jquery-datatable/media/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/plugins/jquery-datatable/extensions/FixedColumns/css/dataTables.fixedColumns.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/plugins/datatables-responsive/css/datatables.responsive.css" rel="stylesheet" type="text/css" media="screen" />

    <link href="/assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" type="text/css" media="screen">
    <link href="/assets/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" media="screen">
    <link href="/assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" media="screen">

@endsection
    
@section('content')
<?php use \Carbon\Carbon; 
    setlocale(LC_TIME, "ES");
    setlocale(LC_TIME, "es_ES");
?>
    <style type="text/css">
        .Reservado{
            background-color: #0DAD9E !important;
            color: black;
        }
        .Reservado.start{
            background: red; /* For browsers that do not support gradients */
            background: -webkit-linear-gradient(left, white, green); /* For Safari 5.1 to 6.0 */
            background: -o-linear-gradient(right, white, green); /* For Opera 11.1 to 12.0 */
            background: -moz-linear-gradient(right, white, green); /* For Firefox 3.6 to 15 */
            background: linear-gradient(to right, white, green); /* Standard syntax */
        }
        .Reservado.end{
            background: red; /* For browsers that do not support gradients */
            background: -webkit-linear-gradient(left, green, white); /* For Safari 5.1 to 6.0 */
            background: -o-linear-gradient(right, green, white); /* For Opera 11.1 to 12.0 */
            background: -moz-linear-gradient(right, green, white); /* For Firefox 3.6 to 15 */
            background: linear-gradient(to right, green, white); /* Standard syntax */
        }
        .Pagada-la-señal{
            background-color: #F77975  !important;
            color: black;
        }
        .Pagada-la-señal.start{
            background: red; /* For browsers that do not support gradients */
            background: -webkit-linear-gradient(left, yellow , red); /* For Safari 5.1 to 6.0 */
            background: -o-linear-gradient(right, yellow, red); /* For Opera 11.1 to 12.0 */
            background: -moz-linear-gradient(right, yellow, red); /* For Firefox 3.6 to 15 */
            background: linear-gradient(to right, yellow , red); /* Standard syntax */
        }
        .Pagada-la-señal.end{
            background: red; /* For browsers that do not support gradients */
            background: -webkit-linear-gradient(left, red , yellow); /* For Safari 5.1 to 6.0 */
            background: -o-linear-gradient(right, red, yellow); /* For Opera 11.1 to 12.0 */
            background: -moz-linear-gradient(right, red, yellow); /* For Firefox 3.6 to 15 */
            background: linear-gradient(to right, red , yellow); /* Standard syntax */
        }
        .Bloqueado{
            background-color: #F9D975 !important;
            color: black;
        }
        .Bloqueado.start{
            background: red; /* For browsers that do not support gradients */
            background: -webkit-linear-gradient(left, white, yellow); /* For Safari 5.1 to 6.0 */
            background: -o-linear-gradient(right, white, yellow); /* For Opera 11.1 to 12.0 */
            background: -moz-linear-gradient(right, white, yellow); /* For Firefox 3.6 to 15 */
            background: linear-gradient(to right, white, yellow); /* Standard syntax */
        }
        .Bloqueado.end{
            background: red; /* For browsers that do not support gradients */
            background: -webkit-linear-gradient(left, yellow, white); /* For Safari 5.1 to 6.0 */
            background: -o-linear-gradient(right, yellow, white); /* For Opera 11.1 to 12.0 */
            background: -moz-linear-gradient(right, yellow, white); /* For Firefox 3.6 to 15 */
            background: linear-gradient(to right, yellow, white); /* Standard syntax */
        }
        .SubComunidad{
            background-color: #8A7DBE !important;
            color: black;
        }
        .SubComunidad.start{
            background: red; /* For browsers that do not support gradients */
            background: -webkit-linear-gradient(left, white, purple); /* For Safari 5.1 to 6.0 */
            background: -o-linear-gradient(right, white, purple); /* For Opera 11.1 to 12.0 */
            background: -moz-linear-gradient(right, white, purple); /* For Firefox 3.6 to 15 */
            background: linear-gradient(to right, white, purple); /* Standard syntax */
        }
        .SubComunidad.end{
            background: red; /* For browsers that do not support gradients */
            background: -webkit-linear-gradient(left, purple, white); /* For Safari 5.1 to 6.0 */
            background: -o-linear-gradient(right, purple, white); /* For Opera 11.1 to 12.0 */
            background: -moz-linear-gradient(right, purple, white); /* For Firefox 3.6 to 15 */
            background: linear-gradient(to right, purple, white); /* Standard syntax */
        }
        .botones{
            padding-top: 0px!important;
            padding-bottom: 0px!important;
        }
        td{
            margin: 0px;
            padding: 0px!important;
        }
        a {
            color: black;
            cursor: pointer;
        }
        .S, .D{
            background-color: rgba(0,0,0,0.2);
            color: red;
        }
    </style>

    <div class="container-fluid padding-10 sm-padding-10">
        <div class="row">
                <div class="col-md-12 col-xs-12 push-20">
                    <div class="col-xs-12 col-md-2 pull-right">
                        <select id="date" class="form-control">
                            <?php $fecha = $date->copy()->startOfYear(); ?>
                            <?php for ($i=1; $i <= 24; $i++): ?>
                                <?php if( $date->copy()->format('n') == $i ){ $selected = "selected"; }else{$selected = "";} ?>
                                <option value="<?php echo $fecha->format('d-m-Y'); ?>" <?php echo $selected ?>>
                                    <?php echo ucfirst($fecha->formatLocalized('%B')); ?>  <?php echo $fecha->formatLocalized('%Y'); ?> 
                                </option>
                                <?php $fecha->addMonth(); ?>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
            <div class="col-md-7 col-xs-12">
                <div class="panel">
                    <ul class="nav nav-tabs nav-tabs-simple" role="tablist" data-init-reponsive-tabs="collapse">
                        <li class="nuevo"><a href="#tabNueva" data-toggle="tab" role="tab" ><i class="fa fa-plus-circle fa-3x" style="color:green" aria-hidden="true"></i></a>
                        </li>
                        <li class="active" ><a href="#tabPendientes" data-toggle="tab" role="tab">Pendientes </a>
                        </li>
                        <li><a href="#tabEspeciales" data-toggle="tab" role="tab">Especiales </a>
                        </li>
                        <li><a href="#tabPagadas" data-toggle="tab" role="tab">Confirmadas </a>
                        </li>
                    </ul>
                    <div class="tab-content">

                        <div class="tab-pane " id="tabNueva">
                            <div class="row">
                                <div class="col-md-12">
                                    <form role="form"  action="{{ url('/admin/reservas/create') }}" method="post">
                                        
                                        <!-- Seccion Cliente -->
                                        <div class="panel-heading">
                                            <div class="panel-title">
                                                Datos del cliente
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
                                                            <input id="start" type="text" class="input-sm form-control" name="start" data-date-format="dd-mm-yyyy">
                                                            <span class="input-group-addon">Hasta</span>
                                                            <input id="finish" type="text" class="input-sm form-control" name="finish" data-date-format="dd-mm-yyyy">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <label>Noches</label>
                                                        <input type="text" class="form-control nigths" name="nigths" value="" style="width: 100%">
                                                    </div> 
                                                    <div class="col-md-1">
                                                        <label>Pax</label>
                                                        <input  type="text" class="form-control full-width pax" name="pax" style="width: 100%">
                                                            
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label>Apartamento</label>
                                                        <select class="form-control full-width newroom" data-init-plugin="select2" name="newroom" id="newroom">
                                                            <?php foreach ($rooms as $room): ?>
                                                                <option value="<?php echo $room->id ?>"><?php echo $room->name ?></option>
                                                            <?php endforeach ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label>Park</label>
                                                        <select class=" form-control parking"  name="parking">
                                                            <?php for ($i=1; $i <= 4 ; $i++): ?>
                                                                <option value="<?php echo $i ?>"><?php echo $book->getParking($i) ?></option>
                                                            <?php endfor;?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label>Extras</label>
                                                        <select class="full-width select2-hidden-accessible" data-init-plugin="select2" multiple="" name="extras[]" tabindex="-1" aria-hidden="true">
                                                            <?php foreach ($extras as $extra): ?>
                                                                <option value="<?php echo $extra->id ?>"><?php echo $extra->name ?></option>
                                                            <?php endforeach ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="input-group col-md-12">
                                                    <div class="col-md-1">                                                        
                                                        <label>Cost Agencia</label>
                                                        <input type="text" class="agencia form-control" name="agencia" value="0">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label>Agencia</label>
                                                        <select class=" form-control full-width agency" data-init-plugin="select2" name="agency">
                                                            <?php for ($i=1; $i <= 2 ; $i++): ?>
                                                                <option value="<?php echo $i ?>"><?php echo $book->getAgency($i) ?></option>
                                                            <?php endfor;?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Total</label>
                                                        <input type="text" class="form-control total" name="total" value="" style="width: 100%">
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
                                                    <div class="col-md-6">
                                                        <label>Comentarios Usuario</label>
                                                        <textarea class="form-control" name="comments" style="width: 100%">
                                                        </textarea>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>Comentarios reserva</label>
                                                        <textarea class="form-control book_comments" name="book_comments" style="width: 100%">
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
                        </div>

                        <div class="tab-pane active" id="tabPendientes">
                            <div class="row column-seperation">
                                <div class="pull-left">
                                        <div class="col-xs-12 ">
                                            <input type="text" id="search-table" class="form-control pull-right" placeholder="Buscar">
                                        </div>
                                    </div>
                            
                                    <div class="clearfix"></div>
        
                                    <table class="table table-hover demo-table-search table-responsive" id="tableWithSearch" >
                                        <thead>
                                            <tr>   
                                                <th class ="text-center bg-complete text-white" style="width:5%!important"> Cobros </th>
                                                <th class ="text-center bg-complete text-white" style="width:20%!important">  Cliente     </th>
                                                <th class ="text-center bg-complete text-white" style="width:10%">  Telefono     </th>
                                                <th class ="text-center bg-complete text-white" style="width:2%">   P         </th>
                                                <th class ="text-center bg-complete text-white" style="width:5%">   Apart       </th>
                                                <th class ="text-center bg-complete text-white" style="width:5%!important">  IN     </th>
                                                <th class ="text-center bg-complete text-white" style="width:5%!important">  OUT      </th>
                                                <th class ="text-center bg-complete text-white" style="width:5%">   Noc         </th>
                                                <th class ="text-center bg-complete text-white">                    Precio      </th>
                                                <th class ="text-center bg-complete text-white" style="width:5%">   Estado      </th>
                                                <th class ="text-center bg-complete text-white">                    A  </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($arrayBooks["nuevas"] as $book): ?>
                                                    <tr>
                                                        <td class ="text-center ">
                                                            <?php if (isset($payment[$book->id])): ?>
                                                                <?php if ($payment[$book->id] == 0): ?>
                                                                <?php else:?>
                                                                    <!-- input class="progress-circle" data-pages-progress="circle" value="<?php echo number_format(100/($book->total_price/$payment[$book->id]),0) ?>" type="hidden">
                                                                    <?php echo number_format(100/($book->total_price/$payment[$book->id]),0).'%' ?> -->
                                                                        <p><?php echo number_format(100/($book->total_price/$payment[$book->id]),0).'%' ?></p>

                                                                        <div class="progress ">
                                                                        <div class="progress-bar progress-bar-danger" style="width:<?php echo number_format(100/($book->total_price/$payment[$book->id]),0).'%' ?>"></div>
                                                                        </div> 
                                                                                                                               
                                                                <?php endif; ?>
                                                            <?php else: ?>
                                                            <?php endif ?> 
                                                        </td> 

                                                        <td class ="text-center">

                                                                <a class="update-book" data-id="<?php echo $book->id ?>"  title="Editar Reserva"  href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>"><?php echo $book->customer['name']  ?></a>                                                        
                                                        </td>

                                                        <td class ="text-center"> 
                                                            <?php if ($book->customer->phone != 0): ?>
                                                                <a class="btn btn-tag btn-primary" href="tel:<?php echo $book->customer->phone ?>"><?php echo $book->customer->phone ?>
                                                            <?php else: ?>
                                                            <?php endif ?>
                                                        </td>

                                                        <td class ="text-center"><?php echo $book->pax ?></td>

                                                        <td class ="text-center">
                                                            <select class="room" data-id="<?php echo $book->id ?>" >
                                                                
                                                                <?php foreach ($rooms as $room): ?>
                                                                    <?php if ($room->id == $book->room_id): ?>
                                                                        <option selected value="<?php echo $book->room_id ?>" data-id="<?php echo $room->name ?>">
                                                                            <?php echo substr($room->name,0,5) ?>
                                                                        </option>
                                                                    <?php else:?>
                                                                        <option value="<?php echo $room->id ?>"><?php echo substr($room->name,0,5) ?></option>
                                                                    <?php endif ?>
                                                                <?php endforeach ?>

                                                            </select>
                                                        </td>

                                                        <td class ="text-center" style="width: 20%!important">
                                                            <?php
                                                                $start = Carbon::createFromFormat('Y-m-d',$book->start);
                                                                echo $start->formatLocalized('%d %b');
                                                            ?>
                                                        </td>

                                                        <td class ="text-center" style="width: 20%!important">
                                                            <?php
                                                                $finish = Carbon::createFromFormat('Y-m-d',$book->finish);
                                                                echo $finish->formatLocalized('%d %b');
                                                            ?>
                                                        </td>

                                                        <td class ="text-center"><?php echo $book->nigths ?></td>

                                                        <td class ="text-center"><?php echo $book->total_price."€" ?><br>
                                                                                <?php if (isset($payment[$book->id])): ?>
                                                                                    <?php echo "<p style='color:red'>".$payment[$book->id]."</p>" ?>
                                                                                <?php else: ?>
                                                                                <?php endif ?>
                                                        </td>

                                                        <td class ="text-center">
                                                            <select class="status form-control" data-id="<?php echo $book->id ?>" >
                                                                <?php for ($i=1; $i < 9; $i++): ?> 
                                                                    <?php if ($i == $book->type_book): ?>
                                                                        <option selected value="<?php echo $i ?>"  data-id="<?php echo $book->id ?>"><?php echo $book->getStatus($i) ?></option>
                                                                    <?php else: ?>
                                                                        <option value="<?php echo $i ?>"><?php echo $book->getStatus($i) ?></option>
                                                                    <?php endif ?>                                          
                                                                     
                                                                <?php endfor; ?>
                                                            </select>
                                                        </td>

                                                        <td>                                                        
                                                                <!--  -->
                                                                <!-- <?php if ($book->customer['phone'] != 0): ?>
                                                                        <a class="btn btn-tag btn-primary" href="tel:<?php echo $book->customer['phone'] ?>"><i class="pg-phone"></i>
                                                                        </a>
                                                                <?php endif ?> -->
                                                                
                                                                <?php if ($book->send == 0): ?>
                                                                    <a class="btn btn-tag btn-primary" ><i class=" pg-mail"></i></a>
                                                                    </a>
                                                                <?php else: ?>
                                                                <?php endif ?>
                                                                
                                                            </div>
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
                                        <input type="text" id="search-table2" class="form-control pull-right" placeholder="Buscar">
                                    </div>
                                </div>
                                
                                <div class="clearfix"></div>

                                <div class="col-md-12">
                                    <table class="table table-hover demo-table-search table-responsive" id="tableWithSearch2" >
                                        <thead>
                                            <tr>
                                                <th class ="text-center bg-complete text-white" style="width:10%">  Cliente     </th>
                                                <th class ="text-center bg-complete text-white" style="width:5%">   Telefono    </th>
                                                <th class ="text-center bg-complete text-white" style="width:2%">   Pax         </th>
                                                <th class ="text-center bg-complete text-white" style="width:5%">   Apart       </th>
                                                <th class ="text-center bg-complete text-white" style="width:11%!important">  IN     </th>
                                                <th class ="text-center bg-complete text-white" style="width:11%!important">  OUT      </th>
                                                <th class ="text-center bg-complete text-white" style="width:5%">   Noc         </th>
                                                <th class ="text-center bg-complete text-white">                    Precio      </th>
                                                <th class ="text-center bg-complete text-white" style="width:17%">   Estado      </th>
                                                <th class ="text-center bg-complete text-white">                    Acciones    </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($arrayBooks["especiales"] as $book): ?>
                                                    <tr>
                                                        <td class ="text-center">                                                            
                                                            <a class="update-book" data-id="<?php echo $book->id ?>"  title="Editar Reserva"  href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>"><?php echo $book->customer['name'] ?></a>
                                                        </td>

                                                        <td class ="text-center"><?php echo $book->customer->phone ?></td>
                                                        <td class ="text-center"><?php echo $book->pax ?></td>
                                                        <td class ="text-center">
                                                            <select class="room" class="form-control" data-id="<?php echo $book->id ?>" >
                                                                
                                                                <?php foreach ($rooms as $room): ?>
                                                                    <?php if ($room->id == $book->room_id): ?>
                                                                        <option selected value="<?php echo $book->room_id ?>" data-id="<?php echo $room->name ?>">
                                                                            <?php echo substr($room->name,0,5) ?>
                                                                        </option>
                                                                    <?php else:?>
                                                                        <option value="<?php echo $room->id ?>"><?php echo substr($room->name,0,5) ?></option>
                                                                    <?php endif ?>
                                                                <?php endforeach ?>

                                                            </select>
                                                        </td>
                                                        <td class ="text-center" style="width: 20%!important">
                                                            <?php
                                                                $start = Carbon::createFromFormat('Y-m-d',$book->start);
                                                                echo $start->formatLocalized('%d %b');
                                                            ?>
                                                        </td>
                                                        <td class ="text-center" style="width: 20%!important">
                                                            <?php
                                                                $finish = Carbon::createFromFormat('Y-m-d',$book->finish);
                                                                echo $finish->formatLocalized('%d %b');
                                                            ?>
                                                        </td>
                                                        <td class ="text-center"><?php echo $book->nigths ?></td>
                                                        <td class ="text-center"><?php echo $book->total_price."€" ?><br>
                                                                                <?php if (isset($payment[$book->id])): ?>
                                                                                    <?php echo "<p style='color:red'>".$payment[$book->id]."</p>" ?>
                                                                                <?php else: ?>
                                                                                <?php endif ?>
                                                        </td>
                                                        <td class ="text-center">
                                                            <select class="status form-control" data-id="<?php echo $book->id ?>" >
                                                                <?php for ($i=1; $i < 9; $i++): ?> 
                                                                    <?php if ($i == $book->type_book): ?>
                                                                        <option selected value="<?php echo $i ?>"  data-id="<?php echo $book->id ?>"><?php echo $book->getStatus($i) ?></option>
                                                                    <?php else: ?>
                                                                        <option value="<?php echo $i ?>"><?php echo $book->getStatus($i) ?></option>
                                                                    <?php endif ?>                                          
                                                                     
                                                                <?php endfor; ?>
                                                            </select>
                                                        </td>
                                                        <td>                                                        
                                                                <!--  -->
                                                                <!-- <?php if ($book->customer['phone'] != 0): ?>
                                                                        <a class="btn btn-tag btn-primary" href="tel:<?php echo $book->customer['phone'] ?>"><i class="pg-phone"></i>
                                                                        </a>
                                                                <?php endif ?> -->
                                                                
                                                                <?php if ($book->send == 0): ?>
                                                                    <a class="btn btn-tag btn-primary" ><i class=" pg-mail"></i></a>
                                                                    </a>
                                                                <?php else: ?>
                                                                <?php endif ?>
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
                                        <input type="text" id="search-table3" class="form-control pull-right" placeholder="Buscar">
                                    </div>
                                </div>
                                
                                <div class="clearfix"></div>

                                <table class="table table-hover demo-table-search table-responsive" id="tableWithSearch3" >
                                    <thead>
                                        <tr>   
                                            <th class ="text-center bg-complete text-white" style="width:5%"> Cobros </th>
                                            <th class ="text-center bg-complete text-white" style="width:5%">  Cliente     </th>
                                            <th class ="text-center bg-complete text-white" style="width:5%">  Telefono     </th>
                                            <th class ="text-center bg-complete text-white" style="width:2%">   Pax         </th>
                                            <th class ="text-center bg-complete text-white" style="width:5%">   Apart       </th>
                                            <th class ="text-center bg-complete text-white" style="width:20%!important">  IN     </th>
                                            <th class ="text-center bg-complete text-white" style="width:20%!important">  OUT      </th>
                                            <th class ="text-center bg-complete text-white" style="width:5%">   Noc         </th>
                                            <th class ="text-center bg-complete text-white" style="width:5%">   Precio      </th>
                                            <th class ="text-center bg-complete text-white" style="width:5%">   Estado      </th>
                                            <th class ="text-center bg-complete text-white" style="width:5%">   Acciones    </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($arrayBooks["pagadas"] as $book): ?>
                                                <tr>
                                                    <td class ="text-center">                                                    
                                                        <?php if (isset($payment[$book->id])): ?>
                                                            <?php if ($payment[$book->id] == 0): ?>
                                                            <?php else:?>
                                                                <p><?php echo number_format(100/($book->total_price/$payment[$book->id]),0).'%' ?></p>
                                                                <div class="progress ">
                                                                <div class="progress-bar progress-bar-danger" style="width:<?php echo number_format(100/($book->total_price/$payment[$book->id]),0).'%' ?>"></div>
                                                                </div>                                                            
                                                            <?php endif; ?>
                                                        <?php else: ?>
                                                        <?php endif ?>
                                                    </td> 
                                                    <td class ="text-center">
                                                            <a class="update-book" data-id="<?php echo $book->id ?>"  title="Editar Reserva"  href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>"><?php echo substr($book->customer['name'], 0,10)  ?></a>                                                        
                                                    </td>

                                                    <td class ="text-center"><?php echo $book->customer->phone ?></td>
                                                    <td class ="text-center"><?php echo $book->pax ?></td>
                                                    <td class ="text-center">
                                                        <select class="room" data-id="<?php echo $book->id ?>" >
                                                            
                                                            <?php foreach ($rooms as $room): ?>
                                                                <?php if ($room->id == $book->room_id): ?>
                                                                    <option selected value="<?php echo $book->room_id ?>" data-id="<?php echo $room->name ?>">
                                                                        <?php echo substr($room->name,0,5) ?>
                                                                    </option>
                                                                <?php else:?>
                                                                    <option value="<?php echo $room->id ?>"><?php echo substr($room->name,0,5) ?></option>
                                                                <?php endif ?>
                                                            <?php endforeach ?>

                                                        </select>
                                                    </td>
                                                    <td class ="text-center" style="width: 20%!important">
                                                        <?php
                                                            $start = Carbon::createFromFormat('Y-m-d',$book->start);
                                                            echo $start->formatLocalized('%d %b');
                                                        ?>
                                                    </td>
                                                    <td class ="text-center" style="width: 20%!important">
                                                        <?php
                                                            $finish = Carbon::createFromFormat('Y-m-d',$book->finish);
                                                            echo $finish->formatLocalized('%d %b');
                                                        ?>
                                                    </td>
                                                    <td class ="text-center"><?php echo $book->nigths ?></td>
                                                    <td class ="text-center"><?php echo $book->total_price."€" ?><br>
                                                                            <?php if (isset($payment[$book->id])): ?>
                                                                                <?php echo "<p style='color:red'>".$payment[$book->id]."</p>" ?>
                                                                            <?php else: ?>
                                                                            <?php endif ?>
                                                    </td>
                                                    <td class ="text-center">
                                                        <select class="status form-control" data-id="<?php echo $book->id ?>" >
                                                            <?php for ($i=1; $i < 9; $i++): ?> 
                                                                <?php if ($i == $book->type_book): ?>
                                                                    <option selected value="<?php echo $i ?>"  data-id="<?php echo $book->id ?>"><?php echo $book->getStatus($i) ?></option>
                                                                <?php else: ?>
                                                                    <option value="<?php echo $i ?>"><?php echo $book->getStatus($i) ?></option>
                                                                <?php endif ?>                                          
                                                                 
                                                            <?php endfor; ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <div class="col-md-6 <?php echo $book->getStatus($book->type_book) ?>" style="    position: relative;height: 30px;width: 30px;border-radius:50%">&nbsp;</div>
                                                        <div class="btn-group col-md-6">
                                                            <!--  -->
                                                            <!-- <?php if ($book->customer['phone'] != 0): ?>
                                                                    <a class="btn btn-tag btn-primary" href="tel:<?php echo $book->customer['phone'] ?>"><i class="pg-phone"></i>
                                                                    </a>
                                                            <?php endif ?> -->
                                                            
                                                            <?php if ($book->send == 0): ?>
                                                                <a class="btn btn-tag btn-primary" ><i class=" pg-mail"></i></a>
                                                                </a>
                                                            <?php else: ?>
                                                            <?php endif ?>
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
        
            <div class="col-md-5 col-xs-12">
                <div class="panel">
                    <ul class="nav nav-tabs nav-tabs-simple" role="tablist" data-init-reponsive-tabs="collapse">
                     <li class="active"><a href="#tabPrimera" data-toggle="tab" role="tab"><?php echo ucfirst($date->copy()->formatLocalized('%B %Y'))?></a>
                     </li>
                     <li><a href="#tabSegunda" data-toggle="tab" role="tab"><?php echo ucfirst($date->addMonth()->formatLocalized('%B %Y'))?> </a>
                     </li>
                     <li><a href="#tabTercera" data-toggle="tab" role="tab"><?php echo ucfirst($date->addMonth()->formatLocalized('%B %Y'))?> </a>
                     </li>
                     <li><a href="#tabCuarta" data-toggle="tab" role="tab"><?php echo ucfirst($date->addMonth()->formatLocalized('%B %Y'))?> </a>
                     </li>
                    </ul>
                    <div class="tab-content">
                        <?php $date=$date->subMonth(3); ?>
                        <div class="tab-pane active" id="tabPrimera">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="fc-border-separate" style="width: 100%">
                                       <thead>
                                            <tr >
                                                <td class="text-center" colspan="<?php echo $arrayMonths[$date->copy()->format('n')]+1 ?>">
                                                    <?php echo  ucfirst($date->copy()->formatLocalized('%B %Y'))?>
                                                </td> 
                                            </tr>
                                            <tr>
                                                <td rowspan="2" style="width: 1%!important"></td>
                                                    <?php for ($i=1; $i <= $arrayMonths[$date->copy()->format('n')] ; $i++): ?> 
                                                        <td style='border:1px solid black;width: 3%;font-size: 10px' class="text-center">
                                                            <?php echo $i?> 
                                                        </td> 
                                                     <?php endfor; ?>
                                            </tr>
                                            <tr>
                                                
                                                <?php for ($i=1; $i <= $arrayMonths[$date->copy()->format('n')] ; $i++): ?> 
                                                    <td style='border:1px solid black;width: 3%;font-size: 10px' class="text-center <?php echo $days[$date->copy()->format('n')][$i]?>">
                                                        <?php echo $days[$date->copy()->format('n')][$i]?> 
                                                    </td> 
                                                 <?php endfor; ?> 
                                            </tr>
                                       </thead>
                                       <tbody>
                                       
                                            <?php foreach ($roomscalendar as $room): ?>
                                                <tr>
                                                    <?php $date = $date->startOfMonth() ?>
                                                    <td class="text-center"><b><?php echo substr($room->nameRoom, 0,5)?></b></td>
                                                        
                                                    <?php for ($i=01; $i <= $arrayMonths[$date->copy()->format('n')] ; $i++): ?> 

                                                            <?php if (isset($arrayReservas[$room->id][$date->copy()->format('Y')][$date->copy()->format('n')][$i])): ?>
                                                                <?php if ($arrayReservas[$room->id][$date->copy()->format('Y')][$date->copy()->format('n')][$i]->start == $date->copy()->format('Y-m-d')): ?>
                                                                        <td style='border:1px solid grey;width: 3%'>
                                                                            <div style="width: 50%;float: left;">
                                                                                &nbsp;
                                                                            </div>
                                                                            <div class="<?php echo $book->getStatus($arrayReservas[$room->id][$date->copy()->format('Y')][$date->copy()->format('n')][$i]->type_book) ?> start" style="width: 50%;float: left;">
                                                                                &nbsp;
                                                                            </div>

                                                                        </td>    
                                                                <?php elseif($arrayReservas[$room->id][$date->copy()->format('Y')][$date->copy()->format('n')][$i]->finish == $date->copy()->format('Y-m-d')): ?>
                                                                        <td style='border:1px solid grey;width: 3%'>
                                                                            <div class="<?php echo $book->getStatus($arrayReservas[$room->id][$date->copy()->format('Y')][$date->copy()->format('n')][$i]->type_book) ?> end" style="width: 50%;float: left;">
                                                                                &nbsp;
                                                                            </div>
                                                                            <div style="width: 50%;float: left;">
                                                                                &nbsp;
                                                                            </div>
                                                                            

                                                                        </td>
                                                                <?php else: ?>
                                                                    
                                                                        <td style='border:1px solid grey;width: 3%' title="<?php echo $arrayReservas[$room->id][$date->copy()->format('Y')][$date->copy()->format('n')][$i]->customer['name'] ?>" class="<?php echo $book->getStatus($arrayReservas[$room->id][$date->copy()->format('Y')][$date->copy()->format('n')][$i]->type_book) ?>">

                                                                       <a href="{{url ('/admin/reservas/update')}}/<?php echo $arrayReservas[$room->id][$date->copy()->format('Y')][$date->copy()->format('n')][$i]->id ?>">
                                                                           <div style="width: 100%;height: 100%">
                                                                               &nbsp;
                                                                           </div>
                                                                       </a>

                                                                    </td>

                                                                <?php endif ?>
                                                            <?php else: ?>
                                                                <td class="<?php echo $days[$date->copy()->format('n')][$i]?>" style='border:1px solid grey;width: 3%'>
                                                                    
                                                                </td>
                                                            <?php endif; ?>
                                                            <?php if ($date->copy()->format('d') != $arrayMonths[$date->copy()->format('n')]): ?>
                                                                <?php $date = $date->addDay(); ?>
                                                            <?php else: ?>
                                                                <?php $date = $date->startOfMonth() ?>
                                                            <?php endif ?>
                                                        
                                                    <?php endfor; ?> 
                                                </tr>
                                                
                                            <?php endforeach; ?>
                                       </tbody>
                                    </table>
                                    <?php $date = $date->addMonth(); ?>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane " id="tabSegunda">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="fc-border-separate" style="border:1px solid black;width: 100%">
                                       <thead>
                                            <tr>
                                                <td class="text-center" colspan="<?php echo $arrayMonths[$date->copy()->format('n')]+1 ?>">
                                                    <?php echo  ucfirst($date->copy()->formatLocalized('%B %Y'))?>
                                                </td> 
                                            </tr>
                                            <tr>
                                                <td rowspan="2" style="width: 1%!important">Apto</td>
                                                    <?php for ($i=1; $i <= $arrayMonths[$date->copy()->format('n')] ; $i++): ?> 
                                                        <td style='border:1px solid black;width: 3%;font-size: 10px' class="text-center">
                                                            <?php echo $i?> 
                                                        </td> 
                                                     <?php endfor; ?>
                                            </tr>
                                            <tr>
                                                
                                                <?php for ($i=1; $i <= $arrayMonths[$date->copy()->format('n')] ; $i++): ?> 
                                                    <td style='border:1px solid black;width: 3%;font-size: 10px' class="text-center <?php echo $days[$date->copy()->format('n')][$i]?>">
                                                        <?php echo $days[$date->copy()->format('n')][$i]?> 
                                                    </td> 
                                                 <?php endfor; ?> 
                                            </tr>
                                       </thead>
                                       <tbody>
                                       
                                            <?php foreach ($roomscalendar as $room): ?>
                                                <tr>
                                                    <?php $date = $date->startOfMonth() ?>
                                                    <td><?php echo substr($room->nameRoom, 0,5)." " ?>      </td>
                                                        
                                                    <?php for ($i=01; $i <= $arrayMonths[$date->copy()->format('n')] ; $i++): ?> 

                                                            <?php if (isset($arrayReservas[$room->id][$date->copy()->format('Y')][$date->copy()->format('n')][$i])): ?>
                                                                <?php if ($arrayReservas[$room->id][$date->copy()->format('Y')][$date->copy()->format('n')][$i]->start == $date->copy()->format('Y-m-d')): ?>
                                                                        <td style='border:1px solid black;width: 3%'>
                                                                            <div style="width: 50%;float: left;">
                                                                                &nbsp;
                                                                            </div>
                                                                            <div class="<?php echo $book->getStatus($arrayReservas[$room->id][$date->copy()->format('Y')][$date->copy()->format('n')][$i]->type_book) ?> start" style="width: 50%;float: left;">
                                                                                &nbsp;
                                                                            </div>

                                                                        </td>    
                                                                <?php elseif($arrayReservas[$room->id][$date->copy()->format('Y')][$date->copy()->format('n')][$i]->finish == $date->copy()->format('Y-m-d')): ?>
                                                                        <td style='border:1px solid black;width: 3%'>
                                                                            <div class="<?php echo $book->getStatus($arrayReservas[$room->id][$date->copy()->format('Y')][$date->copy()->format('n')][$i]->type_book) ?> end" style="width: 50%;float: left;">
                                                                                &nbsp;
                                                                            </div>
                                                                            <div style="width: 50%;float: left;">
                                                                                &nbsp;
                                                                            </div>
                                                                            

                                                                        </td>
                                                                <?php else: ?>
                                                                    
                                                                        <td style='border:1px solid black;width: 3%' title="<?php echo $arrayReservas[$room->id][$date->copy()->format('Y')][$date->copy()->format('n')][$i]->customer['name'] ?>" class="<?php echo $book->getStatus($arrayReservas[$room->id][$date->copy()->format('Y')][$date->copy()->format('n')][$i]->type_book) ?>">

                                                                       <a href="{{url ('/admin/reservas/update')}}/<?php echo $arrayReservas[$room->id][$date->copy()->format('Y')][$date->copy()->format('n')][$i]->id ?>">
                                                                           <div style="width: 100%;height: 100%">
                                                                               &nbsp;
                                                                           </div>
                                                                       </a>

                                                                    </td>

                                                                <?php endif ?>
                                                            <?php else: ?>
                                                                <td class="<?php echo $days[$date->copy()->format('n')][$i]?>" style='border:1px solid black;width: 3%'>
                                                                    
                                                                </td>
                                                            <?php endif; ?>
                                                            <?php if ($date->copy()->format('d') != $arrayMonths[$date->copy()->format('n')]): ?>
                                                                <?php $date = $date->addDay(); ?>
                                                            <?php else: ?>
                                                                <?php $date = $date->startOfMonth() ?>
                                                            <?php endif ?>
                                                        
                                                    <?php endfor; ?> 
                                                </tr>
                                                
                                            <?php endforeach; ?>
                                       </tbody>
                                    </table>
                                    <?php $date = $date->addMonth(); ?>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane " id="tabTercera">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="fc-border-separate" style="border:1px solid black;width: 100%">
                                       <thead>
                                            <tr>
                                                <td class="text-center" colspan="<?php echo $arrayMonths[$date->copy()->format('n')]+1 ?>">
                                                    <?php echo  ucfirst($date->copy()->formatLocalized('%B %Y'))?>
                                                </td> 
                                            </tr>
                                            <tr>
                                                <td rowspan="2" style="width: 1%!important">Apto</td>
                                                    <?php for ($i=1; $i <= $arrayMonths[$date->copy()->format('n')] ; $i++): ?> 
                                                        <td style='border:1px solid black;width: 3%;font-size: 10px' class="text-center">
                                                            <?php echo $i?> 
                                                        </td> 
                                                     <?php endfor; ?>
                                            </tr>
                                            <tr>
                                                
                                                <?php for ($i=1; $i <= $arrayMonths[$date->copy()->format('n')] ; $i++): ?> 
                                                    <td style='border:1px solid black;width: 3%;font-size: 10px' class="text-center <?php echo $days[$date->copy()->format('n')][$i]?>">
                                                        <?php echo $days[$date->copy()->format('n')][$i]?> 
                                                    </td> 
                                                 <?php endfor; ?> 
                                            </tr>
                                       </thead>
                                       <tbody>
                                       
                                            <?php foreach ($roomscalendar as $room): ?>
                                                <tr>
                                                    <?php $date = $date->startOfMonth() ?>
                                                    <td><?php echo substr($room->nameRoom, 0,5)." " ?>      </td>
                                                        
                                                    <?php for ($i=01; $i <= $arrayMonths[$date->copy()->format('n')] ; $i++): ?> 

                                                            <?php if (isset($arrayReservas[$room->id][$date->copy()->format('Y')][$date->copy()->format('n')][$i])): ?>
                                                                <?php if ($arrayReservas[$room->id][$date->copy()->format('Y')][$date->copy()->format('n')][$i]->start == $date->copy()->format('Y-m-d')): ?>
                                                                        <td style='border:1px solid black;width: 3%'>
                                                                            <div style="width: 50%;float: left;">
                                                                                &nbsp;
                                                                            </div>
                                                                            <div class="<?php echo $book->getStatus($arrayReservas[$room->id][$date->copy()->format('Y')][$date->copy()->format('n')][$i]->type_book) ?> start" style="width: 50%;float: left;">
                                                                                &nbsp;
                                                                            </div>

                                                                        </td>    
                                                                <?php elseif($arrayReservas[$room->id][$date->copy()->format('Y')][$date->copy()->format('n')][$i]->finish == $date->copy()->format('Y-m-d')): ?>
                                                                        <td style='border:1px solid black;width: 3%'>
                                                                            <div class="<?php echo $book->getStatus($arrayReservas[$room->id][$date->copy()->format('Y')][$date->copy()->format('n')][$i]->type_book) ?> end" style="width: 50%;float: left;">
                                                                                &nbsp;
                                                                            </div>
                                                                            <div style="width: 50%;float: left;">
                                                                                &nbsp;
                                                                            </div>
                                                                            

                                                                        </td>
                                                                <?php else: ?>
                                                                    
                                                                        <td style='border:1px solid black;width: 3%' title="<?php echo $arrayReservas[$room->id][$date->copy()->format('Y')][$date->copy()->format('n')][$i]->customer['name'] ?>" class="<?php echo $book->getStatus($arrayReservas[$room->id][$date->copy()->format('Y')][$date->copy()->format('n')][$i]->type_book) ?>">

                                                                       <a href="{{url ('/admin/reservas/update')}}/<?php echo $arrayReservas[$room->id][$date->copy()->format('Y')][$date->copy()->format('n')][$i]->id ?>">
                                                                           <div style="width: 100%;height: 100%">
                                                                               &nbsp;
                                                                           </div>
                                                                       </a>

                                                                    </td>

                                                                <?php endif ?>
                                                            <?php else: ?>
                                                                <td class="<?php echo $days[$date->copy()->format('n')][$i]?>" style='border:1px solid black;width: 3%'>
                                                                    
                                                                </td>
                                                            <?php endif; ?>
                                                            <?php if ($date->copy()->format('d') != $arrayMonths[$date->copy()->format('n')]): ?>
                                                                <?php $date = $date->addDay(); ?>
                                                            <?php else: ?>
                                                                <?php $date = $date->startOfMonth() ?>
                                                            <?php endif ?>
                                                        
                                                    <?php endfor; ?> 
                                                </tr>
                                                
                                            <?php endforeach; ?>
                                       </tbody>
                                    </table>
                                    <?php $date = $date->addMonth(); ?>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane " id="tabCuarta">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="fc-border-separate" style="border:1px solid black;width: 100%">
                                       <thead>
                                            <tr>
                                                <td class="text-center" colspan="<?php echo $arrayMonths[$date->copy()->format('n')]+1 ?>">
                                                    <?php echo  ucfirst($date->copy()->formatLocalized('%B %Y'))?>
                                                </td> 
                                            </tr>
                                            <tr>
                                                <td rowspan="2" style="width: 1%!important">Apto</td>
                                                    <?php for ($i=1; $i <= $arrayMonths[$date->copy()->format('n')] ; $i++): ?> 
                                                        <td style='border:1px solid black;width: 3%;font-size: 10px' class="text-center">
                                                            <?php echo $i?> 
                                                        </td> 
                                                     <?php endfor; ?>
                                            </tr>
                                            <tr>
                                                
                                                <?php for ($i=1; $i <= $arrayMonths[$date->copy()->format('n')] ; $i++): ?> 
                                                    <td style='border:1px solid black;width: 3%;font-size: 10px' class="text-center <?php echo $days[$date->copy()->format('n')][$i]?>">
                                                        <?php echo $days[$date->copy()->format('n')][$i]?> 
                                                    </td> 
                                                 <?php endfor; ?> 
                                            </tr>
                                       </thead>
                                       <tbody>
                                       
                                            <?php foreach ($roomscalendar as $room): ?>
                                                <tr>
                                                    <?php $date = $date->startOfMonth() ?>
                                                    <td><?php echo substr($room->nameRoom, 0,5)." " ?>      </td>
                                                        
                                                    <?php for ($i=01; $i <= $arrayMonths[$date->copy()->format('n')] ; $i++): ?> 

                                                            <?php if (isset($arrayReservas[$room->id][$date->copy()->format('Y')][$date->copy()->format('n')][$i])): ?>
                                                                <?php if ($arrayReservas[$room->id][$date->copy()->format('Y')][$date->copy()->format('n')][$i]->start == $date->copy()->format('Y-m-d')): ?>
                                                                        <td style='border:1px solid black;width: 3%'>
                                                                            <div style="width: 50%;float: left;">
                                                                                &nbsp;
                                                                            </div>
                                                                            <div class="<?php echo $book->getStatus($arrayReservas[$room->id][$date->copy()->format('Y')][$date->copy()->format('n')][$i]->type_book) ?> start" style="width: 50%;float: left;">
                                                                                &nbsp;
                                                                            </div>

                                                                        </td>    
                                                                <?php elseif($arrayReservas[$room->id][$date->copy()->format('Y')][$date->copy()->format('n')][$i]->finish == $date->copy()->format('Y-m-d')): ?>
                                                                        <td style='border:1px solid black;width: 3%'>
                                                                            <div class="<?php echo $book->getStatus($arrayReservas[$room->id][$date->copy()->format('Y')][$date->copy()->format('n')][$i]->type_book) ?> end" style="width: 50%;float: left;">
                                                                                &nbsp;
                                                                            </div>
                                                                            <div style="width: 50%;float: left;">
                                                                                &nbsp;
                                                                            </div>
                                                                            

                                                                        </td>
                                                                <?php else: ?>
                                                                    
                                                                        <td style='border:1px solid black;width: 3%' title="<?php echo $arrayReservas[$room->id][$date->copy()->format('Y')][$date->copy()->format('n')][$i]->customer['name'] ?>" class="<?php echo $book->getStatus($arrayReservas[$room->id][$date->copy()->format('Y')][$date->copy()->format('n')][$i]->type_book) ?>">

                                                                       <a href="{{url ('/admin/reservas/update')}}/<?php echo $arrayReservas[$room->id][$date->copy()->format('Y')][$date->copy()->format('n')][$i]->id ?>">
                                                                           <div style="width: 100%;height: 100%">
                                                                               &nbsp;
                                                                           </div>
                                                                       </a>

                                                                    </td>

                                                                <?php endif ?>
                                                            <?php else: ?>
                                                                <td class="<?php echo $days[$date->copy()->format('n')][$i]?>" style='border:1px solid black;width: 3%'>
                                                                    
                                                                </td>
                                                            <?php endif; ?>
                                                            <?php if ($date->copy()->format('d') != $arrayMonths[$date->copy()->format('n')]): ?>
                                                                <?php $date = $date->addDay(); ?>
                                                            <?php else: ?>
                                                                <?php $date = $date->startOfMonth() ?>
                                                            <?php endif ?>
                                                        
                                                    <?php endfor; ?> 
                                                </tr>
                                                
                                            <?php endforeach; ?>
                                       </tbody>
                                    </table>
                                    <?php $date = $date->addMonth(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>    
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
                    alert(data);
                    // window.location.reload();
                });
            });
            /* $('#status, #room').change(function(event) { */
            // $('.status, .room').change(function(event) {
            //     var id = $(this).attr('data-id');
            //     /* var room = $('#room').val();*/
            //     console.log($(this).attr('class'));
            //     var clase = $(this).attr('class');

            //     if (clase == "status form-control") {
            //        var status = $(this).val();
            //     }else{
            //         var status = "";
            //     }if(clase == "room"){
            //         var room = $(this).val();
            //     }else{
            //         var room = "";
            //     }
            //     /* $.get('/admin/apartamentos/update/'+id, {  id: id, status:status, room:room }, function(data) { */
            //     // $.get('//admin/reservas/changeBook/'+id, {status:status,room: room}, function(data) {
            //     //     alert(data);
            //     //     window.location.reload();
            //     // });
            // });

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

            $('#newroom, .pax, .parking, .agencia').change(function(event){ 

                var room = $('#newroom').val();
                var pax = $('.pax').val();
                var park = $('.parking').val();
                var beneficio = 0;
                var costPark = 0;
                var pricePark = 0;
                var agencia = 0;
                $.get('/apartamentos/getPaxPerRooms/'+room).success(function( data ){
                    if (pax < data) {
                        $('.pax').attr('style' , 'background-color:red');
                        $('.book_comments').empty();
                        $('.book_comments').append('Van menos personas que el minimo, se le cobrara el minimo de la habitacion que son :'+data);
                    }else{
                        $('.book_comments').empty();
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
                                        agencia = $('.agencia').val();
                                        cost = (parseFloat(cost) + parseFloat(costPark) + parseFloat(agencia));
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

            $('#date').change(function(event) {
                
                var month = $(this).val();
                window.location = '/admin/reservas/'+month;
            });
            
        });

    </script>

@endsection