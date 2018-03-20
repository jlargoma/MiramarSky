<?php   use \Carbon\Carbon;
setlocale(LC_TIME, "ES");
setlocale(LC_TIME, "es_ES");
?>
@extends('layouts.admin-master')

@section('title') Administrador de reservas MiramarSKI @endsection

@section('externalScripts')
    <link href="/assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" type="text/css" media="screen">
    <link rel="stylesheet" href="{{ asset('/frontend/css/components/daterangepicker.css')}}" type="text/css" />
    <style type="text/css">
        td{
            padding: 8px!important;
        }
        .daterangepicker{
            z-index: 10000!important;
        }
        .pg-close{
            font-size: 45px!important;
            color: white!important;
        }
        @media only screen and (max-width: 767px){
            .daterangepicker {
                left: 12%!important;
                top: 3%!important;
            }
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
                <h2 class="font-w300" style="margin: 0">DATOS DE LA  <span class="font-w800">FACTURA/SOLICITUD #<?php echo $invoice->id?> </span></h2>
            </div>

            <div class="col-xs-12 bg-white">
                <form action="{{ url('/admin/facturas/isde/saveUpdate') }}" method="post">
                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                    <input type="hidden" name="id" value="<?php echo $invoice->id; ?>">
                    <h3 class="font-w300 push-20" style="margin: 0"><span class="font-w800">CLIENTE</span></h3>
                    <div class="row">
                        <div class="col-md-4 col-xs-12 push-20">
                            <label for="">Nombre cliente</label>
                            <input type="text" name="name" class="form-control" value="<?php echo $invoice->name ?>">
                        </div>
                        <div class="col-md-4 col-xs-12 push-20">
                            <label for="">CIF/NIF/DNI/NIE cliente</label>
                            <input type="text" name="nif" class="form-control" value="<?php echo $invoice->nif ?>">
                        </div>
                        <div class="col-md-4 col-xs-12 push-20">
                            <label for="">Dirección cliente</label>
                            <input type="text" name="address" class="form-control" value="<?php echo $invoice->address ?>">
                        </div>
                        <div class="col-md-4 col-xs-12 push-20">
                            <label for="">Telefono cliente</label>
                            <input type="number" name="phone" class="form-control" value="<?php echo $invoice->phone ?>">
                        </div>
                        <div class="col-md-4 col-xs-12 push-20">
                            <label for="">C. postal cliente</label>
                            <input type="text" name="postalcode" class="form-control" value="<?php echo $invoice->postalcode ?>">
                        </div>
                    </div>

                    <h3 class="font-w300 push-20" style="margin: 0"><span class="font-w800">EMISOR</span></h3>
                    <div class="row">
                        <div class="col-md-4 col-xs-12 push-20">
                            <label for="">Nombre emisor</label>
                            <input type="text" name="name_business" class="form-control" value="<?php echo $invoice->name_business ?>">
                        </div>
                        <div class="col-md-4 col-xs-12 push-20">
                            <label for="">CIF/NIF/DNI/NIE emisor</label>
                            <input type="text" name="nif_business" class="form-control" value="<?php echo $invoice->nif_business ?>">
                        </div>
                        <div class="col-md-4 col-xs-12 push-20">
                            <label for="">Dirección emisor</label>
                            <input type="text" name="address_business" class="form-control" value="<?php echo $invoice->address_business ?>">
                        </div>
                        <div class="col-md-4 col-xs-12 push-20">
                            <label for="">Telefono emisor</label>
                            <input type="number" name="phone_business" class="form-control" value="<?php echo $invoice->phone_business ?>">
                        </div>
                        <div class="col-md-4 col-xs-12 push-20">
                            <label for="">C. postal emisor</label>
                            <input type="text" name="zip_code_business" class="form-control" value="<?php echo $invoice->zip_code_business ?>">
                        </div>
                    </div>
                    <h3 class="font-w300 push-20" style="margin: 0"><span class="font-w800">RESERVA</span></h3>
                    <div class="row">
                        <?php
                            $start1 = Carbon::createFromFormat('Y-m-d', $invoice->start)->format('d M, y');
                            $finish1 = Carbon::createFromFormat('Y-m-d', $invoice->finish)->format('d M, y');
                        ?>
                        <div class="col-md-4 col-xs-12 push-20">
                            <label for="">Fechas de la reserva</label>
                            <input type="text" name="fechas" class="form-control dateRange" id="dateRange" name="dateRange" required="" style="cursor: pointer; text-align: center;min-height: 28px; width: 85%;float: left;" readonly="" value="<?php echo $start1 ;?> - <?php echo $finish1 ?>">
                        </div>
                        <div class="col-md-2 col-xs-12 push-20">
                            <label for="">Total</label>
                            <input type="number" step="0.01" name="total_price" class="form-control" value="<?php echo $invoice->total_price ?>">
                        </div>
                        <div class="col-md-2 col-md-offset-1 col-xs-12 push-20">
                            <input type="radio" name="status" value="1" <?php if($invoice->status == 1 ){ echo 'checked="checked"'; }?>> ACEPTAR <br>
                            <input type="radio" name="status" value="0" <?php if($invoice->status == 0 ){ echo 'checked="checked"'; }?>> RECHAZAR
                        </div>
                    </div>
                    <div class="row text-center">
                        <button class="btn btn-complete font-s24 font-w400 padding-block" type="submit" style="min-height: 50px;">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection

@section('scripts')
    <script type="text/javascript" src="{{asset('/frontend/js/components/moment.js')}}"></script>
    <script type="text/javascript" src="{{asset('/frontend/js/components/daterangepicker.js')}}"></script>

<script>
    $(function() {
        $(".dateRange").daterangepicker({
            "buttonClasses": "button button-rounded button-mini nomargin",
            "applyClass": "button-color",
            "cancelClass": "button-light",
            locale: {
                format: 'DD MMM, YY',
                "applyLabel": "Aplicar",
                "cancelLabel": "Cancelar",
                "fromLabel": "From",
                "toLabel": "To",
                "customRangeLabel": "Custom",
                "daysOfWeek": [
                    "Do",
                    "Lu",
                    "Mar",
                    "Mi",
                    "Ju",
                    "Vi",
                    "Sa"
                ],
                "monthNames": [
                    "Enero",
                    "Febrero",
                    "Marzo",
                    "Abril",
                    "Mayo",
                    "Junio",
                    "Julio",
                    "Agosto",
                    "Septiembre",
                    "Octubre",
                    "Noviembre",
                    "Diciembre"
                ],
                "firstDay": 1,
            },

        });
    });
</script>
@endsection