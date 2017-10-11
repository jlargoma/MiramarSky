@extends('layouts.admin-master')

@section('title') Administrador de reservas MiramarSKI @endsection

@section('externalScripts') 

    <link href="/assets/plugins/jquery-datatable/media/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/plugins/jquery-datatable/extensions/FixedColumns/css/dataTables.fixedColumns.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/plugins/datatables-responsive/css/datatables.responsive.css" rel="stylesheet" type="text/css" media="screen" />

@endsection
    
@section('content')
<?php use \Carbon\Carbon; 
setlocale(LC_TIME, "ES");
setlocale(LC_TIME, "es_ES");
?>


<div class="container-fluid padding-10 sm-padding-10">
    <div class="row">
        <div class="col-md-12 text-center">
            <div class="col-md-4 m-t-20">
                <div class="col-md-3">
                    <a class="btn btn-danger btn text-white" href="{{ url('') }}/<?php echo str_replace('descuentos', 'operativa', Request::path()) ?>"  >Opertaiva</a>
                </div>
                <div class="col-md-3">
                    <a class="btn btn-danger btn text-white" href="{{ url('') }}/<?php echo str_replace('descuentos', 'tarifas', Request::path()) ?>" >Tarifas</a>
                </div>
                <div class="col-md-3">
                    <a class="btn btn-danger btn text-white" href="{{ url('') }}" disabled>Descuentos</a>
                </div>
                <div class="col-md-3">
                    <a class="btn btn-danger btn text-white" href="{{ url('') }}/<?php echo str_replace('descuentos', 'fiscalidad', Request::path()) ?>">Fiscalidad</a>
                </div>
                
            </div>
            <div class="col-md-1 pull-right">
                <div class="col-md-3 m-t-20">
                    
                    <a class="btn btn-danger btn text-white" href="{{ url('') }}/<?php echo str_replace('operativa', '', Request::path()) ?>">Volver</a>
                </div>
            </div>        
        </div>

        <div class="row">
            <div align="center"><font size="10px" color="blue"><strong>DESCUENTOS EN FORTFAITS</strong></font></div>
            <br /><br /><br />
            <div class="container">
                <p>
                    *<strong><u> Descuentos en forfaits, clases de Esquí o alquiler de material</u></strong><br /><br />
                    Ponemos a vuestra disposición los descuentos que hemos negociado a un precio especial.

                    <br /><br />

                    Este es un servicio de pago directo al proveedor, nosotros solo queremos ayudaros a que estéis contentos

                    <br /><br />

                    Recuerda que al menos <u>necesitan 48 horas de antelación</u> para poder tramitártelo

                    <br /><br />

                    Rellena el formulario y se pondrán en contacto contigo para <a href="http://www.apartamentosierranevada.net/forfait"> gestionar tu petición</a>
                </p>
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
    <script src="/assets/plugins/moment/moment.min.js"></script>

@endsection