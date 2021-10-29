<?php use \Carbon\Carbon;  setlocale(LC_TIME, "ES"); setlocale(LC_TIME, "es_ES"); ?>
<?php 
$pagototalProp = 0;


$auxPend = $total;
$lstLiq = [];
if (count($pagos)> 0){
  foreach ($pagos as $pago){
    $divisor = 0;
    if(preg_match('/,/', $pago->PayFor)){
        $aux = explode(',', $pago->PayFor);
        for ($i = 0; $i < count($aux); $i++){
            if ( !empty($aux[$i]) ){
                $divisor ++;
            }
        }

    }else{
        $divisor = 1;
    }
    $importAux = round(($pago->import / $divisor),2);
    $pagototalProp += $importAux;
    $auxPend -= $importAux;

    $lstLiq[] = [
      dateMin($pago->date),
      $pago->concept,
      moneda($importAux,true,2),
      moneda($auxPend,true,2),  
    ];
  }
}
?>
<div class="col-xs-12">
  <div class="row">
	<?php if (count($lstLiq)> 0): ?>
    <table class="table table-condensed no-footer" id="basicTable" role="grid">
      <thead>
      <th><i class="fa fa-calendar" aria-hidden="true"></i></th>
      <th>Tipo</th>
      <th><i class="fas fa-euro-sign"></i></th>
      <th>Pend</th>
    </thead>
    <tbody>
	<?php foreach ($lstLiq as $pago): ?>
      <tr>
        <td class="text-center">{{$pago[0]}}</td>
        <td class="text-center">{{$pago[1]}}</td>
        <td class="text-center"><h5>{{$pago[2]}}</h5></td>
        <td class="text-center">{{$pago[3]}}</td>
      </tr>
	<?php endforeach; ?>
    </tbody>
    </table>
	<?php else: ?>
    <div class="col-md-12 text-center">Aun no hay pagos realizados</div>
    <?php endif ?>
  </div>
  <div class="col-xs-12 bg-complete">
      <div class="col-xs-6">
          <h5 class="text-center white">GENERADO</h5>
      </div>
      <div class="col-xs-6 text-center text-white">
          <h5 class="text-center white"><strong><?php echo number_format($total,2,',','.'); ?>€</strong></h5>
      </div>
  </div>
  <div class="col-xs-12 bg-success">
      <div class="col-xs-6">
          <h5 class="text-center white">PAGADO</h5>
      </div>
      <div class="col-xs-6 text-center text-white">
          <h5 class="text-center white"><strong><?php echo number_format($pagototalProp,2,',','.'); ?>€</strong></h5>
      </div>
  </div>
  <div class="col-xs-12 bg-danger">
      <div class="col-xs-6">
          <h5 class="text-center white">PENDIENTE</h5>
      </div>
      <div class="col-xs-6 text-center text-white">
          <h5 class="text-center white"><strong><?php echo number_format(($total - $pagototalProp),2,',','.'); ?>€</strong></h5>
      </div>
  </div>
</div>