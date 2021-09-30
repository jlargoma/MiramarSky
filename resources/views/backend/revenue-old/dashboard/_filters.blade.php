<form id="revenu_filters" method="get" action="{{route('revenue')}}">
  <input type="hidden" class="date_start" id="start" name="start" value="{{$start}}">
  <input type="hidden" class="date_finish" id="finish" name="finish" value="{{$finish}}">
  <input type="hidden" id="ch_sel" name="ch_sel" value="{{$ch_sel}}">
  <input type="hidden" id="month" name="month" value="">
  <div class="filter-field">
    <label>Rango</label>
    <input type="text" class="form-control daterange02" id="fechas" name="fechas" required="" style="cursor: pointer; text-align: center;min-height: 28px;" readonly=""  value="{{$range}}">
   
  </div>
</form>
<div class="clearfix"><br/></div>
 <div class="month_select-box">
@foreach($lstMonhs as $k=>$m)
<a class="month_select @if($month == $k) active @endif" data-month="{{$k}}">
  {{$m['name']}}
</a>
@endforeach
</div>
<div class="clearfix"><br/></div>
<br/>
<span class="tabChannels @if(!$ch_sel) active @endif" data-k="">
  TODOS
</span>
@foreach($channels as $ch)
<span class="tabChannels mb-3 @if($ch_sel == $ch) active @endif"  data-k="{{$ch}}">
  {{$ch}}
</span>
@endforeach