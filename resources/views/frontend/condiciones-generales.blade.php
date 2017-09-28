@extends('layouts.master_withoutslider')

@section('metadescription') Condiciones generales - apartamentosierranevada.net @endsection
@section('title')  Condiciones generales - apartamentosierranevada.net @endsection

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
				<h1 class="center psuh-20">Condiciones generales del Alquiler</h2>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 center" >
				<p class="texto-aparamento " style="text-align: justify;color:black;font-size:16px;padding:0px 30px" >

					1. <b>Hora de Entrada</b>: Desde las <b>17,00h a 19,00h</b> en el caso de llegar más tarde avisarán por teléfono y se incrementara en el alquiler de 10€ por la demora en recogida de llaves. <u>De 22,00h en adelante se cobrará 20€</u><br>

					<br>
					2. <b>Hora de Salida</b>: La vivienda deberá ser desocupada antes de las <b>11,59h a.m</b> (de lo contrario se podrá cobrará un noche más de alquiler apartamento según tarifa apartamento y ocupación. <br>
					<b>La plaza de garaje debe quedar libre a esta hora</b> o bien pagar la estancia de un nuevo día.(según tarifa 15€ / día.)<br>

					<br>
					3. <b>Fianza</b>: Además del precio del alquiler el día de llegada <b>se pedirá una fianza por el importe de 300€</b> a la entrega de llaves para garantizar el buen uso de la vivienda. <br>
					La fianza se devolverá a la entregada de llaves, una vez revisada la vivienda y descontados los gastos correspondientes a los desperfectos (en el caso de que se produzcan.)<br>

					<br>
					4. <b>Señal de la reserva</b>: Para realizar una reserva debe de abonar a la cuenta bancaria el <b>50% del importe total de la reserva.</b> <u>En caso de cancelación la señal no se devolverá, sea el motivo que sea</u>.

					<br>
					<br>
					5. <b>El resto del pago se realizará en metálico a la entrega de llaves</b> 
					
					<br>

					<br>

					6. <b>Periodo del alquiler</b>: Por el motivo que sea si la persona que alquila decide marcharse antes del periodo contratado no tiene derecho a devolución del importe de los días no disfrutados.<br>

					<br>
					7. <b>Fianza : A la entrega de llaves se deberá depositar 300€</b> como depósito,que serán devueltos en el mismo acto del checkout. 

					<br><br>
					8. <b>Meteorología y estado de pistas</b>: Las condiciones del alquiler de la vivienda son completamente ajenas a las condiciones meteorológicas, al estado de las carreteras, al estado de las pistas de esquí, falta de nieve o incluso al cierre de la estación por lo que tampoco se podrá reclamar devolución por estos motivos.<br>


					<br>
					9. <b>Nº de personas</b>: El apartamento no podrá ser habitado por más personas de las camas que dispone.<br>

					<br>
					10. <b>No se admiten animales.</b><br>

					<br>
					11.<b>Sabanas y Toallas están incluidas en todas las reservas</b><br>

					<br>
					12. <b>A partir de las 23 hrs se ruega guardar silencio en los apartamentos y en las zonas comunes</b>, por respeto al sueño y a la tranquilidad de los demás inquilinos y propietarios del edificio Miramarski.<br>

					<br>
					13. <b>Checkout : El alojamiento se deberá entregar antes de las 12:00 con : </b><br>
					<div class="col-xs-10 col-xs-offset-1">
						<ul>
							<li class="text-left">Estado de limpieza aceptable</li>
							<li class="text-left">Vajilla limpia y recogida</li>
							<li class="text-left">Muebles de cama en la misma posición que se entregaron</li>
							<li class="text-left">Sin basuras en el apartamento</li>
							<li class="text-left">Nevera vacia(sin comida sobrante).</li>
							<li class="text-left">Edredones doblados en los armarios.</li>
						</ul>
					</div>
					<div style="clear: both"></div>
					<h3><b>Si algunos de estos requisitos no se cumplen podría conllevar la perdida de la fianza, total o parcialmente.</b></h3>
					
					
				</p>
			</div>
	</section>
	
@endsection
@section('scripts')

@endsection