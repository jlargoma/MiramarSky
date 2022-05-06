<div class="modal fade" id="modalRepartoBenefTemp" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document" style="width: 80vw">
    <div class="modal-content">
      <div class="modal-header">
        <strong class="modal-title" style="font-size: 1.4em;">REPARTO DE BENEFICIOS TEMPORADA</strong>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body row box-reparto">
        <div class="col-md-6 repartResult">
        <strong>RESULTADO OPERATIVO BRUTO</strong>
          <table class="table">
            <tr class="bg-header">
              <th></th>
              <th>TOTAL</th>
            </tr>
            <tr>
              <th>INGRESOS</th>
              <td>{{moneda($oExcel->bruto->ingr)}}</td>
            </tr>
            <tr>
              <th>GASTOS</th>
              <td>{{moneda($oExcel->bruto->gastos)}}</td>
            </tr>
            <tr>
              <th>IVA A PAGAR</th>
              <td>{{moneda($oExcel->bruto->ivaPAy)}}</td>
            </tr>
            <tr>
              <td>SubTotal</td>
              <td>{{moneda($oExcel->bruto->subTot)}}</td>
            </tr>
            <tr>
              <td>Gastos Pend.</td>
              <td>{{moneda($oExcel->bruto->gastoPend)}}</td>
            </tr>
            <tr class="bg-header">
              <th>TOTAL</th>
              <th>{{moneda($oExcel->bruto->total)}}</th>
            </tr>
          </table>
        </div>
        <div class="col-md-6 repartBenef">
        <strong>REPARTO DE LA TEMPORADA</strong>
          <form action="/admin/perdidas-ganancias/upd-benefic" method="POST">
          {{csrf_field()}}
            <input type="hidden" id="brutoTotal" value="{{ceil($oExcel->bruto->total)}}">
            <table  class="table">
              <tr class="bg-header">
                <th></th>
                <th>%</th>
                <th>TOTAL</th>
              </tr>
              <tr>
                <th>Jorge</th>
                <td><input name="percentJorge" id="percentJorge" value="{{$oExcel->bruto->percJorge}}" class="editBenef"></td>
                <th id="benfJorge">{{moneda($oExcel->bruto->benfJorge)}}</th>
              </tr>
              <tr>
                <th>Jaime</th>
                <td><input id="percJaime" value="{{$oExcel->bruto->percJaime}}" class="editBenef"></td>
                <th id="benfJaime">{{moneda($oExcel->bruto->benfJaime)}}</th>
              </tr>
            </table>
            <div class="mt-1em">
              <button class="btn btn-success">Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  .repartResult,.repartBenef{
    margin: 1em auto;
    text-align: center;
  }
  .repartResult table.table,
  .repartBenef table.table{
    max-width: 310px;
    margin: auto;
  }
  tr.bg-header {
    background-color: #00b0f0;
    color: #FFF;
  }
  tr.bg-header th{
    text-align: center;
  }
  input.editBenef {
    width: 52px;
    text-align: center;
    border: none;
    background-color: #ebebeb;
}
.box-reparto{
  max-width: 742px;
    text-align: center;
    margin: 5px auto 1em !important;
}
#benfJaime,#benfJorge{
  text-align: center;
}
</style>