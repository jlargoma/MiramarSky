<div class="modal fade" id="modalRepartoBenefTemp" tabindex="-1" role="dialog"  aria-hidden="true">
  <div class="modal-dialog" role="document" style="width: 80vw">
    <div class="modal-content" >
      <div class="modal-header">
        <strong class="modal-title" style="font-size: 1.4em;">REPARTO DE BENEFICIOS TEMPORADA</strong>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="table-responsive tabla-funcional-resultados">
        <h1 class="alert alert-danger">En Revisión</h1>
  <div class="">
    <table>
      <thead>
        <tr>
          <th colspan="2">REPARTO DE BENEFICIOS TEMPORADA</th>
          <td class="empty"></td>
        </tr>
      </thead>
      <tr>
        <td>VENTA INTERM INMOB</td>
        <td>{{moneda($ingr_reservas)}}</td>
        <td class="empty"></td>
      </tr>
      <tr>
        <td>OTROS INGRESOS</td>
        <td>{{moneda($otros_ingr)}}</td>
        <td class="empty"></td>
      </tr>
      <tr>
        <td>GASTOS OPERATIVOS</td>
        <td>{{moneda($gasto_operativo_baseImp+$gasto_operativo_iva)}}</td>
        <td class="empty"></td>
      </tr>
      <tr>
        <td>IVA A PAGAR</td>
        <td>{{moneda($t_iva)}}</td>
        <td class="empty"></td>
      </tr>
      <tr>
        <th>DIV A REPARTIR</th>
        <th>{{moneda($repartoTemp_fix-$t_iva)}}</th>
        <td class="empty"></td>
      </tr>
      <tr><td class="empty" colspan="2"></td></tr>
      <tr>
        <th>JAIME</th>
        <th>{{moneda($repartoTemp_jaime1)}}</th>
        <td class="empty imputs"><input type="text" id="benefJaime" value="{{$benefJaime}}" class="">%</td>
      </tr>
      <tr>
        <th>JORGE</th>
        <th>{{moneda($repartoTemp_jorge1)}}</th>
        <td class="empty imputs"><input type="text" id="benefJorge" value="{{$benefJorge}}">%</td>
      </tr>
    </table>
  </div>
</div>
      </div>
    </div>
  </div>
</div>
