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
        <div class="row tabla-funcional ">
          <div class="col-md-4 resultado">
            <table>
              <tr>
                <th>RTDO VENTA ALOJAMIENTOS</th>
                <th>{{moneda($ingr_reservas-$tGastos_operativos)}}</th>
              </tr>
              <tr class="white border">
                <td>VTA INTERMEDIACION INMOB</td>
                <td>{{moneda($ingr_reservas)}}</td>
              </tr>
              <tr class="white border">
                <td>GASTOS OPERATIVOS</td>
                <td >{{moneda($tGastos_operativos)}}</td>
              </tr>
            </table>
          </div>
          <div class="col-md-4 resultado">
            <table>
              <tr>
                <th>OTROS INGRESOS</th>
                <th>{{moneda($otros_ingr)}}</th>
              </tr>
            </table>
          </div>
          <div class="col-md-4 resultado">
            <table>
              <tr>
                <th>RTDO OPERTIVO BRUTO</th>
                <th>{{moneda($ingr_reservas-$tGastos_operativos+$otros_ingr)}}</th>
              </tr>
            </table>
          </div>
        </div>
        <div class="table-responsive tabla-funcional-resultados">
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
        <th>IVA A PAGAR C/ARQUEO</th>
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
      </div>
    </div>
  </div>
</div>
