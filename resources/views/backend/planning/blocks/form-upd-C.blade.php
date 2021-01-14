<?php 
$uRole = getUsrRole();
$disabl_limp = ($uRole == "limpieza") ? 'disabled' : '';
?>
<div class="row col-xs-12 push-20">
  <div class="col-md-3 col-xs-12 text-center boxtotales" style="background-color: #0c685f;">
    <label class="font-w800 text-white" for="">PVP</label>
    <input type="number" step='0.01' class="form-control total m-t-10 m-b-10 white" {{$disabl_limp}}
           name="total" value="<?php echo $book->total_price ?>" id="total_pvp">
  </div>
<?php if ($uRole == "admin"): ?>
    <div class="col-md-3 col-xs-12 text-center boxtotales" style="background: #99D9EA;">
      <label class="font-w800 text-white" for="">COSTE TOTAL</label>
      <input  readonly=""  step='0.01' class="form-control cost m-t-10 m-b-10 white" value="{{$book->cost_total}}">
    </div>
    <div class="col-md-3 col-xs-12 text-center boxtotales" style="background: #91cf81;">
      <label class="font-w800 text-white" for="">COSTE APTO</label>
      <input type="number" step='0.01' class="form-control costApto m-t-10 m-b-10 white"
             name="costApto" value="<?php echo $book->cost_apto ?>">
    </div>
    <div class="col-md-3 col-xs-12 text-center boxtotales" style="background: #337ab7;">
      <label class="font-w800 text-white" for="">COSTE PARKING</label>
      <input type="number" step='0.01' class="form-control costParking m-t-10 m-b-10 white"
             name="costParking" value="<?php echo $book->cost_park ?>">
    </div>
    <div class="col-md-3 col-xs-12 text-center boxtotales not-padding"
         style="background: #ff7f27;">
      <label class="font-w800 text-white" for="">BENEFICIO</label>
      <input class="form-control beneficio m-t-10 m-b-10 white" value="{{$book->total_ben}}" readonly="">
      <div class="beneficio-text font-w400 font-s18 white">{{$book->inc_percent}}%</div>
    </div>
<?php endif ?>

</div>
<?php if ($uRole != "agente"): ?>
  <p class="text-center">Precio que se muestra al público</p>
  <div class="col-md-12 col-xs-12 push-20 not-padding" >
    <div class="col-md-2 col-xs-6 box-info">
      <input type="hidden" id="confirm_publ_total" value="{{$priceBook['pvp']}}">
      PVP Final<br><span  id="publ_total">{{$priceBook['pvp']}}</span>
    </div>
    <div class="col-md-2 col-xs-6 box-info">
      <input type="hidden" id="confirm_publ_price" value="{{$priceBook['pvp_init']}}">
      PVP Inicial<br><span  id="publ_price">{{$priceBook['pvp_init']}}</span><br/>
    </div>
    <div class="col-md-2 col-xs-4 box-info">
      <input type="hidden" id="confirm_publ_disc" value="{{$priceBook['discount_pvp']}}">
      DESC<br><span  id="publ_disc">{{$priceBook['discount_pvp']+$priceBook['PRIVEE']}}</span>
    </div>
    <div class="col-md-2 col-xs-4 box-info">
      <input type="hidden" id="confirm_publ_promo" value="{{$priceBook['promo_pvp']}}">
      PROMO<br><span  id="publ_promo">{{$priceBook['promo_pvp']}}</span>
    </div>
    <div class="col-md-2 col-xs-4 box-info">
      <input type="hidden" id="confirm_publ_limp" value="{{$priceBook['price_limp']}}">
      SUPL LIMP<br><span  id="publ_limp">{{$priceBook['price_limp']}}</span>
    </div>
    <div class="col-md-2 col-xs-4 box-info">
      Benef<br>
      <span  id="publ_beneficio">{{$priceBook['profit'] ?? '--'}}</span> | <span  id="publ_beneficio_perc">{{$priceBook['profit_percentage'] ?? ''}}%</span>
    </div>
  </div>
  <div id="promos_aplic">
    
      @if ($priceBook['discount_pvp']>0 && isset($priceBook['discount_name']))
      <b>Descuento {{$priceBook['discount_name']}} ({{$priceBook['discount']}}%):</b> -{{$priceBook['discount_pvp']}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      @endif
      @if ($priceBook['promo_pvp']>0)
      <b>Descuento {{$priceBook['promo_name']}} :</b> -{{$priceBook['promo_pvp']}}
      @endif
  </div>
<?php endif ?>

<div class="col-md-12 col-xs-12 push-20 not-padding">
  <p class="personas-antiguo" style="color: red">
    <?php if ($book->pax < $book->room->minOcu): ?>
      Van menos personas que la ocupacion minima del apartamento.
<?php endif ?>
  </p>
</div>
<div class="col-xs-12 padding-block">
  <div class="col-md-4 col-xs-12">
    <label>Comentarios Cliente </label>
    <textarea class="form-control" name="comments" rows="5"
              data-idBook="<?php echo $book->id ?>"
              data-type="1"><?php echo $book->comment ?></textarea>
  </div>
  <div class="col-md-4 col-xs-12">
    <label>Comentarios Internos</label>
    <textarea class="form-control book_comments" name="book_comments" rows="5"
              data-idBook="<?php echo $book->id ?>"
              data-type="2"><?php echo $book->book_comments ?></textarea>
  </div>
  <div class="col-md-4 col-xs-12 content_book_owned_comments">
    <label>Comentarios Propietario</label>
    <input type="hidden" id="promo_text" value="{{$priceBook['promo_name']}}">
    <textarea class="form-control book_owned_comments" name="book_owned_comments" rows="5"
              data-idBook="<?php echo $book->id ?>"
              data-type="3"><?php if (!empty($book->book_owned_comments)): ?><?php echo $book->book_owned_comments ?><?php endif; ?></textarea>
  </div>
</div>

