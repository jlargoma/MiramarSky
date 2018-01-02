@extends('layouts.admin-master')

@section('title') Administrador de reservas MiramarSKI @endsection

@section('externalScripts') 

    <link href="/assets/plugins/jquery-datatable/media/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/plugins/jquery-datatable/extensions/FixedColumns/css/dataTables.fixedColumns.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/plugins/datatables-responsive/css/datatables.responsive.css" rel="stylesheet" type="text/css" media="screen" />

@endsection
    
@section('content') 
<div class="container-fluid padding-25 sm-padding-10">
    <div class="row">
        <div class="col-md-12 col-xs-12 text-center">
            <h2>Usuarios</h2>
        </div>
        <div class="col-md-8 table-responsive">
            <?php if (count($users) > 0): ?>
                
               
                <table class="table table-hover table-condensed ">
                    <thead>
                        <tr>
                            <th class ="text-center hidden" style="width: 25%;">id</th>
                            <th class ="text-center bg-complete text-white" style="width: 25%;">Nombre</th>
                            <th class ="text-center bg-complete text-white" style="width: 25%;">Telefono</th>
                            <th class ="text-center bg-complete text-white" style="width: 15%;"> Tipo</th>
                            <th class ="text-center bg-complete text-white" style="width: 35%;">Email</th>
                            <th class ="text-center bg-complete text-white" style="width: 15%;">pantalla inicio</th>
                            <th class ="text-center bg-complete text-white" style="width: 15%;">ACCIONES</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td class="text-center hidden" style="padding: 0px 5px;">
                                    <input type="text" name="<?php echo $user->id?>" value="<?php echo $user->id?>">
                                </td>
                                <td class="text-center " style="padding: 0px 5px;"><?php echo $user->name?>
                                </td>
                                <td class="text-center " style="padding: 0px 5px;"><?php echo $user->phone?>
                                </td>
                                <td class="text-center " style="padding: 0px 5px;">
                                    <?php echo $user->role?>
                                </td>
                                <td class="text-center ">
                                    <?php echo $user->email?>
                                </td>

                                <td class="text-center ">
                                    <select class="form-control minimal"  >
                                        <option>---</option>
                                    <?php $array = [
                                                'pendientes',
                                                'especiales',
                                                'confirmadas',
                                                'checkin',
                                                'checkout',
                                                ] 
                                    ?>
                                    <?php for ($i = 1; $i <= 4; $i++): ?>
                                        <option value="<?php echo $array[$i] ?>" <?php if($array[$i] == $user->defaultTable){ echo "selected";} ?>>
                                            <?php echo $array[$i] ?>
                                        </option>
                                    <?php endfor ?>
                                    </select>
                                </td>

                                <td class="text-center" style="padding: 0px 5px;">
                                    <div class="btn-group">
                                        <!--  -->
                                        <a class="btn btn-tag btn-complete update-user" type="button"  data-id="<?php echo $user->id ?>" data-toggle="modal" data-target="#myModal" title="Editar Usuario" >
                                        <!-- <a href="{{ url('/admin/usuarios/delete/')}}/<?php echo $user->id ?>" class="btn btn-tag btn-danger" type="button" data-toggle="tooltip" title="" data-original-title="Eliminar Usuario" onclick="return confirm('¿Quieres eliminar el usuario?');"> -->
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>

            <?php else: ?>
                
            <?php endif ?>
        </div>
        <div class="col-md-4">
            <div class="sm-m-l-5 sm-m-r-5">
                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingOne">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne" class="collapsed">
                                    Agregar usuario
                                </a>
                            </h4>
                        </div>
                        <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne" aria-expanded="false" style="height: 0px;">
                            <div class="panel-body">
                                <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="panel-title col-md-12">Agregar Usuario
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <form role="form"  action="{{ url('/admin/usuarios/create') }}" method="post">
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
                                                <i class="pg-phone"></i>
                                            </span>
                                            <input type="number" class="form-control" name="phone" placeholder="Telefono" required="" aria-required="true" aria-invalid="false">
                                        </div>
                                            <br> 
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="pg-plus_circle"></i>
                                            </span>
                                            <select class="full-width" data-init-plugin="select2" name="role">
                                                <option></option>
                                                <option value="admin">Admin</option>
                                                <option value="subadmin">subadmin</option>
                                                <option value="limpieza">Limpieza</option>
                                                <option value="agente">Agente</option>
                                                <option value="propietario">Propietario</option>
                                                <option value="recepcionista">Recepcionista</option>
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
                        </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form role="form">
  <div class="form-group form-group-default required" style="display: none">
    <label class="highlight">Message</label>
    <input type="text" hidden="" class="form-control notification-message" placeholder="Type your message here" value="This notification looks so perfect!" required>
  </div>
  <button class="btn btn-success show-notification hidden" id="boton">Show</button>
</form>


<div class="modal fade slide-up disable-scroll in" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content-wrapper">
      <div class="modal-content">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-50"></i>
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
    <script src="/assets/plugins/jquery-datatable/media/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="/assets/plugins/jquery-datatable/extensions/TableTools/js/dataTables.tableTools.min.js" type="text/javascript"></script>
    <script src="/assets/plugins/jquery-datatable/media/js/dataTables.bootstrap.js" type="text/javascript"></script>
    <script src="/assets/plugins/jquery-datatable/extensions/Bootstrap/jquery-datatable-bootstrap.js" type="text/javascript"></script>
    <script type="text/javascript" src="/assets/plugins/datatables-responsive/js/datatables.responsive.js"></script>
    <script type="text/javascript" src="/assets/plugins/datatables-responsive/js/lodash.min.js"></script>
  
    <script type="text/javascript">
        $(document).ready(function() {
            $('.update-user').click(function(event) {
                var id = $(this).attr('data-id');
                $.get('/admin/usuarios/update/'+id, function(data) {
                    $('.modal-body').empty().append(data);
                });
            });

            $('.editables').change(function(event) {
                var id = $(this).attr('data-id');

                var name = $('.name-user-'+id).val();
                var email = $('.email-user-'+id).val();

                $.get('/admin/usuarios/ajax', {  id: id, name: name, email: email}, function(data) {
                    alert(data);
                });
            });
        });
    </script>
@endsection