<?php
function printTableIva($type, $obj)
{
?>
  <td>{{moneda($obj->base[$type])}}</td>
  <td><?= ($obj->iva[$type] > 0) ? $obj->iva[$type] . '%' : '--' ?></td>
  <td>{{moneda($obj->ivaVal[$type])}}</td>
  <td>{{moneda($obj->total[$type])}}</td>
<?php
}
?>
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
        <td>TOTAL VENTAS ALOJAMIENTO</td>
        <?= printTableIva('rvs', $oExcel->ingr); ?>
      </tr>
      <tr class="white border">
        <td>VTAS PARA PROPIETARIOS (Pagos a Prop.)</td>
        <?= printTableIva('rvsProp', $oExcel->ingr); ?>
      </tr>
      <tr class="white border">
        <td>VTAS INTERM INMOB</td>
        <?= printTableIva('rvsInter', $oExcel->ingr); ?>
      </tr>
      <tr class="border">
        <td>VTAS FORFAITS </td>
        <?= printTableIva('ff', $oExcel->ingr); ?>
      </tr>
      <tr class="border">
        <td>VTAS CLASES/OTROS</td>
        <?= printTableIva('clases', $oExcel->ingr); ?>
      </tr>
      <tr class="border">
        <td>OTROS INGRESOS</td>
        <?= printTableIva('others', $oExcel->ingr); ?>
      </tr>
      <tr class="border">
        <th>Total</th>
        <th>{{moneda($oExcel->ingr->base['t'])}}</th>
        <th colspan="2">{{moneda($oExcel->ingr->ivaVal['t'])}}</th>
        <th>{{moneda($oExcel->ingr->total['t'])}}</th>
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
          <td>TOTAL PAGO PROPIETARIOS <br />
            <smal>(sólo lo pagado)</smal>
          </td>
          <?= printTableIva('payprop', $oExcel->gastos); ?>
        </tr>
        <tr class="border">
          <td>PROV FORFAITS </td>
          <?= printTableIva('excursion', $oExcel->gastos); ?>
        </tr>
        <tr class="border">
          <td>PROV CLASES/OTROS</td>
          <?= printTableIva('material', $oExcel->gastos); ?>
        </tr>
        <tr class="border">
          <td>GASTOS OPERATIVOS
            <i class="fa fa-question-circle box-popoer">
              <div class="popover"><?= $detailOp ?></div>
            </i>
          </td>
          <td>{{moneda($oExcel->gastos->base['others'])}}</td>
          <td>--</td>
          <td>{{moneda($oExcel->gastos->ivaVal['others'])}}</td>
          <td>{{moneda($oExcel->gastos->total['others'])}}</td>
        </tr>
        <tr class="border">
          <th>Total</th>
          <th>{{moneda($oExcel->gastos->base['t'])}}</th>
          <th colspan="2">{{moneda($oExcel->gastos->ivaVal['t'])}}</th>
          <th>{{moneda($oExcel->gastos->total['t'])}}</th>
        </tr>
      </table>
      @if($canEdit)
      <div class="resultado">
        <h5>IVA</h5>
        <table>
          <tr class="border">
            <th class="text-left">IVA REPERCUTIDO</th>
            <td>{{moneda($oExcel->iva->REPERCUTIDO)}}</td>
          </tr>
          <tr class="border">
            <th class="text-left">IVA SOPORTADO</th>
            <td>{{moneda($oExcel->iva->SOPORTADO)}}</td>
          </tr>
          <tr class="border">
            <th class="text-left">ARQUEO IVA</th>
            <td>
              <input type="text" class="saveIVA" data-k="ivaTemp" value="{{$oExcel->iva->ARQUEO}}">
              <span>€</span>
            </td>
          </tr>
          <tr class="border">
            <th class="text-left">IVA A PAGAR</th>
            <td>{{moneda($oExcel->iva->toPay)}}</td>
          </tr>
        </table>
        <span id="message_iva"></span>
      </div>
      @endif

    </div>
    <div class="col-md-6 resultado">
      <h5>RESULTADO OPERATIVO BRUTO</h5>
      <table>
        <tr class="border">
          <th></th>
          <th>TOTAL</th>
        </tr>
        <tr class="border">
          <td>INGRESOS</td>
          <td>{{moneda($oExcel->bruto->ingr)}}</td>
        </tr>
        <tr class="border">
          <td>GASTOS</td>
          <td>{{moneda($oExcel->bruto->gastos)}}</td>
        </tr>
        <tr class="border">
          <td>IVA A PAGAR</td>
          <td>{{moneda($oExcel->bruto->ivaPAy)}}</td>
        </tr>
        <tr class="border">
          <td>SubTotal</td>
          <td>{{moneda($oExcel->bruto->subTot)}}</td>
        </tr>
        <tr class="border">
          <td>Gastos Pend.</td>
          <td>{{moneda($oExcel->bruto->gastoPend)}}</td>
        </tr>
        <tr class="border">
          <td>TOTAL</td>
          <td>{{moneda($oExcel->bruto->total)}}</td>
        </tr>
      </table>
      @if($canEdit)
      <button class="btn btn-primary btn-reparto" type="button" data-toggle="modal" data-target="#modalRepartoBenefTemp">
        <i class="fa fa-eye"></i> Ver resultado de la temporada
      </button>
      @endif
    </div>

  </div>
