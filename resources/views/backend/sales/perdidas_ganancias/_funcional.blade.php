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
        <td >VTAS ALOJAMIENTO</td>
        <td >{{moneda($ing_baseImp)}}</td>
        <td ><input value="{{$ivas['ing_iva']}}" min="0" max="22" data-k="ing_iva" class="updIVA">%</td>
        <td >{{moneda($ing_iva)}}</td>
        <td >{{moneda($ingr_reservas)}}</td>
      </tr>
      <tr class="border">
        <td >VTAS FORFAITS </td>
        <td >{{moneda($_ff_FFExpress_baseImp)}}</td>
        <td ><input value="{{$ivas['ff_FFExpress']}}" min="0" max="22" data-k="ff_FFExpress" class="updIVA">%</td>
        <td >{{moneda($_ff_FFExpress_iva)}}</td>
        <td >{{moneda($_ff_FFExpress_iva+$_ff_FFExpress_baseImp)}}</td>
      </tr>
      <tr class="border">
        <td >VTAS CLASES/OTROS</td>
        <td >{{moneda($_ff_ClassesMat_baseImp)}}</td>
        <td ><input value="{{$ivas['ff_ClassesMat']}}" min="0" max="22" data-k="ff_ClassesMat" class="updIVA">%</td>
        <td >{{moneda($_ff_ClassesMat_iva)}}</td>
        <td >{{moneda($_ff_ClassesMat_iva+$_ff_ClassesMat_baseImp)}}</td>
      </tr>
      <tr class="border">
        <td >OTROS INGRESOS</td>
        <td >{{moneda($otros_ingr_base)}}</td>
        <td ><input value="{{$ivas['otros_ingr']}}" min="0" max="22" data-k="otros_ingr" class="updIVA">%</td>
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
        <th colspan="2">IVA SOPORTADO</th>
        <th>TOTAL</th>
      </tr>
      <tr class="border">
        <td >TOTAL PAGO PROPIETARIOS</td>
        <td >{{moneda($tPayProp)}}</td>
        <td >0%</td>
        <td >0 €</td>
        <td >{{moneda($tPayProp)}}</td>
      </tr>
     
      <tr class="border">
        <td >PROV FORFAITS </td>
        <td >{{moneda($_ff_prov_baseImp)}}</td>
        <td ><input value="{{$ivas['ff_FFExpress_expense']}}" min="0" max="22" data-k="ff_FFExpress_expense" class="updIVA">%</td>
        <td >{{moneda($_ff_prov_iva)}}</td>
        <td >{{moneda($_ff_prov_baseImp+$_ff_prov_iva)}}</td>
      </tr>
      <tr class="border">
        <td >PROV CLASES/OTROS</td>
        <td >{{moneda($_ff_mat_iva)}}</td>
        <td ><input value="{{$ivas['ff_ClassesMat_exp']}}" min="0" max="22" data-k="ff_ClassesMat_exp" class="updIVA">%</td>
        <td >{{moneda($_ff_mat_baseImp)}}</td>
        <td >{{moneda($_ff_mat_baseImp+$_ff_mat_iva)}}</td>
      </tr>
      <tr class="border">
        <td >GASTOS OPERATIVOS</td>
         <td >{{moneda($gasto_operativo_baseImp)}}</td>
        <td ><input value="{{$ivas['gasto_operativo']}}" min="0" max="22" data-k="gasto_operativo" class="updIVA">%</td>
        <td >{{moneda($gasto_operativo_iva)}}</td>
        <td >{{moneda($tGastos_operativos)}}</td>
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
<div class="row tabla-funcional">
  <div class="col-md-4 ingresos">
    <h5>TOTAL VENTAS POR ALOJAMIENTO</h5>
     <table>
      <tr class="border">
        <th></th>
        <th>BASE IMP</th>
        <th colspan="2">IVA REPERCIDO</th>
        <th>TOTAL</th>
      </tr>
      <tr class="white border">
        <td >VTAS PARA PROPIETARIOS (Pagos a Prop.)</td>
        <td >{{moneda($tPayProp)}}</td>
        <td >0%</td>
        <td >0 €</td>
        <td >{{moneda($tPayProp)}}</td>
      </tr>
      <tr class="white border">
        <td >VTAS INTERM INMOB</td>
        <td >{{moneda($ing_baseImp)}}</td>
        <td ><input value="{{$ivas['ing_iva']}}" min="0" max="22" data-k="ing_iva" class="updIVA">%</td>
        <td >{{moneda($ing_iva)}}</td>
        <td >{{moneda($ingr_reservas)}}</td>
      </tr>
      <tr>
        <th></th>
        <th colspan="3">TOTAL VENTAS ALOJAMIENTO</th>
        <th>{{moneda($lstT_ing['ventas'])}}</th>
      </tr>
    </table>
  </div>
  @if(isset($repartoTemp_fix))
  <div class="col-md-4 resultado">
    <h5>IVA</h5>
    <table>
      <tr class="border">
        <th class="text-left">IVA REPERCUTIDO</th>
        <td>{{moneda($t_ingrTabl_iva)}}</td>
      </tr>
      <tr class="border">
        <th class="text-left">IVA SOPORTADO</th>
        <td>{{moneda($iva_soportado)}}</td>
      </tr>
      <tr class="border">
        <th class="text-left">ARQUEO IVA</th>
        <td>
          <input type="text" id="ivaTemp" value="{{$ivaTemp}}">
          <span>€</span>
        </td>
      </tr>
      <tr class="border">
        <th class="text-left">IVA A PAGAR</th>
        <th>{{moneda($iva_soportado+$t_ingrTabl_iva+$ivaTemp)}}</th>
      </tr>
    </table>
    <span id="message_iva"></span>
   </div>
   <div class="col-md-4">
  
  <button class="btn btn-primary btn-reparto" type="button" data-toggle="modal" data-target="#modalRepartoBenefTemp">
    <i class="fa fa-eye"></i> Ver resultado de la temporada
  </button>
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
  </style>