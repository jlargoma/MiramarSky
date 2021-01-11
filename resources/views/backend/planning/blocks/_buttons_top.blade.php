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
          <i class="fas fa-dollar-sign" aria-hidden="true"></i> <span class="bold hidden-mobile">Cobros TPV</span>
        </button>
        
        <button class="btn btn-success btn-orange @if($CustomersRequest>0) btn-alarms @endif" id="btnCustomersRequest">
          <span class="bold">LEADS</span>
          @if($CustomersRequest>0)
          <span class="numPaymentLastBooks" data-val="{{$CustomersRequest}}">{{$CustomersRequest}}</span>
          @endif
        </button>
        
      <?php endif ?>
      <button class="btn btn-success btn-calcuteBook btn-cons" type="button">
        <span class="bold hidden-mobile"><i class="fa fa-calendar-alt" aria-hidden="true"></i>&nbsp;Calcular reserva</span>
        <span class="bold show-mobile">$</span>
      </button>
      <?php if (Auth::user()->role != "agente"): ?>
        <button class="btn btn-orange btn-cons btn-tables btn-blink hiddenOnlyRiad"  type="button" data-type="ff_pdtes">
          <span class="bold">FORFAITS</span>
          <span class="numPaymentLastBooks show"><?php echo $ff_pendientes; ?></span>
        </button>
        @if ($alarms > 0)
        <button id="lastBooksPendientes"  class="btn btn-danger btn-cons btn-blink btn-alarms" type="button">
          <i class="fa fa-bell" aria-hidden="true"></i> <span class="bold">COBROS PDTES</span>
          <span class="numPaymentLastBooks"><?php echo $alarms; ?></span>
        </button>
        @else
        <button id="lastBooksPendientes"  class="btn btn-grey btn-cons" type="button">
          <i class="fa fa-bell" aria-hidden="true"></i> <span class="bold">COBROS PDTES</span>
        </button>
        @endif
        <button class="btn btn-danger btn-cons btn-blink <?php if ($alert_lowProfits) echo 'btn-alarms'; ?> "  id="btnLowProfits" type="button" data-toggle="modal" data-target="#modalLowProfits">

          <span class="bold hidden-mobile"><i class="fa fa-bell" aria-hidden="true"></i> BAJO BENEFICIO</span>
          <span class="bold show-mobile"><i class="fa fa-bell" aria-hidden="true"></i> BJ BENEF</span>
          <span class="numPaymentLastBooks" data-val="{{$alert_lowProfits}}">{{$alert_lowProfits}}</span>
        </button>
        <button class="btn btn-danger btn-cons btn-blink <?php if ($parteeToActive > 0) echo 'btn-alarms'; ?> "  id="btnParteeToActive" test-target="#modalParteeToActive">
          <i class="fa fa-bell" aria-hidden="true"></i> <span class="bold">PARTEE</span>
          <span class="numPaymentLastBooks"><?php echo $parteeToActive; ?></span>
        </button>
        <button class="btn btn-success btn-orange @if($CustomersRequest>0) btn-alarms @endif" id="btnBookingsWithoutCvc">
          <span class="bold">SIN VISA</span>
          @if($bookings_without_Cvc>0)
          <span class="numPaymentLastBooks" data-val="{{$bookings_without_Cvc}}">{{$bookings_without_Cvc}}</span>
          @endif
        </button>
        <button class="btn btn-success btn-tables" style="background-color: #96ef99; color: black;padding: 7px 18px;     width: auto !important;border: none;" type="button" data-type="reservadas">
          <span >RVA({{$totalReserv}}) <?php echo number_format($amountReserv, 0, ',', '.') ?>€</span>
        </button>
        @if(is_array($overbooking) && count($overbooking)>0)
        <button class="btn btn-success btn-tables"  type="button" style="min-width: 165px;background-color: #e8b0e7; color: black;padding: 7px 18px;border: none;"  data-type="overbooking">
          <span >OverBooking({{count($overbooking)}})</span>
        </button>
        @endif
        <button class="btn btn-danger btn-cons btn-blink"  id="btnBookSafetyBox" >
          <i class="fa fa-lock" aria-hidden="true"></i> <span class="bold">CAJAS</span>
        </button>
        <?php if (getUsrRole() == "admin"): ?>
        <a class="btn btn-primary" href="/admin/sales">
          <span class="bold">Informes</span>
        </a>       
        <?php endif ?>
      <?php endif ?>
    </div>
  </div>
  @if(!$is_mobile)
  <div class="col-md-5 hidden-mobile">
    <div class="row btn-mb-1">
      <?php if (Auth::user()->role != "agente"): ?>
        <button id="btnAlertsBookking" disabled class="btn btn-success btn-cons minimal" type="button" data-toggle="modal" data-target="#modalAlertsBooking">
          <span class="bold">Alertas booking</span>
        </button>

        <button class="btn btn-primary btn-calendarBooking btn-cons minimal" type="button" data-toggle="modal" data-target="#modalCalendarBooking">
          <span class="bold">Calendario booking</span>
        </button>
        <?php if (Auth::user()->role == "admin"): ?>
          <button class="btn btn-primary btn-cupos btn-cons minimal" type="button" data-toggle="modal" data-target="#modalCuposVtn">
            <span class="bold">Cupos Vtn Rapida</span>
          </button>
        <?php endif ?>
        <button class="btn btn-blue btn_intercambio btn-cons minimal" type="button">
          <span class="bold">intercambio</span>
        </button>
        <button class="btn btn-danger btn-cons btn-blink"  id="btnBookBlockAll" >
          <i class="fa fa-key" aria-hidden="true"></i>
          <span class="bold hidden-mobile">Bloqueo</span>
        </button>
      <?php endif ?>
    </div>
  </div>
  @else
          <button class="btn btn-blue btn_intercambio btn-cons minimal" type="button">
          <span class="bold">intercambio</span>
        </button>
        <button class="btn btn-danger btn-cons btn-blink"  id="btnBookBlockAll" >
          <i class="fa fa-key" aria-hidden="true"></i>
          <span class="bold hidden-mobile">Bloqueo</span>
        </button>
  @endif
</div>
 @if(is_array($urgentes) && count($urgentes)>0)
  <div class="box-alerts-popup">
    <div class="content-alerts">
      <h2>Alertas Urgentes</h2>
      <button type="button" class="close" id="closeUrgente" >
        <i class="fa fa-times fa-2x" ></i>
      </button>
      @foreach($urgentes as $item)
      <div class="items">
        <button {!! $item['action'] !!}><i class="fa fa-bell" aria-hidden="true"></i> </button>
        {{$item['text']}}
      </div>
      @endforeach 
    </div>
  </div>
 @endif