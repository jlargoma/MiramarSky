@extends('layouts.master_withoutslider')

@section('metadescription') Política de privacidad y política de cookies - apartamentosierranevada.net @endsection
@section('title')  Política de privacidad y política de cookies - apartamentosierranevada.net @endsection

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
				<h1 class="center psuh-20">Política de privacidad y política de cookies</h2>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 center" >
				<p class="texto-aparamento " style="text-align: justify;color:black;font-size:16px;padding:0px 30px" >
					<a href="{{ url('/') }}">apartamentosierranevada.net</a> es un sitio web titularidad de  Instituto Superior para el desarrollo Empresarial (ISDE ) S.L.<br><br>

					El usuario accede a este sitio web de forma voluntaria. El acceso a este sitio web tiene carácter gratuito. La navegación en este sitio web implica aceptar y conocer las advertencias legales, condiciones y términos de uso y política de privacidad contenidos en él. Si el usuario no está de acuerdo con estas condiciones de uso y no presta su consentimiento, no podrá hacer uso de este sitio web.<br><br>

					El acceso a este sitio web, así como el uso que pueda hacerse de la información que contiene, son de la exclusiva responsabilidad del usuario. El usuario se compromete a hacer uso del mismo de acuerdo a la finalidad del sitio web<br><br>

					El usuario no puede hacer un uso ilícito de la información contenida en esta web, ni llevar a cabo acciones que puedan dañar o alterar los sistemas informáticos de esta web.<br><br>

					Queda prohibida la inclusión y comunicación de contenidos, por parte de los usuarios, que sean falsos o inexactos y que induzcan o puedan inducir a error a Siempre Free, S.L. o a otros usuarios o terceros. El usuario será el único responsable de los perjuicios que cause mediante la comunicación de dichos datos.<br><br>


					Queda prohibido el uso de datos personales de terceros sin su consentimiento, así como el uso de datos identificativos de terceros con el ánimo de hacerse pasar por o fingir ser cualquier otra persona o entidad.<br><br>

					La propiedad realiza los máximos esfuerzos para evitar errores en los contenidos que se publican en el sitio web, reservándose la facultad de poder modificarlos en cualquier momento. <br><br>

					ISDE S.L. declina expresamente cualquier responsabilidad por error u omisión en los contenidos de este sitio web y de los daños y perjuicios que puedan deberse a la falta de veracidad, exactitud y actualidad de los mismos.<br><br>

					<a href="{{ url('/') }}">www.apartamentosierranevada.net</a> puede ofrecer enlaces a otros sitios web o facilitar su acceso mediante buscadores ubicados en su sitio web. <br><br>

					ISDE S.L. no asume ninguna responsabilidad en relación con estos sitios enlazados, ni los resultados de las búsquedas, ya que no tiene ningún tipo de control sobre ellos, su contenido, productos y servicios ofrecidos, etc. <br><br>

					La finalidad de estos servicios es informar al usuario de otras fuentes de información, por lo que el usuario accede bajo su exclusiva responsabilidad al contenido y en las condiciones de uso que rijan en los mismos.<br><br>

					ISDE SL. no se responsabilizará de ninguna consecuencia, daño o perjuicio que pudieran derivarse del uso de este sitio web o de sus contenidos, incluidos daños informáticos y la introducción de virus. ISDE SL. no garantiza la ausencia de virus ni de otros elementos en el sitio web, introducidos por terceros ajenos, que puedan producir alteraciones en los sistemas físicos o lógicos de los usuarios, pero utiliza todos los medios a su alcance para que esto no suceda.<br><br>

					ISDE SL se reserva el derecho de modificar o borrar en cualquier momento, sin previo aviso y/o justificación, el diseño, la configuración y la información contenida en este sitio web, si así lo estima oportuno. <br><br>

					ISDE S.L. no se hace responsable de los perjuicios que estas modificaciones puedan causar. No obstante utilizará todos los recursos que tenga a su alcance para informar a los usuarios de dichas modificaciones.<br><br>

				</p>
				<h3>2. Registros y suscripciones</h3>

				<p class="texto-aparamento " style="text-align: justify;color:black;font-size:16px;padding:0px 30px" >
					
					El acceso a este sitio web no implica la obligación de facilitar datos personales. No obstante, algunos de los servicios ofrecidos, como la propia reserva de alojamientos, requieren que el usuario facilite una serie de datos personales.<br><br>

					El usuario se compromete a aportar datos veraces, exactos y completos, tanto en el momento del registro, como en posteriores comunicaciones y se hace responsable de comunicar cualquier modificación en los mismos.<br><br>

					La no cumplimentación de campos indicados como obligatorios, que aparecen en los formularios de reserva y contacto, podrá tener como consecuencia que ISDE S.L. no pueda atender la solicitud del usuario.<br><br>

					El uso de este sitio web implica la aceptación de las condiciones generales y política de privacidad de ISDE S.L. <br><br>

					Los usuarios podrán solicitar la suscripción a boletines informativos y de ofertas (“newsletter”) de forma voluntaria. Los usuarios podrán solicitar la baja de dichos servicios en el momento que lo deseen dirigiendo un escrito a ISDE S.L. o utilizando cualquier otro medio que la empresa facilite a tal efecto, como por ejemplo el mecanismo denominado “Darse de baja”, ubicado en la parte final de los propios boletines informativos.<br><br>



				</p>

				<h3>3. Propiedad intelectual e industrial</h3>

				<p class="texto-aparamento " style="text-align: justify;color:black;font-size:16px;padding:0px 30px" >
					
					Todo el contenido gráfico y la información de la página web <a href="{{ url('/') }}">www.apartamentosierranevada.net</a>, así como el diseño gráfico, las imágenes, las bases de datos y los programas son propiedad exclusiva ISDE S.L., la cual se reserva todos los derechos de explotación.<br><br>

					En ningún caso el acceso o navegación en <a href="{{ url('/') }}">www.apartamentosierranevada.net</a> implica renuncia, transmisión o licencia total o parcial de ISDE S.L.para uso personal al usuario sobre sus derechos de propiedad intelectual e industrial.<br><br>

					La reproducción, distribución, comercialización o transformación no autorizadas de estas obras constituye una infracción de los derechos de propiedad intelectual de ISDE S.L.<br><br>

					El usuario se compromete a no realizar ninguna acción que perjudique la titularidad de este sitio. La utilización no autorizada de la información contenida en este sitio web, así como los perjuicios ocasionados en los derechos de propiedad intelectual e industrial de sus titulares, pueden dar lugar al ejercicio de las acciones que legalmente correspondan y, si procede, a las responsabilidades que de dicho ejercicio se deriven.

				</p>

				<h3>4. Modificaciones</h3>

				<p class="texto-aparamento " style="text-align: justify;color:black;font-size:16px;padding:0px 30px" >
					
					Estas Condiciones podrán sufrir modificaciones cuando ISDE S.L. lo considere oportuno, bien con la finalidad de adecuarse a los cambios legislativos, bien con el fin de llevar a cabo otro tipo de mejoras. Estas modificaciones serán válidas desde su publicación en este sitio web. <br><br>
						
					ISDE S.L. utilizará todos los medios a su alcance para dar a conocer a los usuarios de la web los cambios realizados.

				</p>
				
				<h3>5. Jurisdicción</h3>

				<p class="texto-aparamento " style="text-align: justify;color:black;font-size:16px;padding:0px 30px" >
					
					Las partes se someten, con renuncia a cualquier otro fuero, a los juzgados y tribunales del domicilio de ISDE S.L..

				</p>

				<h3>6. Política de Privacidad - Protección de datos de carácter personal</h3>

				<p class="texto-aparamento " style="text-align: justify;color:black;font-size:16px;padding:0px 30px" >
					
					En cumplimiento con la Ley Orgánica 15/1999, de 13 de diciembre, sobre Protección de Datos de Carácter Personal (en adelante "LOPD") y su normativa de desarrollo, se informa a los usuarios que los datos de carácter personal que faciliten mediante correo electrónico, enviados a través de formularios web, áreas privadas o por cualquier otro medio ubicado en este sitio web, como la contratación del alojamiento, los servicios adicionales y a lo largo de su relación con la empresa, serán incorporados a ficheros titularidad de ISDE S.L. (Responsable del Fichero), con la finalidad de atender a las consultas y solicitudes recibidas, contactar con usted, gestionar los servicios solicitados, las relaciones comerciales y las funciones legítimas propias de su actividad, así como para el envío de los boletines y noticias a los que se haya podido suscribir y para el envío de futuras comunicaciones comerciales que pudieran ser de su interés. <br><br>

					El tratamiento de sus datos personales se realizará de manera confidencial. ISDE, S.L. está comprometida con la privacidad y la protección de los datos personales de los usuarios y utiliza todos los recursos a su alcance para garantizar la seguridad y privacidad de los interesados.  <br><br>

					ISDE, S.L. cumple con la legislación vigente en Protección de Datos de Carácter Personal, figura inscrita en el Registro General de la Agencia de Protección de Datos y actúa siguiendo las recomendaciones, informes, innovaciones y directrices que ofrecen los diferentes organismos de protección de datos, autonómicos, estatales y europeos y otras autoridades de control. <br><br>

					En su oferta de servicios de alojamiento ISDE S.L. pone a disposición de sus clientes una serie de servicios opcionales, característicos de cada destino, para intentar hacer su estancia lo más agradable posible. Para prestar estos servicios la empresa puede contar con personal propio o con colaboradores externos.  <br><br>

					El cliente que contrata los servicios da su consentimiento expreso a la empresa para que pueda comunicar sus datos (identificativos, de contacto y relativos a los alojamientos y servicios contratados) a las empresas y personas colaboradoras de ISDE, S.L. Sólo aquellos empleados y colaboradores de ISDE, S.L. que necesiten conocer tales datos en orden a la eficaz prestación de cada uno de los servicios solicitados por Vd. podrán tener acceso a los mismos.


				</p>
					
				<h4>6.1. Recogida de datos de carácter personal</h4>

				<p class="texto-aparamento " style="text-align: justify;color:black;font-size:16px;padding:0px 30px" >
					
					ISDE S.L. recogerá los datos de carácter personal de manera adecuada, pertinente según las finalidades, utilidades, servicios y/o prestaciones incluidas en su web, de forma, además, determinada, explícita y legítima, por lo que en ningún caso se emplearán medios fraudulentos, desleales o, naturalmente, ilícitos o que en alguna forma pongan en peligro los legítimos derechos de los visitantes.<br><br>

					El usuario aporta sus datos personales de forma libre y voluntaria. ISDE S.L. no solicita más información personal que la que es necesaria para recibir el servicio solicitado. El envío de información a través de esta web conlleva necesariamente la autorización expresa para los tratamientos de datos descritos en esta política de privacidad y en las condiciones generales del sitio web.

				</p>

				<h4>6.2. Derechos de los interesados</h4>

				<p class="texto-aparamento " style="text-align: justify;color:black;font-size:16px;padding:0px 30px" >
					
					Puede ejercer los derechos de acceso, rectificación, cancelación y oposición dirigiéndose a ISDE S.L.,  por correo electrónico <a href="mailto:reservas@apartamentosierranevada.net">reservas@apartamentosierranevada.net</a> Las solicitudes deberán adjuntar copia de documento acreditativo de la identidad del solicitante.

				</p>
				
				<h4>6.3. Medidas de seguridad</h4>

				<p class="texto-aparamento " style="text-align: justify;color:black;font-size:16px;padding:0px 30px" >
					
					ISDE S.L. tratará los datos de carácter personal contenidos en sus ficheros adoptando las medidas de índole técnica y organizativa que sean necesarias para garantizar su seguridad y evitar su alteración, pérdida, tratamiento o acceso no autorizado, habida cuenta del estado de la tecnología, la naturaleza de los datos almacenados y los riesgos a que están expuestos, ya provengan de la acción humana o del medio físico o natural, de acuerdo con la legislación vigente que resulte de aplicación en materia de medidas de seguridad de los ficheros que contengan datos de carácter personal, particularmente por la LOPD y su normativa de desarrollo. <br><br>

					Sin embargo, los usuarios son informados de que las medidas de seguridad informática no son inexpugnables y no se está a salvo de posibles intromisiones ilegales e indebidas, que no serían responsabilidad de ISDE S.L.


				</p>

				<h4>6.4. Datos de Tráfico y Cookies</h4>

				<p class="texto-aparamento " style="text-align: justify;color:black;font-size:16px;padding:0px 30px" >
					
					Para la utilización de nuestro sitio web es necesario la utilización de cookies, es decir, pequeños ficheros de datos que se generan en el ordenador del usuario y que permiten el correcto funcionamiento de la web (selección de idioma, parámetros de búsquedas, etc.) y que desaparecen al terminar la conexión. Si lo deseas puedes configurar tu navegador para ser avisado en pantalla de la recepción de cookies y para impedir la instalación de cookies en tu disco duro.  <br><br>

					Por favor, consulta las instrucciones y manuales de tu navegador para ampliar esta información. La información obtenida es totalmente anónima y, en ningún caso, puede ser asociada a un usuario concreto e identificado. <br><br>

					Asimismo, y con la finalidad de ofrecer un mejor servicio a través de este sitio, <a href="{{ url('/') }}">www.apartamentosierranevada.net</a> registra la dirección IP, permitiéndose así el posterior procesamiento de los datos con el fin de analizar el número de páginas visitadas, el número de visitas, así como la actividad de los visitantes de la web, y su frecuencia de utilización. <br><br>

					Con la finalidad ya mencionada, este sitio está siendo medido y analizado con Google Analytics y/o herramientas similares, que pueden utilizar marcas en las páginas y cookies para analizar lo que sucede en las diferentes páginas del sitio web de ISDE S.L.


				</p>

				<h4>6.5. Vigencia</h4>

				<p class="texto-aparamento " style="text-align: justify;color:black;font-size:16px;padding:0px 30px" >
					
					ISDE, S.L. se reserva el derecho de modificar su política de privacidad de acuerdo a su criterio, a cambios legislativos o jurisprudenciales. <br><br>
					
					Si ISDE, S.L. introdujera alguna modificación, el nuevo texto será publicado en este mismo sitio web, donde el usuario podrá tener conocimiento de la misma. <br><br>
					
					En cualquier caso, la relación con los usuarios se regirá por las normas previstas en el momento preciso en que se accede al sitio web. Es por ello que ISDE S.L. le recomienda visitar esta política de privacidad de forma periódica.


				</p>

			</div>
	</section>
	
@endsection
@section('scripts')

@endsection