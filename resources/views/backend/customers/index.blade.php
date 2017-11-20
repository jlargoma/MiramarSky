@extends('layouts.admin-master')

@section('title') Administrador de reservas MiramarSKI @endsection

@section('externalScripts') 

    <link href="/assets/plugins/jquery-datatable/media/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/plugins/jquery-datatable/extensions/FixedColumns/css/dataTables.fixedColumns.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/plugins/datatables-responsive/css/datatables.responsive.css" rel="stylesheet" type="text/css" media="screen" />
@endsection

@section('content')

<div class="container-fluid padding-25 sm-padding-10 bg-white">
    <div class="container clearfix">
        <div class="col-md-6 col-xs-12 text-left push-30">
            <h2 class="font-w300" style="margin: 0">LISTADO DE <span class="font-w800">CLIENTES</span></h2>
        </div>

        <div class="col-md-2 col-xs-12 text-center pull-right push-30">
            <a class="btn btn-success btn-cons" href="{{ url('/admin/customers/importExcelData') }}">
                <i class="fa fa-file-excel-o" aria-hidden="true"></i> <span class="bold">Exportar excel</span>
            </a>
        </div>

        <div class="col-xs-12">
            <div class="pull-left push-20">
              <div class="col-xs-12 not-padding">
                <input type="text" id="search-table" class="form-control" placeholder="Buscar">
              </div>
            </div>
            <table class="table table-hover demo-table-search table-responsive-block" id="tableWithSearch">
                <thead>
                    <tr>
                        <th class ="text-center hidden bg-complete text-white">id          </th>
                        <th class ="text-center bg-complete text-white" >       Nombre      </th>
                        <th class ="text-center bg-complete text-white" >       Email       </th>
                        <th class ="text-center bg-complete text-white" >       Telefono    </th>
                        <th class ="text-center bg-complete text-white" >       DNI </th>
                        <th class ="text-center bg-complete text-white" >       Dirección </th>                  
                        <th class ="text-center bg-complete text-white">Acción</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($customers as $customer): ?>
                        <tr id="customer-<?php echo $customer->id ?>">
                            <td class="text-center font-s16 hidden" >
                                <?php echo $customer->id ?>
                            </td>
                            <td class="text-center font-s16">
                               <?php  echo $customer->name?>
                            </td>
                            <td class="text-center font-s16">
                                <?php  echo $customer->email?>
                            </td>
                            <td class="text-center font-s16">
                                <?php  echo $customer->phone?>
                            </td>
                            <td class="text-center font-s16">
                               <?php  echo $customer->DNI?>
                            </td>
                            <td class="text-center font-s16">
                               <?php  echo $customer->address?>
                            </td>
                            <td class="text-justify font-s16">
                                <button class="btn btn-danger btn-xs deleteCustomer" type="button" data-id="<?php echo $customer->id ?>">
                                   <i class="fa fa-close"></i>
                               </button>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
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
<div class="modal fade slide-up disable-scroll in" id="modalCreateCustomer" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content-wrapper">
      <div class="modal-content">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-30"></i>
        </button>
        <div class="container-xs-height full-height">
          <div class="row-xs-height">
            <div class="modal-body col-xs-height col-middle text-center   ">
                <div class="col-md-12">
                <form role="form"  action="{{ url('/admin/clientes/create') }}" method="post">
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

    <script src="/assets/js/notifications.js" type="text/javascript"></script>
    <script src="/assets/js/scripts.js" type="text/javascript"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('.update-customer').click(function(event) {
                var id = $(this).attr('data-id');
                $.get('/admin/clientes/update/'+id, function(data) {
                    $('.modal-body').empty().append(data);
                });
            });

            $('.deleteCustomer').click(function(event) {
                var id = $(this).attr('data-id');
                var line = "#customer-"+id;

                $.get('/admin/customer/delete/'+id, function(data) {
                    if (data == "ok") {
                        $(line).hide();
                    } else {
                        alert(data);
                    }
                });
            });
        });
    </script>
@endsection