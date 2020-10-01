<div class="modal fade slide-up in" id="modalEmailing" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content-wrapper">
      <div class="modal-content" id="contentEmailing"></div>
    </div>
  </div>
</div>

<form role="form">
  <div class="form-group form-group-default required" style="display: none">
    <label class="highlight">Message</label>
    <input type="text" hidden="" class="form-control notification-message"
           placeholder="Type your message here" value="This notification looks so perfect!" required>
  </div>
  <button class="btn btn-success show-notification hidden" id="boton">Show</button>
</form>
<input type="hidden" class="precio-oculto" value="<?php echo $book->total_price ?>">

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


<form method="post" id="formFF" action="" <?php if (!$mobile->isMobile()) {
  echo 'target="_blank"';
} ?>>
  <input type="hidden" name="admin_ff" id="admin_ff">
</form>



<div class="modal fade slide-up in" id="modalResponseEmail" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xd">
    <div class="modal-content-classic">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="position: absolute; top: 0px; right: 10px; z-index: 100">
        <i class="fa fa-times fa-2x" style="color: #000!important;"></i>
      </button>
      <form method="POST" action="/admin/response-email">
        <input type="hidden" id="booking" name="booking" value="{{$book->id}}">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        <div class="form-group text-left">
          <label for="subject">Asunto</label>
          <input type="text" class="form-control" id="subject" name="subject" value="Repuesta desde {{env('APP_NAME')}}" />
        </div>
        <div class="form-group text-left">
          <label for="content">Contenido</label>
          <textarea class="form-control" id="content" name="content" rows="5"></textarea>
        </div>
        <div class="form-group">
          <button type="submit" class="btn btn-primary" >Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade slide-up in" id="modal_seeLog" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xd text-left">
    <div class="modal-content-classic">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="position: absolute; top: 0px; right: 10px; z-index: 100">
        <i class="fa fa-times fa-2x" style="color: #000!important;"></i>
      </button>
      <div class="msl-data">
        <label>Asunto</label>
        <div id="msl_subj"></div>
      </div>
      <div class="msl-data">
        <label>Fecha</label>
        <div id="msl_date"></div>
      </div>
      <div class="msl-data">
        <label>Usuario</label>
        <div id="msl_user"></div>
      </div>
      <div class="msl-data">
        <label>Apto</label>
        <div id="msl_room"></div>
      </div>
      <div class="msl-data">
        <label>Mensaje</label>
        <div id="msl_content"></div>
      </div>
    </div>
  </div>
</div>

<div id="dialog-confirm" title="Atención: cambio del PVP" style="display: none;">
  <h4>Reserva Original: <b id="confirm_PVP_current"></b></h4>
  <?php 
  if ($priceBook['discount']>0) echo '<div class="text-danger"><b>Descuento del '.$priceBook['discount'].'%:</b> -'.moneda($priceBook['discount_pvp'],false,2).'</div>';
  if ($priceBook['promo_name'] != '') echo '<div class="text-danger"><b>Promo '.$priceBook['promo_name'].':</b> -'.moneda($priceBook['promo_pvp'],false,2).'</div>';
  ?>
  
  <h4>Modificación: <b id="confirm_PVP_modif"></b></h4>
  <div id="confirm_PVP_disc" class="text-danger"></div>
  <div id="confirm_PVP_promo" class="text-danger"></div>
  <hr/>
</div>