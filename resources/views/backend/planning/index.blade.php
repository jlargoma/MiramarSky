<?php   use \Carbon\Carbon;  
        setlocale(LC_TIME, "ES"); 
        setlocale(LC_TIME, "es_ES"); 
?>
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
    <style type="text/css">

        .Reservado-table{ 
            background-color: #295d9b !important;
            color: black;
        }

        .Pagada-la-señal{
            background-color: green  !important;
            color: black;
        }
        .Reserva.Propietario{
            background-color: orange !important;
            color: black;
        }
        .SubComunidad{
            background-color: orange !important;
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
        .Reservado,.table.table-striped tbody tr.Reservado td {
            background-color: rgba(0,200,10,0.2)  !important;
            color: black;
        }
        .table.table-striped tbody tr.Reservado td select.minimal{
            background-color: rgba(0,200,10,0.0)  !important;
            color: black!important;
            font-weight: bold!important;
        }

        .Bloqueado,.table.table-striped tbody tr.Bloqueado td {
            background-color: rgba(0,100,255,0.2)  !important;
            color: red;
        }
            
        
        .table.table-striped tbody tr.Bloqueado td select.minimal{
            background-color: rgba(0,100,255,0.0)  !important;
            color:red!important;
            font-weight: bold!important;

        }
        .nav-tabs-simple > li.active a{
            font-weight: 800;
        }
    </style>

@endsection
    
@section('content')

    <div class="container-fluid  p-l-15 p-r-15 p-t-20">
        <div class="row bg-white">
            <div class="col-md-12 text-center">
                <div class="col-md-3 col-md-offset-3 not-padding">
                    <h2><b>Planning de reservas</b>  Fechas:
                    </h2>
                </div>  
                <div class="col-md-1" style="padding: 15px 0px;">
                    <select id="fecha" class="form-control minimal">
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
                </div>      
            </div>
            <div class="col-xs-12">
                <div class="col-md-6 not-padding">
                    <button class="btn btn-success btn-cons m-b-10" type="button" data-toggle="modal" data-target="#modalNewBook">
                        <i class="fa fa-plus-square" aria-hidden="true"></i> <span class="bold">Nueva Reserva</span>
                    </button>
                </div>
            </div>
            
    
            <!-- Planning reservas  -->
            <div class="col-md-7">
                
                <div class="col-md-12 col-xs-12 not-padding">
                    <div class="row">
                        <ul class="nav nav-tabs nav-tabs-simple bg-info-light " role="tablist" data-init-reponsive-tabs="collapse">
                            <li class="active res" >
                                <a href="#tabPendientes" data-toggle="tab" role="tab" class="pendientes">Pendientes 
                                    <span class="badge font-w800 "><?php echo count($arrayBooks["nuevas"]) ?></span>
                                </a>
                            </li>
                            <li class="bloq">
                                <a href="#tabEspeciales" data-toggle="tab" role="tab" class="especiales">Especiales
                                    <span class="badge font-w800 "><?php echo count($arrayBooks["especiales"]) ?></span>
                                </a>
                            </li>
                            <li class="pag">
                                <a href="#tabPagadas" data-toggle="tab" role="tab" class="confirmadas">Confirmadas 
                                    <span class="badge font-w800 "><?php echo count($arrayBooks["pagadas"]) ?></span>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            
                            @include('backend.planning.listados._pendientes')
                            
                            @include('backend.planning.listados._especiales')

                            @include('backend.planning.listados._pagadas')
                            

                        </div>
                    </div>
                </div>
            </div>
            <!-- Planning reservas -->
            
            <div class="col-md-5">
                <!-- Seccion Calendario -->
                <div class="col-md-12 col-xs-12">
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
                                                        <td class="text-center"><b title="<?php echo $room->name ?>"><?php echo substr($room->nameRoom, 0,5)?></b></td>
                                                            
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
                <!-- Seccion Calendario -->

                <!-- Seccion Estadistica -->
                <div class="col-md-12 m-t-20">
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
                <!-- Seccion Estadistica -->
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


    <div class="modal fade slide-up disable-scroll in" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content-wrapper">
                <div class="modal-content"></div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div class="modal fade slide-up in" id="modalNewBook" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content-wrapper">
                <div class="modal-content">
                    @include('backend.planning.listados._nuevas')
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
    <script src="/assets/plugins/moment/moment.min.js"></script>
    <script src="/assets/plugins/bootstrap-typehead/typeahead.bundle.min.js"></script>
    <script src="/assets/plugins/bootstrap-typehead/typeahead.jquery.min.js"></script>
    <script src="/assets/plugins/handlebars/handlebars-v4.0.5.js"></script>

    <script type="text/javascript" src="/assets/plugins/jquery-autonumeric/autoNumeric.js"></script>

    <script src="/assets/js/notifications.js" type="text/javascript"></script>
    

        
    <script type="text/javascript">

        

        $(document).ready(function() {          

            $('.status,.room').change(function(event) {
                var id = $(this).attr('data-id');
                var clase = $(this).attr('class');
                
                if (clase == 'status form-control minimal') {
                    var status = $(this).val();
                    var room = "";
                }else if(clase == 'room form-control minimal'){
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
                        $('.notification-message').val(data);
                        document.getElementById("boton").click();
                        if (data == "Ya hay una reserva para ese apartamento" || data == "No se puede cambiar el estado" || data == "Valor nulo o vacio") {
                            
                        }else{
                            setTimeout('document.location.reload()',2000);
                        }                        
                   }); 
                }
                
            });

            $('#fecha').change(function(event) {
                
                var year = $(this).val();
                window.location = '/admin/reservas/'+year;
            });
            
               
        });
    </script>

    

@endsection