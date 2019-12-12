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
        .sendFianza {
          border: 3px solid;
          border-radius: 50%;
          padding: 3px;
          width: 29px;
          font-size: 18px;
        }
        .tooltiptext {
          position: absolute;
          font-size: 11px;
          color: #fff;
          background-color: rgba(0, 0, 0, 0.42);
          padding: 2px 5px;
          width: 10em;
          border-radius: 7px;
        }
        .tooltiptext.FF_resume{
          color: inherit;
          text-align: left;
          z-index: 9;
          width: 22em;
          display: none;
        }
        .tooltiptext.FF_resume p{
          color: #fff;
        }
        .showFF_resume:hover .tooltiptext.FF_resume{
          display: block;
        }
        .tooltiptext.FF_resume .table tbody tr td{
          padding: 8px !important;
          font-size: 1.3em;
          font-weight: 600;
        }
        .tooltiptext.FF_resume span.Pendiente {
          color: red;
        }
        .th-w125{
          min-width: 125px;
        }
        .btn-tabs .btn{
          width: 13%;
          padding: 8px 0;
          margin-bottom: 5px;
          min-width: 82px;
        }
        
        .btn-tabs .btn span.bold {
            max-width: 60%;
            display: inline-block;
            overflow: hidden;
        }
        .table tbody tr td {
          background: transparent;
        }
        select.status.form-control.minimal {
            padding: 0;
        }
        @media only screen and (max-width: 991px){
          select#schedule,
          select#scheduleOut {
            width: 3em !important;
            background-color: transparent;
            height: 2em;
          }
          .static-td{
            width: 130px;
            color: black;
            overflow-x: scroll;
            padding: 7px 3px !important;
            background-color: #fff !important;
            margin-top: 1px;
            height: 36px;
            z-index: 100 !important;
            border-right: 1px solid #5f5d5d !important;
          }
          .Reservado .static-td{       
            background-color: #bdf9bd  !important;
          }
          .Reservado .static-td{       
            background-color: #bdf9bd  !important;
          }
          .Blocked-ical  .static-td{   
            background-color: #c1c1c1 !important;
          }
          .tooltip-2 {
            position: relative !important;
          }
        }
    </style>
@endsection

