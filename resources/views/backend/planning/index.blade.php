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

    <?php use \Carbon\Carbon;  setlocale(LC_TIME, "ES"); setlocale(LC_TIME, "es_ES"); ?>
    
    <style type="text/css">

        .Reservado{
            background-color: green !important;
            color: black;
        }
        .Pagada-la-señal{
            background-color: red  !important;
            color: black;
        }
        .Bloqueado{
            background-color: #F9D975 !important;
            color: black;
        }
        .SubComunidad{
            background-color: rgba(138,125,190,1) !important;
            color: black;
        }
        .botones{
            padding-top: 0px!important;
            padding-bottom: 0px!important;
        }
        td{
            margin: 0px;
            padding: 0px!important;
            vertical-align: middle!important;
        }
        a {
            color: black;
            cursor: pointer;
        }
        .S, .D{
            background-color: rgba(0,0,0,0.2);
            color: red;
        }
        .active>a{
            color: white!important;
        }
        .bg-info-light>li>a{
            color: white;
        }
        .active.res{
            background-color: green !important; 
        }
        .active.bloq{
            background-color: #F9D975 !important; 
        }
        .active.pag{
            background-color: red !important; 
        }
        .res,.bloq,.pag{
            background-color: #626c75;
        }
        .nav-tabs > li > a:hover, .nav-tabs > li > a:focus{
            color: white!important;
        }
    </style>

@endsection
    
