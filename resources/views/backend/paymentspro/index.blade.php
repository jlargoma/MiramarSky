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
        <div class="col-md-12">
            <div class="clearfix"></div>
                <table class="table table-hover demo-table-search table-responsive-block" id="tableWithSearch">

                    <thead>
                        <th class ="text-center bg-complete text-white"> Habitacion    </th>
                        <th class ="text-center bg-complete text-white"> Tipo de apto.    </th>
                        <th class ="text-center bg-complete text-white"> Total a pagar      </th>
                        <th class ="text-center bg-complete text-white"> Total pagado    </th>
                        <th class ="text-center bg-complete text-white"> Total deuda  </th>
                    </thead>
                    <tbody>
                        <?php foreach ($rooms as $room): ?>
                          <tr>
                            <td class="text-center"><a class="update-payments" type="button"  data-id="<?php echo $room->id ?>" data-toggle="modal" data-target="#payments" title="Añadir pago" ><?php echo $room->nameRoom ?></a></td>
                            <td class="text-center"><?php echo $room->typeAptos->name ?></td>
                            <td class="text-center">
                              <?php if (isset($payments[$room->id])): ?>
                                <?php echo number_format($payments[$room->id],2,',','.')." €" ?>
                              <?php else: ?>
                                No hay reservas para este apartamento
                              <?php endif ?>
                            </td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                          </tr>
                          
                        <?php endforeach ?>
                    </tbody>

                </table>

        </div>
    </div>
</div>


<div class="modal fade slide-up disable-scroll in" id="payments" tabindex="-1" role="dialog" aria-hidden="true">
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




@endsection