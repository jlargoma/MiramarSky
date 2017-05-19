@extends('layouts.admin-master')

@section('title') Administrador de reservas MiramarSKI @endsection

@section('externalScripts') 

    <link href="assets/plugins/jquery-datatable/media/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/plugins/jquery-datatable/extensions/FixedColumns/css/dataTables.fixedColumns.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/plugins/datatables-responsive/css/datatables.responsive.css" rel="stylesheet" type="text/css" media="screen" />
@endsection

@section('content')
<?php use \Carbon\Carbon; ?>

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
                        <th class ="text-center hidden bg-complete text-white">id          </th>
                        <th class ="text-center bg-complete text-white">       Nombre      </th>
                        <th class ="text-center bg-complete text-white">       Email       </th>
                        <th class ="text-center bg-complete text-white">       Telefono    </th>                  
                        <th class ="text-center bg-complete text-white">       Comentarios </th>                  
                        <th class ="text-center bg-complete text-white">       Editar      </th>

                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($customers as $customer): ?>
                        <tr>
                            <td class="text-center font-montserrat" hidden><?php echo $customer->id ?></td>
                            <td class="text-center font-montserrat">
                               <?php  echo $customer->name?>
                            </td>
                            <td class="text-center font-montserrat">
                               <?php  echo $customer->email?>
                            </td>
                            <td class="text-center font-montserrat">
                               <?php  echo $customer->phone?>
                            </td>
                            <td class="text-center font-montserrat">
                                <?php  echo $customer->comments?>
                            </td>
                            <td class="text-center font-montserrat">
                                    <div class="btn-group">
                                        <a href="{{ url('clientes/delete/')}}/<?php echo $customer->id ?>" class="btn btn-sm btn-danger font-montserrat" type="button" data-toggle="tooltip" title="" data-original-title="Eliminar Cliente" onclick="return confirm('¿Quieres eliminar el cliente?');">
                                            <i class="fa fa-times"></i>
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
                                <div class="panel-title">Agregar Cliente
                                </div>
                            </div>
                            <div class="panel-body">
                                <form role="form"  action="{{ url('clientes/create') }}" method="post">
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
                                            <i class="pg-mail"></i>
                                        </span>
                                            <input type="email" class="form-control" name="email" placeholder="email" required="" aria-required="true" aria-invalid="false">
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
                                            <i class="pg-comment"></i>
                                        </span>
                                            <input type="text" placeholder="Comentario" id="comment" name="comment" class="form-control">
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

@endsection

@section('scripts')
    <script src="assets/plugins/jquery-datatable/media/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-datatable/extensions/TableTools/js/dataTables.tableTools.min.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-datatable/media/js/dataTables.bootstrap.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-datatable/extensions/Bootstrap/jquery-datatable-bootstrap.js" type="text/javascript"></script>
    <script type="text/javascript" src="assets/plugins/datatables-responsive/js/datatables.responsive.js"></script>
    <script type="text/javascript" src="assets/plugins/datatables-responsive/js/lodash.min.js"></script>
    
@endsection