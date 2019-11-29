@extends('layouts.master')

@section('metadescription')Restaurantes en Sierra Nevada @endsection
@section('title') Restaurantes en Sierra Nevada @endsection
<?php
if (!isset($oContents))
  $oContents = new App\Contents();
$content = $oContents->getContentByKey('resto');
?>


@section('content')

<section class="page-title apartamento centered">
  <div class="container">
    <div class="content-box">
      <h1 class="center ">Restaurantes</h1>
    </div>
  </div>
</section>
<section class="content-1">
  <h2>¿Qué comprar o dónde comer en Sierra Nevada?</h2>
  <h3 class="bp-rest-title ">LISTADO DE COMERCIOS, BARES Y RESTAURANTES DE LA ESTACIÓN DE ESQUÍ</h3>

  <div class="row container">
    <div class="col-md-6 col-sm-12">
      <img class="img-responsive" src="{{ asset('/img/posts/mapa-restaurante-sierra-nevada.png')}}" alt="Mapa" style="margin: 0 auto;">
    </div>
    <div class="col-md-6 col-sm-12 bloque_blanco">
      {!!$content['content'] !!}
    </div>
  </div>

  <div class="container-fluid">

    <div class="row tit-float">
      <h3 class="bp-ot-text destacado">LOS IMPRESCINDIBLES</h3>
    </div>
    <div class="row">
      <div class="col-md-12 col-xs-12 bg-primary text-white text-center">
        <p class="text-white destacado-txt">Vamos a dividir los restaurantes según los gustos, de esta forma puedes ver lo que tienes a tu alcance para degustar en Sierra Nevada. Encontramos principalmente 6 grandes grupos de los que te contaremos los principales establecimientos:</p>
      </div>
    </div>
  </div>
  
  <div class="row mt-2 pb-2">
      <div class="col-md-12 col-xs-12 text-center">

  <a href="#carne-y-comida-casera"><button type="button" class="btn btn-info">Especialidad en carne y comida casera</button></a> 
  <a href="#comida-granadina-y-andaluza"><button type="button" class="btn btn-info">Comida típica de Granadina o andaluza</button></a> 
  <a href="#estilo-mediterraneo-o-variada"><button type="button" class="btn btn-info">Cocina de estilo mediterráneo o variada</button></a> 
  <a href="#pizzerias"><button type="button" class="btn btn-info">Pizzerías</button></a> 
  <a href="#comida-rapida-y-en-pista"><button type="button" class="btn btn-info">Comida rápida y en pista</button></a> 
  <a href="#bares"><button type="button" class="btn btn-info">Bares</button></a> 

 
  
  
      </div>
    </div>
</section>
<section>
  <?php
   $items = [
       '',
      'carne-y-comida-casera',
      'comida-granadina-y-andaluza',
      'estilo-mediterraneo-o-variada',
      'pizzerias',
      'comida-rapida-y-en-pista',
      'bares'
      ];
  ?>
  
      
  @for($i=1;$i<7;$i++)
  <?php $resto_item = $oContents->getContentByKey('resto_'.$i); ?>
  @if(isset($resto_item))
  <div class="space-apartamentos" id="{{$items[$i]}}">
          <h2 class="bp-ot-title">{{$i}} - {{$resto_item['title']}}</h2>
          <hr style="margin: 5px auto; width: 85px; height: 4px; background-color: #3f51b5;">
          <div class="subcontent">{!! $resto_item['content'] !!}</div>
  </div>
  <div class="container resto-list">
  @include('frontend.blocks.resto',['content'=>$resto_item]);
  </div>
  @endif
  @endfor
 
</section>
@endsection

@section('scripts')
<style>
  .mt-2{
    margin-top: 2em;
  }
  .bp-ot-title {
    text-align: center;
    color: #3f51b5;
    font-size: 3em;
    font-weight: 700;
    padding-bottom: 0px;
    margin-bottom: -10px;
      padding-top: 2em;
}
.subcontent{
      max-width: 780px;
    margin: 1em auto;
}
.img-resto{
  box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
  padding: 1rem!important;
  margin-bottom: 3em;
}
.resto-list p {
    margin: 8px;
}
  .apartamento {
    background-image: url('/frontend/images/restaurantes.jpg') !important;
  }
  .apartamento h1 {
    transform-origin: center center 0;
    transform: scale(1) translateZ(0);
    font-family: miramar;
    color: #ffffff;
    font-size: 3em;
    text-shadow: #000 1px 1px;
    line-height: 80px;
    font-weight: 700;
    font-size: 5em;
    line-height: 1;
  }
  .container-fluid{
    position: relative;
    margin-top: 7em;
  }
  .bloque_blanco {
    background-color: white;
    padding: 35px;
    -webkit-box-shadow: 0px 4px 5px 0px rgba(0,0,0,0.51);
    -moz-box-shadow: 0px 4px 5px 0px rgba(0,0,0,0.51);
    box-shadow: 0px 4px 5px 0px rgba(0,0,0,0.51);
  }
  .content-1{
    background-image: url('/frontend/images/fondo.jpg') ;
    background-repeat: no-repeat;
    background-position: center;
    background-size: cover;
  }
  .content-1 h2{
    color: #fff;
    text-shadow: #000 1px 1px;
  }
  .bp-rest-title {
    text-align: center;
    color: #3f51b5;
    font-size: 29px;
    font-family: 'Muli', sans-serif;
    font-weight: 700;
    margin: 13px auto;
  }
  .destacado {
    padding: 20px;
    background-color: #16bcd9;
    width: 25%;
    border-radius: 5px;
    font-size: 19px;
    font-weight: 700;
    margin: 0 auto -25px;
    z-index: 10;
  }
  .bp-ot-text {
    text-align: center;
    color: #fff;
    text-shadow: 1px 1px 3px #000;
    font-family: 'Muli', sans-serif;
    font-weight: 900;
    font-size: 2rem;
  }
  .destacado-txt {
    padding: 45px 20px 20px 20px;
    font-size: 20px;
  }
  .tit-float{
        z-index: 9;
    position: absolute;
    /* text-align: center; */
    width: 100%;
    top: -40px;
  }
  @media (max-width: 818px){
    .apartamento h1{
      padding-top: 2em;
    }
  }
  @media (max-width: 768px){
    .apartamento h1{
      padding-top: 2em;
    }
    .container-mobile{
      padding: 0!important
    }
    .container-image-box img{
      height: 180px!important;
    }
  }

</style>
@endsection