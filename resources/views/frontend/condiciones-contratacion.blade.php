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
                    <b>1. Hora de Entrada: La entrega de llaves la realizamos en el propio edifico entre las 17:00 a 19:30 Horas</b>
                    <br><br>
                    La entrega de llaves fuera de horario puede llevar gastos por el tiempo de espera.
                    <br><br>
                    10€ Si llegas entre 20:00 h de las 22.00
                    <br><br>
                    20€ Si llegas más tarde de de las 22 h
                    <br><br>
                    No se entregan llaves a partir de las 00.00 sin previo aviso (el dia anterior a la entrada)
                    <br><br>
                    El cargo se le abonan directamente en metálico a la persona que te entrega las llaves.
                    <br><br>
                    Nos sabe muy mal tener que cobrarte este recargo, Esperamos que entiendas que es solo para compensar el tiempo de espera de esta persona.<br><br>

                    <b>2. Hora de Salida: La vivienda deberá ser desocupada antes de las 11,59h A.M.</b> (de lo contrario se podrá cobrará una noche más de alquiler apartamento según tarifa apartamento y ocupación.<br><br>

                    La plaza de garaje debe quedar libre a esta hora o bien pagar la estancia de un nuevo día. (según tarifa 20€ / día.)<br><br>


                    <b>3. Fianza:</b> Además del precio del alquiler el día de llegada se pedirá una fianza por el importe de 300€ a la entrega de llaves para garantizar el buen uso de la vivienda.<br><br>

                    La fianza se devolverá a la entregada de llaves, una vez revisada la vivienda y descontados los gastos correspondientes a los desperfectos (en el caso de que se produzcan).<br><br>

                    <b>4. Señal de la reserva: Para realizar una reserva debe de abonar el 50% del importe total de la reserva.</b> En caso de cancelación la empresa tiene el derecho a no devolver la señal sea la cancelación por el motivo que sea, aunque intentaremos colocar tu estancia a otro huésped y por supuesto te devolveremos tu señal si lo conseguimos.<br><br>

                    <b>El segundo pago se hará 15 días antes de la entrada</b>, si este último pago no se realizase la reserva quedaría anulada.<br><br>


                    <b>5. Periodo del alquiler:</b> Por el motivo que sea si la persona que alquila decide marcharse antes del periodo contratado no tiene derecho a devolución del importe de los días no disfrutados.


                    <b>6. Meteorología y estado de pistas:</b> Las condiciones del alquiler de la vivienda son
                    completamente ajenas a las condiciones meteorológicas, al estado de las carreteras, al estado de las pistas de esquí, falta de nieve o incluso al cierre de la estación por lo que tampoco se podrá reclamar devolución por estos motivos.<br><br>


                    <b>7. Nº de personas:</b> El apartamento no podrá ser habitado por más personas de las camas que
                    dispone.<br><br>


                    <b>8. No se admiten animales.</b><br><br>


                    <b>9. Sabanas y Toallas están incluidas en todas las reservas.</b><br><br>


                    <b>10.</b> A partir de las 23 horas se ruega guardar silencio en los apartamentos y en las zonas
                    comunes, por respeto al sueño y a la tranquilidad de los demás inquilinos y propietarios del edificio Miramarski.<br><br>


                    <b>11. Condiciones del apartamento en el Checkout:</b> El alojamiento se deberá entregar antes de las 12:00am con un estado de limpieza aceptable, Vajilla limpia y recogida, Muebles de cama en la misma posición que se entregaron, Sin basuras en el apartamento, Nevera vacía (sin comida sobrante), Edredones doblados en los armarios.<br><br>

                    Si algunos de estos requisitos no se cumplen podría conllevar la perdida de la fianza, total o parcialmente.<br><br>


                    <b>12. Cancelaciones y modificaciones:</b><br><br>

                    Para la cancelación de una reserva debe ponerse en contacto por email con nosotros <a
                            href="mailto:reservas@apartamentosierranevada.net">reservas@apartamentosierranevada.net</a>.

                    La política de cancelaciones es la siguiente:<br><br>

                    - Para cancelaciones antes de 60 días de la fecha de llegada, 0% de penalización sobre el importe total de la estancia.<br>

                    - En las cancelaciones de menos de 60 días, el apartamento se volverá a poner a la venta, en el caso de que se vuelva alquilar se le devolverá el dinero pagado y en el caso de que no, el huésped perderá la señal entregada.<br><br>

                    Cuanto más tiempo tengamos para recolocarlo, más garantía tendrás de recuperar tu señal<br><br>

                    En el caso de salida anticipada a la fecha prevista, el cliente queda obligado al pago de toda la estancia.<br><br>


                    <b>13. Fuerza mayor</b>
                    ISDE, S.L. no será responsable de ningún retraso u error en el funcionamiento o interrupción del servicio como resultado directo o indirecto de cualquier causa o circunstancia más allá de nuestro control.<br><br>

                    Esto incluye el fallo del equipo electrónico o mecánico, líneas de comunicación, teléfono, u otros problemas de conexión, virus informáticos, accesos no autorizados, robo, causas climatológicas, desastres naturales, huelgas u otro tipo de problemas laborales, guerras o restricciones gubernamentales.<br><br>

                    <b>14. Leyes aplicables.</b><br><br>
                    Este contrato está realizado en España, por lo que las leyes españolas son de aplicación. Las partes contractuales se someten a la jurisdicción y competencia de Granada, con la exclusión de las cortes de cualquier otro país.
                </p>
            </div>
    </section>

@endsection
@section('scripts')

@endsection