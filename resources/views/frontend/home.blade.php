@extends('layouts.master')
@section('title')Apartamentos de lujo en Sierra Nievada a pie de pista @endsection

@section('content')

<link href="{{ asset('/frontend/hover.css')}}" rel="stylesheet" media="all">
<link rel="stylesheet" href="{{ asset('/frontend/css/components/radio-checkbox.css')}}" type="text/css" />
<style type="text/css">
	.hvr-reveal:before{
		border-color: white!important;
	}
	@media (max-width: 768px){
		#primary-menu.style-2 {
		    background-color: transparent;
		}
		#primary-menu{
			padding: 40px 15px 0 15px;
		}
		#primary-menu-trigger {
		    top: 5px!important;
		    left: 5px!important;
		}
	}
</style>
<section id="content">

    <div class="content-wrap notoppadding" style="padding-bottom: 0;">
       
		<?php if (!$mobile->isMobile()): ?>
		<!-- DESKTOP -->
			<style type="text/css">
				#content-book-response label.white, #content-book-response label, .tab-content label{
					color: white!important;
				}
			</style>
			<div class="row clearfix" style="background-color: #3F51B5;">
   				<div id="close-form-book" style="position: absolute; top: 20px; right: 10px; z-index: 50;  cursor: pointer;">
   					<span class="white text-white"><i class="fa fa-times fa-2x"></i></span>
   				</div>
   				<div id="content-book" class="container clearfix push-10" style="display: none;">
   					<div class="tabs advanced-real-estate-tabs clearfix">

   						<div class="tab-container" style="padding: 20px 0; background-color: #3F51B5;">
   							
   							<div class="container clearfix">
   								<div class="tab-content clearfix">
   									<div class="col-md-6">
										<div class="col-xs-12">
											<h3 class="text-center white">FORMULARIO DE RESERVA</h3>
										</div>

	   									@include('frontend._formBook')
   									</div>
   									<div class="col-md-6"  id="content-book-response"></div>
   								</div>
   							</div>
   						</div>

   					</div>
   				</div>
   			</div>
   			<div style="clear: both;"></div>
	       	<section class="row full-screen noborder" style="background-image: url('/img/miramarski/mountain.jpg'); background-color:white; background-size: cover; background-position:50%;">

				<div class="col-xs-12">
					<div class="col-xs-12 ">
					
		       			<div class="col-md-6 center fadeInUp animated" data-animation="fadeInUp" style="padding: 40px 15px;">
		       				<div class="col-xs-12 push-20">
		       					<div class="heading-block  black" style="margin-bottom: 20px">
		       						<h1 class="font-w800 black" style="letter-spacing: 0; font-size: 26px;">APARTAMENTOS DE LUJO <span class="font-w800 green">A PIE DE PISTA</span></h1>
		       					</div>
		       					<p class="lead  text-justify black ls-15 font-s14 black">
		       						Todos nuestros Apartamentos están en el Edificio Miramar Ski, situado <b>en la zona baja de Sierra Nevada</b>. Tienen excelentes vistas y todos disponen del equipamiento completo.

		       					</p>
		       					<h2 class="font-w300 text-center black push-18" style="line-height: 1;font-size: 18px!important; text-transform: uppercase;">
		       						<span class="font-w800 black"> El edificio tiene salida directa a las pistas, solo tienes que salir de casa y esquiar!!</span>
		       					</h2>
								
		       					<p class="lead  text-justify black ls-15 font-s14 black">
		       						Se encuentran <b>a 5 minutos andando de la plaza de Andalucía</b>, centro neurálgico de la estación.<br><br>
		       						Muy cerca, a pocos metros, tienes varios supermercados, bares y restaurantes, lo que es muy importante para que disfrutes tus vacaciones sin tener que coger el coche ni remontes, Nuestro edificio Miramar Ski es <b>uno de los edificios más modernos de Sierra Nevada</b> (2006).<br><br>
									Tenemos <b>apartamentos con dos habitaciones</b> ( ocupación 6 / 8 pers) y también <b>estudios</b> (ocupación 4 / 5 pers), Dentro de estos dos modelos, podrás  elegir entre los estándar y de lujo, que están recién reformados.<br><br>
									<b>En todas las reservas las sabanas y toallas están incluidas</b><br><br>
									Queremos ofrecerte un servicio especial, por eso incluimos en todas nuestras reservas un obsequio de bienvenida. <br><br>
									<b>En el botón de reserva  podrás calcular el coste de tu petición y si lo consideras hacer tu solicitud de disponibilidad.</b>

		       					</p>
		       				</div>
		       				<div class="col-xs-12 clearfix">

				       			<div id="oc-clients" class="owl-carousel image-carousel carousel-widget" data-margin="60" data-loop="true" data-nav="false" data-autoplay="3000" data-pagi="false" data-items-xxs="2" data-items-xs="3" data-items-sm="4" data-items-md="6" data-items-lg="8">

				       				<div class="oc-item">
				       					<a class="pc-characteristics">
				       						<img src="{{ asset('/img/miramarski/iconos/small/teleesqui.png') }}" /> A pie de pista 
				       					</a>
				       				</div>
				       				<div class="oc-item">
				       					<a class="pc-characteristics">
				       						<img src="{{ asset('/img/miramarski/iconos/small/parking.png') }}" /> Parking cubierto 
				       					</a>
				       				</div>
				       				<div class="oc-item">
				       					<a class="pc-characteristics">
				       						<img src="{{ asset('/img/miramarski/iconos/small/ascensor.png') }}" /> Ascensor 
				       					</a>
				       				</div>
				       				<div class="oc-item">
				       					<a class="pc-characteristics">
				       						<img src="{{ asset('/img/miramarski/iconos/small/piscina.png') }}" /> Piscina climatizada 
				       					</a>
				       				</div>
				       				<div class="oc-item">
				       					<a class="pc-characteristics">
				       						<img src="{{ asset('/img/miramarski/iconos/small/gimnasio.png') }}" /> gimnasio 
				       					</a>
				       				</div>
				       				<div class="oc-item">
				       					<a class="pc-characteristics">
				       						<img src="{{ asset('/img/miramarski/iconos/small/guardaesqui.png') }}" /> Guarda Esqíes 
				       					</a>
				       				</div>
				       				<div class="oc-item">
				       					<a class="pc-characteristics">
				       						<img src="{{ asset('/img/miramarski/iconos/small/mascotas.png') }}" /> Prohibido mascotas 
				       					</a>
				       				</div>
				       				<div class="oc-item">
				       					<a class="pc-characteristics">
				       						<img src="{{ asset('/img/miramarski/iconos/small/ropa-toallas.png') }}" /> Ropa y toallas 
				       					</a>
				       				</div>
				       				<div class="oc-item">
				       					<a class="pc-characteristics">
				       						<img src="{{ asset('/img/miramarski/iconos/small/cocina.png') }}" /> Cocina 
				       					</a>
				       				</div>
				       				<div class="oc-item">
				       					<a class="pc-characteristics">
				       						<img src="{{ asset('/img/miramarski/iconos/small/ducha.png') }}" /> Baño 
				       					</a>
				       				</div>
				       				<div class="oc-item">
				       					<a class="pc-characteristics">
				       						<img src="{{ asset('/img/miramarski/iconos/small/calefaccion.png') }}" /> Calefaccion 
				       					</a>
				       				</div>
				       				<div class="oc-item">
				       					<a class="pc-characteristics">
				       						<img src="{{ asset('/img/miramarski/iconos/small/shopping.png') }}" /> Shopping 
				       					</a>
				       				</div>

				       			</div>


	       					</div>
		       			</div>

		       			<div class="col-md-5 col-md-offset-1 center  hidden-sm hidden-xs" style="margin:72px 60px">
	       					<div id="oc-slider" class="owl-carousel carousel-widget" data-margin="0" data-items="1" data-animate-in="zoomIn" data-speed="450" data-animate-out="fadeOut">

	       						<a ><img src="{{ asset('/img/miramarski/edificio/alquiler_apartamento_sierra_nevada_a_pie_de_pista.jpg') }}" alt="Slide 1" style="height: 450px;"></a>
	       						<a ><img src="{{ asset('/img/miramarski/edificio/alquiler-apartamento-lujo-edificio-miramar-sierra-nevada.jpg') }}" alt="Slide 2" style="height: 450px;"></a>
	       						<a ><img src="{{ asset('/img/miramarski/edificio/apartamento_sierra_nevada_a_pie_de_pista.jpg') }}" alt="Slide 3" style="height: 450px;"></a>
	       						<a ><img src="{{ asset('/img/miramarski/edificio/miramarski_ALQUILER_ APARTAMENTO_SIERRA_NEVADA.jpg') }}" alt="Slide 4" style="height: 450px;"></a>

	       					</div>
		       			</div>

		       		</div>
				</div>
			</section>
			<section class="page-section">
	   			<div class="row push-30" style="margin-top: 20px;">
	   				<h2 class="text-center black font-w300">
	   					GALERÍA DE <span class="font-w800 green ">APARTAMENTOS</span>
	   				</h2>
	   				<div class="col-md-12 col-xs-12">

	   					<div class="col-md-3 col-xs-12 push-mobile-20 hover-effect">
	   						<a href="{{url('/apartamentos/apartamento-lujo-sierra-nevada')}}">
		   						<div class="col-xs-12 not-padding  container-image-box">
		   							<div class="col-xs-12 not-padding push-0">
	   									<img class="img-responsive imga" src="{{ asset('/img/miramarski/small/apartamento-lujo-sierra-nevada.jpg')}}" alt="Apartamento de lujo sierra nevada"/>
		   							</div>
		   							<div class="col-xs-12 not-padding text-right overlay-text">
		   								<h2 class="font-w600 center push-10 text-center text font-s24 white hvr-reveal" style="padding: 90px 105px;">APARTAMENTO DE LUJO
		   								</h2>
		   							</div>
		   						</div>
	   						</a>
	   					</div>
					
						<div class="col-md-3 col-xs-12 push-mobile-20 hover-effect">
							<a href="{{url('/apartamentos/apartamento-standard-sierra-nevada')}}">
		   						<div class="col-xs-12 not-padding  container-image-box">
		   							<div class="col-xs-12 not-padding push-0">
	   									<img class="img-responsive imga" src="{{ asset('/img/miramarski/small/apartamento-standard-sierra-nevada.jpg')}}"  alt="Apartamento standard sierra nevada"/>
		   							</div>
		   							<div class="col-xs-12 not-padding text-right overlay-text">
		   								<h2 class="font-w600 center push-10 text-center text font-s24 white hvr-reveal" style="padding: 90px 105px;">
		   									APARTAMENTO STANDARD
		   								</h2>
		   							</div>
		   						</div>
							</a>
	   					</div>

	   					<div class="col-md-3 col-xs-12 push-mobile-20 hover-effect">
		   					<a href="{{url('/apartamentos/estudio-lujo-sierra-nevada')}}">
		   						<div class="col-xs-12 not-padding  container-image-box">
		   							<div class="col-xs-12 not-padding push-0">
		   								
	   									<img class="img-responsive" src="{{ asset('/img/miramarski/small/estudio-lujo-sierra-nevada.jpg')}}"  alt="Estudio de lujo sierra nevada"/>
		   							</div>
		   							<div class="col-xs-12 not-padding text-right overlay-text">
		   								<h2 class="font-w600 center push-10 text-center text font-s24 white hvr-reveal" style="padding: 90px 140px;">
		   									ESTUDIO DE LUJO
		   								</h2>
		   							</div>
		   						</div>
		   					</a>
	   					</div>

	   					
	   					<div class="col-md-3 col-xs-12 push-mobile-20 hover-effect">
	   						<a href="{{url('/apartamentos/estudio-standard-sierra-nevada')}}">
		   						<div class="col-xs-12 not-padding  container-image-box">
		   							<div class="col-xs-12 not-padding push-0">
		   								
	   									<img class="img-responsive" src="{{ asset('/img/miramarski/small/estudio-standard-sierra-nevada.jpg')}}"  alt="Estudio standard sierra nevada"/>
		   							</div>
		   							<div class="col-xs-12 not-padding text-right overlay-text">
		   								<h2 class="font-w600 center push-10 text-center text font-s24 white hvr-reveal" style="padding: 90px 140px;">
		   									ESTUDIO STANDARD
		   								</h2>
		   							</div>
		   						</div>
		   					</a>
	   					</div>
	   					
	   				</div>
	   			</div>
	       			
				<div class="container push-20" style="margin-top: 20px;">
	   				<h2 class="text-center black font-w300">
	   					OTROS <span class="font-w800 green ">SERVICIOS</span>
	   				</h2>
	   				<div class="col-md-12 col-xs-12">

	   					<div class="col-md-4 col-xs-12 push-mobile-20 hover-effect">
	   						<a href="https:\\www.apartamentosierranevada.net/forfait.php" target="_blank">
		   						<div class="col-xs-12 not-padding  container-image-box">
		   							<div class="col-xs-12 not-padding push-0">
	   									<img class="img-responsive imga" src="{{ asset('/img/miramarski/descuento forfait.jpg')}}" alt="Apartamento de lujo sierra nevada"/>
		   							</div>
		   							<div class="col-xs-12 not-padding text-right overlay-text">
		   								<h2 class="font-w200 center push-10 text-center text font-s24 white" >DESCUENTOS <span class="font-w800 white">EN FORFAIT</span>
		   								</h2>
		   							</div>
		   						</div>
	   						</a>
	   					</div>
					
						<div class="col-md-4 col-xs-12 push-mobile-20 hover-effect">
							<a href="#">
		   						<div class="col-xs-12 not-padding  container-image-box">
		   							<div class="col-xs-12 not-padding push-0">
	   									<img class="img-responsive imga" src="{{ asset('/img/miramarski/fortfait.jpg')}}"  alt="Apartamento standard sierra nevada"/>
		   							</div>
		   							<div class="col-xs-12 not-padding text-right overlay-text">
		   								<h2 class="font-w200 center push-10 text-center text font-s24 white" >
		   									DESCUENTOS<span class="font-w800 white"> ALQUILER MATERIAL<span class="font-w800 white">
		   								</h2>
		   							</div>
		   						</div>
							</a>
	   					</div>

	   					<div class="col-md-4 col-xs-12 push-mobile-20 hover-effect">
		   					<a href="#">
		   						<div class="col-xs-12 not-padding  container-image-box">
		   							<div class="col-xs-12 not-padding push-0">
		   								
	   									<img class="img-responsive" src="{{ asset('/img/miramarski/ski con niños.jpg')}}"  alt="Estudio de lujo sierra nevada"/>
		   							</div>
		   							<div class="col-xs-12 not-padding text-right overlay-text">
		   								<h2 class="font-w200 center push-10 text-center text font-s24 white">
		   									¿QUÉ HACER EN SIERRA NEVADA?
		   								</h2>
		   							</div>
		   						</div>
		   					</a>
	   					</div>
	   				</div>
	   			</div>
	       		
	       	</section>
   		<!-- END DESKTOP -->
		
		<?php else: ?>
		<!-- MOBILE -->
			<style type="text/css">
				label{
					color: white!important
				}
			</style>
	       	<section class="page-section" style="letter-spacing: 0;line-height: 1;background: #3f51b5!important;color: #fff!important;">
	   		
	   			<div class="row" style="background-color: #3F51B5;">
	   				
	   				<div id="content-book" class="container container-mobile clearfix push-10" style="display: none;">
	   					<div class="tabs advanced-real-estate-tabs clearfix">

	   						<div class="tab-container" style="padding: 20px 0; background-color: #3F51B5;">
	   							<div id="close-form-book" style="position: absolute; top: 0; right: 20px; z-index: 50;  cursor: pointer;">
	   								<span class="white text-white"><i class="fa fa-times fa-2x"></i></span>
	   							</div>
	   								<div class="tab-content clearfix" id="content-book-response">
										<div class="row">
											<h3 class="text-center white">FORMULARIO DE RESERVA</h3>
										</div>

										@include('frontend._formBook')
	   									
	   								</div>
	   						</div>

	   					</div>
	   				</div>
	   			</div>

	       	</section>
			
			<section  class="page-section" style="letter-spacing: 0;line-height: 1; ">
				<div class="col-xs-12" style="background-color: #3F51B5;">
					<div class="col-xs-12" style="padding: 27px 0 0">
						<div class="row white" style="margin-bottom: 20px">
       						<h1 class="font-w800 white center font-s16" style="letter-spacing: 0; margin-bottom: 0">APARTAMENTOS DE LUJO A PIE DE PISTA</h1>
       					</div>
       					<p class="lead  text-justify white ls-15 font-s14 white">
       						Todos nuestros Apartamentos están en el Edificio Miramar Ski, situado <b>en la zona baja de Sierra Nevada</b>. Tienen excelentes vistas y todos disponen del equipamiento completo.

       					</p>
       					<h2 class="font-w300 text-center nobottommargin white push-20" style="line-height: 1;font-size: 19px!important"> <span class="font-w800 white">El edificio tiene salida directa a las pistas,</span> solo tienes que salir de casa y esquiar!!</h2>

	       				<div class="col-xs-12 clearfix">

			       			<div id="oc-clients" class="owl-carousel image-carousel carousel-widget" data-margin="60" data-loop="true" data-nav="false" data-autoplay="3000" data-pagi="false" data-items-xxs="4" data-items-xs="4" data-items-sm="6" data-items-md="6" data-items-lg="8">

			       				<div class="oc-item">
			       					<a class="pc-characteristics white center">
			       						<img src="{{ asset('/img/miramarski/iconos/small/white-piepista.png') }}" /><br> A pie de pista 
			       					</a>
			       				</div>
			       				<div class="oc-item">
			       					<a class="pc-characteristics white center">
			       						<img src="{{ asset('/img/miramarski/iconos/small/white-parking.png') }}" /> Parking cubierto 
			       					</a>
			       				</div>
			       				<div class="oc-item">
			       					<a class="pc-characteristics white center">
			       						<img src="{{ asset('/img/miramarski/iconos/small/white-ascensor.png') }}" /> Ascensor 
			       					</a>
			       				</div>
			       				<div class="oc-item">
			       					<a class="pc-characteristics white center">
			       						<img src="{{ asset('/img/miramarski/iconos/small/white-piscina.png') }}" /> Piscina climatizada 
			       					</a>
			       				</div>
			       				<div class="oc-item">
			       					<a class="pc-characteristics white center">
			       						<img src="{{ asset('/img/miramarski/iconos/small/white-gimnasio.png') }}" /> gimnasio 
			       					</a>
			       				</div>
			       				<div class="oc-item">
			       					<a class="pc-characteristics white center">
			       						<img src="{{ asset('/img/miramarski/iconos/small/white-guardaesqui.png') }}" /> Guarda Esqíes 
			       					</a>
			       				</div>
			       				<div class="oc-item">
			       					<a class="pc-characteristics white center">
			       						<img src="{{ asset('/img/miramarski/iconos/small/white-mascotas.png') }}" /> Prohibido mascotas 
			       					</a>
			       				</div>
			       				<div class="oc-item">
			       					<a class="pc-characteristics white center">
			       						<img src="{{ asset('/img/miramarski/iconos/small/white-ropa-toallas.png') }}" /> Ropa y toallas 
			       					</a>
			       				</div>
			       				<div class="oc-item">
			       					<a class="pc-characteristics white center">
			       						<img src="{{ asset('/img/miramarski/iconos/small/white-cocina.png') }}" /> Cocina 
			       					</a>
			       				</div>
			       				<div class="oc-item">
			       					<a class="pc-characteristics white center">
			       						<img src="{{ asset('/img/miramarski/iconos/small/white-ducha.png') }}" /> Baño 
			       					</a>
			       				</div>
			       				<div class="oc-item">
			       					<a class="pc-characteristics white center">
			       						<img src="{{ asset('/img/miramarski/iconos/small/white-calefaccion.png') }}" /> Calefaccion 
			       					</a>
			       				</div>
			       				<div class="oc-item">
			       					<a class="pc-characteristics white center">
			       						<img src="{{ asset('/img/miramarski/iconos/small/white-shopping.png') }}" /> Shopping 
			       					</a>
			       				</div>

			       			</div>


       					</div>

       					<p class="lead  text-justify white ls-15 font-s14 white">
       						Se encuentran <b>a 5 minutos andando de la plaza de Andalucía</b>, centro neurálgico de la estación.<br><br>
       						Muy cerca, a pocos metros, tienes varios supermercados, bares y restaurantes, lo que es muy importante para que disfrutes tus vacaciones sin tener que coger el coche ni remontes, Nuestro edificio Miramar Ski es <b>uno de los edificios más modernos de Sierra Nevada</b> (2006).<br><br>
							Tenemos <b>apartamentos con dos habitaciones</b> ( ocupación 6 / 8 pers) y también <b>estudios</b> (ocupación 4 / 5 pers), Dentro de estos dos modelos, podrás  elegir entre los estándar y de lujo, que están recién reformados.<br><br>
							<b>En todas las reservas las sabanas y toallas están incluidas</b><br><br>
							Queremos ofrecerte un servicio especial, por eso incluimos en todas nuestras reservas un obsequio de bienvenida. <br><br>							

							<!-- Tenemos <b>apartamentos con dos habitaciones</b> ( ocupación 6 / 8 pers) y también <b>estudios</b> (ocupación 4 / 5 pers), Dentro de estos dos modelos, podrás  elegir entre los estándar y de lujo, que están recién reformados.<br><br>
							<b>En todas las reservas las sabanas y toallas están incluidas</b><br><br>
							Queremos ofrecerte un servicio especial, por eso incluimos en todas nuestras reservas un obsequio de bienvenida. <br><br>
							<b>En el botón de reserva  podrás calcular el coste de tu petición y si lo consideras hacer tu solicitud de disponibilidad.</b>
 							-->
       					</p>
						<div class="col-xs-12 not-padding push-30" style="margin-bottom:0px!important; ">
							<div id="oc-slider" class="owl-carousel carousel-widget" data-margin="0" data-items="1" data-animate-in="zoomIn" data-speed="450" data-animate-out="fadeOut">

	       						<a href="#"><img src="{{ asset('/img/miramarski/exteriores.jpg') }}" alt="Slide 1" style="height: 200px;"></a>
	       						<a href="#"><img src="{{ asset('/img/miramarski/cama-principal-apartamento-sierra-nevada.jpg') }}" alt="Slide 2" style="height: 200px;"></a>
	       						<a href="#"><img src="{{ asset('/img/miramarski/salon-miramar-apartamento-sierra-nevada.jpg') }}" alt="Slide 3" style="height: 200px;"></a>
	       						<a href="#"><img src="{{ asset('/img/miramarski/television-chimenea-apartamento-sierra-nevada.jpg') }}" alt="Slide 4" style="height: 200px;"></a>

	       					</div>
						</div>
						<p class="lead  text-justify white ls-15 font-s14 white" >
							<b>En el botón de reserva  podrás calcular el coste de tu petición y si lo consideras hacer tu solicitud de disponibilidad.</b>
						</p>
					</div>

				</div>
				
			</section>
			<div style="clear: both;"></div>
			<section  class="page-section" style="letter-spacing: 0;line-height: 1; margin: 45px 0;">
				<div class="heading-block center push-20">
					<h3 class="green">GALERÍA DE APARTAMENTOS</h3>
				</div>
				<div class=" row fadeInAppear">
					<a href="{{url('/apartamentos/apartamento-lujo-sierra-nevada')}}"  class=" hover-effect"> 
						<div class="section parallax noborder center" style="background-image: url({{ asset('/img/miramarski/galerias/apto-lujo.jpg') }}); padding: 40px 0; margin: 20px 0;" data-stellar-background-ratio="0.4">
							<h3 class="h2 text-center white text-white font-w800 wst" style="text-shadow: 1px 1px #000;">APARTAMENTO DE LUJO</h3>
						</div>
					</a>
				</div>
				<div class=" row fadeInAppear">
					<a href="{{url('/apartamentos/apartamento-standard-sierra-nevada')}}"  class=" hover-effect"> 
						<div class="section parallax noborder center" style="background-image: url({{ asset('/img/miramarski/galerias/apto-standard.jpg') }}); padding: 40px 0; margin: 20px 0;" data-stellar-background-ratio="0.4">
							<h3 class="h2 text-center white text-white font-w800 wst" style="text-shadow: 1px 1px #000;">APARTAMENTO STANDARD</h3>
						</div>
					</a>
				</div>
				<div class=" row fadeInAppear">
					<a href="{{url('/apartamentos/estudio-lujo-sierra-nevada')}}" class=" hover-effect"> 
						<div class="section parallax noborder center" style="background-image: url({{ asset('/img/miramarski/galerias/estudio-lujo.jpg') }}); padding: 40px 0; margin: 20px 0;" data-stellar-background-ratio="0.4">
							<h3 class="h2 text-center white text-white font-w800 wst" style="text-shadow: 1px 1px #000;">ESTUDIO DE LUJO</h3>
						</div>
					</a>
				</div>
				<div class=" row fadeInAppear">
					<a href="{{url('/apartamentos/estudio-standard-sierra-nevada')}}" class=" hover-effect"> 
						<div class="section parallax noborder center" style="background-image: url({{ asset('/img/miramarski/galerias/estudio-standard.jpg') }}); padding: 40px 0; margin: 20px 0;" data-stellar-background-ratio="0.4">
							<h3 class="h2 text-center white text-white font-w800 wst" style="text-shadow: 1px 1px #000;">ESTUDIO STANDARD</h3>
						</div>
					</a>
				</div>
			</section>
			<section  class="page-section" style="letter-spacing: 0;line-height: 1; margin: 45px 0;">
				
				<div class="row">
					<div class="heading-block center">
						<h3 class="green">OTROS SERVICIOS</h3>
					</div>

					

					<a href="{{url('/actividades')}}" >
						<div class="section nomargin noborder center" style="background-image: url({{ asset('/img/miramarski/banners/actividades.jpg') }}); padding: 60px 0;" data-stellar-background-ratio="0.4">
								<h3 class="h3 text-center white text-white font-w800" style="text-shadow: 1px 1px #000;">¿QUÉ HACER EN SIERRA NEVADA?</h3>
								<span  class="text-center white text-white" style="text-shadow: 1px 1px #000;"></span>
						</div>
					</a>

					<a href="{{ url('/forfait') }}">
						<img src="{{asset('/img/miramarski/banners/descuentos.jpg')}}" class="img-responsive" />
					</a>
				</div>

			</section>
		<!-- END MOBILE -->
		<?php endif; ?>
    </div>
    
