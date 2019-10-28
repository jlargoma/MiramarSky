<?php

use \App\Classes\Mobile;

$mobile = new Mobile();
?>
@extends('layouts.admin-master')

@section('title') Administrador de reservas MiramarSKI @endsection

@section('externalScripts') 
<script src="{{ asset('/vendors/ckeditor/ckeditor.js') }}"></script>
<style type="text/css">
  .name-back{
    background-color: rgba(72,176,247,0.5)!important;
  }
  .name-back input{
    background-color: transparent;
    color: black;
    font-weight: 800;
  }
  .ocupation-back{
    background-color: rgba(72,176,247,0.5)!important;
  }
  .ocupation-back input{
    background-color: transparent;
    color: black;
    font-weight: 800;
  }
  
  #tit_apto{
    text-align: center;
    font-size: 1.3em;
    width: 94%;
    background-color: #337cae;
    margin: 8px 3%;
    color: #fff;
  }
</style>
@endsection

@section('content')

<div class="container-fluid padding-25 sm-padding-10 table-responsive">
  <div class="row">
    <div class="col-md-12 text-center">
      <h2>LISTADO DE <span class="font-w800"> GALERÍAS PARA APARTAMENTOS</span></h2>
    </div>
    
  </div>
    @if($errors->any())
      <p class="alert alert-danger">{{$errors->first()}}</p>
    @endif
    @if (\Session::has('success'))
    <p class="alert alert-success">{!! \Session::get('success') !!}</p>
    @endif
  <div class="clearfix"></div>
  <div class="row">
    <button type="button" class="btn btn-success btn-sm newAptoText" data-toggle="modal" data-target="#modalTexts" title="Agregar aptos">
      <i class="fa fa-plus-circle" aria-hidden="true"></i>Agregar Apto
    </button> 
    <button type="button" class="btn btn-success btn-sm uploadHeaderEdificio" data-toggle="modal" data-target="#modalHeaders" data-id="-1" title="Subir cabecera aptos">
      <i class="fa fa-upload" aria-hidden="true"></i> Cabeceras de <b>El edificio</b>
    </button> 
    <div class="col-xs-12 content-table-rooms">
      <table class="table table-condensed table-striped">
        <thead>
          <tr>
            <th class ="text-center bg-complete text-white font-s12" style="width: 50%;">APTO</th>
            <th class ="text-center bg-complete text-white font-s12" >Texto</th>
            <th class ="text-center bg-complete text-white font-s12" >Galería</th>
            <th class ="text-center bg-complete text-white font-s12" >Cabeceras</th>
            <th class ="text-center bg-complete text-white font-s12" >Url</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rooms as $item): ?>
            <tr>
              <td class="text-left" >{{$item->title}}</td>
              <td class="text-center" >
                <button type="button" class="btn btn-success btn-sm editAptoText" data-toggle="modal" data-target="#modalTexts" data-id="{{$item->id}}" title="Editar textos aptos">
                  <i class="fa fa-pencil" aria-hidden="true"></i>
                </button>                    
              </td>
              <td class="text-center" >
                <button type="button" class="btn btn-success btn-sm uploadFile" data-toggle="modal" data-target="#modalFiles" data-id="{{$item->id}}" title="Subir imagenes aptos">
                  <i class="fa fa-upload" aria-hidden="true"></i>
                </button>                    
              </td>
              <td class="text-center" >
                <button type="button" class="btn btn-success btn-sm uploadHeader" data-toggle="modal" data-target="#modalHeaders" data-id="{{$item->id}}" title="Subir cabecera aptos">
                  <i class="fa fa-upload" aria-hidden="true"></i>
                </button>                    
              </td>
              <td class="text-center" >
                <a type="button" class="btn btn-default btn-sm" href="{{ url ('/apartamentos') }}/{{$item->name}}" target="_blank" data-original-title="Enlace de Apartamento" data-toggle="tooltip">
                  <i class="fa fa-paperclip"></i>
                </a>
              </td>
            </tr>
          <?php endforeach ?>
        </tbody>
      </table>
    </div>
  </div>
</div>



