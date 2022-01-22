<div class="buttonsTops">

  <button class="btn btn-success btn-cons btn-newBook" type="button" data-toggle="modal" data-target="#modalNewBook">
    <i class="fa fa-plus-square" aria-hidden="true"></i> <span class="bold hidden-mobile" >Nueva Reserva</span>
  </button>
  <button id="lastBooks" class="btn btn-success btn-cons" type="button" data-toggle="modal" data-target="#modalLastBooks">
    <span class="bold hidden-mobile">Últimas Confirmadas</span>
    <span class="bold show-mobile">Últ Conf</span>
    <?php if ($lastBooksPayment > 0): ?>
      <span class="numPaymentLastBooks"><?php echo $lastBooksPayment; ?></span>
    <?php endif; ?>
  </button>
  <button class="btn btn-success btn-cons" type="button" data-toggle="modal" data-target="#modalLinkStrip">
    <i class="fas fa-money-bill-wave" aria-hidden="true"></i> 
    <span class="bold hidden-mobile">Cobros TPV</span>
  </button>
  <button class="btn btn-success btn-calcuteBook btn-cons" type="button">
    <i class="fa fa-dollar-sign" aria-hidden="true"></i>
    <span class="bold hidden-mobile">&nbsp;Calcular reserva</span>
  </button>
  <button class="btn btn-danger btn-cons btn-blink <?php if ($alert_lowProfits) echo 'btn-alarms'; ?> "  id="btnLowProfits" type="button" data-toggle="modal" data-target="#modalLowProfits">
    <i class="fa fa-bell" aria-hidden="true"></i> <span class="bold hidden-mobile">BAJO BENEFICIO</span>
    <span class="numPaymentLastBooks" data-val="{{$alert_lowProfits}}">{{$alert_lowProfits}}</span>
  </button>
  <button class="btn btn-danger btn-cons btn-blink <?php if ($parteeToActive > 0) echo 'btn-alarms'; ?> "  id="btnParteeToActive" test-target="#modalParteeToActive">
    <i class="fa fa-file-powerpoint" aria-hidden="true"></i> <span class="bold hidden-mobile">PARTEE</span>
    <span class="numPaymentLastBooks"><?php echo $parteeToActive; ?></span>
  </button>
  <button class="btn btn-success btn-tables hidden-mobile" style="background-color: #96ef99; color: black;padding: 7px 18px;     width: auto !important;border: none;" type="button" data-type="reservadas">
    <span >RVA({{$totalReserv}}) <?php echo number_format($amountReserv, 0, ',', '.') ?>€</span>
  </button>
  <button class="btn btn-danger btn-cons btn-blink"  id="btnBookSafetyBox" >
    <i class="fa fa-lock" aria-hidden="true"></i> <span class="bold hidden-mobile">CAJAS DE SEGURIDAD</span>
  </button>
  <button class="btn btn-danger btn-cons btn-blink"  id="btnBookBlockAll" >
    <i class="fa fa-key" aria-hidden="true"></i>
    <span class="bold hidden-mobile">Bloqueo</span>
  </button>
  <button class="btn btn-success btn-orange <?php if ($bookings_without_Cvc > 0) echo 'btn-alarms'; ?>" id="btnBookingsWithoutCvc">
    <span class="bold">SIN VISA</span>
    @if($bookings_without_Cvc>0)
    <span class="numPaymentLastBooks" data-val="{{$bookings_without_Cvc}}">{{$bookings_without_Cvc}}</span>
    @endif
  </button>

  @if ($alarms > 0)
  <button id="lastBooksPendientes"  class="btn btn-danger btn-cons btn-blink btn-alarms" type="button">
    <i class="fa fa-bell" aria-hidden="true"></i> <span class="bold hidden-mobile">COBROS PDTES</span>
    <span class="numPaymentLastBooks"><?php echo $alarms; ?></span>
  </button>
  @else
  <button id="lastBooksPendientes"  class="btn btn-grey btn-cons" type="button">
    <i class="fa fa-bell" aria-hidden="true"></i> <span class="bold hidden-mobile">COBROS PDTES</span>
  </button>
  @endif

  @if(is_array($overbooking) && count($overbooking)>0)
  <button class="btn btn-success btn-tables"  type="button" style="min-width: 165px;background-color: #e8b0e7; color: black;padding: 7px 18px;border: none;"  data-type="overbooking">
    <span >OverBooking({{count($overbooking)}})</span>
  </button>
  @endif
  <?php if ($uRole == "admin"): ?>
    <a class="btn btn-primary" href="/admin/revenue/DASHBOARD" style="top: 6px;">
      <span class="bold">DASHB<span class="bold hidden-mobile">OARD</span></span>
    </a>   
  <?php endif ?>

  <button class="btn btn-orange btn-cons btn-tables btn-blink hidden-mobile"  type="button" data-type="ff_pdtes">
    <span class="bold">FORFAITS</span>
    <span class="numPaymentLastBooks show"><?php echo $ff_pendientes; ?></span>
  </button>
  <button class=" btn btn-blue btn_intercambio btn-cons minimal hidden-mobile" type="button">
    <span class="bold">intercambio</span>
  </button>
  <button class="btn btn-success btn-orange <?php if ($ota_errLogs > 0) echo 'btn-alarms'; ?>" id="btnOTAsLogs">
    <span class="bold"><i class="fa fa-exclamation-triangle show-mobile" style="font-size: 12px;"></i> OTAs</span><span class="bold hidden-mobile"> Errors</span>
    @if($ota_errLogs>0)
    <span class="numPaymentLastBooks" data-val="{{$ota_errLogs}}">{{$ota_errLogs}}</span>
    @endif
  </button>
  <button class="btn btn-tables btn-cons  hidden-mobile" type="button" data-type="ff_interesado">
    <img src="http://miramarski.virtual/img/miramarski/ski_icon_status_rosa.png" style="max-width:30px;" alt="INTERESADOS">
      <span class="text-black text-cont" >
        {{ $booksCount['ff_interesado'] }}
      </span>
  </button>
</div>

