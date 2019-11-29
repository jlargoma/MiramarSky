@if($content)
@for($i=1;$i<5;$i++)
@if(isset($content['content_'.$i]))
<div class="row text-left">
  <div class="col-md-4">
    <img class="img-responsive img-resto" src="{{$content['content_'.$i.'_img']}}"/>
  </div>
  <div class="col-md-8">
    {!! $content['content_'.$i] !!}
  </div>
</div>
@endif
@endfor
@endif