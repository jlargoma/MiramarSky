@extends('layouts.master')

@section('metadescription') Huesped - apartamentosierranevada.net @endsection
@section('title')  Huesped - apartamentosierranevada.net @endsection

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
				<h1 class="center psuh-20">Instrucciones para tu viaje</h2>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 center" >
				
				<p class="texto-aparamento " style="text-align: justify;color:black;font-size:16px;padding:0px 30px;" >					
					Hola, dentro de poco tienes la entrada en tu apartamento,te estamos esperando.	
				</p>
				<h2>Como Llegar (Ubicación GPS)</h2>

				<p class="texto-aparamento " style="text-align: justify;color:black;font-size:16px;padding:0px 30px;" >					
					Te mando in ubicación GPS para que para llegar al edificio MirarMarSki. Pincha <a class="map" href="https://www.google.com/maps?ll=37.093311,-3.396972&z=17&t=m&hl=es-ES&gl=ES&mapclient=embed&cid=335969053959651753" target="_blank">aqui</a>
				</p>

				<h2>Entrega de llaves y Check IN</h2>

				<p class="texto-aparamento " style="text-align: justify;color:black;font-size:16px;padding:0px 30px;" >					
					<b>*La entrega de llaves</b> la realizamos en el propio edifico entre las <b><u>17 - 19 Horas</u></b>.<br><br>

					*Si vas a llegar más tarde de esa hora por favor ponte en contacto con Jaime, la entrega de llaves fuera de horario puede llevar gastos por el tiempo de espera. (10€/hora).<br><br>

					* Las sábanas y toallas, están incluidas en tu reserva. <br><br>

					* <b>La piscina climatizada</b> está a tu disposición todos los días de 16 a 19.30 aprox.(salvo los martes cerrado por descanso).<br><br>

					*Llevaros gorros de baño.<br><br>

					* Unas horas antes de tu entrada encendemos la calefacción para que cuando tú llegues este todo confortable.<br><br>

					*Tienes a tu disposición unos <b><u>descuentos especiales en forfaits, clases o alquiler de material…</u></b>. rellena este formulario y págales directamente a ellos si te interesa. <br><br>

				</p>

				<h2>Descuentos en fortfaits,clases de Esquí o alguiler de material</h2>

				<p class="texto-aparamento " style="text-align: justify;color:black;font-size:16px;padding:0px 30px;" >					
					*Hemos negociado un precio especial por volumen para vosotros.<br><br>

					*Este es un servicio de pago directo al proveedor, nosotros solo queremos ayudaros a que estéis contentos. <br><br>

					*Recuerda que al menos necesitan 48 horas de antelación para poder gestionártelo.<br><br>

					*Rellena el formulario y se pondrán en contacto contigo para gestionar tu <a href="{{url ('/forfait') }}">petición</a>.<br><br>

				</p>

				<h2>Teléfonos de Contacto</h2>

				<p class="texto-aparamento " style="text-align: justify;color:black;font-size:16px;padding:0px 30px;" >					
					*Cuando llegues al edificio te recibirá Jaime <a href="tel:664352899">(664352899)</a> (él te llamará 1 ó 2 días antes de tu entrada….si no puedes hacerlo tu).<br><br>

					*Paco <a href="tel:601344919">(601344919)</a> (check out ).<br><br>

					*También te dejo el mío por si lo necesitases, Jorge <a href="tel:656828854">(656828854)</a>. <br><br>

				</p>

				<h2>Información de Carreteras</h2>

				<p class="texto-aparamento " style="text-align: justify;color:black;font-size:16px;padding:0px 30px;" >					
					* La Dirección General de Tráfico DGT te ayuda informarte sobre el estado de las carreteras de Sierra Nevada.<br><br>

					*Teléfonos: <a href="tel:060">(060)</a> / <a href="tel:900 123 505">(900 123 505)</a> / <a href="tel:958 156 911">(958 156 911)</a><br><br>

					*<u>Asegúrate si necesitarás cadenas</u>, si no las llevas la Guardia Civil no te dejará subir.<br><br>

				</p>

				<h2>Devolución de llaves(check out)</h2>

				<p class="texto-aparamento " style="text-align: justify;color:black;font-size:16px;padding:0px 30px;" >					
					<b>*La salida se realizará antes de las 12.00 am</b> de lo contrario se podrá cobrar un día a la tarifa que corresponda según temporada.<br><br>

					<b>*Para realizar el check out y proceder a la devolución de Fianza</b> llamar al teléfono de Cristina <a href="tel:677154966">(677154966)</a> con una antelación de una o dos horas.<br><br>

				</p>

				<h2>Condiciones generales alquiler</h2>

				<p class="texto-aparamento " style="text-align: justify;color:black;font-size:16px;padding:0px 30px;" >					
					<b>* Hora de Entrada</b>: Desde las <b>17,00h a 19,00h</b> en el caso de llegar más tarde avisarán por teléfono y se incrementará en el alquiler 10€ por la demora en recogida de llaves. De 22,00h en adelante se cobrará 20€<br><br>

					<b>* Hora de Salida</b>: La vivienda deberá ser desocupada antes de las <b>11,59h a.m</b> (de lo contrario se podrá cobrará un noche más de alquiler apartamento según tarifa apartamento y ocupación. La plaza de garaje debe quedar libre a esta hora o bien pagar la estancia de un nuevo día (Según tarifa 15€ / día.)<br><br>

					<b>* Fianza: se pedirá una fianza por el importe de 300€</b> a la entrega de llaves para garantizar el buen uso de la vivienda. La fianza se devolverá a la entregada de llaves, una vez revisada la vivienda y descontados los gastos correspondientes a los desperfectos (si se producen)<br><br>

					<b>* Señal de la reserva:</b> Para realizar una reserva debe de abonar a la cuenta bancaria el <b>25% del importe total de la reserva</b>. En caso de cancelación la señal no se devolverá, sea el motivo que sea.<br><br>

					<b>* Resto del Pago: Dos semanas antes de la entrada se deberá abonar al menos otro 50%</b> , pudiendo abonarse antes de la entrada el 25% restante más la fianza de 300€.<b>En el caso de NO cumplir con lo establecido no se podrá ocupar la vivienda.</b><br><br>

					<b>*Periodo del alquiler</b>: Por el motivo que sea si la persona que alquila decide marcharse antes del periodo contratado no tiene derecho a devolución del importe de los días no disfrutados.<br><br>

					<b>* Meteorología y estado de pistas</b>: Las condiciones del alquiler de la vivienda son completamente ajenas a las condiciones meteorológicas, al estado de las carreteras, al estado de las pistas de esquí, falta de nieve o incluso al cierre de la estación por lo que tampoco se podrá reclamar devolución por estos motivos.<br><br>

					<b>* Nº de personas:</b> El apartamento no podrá ser habitado por más personas de las camas que dispone, ni por más personas de las que has contratado con nosotros.<br><br>

					<b>* No se admiten animales:</b> Ningún tipo de animal de compañía ni mascotas.<br><br>

					<b>* Sabanas y Toallas <u>están incluidas</u> en tu reserva</b>.<br><br>

					<b>* Mantas y Almohadas</b> están disponibles en todos los apartamentos.<br><br>

				</p>
			</div>
	</section>
	
@endsection
@section('scripts')

@endsection