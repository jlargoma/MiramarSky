<div class="row">
  <div class="col-md-8 col-xs-12">
    <h3>Resumen Gastos / Mes</h3>
    <div class=" table-responsive">
      <table class="table table-resumen">
        <thead>
          <tr class="resume-head">
            <th class="static">Concepto</th>
            <th class="first-col">Total</th>
            @foreach($lstMonths as $k => $month)
            <th>{{getMonthsSpanish($month['m'])}}</th>
            @endforeach
          </tr>
        </thead>
        <tbody>
          @foreach($gastos as $k=>$item)
          <tr>
            <td class="static">{{$gType[$k]}}</td>
            <?php $auxClass = ' class="first-col" '; ?>
            @foreach($item as $month=>$val)
            <td {{$auxClass}} >{{moneda($val,false)}}</td>
            <?php $auxClass = ''; ?>
            @endforeach
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    </div>
  <div class="col-md-4 col-xs-12">
      <div class="pieChart">
        <canvas id="chart_1"></canvas>
      </div>
    </div>
</div>
