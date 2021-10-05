<div class="row">
  <div class="col-md-12 col-xs-5">
    <input id="nameCustomer" type="text" name="searchName" class="searchabled form-control" placeholder="nombre del cliente" />
  </div>
  <div class="show-mobile btnMob">
    <button class="btn btn-blue btn_intercambio btn-cons minimal" type="button">
      <span class="bold">intercambio</span>
    </button>
    <button class="btn btn-orange btn-cons btn-tables btn-blink hiddenOnlyRiad"  type="button" data-type="ff_pdtes">
      <span class="bold">FORFAITS</span>
      <span class="numPaymentLastBooks show"><?php echo $ff_pendientes; ?></span>
    </button>
  </div>

</div>