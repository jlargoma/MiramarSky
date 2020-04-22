@extends('layouts.master')

@section('content')

<meta name="description" content="Consultar Condiciones generales para el alquiler apartamento sierra nevada,condiciones de cancelación, check in y out" />
<meta name="keywords" content="condiciones generales,alquiler apartamento sierra nevada,condiciones de cancelación">

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
				<h1 class="center psuh-20">Condiciones generales del Alquiler</h1>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 center texto-aparamento" >
                                <p style="text-align:left"><span ><span style="font-family:Times New Roman,serif"><span style="font-size:medium"><span style="color:#000000"><span style="font-family:Arial,serif"><strong>1.</strong></span></span><span style="color:#000000"><span style="font-size:x-small"><strong>&nbsp;&nbsp;&nbsp; </strong></span></span><span style="color:#000000"><span style="font-family:Arial,serif"><u><strong>Hora de Entrada:</strong></u></span></span><span style="color:#000000"><span style="font-family:Arial,serif"><strong> La entrega de llaves la realizamos en el propio edifico entre las 17:30 a 19:30 Horas</strong></span></span></span></span></span></p>

<p style="text-align:left"><span ><span style="font-family:Times New Roman,serif"><span style="font-size:medium"><span style="color:#000000"><span style="font-family:Arial,serif">Si llegas </span></span><span style="color:#000000"><span style="font-family:Arial,serif"><u><strong>fuera de este horario te dejaremos las llaves en una caja de seguridad</strong></u></span></span><span style="color:#000000"><span style="font-family:Arial,serif"> y te mandaremos las instrucciones unos d&iacute;as antes&nbsp;</span></span></span></span></span></p>

