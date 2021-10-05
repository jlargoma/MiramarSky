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
          <?php for ($i = 1; $i < 13; $i++): ?> 
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