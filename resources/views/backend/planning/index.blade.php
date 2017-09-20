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

    <?php use \Carbon\Carbon;  setlocale(LC_TIME, "ES"); setlocale(LC_TIME, "es_ES"); ?>
    
    <style type="text/css">

        .Reservado-table{
            background-color: #295d9b !important;
            color: black;
        }
        .Reservado{
            background-color: rgba(0,100,255,0.2)  !important;
            color: black;
        }
        .Pagada-la-señal{
            background-color: green  !important;
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
            background-color: #295d9b !important; 
        }
        .active.bloq{
            background-color: orange !important; 
        }
        .active.pag{
            background-color: green !important; 
        }
        .res,.bloq,.pag{
            background-color: rgba(98,108,117,0.5);
        }
        .nav-tabs > li > a:hover, .nav-tabs > li > a:focus{
            color: white!important;
        }

        .fechas > li.active{
            background-color: rgb(81,81,81);
        }
        .nav-tabs ~ .tab-content{
            padding: 0px;
        }
        .paginate_button.active>a{
            color: black!important;
        }
        .fa-arrow-down{color:red;}
        .fa-arrow-up{color:green;}

        hr.reserva {border: 0; height: 4px; margin-top: 20px;background:black; text-align: center;}
        hr.reserva:after {content:"Datos de la Reserva"; position: relative; top: -12px; display: inline-block; width: 160px; height: 24px; padding: 0;border: 2px solid black; border-radius: 24px; background: black; color: white; font-size: 12px; line-height: 24px; }

        hr.cliente {border: 0; height: 4px; margin-top: 20px;background:black; text-align: center;}
        hr.cliente:after {content:"Datos del Cliente"; position: relative; top: -12px; display: inline-block; width: 150px; height: 24px; padding: 0;border: 2px solid black; border-radius: 24px; background: black; color: white; font-size: 12px; line-height: 24px; }
        
        hr.cotizacion {border: 0; height: 4px; margin-top: 20px;background:black; text-align: center;}
        hr.cotizacion:after {content:"Cotizacion"; position: relative; top: -12px; display: inline-block; width: 86px; height: 24px; padding: 0;border: 2px solid black; border-radius: 24px; background: black; color: white; font-size: 12px; line-height: 24px; }


        .ingresos_temp{
            background: #99bce7!important;
            color: white!important;
            text-align: center;
        }
        .cobros_temp{
            background: #2dcdaf!important;
            color: white!important;
            text-align: center;
        }
        .table.table-hover tbody tr:hover td {
            background: #99bce7 !important;
        }
    </style>

@endsection
    
