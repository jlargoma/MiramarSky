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
        .table.table-hover tbody tr:hover td {
            background: #99bce7 !important;
        }

        .table.table-striped tbody tr.Reservado td select.minimal{
            background-color: rgba(0,200,10,0.0)  !important;
            color: black!important;
            font-weight: bold!important;
        }

            
        
        .table.table-striped tbody tr.Bloqueado td select.minimal{
            background-color: #D4E2FF  !important;
            color:red!important;
            font-weight: bold!important;

        }
        .nav-tabs-simple > li.active a{
            font-weight: 800;
        }
        span.numPaymentLastBooks{
            position: absolute;
            top: -10px;
            right: -10px;
            background: red;
            border-radius: 100%;
            padding: 0px 7px;
        }
    </style>

@endsection
    
@section('content')
    
    @include('backend.planning._calculateBook')

    <?php if (!$mobile->isMobile() ): ?>
    
        <div class="container-fluid  p-l-15 p-r-15 p-t-20">
            <div class="row bg-white">
                <div class="col-md-4">
                    @include('backend.planning._lastBookPayment')
                </div>
                <div class="col-md-5 pull-right">
                	<?php $notifications = \App\BookNotification::all(); ?>
                	<?php $isNotify = false; ?>
                	<?php $countNotify = 0; ?>
                	<?php foreach ($notifications as $key => $notify): ?>
                	    <?php if ($notify->book->type_book != 3 || $notify->book->type_book != 5 || $notify->book->type_book != 6){ $isNotify = true; $countNotify ++;} ?>
                	<?php endforeach ?>
                	<?php if ($isNotify): ?>
                	    <div class="col-xs-12 content-notify-booking" style="display: none;">
                	        <div class="alert alert-info fade in alert-dismissable" style="background-color: #daeffd!important;">
                	            <h4 class="text-center">Alertas BOOKING</h4> 
                	            <h5 class="text-center">Recuerda eliminar los dias de las siguientes reservas de booking.com</h5> 
                	            
                	            <table class="table table-condensed" style="margin-top: 0;">
                	                <tbody>
                	                <?php foreach ($notifications as $key => $notify): ?>
                	                    <?php if ($notify->book->type_book != 3 || $notify->book->type_book != 5 || $notify->book->type_book != 6 ): ?>
                	                    <tr>
                                            <td class="text-center" style="width: 30px; padding: 5px 0!important">
                                                <?php if ($notify->book->agency != 0): ?>
                                                    <img src="/pages/booking.png" style="width: 20px;"/>
                                                <?php else: ?>

                                                <?php endif ?>
                                            </td>
                	                        <td class="text-center" style="padding: 5px 0!important">
                	                            <?php echo $notify->book->customer->name; ?></td>
                	                        <td class="text-center" style="padding: 5px 0!important">
                	                            <?php
                	                                $start = Carbon::createFromFormat('Y-m-d',$notify->book->start);
                	                                echo $start->formatLocalized('%d %b');
                	                            ?> - 
                	                            <?php
                	                                $finish = Carbon::createFromFormat('Y-m-d',$notify->book->finish);
                	                                echo $finish->formatLocalized('%d %b');
                	                            ?>
                	                        </td>
                	                        <td class="text-center" style="padding: 5px 0!important">
                	                            <b><?php echo $notify->book->room->name?> </b>                                               
                	                        </td>
                	                        <td class="text-center" style="padding: 5px 0!important">
                	                            <b><?php echo $notify->book->getStatus($notify->book->type_book)?> </b>                                               
                	                        </td>
                	                        <td class="text-center" style="padding: 5px 0!important">
                	                            <b><?php echo number_format($notify->book->total_price,2, ',', ".") ?> €</b>                                               
                	                        </td>
                	                        <td class="text-center" style="padding: 5px 0!important">
												<a class="btn btn-default" href="/admin/delete/nofify/<?php echo $notify->id ?>"><i class="fa fa-trash-o"></i></a>
                	                        </td>
                	                    </tr>
                	                    <?php endif ?>
                	                <?php endforeach ?>
                	                </tbody>
                	            </table>
                	            <p class="text-justify">
                	                Puedes acceder a booking haciendo click <a href="#">aquí</a>
                	            </p>
                	        </div>   
                	    </div>
                	<?php endif ?>
                </div>

                <div class="col-xs-12">
                    <div class="col-md-7 not-padding">
                        <div class="col-md-8">
                            <button class="btn btn-success btn-cons m-b-10" type="button" data-toggle="modal" data-target="#modalNewBook">
                                <i class="fa fa-plus-square" aria-hidden="true"></i> <span class="bold">Nueva Reserva</span>
                            </button>
                            <?php 
                                $stripedsPayments = \App\Payments::where('comment', 'LIKE', '%stripe%')
                                                                    ->whereYear('created_at','=', date('Y'))
                                                                    ->whereMonth('created_at','=', date('m'))
                                                                    ->whereDay('created_at','=', date('d'))
                                                                    ->get();
                            ?>
                            <button id="lastBooks" class="btn btn-success btn-cons m-b-10" type="button">
                                <span class="bold">Últimas reservas</span>
                                <span class="numPaymentLastBooks"><?php echo  $stripedsPayments->count(); ?></span>
                            </button>
                            <button id="lastBooksClose" class="btn btn-danger btn-cons m-b-10" type="button" style="display: none;">
                                <span class="bold">Últimas reservas</span>
                                <span class="numPaymentLastBooks"><?php echo  $stripedsPayments->count(); ?></span>
                            </button>
                             <button class="btn btn-success btn-cons m-b-10" type="button" id="stripePayment">
                                <i class="fa fa-money" aria-hidden="true"></i> <span class="bold">Cobros stripe</span>
                            </button>
                        </div>
                        <div class="col-md-4 text-center pull-right">
                            <div class="col-md-5 not-padding">
                                <h2 style="margin: 0;">
                                    <b>Planning</b> 
                                </h2>
                            </div>  
                            <div class="col-md-7">
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
                    </div>
                    <div class="col-md-5">
                        
                        <button id="btnNotifyBooking" class="btn btn-success btn-cons m-b-10" type="button">
                            <span class="bold">Alertas booking</span>
                            <span class="numPaymentLastBooks"><?php echo  $countNotify; ?></span>
                        </button>
                        <button id="btnHideNotifyBooking" class="btn btn-danger btn-cons m-b-10" type="button" style="display: none;">
                            <span class="bold">Alertas booking</span>
                            <span class="numPaymentLastBooks"><?php echo  $countNotify; ?></span>
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

                    <?php 
                        /* No borrar para calendario de booking */
                        $dateX = $inicio->copy(); 
                    ?>

                    <!-- Seccion Calendario -->
                    @include('backend.planning.calendar')
                    <!-- Seccion Calendario -->


                    <div class="col-md-12" id="stripe-conten-index" style="display: none;">
                        @include('backend.stripe.stripe', ['bookTocharge' => null])
                    </div>


                    <div class="row">
                        @include('backend.planning._calendarToBooking')
                    </div>
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


        <button style="display: none;" id="btnContestado" class="btn btn-success btn-cons m-b-10" type="button" data-toggle="modal" data-target="#modalContestado"> </button>
        <div class="modal fade slide-up in" id="modalContestado" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content-wrapper">
                    <div class="modal-content contestado" id="contentEmailing"></div>
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

    <?php else: ?>
        <style type="text/css">
            .bg-info-light>li>a {
                padding: 10px;
            }
            table.calendar-table thead > tr > td {
                width: 20px!important;
                padding: 0px 5px!important;
            }
            .summernote-wrapper .note-editor .note-toolbar .btn-group .btn {
                font-size: 12px;
                font-weight: 600;
                height: 40px;
                min-width: 33px;
            }
            .modal .modal-body{
                padding: 0 10px;
            }
            .first-list > li.active a{
                background-color: #3f3f3f!important;
            }
        </style>
        <div class="container-fluid container-fixed-lg">
            
            <div class="row" style="margin-top: 15px;">
                <div class="col-xs-6" style="padding: 0 15px;">
                    <?php 
                        $stripedsPayments = \App\Payments::where('comment', 'LIKE', '%stripe%')
                                                            ->whereYear('created_at','=', date('Y'))
                                                            ->whereMonth('created_at','=', date('m'))
                                                            ->whereDay('created_at','=', date('d'))
                                                            ->get();
                    ?>
                    <button id="lastBooks" class="btn btn-success btn-cons m-b-10" type="button">
                        <span class="bold">Últimas reservas</span>
                        <span class="numPaymentLastBooks"><?php echo  $stripedsPayments->count(); ?></span>
                    </button>
                    <button id="lastBooksClose" class="btn btn-danger btn-cons m-b-10" type="button" style="display: none;">
                        <span class="bold">Últimas reservas</span>
                        <span class="numPaymentLastBooks"><?php echo  $stripedsPayments->count(); ?></span>
                    </button>
                </div>
                <div class="col-xs-6">
                	<?php $notifications = \App\BookNotification::all(); ?>
                	<?php $isNotify = false; ?>
                	<?php $countNotify = 0; ?>
                	<?php foreach ($notifications as $key => $notify): ?>
                	    <?php if ($notify->book->type_book != 3 || $notify->book->type_book != 5 || $notify->book->type_book != 6){ $isNotify = true; $countNotify ++;} ?>
                	<?php endforeach ?>
                	<button id="btnNotifyBooking" class="btn btn-success btn-cons m-b-10" type="button">
                	    <span class="bold">Alertas booking</span>
                	    <span class="numPaymentLastBooks"><?php echo  $countNotify; ?></span>
                	</button>
                	<button id="btnHideNotifyBooking" class="btn btn-danger btn-cons m-b-10" type="button" style="display: none;">
                	    <span class="bold">Alertas booking</span>
                	    <span class="numPaymentLastBooks"><?php echo  $countNotify; ?></span>
                	</button>
                </div>
            </div>
            <div class="row" style="margin-top: 15px;">
                    
                <div class="col-md-12">
                     @include('backend.planning._lastBookPayment')
                </div>
				
            	<?php if ($isNotify): ?>
            	    <div class="row content-notify-booking" style="display: none;">
            	        <div class="alert alert-info fade in alert-dismissable" style="background-color: #daeffd!important;">
            	            <h4 class="text-center">Alertas BOOKING</h4> 
            	            <h5 class="text-center">Recuerda eliminar los dias de las siguientes reservas de booking.com</h5> 
            	            
            	            <table class="table" style="margin-top: 0;">
            	                <tbody>
            	                <?php foreach ($notifications as $key => $notify): ?>
            	                    <?php if ($notify->book->type_book != 3 || $notify->book->type_book != 5 || $notify->book->type_book != 6 ): ?>
            	                    <tr>
            	                        <td class="text-center" style="padding: 0 5px!important">
            	                            <?php echo $notify->book->customer->name; ?></td>
            	                        <td class="text-center" style="padding: 0 5px!important">
            	                            <?php
            	                                $start = Carbon::createFromFormat('Y-m-d',$notify->book->start);
            	                                echo $start->formatLocalized('%d %b');
            	                            ?><br>
            	                            <?php
            	                                $finish = Carbon::createFromFormat('Y-m-d',$notify->book->finish);
            	                                echo $finish->formatLocalized('%d %b');
            	                            ?>
            	                        </td>
            	                        <td class="text-center" style="padding: 0 5px!important">
            	                            <b><?php echo $notify->book->room->name?> </b>                                               
            	                        </td>
            	                        <td class="text-center" style="padding: 0 5px!important">
            	                            <b><?php echo $notify->book->getStatus($notify->book->type_book)?> </b>                                               
            	                        </td>
            	                        <td class="text-center" style="padding: 0 5px!important">
											<a class="btn btn-default" href="/admin/delete/nofify/<?php echo $notify->id ?>"><i class="fa fa-trash-o"></i></a>
            	                        </td>
            	                    </tr>
            	                    <?php endif ?>
            	                <?php endforeach ?>
            	                </tbody>
            	            </table>
            	            <p class="text-justify">
            	                Puedes acceder a booking haciendo click <a href="#">aquí</a>
            	            </p>
            	        </div>   
            	    </div>
            	<?php endif ?>

            </div>
            <div class="row">
                
                <div class="panel" style="margin-bottom: 0px!important">
                    <ul class="nav nav-tabs nav-tabs-simple bg-info-light first-list" role="tablist" data-init-reponsive-tabs="collapse">
                        <li class="resv  active text-center"  style="width: 25%; min-height: 43px;">
                            <a href="#reservas" data-toggle="tab" role="tab" style="font-size: 12px!important;padding-left: 2px;padding-right: 2px"> RESERVAS </a>
                        </li>
                        <li class="cob text-center" style="width: 25%; min-height: 43px;">
                            <a href="#cobros" data-toggle="tab" role="tab" style="font-size: 12px!important;padding-left: 2px;padding-right: 2px"> RECEPCION </a>
                        </li>
                        <li class="calend text-center" style="width: 25%;min-height: 43px;line-height: 45px;">
                            <i class="fa fa-calendar fa-2x white" aria-hidden="true"></i>
                        </li>

                        <li class="money-stripe text-center" style="width: 25%;min-height: 43px;line-height: 45px;">
                            <i class="fa fa-money fa-2x white" aria-hidden="true"></i>
                        </li>
                    </ul>
                </div>
                <div class="tab-content ">
                    <div class="tab-pane active" id="reservas">
                        <div class="row">
                            <div class="col-xs-3" style="position: fixed; bottom: 20px; right: 10px; z-index: 100">
                                <button class="btn btn-success btn-cons" type="button" data-toggle="modal" data-target="#modalNewBook" style="min-width: 10px!important;width: 80px!important; padding: 25px; border-radius: 100%;">
                                    <i class="fa fa-plus fa-2x" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                        <div class="row column-seperation ">
                            <div class="panel resv" style="margin-bottom: 0;">
                                <ul class="nav nav-tabs nav-tabs-simple bg-info-light rev" role="tablist" data-init-reponsive-tabs="collapse">
                                    <li class="active res text-center"  style="width: 33.33%;">
                                        <a href="#tabPendientes" data-toggle="tab" role="tab" class="pendientes">Pend... 
                                            <span class="badge font-w800 "><?php echo count($arrayBooks["nuevas"]) ?></span>
                                        </a>
                                    </li>
                                    <li class="bloq text-center" style="width: 33.33%;">
                                        <a href="#tabEspeciales" data-toggle="tab" role="tab" class="especiales">Esp...
                                            <span class="badge font-w800 "><?php echo count($arrayBooks["especiales"]) ?></span>
                                        </a>
                                    </li>
                                    <li class="pag text-center" style="width: 33.33%;">
                                        <a href="#tabPagadas" data-toggle="tab" role="tab" class="confirmadas">Confir... 
                                            <span class="badge font-w800 "><?php echo count($arrayBooks["pagadas"]) ?></span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-content ">
                                <div class="tab-pane active table-responsive" id="tabPendientes">
                                    <div class="container column-seperation ">
                                        @include('backend.planning.listados._pendientes-mobile')
                                    </div>
                                </div>
                                <div class="tab-pane table-responsive" id="tabEspeciales">
                                    <div class="container column-seperation ">
                                            @include('backend.planning.listados._especiales-mobile')
                                    </div>
                                </div>
                                <div class="tab-pane table-responsive " id="tabPagadas">
                                    <div class="container column-seperation ">.
                                        @include('backend.planning.listados._pagadas')                                 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane" id="cobros">
                        <div class="col-xs-12 column-seperation">
                            <div class="panel in-out push-0">
                                <ul class="nav nav-tabs nav-tabs-simple bg-info-light rev first-list" role="tablist" data-init-reponsive-tabs="collapse">
                                    <li class="active in text-center cob" style="width: 50%">
                                        <a href="#tabIn" data-toggle="tab" role="tab" style="font-size: 11px;">CHECK IN
                                        </a>
                                    </li>
                                    <li class="out text-center cob"  style="width: 50%">
                                        <a href="#tabOut" data-toggle="tab" role="tab" style="font-size: 11px;">CHECK OUT
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-content">
                                <div class="tab-pane active table-responsive" id="tabIn" style="border: none;">
                                    <table class="table table-striped dataTable no-footer">
                                        <thead>
                                            <th class="bg-success text-white text-center">Nombre</th>
                                            <th class="bg-success text-white text-center">Tel</th>
                                            <th class="bg-success text-white text-center">Apto</th>
                                            <th class="bg-success text-white text-center">Reserva</th>
                                            <th class="bg-success text-white text-center"><i class="fa fa-clock-o" aria-hidden="true"></i> in</th>
                                            <th class="bg-success text-white text-center">Pendiente</th>
                                            
                                        </thead>
                                        <tbody>
                                            <?php foreach ($proxIn as $book): ?>
                                                <tr>
                                                    <td class="text-center" style="padding: 0px 10px!important;">
                                                        <a class="update-book" data-id="<?php echo $book->id ?>"  title="Editar Reserva"  href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>">
                                                             <?php echo substr($book->customer->name, 0, 10) ?>
                                                        </a> 
                                                    </td>
                                                    <td class="text-center sm-p-t-10 sm-p-b-10">
                                                        <a href="tel:<?php echo $book->customer->phone ?>"><i class="fa fa-phone"></i></a>
                                                    </td>
                                                    <td class="text-center sm-p-t-10 sm-p-b-10">
                                                        <b><?php echo $book->room->nameRoom ?></b>
                                                    </td>
                                                    <td class="text-center sm-p-t-10 sm-p-b-10">
                                                        <?php echo Carbon::CreateFromFormat('Y-m-d',$book->start)->formatLocalized('%d-%b') ?> 
                                                        <?php echo Carbon::CreateFromFormat('Y-m-d',$book->finish)->formatLocalized('%d-%b') ?>
                                                            
                                                    </td>

                                                    <td class="text-center sm-p-t-10 sm-p-b-10">
                                                        <select id="schedule" style="width: 100%;" data-type="in" data-id="<?php echo $book->id ?>">
                                                            <option>---</option>
                                                            <?php for ($i = 0; $i < 24; $i++): ?>
                                                                <option value="<?php echo $i ?>" <?php if($i == $book->schedule) { echo 'selected';}?>>
                                                                    <?php if ($i < 10): ?>
                                                                        0<?php echo $i ?>
                                                                    <?php else: ?>
                                                                        <?php echo $i ?>
                                                                    <?php endif ?>
                                                                </option>
                                                            <?php endfor ?>
                                                        </select>
                                                    </td>
                                                    <td class="text-center sm-p-t-10 sm-p-b-10">
                                                        <?php if (isset($payment[$book->id])): ?>
                                                            <p style="{{ $book->total_price - $payment[$book->id] > 0 ? 'color:red' : '' }}"><?php echo number_format($book->total_price - $payment[$book->id],2,',','.') ?> €</p>
                                                        <?php else: ?>
                                                            <p style="color:red"><?php echo number_format($book->total_price,2,',','.') ?> €<p>
                                                        <?php endif ?>
                                                    </td>
                                                   
                                                </tr>
                                            <?php endforeach ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane table-responsive" id="tabOut">
                                    <table class="table table-striped dataTable no-footer">
                                        <thead>
                                            <th class="bg-success text-white text-center">Nombre</th>
                                            <th class="bg-success text-white text-center">Out</th>
                                            <th class="bg-success text-white text-center">Apto</th>
                                            <th class="bg-success text-white text-center"><i class="fa fa-clock-o" aria-hidden="true"></i>Salida</th>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($proxOut as $book): ?>
                                                <tr>
                                                    <td class="text-center sm-p-t-10 sm-p-b-10">
                                                        <a class="update-book" data-id="<?php echo $book->id ?>"  title="Editar Reserva"  href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>">
                                                            <?php echo substr($book->customer->name, 0, 10) ?>
                                                        </a> 
                                                    </td>
                                                    <td class="text-center sm-p-t-10 sm-p-b-10"><?php echo Carbon::CreateFromFormat('Y-m-d',$book->finish)->formatLocalized('%d-%b') ?></td>
                                                    <td class="text-center sm-p-t-10 sm-p-b-10">
                                                        <b><?php echo $book->room->nameRoom ?></b>
                                                    </td>
                                                    <td class="text-center sm-p-t-10 sm-p-b-10">
                                                        <select id="scheduleOut" style="width: 100%;" data-type="out" data-id="<?php echo $book->id ?>">
                                                            <option>---</option>
                                                            <?php for ($i = 0; $i < 24; $i++): ?>
                                                                <option value="<?php echo $i ?>" <?php if($i == $book->scheduleOut) { echo 'selected';}?>>
                                                                    <?php if ($i != 12): ?><b><?php endif ?>
                                                                    <?php if ($i < 10): ?>
                                                                        0<?php echo $i ?>
                                                                    <?php else: ?>
                                                                        <?php echo $i ?>
                                                                    <?php endif ?>
                                                                    <?php if ($i != 12): ?></b><?php endif ?>
                                                                </option>
                                                            <?php endfor ?>
                                                        </select>
                                                       <!--  <?php if (isset($payment[$book->id])): ?>
                                                            <?php echo number_format($book->total_price - $payment[$book->id],2,',','.') ?> €
                                                        <?php else: ?>
                                                            <?php echo number_format($book->total_price,2,',','.') ?> €
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



                    <!-- Seccion Calendario -->
                    <div class="calendar-mobile row">
                        @include('backend.planning.calendar')
                    </div>
                    <!-- Seccion Calendario -->
					
                </div>
                <div class="col-md-12 push-40 stripe-mobile" >
				    <!-- {{ url('admin/reservas/stripe/paymentsBooking') }} -->
				    @include('backend.stripe.stripe', ['bookTocharge' => null])
				</div>
            </div>

            <!-- Modal de cobros -->
            <div class="modal fade slide-up disable-scroll in" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-md">
                    <div class="modal-content-wrapper">
                        <div class="modal-content">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-50" style="font-size: 35px"></i>
                            </button>
                            <div class="container-xs-height full-height">
                                <div class="row-xs-height">
                                    <div class="modal-body col-xs-height col-middle text-center p-0">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- Modal de Cobros -->

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

            <button style="display: none;" id="btnContestado" class="btn btn-success btn-cons m-b-10" type="button" data-toggle="modal" data-target="#modalContestado"> </button>

            <div class="modal fade slide-up in" id="modalContestado" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content-wrapper">
                        <div class="modal-content contestado">
                        </div>
                    </div>
                  <!-- /.modal-content -->
                </div>
              <!-- /.modal-dialog -->
            </div>

            <form role="form">
                <div class="form-group form-group-default required" style="display: none">
                    <label class="highlight">Message</label>
                    <input type="text" hidden="" class="form-control notification-message" placeholder="Type your message here" value="This notification looks so perfect!" required>
                </div>
                <button class="btn btn-success show-notification hidden" id="boton">Show</button>
            </form>
        </div>
    <?php endif ?>
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
       
        $('li.calend').click(function(event) {
            $('html, body').animate({
               scrollTop: $(".calendar-mobile").offset().top 
           }, 2000);
        });
        
        $('#lastBooks').click(function(event) {
            $('.content-last-books').show();
            $('#lastBooksClose').show();
            $(this).hide();
        });
        $('#lastBooksClose').click(function(event) {
            $('.content-last-books').hide();
            $(this).hide();
            $('#lastBooks').show();
            
        });
        


		$('#btnNotifyBooking').click(function(event) {
            $('.content-notify-booking').show();
            $('#btnHideNotifyBooking').show();
            $(this).hide();
        });
        $('#btnHideNotifyBooking').click(function(event) {
            $('.content-notify-booking').hide();
            $(this).hide();
            $('#btnNotifyBooking').show();
            
        });

        $(document).ready(function() {          

            $('.status, .room').change(function(event) {
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

                    $('.modal-content.contestado').empty().load('/admin/reservas/ansbyemail/'+id);

                    $('#btnContestado').trigger('click');

                    
                   
                }else{
                    
                   $.get('/admin/reservas/changeBook/'+id, {status:status,room: room}, function(data) {
                        $('.notification-message').val(data);
                        document.getElementById("boton").click();
                        if (data == "Ya hay una reserva para ese apartamento" || data == "No se puede cambiar el estado" || data == "Valor nulo o vacio" || data == "No tiene Email asignado") {
                            
                        }else{
                            setTimeout('document.location.reload()',1000);
                        }                        
                   }); 
                }
                
            });

            $('#fecha').change(function(event) {
                
                var year = $(this).val();
                window.location = '/admin/reservas/'+year;

            });

            $('#stripePayment').click(function(event) {
               $('#stripe-conten-index').toggle(function() {

                   $('#stripePayment').css('background-color', '#f55753');

               }, function() {

                   $('#stripePayment').css('background-color', '#10cfbd');
                   

               });
               
            });

            $('.money-stripe ').click(function(event) {
                 $('html, body').animate({
                       scrollTop: $(".stripe-mobile").offset().top 
                   }, 2000);
            });
            

            $('#schedule, #scheduleOut').change(function(event) {

                var type = $(this).attr('data-type');
                var id = $(this).attr('data-id');
                var schedule = $(this).val();

                if (type == "in") {
                    var typeNum = 1;
                }else{
                    var typeNum = 0;

                }

                $.get('/admin/reservas/changeSchedule/'+id+'/'+typeNum+'/'+schedule, function(data) {
                    alert(data);
                });
            });
               

        });
    </script>

    

@endsection