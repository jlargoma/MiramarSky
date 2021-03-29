<form id="revenu_filters" method="get" action="{{route('revenue.pickUpNew')}}">
  <div class="form-group row mt-2em">
    <label for="Rango" class="col-sm-2 col-form-label mt-1em" style="font-size: 16px; margin-top: 9px;">Rango</label>
    <div class="col-sm-10 col-md-6">
      <input type="text" class="form-control daterange02" id="fechas" name="fechas" required="" style="cursor: pointer; text-align: center;min-height: 28px;" readonly=""  value="{{$range}}">
    <input type="hidden" class="date_start" id="start" name="start" value="{{$start}}">
    <input type="hidden" class="date_finish" id="finish" name="finish" value="{{$finish}}">
    </div>
  </div>
    <input type="hidden" id="ch_sel" name="ch_sel" value="{{$ch_sel}}">
</form>
<div class="clearfix "></div>
<br/>
<span class="tabChannels @if(!$ch_sel) active @endif" data-k="">
  TODOS
</span>
@foreach($channels as $ch)
<span class="tabChannels @if($ch_sel == $ch) active @endif"  data-k="{{$ch}}">
  {{$ch}}
</span>
@endforeach