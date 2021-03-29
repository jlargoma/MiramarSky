<form action="{{route('revenue.donwlPickUp')}}" method="post"  class="form-inline">
  <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
  <input type="hidden" name="start" value="{{$start}}">
  <input type="hidden" name="finish" value="{{$finish}}">
  <button class="btn btn-complete" >Excel</button>
</form>
<form action="{{route('revenue.generatePickUp')}}" method="post" class="form-inline" >
  <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
  <button class="btn btn-primary" style="white-space: normal;" onclick="return confirm('Adventencia: ésta acción sobrescribirá los datos actuales de la temporada. Desea continuar?')">Generar Datos de la temporada</button>
</form>