<div class="modal fade slide-up in" id="modalTexts" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xs">
    <div class="modal-content-wrapper">
      <div class="modal-content">
        <div class="block">
          <div class="block-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14" style="font-size: 40px!important;color: black!important"></i>
            </button>
            <h2 class="text-center">Descripción Apto</h2>
          </div>
          <div class="container-xs-height full-height" id="error_apto">
            <p class="alert alert-danger"></p>
          </div>
          <div class="container-xs-height full-height" id="content_apto">
              <form enctype="multipart/form-data" action="{{ url('/admin/aptos/edit-descript') }}" method="POST">
                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                <input type="hidden" name="room" id="room"  value="">
                <div class="form-group col-md-12">
                  <label for="Nombre">*Nombre</label>
                  <input type="text" class="form-control" id="item_nombre" name="item_nombre" placeholder="Nombre del Apto" maxlength="40" required>
                </div>
                <div class="form-group col-md-12">
                  <label for="Nombre">*URL Slug</label>
                  <input type="text" class="form-control" id="item_name" name="item_name" placeholder="URL del Apto" required>
                </div>
                <div class="form-group col-md-12">
                  <label for="Estado">Estado</label>
                  <select id="item_status" name="item_status" class="form-control">
                    <option value="1">Publicado</option>
                    <option value="0">No Publicado</option>
                  </select>
                </div>
                <div class="form-group col-md-12">
                  <label for="Nombre">Descripción</label>
                  <textarea class="" name="apto_descript" id="apto_descript" rows="10" cols="80"></textarea>
                </div>
                <div class="form-group col-md-12">
                  <input type="submit" value="Enviar" class="btn btn-primary" />
                </div>
              </form>
          </div>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<div class="modal fade slide-up in" id="modalHeaders" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xs">
    <div class="modal-content-wrapper">
      <div class="modal-content">
        <div class="block">
          <div class="block-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14" style="font-size: 40px!important;color: black!important"></i>
            </button>
            <h2 class="text-center">
              Subida de Imágenes de cabeceras
            </h2>
          </div>
          <div class="container-xs-height full-height">
            <div class="row-xs-height">
              <div class="modal-body col-xs-height col-middle text-center   ">
                <div class="upload-body">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<div class="modal fade slide-up in" id="modalFiles" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xs">
    <div class="modal-content-wrapper">
      <div class="modal-content">
        <div class="block">
          <div class="block-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14" style="font-size: 40px!important;color: black!important"></i>
            </button>
            <h2 class="text-center">
              Subida de archivos
            </h2>
          </div>
          <div class="container-xs-height full-height">
            <div class="row-xs-height">
              <div class="modal-body col-xs-height col-middle text-center   ">
                <div class="upload-body">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
@endsection

@section('scripts')

<script src="/assets/js/notifications.js" type="text/javascript"></script>

<script type="text/javascript">

$(document).ready(function () {
 $('.uploadFile').click(function(event) {
    var id = $(this).attr('data-id');
    $.get('/admin/apartamentos/gallery/'+id, function(data) {
      $('#modalHeaders').find('.upload-body').empty().append(data);
    });
  });

  $('.uploadHeader').click(function(event) {
    var id = $(this).attr('data-id');
    $.get('/admin/apartamentos/headers/room_type/'+id, function(data) {
      $('.upload-body').empty().append(data);
    });
  });
  $('.uploadHeaderEdificio').click(function(event) {
    $.get('/admin/apartamentos/headers/edificio/edificio', function(data) {
      $('#modalHeaders').find('.upload-body').empty().append(data);
    });
  });
  
  
  CKEDITOR.replace('apto_descript',
          {
            toolbar:
                    [
            { name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
            { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
            { name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv',
                '-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] },
            { name: 'links', items : [ 'Link','Unlink','Anchor' ] },
            '/',
            { name: 'styles', items : [ 'Styles','Format','Font','FontSize' ] },
            { name: 'colors', items : [ 'TextColor','BGColor' ] },
            { name: 'tools', items : [ 'Maximize', 'ShowBlocks','-','About' ] }
                    ]
          });

 
  $('#send_apto_descript').click(function (event) {
    
  });
  $('.newAptoText').click(function (event) {
    $('#error_apto').hide();
    $('#content_apto').show();
    $('#room').val('');
    $('#item_nombre').val('');
    $('#item_name').val('');
    CKEDITOR.instances.apto_descript.setData('', function () {
      this.checkDirty();
    });
  });
  $('.editAptoText').click(function (event) {
    var id = $(this).data('id');
    $('#error_apto').hide();
    $('#content_apto').hide();
    $.get('/admin/aptos/edit-descript/' + id, function (data) {
      if ( data.result == 'ok'){
        $('#content_apto').show();
        $('#room').val(id);
        $('#status').val(data.status);
        $('#item_nombre').val(data.title);
        $('#item_name').val(data.name);
        CKEDITOR.instances.apto_descript.setData(data.text, function () {
          this.checkDirty();
        });
      } else {
        $('#error_apto').show();
        $('#error_apto').find('p').text(data.msg);
//        window.show_notif('Error','error',data.msg);
      }
    });
  });

  
  
  
  
  
  
  
  
  
  
  
  
  
});
</script>
@endsection