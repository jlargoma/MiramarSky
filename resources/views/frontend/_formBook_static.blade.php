
<style>
  div#content-response {
    padding: 1em 0px 1em 2em !important;
  }
</style>
<form id="form-book-apto-lujo" action="{{url('/getPriceBook')}}" method="post">
    <input type="hidden" name="_token" id="_static_token" value="ss">

    <div class="col-md-12">
            <div class="form-group col-sm-12 col-xs-6 col-md-6 col-lg-6 white">
                <label for="name">*Nombre</label>
                <input type="text" class="sm-form-control" name="name" id="nombre" placeholder="Nombre..." maxlength="40" required="" aria-label="Escribe tu nombre">
            </div>
            <div class="form-group col-sm-12 col-xs-6 col-md-6 col-lg-6 white">
                <label for="email">*Email</label>
                <input type="email" class="sm-form-control"  name="email" id="email" placeholder="Email..." maxlength="40" required="" aria-label="Escribe tu email">
            </div>
            <div class="form-group col-sm-12 col-xs-6 col-md-6 col-lg-6 white">
                <label for="telefono">*Telefono</label>
                <input type="text" class="sm-form-control only-numbers" name="telefono" id="telefono" placeholder="Teléfono..." maxlength="9" required="" aria-label="Escribe tu telefono">
            </div>
            <div class="form-group col-sm-12 col-xs-6 col-md-6 white">
                <label for="date" style="display: inherit!important;">*Entrada - Salida</label>
                <div class="input-group">
                    <input type="text" class="sm-form-control daterange1" id="date"   name="date" required style="cursor: pointer;text-align: center;" readonly="">
                </div>
                <p  class="help-block min-days" style="display:none;line-height:1.2;color:red;">
                    <b>* ESTANCIA M&IacuteNIMA: 2 NOCHES</b>
                </p>
            </div>

            <div class="hidden-xs hidden-sm" style="clear: both;"></div>

            <div class="form-group col-sm-12 col-xs-6 col-md-3 white">
               <label for="quantity" >*Personas</label>
               <div class="quantity center clearfix divcenter">
                    <select id="quantity" class="sm-form-control" name="quantity">
                        <?php for ($i = 1;  $i <= 14 ; $i++): ?>
                            <?php if ($i != 11 && $i != 13): ?>
                                <option value="<?php echo $i ?>"><?php echo $i ?></option>
                            <?php endif ?>
                            
                        <?php endfor ?>
                    </select>
            </div>
                <p class="help-block white hidden-sm hidden-xs" style="line-height:1.2">Máx 14 pers</p>
            </div>
            
            <div class="form-group col-sm-12 col-xs-6 col-md-5 apto-type" style="padding: 0">
                <label for="parking"  class="col-md-12 text-left parking white">* Tipo Apto</label>

                <div class="col-md-3 col-xs-6">
                    <input id="apto-3dorm" class="radio-style apto-3dorm form-control" name="apto" type="radio" value="3dorm">
                    <label for="apto-3dorm" class="radio-style-3-label">3Dor</label>
                </div>
                <div class="col-md-3 col-xs-6">
                    <input id="apto-2dorm" class="radio-style apto-2dorm form-control" name="apto" type="radio" value="2dorm">
                    <label for="apto-2dorm" class="radio-style-3-label">2Dor</label>
                </div>
                <div class="col-md-3 col-xs-6">
                    <input id="apto-chlt" class="radio-style apto-chlt form-control" name="apto" type="radio" value="chlt">
                    <label for="apto-chlt" class="radio-style-3-label">Chlt</label>
                </div>
            <div class="col-md-3 col-xs-6">
               <input id="apto-estudio" class="radio-style apto-estudio form-control" name="apto" type="radio" value="estudio">
               <label for="apto-estudio" class="radio-style-3-label">Est.</label>
            </div>
            </div>
            <div class="form-group col-sm-12 col-xs-4 col-md-3 apto-lujo">
                <label  class="col-md-12 luxury white">*lujo</label>
                <div class="col-md-6"> 
               <input id="luxury-yes" class="radio-style" name="luxury" type="radio"  value="si">
               <label for="luxury-yes" class="radio-style-3-label">Si</label>
            </div>
            <div class="col-md-6">
               <input id="luxury-no" class="radio-style" name="luxury" type="radio" value="no" checked="">
               <label for="luxury-no" class="radio-style-3-label">No</label>
            </div>
            </div>
            <div style="clear: both;"></div>
            <div class="form-group col-sm-12 col-xs-12 col-md-12 col-lg-12">
                <div class="input-group col-sm-12 col-xs-12 col-md-12 col-lg-12">
                    <textarea class="sm-form-control" name="comment" rows="3" placeholder="Comentanos aqui tus dudas o inquietudes." id="coment" maxlength="200" aria-label="Comentanos aqui tus dudas o inquietudes."></textarea>
                </div>
            </div>
            <div class="form-group col-sm-12 col-xs-12 col-md-12 col-lg-12 text-center">
                <button type="submit" class="button button-3d button-xlarge button-rounded button-white button-light" id="confirmBookStatic">Calcular reserva</button>
            </div>
            
            <div class="form-group col-sm-12 col-xs-12 col-md-12 col-lg-12 text-center">
               <span style="font-size:16px; color: #FFFFFF; font-weight: bold;">
                     OFERTA 5 x 4 NOCHES<br/>
                     OFERTA 7 x 6 NOCHES
                </span>
            </div>
            <div class="form-group col-sm-12 col-xs-12 col-md-12 col-lg-12 text-left">
                <span style="font-size:13px; color: #FFFFFF; font-weight: bold;">
                    *La oferta 5x4 es para días Laborables.<br/>
                    *Ofertas validas toda la temporada excepto del:<br/>
                    5-9 Dic / 20 dic - 6 Ene/ 24 Feb -1 Marz / 6-12 Abril
                </span>
            </div>
    </div>
</form>
