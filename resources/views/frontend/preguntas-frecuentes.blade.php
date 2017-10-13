@extends('layouts.master_withoutslider')

@section('metadescription') Preguntas Frecuentes - apartamentosierranevada.net @endsection
@section('title')  Preguntas Frecuentes - apartamentosierranevada.net @endsection

<meta name="description" content="Aquí encontraras las faq´s, las respuestas a las preguntas frecuentes de nuestros clientes para alquilar su apartamento en sierra nevada, información sobre el pago,condiones alquiler" />

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
				<h1 class="center psuh-20">Preguntas Frecuentes</h2>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 center" >
				<h2>¿Es seguro pagar con tarjeta en <a href="www.apartamentosierranevada.net">apartamentosierranevada</a>?</h2>
				
				<p class="texto-aparamento " style="text-align: justify;color:black;font-size:16px;padding:0px 30px" >
					

					El sistema de pago online con tarjeta de crédito que ofrecemos el más seguro que existe actualmente en Internet, utilizamos esta plataforma <a href="https://stripe.com/about">https://stripe.com/about</a> <br><br>
					
					En apartamentosierranevada.net utilizamos un certificado SSL (Secure Sockets Layer ), que es un protocolo de seguridad en Internet, empleado por los servidores para transmitir información sensible con toda seguridad. <br><br>
					
					<b>(el usuario puedo comprobar esto mirando todas nuestras url´s, ya que en lugar de http, visualizará https.)</b><br><br>

					Cuando realizas un pago mediante tarjeta de crédito, lo haces a través de una "pasarela de pago" completamente segura,  Stripe es la mejor forma de aceptar pagos a través de páginas web y aplicaciones móviles. Operan con miles de millones de euros cada año dando soporte a los modelos de negocio más innovadores en todo el mundo.<br><br>

					De esta manera los datos de tu tarjeta de crédito sólo son conocidos y disponibles Stripe Ni nosotros ni nadie más tiene acceso a dichos datos. 

				</p>
					
				<h2>¿Cómo reservar?</h2>
				
				<p class="texto-aparamento " style="text-align: justify;color:black;font-size:16px;padding:0px 30px" >
				
					Para alquilar un apartamento en Sierra Nevada con <a href="{{ url('/') }}">www.apartamentosierranevada.net</a> debes seguir 3 sencillos pasos. <br><br>

				
					<b>1º)Selecciona el apartamento o estudio que se ajuste a tus necesidades.</b> Puedes elegir entre:<br>
				
				</p>
				
				<ul style="text-align: justify;color:black;font-size:16px;padding:0px 45px">
					<li>Estudios para 4 personas.</li>
					<li>Apartamentos con dos dormitorios (ocupación máxima 8 personas).</li>
					<li>Apartamentos de 3 dormitorios.</li>
				</ul>

				<p class="texto-aparamento " style="text-align: justify;color:black;font-size:16px;padding:0px 30px" >
					Además podrás elegir entre la <u>opción estándar</u> o <u>de Lujo</u>, la superficie útil es la misma pero estos últimos están recién reformados. <br><br>
				
					<b>2º) Rellena tus datos de contacto y en el botón "reservar apartamento".</b> Te aparecerá en pantalla un resumen con el importe de tu reserva, y una de las dos opciones disponibles:<br>
				
				</p>

					<ol type="a" style="text-align: justify;color:black;font-size:16px;padding:0px 45px">
						<li><b>Confirmación inmediata:</b> confirmamos muchas de nuestras reservas en el acto, te ofrecemos la posibilidad de pago inmediato mediante tarjeta de crédito.</li>
						<li>Solicitud de disponibilidad: para algunas fechas más demandadas nos llegará tu petición por correo  y la contestaremos en unos pocos minutos.</li>
					</ol>	

				</p>

				<p class="texto-aparamento " style="text-align: justify;color:black;font-size:16px;padding:0px 30px" >
					
					<b>3º) Tu alquiler de apartamento en Sierra nevada quedará confirmado definitivamente en el momento en que realizas el  depósito de reserva requerido.</b><br><br>
					
					Una vez hecho el <u>pago del 25% de la reserva</u>, te llegará un correo electrónico en el que tendrás toda la información necesaria y el localizador de tu reserva. <br><br>
					15 días antes de la fecha de llegada se abonará el <u>otro 25% del total.</u> <br><br>
					El <u>50% restante</u> se realizará en momento de la entrega de llaves Check in)<br><br>
					<b>La fianza se abonará en el momento de la recepción siempre con tarjeta de crédito.</b><br><br>

				</p>

				<h2>¿Cuáles son las horas de llegada y salida a mi apartamento?</h2>
				
				<p class="texto-aparamento " style="text-align: justify;color:black;font-size:16px;padding:0px 30px" >
					
					La recepción se podrá realizar a partir de la hora indicada en tu confirmación de reserva.( 17 – 19 horas) .<br><br>
					Si vas con niños o deseas llegar antes de dicha hora, ponte en contacto con nosotros y haremos lo posible para adelantar tu entrada.<br><br>
					Para dejar tu  apartamento el día de salida la hora del check out, será antes de las 12:00 am. <br><br>
					Si vas a llegar más tarde tienes que avisarnos y podrías tener un cargo adicional por las horas de espera. <br><br>
					Consultar condiciones en este enlace late check in. El suplemento por llegada fuera de horario se cobrará en el momento de la entrega de las llaves.

				</p>

				<h2>¿Cómo me entregáis las llaves del apartamento?</h2>
				
				<p class="texto-aparamento " style="text-align: justify;color:black;font-size:16px;padding:0px 30px" >
					
					Nosotros te llamaremos unos días antes de tu entrada para confirmar la hora a la que llegas y confirmar todos los detalles contigo, pero si no te hemos podido localizar, por favor el día anterior a tu viaje asegúrate de reconfirmar telefónicamente o email la hora estimada de llegada.<br>
					<b>Cuando llegues a Sierra Nevada, te estará esperando una persona de nuestra organización dentro del edificio Miramarski.</b>


				</p>

				<h2>¿Y si necesito ayuda?</h2>
				
				<p class="texto-aparamento " style="text-align: justify;color:black;font-size:16px;padding:0px 30px" >
					
					No te preocupes estamos allí, te daremos un teléfono de contacto pero lo más importante es que te cruzaras con nosotros dentro del edificio, te ayudaremos en el acto.<br>

				</p>

				<h2>¿Qué tengo que llevar, tiene de todo el apartamento?</h2>
				
				<p class="texto-aparamento " style="text-align: justify;color:black;font-size:16px;padding:0px 30px" >
					
					El apartamento o estudio, se entrega con el equipamiento básico en lo que respecta a la cocina y por supuesto con los edredones y mantas que necesitas para hacer agradable tu estancia.<br><br>

					Como ya sabes, nuestros apartamentos son en modalidad self-catering, lo que significa que eres tú mismo quien se debe hacer cargo del uso y mantenimiento de la casa durante tu estancia.<br><br>

					<b>Todos nuestros apartamentos se entregan con la ropa de cama montada y toallas.</b> <br><br>

					También <b>te entregamos un pequeño pack de cortesía,</b> con los artículos básicos de bienvenida para la llegada tales como jabón de cocina o pastillas para el lavavajillas, y siempre un rollo de papel higiénico en los baños a la llegada.<br><br>

					Como no está permitido dejar alimentos en el apartamento, recuerda llevar los productos básicos de uso diario, tales como sal, aceite, azúcar, etc, <b>si lo prefieres te ponemos en contacto con un supermercado para que te lleven la compra a tu apartamento el día de tu entrada.</b><br><br>

					Al contar con distintos apartamentos cada alojamiento puede disponer de diferente equipamiento, si necesitas saber algo concreto por favor pregúntanos.


				</p>
				
				<h2>¿Computan los niños?</h2>
				
				<p class="texto-aparamento " style="text-align: justify;color:black;font-size:16px;padding:0px 30px" >

					Los niños cuentan en todos los casos para realizar el cálculo de la ocupación del alojamiento, independientemente de la edad que tengan, 
					
					La capacidad máxima del apartamento alquilado nunca se puede exceder sumando adultos y niños. <br><br>
					
					La capacidad máxima del apartamento alquilado será igual al  número de camas. La ocupación máxima nunca podrá sobrepasar el máximo de personas indicado para cada apartamento o casa. <br><br>
					
					<b>En el caso de los bebes , te ofrecemos un <u>servicio gratuito de cuna</u></b> bajo petición y disponibilidad. <br><br>
					
					Para cualquier consulta relativa a cunas ponte en contacto con la nuestra oficina por teléfono o por email <a href="mailto:reservas@apartamentosierranevada.net">reservas@apartamentosierranevada.net</a>

				</p>

				<h2>¿Cómo se paga la reserva?</h2>
				
				<p class="texto-aparamento " style="text-align: justify;color:black;font-size:16px;padding:0px 30px" >
					
					Para que te confirmemos una reserva deberás abonar un 25% del importe total. <br><br>

					Te enviaremos un email 15 días antes de la fecha de entrada con la información necesaria para realizar un segundo pago por el 25% dl importe total. <br><br>

					A la entrega de llaves se liquidará el restante 50%. <br><br>

					La Fianza también se entrega en el momento de la recepción de llaves. <br><br>


				</p>

				<h2>¿Es necesario pagar fianza para alquilar el apartamento?</h2>
				
				<p class="texto-aparamento " style="text-align: justify;color:black;font-size:16px;padding:0px 30px" >
					
					Para alquilar cualquiera de nuestros apartamentos se exige una fianza de 300€. <br><br>

					<b>En el momento de realizar el Check in  se le pedirá que abone 300€ por dicho concepto. </b> <br><br>

					El pago de la fianza es obligatorio y debe ser abonado mediante tarjeta de crédito. <br><br>

				</p>

			</div>
	</section>
	
@endsection
@section('scripts')

@endsection