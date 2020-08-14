<?php   use \Carbon\Carbon;
setlocale(LC_TIME, "ES");
setlocale(LC_TIME, "es_ES");
$uRole = Auth::user()->role;
$is_mobile = $mobile->isMobile();
?>
@extends('layouts.admin-master')

@section('title') Administrador de reservas MiramarSKI @endsection

@section('externalScripts')

    <link href="/assets/css/font-icons.css" rel="stylesheet" type="text/css" />
    <link href="/assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" type="text/css" media="screen">
    <link rel="stylesheet" href="{{ asset('/frontend/css/components/daterangepicker.css')}}" type="text/css" />
    <link rel="stylesheet" href="{{ assetV('/css/backend/planning.css')}}" type="text/css" />
    <script type="text/javascript" src="{{ assetV('/js/backend/buzon.js')}}"></script>
    
@endsection

@section('content')
  @if ($errors->any())
    <div class="alert alert-danger">
      {{ implode('', $errors->all(':message')) }}
    </div>
  @endif
    <?php if (!$is_mobile ): ?>
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
                <?php if ( $uRole != "agente" ): ?>
            <div class="row push-10">
                    <div class="col-md-5 col-xs-12">
                        <input id="nameCustomer" type="text" name="searchName" class="searchabled form-control" placeholder="nombre del cliente" />
                    </div>
                </div>
            <?php endif ?>
          <div class="btn-tabs">
          @include('backend.planning.blocks.buttons_table_tabs')
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
                    
                </div>
              </div>
              @else
                <div class="col-xs-12">
                    <!-- www.tutiempo.net - Ancho:446px - Alto:89px -->
                    <div id="TT_FyTwLBdBd1arY8FUjfzjDjjjD6lUMWzFrd1dEZi5KkjI3535G"> </div>
                    
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
               <div class="row">
                <div class="col-md-12 col-xs-5 title-year-selector">
                   <h2>Planning</h2>
                </div>
                <div class="col-md-12 col-xs-7">
                   @include('backend.years._selector')
                </div>
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
                    @include('backend.planning.blocks.buttons_table_tabs')
                  </div>
                </div>
            </div>
            <div class="row" id="resultSearchBook" style="display: none;"></div>
            <div class="row content-tables" >
                @include('backend.planning._table', ['type'=> 'pendientes'])
            </div>
            <div class="col-md-5" style="overflow: auto;">
                <div class="row content-calendar calendar-mobile" style="min-height: 485px;">
                    <div class="col-xs-12 text-center sending" style="padding: 120px 15px;">
                        <i class="fa fa-spinner fa-5x fa-spin" aria-hidden="true"></i><br>
                        <h2 class="text-center">CARGANDO CALENDARIO</h2>
                    </div>
                </div>

                <div class="col-xs-12">
                    <!-- www.tutiempo.net - Ancho:446px - Alto:89px -->
                    
                    <div id="TT_FyTwLBdBd1arY8FUjfzjDjjjD6lUMWzFrd1dEZi5KkjI3535G">El tiempo - Tutiempo.net</div>
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
        
         <?php if ($uRole != "agente"): ?>
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
                  <a href="/admin/ical/importFromUrl?detail"class="btn btn-secondary">iCal con LOGs</a>
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
          <form method="post" id="formFF" action=""  <?php if (!$is_mobile){ echo 'target="_blank"';} ?>>
              <input type="hidden" name="admin_ff" id="admin_ff">
            </form>
     
          
<div class="modal fade" id="modalChangeBook" tabindex="-1" role="dialog"  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <strong class="modal-title" id="modalChangeBookTit" style="font-size: 1.4em;">Cambiar Reserva</strong>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="btnChangeBook" value="">
        <div id="modalChangeBook_room" style="display:none;">
          <?php foreach ($rooms as $room): ?>
            <?php if ($room->state == 0) continue; ?>
          <button 
            class="btn btnChangeRoom" 
            id="btn_CR{{$room->id}}"
            data-id="{{$room->id}}" 
            >
            <?php echo substr($room->nameRoom . " - " . $room->name, 0, 15) ?>
          </button>
          <?php endforeach ?>
        </div>
        
        <div id="modalChangeBook_status" style="display:none;">
          <?php $bookAux = new App\Book(); ?>
          <?php for ($i=1; $i < 13; $i++): ?> 
          <button 
            class="btn btnChangeStatus" 
            id="btn_CS{{$i}}"
            data-id="{{$i}}" 
            >
            <?php echo $bookAux->getStatus($i) ?>
          </button>
          <?php endfor ?>
          <button 
            class="btn btnChangeStatus" 
            id="btn_CS99"
            data-id="99" 
            >
            <?php echo $bookAux->getStatus(99) ?>
          </button>
          <button 
            class="btn btnChangeStatus" 
            id="btn_CS98"
            data-id="98" 
            >
            <?php echo $bookAux->getStatus(98) ?>
          </button>
        </div>
        
        
        
      </div>
    </div>
  </div>