</section>
@endsection


@section('scripts')
<script type="text/javascript" src="https://cdn.rawgit.com/nnattawat/flip/master/dist/jquery.flip.min.js"></script>
<script type="text/javascript">


	$('#form-book-apto-lujo').submit(function(event) {

		event.preventDefault();

		

		var _token   = $('input[name="_token"]').val();
		var name     = $('input[name="name"]').val();
		var email    = $('input[name="email"]').val();
		var phone    = $('input[name="telefono"]').val();
		var date     = $('input[name="date"]').val();
		var quantity = $('select[name="quantity"]').val();
		var apto     = $('input:radio[name="apto"]:checked').val();
		var luxury   = $('input:radio[name="luxury"]:checked').val();
		var parking  = $('input:radio[name="parking"]:checked').val();
		var comment  = $('textarea[name="comment"]').val();

		var url = $(this).attr('action');

		$('#content-book-response').fadeOut('500');

		$.post( url , {_token : _token,  name : name,    email : email,   phone : phone,   date : date,    quantity : quantity, apto : apto, luxury : luxury,  parking : parking, comment : comment}, function(data) {
			
			$('#content-book-response').empty();
			$('#content-book-response').append(data).fadeIn('500');
			

		});

	});
	
	<?php if ($mobile->isMobile() || $mobile->isTablet()): ?>
		$('#banner-offert, .menu-booking').click(function(event) {
			$('#content-book').show('400');
			$('#banner-offert').hide();
			$('#line-banner-offert').hide();

			$('html, body').animate({
			       scrollTop: $("#content-book").offset().top 
			   }, 2000);
		});
	<?php else: ?>
		$('#banner-offert, .menu-booking').click(function(event) {
			$('#content-book').show('400');
			$('#banner-offert').hide();
			$('#line-banner-offert').hide();

			$('html, body').animate({
			       scrollTop: $("#content-book").offset().top - 85
			   }, 2000);
		});
	<?php endif; ?>


	$('#close-form-book').click(function(event) {
		$('#banner-offert').show();
		$('#line-banner-offert').show();
		$('#content-book').hide('100');
			$('html, body').animate({
			       scrollTop: $("body").offset().top
			   }, 2000);
	});


</script>	
@endsection