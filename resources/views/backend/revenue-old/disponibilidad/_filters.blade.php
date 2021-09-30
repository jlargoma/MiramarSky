<form id="revenu_filters" method="get" action="{{route('revenue.disponibilidad')}}" class="form-inline">
    <div class="filter-field">
        <label>Mes</label>
        <select name="month" id="month" class="form-control">
            @foreach($lstMonths as $k=>$n)
            <option value="{{$k}}" @if($month_key == $k) selected @endif>{{$n['name'].' '.$n['y']}}</option>
            @endforeach
        </select>
    </div>
</form>
<form method="post" action="{{route('revenue.donwlDisponib')}}" class="form-inline">
    <input  name="month_key" value="{{$month_key}}"  type="hidden">
    <input type="hidden" id="_token" name="_token" value="<?php echo csrf_token(); ?>">
    <button class="btn btn-primary">Descargar</button>
</form>