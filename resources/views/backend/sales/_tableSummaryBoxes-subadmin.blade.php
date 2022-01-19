<?php
if (!isset($hide))
  $hide = ['t_day_2'];
//dd($hide);
?>
<style type="text/css">
  .bordered{
    padding: 15px;
    border:1px solid #e8e8e8;
    background: white;
  }
  .sumary.bordered {
    padding: 1em 1.5em;
    float: left;
    max-width: 20em;
    margin-right: 3px;
  }
</style>
<div class="row">
  <div class="col-lg-8 col-md-12">
    <div class="sumary bordered">
      <label>Total Reservas</label>
      <h3 class="text-black font-w400 text-center">{{$summary['total']}}</h3>
    </div>
    <div class="sumary bordered">
      <label>Nº Inquilinos</label>
      <h3 class="text-black font-w400 text-center">{{$summary['pax']}}</h3>
    </div>
    <div class="sumary bordered">
      <label>Total vnts temp</label>
      <h3 class="text-black font-w400 text-center">{{$summary['total_pvp']}}€</h3>
    </div>
    <div class="sumary bordered">
      <label>Venta propia</label>
      <h3 class="text-black font-w400 text-center">{{$summary['vta_prop']}}%</h3>
    </div>
    <div class="sumary bordered">
      <label>Venta agencia</label>
      <h3 class="text-black font-w400 text-center">{{$summary['vta_agency']}}%</h3>
    </div>

    <div class="sumary bordered">
      <label>Estancia media</label>
      <h3 class="text-black font-w400 text-center">
        {{$summary['nights-media']}}
      </h3>
    </div>
    <div class="sumary bordered">
      <label>Total Noches</label>
      <h3 class="text-black font-w400 text-center">{{$summary['nights']}}</h3>
    </div>
    <div class="sumary bordered">
      <label>Dias totales</label>
      @if(!in_array('t_day_1',$hide))
      <input class="form-control text-black font-w400 text-center seasonDays" value="{{$summary['daysTemp']}}" style="border: none; font-size: 32px;margin: 10px 0;color:red!important"/>
      @endif
      @if(!in_array('t_day_2',$hide))
      <h3 class="text-black font-w400 text-center">{{$summary['daysTemp']}}</h3>
      @endif
    </div>
  </div>
  <div class="col-lg-4 col-md-12">
    @if(isset($salesByUser))
    <table class="table">
      <tr>
        <th></th>
        @foreach($yearsLst as $year)
        <th>{{$year}}</th>
        @endforeach
      </tr>
      @foreach($salesByUser as $uid=>$data)
      <tr>
        <th><?php echo (isset($uIdName[$uid])) ? $uIdName[$uid] : 'Otros'; ?></th>
        @foreach($data as $year=>$val)
        <td>{{moneda($val)}}</td>
        @endforeach
      </tr>
      @endforeach
    </table>
    @endif
  </div>
</div>

