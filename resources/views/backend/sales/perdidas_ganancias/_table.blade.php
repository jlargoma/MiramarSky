<div class="table-responsive">
  <table class="table perdidas_ganancias" id="tableItems">
    <thead>
      <tr>
        <th></th>
        <th>Total</th>
        <th class="light-blue text-center">Pendiente</th>
        @foreach($lstMonths as $month) <th class="text-center">{{$month['name']}}</th> @endforeach
      </tr>
    </thead>
    <tbody>
      <tr class="pyg_ingresos">
        <th>INGRESOS</th>
        <th class="text-center">{{moneda($totalIngr)}}</th>
        <th class="light-blue text-center">{{moneda($totalPendingIngr)}}</th>
        @foreach($tIngByMonth as $k=>$v)<th class="text-center"> {{moneda($v,false)}}</th> @endforeach
      </tr>
      @foreach($ingresos as $k=>$v)
      <tr>
        <td>{{$ingrType[$k]}}</td>
        <td class="text-center">{{moneda($lstT_ing[$k])}}</td>
        <td class="text-center">{{moneda($aIngrPending[$k])}}</td>
        @foreach($lstMonths as $k_month=>$month) 
        @if($k == 'ventas' || $k == 'ff')
          <td class="text-center" >
        @else
          <td class="text-center editable_ingr" data-key="{{$k}}" data-month="{{$k_month}}" data-val="{{moneda($ingresos[$k][$k_month],false)}}">
        @endif
          {{moneda($ingresos[$k][$k_month],false)}}
        </td> 
        @endforeach
      </tr>
      @endforeach
      
      <tr class="pyg_gastos">
        <th>GASTOS</th>
        <th class="text-center">{{moneda($totalGasto)}}</th>
        <th class="light-blue text-center">{{moneda($totalPendingGasto)}}</th>
        @foreach($tGastByMonth as $k=>$v)<th class="text-center"> {{moneda($v,false)}}</th> @endforeach
      </tr>
      @foreach($listGasto as $k=>$v)
      <tr>
        <td class="open_detail" data-key="{{$k}}">{{$gastoType[$k]}}</td>
        <td class="text-center">{{moneda($lstT_gast[$k])}}</td>
        @if($aExpensesPending[$k] === "N/A")
        <td class="text-center editable" data-current="0" data-key="{{$k}}" data-val="{{moneda($aExpensesPendingOrig[$k],false)}}">
          N/A
        </td>
        @else
        <td class="text-center editable" data-current="1" data-key="{{$k}}" data-val="{{moneda($aExpensesPendingOrig[$k],false)}}">
          {{moneda($aExpensesPending[$k],false)}}
        </td>
          @endif
        @foreach($lstMonths as $k_month=>$month) <td class="text-center">{{moneda($listGasto[$k][$k_month],false)}}</td> @endforeach
      </tr>
      @endforeach
      
      <tr class="pyg_beneficio">
        <th>EBITDA</th>
        <th class="text-center">{{moneda($totalIngr-$totalGasto)}}</th>
        <th class="light-blue text-center">{{moneda($totalIngr+$totalPendingIngr-$totalGasto-$totalPendingGasto)}}</th>
        @foreach($lstMonths as $k_month=>$v)<th class="text-center"> {{moneda(($tIngByMonth[$k_month]-$tGastByMonth[$k_month]))}}</th> @endforeach
      </tr>
      <tr>
        <td class="open_detail" data-key="iva">IVA A PAGAR</td>
        <td class="text-center editable_ingr" data-key="iva" data-month="" data-val="{{moneda($expenses_fix['iva'],false)}}">
          {{moneda($expenses_fix['iva'])}}
        </td>
      </tr>
      <tr>
        <td class="open_detail" data-key="impuestos">IMPUESTOS</td>
        <td class="text-center editable_ingr" data-key="impuestos" data-month="" data-val="{{moneda($expenses_fix['impuestos'],false)}}">
          {{moneda($expenses_fix['impuestos'])}}
        </td>
      </tr>
      <tr class="pyg_beneficio">
        <th>BENEFICIO OPERATIVO</th>
        <th class="text-center">{{moneda($totalIngr-$totalGasto-$tExpenses_fix)}}</th>
        <th class="light-blue text-center">{{moneda($ingr_bruto+$totalPendingIngr-$totalPendingGasto-$totalPendingImp)}}</th>
        @foreach($lstMonths as $k_month=>$v)<th class="text-center"> {{moneda(($tIngByMonth[$k_month]-$tGastByMonth[$k_month]))}}</th> @endforeach
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
    background-color: #2c5d9b;
  }
  .table.perdidas_ganancias tr.pyg_beneficio th{
    color: #fff;
  }
  .perdidas_ganancias .pendientes{
    background-color: #48b0f7;
  }
  
  </style>