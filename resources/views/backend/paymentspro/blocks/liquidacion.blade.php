<div class="row push-20" style="padding: 10px; background-color: rgba(16,207,189,0.5);">
				<h4 class="text-left">
        Liquidación:
        <span class="font-w800"><?php echo $startDate ?></span> -
        <span class="font-w800"><?php echo $finishDate ?></span>
				</h4>
				<table class="table table-bordered table-hover  no-footer" id="basicTable" role="grid">
        <tr>
            <th class="text-center bg-complete text-white">ING. PROP</th>
            <th class="text-center bg-complete text-white">Apto</th>
            <th class="text-center bg-complete text-white">Park</th>
            <?php if ($room->luxury == 1): ?>
              <th class="text-center bg-complete text-white">Sup.Lujo</th>
            <?php endif ?>
        </tr>
        <tr>
            <td class="text-center total">{{moneda($total,false)}}</td>
            <td class="text-center">{{moneda($apto,false)}}</td>
            <td class="text-center">{{moneda($park,false)}}</td>
            <?php if ($room->luxury == 1): ?>
              <td class="text-center">{{moneda($lujo,false)}}</td>
            <?php endif ?>
        </tr>
				</table>
</div>

<table class="table table-bordered no-footer">
    <tr>
        <td class="text-center white" style="background-color: #48b0f7;">
            <h5 class="text-center white" style="margin-top: 0">GENERADO</h5>
            <strong>{{moneda($total,false)}}</strong>
        </td>
        <td class="text-center white" style="background-color: #10cfbd;">
            <h5 class="text-center white" style="margin-top: 0">PAGADO</h5>
            <strong>{{moneda($pagototalProp,false)}}</strong>
        </td>
        <td class="text-center white" style="background-color: #f55753;">
            <h5 class="text-center white" style="margin-top: 0">PENDIENTE</h5>
            <strong>{{moneda(($total - $pagototalProp),false)}}</strong>
        </td>
    </tr>
</table>
<div class="addExpence">
    <button type="button" class="toggle_formNewExpense btn btn-success">
        <i class="fa fa-plus-circle"></i> Añadir Gastos
    </button>
    <form action="{{ url('/admin/gastos/create') }}" method="post"  id="formNewExpense">
  <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
  <input type="hidden" name="back"  id="back" value="1">
  <input type="hidden" name="type_payFor"  id="type_payFor" value="1">
  <input type="hidden" name="asig_rooms"  id="asig_rooms" value="{{$roomID}}">
  <div class="row">
    <div class="col-lg-2 col-md-3 col-xs-12 mb-1em">
      <label for="date">Fecha</label>
      <div id="datepicker-component" class="input-group date col-xs-12">
          <input type="text" class="form-control datepicker" name="fecha" id="fecha" value="<?php echo date('d/m/Y') ?>" style="font-size: 12px">
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-xs-12 mb-1em">
      <label for="concept">Concepto</label>
      <input  type="text" class="form-control" name="concept" id="concept" />
    </div>

    <div class="col-lg-3 col-md-3 col-xs-12">
      <label for="import">Importe</label>
      <input  type="number" step="0.01" name="import" id="import" class="form-control" required />
    </div>
    <div class="col-lg-3 col-md-3 col-xs-12">
      <label for="pay_for">Met de pago</label>
      <select class="js-select2 form-control" id="type_payment" name="type_payment" style="width: 100%;" data-placeholder="Seleccione una" required>
        @foreach($typePayment as $k=>$v)
        <option value="{{$k}}" >{{$v}}</option>
        @endforeach
      </select>
    </div>
    </div>
  <div class="row">
    
    <div class="col-lg-6 col-md-6 col-xs-12">
      <label for="comment">Observaciones</label>
      <textarea class="form-control" name="comment" id="comment"></textarea>
    </div>
    <div class="col-lg-3 col-md-4 col-xs-12 mb-1em">
      <label for="type">T. Gasto</label>
        <select class="form-control" id="type" name="type"  data-placeholder="Seleccione un tipo" required >
        @foreach($gType as $k=>$v)
        <option value="{{$k}}">{{$v}}</option>
        @endforeach
      </select>
    </div>
    <div class="col-lg-2 col-md-2 col-xs-12">
        <label style="display: block">&nbsp;</label>
      <button class="btn btn-success btn-block" type="submit">Añadir</button>
    </div>
  </div>
</form>
</div>
