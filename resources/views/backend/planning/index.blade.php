<?php   use \Carbon\Carbon;  
        setlocale(LC_TIME, "ES"); 
        setlocale(LC_TIME, "es_ES"); 
?>
@extends('layouts.admin-master')

@section('title') Administrador de reservas MiramarSKI @endsection

@section('externalScripts') 
    <link href="/assets/css/font-icons.css" rel="stylesheet" type="text/css" />

    <link href="/assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" type="text/css" media="screen">

    <link rel="stylesheet" href="{{ asset('/frontend/css/components/daterangepicker.css')}}" type="text/css" />
   
    <style type="text/css">
        [data-notify="progressbar"] {
            margin-bottom: 0px;
            position: absolute;
            bottom: 0px;
            left: 0px;
            width: 100%;
            height: 5px;
        }
    </style>
@endsection
    
@section('content')
    
    
    <?php if (!$mobile->isMobile() ): ?>
    
        <div class="container-fluid  p-l-15 p-r-15 p-t-20 bg-white">
            <div class="row push-10">
                <div class="container">
                    <div class="col-xs-12 text-center">
                        <div class="col-md-2 col-md-offset-3 not-padding">
                            <h2 style="margin: 0;">
                                <b>Planning</b> 
                            </h2>
                        </div>  
                        <div class="col-md-2">
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

            </div>
            
            <div class="row push-10">
                <div class="col-md-7">
                    <div class="row">
                        <button class="btn btn-success btn-cons" type="button" data-toggle="modal" data-target="#modalNewBook">
                            <i class="fa fa-plus-square" aria-hidden="true"></i> <span class="bold">Nueva Reserva</span>
                        </button>

                        <button id="lastBooks" class="btn btn-success btn-cons" type="button" data-toggle="modal" data-target="#modalLastBooks">
                            <span class="bold">Últimas reservas</span>
                            <span class="numPaymentLastBooks"><?php echo  $stripedsPayments->count(); ?></span>
                        </button>

                         <button class="btn btn-success btn-cons" type="button" id="stripePayment">
                            <i class="fa fa-money" aria-hidden="true"></i> <span class="bold">Cobros stripe</span>
                        </button>

                        <button class="btn btn-success btn-calcuteBook btn-cons" type="button" data-toggle="modal" data-target="#modalCalculateBook"> 
                            <span class="bold">Calcular reserva</span>
                        </button>
                        
                    </div>
                    
                </div>
                <div class="col-md-5">
                    
                    <button id="btnAlertsBookking" class="btn btn-success btn-cons " type="button" data-toggle="modal" data-target="#modalAlertsBooking">
                        <span class="bold">Alertas booking</span>
                        <span class="numPaymentLastBooks"><?php echo  $notifications ?></span>
                    </button>

                    <button class="btn btn-primary btn-calendarBooking btn-cons" type="button" data-toggle="modal" data-target="#modalCalendarBooking"> 
                        <span class="bold">Calendario booking</span>
                    </button>

                </div>
            </div>


            <div class="row push-20">

                <div class="col-md-7">
                    <div class="row push-10">
                        <div class="col-md-5 col-xs-12 pull-right">
                            <input id="nameCustomer" type="text" name="searchName" class="searchabled form-control" placeholder="nombre del cliente" />
                        </div>
                    </div>
                    <div class="row text-left push-0">
                        
                        <button class="btn btn-primary  btn-blue btn-tables btn-cons" type="button" data-type="pendientes"> 
                            <span class="bold">Pendientes</span>
                        </button>
                    
                        <button class="btn btn-primary  btn-orange btn-tables btn-cons" type="button" data-type="especiales"> 
                            <span class="bold">Especiales</span>
                        </button>
                    
                        <button class="btn  btn-primary btn-green btn-tables btn-cons" type="button" data-type="confirmadas"> 
                            <span class="bold">Confirmadas</span>
                        </button>
                    
                        <button class="btn btn-success btn-tables btn-cons" type="button" data-type="checkin"> 
                            <span class="bold">Check IN</span>
                        </button>
                    
                        <button class="btn btn-primary btn-tables btn-cons" type="button" data-type="checkout"> 
                            <span class="bold">Check OUT</span>
                        </button>
                       
                    </div>
                    <div class="row" id="resultSearchBook" style="display: none;"></div>
                    <div class="row content-tables" >
                        @include('backend.planning._table', ['type'=> 'pendientes'])
                    </div>

                </div>
                <div class="col-md-5">
                    <div class="row content-calendar push-20" style="min-height: 515px;">
                        <div class="col-xs-12 text-center sending" style="padding: 120px 15px;">
                            <i class="fa fa-spinner fa-5x fa-spin" aria-hidden="true"></i><br>
                            <h2 class="text-center">CARGANDO CALENDARIO</h2>
                        </div>
                    </div>

                    <div class="col-md-12" id="stripe-conten-index" style="display: none;">
                        @include('backend.stripe.stripe', ['bookTocharge' => null])
                    </div>
                </div>
            </div>

        </div>
       
       <!-- NUEVAS RESERVAS -->
        <div class="modal fade slide-up in" id="modalNewBook" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content-wrapper">
                    <div class="modal-content">
                        @include('backend.planning._nueva')
                    </div>
                </div>
            </div>
        </div>
    
        <!-- CALCULAR RESERVAS -->
        <div class="modal fade slide-up in" id="modalCalculateBook" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content-wrapper">
                    <div class="modal-content"></div>
                </div>
            </div>
        </div>

        <!-- ÚLTIMAS RESERVAS -->
        <div class="modal fade slide-up in" id="modalLastBooks" tabindex="-1" role="dialog" aria-hidden="true" >
            <div class="modal-dialog modal-lg">
                <div class="modal-content-wrapper">
                    <div class="modal-content">
                        
                    </div>
                </div>
            </div>
        </div>

        <!-- ALERTAS DE BOOKING -->
        
        <div class="modal fade slide-up in" id="modalAlertsBooking" tabindex="-1" role="dialog" aria-hidden="true" >
            <div class="modal-dialog modal-lg" style="float: left; margin-left: 5%;">
                <div class="modal-content-wrapper">
                    <div class="modal-content">
                        
                    </div>
                </div>
            </div>
        </div>

        <!-- IMAGENES POR PISO -->
        <div class="modal fade slide-up in" id="modalRoomImages" tabindex="-1" role="dialog" aria-hidden="true" >
            <div class="modal-dialog modal-lg" style="width: 85%;">
                <div class="modal-content-wrapper">
                    
                    <div class="modal-content" style="max-height: 800px; overflow-y: auto;">
                        
                    </div>
                </div>
            </div>
        </div>

        <!-- CALENDARIO DE BOOKING -->
        <div class="modal fade slide-up in" id="modalCalendarBooking" tabindex="-1" role="dialog" aria-hidden="true" >
            <div class="modal-dialog modal-lg" style="float: left; margin-left: 5%;">
                <div class="modal-content-wrapper">
                    
                    <div class="modal-content">
                        
                    </div>
                </div>
            </div>
        </div>

        <!-- RESPUESTA POR EMAIL AL CLIENTE  -->
        <button style="display: none;" id="btnContestado" class="btn btn-success btn-cons m-b-10" type="button" data-toggle="modal" data-target="#modalContestado"> </button>
        <div class="modal fade slide-up in" id="modalContestado" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content-wrapper">
                    <div class="modal-content contestado" id="contentEmailing"></div>
                </div>
            </div>
        </div>


    
    
    <?php else: ?>
        <script type="text/javascript">
            $(document).ready(function() {
                $('.calend').click(function(event) {
                    $('html, body').animate({
                       scrollTop: $(".calendar-mobile").offset().top 
                    }, 2000);
                });
                $('.money-stripe ').click(function(event) {
                    $('html, body').animate({
                       scrollTop: $(".stripe-mobile").offset().top 
                    }, 2000);
                });
            }); 
        </script>

        <div class="container-fluid  p-l-15 p-r-15 p-t-20 bg-white">
            <div class="row push-10">
                <div class="container">
                    <div class="col-xs-12 text-center">
                        <div class="col-md-2 col-md-offset-3 col-xs-5 not-padding">
                            <h2 style="margin: 0;">
                                <b>Planning</b> 
                            </h2>
                        </div>  
                        <div class="col-md-2 col-xs-7">
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

            </div>
            <div class="row">
                <div class="col-xs-3" style="position: fixed; bottom: 20px; right: 10px; z-index: 100">
                    <button class="btn btn-success btn-cons" type="button" data-toggle="modal" data-target="#modalNewBook" style="min-width: 10px!important;width: 80px!important; padding: 25px; border-radius: 100%;">
                        <i class="fa fa-plus fa-2x" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
            <div class="row push-10">
                <div class="col-xs-12">
                    <div class="row">
                        <div class="col-xs-6 push-10 text-center">
                            <button id="lastBooks" class="btn btn-success btn-cons" type="button" data-toggle="modal" data-target="#modalLastBooks">
                                <span class="bold">Últimas reservas</span>
                                <span class="numPaymentLastBooks"><?php echo  $stripedsPayments->count(); ?></span>
                            </button>
                        </div>
                        <div class="col-xs-6 push-10 text-center">
                            <button class="btn btn-success btn-cons" type="button" id="stripePayment">
                                <i class="fa fa-money" aria-hidden="true"></i> <span class="bold">Cobros stripe</span>
                            </button>
                        </div>
                        <div class="col-xs-6 push-10 text-center">
                            <button class="btn btn-success btn-calcuteBook btn-cons" type="button" data-toggle="modal" data-target="#modalCalculateBook"> 
                                <span class="bold">Calcular reserva</span>
                            </button>
                        </div>
                        <div class="col-xs-6 push-10 text-center">
                            <button id="btnAlertsBookking" class="btn btn-success btn-cons " type="button" data-toggle="modal" data-target="#modalAlertsBooking">
                                <span class="bold">Alertas booking</span>
                                <span class="numPaymentLastBooks"><?php echo  $notifications ?></span>
                            </button>
                        </div>

                    </div>
                </div>
            </div>


            <div class="row push-20">

                <div class="col-md-7">
                    <div class="row push-10">
                        <div class="col-md-5 col-xs-12 pull-right">
                            <input id="nameCustomer" type="text" name="searchName" class="searchabled form-control" placeholder="nombre del cliente" />
                        </div>
                    </div>
                    <div class="row text-left push-0">

                        <button class="btn btn-primary  btn-blue btn-tables" type="button" data-type="pendientes"> 
                            <span class="bold">Pend</span>
                            
                        </button>

                        <button class="btn btn-primary  btn-orange btn-tables" type="button" data-type="especiales"> 
                            <span class="bold">Esp</span>
                            
                        </button>

                        <button class="btn  btn-primary btn-green btn-tables" type="button" data-type="confirmadas"> 
                            <span class="bold">Confir</span>
                           
                        </button>

                        <button class="btn btn-success btn-tables" type="button" data-type="checkin"> 
                            <span class="bold">IN</span>
                            
                        </button>

                        <button class="btn btn-primary btn-tables" type="button" data-type="checkout"> 
                            <span class="bold">OUT</span>
                            
                        </button>

                        <button class="btn btn-default calend btn-calMobile" type="button" > 
                            <span class="bold"><i class="fa fa-calendar fa-2x"></i></span>
                            
                        </button>

                    </div>
                    <div class="row" id="resultSearchBook" style="display: none;"></div>
                    <div class="row content-tables" >
                        @include('backend.planning._table', ['type'=> 'pendientes'])
                    </div>

                </div>
                <div class="col-md-5">
                    <div class="row content-calendar calendar-mobile push-20" style="min-height: 485px;">
                        <div class="col-xs-12 text-center sending" style="padding: 120px 15px;">
                            <i class="fa fa-spinner fa-5x fa-spin" aria-hidden="true"></i><br>
                            <h2 class="text-center">CARGANDO CALENDARIO</h2>
                        </div>
                    </div>

                    <div class="col-md-12" id="stripe-conten-index" style="display: none;">
                        @include('backend.stripe.stripe', ['bookTocharge' => null])
                    </div>
                </div>
            </div>

        </div>

        <!-- NUEVAS RESERVAS -->
        <div class="modal fade slide-up in" id="modalNewBook" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content-wrapper">
                    <div class="modal-content">
                        @include('backend.planning._nueva')
                    </div>
                </div>
            </div>
        </div>

        <!-- CALCULAR RESERVAS -->
        <div class="modal fade slide-up in" id="modalCalculateBook" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content-wrapper">
                    <div class="modal-content"></div>
                </div>
            </div>
        </div>

        <!-- ÚLTIMAS RESERVAS -->
        <div class="modal fade slide-up in" id="modalLastBooks" tabindex="-1" role="dialog" aria-hidden="true" >
            <div class="modal-dialog modal-lg" style="margin: 0;">
                <div class="modal-content-wrapper">
                    <div class="modal-content">

                    </div>
                </div>
            </div>
        </div>

        <!-- ALERTAS DE BOOKING -->

        <div class="modal fade slide-up in" id="modalAlertsBooking" tabindex="-1" role="dialog" aria-hidden="true" >
            <div class="modal-dialog modal-lg" style="margin: 0;">
                <div class="modal-content-wrapper">
                    <div class="modal-content">

                    </div>
                </div>
            </div>
        </div>

        <!-- IMAGENES POR PISO -->
        <div class="modal fade slide-up in" id="modalRoomImages" tabindex="-1" role="dialog" aria-hidden="true" >
            <div class="modal-dialog modal-lg" style="width: 85%;">
                <div class="modal-content-wrapper">

                    <div class="modal-content" style="max-height: 800px; overflow-y: auto;">

                    </div>
                </div>
            </div>
        </div>

        <!-- CALENDARIO DE BOOKING -->
        <div class="modal fade slide-up in" id="modalCalendarBooking" tabindex="-1" role="dialog" aria-hidden="true" >
            <div class="modal-dialog modal-lg" style="float: left; margin-left: 5%;">
                <div class="modal-content-wrapper">

                    <div class="modal-content">

                    </div>
                </div>
            </div>
        </div>

        <!-- RESPUESTA POR EMAIL AL CLIENTE  -->
        <button style="display: none;" id="btnContestado" class="btn btn-success btn-cons m-b-10" type="button" data-toggle="modal" data-target="#modalContestado"> </button>
        <div class="modal fade slide-up in" id="modalContestado" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content-wrapper">
                    <div class="modal-content contestado" id="contentEmailing"></div>
                </div>
            </div>
        </div>
        




        
    <?php endif ?>


