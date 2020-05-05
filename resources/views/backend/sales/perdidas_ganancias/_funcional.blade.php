<div class="row tabla-funcional">
  <div class="col-md-4 ingresos">
    <table>
      <tr class="border">
        <th></th>
        <th>BASE IMP</th>
        <th colspan="2">IVA REPERCIDO</th>
        <th>TOTAL</th>
      </tr>
      <tr class="white border">
        <td >VTAS PARA <br> PROPIETARIOS</td>
        <td >{{moneda($tPayProp)}}</td>
        <td >0%</td>
        <td >0 €</td>
        <td >{{moneda($tPayProp)}}</td>
      </tr>
      <tr class="white border">
        <td >VTAS <br>INTERM INMOB</td>
        <td >{{moneda($ing_baseImp)}}</td>
        <td >21%</td>
        <td >{{moneda($ing_iva)}}</td>
        <td >{{moneda($ingr_reservas)}}</td>
      </tr>
      <tr>
        <th></th>
        <th colspan="3">TOTAL VENTAS ALOJAMIENTO</th>
        <th>{{moneda($lstT_ing['ventas'])}}</th>
      </tr>
    </table>
    <h5>DESGLOSE DE LOS INGRESOS</h5>
    <table>
      <tr class="border">
        <th></th>
        <th>BASE IMP</th>
        <th colspan="2">IVA REPERCIDO</th>
        <th>TOTAL</th>
      </tr>
      <tr class="border">
        <td >VTAS <br> ALOJAMIENTO</td>
        <td >{{moneda($vtas_alojamiento_base)}}</td>
        <td >21%</td>
        <td >{{moneda($vtas_alojamiento_iva)}}</td>
        <td >{{moneda($vtas_alojamiento)}}</td>
      </tr>
      <tr class="border">
        <td >VTAS <br>  FORFAITS / CLASES</td>
        <td >{{moneda($ing_ff_baseImp)}}</td>
        <td >10%</td>
        <td >{{moneda($ing_ff_iva)}}</td>
        <td >{{moneda($ing_ff_baseImp+$ing_ff_iva)}}</td>
      </tr>
      <tr class="border">
        <td >OTROS INGRESOS</td>
        <td >{{moneda($otros_ingr_base)}}</td>
        <td >21%</td>
        <td >{{moneda($otros_ingr_iva)}}</td>
        <td >{{moneda($otros_ingr)}}</td>
      </tr>
      <tr class="border">
        <th>Total</th>
        <th>{{moneda($t_ingrTabl_base)}}</th>
        <th colspan="2">{{moneda($t_ingrTabl_iva)}}</th>
        <th>{{moneda($t_ingrTabl_base+$t_ingrTabl_iva)}}</th>
      </tr>
    </table>
  </div>
  <div class="col-md-4 gasto">
    <h5>DESGLOSE GASTOS OPERATIVOS</h5>
    <table>
      <tr class="border">
        <th></th>
        <th>BASE IMP</th>
        <th colspan="2">IVA REPERCIDO</th>
        <th>TOTAL</th>
      </tr>
      <tr class="border">
        <td >TOTAL <br>PAGO PROPIETARIOS</td>
        <td >{{moneda($tPayProp)}}</td>
        <td >0%</td>
        <td >0 €</td>
        <td >{{moneda($tPayProp)}}</td>
      </tr>
      <tr class="border">
        <td >PROVEEDOR <br> EXCURSIONES</td>
        <td >{{moneda($gasto_ff_baseImp)}}</td>
        <td >10%</td>
        <td >{{moneda($gasto_ff_iva)}}</td>
        <td >{{moneda($gasto_ff_baseImp+$gasto_ff_iva)}}</td>
      </tr>
      <tr class="border">
        <td >GASTOS OPERATIVOS</td>
         <td >{{moneda($gasto_operativo_baseImp)}}</td>
        <td >21%</td>
        <td >{{moneda($gasto_operativo_iva)}}</td>
        <td >{{moneda($gasto_operativo_baseImp+$gasto_operativo_iva)}}</td>
      </tr>
      <tr class="border">
        <th>Total</th>
         <th>{{moneda($t_gastoTabl_base)}}</th>
        <th colspan="2">{{moneda($t_gastoTabl_iva)}}</th>
        <th>{{moneda($t_gastoTabl_base+$t_gastoTabl_iva)}}</th>
      </tr>
    </table>
  </div>
  <div class="col-md-4 resultado">
   <h5>RESULTADO OPERATIVO BRUTO</h5>
    <table>
      <tr class="border">
        <th></th>
        <th>BASE IMP</th>
        <th>IVA</th>
        <th>TOTAL</th>
      </tr>
      <tr class="border">
        <td >INGRESOS</td>
        <td >{{moneda($t_ingrTabl_base)}}</td>
        <td >{{moneda($t_ingrTabl_iva)}}</td>
        <td >{{moneda($t_ingrTabl_base+$t_ingrTabl_iva)}}</td>
      </tr>
      <tr class="border">
        <td >GASTOS</td>
        <td >{{moneda($t_gastoTabl_base)}}</td>
        <td >{{moneda($t_gastoTabl_iva)}}</td>
        <td >{{moneda($t_gastoTabl_base+$t_gastoTabl_iva)}}</td>
     <tr class="border">
        <th>Total</th>
        <th>{{moneda($t_ingrTabl_base-$t_gastoTabl_base)}}</th>
        <th>{{moneda($t_ingrTabl_iva-$t_gastoTabl_iva)}}</th>
        <th>{{moneda( ($t_ingrTabl_base+$t_ingrTabl_iva) - ($t_gastoTabl_base+$t_gastoTabl_iva) )}}</th>
      </tr>
    </table>
  </div>
