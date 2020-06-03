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
  @if($errors->any())
  <p class="alert alert-danger">{{$errors->first()}}</p>
  @endif
  @if (\Session::has('success'))
  <p class="alert alert-success">{!! \Session::get('success') !!}</p>
  @endif
</div>
 @endif