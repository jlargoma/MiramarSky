<?php   use \Carbon\Carbon;
        setlocale(LC_TIME, "ES"); 
        setlocale(LC_TIME, "es_ES"); 
?>
@extends('layouts.admin-master')

@section('title') Administrador de reservas MiramarSKI @endsection

@section('externalScripts') 
<link href="/assets/plugins/jquery-datatable/media/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/plugins/jquery-datatable/extensions/FixedColumns/css/dataTables.fixedColumns.min.css" rel="stylesheet" type="text/css" />
<link href="/assets/plugins/datatables-responsive/css/datatables.responsive.css" rel="stylesheet" type="text/css" media="screen" />
<style type="text/css">
    td{
        padding: 8px!important;
    }
</style>
@endsection

@section('content')

    <div class="container-fluid padding-25 sm-padding-10 bg-white">
        <div class="container clearfix">
            <div class="row">

                <div class="col-md-2 col-xs-4 push-10">
                    <a class="text-white btn btn-md btn-primary {{ Request::path() == 'admin/facturas' ? 'active' : '' }}" href="{{ url('admin/facturas') }}">
                        FACTURAS
                    </a>

                </div>

                <div class="col-md-2 col-xs-4 push-10">
                    <a class="text-white btn btn-md btn-complete {{ Request::path() == 'admin/facturas/isde' ? 'active' : '' }}" href="{{ url('admin/facturas/isde') }}">
                        FACTURAS ISDE
                    </a>

                </div>
                <div class="col-md-2 col-xs-4 push-10">
                    <a class="btn btn-md btn-default {{ Request::path() == 'admin/facturas/solicitudes' ? 'active' : '' }}" href="{{ url('admin/facturas/solicitudes') }}">
                        SOLICITUDES FACTURAS ISDE
                    </a>

                </div>
            </div>

            <div class="col-md-6 col-xs-12 text-left">
                <?php $sum = 0;?>
                <?php foreach ($books as $key => $book): ?>
                    <?php $sum += ($book->total_price/2); ?>
                <?php endforeach ?>
                <h2 class="font-w300" style="margin: 0">LISTADO DE <span class="font-w800">FACTURA (<?php echo number_format($sum, 0, ',','.')?> €) </span></h2>
            </div>
            <div class="col-md-3 pull-right col-xs-12 text-left">
                <a href="{{ url('admin/facturas/descargar-todas') }}" class="text-white btn btn-md btn-primary">
                    Descargar Todas
                </a>
            </div>
            <div class="col-xs-12 bg-white">
                <div class="row">

                    <div class="col-md-3 pull-right push-20">
                        <div class="col-xs-12">
                            <input type="text" id="search-table" class="form-control pull-right" placeholder="Buscar...">
                        </div>
                    </div>
                    <div class="col-md-12 col-xs-12" id="table-customers" >
                        @include('backend.invoices._table', ['books' => $books])
                    </div>


                </div>

            </div>
        </div>
    </div>


@endsection

@section('scripts')
    <script>
        $('#search-table').keyup(function(event) {
            var searchString = $(this).val();;

            $.get('/admin/invoices/searchByName/'+searchString, function(data) {
                $('#table-customers').empty();
                $('#table-customers').append(data);
            });
        });



        $('.dni-customer').change(function () {

            var idCustomer = $(this).attr('idCustomer');
            var dni = $(this).val();
            $.get( "/admin/cliente/dni/"+ idCustomer +"/update/"+dni, function( data ) {
                console.log(data);
            });

        });
    </script>
@endsection