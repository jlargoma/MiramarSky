@extends('layouts.admin-master')

@section('title') Administrador de reservas MiramarSKI @endsection

@section('externalScripts') 

    <link href="/assets/plugins/jquery-datatable/media/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/plugins/jquery-datatable/extensions/FixedColumns/css/dataTables.fixedColumns.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/plugins/datatables-responsive/css/datatables.responsive.css" rel="stylesheet" type="text/css" media="screen" />
  
    <link href="{{ asset('/pages/css/pages-icons.css') }}" rel="stylesheet" type="text/css">
    <link href="/assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" type="text/css" media="screen">
    <link href="/assets/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" media="screen">
    <link href="/assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" media="screen">
<style>
    .fileUpload {
        position: relative;
        overflow: hidden;
        margin: 10px;
    }
    .fileUpload input.upload {
        position: absolute;
        top: 0;
        right: 0;
        margin: 0;
        padding: 0;
        font-size: 20px;
        cursor: pointer;
        opacity: 0;
        filter: alpha(opacity=0);
    }
    .btn-file input[type=file] {
        position: absolute;
        top: 0;
        right: 0;
        min-width: 100%;
        min-height: 100%;
        font-size: 100px;
        text-align: right;
        filter: alpha(opacity=0);
        opacity: 0;
        outline: none;
        background: white;
        cursor: inherit;
        display: block;
    }
</style>
@endsection
    
@section('content')

