<?php
use \Carbon\Carbon;
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
    <div class="col-md-3 col-xs-9 push-10">
      <label>Entrada</label>
      <div class="input-prepend input-group input_dates">
        <?php
        $start1 = Carbon::createFromFormat('Y-m-d', $book->start)->format('d M, y');
        $finish1 = Carbon::createFromFormat('Y-m-d', $book->finish)->format('d M, y');
        ?>
        <input type="text" class="form-control" id="fechas" name="fechas" required=""
               value="<?php echo $start1; ?> - <?php echo $finish1 ?>" readonly="" disable>
      </div>
    </div>
    <div class="col-md-1 col-xs-3 push-10 p-l-0">
      <label>Min. Est.</label>
      <input class="form-control minimal" disabled="" id="minDay" value="0">
    </div>
    <div class="col-md-1 col-xs-3 push-10 p-l-0">
      <label>Noches</label>
      <input type="number" class="form-control nigths" name="nigths" id="nigths" disabled value="<?php echo $book->nigths ?>">
    </div>
    <div class="col-md-2 col-xs-3">
      <label>Pax</label>
      <input type="number" class="form-control pax minimal" disabled value="<?php echo $book->pax ?>">
    </div>
    <div class="col-md-2 col-xs-3 ">
      <label style="color: red">Pax-Real</label>
      <input type="number" class="form-control pax minimal" disabled value="<?php echo $book->real_pax ?>">
    </div>
    <div class="col-md-3 col-xs-6 push-10">
      <label>ALOJAMIENTO</label>
      <select class="form-control full-width minimal newroom" name="newroom" disable
              id="newroom" <?php
                if (isset($_GET['saveStatus']) && !empty($_GET['saveStatus'])): echo "style='border: 1px solid red'";
                endif
                ?>>
                <?php foreach ($rooms as $room): ?>
          @if($room->id == $book->room_id)
          <option data-size="<?php echo $room->sizeApto ?>"
                  data-luxury="<?php echo $room->luxury ?>"
                  value="<?php echo $room->id ?>" {{ $room->id == $book->room_id ? 'selected' : '' }} >
  <?php echo substr($room->nameRoom . " - " . $room->name, 0, 15) ?>
          </option>
          @endif
<?php endforeach ?>
      </select>
    </div>
  </div>
  <div class="col-xs-12 row">
    <div class="col-md-2 col-xs-6 push-10">
      <label>Parking</label>
      <?php $sel = ($book->type_park) ? $book->type_park : 2; ?>
      <input class="form-control pax minimal" disabled value="<?php echo $book->getParking($sel) ?>">
    </div>
    <div class="col-md-2 col-xs-6 push-10">
      <label>Sup. Lujo</label>
        <?php $sel = ($book->type_luxury) ? $book->type_luxury : 2;?>
      <input class="form-control pax minimal" disabled value="<?php echo $book->getSupLujo($sel) ?>">
    </div>
    <div class="col-md-2 col-xs-6 push-10">
      <label>IN</label>
      <select id="schedule" class="form-control minimal" style="width: 100%;" name="schedule" disabled>
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
      <select id="scheduleOut" class="form-control minimal" name="scheduleOut" disabled>
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
</div>