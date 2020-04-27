<div class="row tabla-funcional">
  <div class="col-md-9">
    <div class="row">
      <div class="col-md-4 ingresos">
        <h5>INGRESOS POR VENTAS DE RESERVAS</h5>
        <table>
          <tr>
            <td colspan="2">VTAS  x RESERVAS</td>
            <td >{{moneda($lstT_ing['ventas'])}}</td>
          </tr>
          <tr>
            <td colspan="2">PAGO A PROPIETARIOS</td>
            <td >{{moneda($lstT_gast['prop_pay'])}}</td>
          </tr>
          <tr ><td colspan="3"><hr></td></tr>
          <tr>
            <td colspan="2">ING. X  RESERVAS</td>
            <td >{{moneda($ingr_reservas)}}</td>
          </tr>
          <tr><td colspan="3"></td></tr>
          <tr class="border">
            <td >BASE IMP</td>
            <td >IVA REPERC</td>
            <td >TOTAL</td>
          </tr>
          <tr class="border">
            <td >{{moneda($ing_baseImp)}}</td>
            <td >{{moneda($ing_iva)}}</td>
            <td >{{moneda($ingr_reservas)}}</td>
          </tr>
        </table>
      </div>
      <div class="col-md-4 ingresos">
        <h5>INGRESOS POR VENTAS DE FORFAITS</h5>
        <table>
          <tr>
            <td colspan="2">VTAS  FORFAITS /CLASES</td>
            <td >{{moneda($lstT_ing['ff'])}}</td>
          </tr>
          <tr>
            <td colspan="2">PAGO A PROVEEDORES</td>
            <td >{{moneda($lstT_gast['excursion'] + floatval($aExpensesPending['excursion']))}}</td>
          </tr>
          <tr ><td colspan="3"><hr></td></tr>
          <tr>
            <td colspan="2">ING. X  COMISIONES</td>
            <td >{{moneda($lstT_ing['rappel_forfaits'])}}</td>
          </tr>
          <tr><td colspan="3"></td></tr>
          <tr class="border">
            <td >BASE IMP</td>
            <td >IVA REPERC</td>
            <td >TOTAL</td>
          </tr>
          <tr class="border">
            <td >{{moneda($ing_ff_baseImp)}}</td>
            <td >{{moneda($ing_ff_iva)}}</td>
            <td >{{moneda($ing_ff_baseImp+$ing_ff_iva)}}</td>
          </tr>
          <tr class="border">
            <td >{{moneda($ing_comision_baseImp)}}</td>
            <td >{{moneda($ing_comision_iva)}}</td>
            <td >{{moneda($ing_comision_baseImp+$ing_comision_iva)}}</td>
          </tr>
        </table>
      </div>
      <div class="col-md-4 ingresos">
        <h5>TOTAL INGRESOS TEMPORADA</h5>
        <table>
          <tr class="border">
            <td ></td>
            <td >BASE IMP</td>
            <td >IVA REPERC</td>
            <td >TOTAL</td>
          </tr>
          <tr class="border">
            <td>ING. X  RESERVAS</td>
            <td >{{moneda($ing_baseImp)}}</td>
            <td >{{moneda($ing_iva)}}</td>
            <td >{{moneda($ingr_reservas)}}</td>
          </tr>
          <tr class="border">
            <td>VTAS  FORFAITS /CLASES</td>
            <td >{{moneda($ing_ff_baseImp)}}</td>
            <td >{{moneda($ing_ff_iva)}}</td>
            <td >{{moneda($ing_ff_baseImp+$ing_ff_iva)}}</td>
          </tr>
          <tr class="border">
            <td>ING. X  COMISIONES</td>
            <td >{{moneda($ing_comision_baseImp)}}</td>
            <td >{{moneda($ing_comision_iva)}}</td>
            <td >{{moneda($ing_comision_baseImp+$ing_comision_iva)}}</td>
          </tr>
          <tr class="border">
            <td>Total</td>
            <td >{{moneda($tIngr_base)}}</td>
            <td >{{moneda($tIngr_imp)}}</td>
            <td >{{moneda($tIngr_base+$tIngr_imp)}}</td>
          </tr>
        </table>
      </div>
    </div>
    
    <div class="row">
      <div class="col-md-4 gasto">
        <h5>PAGO A PROPIETARIOS</h5>
        <table>
          <tr>
            <td colspan="2">TOTAL PAGO PROP</td>
            <td >{{moneda($tPayProp)}}</td>
          </tr>
          <tr class="border">
            <td >BASE IMP</td>
            <td >IVA REPERC</td>
            <td >TOTAL</td>
          </tr>
          <tr class="border">
            <td >{{moneda($tPayProp)}}</td>
            <td >--</td>
            <td >{{moneda($tPayProp)}}</td>
          </tr>
        </table>
      </div>
      <div class="col-md-4 gasto">
        <h5>TOTAL GASTOS PROV FORFAITS/CLASES</h5>
        <table>
          <tr>
            <td colspan="2">TOTAL PROV FF/CLASES</td>
            <td >{{moneda($lstT_gast['excursion'])}}</td>
          </tr>
          <tr class="border">
            <td >BASE IMP</td>
            <td >IVA REPERC</td>
            <td >TOTAL</td>
          </tr>
          <tr class="border">
            <td >{{moneda($gasto_ff_baseImp)}}</td>
            <td >{{moneda($gasto_ff_iva)}}</td>
            <td >{{moneda($gasto_ff_baseImp+$gasto_ff_iva)}}</td>
          </tr>
        </table>
      </div>
      <div class="col-md-4 gasto">
        <h5>TOTAL GASTOS TEMPORADA</h5>
        <table>
          <tr class="border">
            <td ></td>
            <td >BASE IMP</td>
            <td >IVA REPERC</td>
            <td >TOTAL</td>
          </tr>
          <tr class="border">
            <td>TOTAL GASTOS PROV <br/> FORFAITS/CLASES</td>
            <td >{{moneda($gasto_ff_baseImp)}}</td>
            <td >{{moneda($gasto_ff_iva)}}</td>
            <td >{{moneda($gasto_ff_baseImp+$gasto_ff_iva)}}</td>
          </tr>
          <tr class="border">
            <td>GASTOS OPERATIVOS</td>
            <td >{{moneda($gasto_operativo_baseImp)}}</td>
            <td >{{moneda($gasto_operativo_iva)}}</td>
            <td >{{moneda($gasto_operativo_baseImp+$gasto_operativo_iva)}}</td>
          </tr>
          <tr class="border">
            <td>Total</td>
            <td >{{moneda($tGastos_base)}}</td>
            <td >{{moneda($tGastos_imp)}}</td>
            <td >{{moneda($tGastos_base+$tGastos_imp)}}</td>
          </tr>
        </table>
      </div>
    </div>
  </div>
  <div class="col-md-3 resultado">
    <h5>RESULTADO OPERATIVO BRUTO</h5>
    <table>
      <tr class="border">
        <td ></td>
        <td >BASE IMP</td>
        <td >IVA REPERC</td>
        <td >TOTAL</td>
      </tr>
      <tr class="border">
        <td>INGRESOS</td>
        <td >{{moneda($tIngr_base)}}</td>
        <td >{{moneda($tIngr_imp)}}</td>
        <td >{{moneda($tIngr_base+$tIngr_imp)}}</td>
      </tr>
      <tr class="border">
        <td>GASTOS</td>
        <td >{{moneda($tGastos_base)}}</td>
        <td >{{moneda($tGastos_imp)}}</td>
        <td >{{moneda($tGastos_base+$tGastos_imp)}}</td>
      </tr>
      <tr class="border">
        <td>Total</td>
        <td >{{moneda($tIngr_base-$tGastos_base)}}</td>
        <td >{{moneda($tIngr_imp-$tGastos_imp)}}</td>
        <td >{{moneda($tIngr_base+$tIngr_imp - ($tGastos_base+$tGastos_imp))}}</td>
      </tr>
    </table>
  </div>
</div>

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
  .tabla-funcional .border td{
    border: 1px solid #000;
    
  }
  .tabla-funcional .gasto,
  .tabla-funcional .ingresos{
    padding: 8px;
    color: #fff;
  }
  
  
  
  

  </style>