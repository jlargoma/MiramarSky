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

        <div class="col-md-6 col-xs-12 text-left push-30">
            <h2 class="font-w300" style="margin: 0">LISTADO DE <span class="font-w800">FACTURA</span></h2>
        </div>
        <div class="col-xs-12 bg-white">
            <div class="row">
                <div class="pull-right push-20">
                    <div class="col-xs-12">
                        <input type="text" id="search-table" class="form-control pull-right" placeholder="Buscar...">
                    </div>
                </div>
                <table class="table table-hover demo-table-search table-responsive-block" id="tableWithSearch">
                    <thead>
                        <tr>
                            <th class ="text-center bg-complete text-white">
                                F. Fact
                            </th>
                            <th class ="text-center bg-complete text-white" >
                                # Fact
                            </th>
                            <th class ="text-center bg-complete text-white" >
                                Apto
                            </th>
                            <th class ="text-center bg-complete text-white" >
                                Propietario
                            </th>
                            <th class ="text-center bg-complete text-white" >
                                Cliente
                            </th>
                            <th class ="text-center bg-complete text-white" >
                                Importe
                            </th> 
                            <th class ="text-center bg-complete text-white" >
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($books as $key => $book): ?>
                            <?php $num = $key + 1; ?>
                            <tr>
                                <td class="text-left font-s16" >
                                    <span class="hidden"><?php echo Carbon::CreateFromFormat('Y-m-d',$book->start)->format('U'); ?></span>
                                    <?php echo Carbon::CreateFromFormat('Y-m-d',$book->start)->formatLocalized('%d %B %Y'); ?>
                                </td>
                                <td class="text-center font-s16">
                                    <b>#<?php echo substr($book->room->nameRoom , 0,2)?>/<?php echo Carbon::CreateFromFormat('Y-m-d',$book->start)->format('Y'); ?>/<?php echo str_pad($num, 5, "0", STR_PAD_LEFT);  ?></b>
                                </td>
                                <td class="text-center font-s16">
                                    <b><?php echo $book->room->nameRoom ?></b>
                                </td>
                                <td class="text-center font-s16">
                                    <?php echo ucfirst($book->room->user->name) ?>
                                </td>
                                <td class="text-center font-s16">
                                    <b><?php echo ucfirst($book->customer->name) ?></b>
                                </td>
                                <td class="text-center font-s16">
                                    <b><?php echo number_format($book->total_price/2, 2, ',','.') ?>€</b>
                                </td>
                                <td class="text-center font-s16">
                                    <div class="btn-group">
                                        <a href="{{ url ('/admin/facturas/ver') }}/<?php echo base64_encode($book->id."-".$num) ?>" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i>
                                        </a>
                                        <a href="{{ url ('/admin/facturas/descargar') }}/<?php echo base64_encode($book->id."-".$num) ?>" class="btn btn-sm btn-success">    <i class="fa fa-download"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>

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
<script type="text/javascript" src="assets/plugins/datatables-responsive/js/datatables.responsive.js"></script>
 <script src="/assets/js/datatables.js" type="text/javascript"></script>
@endsection