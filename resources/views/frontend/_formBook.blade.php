<?php if (!$mobile->isMobile()): ?>
<form id="form-book-apto-lujo" action="{{url('/getPriceBook')}}" method="post">
    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

    <div class="col-md-12">
            <div class="form-group col-sm-12 col-xs-6 col-md-4 col-lg-4 white">
                <label>*Nombre</label>
                <input type="text" class="sm-form-control" name="name" id="nombre" placeholder="Nombre..." maxlength="40" required="">
            </div>
            <div class="form-group col-sm-12 col-xs-6 col-md-4 col-lg-4 white">
                <label>*Email</label>
                <input type="email" class="sm-form-control"  name="email" id="email" placeholder="Email..." maxlength="40" required="">
            </div>
            <div class="form-group col-sm-12 col-xs-6 col-md-4 col-lg-4 white">
                <label>*Telefono</label>
                <input type="text" class="sm-form-control only-numbers" name="telefono" id="telefono" placeholder="Teléfono..." maxlength="9" required="">
            </div>
            
			<div class="hidden-xs hidden-sm" style="clear: both;"></div>

            <div class="form-group col-sm-12 col-xs-6 col-md-3 white">
                <label style="display: inherit!important;">*Entrada - Salida</label>
                <div class="input-group">
                    <input type="text" class="sm-form-control daterange1" id="date"   name="date" required style="cursor: pointer;">
                </div>
                <p class="help-block white hidden-sm hidden-xs" style="line-height:1.2">La entrada es a partir de la 16:00 pm
                    <br><b>* ESTANCIA MÍNIMA: 2 NOCHES</b>
                </p>
            </div>
            <div class="form-group col-sm-12 col-xs-6 col-md-1 white">
           		<label style="display: inherit!important;">*Ocupantes</label>
            	<div class="quantity center clearfix divcenter">
					<!-- <input type="button" value="-" class="minus black" style=" color: black;">
					<input id="quantity" type="text" name="quantity" value="4" class="qty" style="background: white; color: black;"> -->
                    <select id="quantity" class="sm-form-control" name="quantity">
                        <?php for ($i = 1;  $i <= 8 ; $i++): ?>
                            <option value="<?php echo $i ?>"><?php echo $i ?></option>
                        <?php endfor ?>
                    </select>
					<!-- <input type="button" value="+" class="plus black" style=" color: black;"> -->
				</div>
                
                <p class="help-block white hidden-sm hidden-xs" style="line-height:1.2">Máximo 8 personas</p>
               <!--  <p class="help-block white hidden-sm hidden-xs" style="color: white; line-height:1.2;">    
                    <b>* SOLICITAR APTO DOS DORM 6 PAX</b>
                </p> -->
            </div>
            
            <div class="form-group col-sm-12 col-xs-4 col-md-3" style="padding: 0">
                <label style="display: inline!important;" class="col-md-12 parking white">* Tipo Apto</label>
                <div class="col-md-6">
					<input id="apto-2dorm" class="radio-style" name="apto" type="radio" checked="" value="2dorm">
					<label for="apto-2dorm" class="radio-style-3-label">2 dor</label>
				</div>
				<div class="col-md-6">
					<input id="apto-estudio" class="radio-style" name="apto" type="radio" value="estudio">
					<label for="apto-estudio" class="radio-style-3-label">Est.</label>
				</div>
            </div>
            <div class="form-group col-sm-12 col-xs-4 col-md-2">
                <label style="display: inline!important;" class="col-md-12 luxury white">*lujo</label>
                <div class="col-md-6"> 
					<input id="luxury-yes" class="radio-style" name="luxury" type="radio" checked="" value="si">
					<label for="luxury-yes" class="radio-style-3-label">Si</label>
				</div>
				<div class="col-md-6">
					<input id="luxury-no" class="radio-style" name="luxury" type="radio" value="no">
					<label for="luxury-no" class="radio-style-3-label">No</label>
				</div>
            </div>
            <div class="form-group col-sm-12 col-xs-4 col-md-2">
                <label style="display: inline!important;" class="col-md-12 parking white">*Parking</label>
                <div class="col-md-6">
					<input id="parking-yes" class="radio-style" name="parking" type="radio" checked="" value="si">
					<label for="parking-yes" class="radio-style-3-label">Si</label>
				</div>
				<div class="col-md-6">
					<input id="parking-no" class="radio-style" name="parking" type="radio" value="no">
					<label for="parking-no" class="radio-style-3-label">No</label>
				</div>
            </div>
            <div class="form-group col-sm-12 col-xs-12 col-md-12 col-lg-12">
                <div class="input-group col-sm-12 col-xs-12 col-md-12 col-lg-12">
                    <textarea class="sm-form-control" name="comment" rows="3" placeholder="Comentanos aqui tus dudas o inquietudes." id="coment" maxlength="200"></textarea>
                </div>
            </div>
            <div class="form-group col-sm-12 col-xs-12 col-md-12 col-lg-12 text-center">
                <button type="submit" class="button button-3d button-xlarge button-rounded button-white button-light" id="confirm-reserva">Calcular reserva</button>
            </div>
    </div>
