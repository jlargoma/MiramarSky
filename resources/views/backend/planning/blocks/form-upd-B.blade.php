<?php

use \Carbon\Carbon;

$uRole = getUsrRole();
$disabl_limp = ($uRole == "limpieza") ? 'disabled' : '';
?>
<!-- DATOS DE LA RESERVA -->
<div class="row col-xs-12 padding-block" style="padding-bottom:0">
  <div class="col-xs-12 bg-black push-20">
    <h4 class="text-center white">
      DATOS DE LA RESERVA
      <i class="fas fa-sync-alt" id="reset" style="cursor:pointer; position:absolute; right:2rem"></i>
    </h4>
  </div>
  <div class="col-xs-12 row">
    <div class="col-md-4 col-xs-9 push-10">
      <label>Entrada</label>
      <div class="input-prepend input-group input_dates">
        <?php
        $start1 = Carbon::createFromFormat('Y-m-d', $book->start)->format('d M, y');
        $finish1 = Carbon::createFromFormat('Y-m-d', $book->finish)->format('d M, y');
        ?>
        <input type="text" class="form-control daterange1" id="fechas" name="fechas" required=""
               value="<?php echo $start1; ?> - <?php echo $finish1 ?>" readonly="" {{$disabl_limp}}>
        <input type="hidden" class="date_start" id="start" name="start" value="{{$book->start}}">
        <input type="hidden" class="date_finish" id="finish" name="finish" value="{{$book->finish}}">
      </div>
    </div>
    <div class="col-md-1 col-xs-3 push-10 p-l-0">
      <label>Noches</label>
      <input type="number" class="form-control nigths" name="nigths" id="minDay" disabled value="<?php echo $book->nigths ?>">
    </div>
    <div class="col-md-2 col-xs-3">
      <label>Pax</label>
      <select class=" form-control pax minimal" name="pax" {{$disabl_limp}}>
          <?php for ($i = 1; $i <= 14; $i++): ?>
          <option value="<?php echo $i ?>" <?php echo ($i == $book->pax) ? "selected" : ""; ?>>
          <?php echo $i ?>
          </option>
<?php endfor; ?>
      </select>
    </div>
    <div class="col-md-2 col-xs-3 ">
      <label style="color: red">Pax-Real</label>
      <select class=" form-control real_pax minimal" name="real_pax" {{$disabl_limp}}>
                <?php for ($i = 1; $i <= 14; $i++): ?>
                  <?php if ($i != 9 && $i != 11): ?>
            <option value="<?php echo $i ?>"
            <?php echo ($i == $book->real_pax) ? "selected" : ""; ?> style="color: red">
            <?php echo $i ?>
            </option>
  <?php endif; ?>
<?php endfor; ?>
      </select>
    </div>
    <div class="col-md-3 col-xs-6 push-10">
      <label>Apartamento</label>
      <select class="form-control full-width minimal newroom" name="newroom" {{$disabl_limp}}
              id="newroom" <?php
                if (isset($_GET['saveStatus']) && !empty($_GET['saveStatus'])): echo "style='border: 1px solid red'";
                endif
                ?>>
                <?php foreach ($rooms as $room): ?>
          <option data-size="<?php echo $room->sizeApto ?>"
                  data-luxury="<?php echo $room->luxury ?>"
          <?php if ($room->state == 0) echo 'disabled'; ?>
                  value="<?php echo $room->id ?>" {{ $room->id == $book->room_id ? 'selected' : '' }} >
  <?php echo substr($room->nameRoom . " - " . $room->name, 0, 15) ?>
          </option>
<?php endforeach ?>
      </select>
    </div>
  </div>
  <div class="col-xs-12 row">
    <div class="col-md-2 col-xs-6 push-10">
      <label>Parking</label>
      <select class=" form-control parking recalc minimal" name="parking" {{$disabl_limp}}>
        <option value="0"> -- </option>
<?php for ($i = 1; $i <= 4; $i++): ?>
          <option value="<?php echo $i ?>" {{ $book->type_park == $i ? 'selected' : '' }}><?php echo $book->getParking($i) ?></option>
<?php endfor; ?>
      </select>
    </div>
    <div class="col-md-2 col-xs-6 push-10">
      <label>Sup. Lujo</label>
      <select class=" form-control full-width type_luxury recalc minimal" name="type_luxury" {{$disabl_limp}}>
        <option value="0"> -- </option>
<?php for ($i = 1; $i <= 4; $i++): ?>
          <option value="<?php echo $i ?>" {{ $book->type_luxury == $i ? 'selected' : '' }}><?php echo $book->getSupLujo($i) ?></option>
<?php endfor; ?>
      </select>
    </div>
    <div class="col-md-2 col-xs-6 push-10">
      <label>IN</label>
      <select id="schedule" class="form-control minimal" style="width: 100%;" name="schedule" {{$disabl_limp}}>
        <option>-- Sin asignar --</option>
        <?php for ($i = 0; $i < 24; $i++): ?>
          <option value="<?php echo $i ?>" <?php
                  if ($i == $book->schedule) {
                    echo 'selected';
                  }
                  ?>>
            <?php if ($i < 10): ?>
              <?php if ($i == 0): ?>
                --
              <?php else: ?>
                0<?php echo $i ?>
              <?php endif ?>

          <?php else: ?>
            <?php echo $i ?>
  <?php endif ?>
          </option>
<?php endfor ?>
      </select>
    </div>
    <div class="col-md-2 col-xs-6 push-10">
      <label>Out</label>
      <select id="scheduleOut" class="form-control minimal" name="scheduleOut" {{$disabl_limp}}>
        <option>-- Sin asignar --</option>
        <?php for ($i = 0; $i < 24; $i++): ?>
          <option value="<?php echo $i ?>" <?php if ($i == $book->scheduleOut) echo 'selected';?>>
            
            <?php if ($i < 10): ?>
              <?php if ($i == 0): ?>
                --
              <?php else: ?>
                0<?php echo $i ?>
              <?php endif ?>

          <?php else: ?>
            <?php echo $i ?>
  <?php endif ?>
          </option>
<?php endfor ?>
      </select>
    </div>
  </div>
  <div class="col-xs-12 row">
    <div class="col-md-3 col-xs-6 push-10">
      <label>Agencia</label>
      <select class="form-control full-width agency recalc minimal" name="agency" >
        @include('backend.blocks._select-agency', ['agencyID'=>$book->agency,'book' => $book])
      </select>
    </div>
    <div class="col-md-3 col-xs-6 push-10">
      <label>Cost Agencia</label>
<?php if ($book->PVPAgencia == 0.00): ?>
        <input type="number" step='0.01' class="agencia form-control recalc" name="agencia" value="" {{$disabl_limp}}>
             <?php else: ?>
        <input type="number" step='0.01' class="agencia form-control recalc" name="agencia" {{$disabl_limp}}
               value="<?php echo $book->PVPAgencia ?>">
<?php endif ?>
    </div>
    <div class="col-md-3 col-xs-6 push-20 ">
      <label title="Descuento al propietario">Desc. al prop.</label>
      <input type="number" step='0.01' class="promociones recalc only-numbers form-control " {{$disabl_limp}}
             name="promociones"
             value="<?php echo ($book->promociones > 0) ? $book->promociones : "" ?>">
    </div>
    
      <div class="col-md-2 col-xs-6 push-10 content_image_offert" <?php if ($book->promociones <1) echo 'style="display:none"' ?>>
        <img src="/pages/oferta.png" style="width: 90px;">
      </div>
  </div>
</div>