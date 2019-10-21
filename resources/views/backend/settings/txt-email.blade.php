<?php

use \Carbon\Carbon;

setlocale(LC_TIME, "ES");
setlocale(LC_TIME, "es_ES"); ?>
@extends('layouts.admin-master')

@section('title') Configuración TXT emails @endsection

@section('externalScripts')
<script src="{{ asset('/vendors/ckeditor/ckeditor.js') }}"></script>
@endsection

@section('content')

<div class="container-fluid padding-25 sm-padding-10">
  <div class="row">
    <div class="col-md-12 text-center">
      <h2 class="font-w800">CONFIGURACIONES - TEXTOS</h2>

      <div>
         <?php if (\Request::is('admin/settings_msgs')): ?>
          <button class="btn btn-md btn-primary active"  disabled>Español</button>
        <?php else: ?>
          <a class="text-white btn btn-md btn-primary" href="/admin/settings_msgs">Español</a>
<?php endif ?>	
          <?php if (\Request::is('admin/settings_msgs/en')): ?>
          <button class="btn btn-primary active"  disabled>Ingles</button>
        <?php else: ?>
          <a class="text-white btn btn-md btn-primary" href="/admin/settings_msgs/en">Ingles</a>
<?php endif ?>
      </div>
    </div>
    @foreach($settings as $k=>$v)
    <div class="col-md-6 text-center m-t-20 p-l-50 p-r-50">
      <h3 class="font-w800">{{$v}}</h3>
      <form method="POST" action="{{route('settings.msgs.upd',$lng)}}">
        <input type="hidden" id="_token" name="_token" value="<?php echo csrf_token(); ?>">
        <input type="hidden" name="key" id="key" value="{{$k}}">
        <?php 
          $content = isset($data[$k]) ? trim($data[$k]) : ''; 
          $ckeditor = true;
          if ( $k =='SMS_Partee_msg' 
              || $k == 'SMS_Partee_upload_dni'
              || $k == 'SMS_Partee_upload_dni_en'
              || $k == 'SMS_Partee_msg_en'
            )
            $ckeditor = false;
        ?>
        @if($ckeditor)
          <textarea class="ckeditor" name="{{$k}}" id="{{$k}}" rows="10" cols="80">{{$content}}</textarea>
        @else
          <textarea class="form-control" name="{{$k}}" id="{{$k}}" rows="10" cols="80">{{$content}}</textarea>
        @endif
        <button class="btn btn-primary m-t-20">Guardar</button>
      </form>
    </div>
    
    @endforeach
  </div>
  
  <div class="infomsg">
  <h2>Variables</h2>
  <div class="row">
    <div class="col-md-6">
      Nombre:<b>{customer_name}</b> <br>
      Teléfono:<b><a href="tel:{customer_phone}">{customer_phone}</a></b> <br>
      Apartamento: <b>{room}  {room_type}</b><br>
      Email:<b>{customer_email}</b> <br>
      Fecha Entrada:<b>{date_start}</b> <br>
      Fecha Salida:<b>{date_end}</b> <br>
      Noches:<b>{nigths}</b> <br>
      Ocupantes:<b>{pax}</b> <br>
    </div>
    <div class="col-md-6">
      Suplemento lujo:<b>{sup_lujo} €</b> <br>
      Comentarios:<b>{comment}</b><br>
      Observaciones internas:<b>{book_comments}</b><br>
      Precio total:<b>{total_price} €</b><br>
      Tipo de habitación:<b>{room_type}</b><br>
      Nombre habitación:<b>{room_name}</b><br>
      <b>Pago de la señal {percent}% del total = {mount_percent} €.</b><br>
      
      % restante de tu reserva (Recordatorio 2º pago):<b>{pend_percent}</b><br>
      Valor restante de tu reserva (Recordatorio 2º pago):<b>{pend_payment}</b><br>
      Url pago restante de tu reserva (Recordatorio 2º pago):<b>{urlPaymeny_rest}</b><br>
      Cobrado (Recordatorio 2º pago):<b>{total_payment}</b><br>
      Partee link:<b>{partee}</b> <br>
    </div>
  </div>
 </div>
  
  
  
</div>


@endsection

@section('scripts')
 <script>tinymce.init({selector:'textarea'});</script>
 <style>
    .infomsg{
      font-size: 0.85em;
      background-color: #fff;
      padding: 3em;
      width: 80%;
      margin: 5em auto 0;
      box-shadow: 3px 1px 6px 1px;
      line-height: 1.85em;
    }
    
    .infomsg b{
      margin-left: 1em;
    }
 </style>
@endsection