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
            background-color: orange !important;
            color: black;
        }
        .SubComunidad{
            background-color: rgba(0,100,255,0.5) !important;
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
            background-color: orange !important; 
        }
        .active.pag{
            background-color: red !important; 
        }
        .res,.bloq,.pag{
            background-color: rgba(98,108,117,0.5);
        }
        .nav-tabs > li > a:hover, .nav-tabs > li > a:focus{
            color: white!important;
        }

        .fechas > li.active{
            background-color: red;
        }
        .nav-tabs ~ .tab-content{
            padding: 0px;
        }
        .paginate_button.active>a{
            color: black!important;
        }
        .fa-arrow-down{color:red;}
        .fa-arrow-up{color:green;}
    </style>

@endsection
    
@section('content')

    <div class="container-fluid  sm-padding-10 p-l-10 p-r-10 p-t-5">
        <div class="row bg-white">
            <div class="col-md-2">
                <div class="col-md-12 col-xs-12">
                    <canvas id="barChart" style="width: 100%; height: 250px;"></canvas>
                </div>
            </div>
            <div class="col-md-2 m-t-50">
                <div class="col-md-12">
                    <table>
                        <thead>
                            <th colspan="2">
                                Ingresos de la temporada  <?php echo $inicio->copy()->format('Y') ?>-<?php echo $inicio->copy()->addYear()->format('Y') ?>                            
                            </th>
                        </thead>
                        <tr>
                            <td>Ventas Temporada</td>
                            <td>
                                <?php echo number_format($arrayTotales[$inicio->copy()->format('Y')],2,',','.') ?> €                            
                            </td>
                        </tr>
                        <tr>
                            <td>Pend Cobro</td>
                            <td><?php echo number_format($arrayTotales[$inicio->copy()->format('Y')]-$paymentSeason["total"],2,',','.')?></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-12 m-t-20">
                    <table>
                        <thead>
                            <th colspan="2">
                                Cobros de la temporada <?php echo $inicio->copy()->format('Y') ?>-<?php echo $inicio->copy()->addYear()->format('Y') ?>
                            </th>
                        </thead>
                        <tr>
                            <td>Cash</td>
                            <td><?php echo number_format($paymentSeason["cash"],2,',','.')?>€</td>
                        </tr>
                        <tr>
                            <td>Banco</td>
                            <td><?php echo number_format($paymentSeason["banco"],2,',','.')?>€</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="col-md-3 m-t-20">
                <div class="col-xs-12 not-padding push-20">
                    <?php if ($paymentSeason["total"] == 0): ?>
                        <h3>No hay pagos</h3>
                    <?php else: ?>
                    <div id="piechart" ></div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-5">
                <div class="row">
                    <div class="col-md-12 m-b-10">
                        <div class="widget-8 panel no-border bg-success no-margin widget-loader-bar" style="min-height: 300px;">
                            <div class="container-xs-height full-height">
                                <div class="row-xs-height">
                                    <div class="col-xs-height col-top">
                                        <div class="panel-heading top-left top-right">
                                            <div class="panel-title text-black hint-text">
                                                <span class="font-montserrat fs-11 all-caps">Ingresos de la temporada
                                                    <?php echo $inicio->copy()->format('Y') ?>-<?php echo $inicio->copy()->addYear()->format('Y') ?>
                                                </span>
                                            </div>                                    
                                        </div>
                                    </div>
                                </div>
                                <div class="row-xs-height ">
                                    <div class="col-xs-height col-top relative">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="p-l-20 p-r-20">
                                                    <table class="table table-hover demo-table-search table-responsive " >
                                                        <thead>
                                                            <th class="text-center bg-complete text-white">&nbsp;</th>
                                                            <th class="text-center bg-complete text-white">Nov/Dic</th>
                                                            <th class="text-center bg-complete text-white">Ene</th>
                                                            <th class="text-center bg-complete text-white">Feb</th>
                                                            <th class="text-center bg-complete text-white">Mar</th>
                                                            <th class="text-center bg-complete text-white">Abr/May</th>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td class="text-center">Ventas</td>
                                                                <td class="text-center"><?php echo number_format($ventas["Ventas"][12],2,',','.')?></td>
                                                                <td class="text-center"><?php echo number_format($ventas["Ventas"][1],2,',','.')?></td>
                                                                <td class="text-center"><?php echo number_format($ventas["Ventas"][2],2,',','.')?></td>
                                                                <td class="text-center"><?php echo number_format($ventas["Ventas"][3],2,',','.')?></td>
                                                                <td class="text-center"><?php echo number_format($ventas["Ventas"][4],2,',','.')?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-center">Benº</td>
                                                                <td class="text-center"><?php echo number_format($ventas["Ben"][12],2,',','.')?></td>
                                                                <td class="text-center"><?php echo number_format($ventas["Ben"][1],2,',','.')?></td>
                                                                <td class="text-center"><?php echo number_format($ventas["Ben"][2],2,',','.')?></td>
                                                                <td class="text-center"><?php echo number_format($ventas["Ben"][3],2,',','.')?></td>
                                                                <td class="text-center"><?php echo number_format($ventas["Ben"][4],2,',','.')?></td>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-center bg-complete text-white">
                                                                    <?php echo $inicio->copy()->subYear()->format('Y') ?>-<?php echo $inicio->copy()->format('Y') ?> </th>
                                                                    <th class="text-center bg-complete text-white">Nov/Dic</th>
                                                                    <th class="text-center bg-complete text-white">Ene</th>
                                                                    <th class="text-center bg-complete text-white">Feb</th>
                                                                    <th class="text-center bg-complete text-white">Mar</th>
                                                                    <th class="text-center bg-complete text-white">Abr/May</th>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-center">Ventas</td>
                                                                <td class="text-center"><?php echo number_format($ventasOld["Ventas"][12],2,',','.') ?></td>
                                                                <td class="text-center"><?php echo number_format($ventasOld["Ventas"][1],2,',','.') ?></td>
                                                                <td class="text-center"><?php echo number_format($ventasOld["Ventas"][2],2,',','.') ?></td>
                                                                <td class="text-center"><?php echo number_format($ventasOld["Ventas"][3],2,',','.') ?></td>
                                                                <td class="text-center"><?php echo number_format($ventasOld["Ventas"][4],2,',','.') ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-center">Benº</td>
                                                                <td class="text-center"><?php echo number_format($ventasOld["Ben"][12],2,',','.') ?></td>
                                                                <td class="text-center"><?php echo number_format($ventasOld["Ben"][1],2,',','.') ?></td>
                                                                <td class="text-center"><?php echo number_format($ventasOld["Ben"][2],2,',','.') ?></td>
                                                                <td class="text-center"><?php echo number_format($ventasOld["Ben"][3],2,',','.') ?></td>
                                                                <td class="text-center"><?php echo number_format($ventasOld["Ben"][4],2,',','.') ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-center bg-complete text-white">Comparativa Ventas</th>
                                                                <td class="text-center">
                                                                    <?php echo number_format($ventas["Ventas"][12]-$ventasOld["Ventas"][12],2,',','.');
                                                                        echo ($ventas["Ventas"][12]-$ventasOld["Ventas"][12] > 0) ? "<i class='fa fa-arrow-up' aria-hidden='true'></i>" : "<i class='fa fa-arrow-down' aria-hidden='true'></i>";
                                                                         ?>
                                                                </td>
                                                                <td class="text-center">
                                                                    <?php echo number_format($ventas["Ventas"][1]-$ventasOld["Ventas"][1],2,',','.');
                                                                        echo ($ventas["Ventas"][1]-$ventasOld["Ventas"][1] > 0) ? "<i class='fa fa-arrow-up' aria-hidden='true'></i>" : "<i class='fa fa-arrow-down' aria-hidden='true'></i>";
                                                                         ?>
                                                                </td>
                                                                <td class="text-center">
                                                                    <?php echo number_format($ventas["Ventas"][2]-$ventasOld["Ventas"][2],2,',','.');
                                                                        echo ($ventas["Ventas"][2]-$ventasOld["Ventas"][2] > 0) ? "<i class='fa fa-arrow-up' aria-hidden='true'></i>" : "<i class='fa fa-arrow-down' aria-hidden='true'></i>";
                                                                         ?>
                                                                </td>
                                                                <td class="text-center">
                                                                    <?php echo number_format($ventas["Ventas"][3]-$ventasOld["Ventas"][3],2,',','.');
                                                                        echo ($ventas["Ventas"][3]-$ventasOld["Ventas"][3] > 0) ? "<i class='fa fa-arrow-up' aria-hidden='true'></i>" : "<i class='fa fa-arrow-down' aria-hidden='true'></i>";
                                                                         ?>
                                                                </td>
                                                                <td class="text-center">
                                                                    <?php echo number_format($ventas["Ventas"][4]-$ventasOld["Ventas"][4],2,',','.');
                                                                        echo ($ventas["Ventas"][4]-$ventasOld["Ventas"][4] > 0) ? "<i class='fa fa-arrow-up' aria-hidden='true'></i>" : "<i class='fa fa-arrow-down' aria-hidden='true'></i>";
                                                                         ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-center bg-complete text-white">Comparativa Benº</th>
                                                                <td class="text-center">
                                                                    <?php echo number_format($ventas["Ben"][12]-$ventasOld["Ben"][12],2,',','.');
                                                                        echo ($ventas["Ben"][12]-$ventasOld["Ben"][12] > 0) ? "<i class='fa fa-arrow-up' aria-hidden='true'></i>" : "<i class='fa fa-arrow-down' aria-hidden='true'></i>";
                                                                         ?>
                                                                </td>
                                                                <td class="text-center">
                                                                    <?php echo number_format($ventas["Ben"][1]-$ventasOld["Ben"][1],2,',','.');
                                                                        echo ($ventas["Ben"][1]-$ventasOld["Ben"][1] > 0) ? "<i class='fa fa-arrow-up' aria-hidden='true'></i>" : "<i class='fa fa-arrow-down' aria-hidden='true'></i>";
                                                                         ?>
                                                                </td>
                                                                <td class="text-center">
                                                                    <?php echo number_format($ventas["Ben"][2]-$ventasOld["Ben"][2],2,',','.');
                                                                        echo ($ventas["Ben"][2]-$ventasOld["Ben"][2] > 0) ? "<i class='fa fa-arrow-up' aria-hidden='true'></i>" : "<i class='fa fa-arrow-down' aria-hidden='true'></i>";
                                                                         ?>
                                                                </td>
                                                                <td class="text-center">
                                                                    <?php echo number_format($ventas["Ben"][3]-$ventasOld["Ben"][3],2,',','.');
                                                                        echo ($ventas["Ben"][3]-$ventasOld["Ben"][3] > 0) ? "<i class='fa fa-arrow-up' aria-hidden='true'></i>" : "<i class='fa fa-arrow-down' aria-hidden='true'></i>";
                                                                         ?>
                                                                </td>
                                                                <td class="text-center">
                                                                    <?php echo number_format($ventas["Ben"][4]-$ventasOld["Ben"][4],2,',','.');
                                                                        echo ($ventas["Ben"][4]-$ventasOld["Ben"][4] > 0) ? "<i class='fa fa-arrow-up' aria-hidden='true'></i>" : "<i class='fa fa-arrow-down' aria-hidden='true'></i>";
                                                                         ?>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 text-center">
                <div class="col-md-6 col-md-offset-4">
                    <h2><b>Planning de reservas</b>  Fechas:
                        
                        
                        <select id="fecha" >
                            <?php $fecha = $inicio->copy()->SubYear(2); ?>
                            <?php if ($fecha->copy()->format('Y') < 2015): ?>
                                <?php $fecha = new Carbon('first day of September 2015'); ?>
                            <?php endif ?>
                        
                            <?php for ($i=1; $i <= 4; $i++): ?>                           
                                <option value="<?php echo $fecha->copy()->format('Y'); ?>" {{ $date->copy()->format('Y') == $fecha->format('Y') ? 'selected' : '' }}>
                                    <?php echo $fecha->copy()->format('Y')."-".$fecha->copy()->addYear()->format('Y'); ?> 
                                </option>
                                <?php $fecha->addYear(); ?>
                            <?php endfor; ?>
                        </select>
                    </h2>
                </div>
                
            </div>
            <br><br>
            <div class="col-md-7 col-xs-12">
                <div class="panel">
                    <ul class="nav nav-tabs nav-tabs-simple bg-info-light " role="tablist" data-init-reponsive-tabs="collapse">
                        <li class="" style="background-color: #889096;width: 50px" ><a href="#tabNueva" data-toggle="tab" role="tab" style="padding: 0px;"><img src="{{ asset('/img/miramarski/plus_icon.png') }}" width="50px!important" /></a>
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
                                <div class="col-md-10">
                                    <form role="form"  action="{{ url('/admin/reservas/create') }}" method="post">
                                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
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
                                            
                                            

                                                <div class="input-group col-md-12">
                                                    <div class="col-md-4">
                                                        <label>Entrada</label>
                                                        <div class="input-prepend input-group">
                                                          <span class="add-on input-group-addon"><i
                                                                        class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                                                          <input type="text" style="width: 100%" name="reservation" id="daterangepicker" class="form-control" value="" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <label>Noches</label>
                                                        <input type="text" class="form-control nigths" name="nigths" value="" style="width: 100%">
                                                    </div> 
                                                    <div class="col-md-2">
                                                        <label>Pax</label>
                                                        <input type="text" data-v-min="0" data-v-max="8" name="pax" class="autonumeric form-control full-width pax">
                                                            
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
                                                        <select class=" form-control full-width parking"  name="parking">
                                                            <?php for ($i=1; $i <= 4 ; $i++): ?>
                                                                <option value="<?php echo $i ?>"><?php echo $book->getParking($i) ?></option>
                                                            <?php endfor;?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label>Sup. Lujo</label>
                                                        <select class=" form-control full-width type_luxury" data-init-plugin="select2" name="type_luxury">
                                                            <?php for ($i=1; $i <= 4 ; $i++): ?>
                                                                <option value="<?php echo $i ?>"><?php echo $book->getSupLujo($i) ?></option>
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
                                                        <label>Agencia</label>
                                                        <select class=" form-control full-width agency" data-init-plugin="select2" name="agency">
                                                            <?php for ($i=0; $i <= 2 ; $i++): ?>
                                                                <?php if ($i == 0): ?>
                                                                    <option></option>
                                                                <?php else: ?>
                                                                    <option value="<?php echo $i ?>"><?php echo $book->getAgency($i) ?></option>
                                                                <?php endif ?>
                                                                
                                                            <?php endfor;?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2">                                                        
                                                        <label>Cost Agencia</label>
                                                        <input type="text" class="agencia form-control" name="agencia" value="0">
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
                                    </form>
                                </div>
                                <div class="col-md-2">

                                    <div class="col-md-12" style="padding: 0px">
                                        <div class="panel-heading">
                                            <div class="panel-title">
                                                Cotización
                                            </div>
                                        </div>
                                        <table>

                                            <tbody>
                                                <tr class="text-white" style="background-color: #0c685f">
                                                    <th style="padding-left: 5px">PVP</th>
                                                    <th style="padding-right: 5px;padding-left: 5px">
                                                        <input type="text" class="form-control total m-t-10 m-b-10 text-white" name="total" value="" style="width: 100%;background-color: #0c685f;border:none;font-weight: bold">
                                                    </th>
                                                </tr>
                                                <tr class=" text-white m-t-5" style="background-color: #99D9EA">
                                                    <th style="padding-left: 5px">COSTE</th>
                                                    <th style="padding-right: 5px;padding-left: 5px">
                                                        <input type="text" class="form-control cost m-t-10 m-b-10 text-white" name="cost" value="" disabled style="width: 100%;color: black;background: #99D9EA;border:none;font-weight: bold">
                                                    </th>
                                                </tr>
                                                <tr class="text-white m-t-5" style="background-color: #ff7f27">
                                                    <th style="padding-left: 5px">BENº</th>
                                                    <th style="padding-right: 5px;padding-left: 5px">
                                                        <div class="col-md-7 p-r-0 p-l-0">
                                                            <input type="text" class="form-control beneficio m-t-10 m-b-10 text-white" name="beneficio" value="" disabled style="width: 100%;color: black;background: #ff7f27;border:none;font-weight: bold">
                                                        </div>
                                                        <div class="col-md-2 m-t-5"><div class="m-t-10 m-l-10 beneficio-text">0%</div></div>
                                                        
                                                    </th>
                                                    
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
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
                                                <th class ="text-center Reservado text-white">A&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  </th>
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
                                                                
                                                               <!--  <?php if ($book->send == 0): ?>
                                                                    <a class="btn btn-tag btn-primary sendJaime" title="Enviar Email a Jaime" data-id="<?php echo $book->id ?>"><i class=" pg-mail"></i></a>
                                                                    </a>
                                                                <?php else: ?>
                                                                    <a class="btn btn-tag btn-danger" title="enviado" disabled data-id="<?php echo $book->id ?>"><i class=" pg-mail "></i></a>
                                                                    </a>
                                                                <?php endif ?> -->
                                                                <a href="{{ url('/admin/reservas/delete/')}}/<?php echo $book->id ?>" class="btn btn-tag btn-danger" type="button" data-toggle="tooltip" title="" data-original-title="Eliminar Reserva" onclick="return confirm('¿Quieres Eliminar la reserva?');">
                                                                    <i class="fa fa-trash"></i>
                                                                </a>
                                                                
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
                                                                
                                                                <!-- <?php if ($book->send == 0): ?>
                                                                    <a class="btn btn-tag btn-primary" ><i class=" pg-mail"></i></a>
                                                                    </a>
                                                                <?php else: ?>
                                                                <?php endif ?> -->
                                                                <a href="{{ url('/admin/reservas/delete/')}}/<?php echo $book->id ?>" class="btn btn-tag btn-danger" type="button" data-toggle="tooltip" title="" data-original-title="Eliminar Reserva" onclick="return confirm('¿Quieres Eliminar la reserva?');">
                                                                    <i class="fa fa-trash"></i>
                                                                </a>
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
                                            <th class ="text-center Pagada-la-señal text-white" style="width:5%">  Cliente     </th>
                                            <th class ="text-center Pagada-la-señal text-white" style="width:5%">  Telefono     </th>
                                            <th class ="text-center Pagada-la-señal text-white" style="width:2%">   Pax         </th>
                                            <th class ="text-center Pagada-la-señal text-white" style="width:5%">   Apart       </th>
                                            <th class ="text-center Pagada-la-señal text-white" style="width:20%!important">  IN     </th>
                                            <th class ="text-center Pagada-la-señal text-white" style="width:20%!important">  OUT      </th>
                                            <th class ="text-center Pagada-la-señal text-white" style="width:5%">   Noc         </th>
                                            <th class ="text-center Pagada-la-señal text-white" style="width:35%!important">Precio&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </th>
                                            <th class ="text-center Pagada-la-señal text-white" style="width:5%">   Estado      </th>
                                            <th class ="text-center Pagada-la-señal text-white" style="width:5%">   Acciones    </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($arrayBooks["pagadas"] as $book): ?>
                                                <tr>
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
                                                    <td class ="text-center">
                                                        <div class="col-md-6">
                                                            <?php echo $book->total_price."€" ?><br>
                                                            <?php if (isset($payment[$book->id])): ?>
                                                                <?php echo "<p style='color:red'>".$payment[$book->id]."</p>" ?>
                                                            <?php else: ?>
                                                            <?php endif ?>
                                                        </div>
                                                        <?php if (isset($payment[$book->id])): ?>
                                                            <?php if ($payment[$book->id] == 0): ?>
                                                                <div class="col-md-6 bg-primary m-t-10">
                                                                0%
                                                                </div>
                                                            <?php else:?>
                                                                <div class="col-md-6 bg-danger">
                                                                    <p class="text-white m-t-10"><?php echo number_format(100/($book->total_price/$payment[$book->id]),0).'%' ?></p>
                                                                </div> 
                                                                                                                           
                                                            <?php endif; ?>
                                                        <?php else: ?>
                                                            <div class="col-md-6 bg-primary">
                                                                0%
                                                                </div>
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
                                                            
                                                            <!-- <?php if ($book->send == 0): ?>
                                                                <a class="btn btn-tag btn-primary" ><i class=" pg-mail"></i></a>
                                                                </a>
                                                            <?php else: ?>
                                                            <?php endif ?> -->
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
                    <ul class="nav nav-tabs nav-tabs-simple bg-info-light fechas" role="tablist" data-init-reponsive-tabs="collapse">
                        <?php $dateAux = $inicio->copy(); ?>
                        <?php for ($i=1; $i <= 9 ; $i++) :?>
                            <li <?php if($i == 4 ){ echo "class='active'";} ?>>
                                <a href="#tab<?php echo $i?>" data-toggle="tab" role="tab" style="padding:10px">
                                    <?php echo ucfirst($dateAux->copy()->formatLocalized('%b %y'))?>
                                </a>
                            </li>
                            <?php $dateAux->addMonth(); ?>
                        <?php endfor; ?>
                    </ul>
                    <div class="tab-content">
                        
                        <?php for ($z=1; $z <= 9; $z++):?>
                        <div class="tab-pane <?php if($z == 4){ echo 'active';} ?>" id="tab<?php echo $z ?>">
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

                                                                       <a href="{{url ('/admin/reservas/update')}}/<?php echo $arrayReservas[$room->id][$inicio->copy()->format('Y')][$inicio->copy()->format('n')][$i]->id ?>">
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
    
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
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
                    window.location.reload();
                });
            });

            var start  = 0;
            var finish = 0;
            var noches = 0;
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

            $('#newroom, .pax, .parking, .agencia, .type_luxury').change(function(event){ 

                var room = $('#newroom').val();
                var pax = $('.pax').val();
                var park = $('.parking').val();
                var lujo = $('.type_luxury').val();
                var beneficio = 0;
                var costPark = 0;
                var pricePark = 0;
                var costLujo = 0;
                var priceLujo = 0;
                var agencia = 0;
                var beneficio_ = 0;

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
                        $('.book_comments').empty();
                        $('.book_comments').append('Van menos personas que el minimo, se le cobrara el minimo de la habitacion que son :'+data);
                    }else{
                        $('.book_comments').empty();
                        $('.pax').removeAttr('style');
                    }
                });

                $.get('/admin/reservas/getPricePark', {park: park, noches: noches}).success(function( data ) {
                    pricePark = data;
                    $.get('/admin/reservas/getPriceLujoAdmin', {lujo: lujo}).success(function( data ) {
                        priceLujo = data;

                        $.get('/admin/reservas/getPriceBook', {start: start, finish: finish, pax: pax, room: room, park: park}).success(function( data ) {
                            price = data.toLocaleString();
                            
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

            $('#fecha').change(function(event) {
                
                var year = $(this).val();
                window.location = '/admin/reservas/'+year;
            });
            
            $('.sendJaime').click(function(event) {
                var id = $(this).attr('data-id');
                console.log(id);
                $.get('/admin/reservas/sendJaime', {id: id}).success(function( data ) {

                });
            });

            nv.addGraph(function() {
                var chart = nv.models.multiBarChart()
                  .transitionDuration(350)
                  .reduceXTicks(true)   //If 'false', every single x-axis tick label will be rendered.
                  .rotateLabels(0)      //Angle to rotate x-axis labels.
                  .showControls(true)   //Allow user to switch between 'Grouped' and 'Stacked' mode.
                  .groupSpacing(0.1)    //Distance between each group of bars.
                ;

                chart.xAxis
                    .tickFormat(d3.format(',f'));

                chart.yAxis
                    .tickFormat(d3.format(',.1f'));

                d3.select('#chart1 svg')
                    .datum(exampleData())
                    .call(chart);

                nv.utils.windowResize(chart.update);

                return chart;
            });

            //Generate some nice data.
            function exampleData() {
              return stream_layers(3,10+Math.random()*100,.1).map(function(data, i) {
                return {
                  key: 'Stream #' + i,
                  values: data
                };
              });
            }



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
                                <?php foreach ($arrayTotales as $key => $value): ?>
                                    'rgba(54, 162, 235, 0.2)',
                                <?php endforeach ?>
                            ],
                            borderColor: [
                                <?php foreach ($arrayTotales as $key => $value): ?>
                                    'rgba(54, 162, 235, 1)',
                                <?php endforeach ?>
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
                    type: 'line',
                    data: data,
                });

                google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(drawChart);

                    function drawChart() {

                      var data = google.visualization.arrayToDataTable([
                        ['Tipo', 'Cantidad'],
                        ['Cash',     <?php echo $paymentSeason["cash"] ?>],
                        ['Banco',    <?php echo $paymentSeason["banco"] ?>]
                      ]);

                      var options = {
                        title: 'Metodos de pago '
                      };

                      var chart = new google.visualization.PieChart(document.getElementById('piechart'));

                      chart.draw(data, options);
                    }



        });
            jQuery(document).on('ready', function () {
            jQuery('.mybar_in_view').waypoint(function() {
            jQuery(this).yjsgroundprogress();
            }, {
            offset: '100%',
            triggerOnce:true
            });
            });
    </script>

@endsection