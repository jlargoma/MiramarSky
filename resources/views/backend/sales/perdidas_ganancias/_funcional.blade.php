<div class="row tabla-funcional">
  <div class="col-md-4 ingresos">
   
    <h5>DESGLOSE DE LOS INGRESOS</h5>
    <table>
      <tr class="border">
        <th></th>
        <th>BASE IMP</th>
        <th colspan="2">IVA REPERCIDO</th>
        <th>TOTAL</th>
      </tr>
      <tr class="border">
        <td >TOTAL VENTAS ALOJAMIENTO</td>
        <td >{{moneda($ing_baseImp+$ingr_VtaProp)}}</td>
        <td >{{$ivas['ing_iva']}}%</td>
        <td >{{moneda($ing_iva)}}</td>
        <td >{{moneda($ingr_reservas)}}</td>
      </tr>
      <tr class="white border">
        <td >VTAS PARA PROPIETARIOS (Pagos a Prop.)</td>
        <td >{{moneda($ingr_VtaProp)}}</td>
        <td >0%</td>
        <td >0 €</td>
        <td >{{moneda($ingr_VtaProp)}}</td>
      </tr>
      <tr class="white border">
        <td >VTAS INTERM INMOB</td>
        <td >{{moneda($ing_baseImp)}}</td>
        <td >{{$ivas['ing_iva']}}%</td>
        <td >{{moneda($inr_VtasINTERM-$ing_baseImp)}}</td>
        <td >{{moneda($inr_VtasINTERM)}}</td>
      </tr>
      <tr class="border">
        <td >VTAS FORFAITS </td>
        <td >{{moneda($_ff_FFExpress_baseImp)}}</td>
        <td >{{$ivas['ff_FFExpress']}}%</td>
        <td >{{moneda($_ff_FFExpress_iva)}}</td>
        <td >{{moneda($_ff_FFExpress_iva+$_ff_FFExpress_baseImp)}}</td>
      </tr>
      <tr class="border">
        <td >VTAS CLASES/OTROS</td>
        <td >{{moneda($_ff_ClassesMat_baseImp)}}</td>
        <td >{{$ivas['ff_ClassesMat']}}%</td>
        <td >{{moneda($_ff_ClassesMat_iva)}}</td>
        <td >{{moneda($_ff_ClassesMat_iva+$_ff_ClassesMat_baseImp)}}</td>
      </tr>
      <tr class="border">
        <td >OTROS INGRESOS</td>
        <td >{{moneda($otros_ingr_base)}}</td>
        <td >{{$ivas['ff_ClassesMat']}}%</td>
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
  <div class="col-md-8">
    <div class="col-md-6 gasto">
      <h5>DESGLOSE GASTOS OPERATIVOS</h5>
      <table>
        <tr class="border">
          <th></th>
          <th>BASE IMP</th>
          <th colspan="2">IVA SOPORTADO</th>
          <th>TOTAL</th>
        </tr>
        <tr class="border">
          <td >TOTAL PAGO PROPIETARIOS <br/><smal>(sólo lo pagado)</smal></td>
          <td >{{moneda($tPayProp)}}</td>
          <td >0%</td>
          <td >0 €</td>
          <td >{{moneda($tPayProp)}}</td>
        </tr>
      
        <tr class="border">
          <td >PROV FORFAITS </td>
          <td >{{moneda($_ff_prov_baseImp)}}</td>
          <td >{{$ivas['ff_FFExpress_expense']}}%</td>
          <td >{{moneda($_ff_prov_iva)}}</td>
          <td >{{moneda($_ff_prov_baseImp+$_ff_prov_iva)}}</td>
        </tr>
        <tr class="border">
          <td >PROV CLASES/OTROS</td>
          <td >{{moneda($_ff_mat_iva)}}</td>
          <td >{{$ivas['ff_ClassesMat_exp']}}%</td>
          <td >{{moneda($_ff_mat_baseImp)}}</td>
          <td >{{moneda($_ff_mat_baseImp+$_ff_mat_iva)}}</td>
        </tr>
        <tr class="border">
          <td >GASTOS OPERATIVOS  
            <i 
            class="fa fa-question-circle" data-container="body" data-toggle="popover" data-placement="bottom" 
            data-content="agencias, amenities, comision tpv, lavanderia, limpieza y mantenimiento"></i>
          </td>
          <td >{{moneda($gasto_operativo_baseImp)}}</td>
          <td >{{$ivas['gasto_operativo']}}%</td>
          <td ><input type="text"  class="saveIVA" data-k="gasto_operativo_iva" value="{{$gasto_operativo_iva}}"><span>€</span></td>
          <td >{{moneda($tGastos_operativos)}}</td>
        </tr>
        <tr class="border">
          <td >GASTOS OTROS
          <i class="fa fa-question-circle" data-container="body" data-toggle="popover" data-placement="bottom" 
            data-content="{{implode(', ',$otherExpensesText)}}"></i>
          </td>
          <td >{{moneda($otherExpenses-$iva_otherExpenses)}}</td>
          <td >--</td>
          <td ><input type="text" class="saveIVA" data-k="iva_otherExpenses" value="{{$iva_otherExpenses}}"><span>€</span></td>
          <td >{{moneda($otherExpenses)}}</td>
        </tr>
        <tr class="border">
          <th>Total</th>
          <th>{{moneda($totalGasto-$t_gastoTabl_iva)}}</th>
          <th colspan="2">{{moneda($t_gastoTabl_iva)}}</th>
          <th>{{moneda($totalGasto)}}</th>
        </tr>
      </table>
    </div>
    <div class="col-md-6 resultado">
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
          <td >{{moneda($totalGasto-$ivaSoportado)}}</td>
          <td >{{moneda($ivaSoportado)}}</td>
          <td >{{moneda($totalGasto)}}</td>
        </tr>
        <tr class="border">
          <td >ARQUEO</td>
          <td ></td>
          <td >{{moneda($ivaTemp)}}</td>
          <td >-</td>
        </tr>
      <?php 
      $subtotal = ($t_ingrTabl_base+$t_ingrTabl_iva)-$totalGasto;
      ?>
      <tr class="border">
          <th>SubTotal</th>
          <th>{{moneda($subtotal-$t_iva)}}</th>
          <th>{{moneda($t_iva)}}</th>
          <th>{{moneda($subtotal)}}</th>
        </tr>
        <?php 
      $gastoPendiente = array_sum($aExpensesPending);
      ?>
      <tr class="border">
          <th>Gasto Pend.</th>
          <th></th>
          <th></th>
          <th>{{moneda($gastoPendiente)}}</th>
        </tr>
      <tr class="border">
          <th>Total</th>
          <th></th>
          <th></th>
          <th>{{moneda($subtotal + $gastoPendiente)}}</th>
        </tr>
      </table>
    </div>
    <div class="col-md-12">&nbsp;</div>
    @if(isset($repartoTemp_fix))
    <div class="col-md-6 resultado">
      <h5>IVA</h5>
      <table>
        <tr class="border">
          <th class="text-left">IVA REPERCUTIDO</th>
          <td>{{moneda($t_ingrTabl_iva)}}</td>
        </tr>
        <tr class="border">
          <th class="text-left">IVA SOPORTADO</th>
          <td> <input type="text"  class="saveIVA" data-k="ivaSoportado" value="{{$ivaSoportado}}">
            <span>€</span>
          </td>
        </tr>
        <tr class="border">
          <th class="text-left">ARQUEO IVA</th>
          <td>
            <input type="text" class="saveIVA" data-k="ivaTemp" value="{{$ivaTemp}}">
            <span>€</span>
          </td>
        </tr>
        <tr class="border">
          <th class="text-left">IVA A PAGAR</th>
          <th>{{moneda($t_ingrTabl_iva-$ivaSoportado+$ivaTemp)}}</th>
        </tr>
      </table>
      <span id="message_iva"></span>
    </div>
    <div class="col-md-6">
    
    <button class="btn btn-primary btn-reparto" type="button" data-toggle="modal" data-target="#modalRepartoBenefTemp">
      <i class="fa fa-eye"></i> Ver resultado de la temporada
    </button>
    </div>
  </div>
  @endif
