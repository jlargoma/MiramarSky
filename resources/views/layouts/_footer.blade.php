<?php
if (!isset($oContents)) $oContents = new App\Contents();
$footerContent = $oContents->getContentByKey('footer');
?>

<style>
@media only screen and (min-width: 769px){
  .footer-area{
    background-image: url('{{getCloudfl($footerContent["imagen"])}}') !important;
  }
}
</style>
<footer id="footer" >	
  <!-- Copyrights
	============================================= -->
	<div id="copyrights"  class="footer-area ">
          <div class="capa-blanca">
          <div class="container">
		<div class="row clearfix" style="margin-left: 20px;margin-right: 20px">
			<div class="col-md-2 col-sm-4 col-xs-6">
			    <div class="block footer-block block">
    				<h4 class="title_block push-10 blue font-w800 center">
						¿QUÉ HACER EN SIERRA NEVADA?
					</h4>
					<ul class="toggle-footer list-group bullet center" style="list-style: none;">
						<li><a href="{{ url('/actividades/informacion-interes') }}" >Información de interes</a></li>
						<li><a href="{{ url('/actividades/en-familia') }}" >Familia / Niños</a></li>
						<li><a href="{{ url('/actividades/restaurantes') }}" >Restaurantes y bares</a></li>
						<li><a href="{{ url('/actividades/la-estacion') }}" >La estación</a></li>
					</ul>
				</div>
			</div>
			<div class="col-md-2 col-sm-4 col-xs-6">
			    <div class="block footer-block block">
    				<h4 class="title_block push-10 blue font-w800 center">
						APARTAMENTOS
					</h4>
					<ul class="toggle-footer list-group bullet center" style="list-style: none;">
						<li><a href="{{url('/apartamentos/apartamento-lujo-sierra-nevada')}}" >Apartamento de lujo</a></li>
						<li><a href="{{url('/apartamentos/apartamento-standard-sierra-nevada')}}" >Apartamento standard</a></li>
						<li><a href="{{url('/apartamentos/estudio-lujo-sierra-nevada')}}" >Estudio de lujo</a></li>
						<li><a href="{{url('/apartamentos/estudio-standard-sierra-nevada')}}" >Estudio standard</a></li>					
					</ul>
				</div>
			</div>
			<div class="col-md-2 col-sm-4 col-xs-6">
			    <div class="block footer-block block">
    				<h4 class="title_block push-10 blue font-w800 center">
						SOBRE TU RESERVA
					</h4>
					<ul class="toggle-footer list-group bullet center" style="list-style: none;">
						<li><a href="{{ url('/forfait')}}" target="_blank" >Gestión de forfait</a></li>
						<li><a href="{{url('/condiciones-generales')}}" >Condiciones Generales</a></li>
						<li><a href="{{url('/preguntas-frecuentes')}}" >Preguntas Frecuentes</a></li>
						<li><a href="{{ url('/ayudanos-a-mejorar') }}" >Ayudanos a Mejorar</a></li>
						<li><a href="{{url('/grupos')}}" >Grupos</a></li>
					</ul>
				</div>
			</div>
			<div class="col-md-2 col-sm-4 col-xs-6">
			    <div class="block footer-block block">
    				<h4 class="title_block push-10 blue font-w800 center">
						ACERCA DE NOSOTROS
					</h4>
					<ul class="toggle-footer list-group bullet center" style="list-style: none;">
						<li><a class="map" href="https://www.google.com/maps?ll=37.093311,-3.396972&z=17&t=m&hl=es-ES&gl=ES&mapclient=embed&cid=335969053959651753" target="_blank"><i class="fa fa-map-marker "></i>Como llegar</a></li>
						<li><a href="{{ url('/contacto') }}" >Contacta</a></li>
						<li><a href="{{ url('/quienes-somos') }}" >Quienes Somos</a></li>
						<li><a href="{{url('/eres-propietario')}}" >¿Eres Propietario?</a></li>
						<!-- <li><a href="#" >Preguntas frecuentes</a></li> -->
					</ul>
				</div>
			</div>
			<div class="col-md-2 col-sm-4 col-xs-6">
			    <div class="block footer-block block">
    				<h4 class="title_block push-10 blue font-w800 center">
						ENLACES DE INTERES
					</h4>
					<ul class="toggle-footer list-group bullet center" style="list-style: none;">

						<li><a href="http://sierranevada.es/es/invierno/la-estaci%C3%B3n/parte-nieve/" ><i class="fa fa-snowflake-o" aria-hidden="true" style="color:#3F51B5"></i> Parte de nieve</a></li>
						<li><a href="http://sierranevada.es/es/invierno/la-estaci%C3%B3n/en-pistas/plano-de-pistas/" ><i class="fa fa-map" aria-hidden="true" style="color:#3F51B5"></i> Mapa de la estación</a></li>
						<li><a href="http://sierranevada.es/es/webcams/" ><i class="fa fa-video-camera" aria-hidden="true" style="color:#3F51B5"></i> Webcams</a></li>
						<li><a href="{{url('/eres-propietario')}}" ></a></li>
						<!-- <li><a href="#" >Preguntas frecuentes</a></li> -->
					</ul>
				</div>
			</div>
			<div class="col-md-2 col-sm-4 col-xs-6">
			    <div class="block footer-block block">
    				<h4 class="title_block push-10 blue font-w800 center">
						INFORMACION LEGAL
					</h4>
					<ul class="toggle-footer list-group bullet center" style="list-style: none;">
						<li><a href="{{ url('/politica-privacidad') }}" >Politica de privacidad</a></li>
						<li><a href="{{ url('/aviso-legal') }}" >Aviso Legal</a></li>
						<li><a href="{{ url('/politica-cookies') }}" >Politica de Cookies</a></li>
						<li><a href="{{ url('/condiciones-contratacion') }}" >Condiciones de
						contratación</a></li>

					</ul>

				</div>
			</div>
		</div>


@include('frontend.blocks.footer-info')

</div>
            </div>
	</div><!-- #footer end -->
		<div class=" copyright">
		Copyrights © {{date('Y')}} Todos los derechos reservados.
		</div>
</footer>