<style type="text/css">
  .bordered{
    padding: 15px;
    border:1px solid #e8e8e8;
    background: white;
  }
  .sumary-pyg.bordered {
    width: 12%;
        float: left;
}
</style>

<div class="row">
  <div class="sumary-pyg bordered">
    <label>Total Reservas</label>
    <h3 class="text-black font-w400 text-center">{{$summary['total']}}</h3>
  </div>
  <div class="sumary-pyg bordered">
    <label>Nº Inquilinos</label>
    <h3 class="text-black font-w400 text-center">{{$summary['inquilinos']}}</h3>
  </div>

  <div class="sumary-pyg bordered">
    <label>Estancia media</label>
    <h3 class="text-black font-w400 text-center">{{round($summary['noches']/$summary['total'])}}</h3>
  </div>
  <div class="sumary-pyg bordered">
    <label>Total Noches</label>
    <h3 class="text-black font-w400 text-center">{{$summary['noches']}}</h3>
  </div>

  <div class="sumary-pyg bordered">
    <label>% benef reservas</label>
    <h3 class="text-black font-w400 text-center">{{($summary['benef'])}}%</h3>
  </div>
  <div class="sumary-pyg bordered">
    <label>Venta propia</label>
    <h3 class="text-black font-w400 text-center">{{$summary['vta_prop']}}%</h3>
  </div>
  <div class="sumary-pyg bordered">
    <label>Venta agencia</label>
    <h3 class="text-black font-w400 text-center">{{$summary['vta_agenda']}}%</h3>
  </div>
  <div class="sumary-pyg bordered">
    <label>Dias totales</label>
    <h3 class="text-black font-w400 text-center">{{$summary['daysTemp']}}</h3>
  </div>
</div>