</form>
<?php else: ?>
    <style type="text/css">
        @media screen and (max-width: 767px){
            .daterangepicker{
                left: 55px!important
            }
        }
    </style>
    <form id="form-book-apto-lujo" action="{{url('/getPriceBook')}}" method="post">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

        <div class="col-md-12">
                <div class="form-group col-sm-12 col-xs-6 col-md-4 col-lg-4 white">
                    <label>*Nombre</label>
                    <input type="text" class="sm-form-control" name="name" id="nombre" placeholder="Nombre..." maxlength="40" required="">
                </div>
                <div class="form-group col-sm-12 col-xs-6 col-md-4 col-lg-4 white">
                    <label>*Email</label>
                    <input type="email" class="sm-form-control"  name="email" id="email" placeholder="Email..." maxlength="40" required="">
                </div>
                <div class="form-group col-sm-12 col-xs-6 col-md-4 col-lg-4 white">
                    <label>*Telefono</label>
                    <input type="text" class="sm-form-control only-numbers" name="telefono" id="telefono" placeholder="Teléfono..." maxlength="9" required="">
                </div>
                
                <div class="hidden-xs hidden-sm" style="clear: both;"></div>

                
                <div class="form-group col-sm-12 col-xs-6 col-md-1 white">
                    <label style="display: inherit!important;">*Ocupantes</label>
                    <div class="quantity center clearfix divcenter">
                        <select id="quantity" class="sm-form-control" name="quantity">
                            <?php for ($i = 1;  $i <= 8 ; $i++): ?>
                                <option value="<?php echo $i ?>"><?php echo $i ?></option>
                            <?php endfor ?>
                        </select>
                    </div>
                    
                    <p class="help-block white hidden-sm hidden-xs" style="line-height:1.2">Máximo 8 personas</p>

                </div>
                <div class="form-group col-sm-12 col-xs-12 col-md-3 white">
                    <label style="display: inherit!important;">*Entrada - Salida</label>
                    <input type="text" class="sm-form-control daterange1" id="date"   name="date" required style="cursor: pointer;">

                    <p class="help-block white hidden-sm hidden-xs" style="line-height:1.2">La entrada es a partir de la 16:00 pm
                        <br><b>* ESTANCIA MÍNIMA: 2 NOCHES</b>
                    </p>
                </div>
                <div class="form-group col-sm-12 col-xs-4 col-md-3" style="padding: 0">
                    <label style="display: inline!important;" class="col-md-12 parking white">* Tipo Apto</label>
                    <div class="col-md-6">
                        <input id="apto-2dorm" class="radio-style" name="apto" type="radio" checked="" value="2dorm">
                        <label for="apto-2dorm" class="radio-style-3-label">2 dor</label>
                    </div>
                    <div class="col-md-6">
                        <input id="apto-estudio" class="radio-style" name="apto" type="radio" value="estudio">
                        <label for="apto-estudio" class="radio-style-3-label">Est.</label>
                    </div>
                </div>
                <div class="form-group col-sm-12 col-xs-4 col-md-2">
                    <label style="display: inline!important;" class="col-md-12 luxury white">*lujo</label>
                    <div class="col-md-6"> 
                        <input id="luxury-yes" class="radio-style" name="luxury" type="radio" checked="" value="si">
                        <label for="luxury-yes" class="radio-style-3-label">Si</label>
                    </div>
                    <div class="col-md-6">
                        <input id="luxury-no" class="radio-style" name="luxury" type="radio" value="no">
                        <label for="luxury-no" class="radio-style-3-label">No</label>
                    </div>
                </div>
                <div class="form-group col-sm-12 col-xs-4 col-md-2">
                    <label style="display: inline!important;" class="col-md-12 parking white">*Parking</label>
                    <div class="col-md-6">
                        <input id="parking-yes" class="radio-style" name="parking" type="radio" checked="" value="si">
                        <label for="parking-yes" class="radio-style-3-label">Si</label>
                    </div>
                    <div class="col-md-6">
                        <input id="parking-no" class="radio-style" name="parking" type="radio" value="no">
                        <label for="parking-no" class="radio-style-3-label">No</label>
                    </div>
                </div>
                <div class="form-group col-sm-12 col-xs-12 col-md-12 col-lg-12">
                    <div class="input-group col-sm-12 col-xs-12 col-md-12 col-lg-12">
                        <textarea class="sm-form-control" name="comment" rows="3" placeholder="Comentanos aqui tus dudas o inquietudes." id="coment" maxlength="200"></textarea>
                    </div>
                </div>
                <div class="form-group col-sm-12 col-xs-12 col-md-12 col-lg-12 text-center">
                    <button type="submit" class="button button-3d button-xlarge button-rounded button-white button-light" id="confirm-reserva">Calcular reserva</button>
                </div>
        </div>
    </form>
<?php endif; ?>