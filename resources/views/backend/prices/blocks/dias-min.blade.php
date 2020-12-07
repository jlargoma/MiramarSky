<div class="box">
  <h2>DIAS ESTANCIA MINIMA</h2>
  <div class="row">
    <form method="POST" action="{{route('channel.price.cal.upd','ALL')}}" id="channelFormMinStay">
      <input type="hidden" id="_token" name="_token" value="<?php echo csrf_token(); ?>">
      <div class="pt-1 col-md-12">
        <div class="row">
          <div class="col-md-3 col-xs-5 pt-1"><label>Rango de Fechas</label></div>
          <div class="col-md-9 col-xs-7">
            <input type="text" class="form-control daterange1" id="date_range" name="date_range" value="">
            <input type="hidden" class="date_start" name="date_start" >
            <input type="hidden" class="date_finish" name="date_end" >
          </div>
        </div>

      </div>
      <div class="pt-1 col-md-12 row">
        <button id="selAllDays" type="button"  class="btn_days">Todos</button>
        <button id="selWorkdays" type="button" class="btn_days">Laborales</button>
        <button id="selHolidays" type="button" class="btn_days">Fin de semana</button>
      </div>
      <div class="pt-1 col-md-12 row">
        @foreach($dw as $k=>$v)
        <div class="weekdays">
          <label>
            <input type="checkbox" name="dw_{{$k}}" id="dw_{{$k}}" checked="checked"/>
            <span> {{$v}}</span>
          </label>
        </div> 
        @endforeach
      </div>
      <div class="pt-1 col-xs-6">
        <label>Estancia Mín.</label>
        <input type="number" class="form-control" name="min_estancia" id="min_estancia">
      </div>
      <div class=" pt-1 col-xs-6">
        <button class="btn btn-primary m-t-20">Guardar</button>
      </div>
    </form>
  </div>
  <div class="row pt-1">
    <p class="alert alert-danger" style="display:none;" id="error"></p>
    <p class="alert alert-success" style="display:none;" id="success"></p>
  </div>
  
  @if(count($logMinStays))
  <div class="table-responsive table-logs">
    <table class="table">
      <thead>
        <tr>
          <th >Fecha</th>
          <th >Min. Stay</th>
          <th >Días</th>
          <th >Usuario</th>
        </tr>
      </thead>
      @if($logMinStays)
      <tbody>
        @foreach($logMinStays as $item)
        <tr>
          <td class="nowrap">{{$item['start']}} / {{$item['end']}}</td>
          <td class="nowrap">{{$item['min_stay']}}</td>
          <td class="">{{$item['weekDays']}}</td>
          <td class="nowrap">{{$item['user']}}</td>
        </tr>
        @endforeach
        </tbody>
      @endif
    </table>
  </div>
  @endif
</div>