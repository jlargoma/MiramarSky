<form action="{{ url('/admin/gastos/create') }}" method="post"  id="formNewExpense">
  <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
  <input type="hidden" name="asig_rooms"  id="asig_rooms" value="">
  <div class="row">
    <div class="col-lg-4 col-md-6 col-xs-12 mb-1em">
      <label for="date">Fecha</label>
      <div id="datepicker-component" class="input-group date col-xs-12">
          <input type="text" class="form-control" name="fecha" id="fecha" value="<?php echo date('d/m/Y') ?>" style="font-size: 12px">
          <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-xs-12 mb-1em">
      <label for="concept">Concepto</label>
      <input  type="text" class="form-control" name="concept" id="concept" />
    </div>
    <div class="col-lg-4 col-md-6 col-xs-12 mb-1em">
      <label for="type">T. Gasto</label>
        <select class="form-control" id="type" name="type"  data-placeholder="Seleccione un tipo" required >
        @foreach($gType as $k=>$v)
        <option value="{{$k}}">{{$v}}</option>
        @endforeach
      </select>
    </div>
    <div class="col-lg-4 col-md-6 col-xs-12">
      <label for="import">Importe</label>
      <input  type="number" step="0.01" name="import" id="import" class="form-control" required />
    </div>
    <div class="col-lg-4  col-md-6 col-xs-12">
      <label for="pay_for">Met de pago</label>
      <select class="js-select2 form-control" id="type_payment" name="type_payment" style="width: 100%;" data-placeholder="Seleccione una" required>
        @foreach($typePayment as $k=>$v)
        <option value="{{$k}}">{{$v}}</option>
        @endforeach
      </select>
    </div>
    <div class="col-lg-4 col-md-6 col-xs-12">
      <label for="type">Imputacion</label>
      <select class="js-select2 form-control" id="type_payFor" name="type_payFor" style="width: 100%;" data-placeholder="Seleccione un tipo" required >
        <option value="0">Generíco</option>
        <option value="1">Especifico</option>
      </select>
    </div>
    <div class="col-xs-12 mt-1em">
          <div class="row" id="contentRooms" style="display: none;">
            <?php foreach (\App\Rooms::where('state', 1)->orderBy('order', 'ASC')->get() as $key => $room): ?>
              <div class=" roomEspecifica text-center" data-idRoom="<?php echo $room->id; ?>" data-selected="0">
                <?php echo substr($room->nameRoom, -2); ?>
              </div>
            <?php endforeach ?>
          </div>
          <div class="row content-notifications" style="display: none;">
            <p class="text-center">
              Gasto especifico para: <span class="font-w800 totalRooms">0</span><br>
              Se asignará un gasto a cada propietario de: <span class="font-w800 notifiations"></span> €
            </p>
          </div>
    </div>
    <div class="col-md-6 col-xs-12 mt-1em">
      <label for="comment">Observaciones</label>
      <textarea class="form-control" name="comment" id="comment"></textarea>
    </div>
    
    <div class="col-md-6 col-xs-12 mt-1em">
      <button class="btn btn-success" type="submit">Añadir</button>
      <button class="btn btn-secondary" type="button" id="reload">Refrescar Pantalla</button>
    </div>
  </div>
</form>
<link href="/assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" type="text/css" media="screen">
<link rel="stylesheet" href="{{ asset('/frontend/css/components/daterangepicker.css')}}" type="text/css" />
<script src="/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="/assets/plugins/moment/moment.min.js"></script>
<script type="text/javascript">
  $('#fecha').datepicker();
  $(document).ready(function () {
  $('#type_payFor').change(function (event) {
    if ($(this).val() == 1) {
      $('#contentRooms').show();
    } else {
      $('#contentRooms').hide();
    }
  });

  $('.roomEspecifica').click(function (event) {
    if ($(this).attr('data-selected') == 0) {

      $(this).attr('data-selected', 1);
      $(this).addClass('selected');

    } else {

      $(this).attr('data-selected', 0);
      $(this).removeClass('selected');

    }
    var count = 0;
    var stringRooms = '';
    $('.roomEspecifica').each(function () {
      if ($(this).attr('data-selected') == 1) {
        stringRooms = stringRooms + $(this).attr('data-idRoom') + ",";
        count++;
      }
    });

    $('.totalRooms').empty().append(count);
    var gastoDividido = $('#import').val() / count;
    $('.notifiations').empty().append(gastoDividido.toFixed(2));

    $('#asig_rooms').val(stringRooms);
    

    if (count > 0) {
      $('.content-notifications').show();
    } else {
      $('.content-notifications').hide();
    }
  });
  });
</script>