@endsection

@section('scripts')    
    <script type="text/javascript" src="{{ asset('/pages/js/bootstrap-notify.js')}}"></script>
    <script type="text/javascript" src="{{asset('/frontend/js/components/moment.js')}}"></script>
    <script type="text/javascript" src="{{asset('/frontend/js/components/daterangepicker.js')}}"></script>

    <script src="/assets/js/notifications.js" type="text/javascript"></script>
    <script type="text/javascript">
       
        $(document).ready(function() {


            // Selector de año
            $('#fecha').change(function(event) {

                var year = $(this).val();
                window.location = '/admin/reservas/'+year;

            });

            // Modal de calcular reserva
            $('.btn-calcuteBook').click(function(event) {
                $('#modalCalculateBook .modal-content').empty().load('/admin/reservas/help/calculateBook');
            });

            $('#lastBooks').click(function(event) {
                $('#modalLastBooks .modal-content').empty().load('/admin/reservas/api/lastsBooks');
            });
            

            // Mostrar u ocultar formulario de stripe
            $('#stripePayment').click(function(event) {
                $('#stripe-conten-index').toggle(function() {
                    $('#stripePayment').css('background-color', '#f55753');
                }, function() {
                    $('#stripePayment').css('background-color', '#10cfbd');
                });

            });


            // Alertas de booking

            $('#btnAlertsBookking').click(function(event) {
                $('#modalAlertsBooking .modal-content').empty().load('/admin/reservas/api/alertsBooking');
            });

            // Cargar tablas de reservas
            $('.btn-tables').click(function(event) {
                var type = $(this).attr('data-type');
                var year = $('#fecha').val();
                $.get('/admin/reservas/api/getTableData', { type: type, year: year }, function(data) {
                    
                    $('.content-tables').empty().append(data);

                });


            });


            // Cargamos el calendario cuando acaba de cargar la pagina
            setTimeout(function(){ 
                $('.content-calendar').empty().load('/getCalendarMobile');
            }, 1500);
            

            // CARGAMOS POPUP DE CALENDARIO BOOKING
            $('.btn-calendarBooking').click(function(event) {
                $('#modalCalendarBooking .modal-content').empty().load('/admin/reservas/api/calendarBooking');
            });

            // Buscador al vuelo de reservas por nombre del cliente

            $('.searchabled').keyup(function(event) {
                var searchString = $(this).val();
                var year = '<?php echo $inicio->copy()->format('Y')?>';

                $.get('/admin/reservas/search/searchByName', { searchString: searchString,  year: year}, function(data) {

                    if ( data == 0) {
                        $('#resultSearchBook').empty();
                        $('#resultSearchBook').hide();
                        $('.content-tables').show();
                       
                    } else {
                        $('#resultSearchBook').empty();
                        $('#resultSearchBook').append(data);

                        $('.content-tables').hide();
                        $('#resultSearchBook').show();
                    }
                    
                });
            });








       });
        
    </script>

    

@endsection