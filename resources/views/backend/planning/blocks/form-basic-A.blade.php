<div class="col-xs-12 padding-block push-0" style="padding-bottom:0">
  <div class="col-xs-12 bg-black push-20">
    <h4 class="text-center white">
      DATOS DEL CLIENTE
    </h4>
  </div>
<div class="col-xs-12 row">
  <div class="col-md-4 col-xs-12 push-10">
    <label for="name">Nombre</label>
    <input class="form-control" type="text" name="nombre"
           value="<?php echo $book->customer->name ?>"
           data-id="-1"  disabled>
  </div>
  <div class="col-md-4  col-xs-6 push-10">
    <label for="email">Email</label>
    <input class="form-control" type="email" name="email"
           value="<?php echo $book->customer->email ?>"
           data-id="-1" disabled>
  </div>
  <div class="col-md-4 col-xs-6  push-10">
    <label for="phone">Telefono</label>
    <input class="form-control only-numbers" type="text" name="phone"
           value="<?php echo $book->customer->phone ?>"
           data-id="-1" disabled>
  </div>
</div>

<div class="col-xs-12 row">
  <div class="col-md-3 col-xs-6 push-10">
    <label for="dni">DNI</label>
    <input class="form-control" type="text" name="dni"
           value="<?php echo $book->customer->DNI ?>" disabled>
  </div>
  <div class="col-md-3 col-xs-6 push-10">
    <label for="address">DIRECCION</label>
    <input class="form-control" type="text" name="address"
           value="<?php echo $book->customer->address ?>" disabled>
  </div>
  <div class="col-md-3 col-xs-6  push-10 ">
    <label for="country">PAÍS</label>
    <?php $c_country = ($book->customer->country) ? strtolower($book->customer->country) : 'es'; ?>
    <select class="form-control country minimal" name="country" disabled>
      <option value="">--Seleccione país --</option>
      <?php
      foreach (\App\Countries::orderBy('code', 'ASC')->get() as $country):
        ?>
        <option value="<?php echo $country->code ?>" <?php if (strtolower($country->code) == $c_country) {
        echo "selected";
      } ?>>
        <?php echo $country->country ?> 
        </option>
<?php endforeach; ?>
    </select>
  </div>
  <div class="col-md-3 col-xs-6 push-10 content-cities" <?php if ($c_country != 'es') echo ' style="display: none;" '; ?>>
    <label for="city">PROVINCIA</label>
      <?php $book_prov = ($book->customer->province) ? $book->customer->province : 28; ?>
    <select class="form-control province minimal" name="province" disabled>
      <option>--Seleccione  --</option>
<?php foreach (\App\Provinces::orderBy('province', 'ASC')->get() as $prov): ?>
        <option value="<?php echo $prov->code ?>" <?php if ($prov->code == $book_prov) {
    echo "selected";
  } ?>>
          {{$prov->province}}
        </option>
<?php endforeach; ?>
    </select>
  </div>
</div>
  </div>