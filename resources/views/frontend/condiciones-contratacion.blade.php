@extends('layouts.master_withoutslider')

@section('metadescription') Condiciones de contratación - apartamentosierranevada.net @endsection
@section('title')  Condiciones de contratación - apartamentosierranevada.net @endsection

@section('content')

    <style type="text/css">
        #primary-menu ul li  a{
            color: #3F51B5!important;
        }
        #primary-menu ul li  a div{
            text-align: left!important;
        }
        #content p {
            line-height: 1.2;
        }
        .fa-circle{
            font-size: 10px!important;
        }
        #contact-form input{
            color: black!important;
        }
        *::-webkit-input-placeholder {
            /* Google Chrome y Safari */
            color: rgba(0,0,0,0.85) !important;
        }
        *:-moz-placeholder {
            /* Firefox anterior a 19 */
            color: rgba(0,0,0,0.85) !important;
        }
        *::-moz-placeholder {
            /* Firefox 19 y superior */
            color: rgba(0,0,0,0.85) !important;
        }
        *:-ms-input-placeholder {
            /* Internet Explorer 10 y superior */
            color: rgba(0,0,0,0.85) !important;
        }
        @media (max-width: 768px){


            .container-mobile{
                padding: 0!important
            }
            #primary-menu{
                padding: 40px 15px 0 15px;
            }
            #primary-menu-trigger {
                color: #3F51B5!important;
                top: 5px!important;
                left: 5px!important;
                border: 2px solid #3F51B5!important;
            }
            .container-image-box img{
                height: 180px!important;
            }

            #content-form-book {
                padding: 0px 0 40px 0
            }
            .daterangepicker {
                top: 135%!important;
            }
            .img{
                max-height: 530px;
            }
            .button.button-desc.button-3d{
                background-color: #4cb53f!important;
            }

        }

    </style>
    <section id="content" style="margin-top: 15px">

        <div class="container container-mobile clearfix push-0">
            <div class="row">
                <h1 class="center psuh-20">Condiciones de contratación</h1>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 center" >
                <p class="texto-aparamento " style="text-align: justify;color:black;font-size:16px;padding:0px 30px" >
                    <b>1. Hora de Entrada:</b> Desde las 17,00h a 19,00h en el caso de llegar más tarde avisarán por teléfono y se incrementara en el alquiler de 10€ por la demora en recogida de llaves. De 22,00h en adelante se
                     cobrará 20€.<br><br>

                    <b>2. Hora de Salida:</b> La vivienda deberá ser desocupada antes de las 11,59h A.M. (de lo contrario se podrá cobrará una noche más de alquiler apartamento según tarifa apartamento y ocupación.
                    La plaza de garaje debe quedar libre a esta hora o bien pagar la estancia de un nuevo día. (según tarifa 15€ / día.)<br><br>

                    <b>3. Fianza:</b> Además del precio del alquiler el día de llegada se pedirá una fianza por el importe de 300€ a la entrega de llaves para garantizar el buen uso de la vivienda.
                    La fianza se devolverá a la entregada de llaves, una vez revisada la vivienda y descontados los gastos correspondientes a los desperfectos (en el caso de que se produzcan).<br><br>

                    <b>4. Señal de la reserva:</b> Para realizar una reserva debe de abonar por transferencia o con tarjeta bancaria el 50% del importe total de la reserva. En caso de cancelación la señal no se devolverá, sea el motivo que sea. El resto del pago se hará 15 días antes de la entrada, si este último pago no se realizase la reserva quedaría anulada.<br><br>

                    <b>5. Periodo del alquiler:</b> Por el motivo que sea si la persona que alquila decide marcharse antes del periodo contratado no tiene derecho a devolución del importe de los días no disfrutados.<br><br>

                    <b>6. Meteorología y estado de pistas:</b> Las condiciones del alquiler de la vivienda son completamente ajenas a las condiciones meteorológicas, al estado de las carreteras, al estado de las pistas de esquí, falta de nieve o incluso al cierre de la estación por lo que tampoco se podrá reclamar devolución por estos motivos.<br><br>

                    <b>7. Nº de personas:</b> El apartamento no podrá ser habitado por más personas de las camas que dispone.<br><br>

                    <b>8.</b> No se admiten animales.<br><br>

                    <b>9.</b> Sabanas y Toallas están incluidas en todas las reservas.<br><br>

                    <b>10.</b> A partir de las 23 horas se ruega guardar silencio en los apartamentos y en las zonas comunes, por respeto al sueño y a la tranquilidad de los demás inquilinos y propietarios del edificio Miramarski.<br><br>

                    <b>11. Checkout:</b> El alojamiento se deberá entregar antes de las 12:00 con:
                    Estado de limpieza aceptable, Vajilla limpia y recogida, Muebles de cama en la misma posición que se entregaron, Sin basuras en el apartamento, Nevera vacía (sin comida sobrante), Edredones doblados en los armarios.
                    Si algunos de estos requisitos no se cumplen podría conllevar la perdida de la fianza, total o parcialmente.<br><br>

                    <b>12. Cancelaciones y modificaciones:</b>
                    Para la cancelación de una reserva debe ponerse en contacto por email con nosotros <a
                    href="mailto:reservas@apartamentosierranevada.net">reservas@apartamentosierranevada.net</a>.<br>
                    La política de cancelaciones es la siguiente:<br><br>
                    - Para cancelaciones antes de 46 días de la fecha de llegada, 0% de penalización sobre el importe total de la estancia.<br>
                    - Para cancelaciones de 20 días o menos de la fecha de llegada, el apartamento se volverá a poner a la venta, en el caso de que se vuelva alquilar se le devolverá el dinero pagado y en el caso de que no, perderá lo pagado.<br>
                    En el caso de salida anticipada a la fecha prevista, el cliente queda obligado al pago de toda la estancia.<br><br>


                    <b>13. Fuerza mayor</b><br><br>
                    ISDE, S.L. no será responsable de ningún retraso u error en el funcionamiento o interrupción del servicio como resultado directo o indirecto de cualquier causa o circunstancia más allá de nuestro control. Esto incluye el fallo del equipo electrónico o mecánico, líneas de comunicación, teléfono, u otros problemas de conexión, virus informáticos, accesos no autorizados, robo, causas climatológicas, desastres naturales, huelgas u otro tipo de problemas laborales, guerras o restricciones gubernamentales.<br><br>

                    <b>14. Leyes aplicables.</b><br><br>
                    Este contrato está realizado en España, por lo que las leyes españolas son de aplicación. Las partes contractuales se someten a la jurisdicción y competencia de Granada, con la exclusión de las cortes de cualquier otro país.
                </p>
            </div>
    </section>

@endsection
@section('scripts')

@endsection