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
        <div class="col-md-12 col-xs-12">
            <h2>Clientes</h2>
        </div>
        <div class="col-md-8 col-xs-12">
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
                        <th class ="text-center bg-complete text-white" >       Nombre      </th>
                        <th class ="text-center bg-complete text-white" >       Email       </th>
                        <th class ="text-center bg-complete text-white" >       Telefono    </th>                  
                        <th class ="text-center bg-complete text-white" style="width: 40%">       Comentarios </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($customers as $customer): ?>
                        <tr>
                            <td class="text-center font-montserrat" hidden><?php echo $customer->id ?></td>
                            <td class="text-center font-montserrat">
                               <input type="text" class="editables name-<?php echo $customer->id ?>" data-id="<?php echo $customer->id ?>" value="<?php  echo $customer->name?>" style="border-style: none none solid">
                            </td>
                            <td class="text-center font-montserrat">
                                <input type="text" class="editables email-<?php echo $customer->id ?>" data-id="<?php echo $customer->id ?>" value="<?php  echo $customer->email?>" style="border-style: none none solid">
                            </td>
                            <td class="text-center font-montserrat">
                                <input type="number" class="editables phone-<?php echo $customer->id ?>" data-id="<?php echo $customer->id ?>" value="<?php  echo $customer->phone?>" style="border-style: none none solid">
                               
                            </td>
                            <td class="text-center font-montserrat">
                                <input type="text" class="editables comments-<?php echo $customer->id ?>" data-id="<?php echo $customer->id ?>" value="<?php  echo $customer->comments?>" style="border-style: none none solid;width: 85%">
                                
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>

        <div class="col-md-4">
            <div class="sm-m-l-5 sm-m-r-5">
                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingOne">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne" class="collapsed">
                                    Agregar Cliente
                                </a>
                            </h4>
                        </div>
                        <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne" aria-expanded="false" style="height: 0px;">
                            <div class="panel-body">
                                <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="panel-title col-md-12">Agregar Cliente
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="col-md-12">
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
                        </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade slide-up disable-scroll in" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content-wrapper">
      <div class="modal-content">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-30"></i>
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
            $('.update-customer').click(function(event) {
                var id = $(this).attr('data-id');
                $.get('clientes/update/'+id, function(data) {
                    $('.modal-body').empty().append(data);
                });
            });

            $('.editables').change(function(event) {
                var id = $(this).attr('data-id');

                var name = $('.name-'+id).val();
                var email = $('.email-'+id).val();
                var phone = $('.phone-'+id).val();
                var comments = $('.comments-'+id).val();

                $.get('clientes/save', {  id: id, name: name, email: email, phone: phone, comments: comments}, function(data) {
                    alert(data);
                });
            });
        });
    </script>
@endsection