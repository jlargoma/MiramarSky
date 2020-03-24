<style type="text/css">
  .input-group{
    width: 100%;
  }
</style>
<?php

use \Carbon\Carbon; ?>

<div class="col-xs-12">
  <h2> <?php echo $room->nameRoom . " de (" . $room->user->name ?>)</h2>
</div>
<div class="row">

  <div class="col-xs-12">
    <!-- START PANEL -->

    <div class="row">


      <div class="col-md-12 col-xs-12">
        <div class="col-md-12 col-xs-12">

          <div class="col-md-7 col-xs-12">

            <h3 class="tex-center">Resumen de pagos</h3>
            <div class="row">
              <table class="table table-hover " >
                <thead >
                <th class ="text-center bg-complete text-white">Fecha</th>
                <th class ="text-center bg-complete text-white">Importe</th>
                <th class ="text-center bg-complete text-white">Metodo</th>
                <th class ="text-center bg-complete text-white">Concepto</th>
                </thead>
                <tbody>
                  <?php if (count($lstGastos) > 0): ?>
                    <?php foreach ($lstGastos as $payment): ?>
                      <tr id="payment-{{$payment['ID']}}">
                        <td class="text-center">{{$payment['date']}}</td>
                        <td class="text-center">{{moneda($payment['import'])}}</td>
                        <td class="text-center">{{$payment['type_payment']}}</td>
                        <td class="text-center">
                          {{$payment['type']}}
                          @if (! empty($payment['comment']))
                          <span data-toggle="tooltip" data-placement="top" title="{{ $payment['comment'] }}"><i class="fa fa-comment"></i></button>
                            @endif
                        </td>
                      </tr>
                    <?php endforeach ?>
                  <?php else: ?>
                    <tr>
                      <td class="text-center" colspan="3">No hay Pagos para este apartamento</td>
                    </tr>
                  <?php endif ?>
                </tbody>
              </table>
            </div>
          </div>
          <div class="col-md-5 col-xs-12">
            <h3 class="tex-center">Grafica de pagos</h3>
            <div class="row">
              <div class="table-responsive">
                <table class="table table-hover">
                  <thead>
                  <th class="text-center bg-complete text-white">Generado</th>
                  <th class="text-center bg-success text-white">Pagado</th>
                  <th class="text-center bg-danger text-white">Pendiente</th>
                  </thead>
                  <thead >
                  <th class ="text-center bg-complete text-white">{{moneda($total)}}</th>
                  <th class ="text-center bg-success text-white" >{{moneda($pagoProp)}}</th>
                  <th class ="text-center bg-danger text-white">{{moneda($total - $pagoProp)}}</th>
                  </thead>
                </table>
              </div>
            </div>
            <div class="row">
              <table class="table table-striped" >
                <thead >
                  @foreach ($lstByPayment as $k=>$v)
                  <tr>
                    <th class ="text-center bg-complete text-white">{{$typePayment[$k]}}</th>
                    <th class="text-center ">{{moneda($v)}}</th>
                  </tr>
                  @endforeach
                </thead>
              </table>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
  <div class="col-xs-12">
    <input type="hidden" name="room_id" value="{{$room->id}}"/>
    @include('backend.paymentspro.gastos._formGastosApto')
  </div>
</div>
{{-- @TODO remove this, something is causing a conflict with the custom.js --}}
<script>
  $(function () {
    $('[data-toggle="tooltip"]').tooltip();

    $('.roomEspecifica').not('[data-idRoom="' + $('input[name="room_id"]').val() + '"]').hide();
    room_id_button = $('.roomEspecifica[data-idRoom="' + $('input[name="room_id"]').val() + '"]');
    room_id_button.click();
    room_id_button.unbind('click');
  });
</script>

