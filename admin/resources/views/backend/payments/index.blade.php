@extends('layouts.admin-master')

@section('title') Administrador de reservas MiramarSKI @endsection

@section('externalScripts') 

    <link href="assets/plugins/jquery-datatable/media/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/plugins/jquery-datatable/extensions/FixedColumns/css/dataTables.fixedColumns.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/plugins/datatables-responsive/css/datatables.responsive.css" rel="stylesheet" type="text/css" media="screen" />

    <link href="assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" type="text/css" media="screen">
    <link href="assets/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" media="screen">
    <link href="assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" media="screen">

@endsection
    
@section('content')

<div class="container-fluid padding-25 sm-padding-10">
    <div class="row">
        <div class="col-md-12">
            <div class="clearfix"></div>
                <table class="table table-hover demo-table-search table-responsive-block" id="tableWithSearch">

                    <thead>
                        <th class ="text-center bg-complete text-white"> Reserva    </th>
                        <th class ="text-center bg-complete text-white"> Total      </th>
                        <th class ="text-center bg-complete text-white"> Pagado     </th>
                        <th class ="text-center bg-complete text-white"> Pendiente  </th>
                    </thead>
                    <tbody>
                        <?php if (count($pagos) >0): ?>
                            
                            <?php foreach ($pagos as $pago): ?>
                                
                                <tr>
                                    
                                    <td><?php echo $pago->book_id ?></td>
                                    <td><?php echo $pago->book->total_price ?></td>
                                    <td><?php echo $pago->book_id ?></td>
                                    <td><?php echo $pago->book_id ?></td>

                                </tr> 

                            <?php endforeach ?>
                            
                        <?php endif ?>
                    </tbody>

                </table>

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

   <script src="assets/plugins/bootstrap3-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
   <script type="text/javascript" src="assets/plugins/jquery-autonumeric/autoNumeric.js"></script>
   <script type="text/javascript" src="assets/plugins/dropzone/dropzone.min.js"></script>
   <script type="text/javascript" src="assets/plugins/bootstrap-tag/bootstrap-tagsinput.min.js"></script>
   <script type="text/javascript" src="assets/plugins/jquery-inputmask/jquery.inputmask.min.js"></script>
   <script src="assets/plugins/bootstrap-form-wizard/js/jquery.bootstrap.wizard.min.js" type="text/javascript"></script>
   <script src="assets/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
   <script src="assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
   <script src="assets/plugins/summernote/js/summernote.min.js" type="text/javascript"></script>
   <script src="assets/plugins/moment/moment.min.js"></script>
   <script src="assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
   <script src="assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
   <script src="assets/plugins/bootstrap-typehead/typeahead.bundle.min.js"></script>
   <script src="assets/plugins/bootstrap-typehead/typeahead.jquery.min.js"></script>
   <script src="assets/plugins/handlebars/handlebars-v4.0.5.js"></script>



@endsection