@extends('layouts.master_withoutslider')

@section('title') Apartamentosierranevada.net - Facturas @endsection



@section('content')
    <style type="text/css">
        label{
            color: #000000!important;
        }
        .sm-form-control::-webkit-input-placeholder { /* Chrome/Opera/Safari */
            color: #000000;
        }
        .sm-form-control::-moz-placeholder { /* Firefox 19+ */
            color: #000000;
        }
        .sm-form-control:-ms-input-placeholder { /* IE 10+ */
            color: #000000;
        }
        .sm-form-control:-moz-placeholder { /* Firefox 18- */
            color: #000000;
        }
        #primary-menu ul li  a{
            color: #3F51B5!important;
        }
        #primary-menu ul li  a div{
            text-align: left!important;
        }

        #content-form-book {
            padding: 40px 15px;
        }
    </style>

    <section id="slider" class="full-screen">
        <div class="slider-parallax-inner">

            <div class="container container-mobile vertical-middle center clearfix">

                <div class="col-md-12 col-xs-12 not-padding-mobile">
                    <div class="col-md-8 col-md-offset-2 col-xs-12" style="margin-bottom: 25px;">
                        <?php if (!isset($contacted)): ?>
                        <div class="col-xs-12" style="margin-top: 20px;">
                            <div class="heading-block center">
                                <h2 class="text-center" style="color: #3F51B5!important;">SOLICITUD DE FACTURA</h2>
                                <span>Rellena los datos y envianos la solicitud</span>
                            </div>
                            <div class="col-xs-12 not-padding-mobile">
                                <div class="col-xs-12 not-padding-mobile text-center">
                                    <div class="col-xs-12 not-padding" >

                                        <div class="col-xs-12" >
                                            <form  method="post" action="{{url('/facturas/solicitud')}}" class="form-horizontal">
                                                {{ csrf_field() }}
                                                <div class="col-md-4 col-xs-12 push-20">
                                                    <label for="name">Nombre</label>
                                                    <input type="text" id="name" name="name" class="sm-form-control" required placeholder="Nombre">
                                                </div>

                                                <div class="col-md-4 col-xs-12 push-20">
                                                    <label for="email">E-mail</label>

                                                    <input type="email" id="email" name="email"  class="email sm-form-control" required placeholder="Email">
                                                </div>

                                                <div class="col-md-4 col-xs-12 push-20">
                                                    <label for="date">Fecha (Aprox)</label>

                                                    <input type="text" id="date" name="date"  class="daterange1 sm-form-control" required>
                                                </div>

                                                <div class="col-md-4 col-xs-12 push-20">
                                                    <label for="nif">NIF/CIF/DNI</label>

                                                    <input type="text" id="nif" name="nif"  class="sm-form-control" required placeholder="NIF/CIF/DNI">
                                                </div>

                                                <div class="col-md-4 col-xs-12 push-20">
                                                    <label for="address">Dirección</label>

                                                    <input type="text" id="address" name="address" class="sm-form-control" required placeholder="Dirección">
                                                </div>

                                                <div class="col-md-4 col-xs-12 push-20">
                                                    <label for="phone">Teléfono</label>

                                                    <input type="text" id="phone" name="phone" maxlength="9" class="sm-form-control only-numbers" required placeholder="Teléfono">
                                                </div>



                                                <div class="col-xs-12 center">
                                                    <button class="button button-3d nomargin" type="submit">Enviar</button>
                                                </div>

                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php else: ?>
                            <?php if ($contacted == 1): ?>
                            <div class="col-padding black-cover">

                                <div class="heading-block center nobottomborder nobottommargin">
                                    <div class="col-xs-12 center white">
                                        <i class="white fa fa-check-circle-o fa-5x"></i>
                                    </div>
                                    <h2 class="white">Muchas gracias!</h2>
                                    <span class="white">Tu petición esta en proceso, te enviaremos un email con tu factura.</span>
                                </div>
                            </div>
                            <?php else: ?>
                            <div class="col-padding black-cover">
                                <div class="heading-block center nobottomborder nobottommargin">
                                    <div class="col-xs-12 center white">
                                        <i class="white fa fa-exclamation-circle fa-5x"></i>
                                    </div>
                                    <h2 class="white">Lo sentimos!</h2>
                                    <span class="white">Ha ocurrido algo inesperado, por favor intentalo de nuevo más tarde.</span>
                                </div>
                            </div>
                            <?php endif ?>
                        <?php endif ?>


                    </div>

                </div>
            </div>

        </div>
    </section>
@endsection

@section('scripts')
    <script type="text/javascript" src="{{asset('/frontend/js/components/moment.js')}}"></script>
    <script type="text/javascript" src="{{asset('/frontend/js/components/daterangepicker.js')}}"></script>
    <script type="text/javascript">
        /* Calendario */
        $(function() {
            $(".daterange1").daterangepicker({
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

        $(document).ready(function() {
            $(".only-numbers").keydown(function (e) {
                // Allow: backspace, delete, tab, escape, enter and .
                if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                    // Allow: Ctrl+A, Command+A
                    (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                    // Allow: home, end, left, right, down, up
                    (e.keyCode >= 35 && e.keyCode <= 40)) {
                    // let it happen, don't do anything
                    return;
                }
                // Ensure that it is a number and stop the keypress
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            });



        });
    </script>

@endsection