@extends('layouts.admin-master')

@section('title') Administrador de reservas MiramarSKI @endsection

@section('externalScripts') 

    <link href="assets/plugins/jquery-datatable/media/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/plugins/jquery-datatable/extensions/FixedColumns/css/dataTables.fixedColumns.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/plugins/datatables-responsive/css/datatables.responsive.css" rel="stylesheet" type="text/css" media="screen" />

@endsection
    
@section('content')


<div class="container-fluid padding-25 sm-padding-10">
    <div class="row">

        <div class="col-md-8">
            <div class="pull-right">
              <div class="col-xs-12 ">
                <input type="text" id="search-table" class="form-control pull-right" placeholder="Buscar">
              </div>
            </div>
            <div class="clearfix"></div>
            <table class="table table-hover demo-table-search table-responsive-block" id="tableWithSearch">
                <thead>
                    <tr>
                        <th class ="text-center hidden" style="width: 25%">id</th>
                        <th class ="text-center bg-complete text-white" style="width: 25%">Nombre</th>
                        <th class ="text-center bg-complete text-white" style="width: 15%"> Tipo</th>
                        <th class ="text-center bg-complete text-white" style="width: 35%">Email</th>
                        <th class ="text-center bg-complete text-white" style="width: 25%">Editar</th>

                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td class="text-center hidden">
                                <input type="text" name="<?php echo $user->id?>" value="<?php echo $user->id?>">
                            </td>
                            <td class="text-center "><?php echo $user->name?>
                            </td>
                            <td class="text-center ">
                                <?php echo $user->role?>
                            </td>
                            <td class="text-center ">
                                <?php echo $user->email?>
                            </td>

                            <td class="text-center">
                                <div class="btn-group">
                                    <!--  -->
                                    <a href="{{ url('usuarios/delete/')}}/<?php echo $user->id ?>" class="btn btn-tag btn-danger" type="button" data-toggle="tooltip" title="" data-original-title="Eliminar Usuario" onclick="return confirm('¿Quieres eliminar el usuario?');">
                                        <i class="fa fa-times"></i>
                                    </a>
                                    <a class="btn btn-tag btn-warning update-user" type="button"  data-id="<?php echo $user->id ?>" data-toggle="modal" data-target="#modalSlideLeft" title="" data-original-title="Editar Usuario" >
                                        <i class="fa fa-edit"></i>
                                    </a>
                                  
                                </div>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
        <div class="col-md-4">
            <div class="container-fluid container-fixed-lg">
                <div class="row">
                    <div class="col-md-4">
                        <!-- START PANEL -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="panel-title">Agregar Usuario
                                </div>
                            </div>
                            <div class="panel-body">
                                <form role="form"  action="{{ url('usuarios/create') }}" method="post">
                                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                    <div class="input-group transparent">
                                        <span class="input-group-addon">
                                            <i class="fa fa-user"></i>
                                        </span>
                                        <input type="text" class="form-control" name="name" placeholder="Nombre" required="" aria-required="true" aria-invalid="false">
                                    </div>
                                        <br>
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="pg-plus_circle"></i>
                                        </span>
                                        <select class="full-width" data-init-plugin="select2" name="role">
                                            <option></option>
                                            <option value="Admin">Admin</option>
                                            <option value="Jaime">Jaime</option>
                                            <option value="Limpieza">Limpieza</option>
                                            <option value="Agente">Agente</option>
                                            <option value="Propietario">Propietario</option>
                                        </select>
                                    </div>
                                        <br>
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="fa fa-key"></i>
                                        </span>
                                            <input type="password" class="form-control" name="password"  required="" aria-required="true" aria-invalid="false">
                                    </div>
                                        <br>
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="pg-mail"></i>
                                        </span>
                                            <input type="email" class="form-control" name="email" placeholder="Email" required="" aria-required="true" aria-invalid="false">
                                    </div>
                                        <br>
                                    <div class="input-group">
                                        <button class="btn btn-complete" type="submit">Guardar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- END PANEL -->
                    </div>
                        <!-- END PANEL -->      
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade slide-right" id="modalSlideLeft" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content-wrapper">
      <div class="modal-content">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i>
        </button>
        <div class="container-xs-height full-height">
          <div class="row-xs-height">
            <div class="modal-body col-xs-height col-middle text-center   ">

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
    <script src="assets/plugins/jquery-datatable/media/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-datatable/extensions/TableTools/js/dataTables.tableTools.min.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-datatable/media/js/dataTables.bootstrap.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-datatable/extensions/Bootstrap/jquery-datatable-bootstrap.js" type="text/javascript"></script>
    <script type="text/javascript" src="assets/plugins/datatables-responsive/js/datatables.responsive.js"></script>
    <script type="text/javascript" src="assets/plugins/datatables-responsive/js/lodash.min.js"></script>
  
    <script type="text/javascript">
        $(document).ready(function() {
            $('.update-user').click(function(event) {
                var id = $(this).attr('data-id');
                $.get('usuarios/update/'+id, function(data) {
                    $('.modal-body').empty().append(data);
                });
            });

            $('.editables').change(function(event) {
                var id = $(this).attr('data-id');

                var name = $('.name-user-'+id).val();
                var email = $('.email-user-'+id).val();

                $.get('usuarios/ajax', {  id: id, name: name, email: email}, function(data) {
                    alert(data);
                });
            });
        });
    </script>
@endsection