</div>
@if(isset($repartoTemp_fix))
@include('backend.sales.perdidas_ganancias.__repartoBenefTemp')
@endif

<style>
  .tabla-funcional table  {
    width: 100%;
    color: #fff;
  }
  .tabla-funcional .ingresos table,.iva-2 {
    /*background-color: #c2ffc2;*/
    background-color: #349634;
  }
  .tabla-funcional .gasto table,.iva-1,.iva-3 {
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
    border: 1px solid #afafaf;
    
  }
  .tabla-funcional .gasto,
  .tabla-funcional .resultado,
  .tabla-funcional .ingresos{
    padding: 8px;
    color: #fff;
  }
  
  
  tr.white td{
    background-color: #fff;
    color: #000;
  }
  
  .tabla-funcional-resultados table,
  .modal-body .row.tabla-funcional{
    width: 80%;
    margin: 1em auto;
  }
  .tabla-funcional-resultados table tr th,
  .tabla-funcional-resultados table tr td{
    border: 1px solid #afafaf;
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
  
  .iva-1 ,.iva-2{
    color: #fff;
    
  }
  
  

  .iva-1 .col-xs-7,
  .iva-1 .col-xs-5,
  .iva-2 .col-xs-7,
  .iva-2 .col-xs-5,
  .iva-3
  {
    border: 1px solid #afafaf;
    padding: 5px 15px;
    height: 3em;
    line-height: 2;
  }
  
  .iva-3{
    height: 6em;
    text-align: center;
   
  }
  #resultIVA_modif{
     font-size: 1.5em;
    font-weight: 800;
    color: #fff;
    line-height: 3;
  }
  
    .iva-1 .col-xs-5 input,
    .iva-2 .col-xs-5 input{
      background-color: transparent;
      border: none;
      width: 84%;
      text-align: right;
      padding-right: 0;
    }

    .iva-1 span,
    .iva-2 span{
      float: right;
      width: 15%;
    }
    button.btn.btn-primary.btn-reparto {
        margin-top: 4em;
        margin-left: 1em;
    }
    input.saveIVA{
      background: transparent;
      text-align: right;
      padding: 0 6px;
      width: 85px;
      border: none;
      border-bottom: 1px solid;
    }
  </style>