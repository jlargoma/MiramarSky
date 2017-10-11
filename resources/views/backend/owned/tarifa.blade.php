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
                    <a class="btn btn-danger btn text-white" href="{{ url('') }}/<?php echo str_replace('tarifas', 'operativa', Request::path()) ?>"  >Opertaiva</a>
                </div>
                <div class="col-md-3">
                    <a class="btn btn-danger btn text-white" href="{{ url('') }}" disabled>Tarifas</a>
                </div>
                <div class="col-md-3">
                    <a class="btn btn-danger btn text-white" href="{{ url('') }}/<?php echo str_replace('tarifas', 'descuentos', Request::path()) ?>">Descuentos</a>
                </div>
                <div class="col-md-3">
                    <a class="btn btn-danger btn text-white" href="{{ url('') }}/<?php echo str_replace('tarifas', 'fiscalidad', Request::path()) ?>">Fiscalidad</a>
                </div>
                
            </div> 
            <div class="col-md-1 pull-right">
                <div class="col-md-3 m-t-20">
                    
                    <a class="btn btn-danger btn text-white" href="{{ url('') }}/<?php echo str_replace('operativa', '', Request::path()) ?>">Volver</a>
                </div>
            </div>     
        </div>

        <div class="container ">
            <div align="center"><font size="10px" color="blue"><strong>Tarifas</strong></font></div>
            <div>
                <div class="col-md-6">
                    <img src="{{ asset('/img/miramarski/calendario 2017-2018.png') }}" style="max-width: 100%" />
                </div>
                <div class="col-md-6">
                    <img src="{{ asset('/img/miramarski/tarifas 2017-2018.png') }}" />
                    
                </div>
            </div>
            <div style="clear: both;"></div>
            <div class="m-t-20">
                <p style="font-size: 18px">
                    Con la finalidad de aumentar la ocupación en los días valle vamos a sacar una oferta de 3 x 2 días en noches de entre semana (de domingo a jueves) y siempre que no coincida con ningún puente o festivo de alta disponibilidad. <br><br>

                    Esta promoción no se realizará por defecto ni para todos los apartamentos, si no en función de cómo vaya la ocupación y del consentimiento de cada propietario:<br><br>

                    <input type="checkbox"><b>Autorizo a que se realice la oferta 3x2 en mi apartamento, siempre y cuando me informen previamente de las fechas en las que se realizará la promoción.</b>
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