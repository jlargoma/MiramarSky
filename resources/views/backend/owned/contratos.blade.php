@extends('layouts.admin-master')

@section('title') Contratos @endsection

@section('content')
<?php echo $calStyles; ?>
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
  table.signs{
    width: 100%;
  }
  table.signs td{
    width: 49%;
      text-align: center;
  }
  table.signs td img {
    width: 160px;
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
</style>


<div class="contratoBox">
  <h1>CONTRATO DE COMERCIALIZACIÓN DE VIVIENDA PARTICULAR</h1>
  <h3>Sierra Nevada {{$date}}</h3>
  <div class="body">
    <?php echo $text; ?>
  </div>
  <table class="signs">
    <tr>
      <td >
        @if($sign)
        <img src="/admin/contrato/sign/{{$signFile}}" >
        
        @else
        <form  action="{{ route('contract.sign') }}" method="post" style="width: 325px; margin: 1em auto;"> 
          <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
          <input type="hidden" name="sign"  id="sign" value="">
          <input type="hidden" name="ID"  id="ID" value="{{$id}}">
          <h5>Firma</h5>
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
        @endif
      </td>
      <td>
        <img src="/admin/contrato/sign/contratos.png" >
      </td>
    </tr>
    <tr>
      <td >Firma Propietario</td>
      <td >Firma MiramarSki</td>
    </tr>
  </table>
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
        e.preventDefault();
        $('#sign').val(signaturePad.toDataURL()); // save image as PNG
        $(this).closest('form').submit();
    });
});

  </script>
  @endsection