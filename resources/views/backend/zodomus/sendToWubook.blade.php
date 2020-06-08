 @if (Auth::user()->email == "jlargo@mksport.es")
<div class="inline-block">
  @if( !(\App\ProcessedData::emptyContent('sentUPD_wubook')) )
    <form action="{{route('Wubook.sendPrices')}}" method="POST">
      <input type="hidden" id="_token" name="_token" value="<?php echo csrf_token(); ?>">
      <button class="btn btn-primary">Enviar Precios a WuBook</button>
    </form>
  @else
    <button class="btn btn-primary" disabled>Enviar Precios a WuBook</button>
  @endif
</div>
 @endif