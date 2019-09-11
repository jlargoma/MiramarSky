<style>
  .btn-primary{
    background-color: #295d9b!important;
  }
</style>
<?php
$url = substr(Request::path(), 6);
$posicion = strpos($url, '/');
if ($posicion > 0) {
  $url = substr($url, 0, $posicion);
} else {
  $url;
};
?>

  <div class="btn-contabilidad">
    <?php if ($url == "contabilidad"): ?>
      <button class="btn btn-md text-white active"  disabled>Estadisticas</button>
    <?php else: ?>
      <a class="text-white btn btn-md btn-primary" href="{{url('/admin/contabilidad/')}}">Estadisticas</a>
    <?php endif ?>	
  </div>
  
  <div class="btn-contabilidad">
    <?php if ($url == "gastos"): ?>
      <button class="btn btn-md text-white active"  disabled>Gastos</button>
    <?php else: ?>
      <a class="text-white btn btn-md btn-primary" href="{{url('/admin/gastos/')}}">Gastos</a>
    <?php endif ?>	
  </div>

  <div class="btn-contabilidad">
    <?php if ($url == "ingresos"): ?>
      <button class="btn btn-md text-white active"  disabled>Ingresos</button>
    <?php else: ?>
      <a class="text-white btn btn-md btn-primary" href="{{url('/admin/ingresos/')}}">Ingresos</a>
    <?php endif ?>
  </div>

  <div class="btn-contabilidad">
    <?php if ($url == "banco"): ?>
      <button class="btn btn-md text-white active"  disabled>Banco</button>
    <?php else: ?>
      <a class="text-white btn btn-md btn-primary" href="{{url('/admin/banco/')}}">Banco</a>
    <?php endif ?>
  </div>

  <div class="btn-contabilidad">
    <?php if ($url == "caja"): ?>
      <button class="btn btn-md text-white active"  disabled>Caja</button>
    <?php else: ?>
      <a class="text-white btn btn-md btn-primary" href="{{url('/admin/caja/')}}">Caja</a>
    <?php endif ?>
  </div>

  <div class="btn-contabilidad">
    <?php if ($url == "perdidas-ganancias"): ?>
      <button class="btn btn-md text-white active"  disabled>CTA P &amp; G</button>

    <?php else: ?>
      <a class="text-white btn btn-md btn-primary" href="{{url('/admin/perdidas-ganancias/')}}">CTA P &amp; G</a>
    <?php endif ?>
  </div>

  <div class="btn-contabilidad">
    <?php if ($url == "limpiezas"): ?>
      <button class="btn btn-md text-white active"  disabled>LIMPIEZAS</button>
    <?php else: ?>
      <a class="text-white btn btn-md btn-primary" href="{{url('/admin/limpiezas/')}}">LIMPIEZAS</a>
    <?php endif ?>
  </div>
  <div class="btn-contabilidad">
    <?php if ($url == "orders-payland"): ?>
      <button class="btn btn-md text-white active"  disabled>PAYLAND</button>
    <?php else: ?>
      <a class="text-white btn btn-md btn-primary" href="{{url('/admin/orders-payland')}}">PAYLAND</a>
    <?php endif ?>
  </div>
