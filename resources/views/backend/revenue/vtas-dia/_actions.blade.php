<form action="{{route('revenue.donwlVtasDia')}}" method="post" style="display: inline-block">
  <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
  <input type="hidden" name="ch_sel" value="{{$ch_sel}}">
  <input type="hidden" name="sel_mes" value="{{$sel_mes}}">
  <button class="btn btn-complete" >Descargar Excel</button>
</form>