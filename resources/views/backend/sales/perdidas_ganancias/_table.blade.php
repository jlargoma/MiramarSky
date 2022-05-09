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
        @foreach($oIngr->table->total as $k=>$v)<th class="text-center"> {{moneda($v,false)}}</th> @endforeach
      </tr>
      @foreach($oIngr->table as $k=>$v)
      <?php if ($k == 'total') continue; ?>
      <tr>
        <td>{{$oIngr->types->{$k} }}</td>
        <td class="text-center">{{moneda($oIngr->total->{$k})}}</td>
        <td class="text-center">{{moneda($oIngr->pending->{$k})}}</td>
        @foreach($lstMonths as $k_month=>$month)
        <td class="text-center">{{moneda($oIngr->table->{$k}[$k_month],false)}}</td>
        @endforeach
      </tr>
      @endforeach

      <tr class="pyg_gastos">
        <th>GASTOS</th>
        <th class="text-center">{{moneda($totalGasto)}}</th>
        <th class="light-blue text-center">{{moneda($totalPendingGasto)}}</th>
        @foreach($oGastos->table->total as $k=>$v)<th class="text-center"> {{moneda($v,false)}}</th> @endforeach
      </tr>
      @foreach($oGastos->table as $k=>$v)
      <?php if ($k == 'total') continue; ?>
      <tr>
        <td class="open_detail" data-key="{{$k}}">{{$oGastos->types->{$k} }}</td>
        <td class="text-center">{{moneda($oGastos->total->{$k})}}</td>
        @if($canEdit)
        @if($oGastos->pending->{$k} === "N/A")
        <td class="text-center editable" data-current="0" data-key="{{$k}}" data-val="{{moneda($oGastos->pendingOrig->{$k},false)}}">
          N/A
        </td>
        @else
        <td class="text-center editable" data-current="1" data-key="{{$k}}" data-val="{{moneda($oGastos->pendingOrig->{$k},false)}}">
          {{moneda($oGastos->pendingOrig->{$k},false)}}
        </td>
        @endif
        @else
        <td class="text-center"> {{moneda($oGastos->pending->{$k},false)}}</td>
        @endif

        @foreach($lstMonths as $k_month=>$month)
        <td class="text-center">{{moneda($oGastos->table->{$k}[$k_month],false)}}</td>
        @endforeach
      </tr>
      @endforeach


      <tr class="pyg_beneficio">
        <th>EBITDA</th>
        <th class="text-center">{{moneda($totalIngr-$totalGasto)}}</th>
        <th class="text-center"></th>
        @foreach($lstMonths as $k_month=>$v)
        <?php $aux = ($oIngr->table->total[$k_month] - $oGastos->table->total[$k_month]); ?>
        <th class="text-center <?php if ($aux < 0) echo 'result-danger'; ?> "> {{moneda($aux)}}</th>
        @endforeach
      </tr>
      <tr>
        <td class="open_detail" data-key="iva">IVA A PAGAR</td>
        <td class="text-center" data-key="iva">{{moneda($oExcel->iva->toPay)}}</td>
      </tr>
      <tr class="pyg_beneficio">
        <th>BENEFICIO OPERATIVO</th>
        <th class="text-center">{{moneda($totalIngr-$totalGasto-($oExcel->iva->impuestos+$oExcel->iva->toPay))}}</th>
        <th class="text-center"></th>
        @foreach($lstMonths as $k_month=>$v)
        <?php $aux = ($oIngr->table->total[$k_month] - $oGastos->table->total[$k_month]); ?>
        <th class="text-center <?php if ($aux < 0) echo 'result-danger'; ?> "> {{moneda($aux)}}</th>
        @endforeach
      </tr>
    </tbody>
  </table>
</div>
<style>
  .perdidas_ganancias thead {
    background-color: #2b5d9b;
  }

  .table.perdidas_ganancias thead tr th {
    color: #fff;
  }

  .perdidas_ganancias .pyg_ingresos {
    background-color: #4ec37b;
  }

  .table.perdidas_ganancias tr.pyg_ingresos th {
    color: #fff;
  }

  .perdidas_ganancias .pyg_gastos {
    background-color: #a94441;
  }

  .table.perdidas_ganancias tr.pyg_gastos th {
    color: #fff;
  }

  .perdidas_ganancias .pyg_beneficio {
    background-color: #2c5d9b;
  }

  .table.perdidas_ganancias tr.pyg_beneficio th {
    color: #fff;
  }

  .perdidas_ganancias .pendientes {
    background-color: #48b0f7;
  }

  .result-danger {
    background-color: #c1413d;
  }
</style>