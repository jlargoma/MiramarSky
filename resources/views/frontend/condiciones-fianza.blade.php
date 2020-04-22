@extends('layouts.master')
@section('content')

<?php
if (!isset($oContents)) $oContents = new App\Contents();
$fianza = $oContents->getContentByKey('fianza');
?>
<meta name="description" content="Consultar Condiciones De Fianzas para el alquiler apartamento sierra nevada,condiciones se Fianza" />
<meta name="keywords" content="condiciones De Fianzas,alquiler apartamento sierra nevada,condiciones se Fianza">


<section id="content" style="margin-top: 15px">

  <div class="container container-mobile clearfix push-0">
    <div class="row">
      <h1 class="center psuh-20">Condiciones De Fianzas</h1>
    </div>
    <div class="text-left">
    {!!$fianza['content']!!}
    </div>
</section>

@endsection
@section('scripts')

@endsection