</div>
          
<div class="modal fade slide-up in" id="modalBookSafetyBox" tabindex="-1" role="dialog" aria-hidden="true" >
     <div class="modal-dialog modal-lg">
         <div class="modal-content-wrapper">
             <div class="modal-content" id="_BookSafetyBox">

             </div>
         </div>
     </div>
 </div>
<div class="modal fade slide-up in" id="modalSafetyBox" tabindex="-1" role="dialog" aria-hidden="true" style=" z-index: 9999;">
<div class="modal-dialog modal-xd">
 <div class="modal-content-classic">
   <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="position: absolute; top: 0px; right: 10px; z-index: 100">
     <i class="fa fa-times fa-2x" style="color: #000!important;"></i>
   </button>
   <h3 id="modalSafetyBox_title"></h3>
   <div class="row" id="modalSafetyBox_content" style="margin-top:1em;">
   </div>
 </div>
</div>
</div>
          
          
<div class="modal fade" id="modalIntercambio" tabindex="-1" role="dialog"  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <strong class="modal-title" style="font-size: 1.4em;">Intercambio de Habitaciones</strong>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body contentModalIntercambio">
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')

    <script type="text/javascript" src="{{asset('/frontend/js/components/moment.js')}}"></script>
    <script type="text/javascript" src="{{asset('/frontend/js/components/daterangepicker.js')}}"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.20/datatables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/3.3.0/js/dataTables.fixedColumns.min.js"></script>


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
        $('.btn_intercambio').click(function(event) {
          $('#modalIntercambio .contentModalIntercambio').empty().load('/admin/reservas/api/intercambio');
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
        
        $('.sendImportICal').click(function(event) {
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
      
//        $('.cargar_calend').on('click',function(){
//          $('.content-calendar').empty().load('/getCalendarMobile');
//          $(this).remove();
//        });
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
      
      
$('body').on('click','.deleteBook',function(event) {
      if(!confirm('¿Quieres Eliminar la reserva?')) return false;
      
        var id = $(this).attr('data-id');
    	$.get('/admin/reservas/delete/'+id, function(data) {

    		$.notify({
                title: '<strong>'+data.title+'</strong>, ',
                icon: 'glyphicon glyphicon-star',
                message: data.response
                },{
                type: data.status,
                animate: {
                    enter: 'animated fadeInUp',
                    exit: 'animated fadeOutRight'
                },
                placement: {
                    from: "top",
                    align: "left"
                },
                allow_dismiss: false,
                offset: 80,
                spacing: 10,
                z_index: 1031,
                delay: 5000,
                timer: 1500
                }); 

                if (data.title == 'OK'){
                  // recargamos la actual tabla
                  var type = $('.table-data').attr('data-type');
                  var year = $('#fecha').val();
                  $.get('/admin/reservas/api/getTableData', { type: type, year: year }, function(data) {
                      $('.content-tables').empty().append(data);
                  });

                  // recargamos el calendario

                  $('.content-calendar').empty().append('<div class="col-xs-12 text-center sending" style="padding: 120px 15px;"><i class="fa fa-spinner fa-5x fa-spin" aria-hidden="true"></i><br><h2 class="text-center">CARGANDO CALENDARIO</h2></div>');

                  $('.content-calendar').empty().load('/getCalendarMobile');
                }


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
      
      
      
      ///////////////////////////////////////////////
      
      @if (!($uRole == "limpieza") || ($uRole == "agente"))
        $('body').on('click','.changeRoom',function(){
          var bID = $(this).closest('tr').data('id');
          var current = $(this).data('c');
          
          $('#modalChangeBook_room').show();
          $('#modalChangeBook_status').hide();
          $('#btnChangeBook').val(bID);
          $('#modalChangeBookTit').text('Cambiar Apartamento');
          $('.btnChangeRoom').removeClass('active');
          $('#btn_CR'+current).addClass('active');
          $('#modalChangeBook').modal('show'); 
        });
        $('body').on('click','.changeStatus',function(){
          var bID = $(this).closest('tr').data('id');
          var current = $(this).data('c');
          
          $('#modalChangeBookTit').text('Cambiar Estado');
          $('#modalChangeBook_room').hide();
          $('#modalChangeBook_status').show();
          $('#btnChangeBook').val(bID);
          $('.btnChangeStatus').removeClass('active');
          $('#btn_CS'+current).addClass('active');
          $('#modalChangeBook').modal('show'); 
        });
      
      // Cambiamos las reservas
	$('.btnChangeRoom, .btnChangeStatus').on('click',function(event) {
	    var id = $('#btnChangeBook').val();
	    if ($(this).hasClass('btnChangeStatus')) {
	        var status = $(this).data('id');
	        var room = "";
	    }else if ($(this).hasClass('btnChangeRoom')) {
	        var room = $(this).data('id');
	        var status = "";
	    }
            $('#modalChangeBook').modal('hide');
	    if (status == 5) {
	        $('.modal-content.contestado').empty().load('/admin/reservas/ansbyemail/'+id);
	        $('#btnContestado').trigger('click');      
	    }else{
	       	$.get('/admin/reservas/changeBook/'+id, {status:status,room: room}, function(data) {
	            if (data.status == 'danger') {
                      window.show_notif(data.title,data.status,data.response);
	            } else {
                      window.show_notif(data.title,data.status,data.response);
	            }
	            var type = $('.table-data').attr('data-type');
                    var year = $('#fecha').val();
                    $.get('/admin/reservas/api/getTableData', { type: type, year: year }, function(data) {
		            $('.content-tables').empty().append(data);
		        });
	       }); 
	    }
	});
      @endif
      
      var load_comment = true;
      $('body').on('mouseover','.showBookComm',function(){
        var id = $(this).data('booking');
          if (load_comment != id){
            var tooltip = $(this).find('.BookComm');
            tooltip.load('/ajax/get-book-comm/'+id);
            load_comment = id;
          }
      });
      
      ///////////////////////////////////////////////

      setTimeout(
        function () {
          var tutiempo_script = document.createElement('script');
          tutiempo_script.setAttribute('src', "https://www.tutiempo.net/s-widget/l_FyTwLBdBd1arY8FUjfzjDjjjD6lUMWzFrd1dEZi5KkjI3535G");
          document.body.appendChild(tutiempo_script);
        }, 700);
      
      $('body').on('click','.send_encuesta',function(event) {
        var id = $(this).data('id');
        var that = $(this);
        if (!confirm('Enviar encuesta por mail?')) {
          return ;
        }
        $('#loadigPage').show('slow');
        
        $.get('/admin/sendEncuesta/'+id, function(data) {
              if (data == 'ok') {
                window.show_notif('OK','success','Encuesta enviada');
                that.addClass('disabled')
                that.prop('disabled', true);
              } else {
                window.show_notif('Error','danger',data);
              }
              $('#loadigPage').hide('slow');
          });
      });
      
      
          
  // CARGAMOS POPUP DE CLIENTES POSIBLES
  $('#btnCustomersRequest').click(function (event) {
    $('#modalBookSafetyBox').modal('show');
    $('#_BookSafetyBox').empty().load('/admin/get-CustomersRequest');
  });

  $('#_BookSafetyBox').on('click','.hideCustomerRequest',function(){
    var ObjTR = $(this).closest('tr');
    var id = $(this).data('id');
    $.post('/admin/hideCustomersRequest', {_token: "{{ csrf_token() }}", id: id}, function (data) {
      ObjTR.remove();
    });
  });
  $('#_BookSafetyBox').on('click','.filterSite',function(){
    var site_id = $(this).data('key');
    $('.filterSite').removeClass('active');
    $(this).addClass('active');
    $('#_BookSafetyBox tr').each(function(item){
      if (item == 0){
         $(this).show();
      } else {
        if (site_id>0){
          if ($(this).data('site') == site_id) $(this).show();
          else  $(this).hide();
        } else {
          $(this).show();
        }
      }
      console.log(item);
    });
  });
 });

    </script>



@endsection