</div>

@if(isset($repartoTemp_fix))
<div class="row tabla-funcional-resultados">
  <div class="">
    <table>
      <thead>
        <tr>
          <th colspan="2">REPARTO DE BENEFICIOS TEMPORADA</th>
          <td class="empty"></td>
          <th colspan="2">REPARTO DE BENEFICIOS TEMPORADA</th>
        </tr>
      </thead>
      <tr>
        <td>VENTA INTERM INMOB</td>
        <td>{{moneda($ingr_reservas)}}</td>
        <td class="empty"></td>
        <td>VENTA INTERM INMOB</td>
        <td>{{moneda($ingr_reservas)}}</td>
      </tr>
      <tr>
        <td>OTROS INGRESOS</td>
        <td>{{moneda($otros_ingr)}}</td>
        <td class="empty"></td>
        <td>OTROS INGRESOS</td>
        <td>{{moneda($otros_ingr)}}</td>
      </tr>
      <tr>
        <td>GASTOS OPERATIVOS</td>
        <td>{{moneda($gasto_operativo_baseImp+$gasto_operativo_iva)}}</td>
        <td class="empty"></td>
        <td>GASTOS OPERATIVOS</td>
        <td>{{moneda($gasto_operativo_baseImp+$gasto_operativo_iva)}}</td>
      </tr>
      <tr>
        <td>IVA A PAGAR</td>
        <td>{{moneda($repartoTemp_fix_iva1)}}</td>
        <td class="empty"></td>
        <td>IVA A PAGAR</td>
        <td>{{moneda($repartoTemp_fix_iva2)}}</td>
      </tr>
      <tr>
        <th>DIV A REPARTIR</th>
        <th>{{moneda($repartoTemp_fix-$repartoTemp_fix_iva1)}}</th>
        <td class="empty"></td>
        <th>DIV A REPARTIR</th>
        <th>{{moneda($repartoTemp_fix-$repartoTemp_fix_iva2)}}</th>
      </tr>
      <tr><td class="empty" colspan="5"></td></tr>
      <tr>
        <th>JAIME</th>
        <th>{{moneda($repartoTemp_jaime1)}}</th>
        <td class="empty imputs"><input type="text" id="benefJaime" value="{{$benefJaime}}" class="">%</td>
        <th>JAIME</th>
        <th>{{moneda($repartoTemp_jaime2)}}</th>
      </tr>
      <tr>
        <th>JORGE</th>
        <th>{{moneda($repartoTemp_jorge1)}}</th>
        <td class="empty imputs"><input type="text" id="benefJorge" value="{{$benefJorge}}">%</td>
        <th>JORGE</th>
        <th>{{moneda($repartoTemp_jorge2)}}</th>
      </tr>
      <tr>
        <th>IVA A PAGAR</th>
        <th>{{moneda($repartoTemp_fix_iva1)}}</th>
        <td class="empty"></td>
        <th>IVA A PAGAR</th>
        <th>{{moneda($repartoTemp_fix_iva2)}}</th>
      </tr>
      <tr>
        <td class="empty"></td>
        <th>{{moneda($repartoTemp_jaime1+$repartoTemp_jorge1+$repartoTemp_fix_iva1)}}</th>
        <td class="empty"></td>
        <td class="empty"></td>
        <th>{{moneda($repartoTemp_jaime2+$repartoTemp_jorge2+$repartoTemp_fix_iva2)}}</th>
      </tr>
    </table>
    
    
    
  </div>
</div>
@endif

<style>
  .tabla-funcional table {
    width: 100%;
    color: #fff;
  }
  .tabla-funcional .ingresos table {
    /*background-color: #c2ffc2;*/
    background-color: #349634;
  }
  .tabla-funcional .gasto table {
    background-color: #a94441;
  }
  
  .tabla-funcional .resultado table {
    background-color: #00b0f0;
  }
  
  .tabla-funcional table th,
  .tabla-funcional table td {
    height: auto !important;
    white-space: nowrap;
    padding: 6px 2px !important;
    text-align: center;
  }
  .tabla-funcional table td:first-child {
    font-size: 11px;
    text-align: left;
    padding-left: 8px !important;
    padding-right: 0px !important;
  }

  .tabla-funcional td hr{
    margin: 0;
  }
  .tabla-funcional table,
  .tabla-funcional .border td,
  .tabla-funcional .border th{
    border: 1px solid #000;
    
  }
  .tabla-funcional .gasto,
  .tabla-funcional .ingresos{
    padding: 8px;
    color: #fff;
  }
  
  
  tr.white td{
    background-color: #fff;
    color: #000;
  }
  
  .tabla-funcional-resultados table{
    width: 80%;
    margin: 1em auto;
  }
  .tabla-funcional-resultados table tr th,
  .tabla-funcional-resultados table tr td{
    border: 1px solid #000;
    padding: 5px 10px;
  }
  .tabla-funcional-resultados table tr td.empty{
    border: none;
  }
  
  .tabla-funcional-resultados thead th{
    background-color: #2b5d9b;
    font-size: 1.2em;
    color: #fff;
  }
  td.empty.imputs {
    width: 90px;
        background-color: #e8e8e8;
  }

  input#benefJaime,
  input#benefJorge {
    width: 50px;
    text-align: right;
    padding: 0;
    margin: 0;
    padding-right: 5px;
    border: none;
    background-color: #e8e8e8;
    cursor: pointer;
  }

  </style>