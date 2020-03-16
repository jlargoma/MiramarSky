<form action="{{ url('/admin/ingresos/create') }}" method="post"  >
  <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
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
      <select class="js-select2 form-control" id="concept" name="concept"  required>
      @foreach($ingrType as $k=>$v)
        <option value="{{$k}}">{{$v}}</option>
      @endforeach
      </select>
    </div>
    
    <div class="col-lg-4 col-md-6 col-xs-12">
      <label for="import">Importe</label>
      <input  type="number" step="0.01" name="import" id="import" class="form-control" required />
    </div>

    <div class="col-md-6 col-xs-12 mt-1em">
      <button class="btn btn-success" type="submit">Añadir</button>
    </div>
  </div>
</form>
<link href="/assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" type="text/css" media="screen">
<link rel="stylesheet" href="{{ asset('/frontend/css/components/daterangepicker.css')}}" type="text/css" />
<script src="/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="/assets/plugins/moment/moment.min.js"></script>
<script type="text/javascript">
  $('#fecha').datepicker();
</script>
