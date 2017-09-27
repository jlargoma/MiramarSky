@extends('layouts.master_withoutslider')

@section('metadescription') Politica de cookies - apartamentosierranevada.net @endsection
@section('title')  Politica de cookies - apartamentosierranevada.net @endsection

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
				<h1 class="center psuh-20">Política de Cookies</h2>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 center" >
				<p class="texto-aparamento " style="text-align: justify;color:black;font-size:16px;padding:0px 30px" >

					<b>¿Qué es una cookie? </b><br><br>

					Una cookie es un archivo de texto que un servidor web puede dejar en el disco duro del usuario con el fin de identificarlo cuando éste se vuelva a conectar. Las cookies facilitan el uso y la navegación por una página web, aportando innumerables ventajas en la prestación de servicios interactivos. <br><br>

					Las cookies se utilizan por ejemplo para gestionar la sesión del usuario (reduciendo el número de veces que tiene que incluir su contraseña) o para adecuar los contenidos de una página web a sus preferencias. Las cookies pueden ser de "sesión", por lo que se borrarán una vez el usuario abandone la página web que las generó o "persistentes", que permanecen en su ordenador hasta una fecha determinada. <br><br>

					<b>¿Qué tipo de cookies utiliza este sitio web? </b><br><br>

					Este sitio web utiliza diferentes tipos de cookies con diferentes finalidades, que se detallan a continuación: <br><br>

					<b>Cookies técnicas </b><br><br>

					Permiten al usuario la navegación a través de este sitio web y la utilización de las diferentes opciones que en él existen como, por ejemplo, identificar la sesión, acceder a partes de acceso restringido o compartir contenidos a través de redes sociales. <br><br>

					<b>Cookies de análisis </b><br><br>

					Permiten el seguimiento y análisis del comportamiento de los usuarios del sitio web. La información recogida mediante este tipo de cookies se utiliza en la medición de la actividad del sitio web, con el fin de introducir mejoras en función del análisis de los datos de uso que hacen los usuarios del servicio. <br><br>

					<b>Cookies publicitarias </b><br><br>

					Permiten la gestión de los espacios publicitarios que el editor incluye en un sitio web, desde el que presta el servicio solicitado en base a criterios como el contenido editado o la frecuencia en la que se muestran los anuncios. <br><br>

					<b>¿Cómo se pueden desactivar o eliminar las cookies? </b><br><br>

					El usuario, si lo desea, pueda desactivar y/o eliminar las cookies. Como información adicional al usuario, aportamos dónde puede encontrar la configuración para el tratamiento de cookies en los principales navegadores: <br><br>

					Internet Explorer: Herramientas. Opciones de Internet. Privacidad. Avanzada. <br><br>

					Firefox: Preferencias. Privacidad. <br><br>

					Google Chrome: Configuración. Opciones Avanzadas. Privacidad. Cookies. <br><br>

					Safari: Preferencias. Privacidad. <br><br>

					<b>Cookies de terceros utilizadas en este sitio web </b><br><br>

					Este sitio web utiliza cookies de terceros. A continuación se muestra el listado de terceros que hacen uso de las cookies:
					Google Analytics: para realizar análisis estadísticos. <br><br>

					Google Adsense: para insertar publicidad <br><br>

					Facebook/Google/Twitter/YouTube: para compartir contenidos con dichos canales y redes sociales <br><br>

					<b><u>Configuración de Cookies</u></b><br><br>
					
					Marca las cookies que quieres activar<br><br>

					<input type="checkbox" checked><b>Cookies obligatorias</b><br>
					Permiten al usuario la navegación a través de este sitio web y la utilización de las diferentes opciones que en él existen como, por ejemplo, identificar la sesión o acceder a partes de acceso restringido.<br><br>

					<input type="checkbox" checked><b>Cookies funcionales</b><br>
					Permiten el seguimiento y análisis del comportamiento de los usuarios del sitio web. La información recogida mediante este tipo de cookies se utiliza en la medición de la actividad del sitio web, con el fin de introducir mejoras en función del análisis de los datos de uso que hacen los usuarios del servicio.<br><br>
					
					<input type="checkbox" checked><b>Cookies de publicidad</b><br>
					Permiten la gestión de los espacios publicitarios que el editor incluye en un sitio web, desde el que presta el servicio solicitado en base a criterios como el contenido editado o la frecuencia en la que se muestran los anuncios.<br><br>
					
					Para deshacer estas preferencias debes eliminar las cookies de la configuración de tu explorador.


				</p>
			</div>
	</section>
	
@endsection
@section('scripts')

@endsection