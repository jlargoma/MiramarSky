<div class="row ">
  <h3 class="col-md-4 text-center">Base Imp.: <b>{{moneda($t_bImp)}}</b></h3>
  <h3 class="col-md-4 text-center">IVA: <b>{{moneda($t_Iva)}}</b></h3>
  <h3 class="col-md-4 text-center">Total: <b>{{moneda($total)}}</b></h3>
</div>
<div class="row">
  <div class="table-responsive">
    <table class="table">
      <thead >
      <th class="text-center bg-complete text-white col-md-1">Fecha</th>
      <th class="text-center bg-complete text-white col-md-2">Concepto</th>
      <th class="text-center bg-complete text-white col-md-1">Método de pago</th>
      <th class="text-center bg-complete text-white col-md-2">Base Imp.</th>
      <th class="text-center bg-complete text-white col-md-2">IVA</th>
      <th class="text-center bg-complete text-white col-md-2">Importe</th>
      <th class="text-center bg-complete text-white col-md-2">Comentario</th>
      <th class="text-center bg-complete text-white col-md-2">Tipo</th>
      </thead>
      <tbody id="tableItems" class="text-center">
        @if($items)
          @foreach($items as $item)
          <tr>
            <td>{{convertDateToShow_text($item->date)}}</td>
            <td>{{$item->concept}}</td>
            <td><?php echo isset($typePayment[$item->typePayment]) ? $typePayment[$item->typePayment] : '--'; ?></td>
            <td>{{moneda($item->bimp)}}</td>
            <td>{{moneda($item->iva)}}</td>
            <td>{{moneda($item->import)}}</td>
            <td>{{$item->comment}}</td>
            <td>{{$item->type}}</td>
          </tr>
          @endforeach
        @endif
      </tbody>
      
    </table>
  </div>
</div>