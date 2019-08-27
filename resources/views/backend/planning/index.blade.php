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
    <style>
        #TT_FyTwLBdBd1arY8FUjfzjDjjjD6lUMWzFrd1dEZi5KkjI3535G{
            margin: 10px auto;
        }
        .table-data .form-control{
            width: 100%;
        }
    </style>
@endsection

@section('content')
    <?php if (!$mobile->isMobile() ): ?>
        <div class="container-fluid  p-l-15 p-r-15 p-t-20 bg-white">
            @include('backend.years.selector', ['minimal' => false])
            <div class="row push-10">
                <div class="col-md-7">
                    <div class="row btn-mb-1">
                        <button class="btn btn-success btn-cons btn-newBook" type="button" data-toggle="modal" data-target="#modalNewBook">
                            <i class="fa fa-plus-square" aria-hidden="true"></i> <span class="bold">Nueva Reserva</span>
                        </button>
                        <?php if ( Auth::user()->role != "agente" ): ?>
                        <button id="lastBooks" class="btn btn-success btn-cons" type="button" data-toggle="modal" data-target="#modalLastBooks">
                                <span class="bold">Últimas Confirmadas</span>
                                <span class="numPaymentLastBooks"><?php echo  $stripedsPayments->count(); ?></span>
                            </button>

                            <button class="btn btn-success btn-cons" type="button" id="stripePayment">
                                <i class="fa fa-money" aria-hidden="true"></i> <span class="bold">Cobros TPV</span>
                            </button>

                            <button class="btn btn-success btn-calcuteBook btn-cons" type="button" data-toggle="modal" data-target="#modalCalculateBook">
                                <span class="bold">Calcular reserva</span>
                            </button>

                        <button class="btn btn-danger btn-cons btn-blink <?php if(count($alarms)>0) echo 'btn-alarms'; ?>" type="button" data-toggle="modal" data-target="#modalAlarms">
                            <i class="fa fa-bell" aria-hidden="true"></i> <span class="bold">ALARMAS</span>
                            <span class="numPaymentLastBooks"><?php echo  count($alarms); ?></span>
                        </button>
                      <button class="btn btn-danger btn-cons btn-blink <?php if($alert_lowProfits) echo 'btn-alarms'; ?> "  id="btnLowProfits" type="button" data-toggle="modal" data-target="#modalLowProfits">
                            <i class="fa fa-bell" aria-hidden="true"></i> <span class="bold">BAJO BENEFICIO</span>
                            <span class="numPaymentLastBooks" data-val="{{$alert_lowProfits}}">{{$alert_lowProfits}}</span>
                        </button>
                      <button class="btn btn-danger btn-cons btn-blink <?php if(count($parteeToActive)>0) echo 'btn-alarms'; ?> "  id="btnParteeToActive" type="button" data-toggle="modal" data-target="#modalParteeToActive">
                            <i class="fa fa-bell" aria-hidden="true"></i> <span class="bold">PARTEE</span>
                            <span class="numPaymentLastBooks"><?php echo  count($parteeToActive); ?></span>
                        </button>
                        <?php endif ?>
                    </div>
                </div>
                <div class="col-md-5">
                    <?php if ( Auth::user()->role != "agente" ): ?>
                    <button id="btnAlertsBookking" disabled class="btn btn-success btn-cons " type="button" data-toggle="modal" data-target="#modalAlertsBooking">
                        <span class="bold">Alertas booking</span>
                    <!--<span class="numPaymentLastBooks"><?php //echo  $notifications ?></span> -->
                    </button>

                    <button class="btn btn-primary btn-calendarBooking btn-cons" type="button" data-toggle="modal" data-target="#modalCalendarBooking">
                        <span class="bold">Calendario booking</span>
                    </button>

                    <a href="{{ url('ical/importFromUrl') }}" class="btn btn-primary btn-cons" <?php if ( count( \App\IcalImport::all() ) == 0): ?> disabled="" <?php endif ?> style="background-color: #337ab7; border-color: #2e6da4;">
                        <span class="bold">IMPORTACIÓN</span>
                    </a>
                     <button class="btn btn-primary btn-cupos btn-cons" type="button" data-toggle="modal" data-target="#modalCuposVtn">
                        <span class="bold">Cupos Vtn Rapida</span>
                    </button>
                    <?php endif ?>
                </div>
            </div>
        </div>

        <div class="col-md-7">
                <?php if ( Auth::user()->role != "agente" ): ?>
            <div class="row push-10">
                    <div class="col-md-5 col-xs-12">
                        <input id="nameCustomer" type="text" name="searchName" class="searchabled form-control" placeholder="nombre del cliente" />
                    </div>
                </div>
            <?php endif ?>
            <div class="col-xs-12 text-left push-0" style="padding-left: 0;">

                    <button class="btn btn-primary  btn-blue btn-tables btn-cons" type="button" data-type="pendientes">
                        <span class="bold">Pendientes</span>
                        <?php if ( Auth::user()->role != "agente" ): ?>
                        <span class="numPaymentLastBooks">
                                {{ $booksCount['pending'] }}
                            </span>
                        <?php endif ?>
                    </button>
                <?php if ( Auth::user()->role != "agente" ): ?>
                <button class="btn btn-primary  btn-orange btn-tables btn-cons" type="button" data-type="especiales">
                        <span class="bold">Especiales</span>
                        <span class="text-black" style="background-color: white; font-weight: 600; border-radius: 100%; padding: 5px;">
                                {{ $booksCount['special'] }}
                            </span>
                    </button>
                <?php endif ?>
                <button class="btn  btn-primary btn-green btn-tables btn-cons" type="button" data-type="confirmadas">
                        <span class="bold">Confirmadas</span>
                        <span class="text-black" style="background-color: white; font-weight: 600; border-radius: 100%; padding: 5px;">
                                {{ $booksCount['confirmed'] }}
                            </span>
                    </button>
                <?php if ( Auth::user()->role != "agente" ): ?>
                <button class="btn btn-success btn-tables btn-cons" type="button" data-type="checkin">
                        <span class="bold">Check IN</span>
                        <span class="text-black" style="background-color: white; font-weight: 600; border-radius: 100%; padding: 5px;">
                                {{ $booksCount['checkin'] }}
                            </span>
                    </button>

                    <button class="btn btn-primary btn-tables btn-cons" type="button" data-type="checkout">
                        <span class="bold">Check OUT</span>
                        <span class="text-black" style="background-color: white; font-weight: 600; border-radius: 100%; padding: 5px;">
                                {{ $booksCount['checkout'] }}
                            </span>
                    </button>

                    <button class="btn btn-success btn-grey btn-tables btn-cons" type="button" data-type="blocked-ical">
                        <span class="bold">Blocked ICal</span>
                        <span class="text-black" style="background-color: white; font-weight: 600; border-radius: 100%; padding: 5px;">
                                {{ $booksCount['blocked-ical'] }}
                            </span>
                    </button>

                    <button class="btn btn-danger btn-tables btn-cons" type="button" data-type="eliminadas">
                        <span class="bold">Eliminadas</span>
                        <span class="text-black" style="background-color: white; font-weight: 600; border-radius: 100%; padding: 5px;">
                                {{ $booksCount['deletes'] }}
                            </span>
                        <?php endif ?>
                    </button>

                </div>
                <div class="col-xs-12" id="resultSearchBook" style="display: none; padding-left: 0;"></div>
                <div class="col-xs-12 content-tables" style="padding-left: 0;">
                    @include('backend.planning._table', ['type'=> 'pendientes'])
                </div>

            </div>
        <div class="col-md-5">
                <div class="col-xs-12">
                    <!-- www.tutiempo.net - Ancho:446px - Alto:89px -->
                    <div id="TT_FyTwLBdBd1arY8FUjfzjDjjjD6lUMWzFrd1dEZi5KkjI3535G"> </div>
                    <script type="text/javascript" src="https://www.tutiempo.net/s-widget/l_FyTwLBdBd1arY8FUjfzjDjjjD6lUMWzFrd1dEZi5KkjI3535G"></script>
                </div>
                <div class="row content-calendar push-20" style="min-height: 515px;">
                    <div class="col-xs-12 text-center sending" style="padding: 120px 15px;">
                        <i class="fa fa-spinner fa-5x fa-spin" aria-hidden="true"></i><br>
                        <h2 class="text-center">CARGANDO CALENDARIO</h2>
                    </div>
                </div>
            <?php if ( Auth::user()->role != "agente" ): ?>
            <div class="col-md-12" id="stripe-conten-index" style="display: none;">
                    @include('backend.stripe.link')
                {{--@include('backend.stripe.stripe', ['bookTocharge' => null])--}}
                </div>
            <?php endif ?>
            </div>

        <!-- NUEVAS RESERVAS -->
        <div class="modal fade slide-up in" id="modalNewBook" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content-wrapper">
                        <div class="modal-content contentNewBook">

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

        <div class="modal fade slide-up in" id="modalAlarms" tabindex="-1" role="dialog" aria-hidden="true" >
            <div class="modal-dialog modal-lg">
                <div class="modal-content-wrapper">
                    <div class="modal-content">
                        @include('backend.planning._alarmsBooks', ['alarms' => $alarms])
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade slide-up in" id="modalLowProfits" tabindex="-1" role="dialog" aria-hidden="true" >
                <div class="modal-dialog modal-lg">
                    <div class="modal-content-wrapper">
                        <div class="modal-content">
                            @include('backend.planning._alarmsLowProfits', ['alarms' => $lowProfits])
                        </div>
                    </div>
                </div>
            </div>

        <div class="modal fade slide-up in" id="modalParteeToActive" tabindex="-1" role="dialog" aria-hidden="true" >
                <div class="modal-dialog modal-lg">
                    <div class="modal-content-wrapper">
                        <div class="modal-content">
                            @include('backend.planning._alarmsPartee', ['alarms' => $parteeToActive])
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

        <div class="modal fade slide-up in" id="modalCuposVtn" tabindex="-1" role="dialog" aria-hidden="true" >
                <div class="modal-dialog modal-lg">
                    <div class="modal-content-wrapper">
                        <div class="modal-content" id="content-cupos">
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
        <style>
            #TT_FyTwLBdBd1arY8FUjfzjDjjjD6lUMWzFrd1dEZi5KkjI3535G{
                width: 100%!important;
            }
            .panel-mobile, .table-responsive.content-calendar{
                margin-bottom: 0px;
            }
        </style>

        <div class="container-fluid  p-l-15 p-r-15 p-t-20 bg-white">
            <div class="row push-10">
            <div class="container">
                <div class="col-xs-12 text-center">
                     @include('backend.years.selector', ['minimal' => false])
                </div>
            </div>
            <div class="row push-10">
                <div class="col-xs-12">
                    <div class="row">
                        <?php if ( Auth::user()->role != "agente" ): ?>
                        <div class="col-xs-4 push-10 text-center">
                            <button id="lastBooks" class="btn btn-success btn-sm" type="button" data-toggle="modal" data-target="#modalLastBooks">
                                <span class="bold">Últ. reser</span>
                                <span class="numPaymentLastBooks">&nbsp;<?php echo  $stripedsPayments->count(); ?>&nbsp;</span>
                            </button>
                        </div>
                        <div class="col-xs-2 push-10 text-center">
                            <button class="btn btn-success btn-sm" type="button" id="stripePayment">
                                <i class="fa fa-money" aria-hidden="true"></i>
                            </button>
                        </div>
                        <div class="col-xs-2 push-10 text-center">
                            <button class="btn btn-success btn-calcuteBook btn-sm" type="button" data-toggle="modal" data-target="#modalCalculateBook">
                                <span class="bold"><i class="fa fa-calendar-alt" aria-hidden="true"></i></span>
                            </button>
                        </div>
                        <div class="col-xs-2 push-10 text-center">
                            <button class="btn btn-danger btn-sm btn-alarms" type="button" data-toggle="modal" data-target="#modalAlarms">
                                &nbsp;&nbsp;<i class="fa fa-bell" aria-hidden="true"></i>&nbsp;&nbsp;
                                <span class="numPaymentLastBooks">&nbsp;<?php echo  count($alarms); ?>&nbsp;</span>
                            </button>
                        </div>
                      <div class="col-xs-6 text-center">
                      <button class="btn btn-danger btn-cons btn-blink <?php if($alert_lowProfits) echo 'btn-alarms'; ?> "  id="btnLowProfits" type="button" data-toggle="modal" data-target="#modalLowProfits">
                            <i class="fa fa-bell" aria-hidden="true"></i> <span class="bold">BAJO BENEFICIO</span>
                            <span class="numPaymentLastBooks" data-val="{{$alert_lowProfits}}">{{$alert_lowProfits}}</span>
                        </button>
                        </div>
                      <div class="col-xs-6 text-center">
                      <button class="btn btn-danger btn-cons btn-blink <?php if(count($parteeToActive)>0) echo 'btn-alarms'; ?> "  id="btnParteeToActive" type="button" data-toggle="modal" data-target="#modalParteeToActive">
                            <i class="fa fa-bell" aria-hidden="true"></i> <span class="bold">PARTEE</span>
                            <span class="numPaymentLastBooks"><?php echo  count($parteeToActive); ?></span>
                        </button>
                      </div>

                        <?php endif ?>
                        <div class="col-xs-2 push-10 text-center">
                            <button class="btn btn-primary btn-sm calend" type="button" >
                                <span class="bold"><i class="fa fa-calendar"></i></span>
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12" id="stripe-conten-index" style="display: none;">
            <?php if ( Auth::user()->role != "agente" ): ?>
                @include('backend.stripe.link')
                @include('backend.stripe.stripe', ['bookTocharge' => null])
            <?php endif ?>
        </div>

        <div class="row push-20">
            <div class="col-md-7">
                <div class="row push-10">
                    <div class="col-md-5 col-xs-12">
                        <input id="nameCustomer" type="text" name="searchName" class="searchabled form-control" placeholder="nombre del cliente" />
                    </div>
                </div>
                <div class="row text-left push-0" style="overflow-x:auto;">
                    <div style=" width: 515px;">
                        <button class="btn btn-primary  btn-blue btn-tables" type="button" data-type="pendientes">
                            <span class="bold">Pend</span>
                            <?php if ( Auth::user()->role != "agente" ): ?>
                            <span class="numPaymentLastBooks" style="top: 0px;right: 0;padding: 0px 7px;">
                                    {{ $booksCount['pending'] }}
                                </span>
                            <?php endif ?>
                        </button>
                        <?php if ( Auth::user()->role != "agente" ): ?>
                            <button class="btn btn-primary  btn-orange btn-tables" type="button" data-type="especiales">
                                <span class="bold">Esp</span>
                                <span class="text-black" style="background-color: white; font-weight: 800; border-radius: 100%; padding: 5px;font-size: 10px">
                                        {{ $booksCount['special'] }}
                                    </span>
                            </button>
                        <?php endif ?>
                            <button class="btn  btn-primary btn-green btn-tables" type="button" data-type="confirmadas">
                                <span class="bold">Conf</span>
                                <span class="text-black" style="background-color: white; font-weight: 800; border-radius: 100%; padding: 5px;font-size: 10px">
                                       {{ $booksCount['confirmed'] }}
                                   </span>
                            </button>
                        <?php if ( Auth::user()->role != "agente" ): ?>
                            <button class="btn btn-success btn-tables" type="button" data-type="checkin">
                                <span class="bold">IN</span>
                                <span class="text-black" style="background-color: white; font-weight: 800; border-radius: 100%; padding: 5px;font-size: 10px">
                                         {{ $booksCount['checkin'] }}
                                    </span>
                            </button>

                            <button class="btn btn-primary btn-tables" type="button" data-type="checkout">
                                <span class="bold">OUT</span>
                                <span class="text-black" style="background-color: white; font-weight: 800; border-radius: 100%; padding: 5px;font-size: 10px">
                                          {{ $booksCount['checkout'] }}
                                    </span>
                            </button>


                            <button class="btn btn-primary  btn-grey btn-tables" type="button" data-type="blocked-ical">
                                <span class="bold">ICal</span>
                                <span class="text-black" style="background-color: white; font-weight: 800; border-radius: 100%; padding: 5px;font-size: 10px">
                                        {{ $booksCount['blocked-ical'] }}
                                    </span>
                            </button>

                            <button class="btn btn-danger btn-tables" type="button" data-type="eliminadas">
                                <span class="bold">Elimin...</span>
                                <span class="text-black" style="background-color: white; font-weight: 600; border-radius: 100%; padding: 5px;">
                                        {{ $booksCount['deletes'] }}
                                    </span>
                            </button>
                        <?php endif ?>
                    </div>
                </div>
            </div>
            <div class="row" id="resultSearchBook" style="display: none;"></div>
            <div class="row content-tables" >
                @include('backend.planning._table', ['type'=> 'pendientes'])
            </div>
            <div class="col-md-5">
                <div class="row content-calendar calendar-mobile" style="min-height: 485px;">
                    <div class="col-xs-12 text-center sending" style="padding: 120px 15px;">
                        <i class="fa fa-spinner fa-5x fa-spin" aria-hidden="true"></i><br>
                        <h2 class="text-center">CARGANDO CALENDARIO</h2>
                    </div>
                </div>

                <div class="col-md-12" id="stripe-conten-index" style="display: none;">
                    @include('backend.stripe.stripe', ['bookTocharge' => null])
                </div>

                <div class="col-xs-12">
                    <!-- www.tutiempo.net - Ancho:446px - Alto:89px -->
                    <div id="TT_FyTwLBdBd1arY8FUjfzjDjjjD6lUMWzFrd1dEZi5KkjI3535G">El tiempo - Tutiempo.net</div>
                    <script type="text/javascript" src="https://www.tutiempo.net/s-widget/l_FyTwLBdBd1arY8FUjfzjDjjjD6lUMWzFrd1dEZi5KkjI3535G"></script>
                </div>
            </div>
        </div>

        <!-- NUEVAS RESERVAS -->
        <div class="modal fade slide-up in" id="modalNewBook" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content-wrapper">
                    <div class="modal-content contentNewBook">

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
            <div class="modal-dialog modal-lg" >
                <div class="modal-content-wrapper">
                    <div class="modal-content" style="width: 90%;">

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

        <div class="modal fade slide-up in" id="modalAlarms" tabindex="-1" role="dialog" aria-hidden="true" >
            <div class="modal-dialog modal-xs" >
                <div class="modal-content-wrapper">
                    <div class="modal-content" style="width: 90%;">
                        @include('backend.planning._alarmsBooks', ['alarms' => $alarms])
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade slide-up in" id="modalLowProfits" tabindex="-1" role="dialog" aria-hidden="true" >
            <div class="modal-dialog modal-xs">
                <div class="modal-content-wrapper">
                    <div class="modal-content">
                        @include('backend.planning._alarmsLowProfits', ['alarms' => $lowProfits])
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade slide-up in" id="modalParteeToActive" tabindex="-1" role="dialog" aria-hidden="true" >
            <div class="modal-dialog modal-xs">
                <div class="modal-content-wrapper">
                    <div class="modal-content">
                        @include('backend.planning._alarmsPartee', ['alarms' => $parteeToActive])
                    </div>
                </div>
            </div>
        </div>
        <!-- IMAGENES POR PISO -->
        <div class="modal fade slide-up in" id="modalRoomImages" tabindex="-1" role="dialog" aria-hidden="true" >
            <div class="modal-dialog modal-lg" style="width: 95%;">
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

    <script type="text/javascript" src="{{asset('/frontend/js/components/moment.js')}}"></script>
    <script type="text/javascript" src="{{asset('/frontend/js/components/daterangepicker.js')}}"></script>

    <script src="/assets/js/notifications.js" type="text/javascript"></script>
    <script type="text/javascript">

      $(document).ready(function() {

        // Modal de nueva reserva
        $('.btn-newBook').click(function(event) {

          $.get('/admin/reservas/new', function(data) {

            $('.contentNewBook').empty().append(data);

          });

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
          var year = $('#fechas').val();
          $.get('/admin/reservas/api/getTableData', { type: type, year: year }, function(data) {

            $('#resultSearchBook').empty();

            $('.content-tables').empty().append(data);
            $('.content-tables').show();

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
          if (searchString.length < 3 && searchString.length != 0) {
            return false;
          }
          var year = $('#fechas').val();

          bookSearch(searchString, year);
        });

        var delayTimer;
        function bookSearch(searchString, year) {
          clearTimeout(delayTimer);

          delayTimer = setTimeout(function () {
            $.get('/admin/reservas/search/searchByName', { searchString: searchString,  year: year}, function(data) {
              $('#resultSearchBook').empty();
              $('#resultSearchBook').append(data);
              $('.content-tables').hide();
              $('#resultSearchBook').show();
            }).fail(function() {
              $('#resultSearchBook').empty();
              $('#resultSearchBook').hide();
              $('.content-tables').show();
            });
          }, 300);
        }

              <?php if (Auth::user()->defaultTable != ''): ?>
        var type = '<?php echo Auth::user()->defaultTable ?>';
        $('button[data-type="'+type+'"]').trigger('click');
          <?php endif ?>


          $('.btn-cupos').click(function () {
            $.get('/admin/rooms/cupos', function(data) {

              $('#content-cupos').empty().append(data);

            });
          });


      });

    </script>



@endsection