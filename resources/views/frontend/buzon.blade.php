@extends('layouts.master')

@section('metadescription') Condiciones generales - apartamentosierranevada.net @endsection
@section('title')  Condiciones generales - apartamentosierranevada.net @endsection

@section('content')

<?php
if (!isset($oContents)) $oContents = new App\Contents();
$content = $oContents->getContentByKey('buzon');
?>
<section id="content" style="margin-top: 15px">

  <div class="container container-mobile clearfix push-0">
    <div class="row">
      <h1 class="center psuh-20">{{$content['title']}}</h1>
    </div>
    <div class="text-left">
    {!!$content['content']!!}
    </div>
</section>

@endsection
@section('scripts')

@endsection