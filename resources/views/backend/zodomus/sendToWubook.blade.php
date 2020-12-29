<a class="text-white btn btn-md btn-primary" href="/admin/channel-manager/index">DISPONIBILIDAD</a>
@if (false)
<div class="inline-block">
  @if( !(\App\ProcessedData::emptyContent('sentUPD_wubook')) )
  <form action="{{route('Wubook.sendPrices')}}" method="POST" class="inline-block">
    <input type="hidden" id="_token" name="_token" value="<?php echo csrf_token(); ?>">
    <button class="btn btn-success">Sincr. Precios WuBook</button>
  </form>
  @else
  <button class="btn btn-primary" disabled>Sincr. Precios WuBook</button>
  @endif
  @if( !(\App\ProcessedData::emptyContent('sentUPD_wubook_minStay')) )
  <form action="{{route('Wubook.sendMinStay')}}" method="POST" class="inline-block">
    <input type="hidden" id="_token" name="_token" value="<?php echo csrf_token(); ?>">
    <button class="btn btn-success">Sincr. MinStay WuBook</button>
  </form>
  @else
  <button class="btn btn-primary" disabled>Sincr. MinStay WuBook</button>
  @endif

</div>
@endif