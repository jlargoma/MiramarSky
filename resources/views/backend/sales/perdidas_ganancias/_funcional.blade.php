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
        <td >VTAS PARA PROPIETARIOS</td>
        <td >{{moneda($tPayProp)}}</td>
        <td >0%</td>
        <td >0 €</td>
        <td >{{moneda($tPayProp)}}</td>
      </tr>
      <tr class="white border">
        <td >VTAS INTERM INMOB</td>
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
  </div>
  @if(isset($repartoTemp_fix))
  <div class="col-md-4 ingresos">
    <h5>VARIABLES A MODIFICAR MANUALMENTE</h5>
    <div class="row">
      <div class="col-xs-7">
        <div class="row iva-1">
        <div class="col-xs-7">IVA SOPORTADO</div>
        <div class="col-xs-5">
          <input type="text" id="iva_soportado" value="{{$iva_soportado}}"> 
          <span>€</span>
        </div>
        </div>
        <div class="row iva-2">
        <div class="col-xs-7">ARREGLO JORGE</div>
        <div class="col-xs-5">
          <input type="text" id="iva_jorge" value="{{$iva_jorge}}">
          <span>€</span>
        </div>
        </div>
      </div>
      <div class="col-xs-5 iva-3" > <span id="resultIVA_modif">{{moneda($resultIVA_modif)}}</span></div>
    </div>
    <span id="message_iva"></span>
   </div>
   <div class="col-md-4">
  
  <button class="btn btn-primary btn-reparto" type="button" data-toggle="modal" data-target="#modalRepartoBenefTemp">
    <i class="fa fa-eye"></i>
  </button>
  </div>
  @endif
</div>
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
        <td >{{moneda($vtas_alojamiento_base)}}</td>
        <td >21%</td>
        <td >{{moneda($vtas_alojamiento_iva)}}</td>
        <td >{{moneda($vtas_alojamiento)}}</td>
      </tr>
      <tr class="border">
        <td >VTAS FORFAITS / CLASES</td>
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
        <td >PROVEEDOR EXCURSIONES</td>
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
  
  .tabla-funcional-resultados table{
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