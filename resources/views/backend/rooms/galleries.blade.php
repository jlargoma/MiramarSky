<?php

use \App\Classes\Mobile;

$mobile = new Mobile();
?>
@extends('layouts.admin-master')

@section('title') Administrador de reservas MiramarSKI @endsection

@section('externalScripts') 

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
    <div class="col-md-5 col-xs-12 content-table-rooms">
      <table class="table table-condensed table-striped">
        <thead>
          <tr>
            <th class ="text-center bg-complete text-white font-s12" style="width: 50%;">APTO</th>
            <th class ="text-center bg-complete text-white font-s12" >Texto</th>
            <th class ="text-center bg-complete text-white font-s12" >Galería</th>
            <th class ="text-center bg-complete text-white font-s12" >Url</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rooms as $k=>$name): ?>
            <tr>
              <td class="text-left" >{{$name}}</td>
              <td class="text-center" >
                <button type="button" class="btn btn-success btn-sm uploadFile" data-toggle="modal" data-target="#modalTexts" data-id="{{$k}}" title="Editar textos aptos">
                  <i class="fa fa-pencil" aria-hidden="true"></i>
                </button>                    
              </td>
              <td class="text-center" >
                <button type="button" class="btn btn-success btn-sm uploadFile" data-toggle="modal" data-target="#modalFiles" data-id="{{$k}}" title="Subir imagenes aptos">
                  <i class="fa fa-upload" aria-hidden="true"></i>
                </button>                    
              </td>
              <td class="text-center" >
                <a type="button" class="btn btn-default btn-sm" href="{{ url ('/apartamentos') }}/{{$k}}" target="_blank" data-original-title="Enlace de Apartamento" data-toggle="tooltip">
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
          <div class="container-xs-height full-height">
            
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
        $('.upload-body').empty().append(data);
      });
    });
  
});
</script>
@endsection