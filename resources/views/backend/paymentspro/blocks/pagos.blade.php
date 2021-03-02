<div class="row">
 <h2 class="text-center font-w800">Gastos</h2>
</div>

<div class="table-responsive">
          <table class="table">
            <thead >
              <th class="text-center bg-complete text-white col-md-1"">Fecha</th>
              <th class="bg-complete text-white col-md-2">Concepto</th>
              <th class="bg-complete text-white col-md-2">Tipo</th>
              <th class="bg-complete text-white col-md-1">Método de pago            </th>
              <th class="text-right bg-complete text-white col-md-2">Importe</th>
              <th class="text-center bg-complete text-white col-md-2">Comentario</th>
              <th class="text-center bg-complete text-white">#</th>
            </thead>
            <tbody id="tableItems" class="text-center">
                @if( count( $pagos ) > 0)
                @foreach($pagos as $pago)
                  <?php
                    $paymentAux = isset($lstPagos[$pago->id]) ? $lstPagos[$pago->id] : 0;
                   ?>
                <tr data-id="{{$pago->id}}" data-import="{{$paymentAux}}">
                  <td>{{convertDateToShow_text($pago->date)}}</td>
                  <td class="editable text-left" data-type="concept">{{$pago->concept}}</td>
                  <td class="editable text-left selects stype" data-type="type" data-current="{{$pago->type}}">
                      {{ show_isset($gType,$pago->type)}}</td>
                  <td class="editable selects spayment" data-type="payment" data-current="{{$pago->typePayment}}" >{{$pago->getTypeCobro($pago->typePayment)}}</td>
                  
                  <td class="editable text-right" data-type="price" >{{moneda($paymentAux)}}</td>
                  <td class="editable" data-type="comm">{{$pago->comment}}</td>
                  <td><button data-id="{{$pago->id}}" type="button" class="del_expense btn btn-danger btn-xs"><i class="fa fa-trash"></i></button></td>
                </tr>
                @endforeach
                @endif
                
            </tbody>
          </table>
        </div>