@extends('layouts.admin-master')

@section('title') Contratos @endsection

@section('content')
<style type="text/css">

  .contratoBox{
    max-width: 860px;
    margin: 50px auto;
    font-size: 16px;
    padding: 30px 10px 45px 50px;
    background-color: #FFF;
  }
  .rateCalendar .item {
    width: 200px;
  }
  .sing-box {
    border: 1px solid;
    width: 325px;
    padding: 5px;
    margin: 15px auto;
  }
  .signProp,
  .signAdmin{
    width: 48%;
    margin: 2%;
    text-align: center;
    display: inline-block;
  }
  .signs img {
    width: 330px;
}
.contratoBox h1 {
    font-size: 20px;
    font-weight: bold;
    text-align: center;
    margin-left: -1em;
}
.contratoBox h3 {
  margin-bottom: 14px;
  font-size: 12px;

}
.contratoBox .body,
.contratoBox .body p{
  font-size: 13px;
  /*text-indent: 5px;*/
  text-align: justify;
}
@media only screen and (max-width: 540px) {
  .contratoBox{
    padding: 30px 15px;
  }
  .contratoBox h1{
    margin-left: 0px;
    line-height: 1.5;
  }
  .contratoBox h2 {
    font-size: 15px;
    line-height: 1;
    letter-spacing: 1px;
    margin: 1em 4px;
  }
  .rateCalendar .item {
    width: 48%;
  }
  .sessionType {
    overflow: auto;
    margin: 1em auto;
  }
  .sessionType table td {
    padding: 5px 10px !important;
    font-size: 13px;
    text-align: center;
  }
  .rateCost table th {
    font-size: 10px;
    min-width: 46px !important;
}
.rateCost table td {
    font-size: 10px !important;
}
  .rateCost {
    width: 100%;
    overflow: auto;
  }
}


input.inputName,input.inputDNI {
    border: none;
    border-bottom: 1px dotted black;
}

input.inputName{
  width: 260px;
}
input.inputDNI{
  width: 150px;
}

input.inputName.danger,
input.inputDNI.danger{
  box-shadow: -1px 2px 4px 0px red;
}

</style>


<div class="contratoBox">
  <h1>CONTRATATO PARA DELEGACION DE REPRESENTACION</h1>
  <h3>Sierra Nevada {{$date}}</h3>
  <div class="body">
    <?php echo $text; ?>
  </div>
  <div class="row signs">
      <form  action="{{ route('contratoDelegacion.sign') }}" method="post" style="width: 325px; margin: 1em auto;"> 
          <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
          <input type="hidden" name="sign"  id="sign" value="">
          <input type="hidden" name="cedeToName"  id="cedeToName" value="">
          <input type="hidden" name="cedeToDni"  id="cedeToDni" value="">
          <input type="hidden" name="ID"  id="ID" value="{{$id}}">
          <h5 >Firma Propietario</h5>
          <div class="sing-box">
            <canvas width="320" height="300" id="cSign"></canvas>
          </div>
          <button class="btn btn-success" type="button" id="saveSign">
            <i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar
          </button>
          <button class="btn btn-danger" type="button" id="clearSign">
            <i class="fa fa-trash" aria-hidden="true"></i> Limpiar
          </button>
        </form>
  </div>
</div>
 @if($sign)
 <div class="text-center mY-1em">
 <a href="{{route('contract.downl',[$id])}}" class="btn btn-success">Descargar</a>
 <br/>
 <br/>
 <br/>
 </div>
  @endif
  
  @endsection

  @section('scripts')
  <script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>
  <script type="text/javascript">
$(document).ready(function () {
    var canvas = document.querySelector("canvas");
    var signaturePad = new SignaturePad(canvas);
    $('#clearSign').on('click', function (e) {
        signaturePad.clear();
    });
    $('#saveSign').on('click', function (e) {
      
//      if ($('#cedeToName').val() == ''){
//        $('.inputName').addClass('danger');
//        alert('Debe completar el nombre para continuar');
//        return false;
//      }
//      if ($('#cedeToDni').val() == ''){
//        $('.inputDNI').addClass('danger');
//        alert('Debe completar el DNI para continuar');
//        return false;
//      }
      
      e.preventDefault();
      $('#sign').val(signaturePad.toDataURL()); // save image as PNG
      $(this).closest('form').submit();
    });
    
    var e = document.getElementsByTagName('name')[0];
    if (typeof e != 'undefined')
      e.innerHTML = '<input type="text" class="inputName">';
    
    var e = document.getElementsByTagName('dni')[0];
    if (typeof e != 'undefined')
      e.innerHTML = '<input type="text"  class="inputDNI">';
    
    $('.contratoBox').on('click','.inputName,.inputDNI',function(){
      $(this).removeClass('danger');
    });
    $('.contratoBox').on('change','.inputName',function(){
      $('#cedeToName').val($(this).val());
    });
    $('.contratoBox').on('change','.inputDNI',function(){
      $('#cedeToDni').val($(this).val());
    });
    
});

  </script>
  @endsection