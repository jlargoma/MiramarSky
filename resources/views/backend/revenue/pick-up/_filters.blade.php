<form id="revenu_filters" method="get" action="{{route('revenue.pickUpNew')}}">
  <div class="filter-field">
    <label>Rango</label>
    <input type="text" class="form-control daterange02" id="fechas" name="fechas" required="" style="cursor: pointer; text-align: center;min-height: 28px;" readonly=""  value="{{$range}}">
    <input type="hidden" class="date_start" id="start" name="start" value="{{$start}}">
    <input type="hidden" class="date_finish" id="finish" name="finish" value="{{$finish}}">
  </div>
    <input type="hidden" id="ch_sel" name="ch_sel" value="{{$ch_sel}}">
</form>
<div class="clearfix"></div>
<span class="tabChannels @if(!$ch_sel) active @endif" data-k="">
  TODOS
</span>
@foreach($channels as $ch)
<span class="tabChannels @if($ch_sel == $ch) active @endif"  data-k="{{$ch}}">
  {{$ch}}
</span>
@endforeach