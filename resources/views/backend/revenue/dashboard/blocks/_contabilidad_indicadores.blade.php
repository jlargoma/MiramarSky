<style type="text/css">
  .bordered{
    padding: 15px;
    border:1px solid #e8e8e8;
    background: white;
  }
  .contabilidad .sumary{
    width: 50% !important;
    padding: 7px 0px !important;
    margin: 0 !important;
    text-align: center;
  }
  .contabilidad .sumary h4{
    font-size: 20px;
    font-weight: 800 !important;
    padding: 0px !important;
    margin: 0;
  }

</style>
<div class="row contabilidad">
  <div class="sumary bordered mobil_1">
    <label>Total Reservas</label>
    <h4 class="text-black font-w400 text-center">{{$summary['total']}}</h4>
  </div>
  <div class="sumary bordered mobil_1">
    <label>Nº Inquilinos</label>
    <h4 class="text-black font-w400 text-center">{{$summary['pax']}}</h4>
  </div>
  <div class="sumary bordered min">
    <label>Total Noches</label>
    <h4 class="text-black font-w400 text-center">{{$summary['nights']}}</h4>
  </div>
  <div class="sumary bordered min">
    <label>Estancia media</label>
    <h4 class="text-black font-w400 text-center">
      {{$summary['nights-media']}}
    </h4>
  </div>
  <div class="sumary bordered mobil_2">
    <label>RVAS</label>
    <h4 class="text-black font-w400 text-center">{{moneda($summary['total_pvp'])}}</h4>
  </div>
  <div class="sumary bordered mobil_2">
    <label>RTDO ESTIM x RVAS</label>
    <h4 class="text-black font-w400 text-center">{{moneda($summary['benef'])}}</h4>
  </div>
  <div class="sumary bordered mobil_1">
    <label>% benef reservas</label>
    <h4 class="text-black font-w400 text-center">{{round($summary['benef_inc'])}}%</h4>
  </div>
  <div class="sumary bordered mobil_1">
    <label>Días totales Temp.</label>
    <h4 class="text-black font-w400 text-center">{{$yDays}}</h4>
  </div>
  <div class="sumary bordered mobil_1">
    <label>Venta propia</label>
    <h4 class="text-black font-w400 text-center">{{$summary['vta_prop']}}%</h4>
  </div>
  <div class="sumary bordered min">
    <label>Venta agencia</label>
    <h4 class="text-black font-w400 text-center">{{$summary['vta_agency']}}%</h4>
  </div>
</div>