@section('content')

    
    <div class="container-fluid padding-10 sm-padding-10">
        <div class="row bg-white">
            <div class="col-md-6">
                <div class="col-md-4 col-xs-12">
                    <canvas id="barChart" style="width: 100%; height: 250px;"></canvas>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-5 m-b-10">
                        <div class="widget-8 panel no-border bg-success no-margin widget-loader-bar">
                            <div class="container-xs-height full-height">
                                <div class="row-xs-height">
                                    <div class="col-xs-height col-top">
                                        <div class="panel-heading top-left top-right">
                                            <div class="panel-title text-black hint-text">
                                                <span class="font-montserrat fs-11 all-caps">Ingresos de la temporada 
                                                    <?php if ($date->copy()->format('n') >= 9): ?>
                                                        <?php echo $date->copy()->format('Y') ?>-<?php echo $date->copy()->addYear()->format('Y') ?>
                                                    <?php else: ?>
                                                        <?php echo $date->copy()->subYear()->format('Y') ?>-<?php echo $date->copy()->format('Y') ?>
                                                    <?php endif ?>
                                                </span>
                                            </div>                                    
                                        </div>
                                    </div>
                                </div>
                                <div class="row-xs-height ">
                                    <div class="col-xs-height col-top relative">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="p-l-20">
                                                    <h3 class="no-margin p-b-5 text-white">
                                                        <?php if ($date->copy()->format('n') >= 9): ?>
                                                            <?php echo number_format($arrayTotales[$date->copy()->format('Y')],2,',','.') ?> €
                                                        <?php else: ?>
                                                            <?php echo number_format($arrayTotales[$date->copy()->subYear()->format('Y')],2,',','.') ?> €
                                                        <?php endif ?>                                            
                                                    </h3>
                                                    <p class="small hint-text m-t-5">
                                                        <span class="label  font-montserrat m-r-5">60%</span>Higher
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5 m-b-10">
                        <div class="widget-9 panel no-border bg-primary no-margin widget-loader-bar">
                            <div class="container-xs-height full-height">
                                <div class="row-xs-height">
                                    <div class="col-xs-height col-top">
                                        <div class="panel-heading  top-left top-right">
                                            <div class="panel-title text-black">
                                                <span class="font-montserrat fs-11 all-caps">Comparativa de la temporada 
                                                    <?php if ($date->copy()->format('n') >= 9): ?>
                                                        <?php echo $date->copy()->subYear()->format('Y') ?>-<?php echo $date->copy()->format('Y') ?>
                                                    <?php else: ?>
                                                        <?php echo $date->copy()->subYear(2)->format('Y') ?>-<?php echo $date->copy()->subYear()->format('Y') ?>
                                                    <?php endif ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row-xs-height">
                                    <div class="col-xs-height col-top">
                                        <div class="p-l-20 p-t-15">
                                            <h3 class="no-margin p-b-5 text-white">                                                
                                                    <?php if ($date->copy()->format('n') >= 9): ?>
                                                        <?php if (isset($arrayTotales[$date->copy()->subYear()->format('Y')])): ?>
                                                            <?php echo number_format($arrayTotales[$date->copy()->subYear()->format('Y')],2,',','.') ?> €
                                                        <?php else: ?>
                                                            No Hay reservas este año
                                                        <?php endif ?>
                                                    <?php else: ?>
                                                        <?php if (isset($arrayTotales[$date->copy()->subYear(2)->format('Y')])): ?>
                                                            <?php echo number_format($arrayTotales[$date->copy()->subYear(2)->format('Y')],2,',','.') ?> €
                                                        <?php else: ?>
                                                            No Hay reservas este año
                                                        <?php endif ?>
                                                    <?php endif ?> 
                                                
                                            </h3>
                                            <span class="small hint-text">
                                                <?php $totalcomparativa = 0; ?>
                                                <?php if ($date->copy()->format('n') >= 9): ?>
                                                    <?php if (isset($arrayTotales[$date->copy()->format('Y')]) && isset($arrayTotales[$date->copy()->subYear()->format('Y')]) ) : ?>
                                                        <?php $totalcomparativa = number_format(($arrayTotales[$date->copy()->format('Y')]-$arrayTotales[$date->copy()->subYear()->format('Y')])/$arrayTotales[$date->copy()->subYear()->format('Y')] *100,2) ?>
                                                        <?php echo $totalcomparativa ?>% 
                                                        <?php if ($totalcomparativa > 100): ?>
                                                            <i class="fa fa-arrow-up text-success fa-2x"></i>
                                                        <?php else: ?>
                                                            <i class="fa fa-arrow-down text-danger fa-2x"></i>
                                                        <?php endif ?>   
                                                    <?php else: ?>
                                                        No Hay reservas este año
                                                    <?php endif ?>
                                                <?php elseif (isset($arrayTotales[$date->copy()->subYear()->format('Y')]) && isset($arrayTotales[$date->copy()->subYear(2)->format('Y')]) ) : ?>
                                                    <?php $totalcomparativa = number_format(($arrayTotales[$date->copy()->subYear()->format('Y')]-$arrayTotales[$date->copy()->subYear(2)->format('Y')])/$arrayTotales[$date->copy()->subYear(2)->format('Y')] *100,2) ?>
                                                    <?php echo $totalcomparativa ?>% 
                                                    <?php if ($totalcomparativa > 100): ?>
                                                        <i class="fa fa-arrow-up text-success fa-2x"></i>
                                                    <?php else: ?>
                                                        <i class="fa fa-arrow-down text-danger fa-2x"></i>
                                                    <?php endif ?>   
                                                <?php endif ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row-xs-height">
                                    <div class="col-xs-height col-bottom">
                                        <div class="progress progress-small m-b-20">

                                        <div class="progress-bar progress-bar-white" style="width:<?php echo $totalcomparativa ?>%"></div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 text-center">
                <h2><b>Planning de reservas</b></h2>
                Fechas:
                <select id="date" >
                    <?php $fecha = $inicio->copy(); ?>

                    <?php for ($i=1; $i <= 4; $i++): ?>
                        <?php if( $date->copy()->format('Y') == $fecha->format('Y') ){ $selected = "selected"; }else{$selected = "";} ?>
                        <option value="<?php echo $fecha->copy()->format('Y'); ?>" <?php echo $selected ?>>
                            <?php echo $fecha->copy()->subYear()->format('Y')."-".$fecha->copy()->format('Y'); ?> 
                        </option>
                        <?php $fecha->addYear(); ?>
                    <?php endfor; ?>
                </select>
            </div>
            <br><br>
            <div class="col-md-7 col-xs-12">
                <div class="panel">
                    <ul class="nav nav-tabs nav-tabs-simple bg-info-light " role="tablist" data-init-reponsive-tabs="collapse">
                        <li style="background-color: white"><a href="#tabNueva" data-toggle="tab" role="tab" ><i class="fa fa-plus-circle fa-2x" style="color:green;background-color: white;border-radius: 10px" aria-hidden="true"></i></a>
                        </li>
                        <li class="active res" >
                            <a href="#tabPendientes" data-toggle="tab" role="tab">Pendientes 
                                <span class="badge"><?php echo count($arrayBooks["nuevas"]) ?></span>
                            </a>
                        </li>
                        <li class="bloq">
                            <a href="#tabEspeciales" data-toggle="tab" role="tab">Especiales
                                <span class="badge"><?php echo count($arrayBooks["especiales"]) ?></span>
                            </a>
                        </li>
                        <li class="pag">
                            <a href="#tabPagadas" data-toggle="tab" role="tab">Confirmadas 
                                <span class="badge"><?php echo count($arrayBooks["pagadas"]) ?></span>
                            </a>
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
                                                    <div class="col-md-1">
                                                        <label>Parking</label>
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
                                                    <div class="col-md-2">                                                        
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
                                                        <input type="text" class="form-control cost" name="cost" value="" disabled style="width: 100%;color: black">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label>Beneficio</label>
                                                        <input type="text" class="form-control beneficio" name="beneficio" value="" disabled style="width: 100%;color: black">
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
        
                                    <table class="table  demo-table-search table-responsive table-striped " id="tableWithSearch" >
                                        <thead>
                                            <tr>   
                                                <th class ="text-center Reservado text-white" style="width:20%!important">  Cliente     </th>
                                                <th class ="text-center Reservado text-white" style="width:10%">  Telefono     </th>
                                                <th class ="text-center Reservado text-white" style="width:2%">   Pax         </th>
                                                <th class ="text-center Reservado text-white" style="width:5%">   Apart       </th>
                                                <th class ="text-center Reservado text-white" style="width:5%!important">  IN     </th>
                                                <th class ="text-center Reservado text-white" style="width:5%!important">  OUT      </th>
                                                <th class ="text-center Reservado text-white" style="width:5%">   Noc         </th>
                                                <th class ="text-center Reservado text-white">                    Precio      </th>
                                                <th class ="text-center Reservado text-white" style="width:5%">   Estado      </th>
                                                <th class ="text-center Reservado text-white">                    A  </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($arrayBooks["nuevas"] as $book): ?>
                                                    <tr> 

                                                        <td class ="text-center">

                                                                <a class="update-book" data-id="<?php echo $book->id ?>"  title="Editar Reserva"  href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>"><?php echo $book->customer['name']  ?></a>                                                        
                                                        </td>

                                                        <td class ="text-center"> 
                                                            <?php if ($book->customer->phone != 0): ?>
                                                                <a href="tel:<?php echo $book->customer->phone ?>"><?php echo $book->customer->phone ?>
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
                                                                    <a class="btn btn-tag btn-primary sendJaime" title="Enviar Email a Jaime" data-id="<?php echo $book->id ?>"><i class=" pg-mail"></i></a>
                                                                    </a>
                                                                <?php else: ?>
                                                                    <a class="btn btn-tag btn-danger" title="enviado" disabled data-id="<?php echo $book->id ?>"><i class=" pg-mail "></i></a>
                                                                    </a>
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
                                    <table class="table table-hover demo-table-search table-responsive table-striped" id="tableWithSearch2" >
                                        <thead>
                                            <tr>
                                                <th class ="text-center Bloqueado text-white" style="width:10%">  Cliente     </th>
                                                <th class ="text-center Bloqueado text-white" style="width:5%">   Telefono    </th>
                                                <th class ="text-center Bloqueado text-white" style="width:2%">   Pax         </th>
                                                <th class ="text-center Bloqueado text-white" style="width:5%">   Apart       </th>
                                                <th class ="text-center Bloqueado text-white" style="width:11%!important">  IN     </th>
                                                <th class ="text-center Bloqueado text-white" style="width:11%!important">  OUT      </th>
                                                <th class ="text-center Bloqueado text-white" style="width:5%">   Noc         </th>
                                                <th class ="text-center Bloqueado text-white">                    Precio      </th>
                                                <th class ="text-center Bloqueado text-white" style="width:17%">   Estado      </th>
                                                <th class ="text-center Bloqueado text-white">                    Acciones    </th>
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

                                <table class="table table-hover demo-table-search table-responsive table-striped" id="tableWithSearch3" >
                                    <thead>
                                        <tr>   
                                            <th class ="text-center Pagada-la-señal text-white" style="width:5%"> Cobros </th>
                                            <th class ="text-center Pagada-la-señal text-white" style="width:5%">  Cliente     </th>
                                            <th class ="text-center Pagada-la-señal text-white" style="width:5%">  Telefono     </th>
                                            <th class ="text-center Pagada-la-señal text-white" style="width:2%">   Pax         </th>
                                            <th class ="text-center Pagada-la-señal text-white" style="width:5%">   Apart       </th>
                                            <th class ="text-center Pagada-la-señal text-white" style="width:20%!important">  IN     </th>
                                            <th class ="text-center Pagada-la-señal text-white" style="width:20%!important">  OUT      </th>
                                            <th class ="text-center Pagada-la-señal text-white" style="width:5%">   Noc         </th>
                                            <th class ="text-center Pagada-la-señal text-white" style="width:5%">   Precio      </th>
                                            <th class ="text-center Pagada-la-señal text-white" style="width:5%">   Estado      </th>
                                            <th class ="text-center Pagada-la-señal text-white" style="width:5%">   Acciones    </th>
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
                    <ul class="nav nav-tabs nav-tabs-simple bg-info-light" role="tablist" data-init-reponsive-tabs="collapse">
                        <?php $dateAux = $inicio->copy(); ?>
                        <?php for ($i=1; $i <= 9 ; $i++) :?>
                            <li <?php if($i == 1 ){ echo "class='active'";} ?>>
                                <a href="#tab<?php echo $i?>" data-toggle="tab" role="tab" style="padding:10px">
                                    <?php echo ucfirst($dateAux->copy()->formatLocalized('%b %y'))?>
                                </a>
                            </li>
                            <?php $dateAux->addMonth(); ?>
                        <?php endfor; ?>
                    </ul>
                    <div class="tab-content">
                        
                        <?php for ($z=1; $z <= 9; $z++):?>
                        <div class="tab-pane <?php if($z == 1){ echo 'active';} ?>" id="tab<?php echo $z ?>">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="fc-border-separate" style="width: 100%">
                                       <thead>
                                            <tr >
                                                <td class="text-center" colspan="<?php echo $arrayMonths[$date->copy()->format('n')]+1 ?>">
                                                    <?php echo  ucfirst($inicio->copy()->formatLocalized('%B %Y'))?>
                                                </td> 
                                            </tr>
                                            <tr>
                                                <td rowspan="2" style="width: 1%!important"></td>
                                                    <?php for ($i=1; $i <= $arrayMonths[$inicio->copy()->format('n')] ; $i++): ?> 
                                                        <td style='border:1px solid black;width: 3%;font-size: 10px' class="text-center">
                                                            <?php echo $i?> 
                                                        </td> 
                                                     <?php endfor; ?>
                                            </tr>
                                            <tr>
                                                
                                                <?php for ($i=1; $i <= $arrayMonths[$inicio->copy()->format('n')] ; $i++): ?> 
                                                    <td style='border:1px solid black;width: 3%;font-size: 10px' class="text-center <?php echo $days[$inicio->copy()->format('n')][$i]?>">
                                                        <?php echo $days[$inicio->copy()->format('n')][$i]?> 
                                                    </td> 
                                                 <?php endfor; ?> 
                                            </tr>
                                       </thead>
                                       <tbody>
                                       
                                            <?php foreach ($roomscalendar as $room): ?>
                                                <tr>
                                                    <?php $inicio = $inicio->startOfMonth() ?>
                                                    <td class="text-center"><b><?php echo substr($room->nameRoom, 0,5)?></b></td>
                                                        
                                                    <?php for ($i=01; $i <= $arrayMonths[$inicio->copy()->format('n')] ; $i++): ?> 

                                                            <?php if (isset($arrayReservas[$room->id][$inicio->copy()->format('Y')][$inicio->copy()->format('n')][$i])): ?>
                                                                <?php if ($arrayReservas[$room->id][$inicio->copy()->format('Y')][$inicio->copy()->format('n')][$i]->start == $inicio->copy()->format('Y-m-d')): ?>
                                                                        <td style='border:1px solid grey;width: 3%'>
                                                                            <div style="width: 50%;float: left;">
                                                                                &nbsp;
                                                                            </div>
                                                                            <div class="<?php echo $book->getStatus($arrayReservas[$room->id][$inicio->copy()->format('Y')][$inicio->copy()->format('n')][$i]->type_book) ?> start" style="width: 50%;float: left;">
                                                                                &nbsp;
                                                                            </div>

                                                                        </td>    
                                                                <?php elseif($arrayReservas[$room->id][$inicio->copy()->format('Y')][$inicio->copy()->format('n')][$i]->finish == $inicio->copy()->format('Y-m-d')): ?>
                                                                        <td style='border:1px solid grey;width: 3%'>
                                                                            <div class="<?php echo $book->getStatus($arrayReservas[$room->id][$inicio->copy()->format('Y')][$inicio->copy()->format('n')][$i]->type_book) ?> end" style="width: 50%;float: left;">
                                                                                &nbsp;
                                                                            </div>
                                                                            <div style="width: 50%;float: left;">
                                                                                &nbsp;
                                                                            </div>
                                                                            

                                                                        </td>
                                                                <?php else: ?>
                                                                    
                                                                        <td 
                                                                            style='border:1px solid grey;width: 3%' 
                                                                            title="
                                                                                <?php echo $arrayReservas[$room->id][$inicio->copy()->format('Y')][$inicio->copy()->format('n')][$i]->customer['name'] ?> 

                                                                                <?php echo 'PVP:'.$arrayReservas[$room->id][$inicio->copy()->format('Y')][$inicio->copy()->format('n')][$i]->total_price ?>
                                                                                <?php if (isset($payment[$arrayReservas[$room->id][$inicio->copy()->format('Y')][$inicio->copy()->format('n')][$i]->id])): ?>
                                                                                    <?php echo 'PEND:'.($arrayReservas[$room->id][$inicio->copy()->format('Y')][$inicio->copy()->format('n')][$i]->total_price - $payment[$arrayReservas[$room->id][$inicio->copy()->format('Y')][$inicio->copy()->format('n')][$i]->id])?>
                                                                                <?php else: ?>
                                                                                <?php endif ?>" 
                                                                                class="<?php echo $book->getStatus($arrayReservas[$room->id][$inicio->copy()->format('Y')][$inicio->copy()->format('n')][$i]->type_book) ?>"
                                                                        >

                                                                       <a href="{{url ('/admin/reservas/upinicio')}}/<?php echo $arrayReservas[$room->id][$inicio->copy()->format('Y')][$inicio->copy()->format('n')][$i]->id ?>">
                                                                           <div style="width: 100%;height: 100%">
                                                                               &nbsp;
                                                                           </div>
                                                                       </a>

                                                                    </td>

                                                                <?php endif ?>
                                                            <?php else: ?>
                                                                <td class="<?php echo $days[$inicio->copy()->format('n')][$i]?>" style='border:1px solid grey;width: 3%'>
                                                                    
                                                                </td>
                                                            <?php endif; ?>
                                                            <?php if ($inicio->copy()->format('d') != $arrayMonths[$inicio->copy()->format('n')]): ?>
                                                                <?php $inicio = $inicio->addDay(); ?>
                                                            <?php else: ?>
                                                                <?php $inicio = $inicio->startOfMonth() ?>
                                                            <?php endif ?>
                                                        
                                                    <?php endfor; ?> 
                                                </tr>
                                                
                                            <?php endforeach; ?>
                                       </tbody>
                                    </table>
                                    <?php $inicio = $inicio->addMonth(); ?>
                                </div>
                            </div>
                        </div>
                        <?php endfor; ?>
                        
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
                    // window.location.reload();
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

            $('#newroom, .pax, .parking, .agencia').change(function(event){ 

                var room = $('#newroom').val();
                var pax = $('.pax').val();
                var park = $('.parking').val();
                var beneficio = 0;
                var costPark = 0;
                var pricePark = 0;
                var agencia = 0;
                $.get('/admin/apartamentos/getPaxPerRooms/'+room).success(function( data ){
                    console.log(data);
                    console.log(pax);
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
                
                var year = $(this).val();
                window.location = '/admin/reservas/'+year;
            });
            
            $('.sendJaime').click(function(event) {
                var id = $(this).attr('data-id');
                console.log(id);
                $.get('/admin/reservas/sendJaime', {id: id}).success(function( data ) {
                    alert(data);
                });
            });



                var data = {
                    labels: [
                                <?php foreach ($arrayTotales as $key => $value): ?>
                                    <?php echo "'".$key."'," ?>
                                <?php endforeach ?>
                            ],
                    datasets: [
                        {
                            label: "Ingresos por Año",
                            backgroundColor: [
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                            ],
                            borderColor: [
                                'rgba(54, 162, 235, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(54, 162, 235, 1)',
                            ],
                            borderWidth: 1,
                            data: [
                                    <?php foreach ($arrayTotales as $key => $value): ?>
                                        <?php echo "'".$value."'," ?>
                                    <?php endforeach ?>
                                    ],
                        }
                    ]
                };

                var myBarChart = new Chart('barChart', {
                    type: 'bar',
                    data: data,
                });

        });

    </script>

@endsection