</div>
@include('backend.sales.perdidas_ganancias.__repartoBenefTemp')

<style>
  .tabla-funcional table {
    width: 100%;
    color: #fff;
  }

  .tabla-funcional .ingresos table,
  .iva-2 {
    /*background-color: #c2ffc2;*/
    background-color: #349634;
  }

  .tabla-funcional .gasto table,
  .iva-1,
  .iva-3 {
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

  .tabla-funcional td hr {
    margin: 0;
  }

  .tabla-funcional table,
  .tabla-funcional .border td,
  .tabla-funcional .border th {
    border: 1px solid #afafaf;

  }

  .tabla-funcional .gasto,
  .tabla-funcional .resultado,
  .tabla-funcional .ingresos {
    padding: 8px;
    color: #fff;
  }


  tr.white td {
    background-color: #fff;
    color: #000;
  }

  .tabla-funcional-resultados table,
  .modal-body .row.tabla-funcional {
    width: 80%;
    margin: 1em auto;
  }

  .tabla-funcional-resultados table tr th,
  .tabla-funcional-resultados table tr td {
    border: 1px solid #afafaf;
    padding: 5px 10px;
  }

  .tabla-funcional-resultados table tr td.empty {
    border: none;
  }

  .tabla-funcional-resultados thead th {
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

  .iva-1,
  .iva-2 {
    color: #fff;

  }



  .iva-1 .col-xs-7,
  .iva-1 .col-xs-5,
  .iva-2 .col-xs-7,
  .iva-2 .col-xs-5,
  .iva-3 {
    border: 1px solid #afafaf;
    padding: 5px 15px;
    height: 3em;
    line-height: 2;
  }

  .iva-3 {
    height: 6em;
    text-align: center;

  }

  #resultIVA_modif {
    font-size: 1.5em;
    font-weight: 800;
    color: #fff;
    line-height: 3;
  }

  .iva-1 .col-xs-5 input,
  .iva-2 .col-xs-5 input {
    background-color: transparent;
    border: none;
    width: 84%;
    text-align: right;
    padding-right: 0;
  }

  .iva-1 span,
  .iva-2 span {
    float: right;
    width: 15%;
  }

  button.btn.btn-primary.btn-reparto {
    margin-top: 4em;
    margin-left: 1em;
  }

  input.saveIVA {
    background: transparent;
    text-align: right;
    padding: 0 6px;
    width: 85px;
    border: none;
    border-bottom: 1px solid;
  }
</style>