@section('content')
  @if ($errors->any())
    <div class="alert alert-danger">
      {{ implode('', $errors->all(':message')) }}
    </div>
  @endif
    <?php if (!$mobile->isMobile() ): ?>
        <div class="container-fluid  p-l-15 p-r-15 p-t-20 bg-white">
            @include('backend.years.selector', ['minimal' => false])
            @include('backend.planning._buttons_top',[
            'alarms'=>$alarms,
            'lastBooksPayment'=>$lastBooksPayment,
            'alert_lowProfits'=>$alert_lowProfits,
            'parteeToActive'=>$parteeToActive,
            'ff_pendientes'=>$ff_pendientes
            ])
         
        </div>

        <div class="col-md-7">
                <?php if ( Auth::user()->role != "agente" ): ?>
            <div class="row push-10">
                    <div class="col-md-5 col-xs-12">
                        <input id="nameCustomer" type="text" name="searchName" class="searchabled form-control" placeholder="nombre del cliente" />
                    </div>
                </div>
            <?php endif ?>
            <div class="col-xs-12 text-left push-0 btn-tabs" style="padding-left: 0;">
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
              @if($ff_mount !== null)
              <div class="row">
                <div class="col-xs-4" style="margin-top: 11px;">
                  <div class="bordered bg-white p-8 ">
                    <strong class="hint-text bold black">Wallet de Forfait Express</strong>
                    <h3 class="text-center <?php if($ff_mount<100) echo 'text-danger';?>"><?php echo number_format($ff_mount, 0, ',', '.')?>€</h3>
                  </div>
                </div>
                
                <div class="col-xs-8">
                    <!-- www.tutiempo.net - Ancho:446px - Alto:89px -->
                    <div id="TT_FyTwLBdBd1arY8FUjfzjDjjjD6lUMWzFrd1dEZi5KkjI3535G"> </div>
                    <script type="text/javascript" src="https://www.tutiempo.net/s-widget/l_FyTwLBdBd1arY8FUjfzjDjjjD6lUMWzFrd1dEZi5KkjI3535G"></script>
                </div>
              </div>
              @else
                <div class="col-xs-12">
                    <!-- www.tutiempo.net - Ancho:446px - Alto:89px -->
                    <div id="TT_FyTwLBdBd1arY8FUjfzjDjjjD6lUMWzFrd1dEZi5KkjI3535G"> </div>
                    <script type="text/javascript" src="https://www.tutiempo.net/s-widget/l_FyTwLBdBd1arY8FUjfzjDjjjD6lUMWzFrd1dEZi5KkjI3535G"></script>
                </div>
              @endif
                <div class="row content-calendar push-20" style="min-height: 515px;">
                    <div class="col-xs-12 text-center sending" style="padding: 120px 15px;">
                        <i class="fa fa-spinner fa-5x fa-spin" aria-hidden="true"></i><br>
                        <h2 class="text-center">CARGANDO CALENDARIO</h2>
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
                        <div class="modal-content" id="_alarmsPartee">
                            
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
            .btn-cons{
                min-width: auto!important;
            }
        </style>

        <div class="container-fluid  p-l-15 p-r-15 p-t-20 bg-white">
            <div class="row push-10">
            <div class="container">
                <div class="col-xs-12 text-center">
                     @include('backend.years.selector', ['minimal' => false])
                </div>
            </div>
            @include('backend.planning._buttons_top',[
            'alarms'=>$alarms,
            'lastBooksPayment'=>$lastBooksPayment,
            'alert_lowProfits'=>$alert_lowProfits,
            'parteeToActive'=>$parteeToActive,
            ])
        </div>
        <div class="row push-20">
            <div class="col-md-7">
                <div class="row push-10">
                    <div class="col-md-5 col-xs-12">
                        <input id="nameCustomer" type="text" name="searchName" class="searchabled form-control" placeholder="nombre del cliente" />
                    </div>
                </div>
                <div class="row text-left push-0" style="overflow-x:auto;">
                    <div class="btn-tabs">
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
                  <div class="modal-content" id="_alarmsPartee">
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
        
         <?php if (Auth::user()->role != "agente"): ?>
          <!-- GENERADOR DE LINKS PAYLAND  -->
           <div class="modal fade slide-up in" id="modalLinkStrip" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-xd">
              <div class="modal-content-classic">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="position: absolute; top: 0px; right: 10px; z-index: 100">
                    <i class="fa fa-times fa-2x" style="color: #000!important;"></i>
                </button>
                @include('backend.stripe.link')
                 </div>
               </div>
              </div>
        <?php endif ?>
          
          
        <div class="modal fade slide-up in" id="modalICalImport" tabindex="-1" role="dialog" aria-hidden="true" >
          <div class="modal-dialog modal-xd">
            <div class="modal-content-classic">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="position: absolute; top: 0px; right: 10px; z-index: 100">
                <i class="fa fa-times fa-2x" style="color: #000!important;"></i>
              </button>
              <div class="row">
                <div class="col-md-7"  id="modal_ical_content"></div>
                <div class="col-md-5">
                  <button id="syncr_ical" class="btn btn-primary">Sincronizar <i class="fa fa-refresh"></i></button>
                </div>
              </div>
              <p class="alert alert-success" id="syncr_ical_succss" style="display: none;">Sincronización enviada</p>
            </div>
          </div>
        </div>
          
        <div class="modal fade slide-up in" id="modalSendPartee" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-xd">
              <div class="modal-content-classic">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="position: absolute; top: 0px; right: 10px; z-index: 100">
                  <i class="fa fa-times fa-2x" style="color: #000!important;"></i>
                </button>
                <h3 id="modalSendPartee_title"></h3>
                <div class="row" id="modalSendPartee_content" style="margin-top:1em;">
                </div>
              </div>
            </div>
        </div>
          <form method="post" id="formFF" action=""  <?php if (!$mobile->isMobile()){ echo 'target="_blank"';} ?>>
              <input type="hidden" name="admin_ff" id="admin_ff">
            </form>
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
        
        $('#sendImportICal').click(function(event) {
          event.preventDefault()
          $('#modalICalImport').modal('show'); 
          $('#modal_ical_content').load("{{ url('ical/getLasts') }}");
        });
        $('#syncr_ical').click(function(event) {
          event.preventDefault()
          $('#syncr_ical_succss').hide();
          var icon = $(this).find('.fa');
          icon.addClass('fa-spin');
          
          var request = $.ajax({url: "{{ url('admin/ical/importFromUrl') }}",method: "GET"});
          request.done(function( msg ) {
            if (msg == 'ok'){
              $('#modal_ical_content').load("{{ url('ical/getLasts') }}");
              $('#syncr_ical_succss').show();
             } else {
               alert( "Sync failed: " + msg );
             }
            icon.removeClass('fa-spin');
          });

          request.fail(function( jqXHR, textStatus ) {
            alert( "Request failed: " + textStatus );
            icon.removeClass('fa-spin');
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
        // CARGAMOS POPUP DE CALENDARIO BOOKING
        $('#btnParteeToActive').click(function(event) {
          $('#modalParteeToActive').modal('show');
          $('#_alarmsPartee').empty().load('/admin/get-partee');
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
 
      $('body').on('click','.openFF', function (event) {
        
        event.preventDefault();
        var id = $(this).data('booking');
        $.post('/admin/forfaits/open', { _token: "{{ csrf_token() }}",id:id }, function(data) {
//          console.log(data);
          var formFF = $('#formFF');
          formFF.attr('action', data.link);
          formFF.find('#admin_ff').val(data.admin);
          formFF.submit();
        });
      });

      });
      
      
      //Fianzas
      $('body').on('click','.sendFianza', function (event) {
        var bID = $(this).data('id');
        $.ajax({
          url: '/ajax/showFianza/'+bID,
          type: 'GET',
          success: function (response) {
            $('#modalSendPartee_content').html(response);
            $('#modalSendPartee_title').html('Fianza');
            $('#modalSendPartee').modal('show');
          },
          error: function (response) {
            alert('No se ha podido obtener los detalles de la consulta.');
          }
        });
      });
      $('body').on('click','.copyMsgFianza', function (event) {
        var data = $(this).data('msg');
        var dummy = document.createElement("textarea");
        $('#copyMsgFianza').append(dummy);
        //Be careful if you use texarea. setAttribute('value', value), which works with "input" does not work with "textarea". – Eduard
        dummy.value = data;
        dummy.select();
        document.execCommand("copy");
        $('#copyMsgFianza').html('');

        alert('Mensaje Fianza Copiado');
      });
      $('body').on('click','.sendFianzaMail',function(event) {
        var id = $(this).data('id');
        var that = $(this);
        if (that.hasClass('disabled-error')) {
          alert('Fianza error.');
          return ;
        }
        if (that.hasClass('disabled')) {
          return ;
        }
        $('#loadigPage').show('slow');
        that.addClass('disabled')
        $.post('/ajax/send-fianza-mail', { _token: "{{ csrf_token() }}",id:id }, function(data) {
              if (data.status == 'danger') {
                window.show_notif('Fianza Error:',data.status,data.response);
              } else {
                window.show_notif('Fianza:',data.status,data.response);
                that.prop('disabled', true);
              }
              $('#loadigPage').hide('slow');
          });
        });
        $('body').on('click','.showParteeLink',function(event) {
          $('#linkPartee').show(700);
        });
        
      $('body').on('click','.sendFianzaSMS',function(event) {
        var id = $(this).data('id');
        var that = $(this);
        if (that.hasClass('disabled-error')) {
          alert('Fianza error.');
          return ;
        }
        if (that.hasClass('disabled')) {
//          alert('No se puede enviar el SMS.');
          return ;
        }
        $('#loadigPage').show('slow');
        that.addClass('disabled')
        $.post('/ajax/send-fianza-sms', { _token: "{{ csrf_token() }}",id:id }, function(data) {
              if (data.status == 'danger') {
                window.show_notif('Fianza Error:',data.status,data.response);
              } else {
                window.show_notif('Fianza:',data.status,data.response);
                that.prop('disabled', true);
              }
              $('#loadigPage').hide('slow');
          });
        });
        
      $('body').on('click','.sendPayment',function(event) {
        var id = $(this).data('id');
        var amount = $('#amount_fianza').val();
        var that = $(this);
        if (that.hasClass('disabled-error')) {
          alert('Fianza error.');
          return ;
        }
        if (that.hasClass('disabled')) {
//          alert('No se puede enviar el SMS.');
          return ;
        }
        $('#loadigPage').show('slow');
        
        $.post('/admin/pagos/cobrar', { _token: "{{ csrf_token() }}",id:id,amount:amount }, function(data) {
              if (data.status == 'danger') {
                window.show_notif('Fianza Error:',data.status,data.response);
              } else {
                window.show_notif('Fianza:',data.status,data.response);
                that.addClass('disabled')
                that.prop('disabled', true);
              }
              $('#loadigPage').hide('slow');
          });
      });
      
      $('body').on('click','.createFianza',function(event) {
        var id = $(this).data('id');
        var that = $(this);
        if (that.hasClass('disabled-error')) {
          alert('Fianza error.');
          return ;
        }
        $('#loadigPage').show('slow');
        that.addClass('disabled')
        $.get('/admin/createFianza/'+id, { _token: "{{ csrf_token() }}"}, function(data) {
              if (data.status == 'danger') {
                window.show_notif('Fianza Error:',data.status,data.response);
              } else {
                window.show_notif('Fianza:',data.status,data.response);
                that.prop('disabled', true);
              }
              $('#loadigPage').hide('slow');
          });
      });
      
      
      var loadFF_resume = true;
      $('body').on('mouseover','.showFF_resume',function(){
        var id = $(this).data('booking');
          if (loadFF_resume != id){
            var tooltip = $(this).find('.FF_resume');
            tooltip.load('/admin/forfaits/resume-by-book/'+id);
            loadFF_resume = id;
          }
      });



    </script>



@endsection