<div class="container-fluid padding-25 sm-padding-10 table-responsive">
    <div class="row">
        <div class="col-md-12 text-center">
            <h2>Apartamentos</h2>
        </div>
        <div class="col-md-9 cols-xs-12 col-sm-12">
            <div class="pull-left">
              <div class="col-xs-12 ">
                <input type="text" id="search-tableRoom" class="form-control pull-right" placeholder="Buscar">
              </div>
            </div>
            <div class="clearfix"></div>
            <table class="table table-hover demo-table-search table-responsive " id="tableWithSearchRoom">
                <thead>
                    <tr>
                        <th class ="text-center hidden">                  ID            </th>
                        <th class ="text-center bg-complete text-white" style="width: 10%">  Nick        </th>
                        <th class ="text-center bg-complete text-white" style="width: 10%">  Piso          </th>
                        <th class ="text-center bg-complete text-white" style="width: 5%">  Ocu min </th>
                        <th class ="text-center bg-complete text-white" style="width: 5%">  Ocu max </th>
                        <th class ="text-center bg-complete text-white">  Tamaño          </th>
                        <th class ="text-center bg-complete text-white">  Lujo          </th>                        
                        <th class ="text-center bg-complete text-white">  Tipo        </th>
                        <th class ="text-center bg-complete text-white">  Prop   </th>
                        <th class ="text-center bg-complete text-white">  Orden   </th>
                        <th class ="text-center bg-complete text-white" style="width: 10%">  Estado </th>
                        <th class ="text-center bg-complete text-white" style="width: 5%;max-width: 5%">  Booking </th>
                        <th class ="text-center bg-complete text-white" style="width: 30%">  Btn </th>
                    </tr>
                </thead>
                <tbody>
                   <?php foreach ($rooms as $room): ?>
                      <tr>
                        <td class="text-center hidden"><?php echo $room->id?></td>
                        <td class="text-center">
                           <input class="name name-<?php echo $room->name?>" type="text" name="name" data-id="<?php echo $room->id ?>" value="<?php echo $room->name?>" style="width: 100%;text-align: center;border-style: none none ">
                        </td>
                        <td class="text-center">
                           <input class="nameRoom nameRoom-<?php echo $room->nameRoom?>" type="text" name="nameRoom" data-id="<?php echo $room->id ?>" value="<?php echo $room->nameRoom?>" style="width: 100%;text-align: center;border-style: none none ">
                        </td>
                        <td class="text-center">
                            <input class="editable minOcu-<?php echo $room->id?>" type="text" name="cost" data-id="<?php echo $room->id ?>" value="<?php echo $room->minOcu?>" style="width: 100%;text-align: center;border-style: none none ">
                        </td>  
                        <td class="text-center">
                           <input class="editable maxOcu-<?php echo $room->id?>" type="text" name="cost" data-id="<?php echo $room->id ?>" value="<?php echo $room->maxOcu?>" style="width: 100%;text-align: center;border-style: none none">
                        </td> 
                        <td class="text-center">
                           <select class="sizes form-control minimal" data-id="<?php echo $room->id ?>">
                             <?php foreach ($sizes as $size): ?>                                   
                                 <option value="<?php echo $size->id; ?>" <?php echo ($size->id == $room->sizeApto) ? "selected" : "" ?>>
                                     <?php echo $size->name ?>
                                 </option>
                             <?php endforeach ?>
                           </select>
                        </td>
                        <td class="text-center">
                           <span class="input-group-addon bg-transparent">
                                <input type="checkbox" class="editable" data-id="<?php echo $room->id ?>" name="luxury" data-init-plugin="switchery" data-size="small" data-color="primary" <?php echo ($room->luxury == 0) ? "" : "checked" ?>/>
                            </span>
                           
                        </td> 
                        <td class="text-center">
                          <select class="type form-control minimal" data-id="<?php echo $room->id ?>">
                            <?php foreach ($tipos as $tipo): ?>
                              <?php if( $tipo->id == $room->typeApto ){ $selected = "selected"; }else{$selected = "";} ?>
                                <option value="<?php echo $tipo->id; ?>" <?php echo $selected ?> >
                                    <?php echo $tipo->name ?>
                                </option>
                            <?php endforeach ?>
                          </select>
                        </td>
                        <td class="text-center">
                          <select class="owned form-control minimal" data-id="<?php echo $room->id ?>">
                            <?php foreach (\App\User::all() as $key => $owned): ?>
                              <?php if ( ($owned->role == 'propietario') || $owned->name == 'jorge'): ?>
                                  <?php if( $owned->name == $room->user->name ){ $selected = "selected"; }else{$selected = "";} ?>
                                  <option value="<?php echo $owned->id; ?>" <?php echo $selected ?> >
                                      <?php echo $owned->name ?>
                                  </option>
                              <?php endif ?>
                              
                            <?php endforeach ?>
                            <?php// echo $room->user->name;?>
                          </select>
                        </td> 
                        <td class="text-center">
                          <p style="display: none"><?php echo $room->order ?></p>
                           <input class="orden order-<?php echo $room->id?>" type="text" name="orden" data-id="<?php echo $room->id ?>" value="<?php echo $room->order?>" style="width: 100%;text-align: center;border-style: none none">
                        </td>             
                        <td class="text-center">
                          <span class="input-group-addon bg-transparent">
                              <input type="checkbox" class="estado" data-id="<?php echo $room->id ?>" name="state" data-init-plugin="switchery" data-size="small" data-color="success" <?php echo ($room->state == 0) ? "" : "checked" ?>> 
                          </span>
                        </td>
                        <td class="text-center">
                          <span class="input-group-addon bg-transparent">
                              <input type="checkbox" class="assingToBooking" data-id="<?php echo $room->id ?>" name="assingToBooking" data-init-plugin="switchery" data-size="small" data-color="danger" <?php echo ( $room->isAssingToBooking() ) ? "checked" : "" ?>> 
                          </span>
                        </td>
                        <td>
                          <a type="button" class="btn btn-default" href="{{ url ('/fotos') }}/<?php echo $room->nameRoom ?>" target="_blank" data-original-title="Enlace de Apartamento" data-toggle="tooltip">
                              <i class="fa fa-paperclip"></i>
                          </a>
                          <a type="button" class="btn btn-default" href="{{ url ('/admin/apartamentos/email') }}/<?php echo $room->nameRoom ?>" data-original-title="E-mail a Propietario" data-toggle="tooltip">
                            <i class=" pg-mail"></i>
                          </a>
                          <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalFiles" >
                            <i class="fa fa-save"></i>
                          </button>
                          
                        </td>
                      </tr>
                   <?php endforeach ?>
                </tbody>
            </table>
        </div>
        
        <div class="col-md-3 cols-xs-12 col-sm-12 " style="border:1px solid black;margin-top: 40px;">
            <div class="row">
                <div class="col-md-12">
                    <div class="sm-m-l-5 sm-m-r-5">
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingOne">
                                    <h4 class="panel-title">
                                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                            Crear Tamaño
                                        </a>
                                      </h4>
                                </div>
                                <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                                    <div class="panel-body">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <div class="panel-title col-md-12">Tamaño de  Apartamento
                                                </div>
                                            </div>
                                            <div class="panel-body">
                                                <div class="col-md-6">
                                                    <form role="form"  action="{{ url('/admin/apartamentos/create-size') }}" method="post">
                                                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                                        <div class="input-group transparent">
                                                            <span class="input-group-addon">
                                                                <i class="fa fa-user"></i>
                                                            </span>
                                                            <input type="text" class="form-control" name="name" placeholder="nombre" required="" aria-required="true" aria-invalid="false">
                                                        </div>
                                                            <br>
                                                        <div class="input-group">
                                                            <button class="btn btn-complete" type="submit">Guardar</button>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="col-md-6">
                                                    <?php foreach ($sizes as $size): ?>
                                                        <?php echo $size->name ?><br>
                                                    <?php endforeach ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingThree">
                                    <h4 class="panel-title">
                                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                            Crear Tipo de apartamento
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                                    <div class="panel-body">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <div class="panel-title col-md-12">Tipo de  Apartamento
                                                </div>
                                            </div>
                                            <div class="panel-body">
                                                <div class="col-md-6">
                                                    <form role="form"  action="{{ url('/admin/apartamentos/create-type') }}" method="post">
                                                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                                        <div class="input-group transparent">
                                                            <span class="input-group-addon">
                                                                <i class="fa fa-user"></i>
                                                            </span>
                                                            <input type="text" class="form-control" name="name" placeholder="nombre" required="" aria-required="true" aria-invalid="false">
                                                        </div>
                                                            <br>
                                                        <div class="input-group">
                                                            <button class="btn btn-complete" type="submit">Guardar</button>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="col-md-6">
                                                    <?php foreach ($types as $type): ?>
                                                        <?php echo $type->name ?><br>
                                                    <?php endforeach ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingThree">
                                    <h4 class="panel-title">
                                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                            Crear Apartamento
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                                    <div class="panel-body">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <div class="panel-title col-md-12">
                                                    Agregar Apartamento
                                                </div>
                                            </div>
                                            <form role="form"  action="{{ url('/admin/apartamentos/create') }}" method="post">
                                                <div class="panel-body">
                                                    <div class="col-md-12">
                                                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                                        <div>
                                                            <div class="input-group transparent">
                                                                <span class="input-group-addon">
                                                                    <i class="fa fa-user"></i>
                                                                </span>
                                                                <input type="text" class="form-control" name="name" placeholder="Nick" required="" aria-required="true" aria-invalid="false">
                                                            </div>
                                                                <br>
                                                            <div class="input-group transparent">
                                                                <span class="input-group-addon">
                                                                    <i class="pg-home"></i>
                                                                </span>
                                                                <input type="text" class="form-control" name="nameRoom" placeholder="Piso" required="" aria-required="true" aria-invalid="false">
                                                            </div>
                                                                <br>
                                                            <div class="input-group transparent">
                                                                <div class="col-md-6">
                                                                    <span class="input-group-addon">
                                                                        <i class="pg-minus_circle"></i>
                                                                    </span>
                                                                    <input type="text" class="form-control" name="minOcu" placeholder="Minima ocupacion" required="" aria-required="true" aria-invalid="false">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <span class="input-group-addon">
                                                                        <i class="pg-plus_circle"></i>
                                                                    </span>
                                                                    <input type="text" class="form-control" name="maxOcu" placeholder="Maxima ocupacion" required="" aria-required="true" aria-invalid="false">
                                                                </div>
                                                            </div>
                                                                <br>
                                                            <div class="input-group transparent" style="width: 45%">
                                                                
                                                            </div>
                                                                <br>
                                                            <div class="input-group">
                                                                <span class="input-group-addon">
                                                                    Propietario
                                                                </span>
                                                                <select class="full-width" data-init-plugin="select2" name="owner">
                                                                        <option></option>
                                                                    <?php foreach ($owners as $owner): ?>
                                                                         <option value="<?php echo $owner->id ?>"><?php echo $owner->name ?></option>
                                                                    <?php endforeach ?>
                                                                </select>
                                                            </div>
                                                                <br>
                                                            <div class="input-group">
                                                                <span class="input-group-addon">
                                                                    Tipo de apartamento
                                                                </span>
                                                                <select class="full-width" data-init-plugin="select2" name="type">
                                                                        <option></option>
                                                                    <?php foreach ($types as $type): ?>
                                                                         <option value="<?php echo $type->id ?>"><?php echo $type->name ?></option>
                                                                    <?php endforeach ?>
                                                                </select>
                                                            </div>
                                                                <br>
                                                            <div class="input-group">
                                                                <span class="input-group-addon">
                                                                    Tamaño de apartamento
                                                                </span>
                                                                <select class="full-width" data-init-plugin="select2" name="sizeRoom">
                                                                        <option></option>
                                                                    <?php foreach ($sizes as $size): ?>
                                                                         <option value="<?php echo $size->id ?>"><?php echo $size->name ?></option>
                                                                    <?php endforeach ?>
                                                                </select>
                                                            </div>
                                                                <br>
                                                            <div class="input-group">
                                                                <label class="inline">Lujo</label>
                                                                    <input type="checkbox" name="luxury" data-init-plugin="switchery" data-size="small" data-color="primary" checked="checked" />
                                                            </div>   
                                                        </div>
                                                            <br>
                                                        <div class="input-group">
                                                            <button class="btn btn-complete" type="submit">Guardar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>   
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                
                <div class="row" style="padding:20px">
                  <div class="col-md-4 col-md-offset-4">
                    <div class="input-group">
                        <label class="input-group-btn">
                            <span class="btn btn-success">
                                Archivo/s <input type="file" style="display: none;" multiple="">
                            </span>
                        </label>
                        <input type="text" class="form-control" readonly="">
                    </div>
                    <div class="input-group col-md-12 padding-10 text-center">
                        <button class="btn btn-complete bloquear" data-id="<?php echo $room->id ?>">Guardar</button>
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
    <script src="/assets/plugins/jquery-datatable/media/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="/assets/plugins/jquery-datatable/extensions/TableTools/js/dataTables.tableTools.min.js" type="text/javascript"></script>
    <script src="/assets/plugins/jquery-datatable/media/js/dataTables.bootstrap.js" type="text/javascript"></script>
    <script src="/assets/plugins/jquery-datatable/extensions/Bootstrap/jquery-datatable-bootstrap.js" type="text/javascript"></script>
    <script type="text/javascript" src="/assets/plugins/datatables-responsive/js/datatables.responsive.js"></script>
    <script type="text/javascript" src="/assets/plugins/datatables-responsive/js/lodash.min.js"></script>

    <script type="text/javascript" src="/assets/plugins/jquery-autonumeric/autoNumeric.js"></script>
    <script type="text/javascript" src="/assets/plugins/bootstrap-tag/bootstrap-tagsinput.min.js"></script>
    <script type="text/javascript" src="/assets/plugins/jquery-inputmask/jquery.inputmask.min.js"></script>
    <script src="/assets/plugins/bootstrap-form-wizard/js/jquery.bootstrap.wizard.min.js" type="text/javascript"></script>
    <script src="/assets/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
    <script src="/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
    <script src="/assets/plugins/summernote/js/summernote.min.js" type="text/javascript"></script>
    <script src="/assets/plugins/moment/moment.min.js"></script>
    <script src="/assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
    <script src="/assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
    <script src="/assets/plugins/bootstrap-typehead/typeahead.bundle.min.js"></script>
    <script src="/assets/plugins/bootstrap-typehead/typeahead.jquery.min.js"></script>
    <script src="/assets/plugins/handlebars/handlebars-v4.0.5.js"></script>

   <script type="text/javascript">

      function changeRooms(){
        $('.editable').change(function(event) {
            var id = $(this).attr('data-id');
            var luxury = $(this).is(':checked');
            
            if (luxury == true) {
                luxury = 1;
            }else{
                luxury = 0;
            }

            var minOcu = $('.minOcu-'+id).val();
            var maxOcu = $('.maxOcu-'+id).val();
            
            console.log(minOcu);
            $.get('/admin/apartamentos/update', {  id: id, luxury: luxury, maxOcu: maxOcu, minOcu: minOcu}, function(data) {
            });
        });

        $('.estado').change(function(event) {
            var id = $(this).attr('data-id');
            var state = $(this).is(':checked');
            
            if (state == true) {
                state = 1;
            }else{
                state = 0;
            }

            $.get('/admin/apartamentos/state', {  id: id, state: state}, function(data) {
                if (data == 0) {
                  alert('No se puede cambiar')
                  location.reload();
                }else{
                  location.reload();
                }
            });
        });

        $('.assingToBooking').change(function(event) {
            var id = $(this).attr('data-id');
            var assing = $(this).is(':checked');

            if (assing == true) {
                assing = 1;
            }else{
                assing = 0;
            }

            $.get('/admin/apartamentos/assingToBooking', {  id: id, assing: assing}, function(data) {

                alert(data);
                location.reload();
            });
        });


        $('.name').change(function(event) {
          var id = $(this).attr('data-id');
          var name = $(this).val();

          $.get('/admin/apartamentos/update-name', {  id: id, name: name}, function(data) {
              location.reload();
          });
        });

        $('.nameRoom').change(function(event) {
          var id = $(this).attr('data-id');
          var nameRoom = $(this).val();

          $.get('/admin/apartamentos/update-nameRoom', {  id: id, nameRoom: nameRoom}, function(data) {
              location.reload();
          });
        });


        $('.sizes').change(function(event) {
          var id = $(this).attr('data-id');
          var size = $(this).val();

          $.get('/admin/apartamentos/update-size', {  id: id, size: size}, function(data) {
              location.reload();
          });
        });
        $('.owned').change(function(event) {
          var id = $(this).attr('data-id');
          var owned = $(this).val();
          $.get('/admin/apartamentos/update-owned', {  id: id, owned: owned}, function(data) {
              location.reload();
          });
        });
        


        $('.type').change(function(event) {
          var id = $(this).attr('data-id');
          var tipo = $(this).val();

          $.get('/admin/apartamentos/update-type', {  id: id, tipo: tipo}, function(data) {
              location.reload();
          });

        });
        $('.orden').change(function(event) {
          var id = $(this).attr('data-id');
          var orden = $(this).val();

          $.get('/admin/apartamentos/update-order', {  id: id, orden: orden}, function(data) {
              location.reload();
          });

        });
       }
       

      $(document).ready(function() {
         changeRooms();
          $('.dataTables_paginate').click(function(event) {
            changeRooms();
          });
       });
   </script>
@endsection