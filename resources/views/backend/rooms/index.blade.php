@extends('layouts.admin-master')

@section('title') Administrador de reservas MiramarSKI @endsection

@section('externalScripts') 

    <link href="/assets/plugins/jquery-datatable/media/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/plugins/jquery-datatable/extensions/FixedColumns/css/dataTables.fixedColumns.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/plugins/datatables-responsive/css/datatables.responsive.css" rel="stylesheet" type="text/css" media="screen" />

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
</style>
@endsection
    
@section('content')

<div class="container-fluid padding-25 sm-padding-10">
    <div class="row">
        <div class="col-md-12 text-center">
            <h2>Apartamentos</h2>
        </div>
        <div class="col-md-8 cols-xs-12 col-sm-12">
            <div class="pull-left">
              <div class="col-xs-12 ">
                <input type="text" id="search-table" class="form-control pull-right" placeholder="Buscar">
              </div>
            </div>
            <div class="clearfix"></div>
            <table class="table table-hover demo-table-search table-responsive " id="tableWithSearch">
                <thead>
                    <tr>
                        <th class ="text-center hidden">                  ID            </th>
                        <th class ="text-center bg-complete text-white">  Nick        </th>
                        <th class ="text-center bg-complete text-white">  Nombre          </th>
                        <th class ="text-center bg-complete text-white">  Tamaño          </th>
                        <th class ="text-center bg-complete text-white">  Tipo        </th>
                        <th class ="text-center bg-complete text-white">  Propietario   </th>
                        <th class ="text-center bg-complete text-white">  Ocupacion min </th>
                        <th class ="text-center bg-complete text-white">  Ocupacion max </th>
                        <th class ="text-center bg-complete text-white">  Lujo          </th>                        
                        <th class ="text-center bg-complete text-white">  Editar        </th>
                    </tr>
                </thead>
                <tbody>
                   <?php foreach ($rooms as $room): ?>
                       <tr>
                           <td class="text-center hidden"><?php echo $room->id?></td>
                           <td class="text-center">
                               <?php echo $room->name?>
                           </td>
                           <td class="text-center">
                               <?php echo $room->nameRoom?>
                           </td>
                           <td class="text-center">
                               <?php echo $room->sizeRooms->name?>
                           </td>
                           <td class="text-center">
                              <select class="type" class="form-control" data-id="<?php echo $room->id ?>">
                                <?php foreach ($tipos as $tipo): ?>
                                  <?php if( $tipo->id == $room->typeApto ){ $selected = "selected"; }else{$selected = "";} ?>
                                    <option value="<?php echo $tipo->id; ?>" <?php echo $selected ?> >
                                        <?php echo $tipo->name ?>
                                    </option>
                                <?php endforeach ?>
                              </select>
                           </td>
                           <td class="text-center">
                               <?php echo $room->user->name?>
                           </td>    
                           <td class="text-center">
                                <input class="editable minOcu-<?php echo $room->id?>" type="text" name="cost" data-id="<?php echo $room->id ?>" value="<?php echo $room->minOcu?>" style="width: 100%;text-align: center;border-style: none none ">
                           </td>  
                           <td class="text-center">
                               <input class="editable maxOcu-<?php echo $room->id?>" type="text" name="cost" data-id="<?php echo $room->id ?>" value="<?php echo $room->maxOcu?>" style="width: 100%;text-align: center;border-style: none none">
                           </td> 
                           <td class="text-center">
                               <?php if ($room->luxury == 0): ?>
                                   <span class="input-group-addon bg-transparent">
                                        <input type="checkbox" class="editable" data-id="<?php echo $room->id ?>" name="luxury" data-init-plugin="switchery" data-size="small" data-color="primary" />
                                    </span>
                               <?php else: ?>
                                   <span class="input-group-addon bg-transparent">
                                        <input type="checkbox" class="editable" data-id="<?php echo $room->id ?>" name="luxury" data-init-plugin="switchery" data-size="small" data-color="primary" checked="checked" />
                                    </span>
                               <?php endif ?>
                               
                           </td>                      
                           <td class="text-center">
                               <div class="btn-group">
                                   <!--  -->
                                   <form action="apartamentos/uploadfile" method="post" >
                                        <input type="hidden" name="id" value="<?php echo $room->id ?>">
                                        <a class="fileUpload btn btn-tag btn-success">
                                            <i class="fa fa-file-image-o" aria-hidden="true"></i>
                                            <input type="file" class="upload" data-id="<?php echo $room->id ?>"/>
                                        </a>
                                   </form>
                                   
                                   <a href="{{ url('/admin/apartamentos/delete/')}}/<?php echo $room->id ?>" class="btn btn-tag btn-danger" type="button" data-toggle="tooltip" title="" data-original-title="Eliminar Apartamento" onclick="return confirm('¿Quieres eliminar el apartamento?');">
                                       <i class="fa fa-trash"></i>
                                   </a>                                     
                               </div>
                           </td>
                       </tr>
                   <?php endforeach ?>
                </tbody>
            </table>
        </div>
        
        <div class="col-md-4 cols-xs-12 col-sm-12" style="border:1px solid black">
            <div class="row">
                <div class="col-md-12">
                    <div class="sm-m-l-5 sm-m-r-5">
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingOne">
                                    <h4 class="panel-title">
                                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                            Tamaño
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
                                            Tipo de apartamento
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
                                            Apartamento
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
                                                                <input type="text" class="form-control" name="name" placeholder="Nombre" required="" aria-required="true" aria-invalid="false">
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

@endsection

@section('scripts')
    <script src="/assets/plugins/jquery-datatable/media/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="/assets/plugins/jquery-datatable/extensions/TableTools/js/dataTables.tableTools.min.js" type="text/javascript"></script>
    <script src="/assets/plugins/jquery-datatable/media/js/dataTables.bootstrap.js" type="text/javascript"></script>
    <script src="/assets/plugins/jquery-datatable/extensions/Bootstrap/jquery-datatable-bootstrap.js" type="text/javascript"></script>
    <script type="text/javascript" src="/assets/plugins/datatables-responsive/js/datatables.responsive.js"></script>
    <script type="text/javascript" src="/assets/plugins/datatables-responsive/js/lodash.min.js"></script>

    <script src="/assets/plugins/bootstrap3-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
    <script type="text/javascript" src="/assets/plugins/jquery-autonumeric/autoNumeric.js"></script>
    <script type="text/javascript" src="/assets/plugins/dropzone/dropzone.min.js"></script>
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
       
       $(document).ready(function() {
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
                    alert(data)
                });
            });

            $('.type').change(function(event) {
              var id = $(this).attr('data-id');
              var tipo = $(this).val();

              $.get('/admin/apartamentos/update-type', {  id: id, tipo: tipo}, function(data) {
                  alert(data)
                  location.reload();
              });

            });
       });
   </script>
@endsection