<p style="text-align:left"><br />
<span ><span style="font-family:Times New Roman,serif"><span style="font-size:medium"><span style="color:#000000"><span style="font-family:Arial,serif"><strong>2. </strong></span></span><span style="color:#000000"><span style="font-family:Arial,serif"><u><strong>Hora de Salida</strong></u></span></span><span style="color:#000000"><span style="font-family:Arial,serif"><strong>: La vivienda deber&aacute; ser desocupada antes de las 11,59h A.M.</strong></span></span><span style="color:#000000"><span style="font-family:Arial,serif">&nbsp;(de lo contrario se podr&aacute; cobrar&aacute; una noche m&aacute;s de alquiler apartamento seg&uacute;n tarifa apartamento y ocupaci&oacute;n.<br />
<br />
La plaza de garaje debe quedar libre a esta hora o bien pagar la estancia de un nuevo d&iacute;a. (seg&uacute;n tarifa 20&euro; / d&iacute;a.)</span></span><br />
<br />
<span style="color:#000000"><span style="font-family:Arial,serif"><strong>3. </strong></span></span><span style="color:#000000"><span style="font-family:Arial,serif"><u><strong>Fianza:</strong></u></span></span><span style="color:#000000"><span style="font-family:Arial,serif">&nbsp;Adem&aacute;s del precio del alquiler el d&iacute;a de llegada se pedir&aacute; una fianza por el importe de 300&euro; a la entrega de llaves para garantizar el buen uso de la vivienda.<br />
<br />
La fianza se devolver&aacute; a la entregada de llaves, una vez revisada la vivienda y descontados los gastos correspondientes a los desperfectos (en el caso de que se produzcan).</span></span><br />
<br />
<span style="color:#000000"><span style="font-family:Arial,serif"><strong>4. </strong></span></span><span style="color:#000000"><span style="font-family:Arial,serif"><u><strong>Se&ntilde;al de la reserva:</strong></u></span></span><span style="color:#000000"><span style="font-family:Arial,serif"><strong> Para realizar una reserva debe de abonar el 50% del importe total de la reserva.</strong></span></span><span style="color:#000000"><span style="font-family:Arial,serif">&nbsp;</span></span></span></span></span></p>

<p style="text-align:left"><span ><span style="font-family:Times New Roman,serif"><span style="font-size:medium"><span style="color:#000000"><span style="font-family:Arial,serif">En caso de cancelaci&oacute;n la empresa tiene el derecho a no devolver la se&ntilde;al sea la cancelaci&oacute;n por el motivo que sea, aunque por supuesto &nbsp;intentaremos colocar tu estancia a otro hu&eacute;sped y si es as&iacute; te devolveremos tu se&ntilde;al integramente.</span></span><br />
<br />
<span style="color:#000000"><span style="font-family:Arial,serif"><strong>El segundo pago se har&aacute; 15 d&iacute;as antes de la entrada</strong></span></span><span style="color:#000000"><span style="font-family:Arial,serif">, si este &uacute;ltimo pago no se realizase la reserva quedar&iacute;a anulada.</span></span><br />
<br />
<span style="color:#000000"><span style="font-family:Arial,serif"><strong>5. </strong></span></span><span style="color:#000000"><span style="font-family:Arial,serif"><u><strong>Periodo del alquiler</strong></u></span></span><span style="color:#000000"><span style="font-family:Arial,serif"><strong>:</strong></span></span><span style="color:#000000"><span style="font-family:Arial,serif">&nbsp;Por el motivo que sea si la persona que alquila decide marcharse antes del periodo contratado no tiene derecho a devoluci&oacute;n del importe de los d&iacute;as no disfrutados.</span></span><br />
<br />
<span style="color:#000000"><span style="font-family:Arial,serif"><strong>6. </strong></span></span><span style="color:#000000"><span style="font-family:Arial,serif"><u><strong>Meteorolog&iacute;a y estado de pistas:</strong></u></span></span><span style="color:#000000"><span style="font-family:Arial,serif">&nbsp;Las condiciones del alquiler de la vivienda son completamente ajenas a las condiciones meteorol&oacute;gicas, al estado de las carreteras, al estado de las pistas de esqu&iacute;, falta de nieve o incluso al cierre de la estaci&oacute;n por lo que tampoco se podr&aacute; reclamar devoluci&oacute;n por estos motivos.</span></span><br />
<br />
<span style="color:#000000"><span style="font-family:Arial,serif"><strong>7</strong></span></span><span style="color:#000000"><span style="font-family:Arial,serif"><u><strong>. N&ordm; de personas</strong></u></span></span><span style="color:#000000"><span style="font-family:Arial,serif"><strong>:</strong></span></span><span style="color:#000000"><span style="font-family:Arial,serif">&nbsp;El apartamento no podr&aacute; ser habitado por m&aacute;s personas de las camas que dispone.</span></span><br />
<br />
<span style="color:#000000"><span style="font-family:Arial,serif"><strong>8.</strong></span></span><span style="color:#000000"><span style="font-family:Arial,serif"><u><strong> No se admiten animales.</strong></u></span></span><br />
<br />
<span style="color:#000000"><span style="font-family:Arial,serif"><strong>9. Sabanas y Toallas est&aacute;n incluidas en todas las reservas.</strong></span></span><br />
<br />
<span style="color:#000000"><span style="font-family:Arial,serif"><strong>10.</strong></span></span><span style="color:#000000"><span style="font-family:Arial,serif">&nbsp;A partir de las 23 horas se ruega guardar silencio en los apartamentos y en las zonas comunes, por respeto al sue&ntilde;o y a la tranquilidad de los dem&aacute;s inquilinos y propietarios del edificio Miramarski.</span></span><br />
<br />
<span style="color:#000000"><span style="font-family:Arial,serif"><strong>11. </strong></span></span><span style="color:#000000"><span style="font-family:Arial,serif"><u><strong>Condiciones del apartamento en el Checkout:</strong></u></span></span><span style="color:#000000"><span style="font-family:Arial,serif">&nbsp;El alojamiento se deber&aacute; entregar antes de las 12:00am con un estado de limpieza aceptable, Vajilla limpia y recogida, Muebles de cama en la misma posici&oacute;n que se entregaron, Sin basuras en el apartamento, Nevera vac&iacute;a (sin comida sobrante), Edredones doblados en los armarios.<br />
<br />
Si algunos de estos requisitos no se cumplen podr&iacute;a conllevar la perdida de la fianza, total o parcialmente.</span></span><br />
<br />
<span style="color:#000000"><span style="font-family:Arial,serif"><strong>12. </strong></span></span><span style="color:#000000"><span style="font-family:Arial,serif"><u><strong>Cancelaciones y modificaciones</strong></u></span></span><span style="color:#000000"><span style="font-family:Arial,serif"><strong>:</strong></span></span><br />
<br />
<span style="color:#000000"><span style="font-family:Arial,serif">Para la cancelaci&oacute;n de una reserva debe ponerse en contacto por email con nosotros&nbsp;</span></span><span style="color:#0000ff"><u><a href="mailto:reservas@apartamentosierranevada.net" style="color:#0000ff" target="_blank"><span style="color:#3f51b5"><span style="font-family:Arial,serif">reservas@apartamentosierranevada.net</span></span></a></u></span><span style="color:#000000"><span style="font-family:Arial,serif">. La pol&iacute;tica de cancelaciones es la siguiente:<br />
<br />
- Para cancelaciones antes de 60 d&iacute;as de la fecha de llegada, 0% de penalizaci&oacute;n sobre el importe total de la estancia.</span></span></span></span></span></p>

<p style="text-align:left"><span ><span style="font-family:Times New Roman,serif"><span style="font-size:medium"><span style="color:#000000"><span style="font-family:Arial,serif">- En las cancelaciones de menos de 60 d&iacute;as, el apartamento se volver&aacute; a poner a la venta, en el caso de que se vuelva alquilar se le devolver&aacute; el dinero pagado y en el caso de que no, el hu&eacute;sped perder&aacute; la se&ntilde;al entregada.<br />
<br />
Cuanto m&aacute;s tiempo tengamos para recolocarlo, m&aacute;s garant&iacute;a tendr&aacute;s de recuperar tu se&ntilde;al<br />
<br />
En el caso de salida anticipada a la fecha prevista, el cliente queda obligado al pago de toda la estancia.</span></span><br />
<br />
<span style="color:#000000"><span style="font-family:Arial,serif"><strong>13. </strong></span></span><span style="color:#000000"><span style="font-family:Arial,serif"><u><strong>Causas de Fuerza mayor:</strong></u></span></span><span style="color:#000000"><span style="font-family:Arial,serif"><strong> </strong></span></span><span style="color:#000000"><span style="font-family:Arial,serif">&nbsp;IN-GEST SIERRA NEVADA S.L. no ser&aacute; responsable de ning&uacute;n retraso u error en el funcionamiento o interrupci&oacute;n del servicio como resultado directo o indirecto de cualquier causa o circunstancia m&aacute;s all&aacute; de nuestro control.<br />
<br />
Esto incluye el fallo del equipo electr&oacute;nico o mec&aacute;nico, l&iacute;neas de comunicaci&oacute;n, tel&eacute;fono, u otros problemas de conexi&oacute;n, virus inform&aacute;ticos, accesos no autorizados, robo, causas climatol&oacute;gicas, desastres naturales, huelgas u otro tipo de problemas laborales, guerras o restricciones gubernamentales.</span></span><br />
<br />
<span style="color:#000000"><span style="font-family:Arial,serif"><strong>14. </strong></span></span><span style="color:#000000"><span style="font-family:Arial,serif"><u><strong>Leyes aplicables</strong></u></span></span><span style="color:#000000"><span style="font-family:Arial,serif"><strong>.</strong></span></span><br />
<br />
<span style="color:#000000"><span style="font-family:Arial,serif">Este contrato est&aacute; realizado en Espa&ntilde;a, por lo que las leyes espa&ntilde;olas son de aplicaci&oacute;n. Las partes contractuales se someten a la jurisdicci&oacute;n y competencia de Granada, con la exclusi&oacute;n de las cortes de cualquier otro pa&iacute;s.</span></span></span></span></span></p>

<p>&nbsp;</p>

			</div>
	</section>
	
@endsection
@section('scripts')

@endsection