@section('content')

    <div class="container-fluid  sm-padding-10 p-l-10 p-r-10 p-t-5">
        <div class="row bg-white">
            <div class="col-md-2 m-t-20">
                <div class="col-md-12 col-xs-12">
                    <canvas id="barChart" style="width: 100%; height: 250px;"></canvas>
                </div>
            </div>
            <div class="col-md-2 m-t-20">
                <div class="col-md-12">
                    <table class="table table-hover demo-table-search table-responsive " style="min-height: 137px" >
                        <thead>
                            <th class="ingresos_temp"  colspan="2">Ingresos Temporada <?php echo $inicio->copy()->format('Y') ?>-<?php echo $inicio->copy()->addYear()->format('Y') ?></th>
                        </thead>
                        <tr>
                            <td class="ingresos_temp">Ventas Temporada</td>
                            <td class="ingresos_temp"> <?php echo number_format($arrayTotales[$inicio->copy()->format('Y')],2,',','.') ?> € </td>
                        </tr>
                        <tr>
                            <td class="ingresos_temp">Total Cobrado</td>
                            <td class="ingresos_temp"><?php echo ($paymentSeason["total"]) ? number_format($paymentSeason["total"],2,',','.') : "---" ?></td>
                        </tr>
                        <thead>
                            <th class="ingresos_temp">Pend Cobro</th>
                            <th class="ingresos_temp"><?php echo number_format($arrayTotales[$inicio->copy()->format('Y')]-$paymentSeason["total"],2,',','.')?></th>
                        </thead>
                    </table>
                    <div class="col-xs-12 not-padding push-20">
                        <canvas id="pie-ing" ></canvas>
                    </div>
                </div>
                
            </div>
            <div class="col-md-2 m-t-20">
                <div class="col-md-12">
                    <table class="table table-hover demo-table-search table-responsive " >
                        <thead>
                            <th class="cobros_temp"  colspan="2">Cobros temporada <?php echo $inicio->copy()->format('Y') ?>-<?php echo $inicio->copy()->addYear()->format('Y') ?></th>
                        </thead>
                        <tr>
                            <td class="cobros_temp">Banco</td>
                            <td class="cobros_temp"><?php echo number_format($paymentSeason["banco"],2,',','.')?>€</td>
                            
                        </tr>
                        <tr>
                            <td class="cobros_temp">Cash</td>
                            <td class="cobros_temp"><?php echo number_format($paymentSeason["cash"],2,',','.')?>€</td>
                        </tr>
                        <thead>
                            <th class="cobros_temp">Total Cobrado</th>
                            <th class="cobros_temp"> <?php echo number_format($paymentSeason["banco"]+$paymentSeason["cash"],2,',','.') ?> € </th>
                        </thead>
                    </table>
                </div>
                <div class="col-xs-12 not-padding push-20">
                    <canvas id="pie-cob" style="max-height: 245px!important;max-width: 245px!important" ></canvas>
                </div>
            </div>
            <div class="col-md-6 m-t-20">
                <div class="row">
                    <div>
                        <table class="table table-hover demo-table-search table-responsive " >
                            <thead>
                                <th class="text-center bg-complete text-white" colspan="7">Ingresos de la temporada <?php echo $inicio->copy()->format('Y') ?>-<?php echo $inicio->copy()->addYear()->format('Y') ?></th>
                            </thead>
                            <thead>
                                <th class="text-center bg-complete text-white">&nbsp;</th>
                                <th class="text-center bg-complete text-white">Nov/Dic</th>
                                <th class="text-center bg-complete text-white">Ene</th>
                                <th class="text-center bg-complete text-white">Feb</th>
                                <th class="text-center bg-complete text-white">Mar</th>
                                <th class="text-center bg-complete text-white">Abr/May</th>
                                <th class="text-center bg-complete text-white">Total&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <th class="text-center p-t-5 p-b-5">Ventas</th>
                                    <td class="text-center p-t-5 p-b-5"><?php echo number_format($ventas["Ventas"][12],2,',','.')?></td>
                                    <td class="text-center p-t-5 p-b-5"><?php echo number_format($ventas["Ventas"][1],2,',','.')?></td>
                                    <td class="text-center p-t-5 p-b-5"><?php echo number_format($ventas["Ventas"][2],2,',','.')?></td>
                                    <td class="text-center p-t-5 p-b-5"><?php echo number_format($ventas["Ventas"][3],2,',','.')?></td>
                                    <td class="text-center p-t-5 p-b-5"><?php echo number_format($ventas["Ventas"][4],2,',','.')?></td>
                                    <td class="text-center p-t-5 p-b-5"><?php echo number_format(array_sum($ventas["Ventas"]),2,',','.')?></td>
                                </tr>
                                <tr>
                                    <th class="text-center p-t-5 p-b-5">Benº</th>
                                    <td class="text-center p-t-5 p-b-5"><?php echo number_format($ventas["Ben"][12],2,',','.')?></td>
                                    <td class="text-center p-t-5 p-b-5"><?php echo number_format($ventas["Ben"][1],2,',','.')?></td>
                                    <td class="text-center p-t-5 p-b-5"><?php echo number_format($ventas["Ben"][2],2,',','.')?></td>
                                    <td class="text-center p-t-5 p-b-5"><?php echo number_format($ventas["Ben"][3],2,',','.')?></td>
                                    <td class="text-center p-t-5 p-b-5"><?php echo number_format($ventas["Ben"][4],2,',','.')?></td>
                                    <td class="text-center p-t-5 p-b-5"><?php echo number_format(array_sum($ventas["Ben"]),2,',','.')?></td>
                                </tr>
                                <thead>
                                    <th colspan="7" class="ingresos_temp">Ingresos de la temporada <?php echo $inicio->copy()->subYear()->format('Y') ?>-<?php echo $inicio->copy()->format('Y') ?> </th>
                                </thead>
                                <tr>
                                    <th class="text-center ingresos_temp text-white"></th>
                                    <th class="text-center ingresos_temp text-white">Nov/Dic</th>
                                    <th class="text-center ingresos_temp text-white">Ene</th>
                                    <th class="text-center ingresos_temp text-white">Feb</th>
                                    <th class="text-center ingresos_temp text-white">Mar</th>
                                    <th class="text-center ingresos_temp text-white">Abr/May</th>
                                    <th class="text-center ingresos_temp text-white">Total&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                </tr>
                                <tr>
                                    <th class="text-center">Ventas</th>
                                    <td class="text-center"><?php echo number_format($ventasOld["Ventas"][12],2,',','.') ?></td>
                                    <td class="text-center"><?php echo number_format($ventasOld["Ventas"][1],2,',','.') ?></td>
                                    <td class="text-center"><?php echo number_format($ventasOld["Ventas"][2],2,',','.') ?></td>
                                    <td class="text-center"><?php echo number_format($ventasOld["Ventas"][3],2,',','.') ?></td>
                                    <td class="text-center"><?php echo number_format($ventasOld["Ventas"][4],2,',','.') ?></td>
                                    <td class="text-center"><?php echo number_format(array_sum($ventasOld["Ventas"]),2,',','.') ?></td>
                                </tr>
                                <tr>
                                    <th class="text-center">Benº</th>
                                    <td class="text-center"><?php echo number_format($ventasOld["Ben"][12],2,',','.') ?></td>
                                    <td class="text-center"><?php echo number_format($ventasOld["Ben"][1],2,',','.') ?></td>
                                    <td class="text-center"><?php echo number_format($ventasOld["Ben"][2],2,',','.') ?></td>
                                    <td class="text-center"><?php echo number_format($ventasOld["Ben"][3],2,',','.') ?></td>
                                    <td class="text-center"><?php echo number_format($ventasOld["Ben"][4],2,',','.') ?></td>
                                    <td class="text-center"><?php echo number_format(array_sum($ventasOld["Ben"]),2,',','.') ?></td>
                                </tr>
                                <thead>
                                    <th colspan="7" class="comparativa bg-primary text-center text-white" >Comparativa de la temporada <?php echo $inicio->copy()->subYear()->format('Y') ?>-<?php echo $inicio->copy()->format('Y') ?> </th>
                                </thead>
                                <tr>
                                    <th class="text-center  bg-primary text-white"></th>
                                    <th class="text-center  bg-primary text-white">Nov/Dic</th>
                                    <th class="text-center  bg-primary text-white">Ene</th>
                                    <th class="text-center  bg-primary text-white">Feb</th>
                                    <th class="text-center  bg-primary text-white">Mar</th>
                                    <th class="text-center  bg-primary text-white">Abr/May</th>
                                    <th class="text-center  bg-primary text-white">Total&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                </tr>
                                <tr>
                                    <th class="text-center">Comp. Ventas</th>
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
                                    <td class="text-center">
                                        <?php echo number_format(array_sum($ventas["Ventas"])-array_sum($ventasOld["Ventas"]),2,',','.');
                                            echo (array_sum($ventas["Ventas"])-array_sum($ventasOld["Ventas"]) > 0) ? "<i class='fa fa-arrow-up' aria-hidden='true'></i>" : "<i class='fa fa-arrow-down' aria-hidden='true'></i>";
                                             ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-center">Comp. Benº</th>
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
                                    <td class="text-center">
                                        <?php echo number_format(array_sum($ventas["Ben"])-array_sum($ventasOld["Ben"]),2,',','.');
                                            echo (array_sum($ventas["Ben"])-array_sum($ventasOld["Ben"]) > 0) ? "<i class='fa fa-arrow-up' aria-hidden='true'></i>" : "<i class='fa fa-arrow-down' aria-hidden='true'></i>";
                                             ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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
                        
                            <?php for ($i=1; $i <= 3; $i++): ?>                           
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
                                            <div class="panel-title col-md-12">
                                                <hr class="cliente">
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
                                        <div class="panel-heading p-t-0">
                                            <div class="panel-title col-md-12">
                                                <hr class="reserva">
                                            </div>
                                        </div>

                                        <div class="panel-body">
                                            
                                            

                                                <div class="input-group col-md-12">
                                                    <div class="col-md-4">
                                                        <label>Entrada</label>
                                                        <div class="input-prepend input-group">
                                                          <span class="add-on input-group-addon"><i
                                                                        class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                                                          <input type="text" class="sm-form-control daterange1" id="fechas" name="fechas" required="" style="cursor: pointer; text-align: center; backface-visibility: hidden;min-height: 28px;   " readonly="">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <label>Noches</label>
                                                        <input type="text" class="form-control nigths" name="nigths" value="" style="width: 100%;display:none">
                                                        <input type="text" class="form-control nigths" name="noches" value="" disabled style="width: 100%">
                                                    </div> 
                                                    <div class="col-md-1">
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
                                                    <div class="col-md-2">
                                                        <label>Parking</label>
                                                        <select class=" form-control full-width parking"  data-init-plugin="select2" name="parking">
                                                            <?php for ($i=1; $i <= 4 ; $i++): ?>
                                                                <option value="<?php echo $i ?>"><?php echo $book->getParking($i) ?></option>
                                                            <?php endfor;?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label>Sup. Lujo</label>
                                                        <select class=" form-control full-width type_luxury" data-init-plugin="select2" name="type_luxury">
                                                            <?php for ($i=1; $i <= 4 ; $i++): ?>
                                                                <option class="luxury-<?php echo $i ?>" value="<?php echo $i ?>"><?php echo $book->getSupLujo($i) ?></option>
                                                            <?php endfor;?>
                                                        </select>
                                                    </div>                                                    
                                                </div>
                                                <div class="input-group col-md-12 m-t-20">
                                                    <div class="col-md-2">
                                                        <label>Extras</label>
                                                        <select class="full-width select2-hidden-accessible" data-init-plugin="select2" multiple="" name="extras[]" tabindex="-1" aria-hidden="true">
                                                            <?php foreach ($extras as $extra): ?>
                                                                <option value="<?php echo $extra->id ?>"><?php echo $extra->name ?></option>
                                                            <?php endforeach ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2">                                                        
                                                        <label>Cost Agencia</label>
                                                        <input type="text" class="agencia form-control" name="agencia" value="0">
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
                                                    
                                                    
                                                    
                                                </div>
                                                <br>
                                                <div class="input-group col-md-12">
                                                    <div class="col-md-6">
                                                        <label>Comentarios Cliente</label>
                                                        <textarea class="form-control" name="comments" rows="5" style="width: 80%">
                                                        </textarea>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>Comentarios Internos</label>
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
                                        <div class="panel">
                                            <div class="panel-heading">
                                                <div class="panel-title col-md-12">
                                                    <hr class="cotizacion">
                                                </div>
                                            </div>
                                        </div>
                                        <table>

                                            <tbody>
                                                <tr class="text-white" style="background-color: #0c685f">
                                                    <th style="padding-left: 5px">PVP</th>
                                                    <th style="padding-right: 5px;padding-left: 5px">
                                                        <input type="text" class="form-control total m-t-10 m-b-10 text-white" name="total" value="" style="width: 100%;background-color: #0c685f;border:none;font-weight: bold;font-size: 17px">
                                                    </th>
                                                </tr>
                                                <tr class=" text-white m-t-5" style="background-color: #99D9EA">
                                                    <th style="padding-left: 5px">COSTE</th>
                                                    <th style="padding-right: 5px;padding-left: 5px">
                                                        <input type="text" class="form-control cost m-t-10 m-b-10 text-white" name="cost" value="" disabled style="width: 100%;color: black;background: #99D9EA;border:none;font-weight: bold;font-size: 17px">
                                                    </th>
                                                </tr>
                                                <tr class="text-white m-t-5" style="background-color: #ff7f27">
                                                    <th style="padding-left: 5px">BENº</th>
                                                    <th style="padding-right: 5px;padding-left: 5px">
                                                        <div class="col-md-7 p-r-0 p-l-0">
                                                            <input type="text" class="form-control beneficio m-t-10 m-b-10 text-white" name="beneficio" value="" disabled style="width: 100%;color: black;background: #ff7f27;border:none;font-weight: bold;font-size: 17px">
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
                                        <input type="text" id="searchPendientes" class="form-control pull-right" placeholder="Buscar">
                                    </div>
                                </div>
                            
                                    <div class="clearfix"></div>
        
                                    <table class="table  demo-table-search table-responsive table-striped " id="tablePendientes" >
                                        <thead>
                                            <tr>  
                                                <th style="display: none">ID</th> 
                                                <th class ="text-center Reservado-table text-white" >   Cliente     </th>
                                                <th class ="text-center Reservado-table text-white" >   Telefono     </th>
                                                <th class ="text-center Reservado-table text-white" >   Pax         </th>
                                                <th class ="text-center Reservado-table text-white" >   Apart       </th>
                                                <th class ="text-center Reservado-table text-white" >   IN     </th>
                                                <th class ="text-center Reservado-table text-white" >   OUT      </th>
                                                <th class ="text-center Reservado-table text-white" >   Noc         </th>
                                                <th class ="text-center Reservado-table text-white" >   Precio      </th>
                                                <th class ="text-center Reservado-table text-white" >   Estado      </th>
                                                <th class ="text-center Reservado-table text-white">A&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($arrayBooks["nuevas"] as $book): ?>
                                                    <tr> 
                                                        <td style="display: none"><?php echo $book->id ?></td>
                                                        <td class ="text-center" >

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

                                                        <td class ="text-center" >
                                                            <select class="status form-control" data-id="<?php echo $book->id ?>" <?php echo ($book->type_book == 1) ? "style='background:rgba(0,100,255,0.2) !important'":""; ?>>

                                                                <?php for ($i=1; $i < 9; $i++): ?> 
                                                                    <option <?php echo $i == ($book->type_book) ? "selected" : ""; ?> 
                                                                            <?php echo ($i  == 1 || $i == 5) ? "style='font-weight:bold'" : "" ?>
                                                                            value="<?php echo $i ?>"  data-id="<?php echo $book->id ?>">
                                                                            <?php echo $book->getStatus($i) ?></option>                                    
                                                                     
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
                                                            <div class="col-md-6">
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
                                                                    <option <?php echo $i == ($book->type_book) ? "selected" : ""; ?> 
                                                                            <?php echo ($i  == 1 || $i == 5) ? "style='font-weight:bold'" : "" ?>
                                                                            value="<?php echo $i ?>"  data-id="<?php echo $book->id ?>">
                                                                            <?php echo $book->getStatus($i) ?></option>                                    
                                                                     
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
                                            <th class ="text-center Pagada-la-señal text-white" style="width:25%!important">   Apart&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;       </th>
                                            <th class ="text-center Pagada-la-señal text-white" style="width:50px!important">  IN     </th>
                                            <th class ="text-center Pagada-la-señal text-white" style="width:50px!important">  OUT      </th>
                                            <th class ="text-center Pagada-la-señal text-white" style="width:5%">   Noc         </th>
                                            <th class ="text-center Pagada-la-señal text-white" style="width:35%!important">Precio&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                            <th class ="text-center Pagada-la-señal text-white" style="width:5%">   Estado&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;      </th>
                                            <!-- <th class ="text-center Pagada-la-señal text-white" style="width:5%">   Acciones    </th> -->
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
                                                    <td class ="text-center">
                                                        <div class="col-md-5 m-r-15">
                                                            <?php echo $book->total_price."€" ?><br>
                                                            <?php if (isset($payment[$book->id])): ?>
                                                                <?php echo "<p style='color:red'>".$payment[$book->id]."</p>" ?>
                                                            <?php else: ?>
                                                            <?php endif ?>
                                                        </div>
                                                        <?php if (isset($payment[$book->id])): ?>
                                                            <?php if ($payment[$book->id] == 0): ?>
                                                                <div class="col-md-5 bg-primary m-t-10 m-r-15">
                                                                0%
                                                                </div>
                                                            <?php else:?>
                                                                <div class="col-md-5 bg-danger m-r-15">
                                                                    <p class="text-white m-t-10"><?php echo number_format(100/($book->total_price/$payment[$book->id]),0).'%' ?></p>
                                                                </div> 
                                                                                                                           
                                                            <?php endif; ?>
                                                        <?php else: ?>
                                                            <div class="col-md-5 bg-success">
                                                                0%
                                                                </div>
                                                        <?php endif ?>
                                                                        
                                                    </td>
                                                    <td class ="text-center">
                                                        <select class="status form-control" data-id="<?php echo $book->id ?>" >
                                                            <?php for ($i=1; $i < 9; $i++): ?> 
                                                                <option <?php echo $i == ($book->type_book) ? "selected" : ""; ?> 
                                                                        <?php echo ($i  == 1 || $i == 5) ? "style='font-weight:bold'" : "" ?>
                                                                        value="<?php echo $i ?>"  data-id="<?php echo $book->id ?>">
                                                                        <?php echo $book->getStatus($i) ?></option>                                    
                                                                 
                                                            <?php endfor; ?>
                                                        </select>
                                                    </td>
                                                    <!-- <td> -->
                                                        <!-- <div class="btn-group col-md-6"> -->
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
                                                    <!-- </td> -->
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
  <div class="modal-dialog modal-md">
    <div class="modal-content-wrapper">
        <div class="modal-content">
            
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
    <script type="text/javascript" src="//assets/plugins/jquery-autonumeric/autoNumeric.js"></script>
    

        
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

            var start  = 0;
            var finish = 0;
            var noches = 0;
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
                        $('.book_comments').empty();
                        $('.book_comments').append('Van menos personas que el minimo, se le cobrara el minimo de la habitacion que son :'+data);
                    }else{
                        $('.book_comments').empty();
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

            //Generate some nice data.
                var data = {
                    labels: [
                                <?php foreach ($arrayTotales as $key => $value): ?>
                                    <?php echo "'".$key."'," ?>
                                <?php endforeach ?>2018,2019
                            ],
                    datasets: [
                        {
                            label: "Ingresos por Año",
                            backgroundColor: [
                                 'rgba(54, 162, 235, 0.2)',
                            ],
                            borderColor: [
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
                    type: 'line',
                    data: data,
                });

        });
    </script>

    <script type="text/javascript">  
        new Chart(document.getElementById("pie-ing"), {
            type: 'pie',
            data: {
              labels: ["Pend", "Cob"],
              datasets: [{
                backgroundColor: ["#99BCE7", "#295d9b"],
                data: [<?php echo ($arrayTotales[$inicio->copy()->subYear()->format('Y')]-$paymentSeason["total"]) ;?>,<?php echo $paymentSeason["total"]?>]
              }]
            },
            options: {
              title: {
                display: true,
                text: 'Ingresos Temporada <?php echo $inicio->copy()->format('Y') ?>-<?php echo $inicio->copy()->addYear()->format('Y') ?>'
              }
            }
        });

        new Chart(document.getElementById("pie-cob"), {
            type: 'pie',
            data: {
              labels: ["Banco", "Cash"],
              datasets: [{
                backgroundColor: ["#2dcdaf", "#0d967b"],
                data: [<?php echo ($paymentSeason["banco"]) ;?>,<?php echo $paymentSeason["cash"]?>]
              }]
            },
            options: {
              title: {
                display: true,
                text: 'Cobros Temporada <?php echo $inicio->copy()->format('Y') ?>-<?php echo $inicio->copy()->addYear()->format('Y') ?>'
              }
            }
        });

    </script>

@endsection