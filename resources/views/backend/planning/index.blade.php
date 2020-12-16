<?php   use \Carbon\Carbon;
setlocale(LC_TIME, "ES");
setlocale(LC_TIME, "es_ES");
$uRole = getUsrRole();
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
    <style>
      div#contentEmailing {
          overflow: auto !important;
          max-height: 88vh !important;
      }
      #modalLastBooks .btn.active{
        background-color: #1e416c;
        color: #FFF;
      }
      #modalLastBooks tr.cancel,
      #modalLastBooks tr.cancel a{
          color: red;
      }
    </style>
    <script src="{{ asset('/vendors/ckeditor/ckeditor.js') }}"></script>
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
            @include('backend.planning.blocks._buttons_top',[
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
        <!-- ÚLTIMAS RESERVAS -->
        <div class="modal fade slide-up in" id="modalLastBooks" tabindex="-1" role="dialog" aria-hidden="true" >
                <div class="modal-dialog modal-lg">
                    <div class="modal-content-wrapper">
                        <div class="modal-content">

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
                min-width: 3em!important;
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
            @include('backend.planning.blocks._buttons_top',[
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
@endsection

@section('scripts')

  <script type="text/javascript">
     window["csrf_token"] = "{{ csrf_token() }}";
     window["uRole"] = "{{ $uRole }}";
  </script>

  <script type="text/javascript" src="{{asset('/frontend/js/components/moment.js')}}"></script>
  <script type="text/javascript" src="{{asset('/frontend/js/components/daterangepicker.js')}}"></script>

  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
  <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.20/datatables.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/3.3.0/js/dataTables.fixedColumns.min.js"></script>


  <script src="/assets/js/notifications.js" type="text/javascript"></script>
  <script src="{{assetV('/js/backend/planning.js')}}" type="text/javascript"></script>
  <script src="{{assetV('/js/backend/booking_script.js')}}" type="text/javascript"></script>
  <?php if (Auth::user()->defaultTable != ''): ?>
  <script type="text/javascript">
    $(document).ready(function() {
      var type = '<?php echo Auth::user()->defaultTable ?>';
      $('button[data-type="'+type+'"]').trigger('click');
    });
  </script>
  <?php endif ?>



@endsection