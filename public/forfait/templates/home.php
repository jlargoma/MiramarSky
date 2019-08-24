<div class="container">
<h1>PETICIÓN FORFAITS Y CLASES</h1>
<input type="text" name="" id="start_date" placeholder="Desde" maxlength="10" required>
<div id="app">
  <button-counter></button-counter>
  
  <div class="row" >
    <h4 class="text-center push-0">* Seleccione tus días de esquí</h4>
    <div class="form-group col-xs-12 row">
          <div class="col-lg-2">
              <i class="fa fa-user" aria-hidden="true"></i> <span>Forfait <span class="forfait_number"></span></span>
          </div>
          <div class="col-lg-3">
              <label class="requests_label">*Primer día de Esquí</label>
              <date-picker v-model="date_start" :config="options"></date-picker>
          </div>
          <div class="col-lg-3">
              <label class="requests_label">*Última día de Esquí</label>
              <date-picker v-model="date_end" :config="options" placeholder="Hasta"></date-picker>
              <input type="date" name="" id="" placeholder="Hasta" maxlength="10" required>
          </div>
          <div class="col-lg-2">
              <label class="requests_label">Días</label>
              <input type="text" name="request_days" class="form-control request_days" placeholder="0" disabled="disabled">
          </div>
          <div class="col-lg-2">
              <label class="requests_label">*Elige Nº Forfaits</label>
               <select id="forfatis_number" class="form-control" name="forfatis_number">
                    <?php
                        for($x=0;$x<=10;$x++){
                            echo '<option value="'.$x.'">'.$x.'</option>';
                        }
                    ?>
                </select>
          </div>
      </div>
  </div>
  <div class="row" >
      <div class="form-group col-xs-12 row" v-for="user in users">
          <div class="col-lg-2">
              <i class="fa fa-user" aria-hidden="true"></i> <span>Forfait <span class="forfait_number"></span></span>
          </div>
          <div class="col-lg-3">
              <label class="requests_label">*Primer día de Esquí</label>
              <input class="sm-form-control datepicker_init_start_date" type="text" name="date-entrada-template" id="date-entrada" name="example-daterange1" placeholder="Desde" maxlength="10" required>
          </div>
          <div class="col-lg-3">
              <label class="requests_label">*Última día de Esquí</label>
              <input class="sm-form-control datepicker_init_end_date" type="text" name="date-salida-template" id="date-salida" name="example-daterange1" placeholder="Hasta" maxlength="10" required>
          </div>
          <div class="col-lg-2">
              <label class="requests_label">Días</label>
              <input type="text" name="request_days" class="form-control request_days" placeholder="0" disabled="disabled">
          </div>
          <div class="col-lg-2">
              <label class="requests_label">Edad</label>
              <input type="number" name="request_years" class="form-control request_years" placeholder="0">
          </div>
      </div>
  </div>
</div>
</div>