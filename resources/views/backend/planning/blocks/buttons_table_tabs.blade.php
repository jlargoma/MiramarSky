<?php if (in_array($uRole, ['admin','subadmin','recepcionista'])): ?>
<?php if (!$is_mobile): ?>
  <div class="lst-tabs-btn">
    <button class="btn btn-primary  btn-blue btn-tables btn-cons" type="button" data-type="pendientes">
      <span class="bold">Pendientes</span>
      <?php if ($uRole != "agente"): ?>
        <span class="numPaymentLastBooks">
          {{ $booksCount['pending'] }}
        </span>
      <?php endif ?>
    </button>
    <button class="btn btn-primary btn-tables btn-cons" type="button" data-type="blocks" style="background-color: #448eff;">
      <span class="bold">Bloqueadas</span>
      <span class="text-black text-cont">{{ $booksCount['blocks'] }}</span>
    </button>
    <button class="btn btn-success btn-tables btn-cons" type="button" data-type="reservadas" style="background-color: #53ca57;">
      <span class="bold">Reservadas</span>
      <span class="text-black text-cont" style="">
        {{ $booksCount['reservadas'] }}
      </span>
    </button>
    <button class="btn  btn-primary btn-green btn-tables btn-cons" type="button" data-type="confirmadas">
      <span class="bold">Confirmadas</span>
      <span class="text-black text-cont" >
        {{ $booksCount['confirmed'] }}
      </span>
    </button>
    <?php if ($uRole != "agente"): ?>
      <button class="btn btn-primary  btn-orange btn-tables btn-cons" type="button" data-type="especiales">
        <span class="bold">Especiales</span>
        <span class="text-black text-cont" >
          {{ $booksCount['special'] }}
        </span>
      </button>
    <?php endif ?>
    <?php if ($uRole != "agente"): ?>
      <button class="btn btn-success btn-tables btn-cons" type="button" data-type="checkin">
        <span class="bold">Check IN</span>
        <span class="text-black text-cont" >
          {{ $booksCount['checkin'] }}
        </span>
      </button>

      <button class="btn btn-primary btn-tables btn-cons" type="button" data-type="checkout">
        <span class="bold">Check OUT</span>
        <span class="text-black text-cont" >
          {{ $booksCount['checkout'] }}
        </span>
      </button>
      <button class="btn btn-danger btn-tables btn-cons" type="button" data-type="eliminadas">
        <span class="bold">Eliminadas</span>
        <span class="text-black text-cont" >
          {{ $booksCount['deletes'] }}
        </span>

      </button>
      <button class="btn btn-danger btn-tables btn-cons" type="button" data-type="cancel-xml">
        <span class="bold">Cancel-XML</span>
        <span class="text-black text-cont" >
          {{ $booksCount['cancel-xml'] }}
        </span>
      </button>
      <button class="btn btn-rosa btn-tables btn-cons" type="button" data-type="ff_interesado">
        <span class="bold">FF interesados</span>
        <span class="text-black text-cont" >
          {{ $booksCount['ff_interesado'] }}
        </span>
      </button>
    <?php endif ?>
  </div>
<?php else: ?>
  <button class="btn btn-primary  btn-blue btn-tables" type="button" data-type="pendientes">
    <span class="bold">Pend</span>
    <?php if ($uRole != "agente"): ?>
      <span class="numPaymentLastBooks" style="top: 0px;right: 0;padding: 0px 7px;">
        {{ $booksCount['pending'] }}
      </span>
    <?php endif ?>
  </button>
  <button class="btn btn-success btn-tables btn-cons" type="button" data-type="blocks" style="background-color: #448eff;">
    <span class="bold">Bloq</span>
    <span class="text-black text-cont" >{{ $booksCount['blocks'] }}</span>
  </button>
  <button class="btn btn-success  btn-tables" type="button" data-type="reservadas" style="background-color: #53ca57;">
    <span class="bold">Reser</span>
    <span class="text-black" >
      {{ $booksCount['reservadas'] }}
    </span>
  </button>
  <button class="btn  btn-primary btn-green btn-tables" type="button" data-type="confirmadas">
    <span class="bold">Conf</span>
    <span class="text-black" >
      {{ $booksCount['confirmed'] }}
    </span>
  </button>
  <?php if ($uRole != "agente"): ?>
    <button class="btn btn-primary  btn-orange btn-tables" type="button" data-type="especiales">
      <span class="bold">Esp</span>
      <span class="text-black" >
        {{ $booksCount['special'] }}
      </span>
    </button>
  <?php endif ?>
  <?php if ($uRole != "agente"): ?>
    <button class="btn btn-success btn-tables" type="button" data-type="checkin">
      <span class="bold">IN</span>
      <span class="text-black" >
        {{ $booksCount['checkin'] }}
      </span>
    </button>

    <button class="btn btn-primary btn-tables" type="button" data-type="checkout">
      <span class="bold">OUT</span>
      <span class="text-black" >
        {{ $booksCount['checkout'] }}
      </span>
    </button>


    <button class="btn btn-primary  Blocked-ical btn-tables" type="button" data-type="blocked-ical">
      <span class="bold">ICal</span>
      <span class="text-black" >
        {{ $booksCount['blocked-ical'] }}
      </span>
    </button>

    <button class="btn btn-danger btn-tables" type="button" data-type="eliminadas">
      <span class="bold">Elim</span>
      <span class="text-black" >
        {{ $booksCount['deletes'] }}
      </span>
    </button>
    <button class="btn btn-danger btn-tables" type="button" data-type="cancel-xml">
      <span class="bold">Cancel</span>
      <span class="text-black" >
        {{ $booksCount['cancel-xml'] }}
      </span>
    </button>
    <button class="btn btn-rosa btn-tables btn-cons" type="button" data-type="ff_interesado">
      <span class="bold">FFs</span>
      <span class="text-black text-cont" >
        {{ $booksCount['ff_interesado'] }}
      </span>
    </button>
  <?php endif ?>
<?php endif ?>
<?php else: ?>
<?php if ($uRole == 'conserje'): ?>
  <button class="btn btn-success btn-tables btn-cons automatic_click" type="button" data-type="checkin">
    <span class="bold">Check IN</span>
    <span class="text-black text-cont" >
      {{ $booksCount['checkin'] }}
    </span>
  </button>

  <button class="btn btn-primary btn-tables btn-cons" type="button" data-type="checkout">
    <span class="bold">Check OUT</span>
    <span class="text-black text-cont" >
      {{ $booksCount['checkout'] }}
    </span>
  </button>
<?php endif ?>
<?php endif ?>