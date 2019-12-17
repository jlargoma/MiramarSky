<div class="row">
  <div class="col-md-7">
    <div class="row btn-mb-1">
      <button class="btn btn-success btn-cons btn-newBook" type="button" data-toggle="modal" data-target="#modalNewBook">
        <i class="fa fa-plus-square" aria-hidden="true"></i> <span class="bold hidden-mobile" >Nueva Reserva</span>
      </button>
      <?php if (Auth::user()->role != "agente"): ?>
        <button id="lastBooks" class="btn btn-success btn-cons" type="button" data-toggle="modal" data-target="#modalLastBooks">
          <span class="bold hidden-mobile">Últimas Confirmadas</span>
          <span class="bold show-mobile">Últi Confirm</span>
          <?php if ($lastBooksPayment > 0): ?>
            <span class="numPaymentLastBooks"><?php echo $lastBooksPayment; ?></span>
          <?php endif; ?>
        </button>

        <!--<button class="btn btn-success btn-cons" type="button" id="stripePayment" modalLinkStrip>-->
        <button class="btn btn-success btn-cons" type="button" data-toggle="modal" data-target="#modalLinkStrip">
          <i class="fa fa-money" aria-hidden="true"></i> <span class="bold hidden-mobile">Cobros TPV</span>
        </button>

      <?php endif ?>
        <button class="btn btn-success btn-calcuteBook btn-cons" type="button" data-toggle="modal" data-target="#modalCalculateBook">
          <span class="bold hidden-mobile"><i class="fa fa-calendar-alt" aria-hidden="true"></i>&nbsp;Calcular reserva</span>
          <span class="bold show-mobile">$</span>
        </button>
      <?php if (Auth::user()->role != "agente"): ?>
       <button class="btn btn-orange btn-cons btn-tables btn-blink hiddenOnlyRiad"  type="button" data-type="ff_pdtes">
            <span class="bold">FORFAITS</span>
            <span class="numPaymentLastBooks show"><?php echo $ff_pendientes; ?></span>
        </button>
      
        <button class="btn btn-danger btn-cons btn-blink <?php if (count($alarms) > 0) echo 'btn-alarms'; ?>" type="button" data-toggle="modal" data-target="#modalAlarms">
          <i class="fa fa-bell" aria-hidden="true"></i> <span class="bold">COBROS PDTES</span>
          <span class="numPaymentLastBooks"><?php echo count($alarms); ?></span>
        </button>
        <button class="btn btn-danger btn-cons btn-blink <?php if ($alert_lowProfits) echo 'btn-alarms'; ?> "  id="btnLowProfits" type="button" data-toggle="modal" data-target="#modalLowProfits">
          
          <span class="bold hidden-mobile"><i class="fa fa-bell" aria-hidden="true"></i> BAJO BENEFICIO</span>
          <span class="bold show-mobile"><i class="fa fa-bell" aria-hidden="true"></i> BJ BENEF</span>
          <span class="numPaymentLastBooks" data-val="{{$alert_lowProfits}}">{{$alert_lowProfits}}</span>
        </button>
        <button class="btn btn-danger btn-cons btn-blink <?php if ($parteeToActive > 0) echo 'btn-alarms'; ?> "  id="btnParteeToActive" test-target="#modalParteeToActive">
          <i class="fa fa-bell" aria-hidden="true"></i> <span class="bold">PARTEE</span>
          <span class="numPaymentLastBooks"><?php echo  $parteeToActive; ?></span>
        </button>
      <?php endif ?>
        <button class="btn btn-primary btn-sm calend show-mobile cargar_calend" type="button" >
              <span class="bold"><i class="fa fa-calendar"></i></span>
          </button>
        
        <a href="#" class=" sendImportICal btn btn-primary btn-cons show-mobile" <?php if (count(\App\IcalImport::all()) == 0): ?> disabled="" <?php endif ?> style="background-color: #337ab7; border-color: #2e6da4;">
        <span class="bold">IMPORTACIÓN</span>
      </a>
    </div>
  </div>
  @if(!$is_mobile)
  <div class="col-md-5 hidden-mobile">
    <div class="row btn-mb-1">
    <?php if (Auth::user()->role != "agente"): ?>
      <button id="btnAlertsBookking" disabled class="btn btn-success btn-cons " type="button" data-toggle="modal" data-target="#modalAlertsBooking">
        <span class="bold">Alertas booking</span>
      </button>

      <button class="btn btn-primary btn-calendarBooking btn-cons " type="button" data-toggle="modal" data-target="#modalCalendarBooking">
        <span class="bold">Calendario booking</span>
      </button>

      <a href="#"  class=" sendImportICal btn btn-primary btn-cons " <?php if (count(\App\IcalImport::all()) == 0): ?> disabled="" <?php endif ?> style="background-color: #337ab7; border-color: #2e6da4;">
        <span class="bold">IMPORTACIÓN</span>
      </a>
      <?php if (Auth::user()->role == "admin"): ?>
      <button class="btn btn-primary btn-cupos btn-cons" type="button" data-toggle="modal" data-target="#modalCuposVtn">
        <span class="bold">Cupos Vtn Rapida</span>
      </button>
      <?php endif ?>
    <?php endif ?>
  </div>
</div>
   @endif
</div>