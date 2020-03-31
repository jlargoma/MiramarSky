<div class="table-responsive">
  <table class="table perdidas_ganancias">
    <thead>
      <tr>
        <th></th>
        <th>Total</th>
        <th class="bg-info text-center">Pendiente</th>
        @foreach($lstMonths as $month) <th class="text-center">{{$month['name']}}</th> @endforeach
      </tr>
    </thead>
    <tbody>
      <tr class="pyg_ingresos">
        <th>INGRESOS</th>
        <th class="text-center">{{moneda($totalIngr)}}</th>
        <th class="bg-info text-center">{{moneda($totalPendingIngr)}}</th>
        @foreach($tIngByMonth as $k=>$v)<th class="text-center"> {{moneda($v,false)}}</th> @endforeach
      </tr>
      @foreach($ingresos as $k=>$v)
      <tr>
        <td>{{$ingrType[$k]}}</td>
        <td class="text-center">{{moneda($lstT_ing[$k])}}</td>
        <td class="text-center">{{moneda($aIngrPending[$k])}}</td>
        @foreach($lstMonths as $k_month=>$month) <td class="text-center">{{moneda($ingresos[$k][$k_month],false)}}</td> @endforeach
      </tr>
      @endforeach
      
      <tr class="pyg_gastos">
        <th>GASTOS</th>
        <th class="text-center">{{moneda($totalGasto)}}</th>
        <th class="bg-info text-center">{{moneda($totalPendingGasto)}}</th>
        @foreach($tGastByMonth as $k=>$v)<th class="text-center"> {{moneda($v,false)}}</th> @endforeach
      </tr>
      @foreach($listGasto as $k=>$v)
      <tr>
        <td>{{$gastoType[$k]}}</td>
        <td class="text-center">{{moneda($lstT_gast[$k])}}</td>
        <td class="text-center">{{moneda($aExpensesPending[$k])}}</td>
        @foreach($lstMonths as $k_month=>$month) <td class="text-center">{{moneda($listGasto[$k][$k_month],false)}}</td> @endforeach
      </tr>
      @endforeach
      
      <tr class="pyg_beneficio">
        <th>BENEFICIO BRUTO</th>
        <th class="text-center">{{moneda($totalIngr-$totalGasto)}}</th>
        <th class="bg-info text-center">{{moneda($totalIngr+$totalPendingIngr-$totalGasto-$totalPendingGasto)}}</th>
        @foreach($lstMonths as $k_month=>$v)<th class="text-center"> {{moneda(($tIngByMonth[$k_month]-$tGastByMonth[$k_month]),false)}}</th> @endforeach
      </tr>
      <tr>
        <td>IMPUESTOS</td>
        <td class="text-center">{{moneda($lstT_gast['impuestos'])}}</td>
        <td class="text-center">{{moneda($aExpensesPending['impuestos'])}}</td>
        @foreach($lstMonths as $k_month=>$month) <td class="text-center">{{moneda($impuestos[$k_month],false)}}</td> @endforeach
      </tr>
      <tr class="pyg_beneficio">
        <th>BENEF NETO</th>
        <th class="text-center">{{moneda($totalIngr-$totalGasto-$lstT_gast['impuestos'])}}</th>
        <th class="bg-info text-center">{{moneda($totalIngr+$totalPendingIngr-$totalGasto-$totalPendingGasto-$aExpensesPending['impuestos'])}}</th>
        @foreach($lstMonths as $k_month=>$v)<th class="text-center"> {{moneda(($tIngByMonth[$k_month]-$tGastByMonth[$k_month]-$impuestos[$k_month]),false)}}</th> @endforeach
      </tr>
    </tbody>
  </table>
</div>
<style>

  .perdidas_ganancias thead{
    background-color: #2b5d9b;
  }
  .table.perdidas_ganancias thead tr th{
    color: #fff;
  }
  
  .perdidas_ganancias .pyg_ingresos{
    background-color: #4ec37b;
  }
  .table.perdidas_ganancias tr.pyg_ingresos th{
    color: #fff;
  }
  .perdidas_ganancias .pyg_gastos{
    background-color: #a94441;
  }
  .table.perdidas_ganancias tr.pyg_gastos th{
    color: #fff;
  }
  .perdidas_ganancias .pyg_beneficio{
    background-color: #48b0f7;
  }
  .table.perdidas_ganancias tr.pyg_beneficio th{
    color: #fff;
  }
  .perdidas_ganancias .pendientes{
    background-color: #48b0f7;
  }
  
  </style>