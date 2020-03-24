<link href="/assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" type="text/css" media="screen">
<link rel="stylesheet" href="{{ asset('/frontend/css/components/daterangepicker.css')}}" type="text/css" />
<style type="text/css">
  .roomEspecifica{
    /*padding: 15px;*/
    border: 2px solid black;
    margin-bottom: 5px;
    cursor: pointer;
  }
  .roomEspecifica.selected{
    /*padding: 15px;*/
    border: 2px solid black;
    background-color: rgba(179,221,234,0.62);
    cursor: pointer;
  }

</style>
<div class="col-xs-12 bg-white">
  <div class="row" style="padding: 20px; border: 2px solid #000;">
    <div class="col-xs-12">
      <form action="{{ url('/admin/gastos/create') }}" method="post" id="formAddGasto">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        <div class="col-xs-12 col-md-12">
          <div class="col-xs-12 col-lg-2 col-md-2 push-10" style="padding: 0">
            <label for="date">fecha</label>
            <div id="datepicker-component" class="input-group date col-xs-12">
              <input type="text" class="form-control" name="fecha" id="fecha" value="<?php echo date('d/m/Y') ?>" style="font-size: 12px">
              <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
            </div>
          </div>
          <div class="col-lg-1 col-md-6 col-xs-2">
            <label for="import">Apto</label>
            <div class="row" id="contentRooms" style="">
              <div class=" roomEspecifica text-center" data-idRoom="<?php echo $room->id; ?>" data-selected="0" style="width: 30px; height: 30px;float: left; margin: 5px 2px;">
                <?php echo substr($room->nameRoom, -2); ?>
              </div>
            </div>
          </div>
          <div class=" col-xs-10 col-lg-3 col-md-3 push-10" style="padding-right:0;">
            <div class="col-xs-12 col-md-12 push-10">
              <label for="concept">Concepto</label>
              <input  type="text" class="form-control" name="concept" id="concept" />
            </div>
          </div>

          <div class="col-xs-12 col-lg-3 col-md-2 push-10">
            <label for="type">T. Gasto</label>
            <select class="js-select2 form-control" id="type" name="type" style="width: 100%;" data-placeholder="Seleccione un tipo" required >
              <option value=""></option>
              @foreach($gType as $k=>$v)
                <option value="{{$k}}">{{$v}}</option>
              @endforeach
            </select>
          </div>

          <div class="col-xs-12 col-lg-2 col-md-1 push-10">
            <label for="import">Importe</label>
            <input  type="number" step="0.01" name="import" id="import" class="form-control"  />
          </div>
        </div>
        <div style="clear:both"></div>
        <div class="col-xs-12 col-lg-2 col-md-2  push-10">
          <label for="pay_for">Met de pago</label>
          <select class="js-select2 form-control" id="type_payment" name="type_payment" style="width: 100%;" data-placeholder="Seleccione una" required>
            <option></option>
            @foreach($typePayment as $k=>$v)
              <option value="{{$k}}">{{$v}}</option>
            @endforeach
          </select>
        </div>
        <input type="hidden" id="type_payFor" name="type_payFor" value="1"/>
        <div class="col-xs-12 col-md-5 col-lg-7">
          <div class="col-lg-10 col-md-8 col-xs-12">
            <div class="row form-group push-10">
              <label for="comment">Observaciones</label>
              <textarea class="form-control" name="comment" id="comment"></textarea>
            </div>
          </div>

          <div class="col-lg-2 col-md-4 form-group text-center push-10" style="padding: 20px;">
            <button class="btn btn-lg btn-success">Añadir</button>
          </div>
        </div>

      </form>
    </div>
  </div>
</div>
<script src="/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="/assets/plugins/moment/moment.min.js"></script>

<script type="text/javascript">
$('#datepicker-range, #datepicker-component, #datepicker-component2').datepicker();
$(document).ready(function () {

  $('.roomEspecifica').click(function (event) {
    if ($(this).attr('data-selected') == 0) {

      $(this).attr('data-selected', 1);
      $(this).addClass('selected');

    } else {

      $(this).attr('data-selected', 0);
      $(this).removeClass('selected');

    }
    var count = 0;
    $('.roomEspecifica').each(function () {
      if ($(this).attr('data-selected') == 1) {
        count++;
      }
    });

    $('.totalRooms').empty().append(count);
    var gastoDividido = $('#import').val() / count;
    $('.notifiations').empty().append(gastoDividido.toFixed(2));



//			if (count > 0) {
//				$('.content-notifications').show();
//			}else{
//				$('.content-notifications').hide();
//			}
  });


  $('#formAddGasto').submit(function (event) {
    event.preventDefault();
    var url = $('#formAddGasto').attr('action');

    var _token = $('input[name="_token"]').val();
    var fecha = $('input[name="fecha"]').val();
    var concept = $('#concept').val();
    var type = $('#type').val();
    var type_payFor = $('#type_payFor').val();
    var importe = $('#import').val();
    var type_payment = $('#type_payment').val();
    var comment = $('#comment').val();

    $.post(url, {
      _token: _token,
      fecha: fecha,
      concept: concept,
      type: type,
      type_payFor: type_payFor,
      import: importe,
      type_payment: type_payment,
      comment: comment,
      asig_rooms: {{$room->id}}
      }, function (data) {
        if (data == 'ok') {
          location.reload();
        }
      });


  });

});
</script>