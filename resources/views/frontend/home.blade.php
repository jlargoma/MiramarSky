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
			<div class="row clearfix" style="background-color: #3F51B5; background-image: url({{asset('/img/miramarski/esquiadores.png')}}); background-position: left bottom; background-repeat: no-repeat; background-size: 50%;">
   				<div id="close-form-book" style="position: absolute; top: 20px; right: 10px; z-index: 50;  cursor: pointer;">
   					<span class="white text-white"><i class="fa fa-times fa-2x"></i></span>
   				</div>
   				<div id="content-book" class="container clearfix push-10" style="display: none; min-height: 615px;">	
					<div class="container clearfix"  style="padding: 20px 0;">
						<div class="row">
							<div class="col-md-6 col-md-offset-3">
								<div class="row" id="content-book-response">
									<div class="front" style="min-height: 550px;">
										<div class="col-xs-12">
											<h3 class="text-center white">FORMULARIO DE RESERVA</h3>
										</div>
										<div id="form-content">
	   										@include('frontend._formBook')
	   									</div>
									</div>
									<div class="back" style="background-color: #3F51B5; min-height: 550px;">
										
									</div>
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
					
		       			<div class="col-lg-6 col-md-7 center fadeInUp animated" data-animation="fadeInUp" style="padding: 40px 15px;">
		       				<div class="col-xs-12 push-20">
		       					<div class="heading-block  black" style="margin-bottom: 20px">
		       						<h1 class="font-w800 black" style="letter-spacing: 0; font-size: 26px;">APARTAMENTOS DE LUJO <span class="font-w800 green">A PIE DE PISTA</span></h1>
		       					</div>
		       					<p class="lead  text-justify black font-s14 black" style="line-height: 1.2">
		       						Todos nuestros Apartamentos están en el Edificio Miramar Ski, situado <b>en la zona baja de Sierra Nevada</b>. Tienen excelentes vistas y todos disponen del equipamiento completo.

		       					</p>
		       					<h2 class="font-w300 text-center black push-18" style="line-height: 1;font-size: 16px!important; text-transform: uppercase;">
		       						<span class="font-w800 black"> El edificio tiene salida directa a las pistas, solo tienes que salir de casa y esquiar!!</span>
		       					</h2>
								
		       					<p class="lead  text-justify black font-s14 black" style="line-height: 1.2">
		       						Se encuentran <b>a 5 minutos andando de la plaza de Andalucía</b>, en Pradollano centro neurálgico de la estación.<br><br>

									<b>Piscina climatizada, gimnasio, parking cubierto, taquilla guardaesquis, acceso directo a las pistas.</b>

		       						A pocos metros, tienes varios supermercados, bares y restaurantes, lo que es muy importante para que disfrutes tus vacaciones sin tener que coger el coche ni remontes<br><br>

		       						Nuestro edificio Miramar Ski es <b>uno de los edificios más modernos de Sierra Nevada</b> (2006).<br><br>

									Tenemos disponibles <b>apartamentos con dos habitaciones</b> ( ocupación 6 / 8 pers) y también <b>estudios</b> (ocupación 4 / 5 pers).<br><br>

									Dentro de estos dos modelos, podrás  elegir entre los estándar y de lujo, que están recién reformados.<br><br>

									<b>En todas las reservas las sabanas y toallas están incluidas</b><br><br>

									Queremos ofrecerte un servicio especial, por eso incluimos en todas nuestras reservas un obsequio de bienvenida. <br><br>

									<b>Para tu comodidad, te llevamos los fortfaits a tu apartamento para evitarle las largas filas de la temporada alta</b><br><br>
									

									<b>En el botón de <span class="menu-booking">reserva</span>  podrás calcular el coste de tu petición y si lo consideras hacer tu solicitud de disponibilidad.</b>

		       					</p>
		       				</div>
		       				<div class="col-xs-12 clearfix">

				       			<div id="oc-clients" class="owl-carousel image-carousel carousel-widget" data-margin="60" data-loop="true" data-nav="false" data-autoplay="1000" data-pagi="false" data-items-xxs="2" data-items-xs="3" data-items-sm="6" data-items-md="8" data-items-lg="8">

				       				<div class="oc-item">
				       					<a class="pc-characteristics">
				       						<img src="{{ asset('/img/miramarski/iconos/small/teleesqui.png') }}" alt="teleesqui" /> A pie de pista 
				       					</a>
				       				</div>
				       				<div class="oc-item">
				       					<a class="pc-characteristics">
				       						<img src="{{ asset('/img/miramarski/iconos/small/parking.png') }}" alt="parking" /> Parking cubierto 
				       					</a>
				       				</div>
				       				<div class="oc-item">
				       					<a class="pc-characteristics">
				       						<img src="{{ asset('/img/miramarski/iconos/small/ascensor.png') }}" alt="ascensor" /> Ascensor 
				       					</a>
				       				</div>
				       				<div class="oc-item">
				       					<a class="pc-characteristics">
				       						<img src="{{ asset('/img/miramarski/iconos/small/piscina.png') }}" alt="piscina" /> Piscina climatizada 
				       					</a>
				       				</div>
				       				<div class="oc-item">
				       					<a class="pc-characteristics">
				       						<img src="{{ asset('/img/miramarski/iconos/small/gimnasio.png') }}" alt="gimnasio" /> gimnasio 
				       					</a>
				       				</div>
				       				<div class="oc-item">
				       					<a class="pc-characteristics">
				       						<img src="{{ asset('/img/miramarski/iconos/small/guardaesqui.png') }}" alt="guardaesqui" /> Guarda Esqíes 
				       					</a>
				       				</div>
				       				<div class="oc-item">
				       					<a class="pc-characteristics">
				       						<img src="{{ asset('/img/miramarski/iconos/small/mascotas.png') }}" alt="mascotas" /> Prohibido mascotas 
				       					</a>
				       				</div>
				       				<div class="oc-item">
				       					<a class="pc-characteristics">
				       						<img src="{{ asset('/img/miramarski/iconos/small/ropa-toallas.png') }}" alt="ropa-toallas" /> Ropa y toallas 
				       					</a>
				       				</div>
				       				<div class="oc-item">
				       					<a class="pc-characteristics">
				       						<img src="{{ asset('/img/miramarski/iconos/small/cocina.png') }}" alt="cocina" /> Cocina 
				       					</a>
				       				</div>
				       				<div class="oc-item">
				       					<a class="pc-characteristics">
				       						<img src="{{ asset('/img/miramarski/iconos/small/ducha.png') }}" alt="ducha" /> Baño 
				       					</a>
				       				</div>
				       				<div class="oc-item">
				       					<a class="pc-characteristics">
				       						<img src="{{ asset('/img/miramarski/iconos/small/calefaccion.png') }}" alt="calefaccion" /> Calefaccion 
				       					</a>
				       				</div>
				       				<div class="oc-item">
				       					<a class="pc-characteristics">
				       						<img src="{{ asset('/img/miramarski/iconos/small/shopping.png') }}" alt="shopping" /> Shopping 
				       					</a>
				       				</div>

				       			</div>


	       					</div>
		       			</div>

		       			<div class="col-lg-6 col-md-5 center  hidden-sm hidden-xs"  style="padding: 40px 15px;">

	       					<div class="fslider" data-easing="easeInQuad">
								<div class="flexslider">
									<div class="slider-wrap">

										<?php foreach ($slidesEdificio as $key => $slide): ?>
											<?php $fotos = explode(",", $slide->getFilename()) ?>
											<?php if (isset($fotos[1])): ?>
												<div class="slide" data-thumb="{{ asset('/img/miramarski/edificio/piscina_climatizadalquiler_apartamento_sierra_nevada_miraramarski.jpg') }}">
													<a href="#">
														<img src="{{ asset('/img/miramarski/edificio/')}}/<?php echo $slide->getFilename() ?>" alt="<?php echo $fotos[2] ?>" title="<?php echo $fotos[3] ?>" style="height: 450px;">
														<div class="flex-caption slider-caption-bg">Fotos del edificio</div>
													</a>
												</div>
											<?php else: ?>
												<div class="slide" data-thumb="{{ asset('/img/miramarski/edificio/piscina_climatizadalquiler_apartamento_sierra_nevada_miraramarski.jpg') }}">
													<a href="#">
														<img src="{{ asset('/img/miramarski/edificio/')}}/<?php echo $slide->getFilename() ?>" alt="<?php echo $fotos[2] ?>" title="<?php echo $fotos[3] ?>" style="height: 450px;">
														<div class="flex-caption slider-caption-bg">Fotos del edificio</div>
													</a>
												</div>
											<?php endif ?>
										<?php endforeach ?>
									</div>
								</div>
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
		   								<h2 class="font-w600 center push-10 text-center text font-s24 white hvr-reveal" style="padding: 65px 85px;width: 90%;">APARTAMENTO<br>2 DORMITORIOS DE LUJO
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
		   								<h2 class="font-w600 center push-10 text-center text font-s24 white hvr-reveal" style="padding: 65px 85px;width: 90%;">
		   									APARTAMENTO<br>2 DORMITORIOS STANDARD
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
	   						<a href="{{ url('/forfait')}}" target="_blank">
		   						<div class="col-xs-12 not-padding  container-image-box">
		   							<div class="col-xs-12 not-padding push-0">
	   									<img class="img-responsive imga" src="{{ asset('/img/miramarski/descuento-forfait.jpg')}}" alt="Apartamento de lujo sierra nevada"/>
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
		   					<a href="{{ url('/actividades')}}">
		   						<div class="col-xs-12 not-padding  container-image-box">
		   							<div class="col-xs-12 not-padding push-0">
		   								
	   									<img class="img-responsive" src="{{ asset('/img/miramarski/ski-con-niños.jpg')}}"  alt="Estudio de lujo sierra nevada"/>
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

	       	<section class="page-section" style="margin-top: 60px;">
	       		<div class="container container-mobile clearfix">
	       			<div class="row">
	       				<div class="col-xs-12">
	       					<p class="text-justify black font-s14 font-w300" style="line-height: 1.3">
	       						<span class="font-w600 font-s16">Sabemos lo importante que son tus vacaciones de esqui, por eso cuidamos cada detalle al máximo, intentando siempre conseguir que tu estancia sea lo más agradable posible.</span>
	       						<br><br>
	       						<b>En nuestra web encontraras una oferta de calidad</b>, en Sierra Nevada la oferta de alojamiento es muy dispar puediendo encontrarse apartamentos muy viejos y en mal estado,<b> no es nuestro caso, <u>todos nuestros apartamentos se revisan y actualizan cada temporada</u></b>
	       						<br><br>
	       						Nuestros apartamentos ( de dos dormitorios y estudios) están ubicados en la mejor zona, Tu experiencia será de máximo confort:<br> <b> Piscina climatizada, gimnasio, ascensor , taquilla guarda esquíes, Parking cubierto, ropa de cama incluida</b>.
	       						<br><br>
	       						<b>A pie de pista... Sin coger remontes. El acceso a las pistas es directo, todo pensado para que disfrutes.</b>
	       						<br><br>
	       						Tambien <b>te ofrecemos nuestros descuentos para la compra de Forfaits, ofertas en alquiler de material y packs cobinados con cursillos de Ski.</b>
	       						<br><br>
	       						Si quieres saber más información sobre la estación de Sierra Nevada <b><a href="{{url('/actividades')}}">consulta nuestro blog</a></b>,  encontraras información sobre la estación, cosas que hacer,actividades de esqui y de a preski,  que visitar, como divertirte con los niños, bares y restaurantes de la estación y de Padrollano….etc
	       					</p>
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
				.degradado-background1{
					background: rgba(251,0,71,1);
					background: -moz-linear-gradient(45deg, rgba(251,0,71,1) 0%, rgba(167,12,245,1) 53%, rgba(167,12,245,1) 54%, rgba(167,12,245,1) 69%, rgba(48,49,199,1) 100%);
					background: -webkit-gradient(left bottom, right top, color-stop(0%, rgba(251,0,71,1)), color-stop(53%, rgba(167,12,245,1)), color-stop(54%, rgba(167,12,245,1)), color-stop(69%, rgba(167,12,245,1)), color-stop(100%, rgba(48,49,199,1)));
					background: -webkit-linear-gradient(45deg, rgba(251,0,71,1) 0%, rgba(167,12,245,1) 53%, rgba(167,12,245,1) 54%, rgba(167,12,245,1) 69%, rgba(48,49,199,1) 100%);
					background: -o-linear-gradient(45deg, rgba(251,0,71,1) 0%, rgba(167,12,245,1) 53%, rgba(167,12,245,1) 54%, rgba(167,12,245,1) 69%, rgba(48,49,199,1) 100%);
					background: -ms-linear-gradient(45deg, rgba(251,0,71,1) 0%, rgba(167,12,245,1) 53%, rgba(167,12,245,1) 54%, rgba(167,12,245,1) 69%, rgba(48,49,199,1) 100%);
					background: linear-gradient(45deg, rgba(251,0,71,1) 0%, rgba(167,12,245,1) 53%, rgba(167,12,245,1) 54%, rgba(167,12,245,1) 69%, rgba(48,49,199,1) 100%);
					filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#fb0047', endColorstr='#3031c7', GradientType=1 );
				}
				.degradado-background2{
					background: rgba(251,0,71,1);
					background: -moz-linear-gradient(45deg, rgba(251,0,71,1) 0%, rgba(185,12,212,1) 31%, rgba(167,12,245,1) 50%, rgba(167,12,245,1) 63%, rgba(48,49,199,1) 100%);
					background: -webkit-gradient(left bottom, right top, color-stop(0%, rgba(251,0,71,1)), color-stop(31%, rgba(185,12,212,1)), color-stop(50%, rgba(167,12,245,1)), color-stop(63%, rgba(167,12,245,1)), color-stop(100%, rgba(48,49,199,1)));
					background: -webkit-linear-gradient(45deg, rgba(251,0,71,1) 0%, rgba(185,12,212,1) 31%, rgba(167,12,245,1) 50%, rgba(167,12,245,1) 63%, rgba(48,49,199,1) 100%);
					background: -o-linear-gradient(45deg, rgba(251,0,71,1) 0%, rgba(185,12,212,1) 31%, rgba(167,12,245,1) 50%, rgba(167,12,245,1) 63%, rgba(48,49,199,1) 100%);
					background: -ms-linear-gradient(45deg, rgba(251,0,71,1) 0%, rgba(185,12,212,1) 31%, rgba(167,12,245,1) 50%, rgba(167,12,245,1) 63%, rgba(48,49,199,1) 100%);
					background: linear-gradient(45deg, rgba(251,0,71,1) 0%, rgba(185,12,212,1) 31%, rgba(167,12,245,1) 50%, rgba(167,12,245,1) 63%, rgba(48,49,199,1) 100%);
					filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#fb0047', endColorstr='#3031c7', GradientType=1 );
				}
			</style>
	       	<section class="page-section degradado-background1" style="letter-spacing: 0;line-height: 1;color: #fff!important;">
	   		
	   			<div class="row degradado-background1" style="">
	   				
	   				<div id="content-book" class="container-mobile clearfix push-10" style="display: none; ">	
	   					<div id="close-form-book" style="position: absolute; top: 20px; right: 10px; z-index: 50;  cursor: pointer;">
		   					<span class="white text-white"><i class="fa fa-times fa-2x"></i></span>
		   				</div>
						<div class="container clearfix"  style="padding: 20px 0;">
							<div class="row">
								<div class="col-md-6 col-md-offset-3">
									<div class="row" id="content-book-response">
										<div class="front" style="min-height: 550px;">
											<div class="col-xs-12">
												<h3 class="text-center white">FORMULARIO DE RESERVA</h3>
											</div>
											<div id="form-content">
		   										@include('frontend._formBook')
		   									</div>
										</div>
										<div class="back degradado-background1" style="min-height: 550px;">
											
										</div>
									</div>
								</div>
							</div>
						</div>
	   				</div>
	   			</div>

	       	</section>
			
			<section id="desc-section" class="page-section" style="letter-spacing: 0;line-height: 1; ">
				<div class="col-xs-12 degradado-background2" style="padding: 30px 0 0;">
					<div class="col-xs-12 white" style="margin-bottom: 20px">
   						<h1 class="font-w800 white center " style="letter-spacing: 0; margin-bottom: 10px; line-height: 1;font-size: 24px">
   							APARTAMENTOS DE LUJO<br>A PIE DE PISTA
   						</h1>
   					
       					<p class="lead  text-justify white ls-15 font-s13 white">
       						Todos nuestros Apartamentos están en el Edificio Miramar Ski, situado <b>en la zona baja de Sierra Nevada</b>. <br>Tienen excelentes vistas y todos disponen del equipamiento completo.
       					</p>
       					<h2 class="font-w300 text-center nobottommargin white push-20" style="line-height: 1;font-size: 16px!important; text-transform: uppercase;">
       						<span class="font-w800 white">Edificio con salida directa a las pistas</span><br>
       						<span class="font-s14 white">solo tienes que salir de casa y esquiar!!</span>
       					</h2>
					</div>
       				<div class="col-xs-12 clearfix push-20">

		       			<div id="oc-clients" class="owl-carousel image-carousel carousel-widget" data-margin="60" data-loop="true" data-nav="false" data-autoplay="3000" data-pagi="false" data-items-xxs="4" data-items-xs="4" data-items-sm="6" data-items-md="6" data-items-lg="8">

		       				<div class="oc-item">
		       					<a class="pc-characteristics white center font-s12">
		       						<img src="{{ asset('/img/miramarski/iconos/small/white-piepista.png') }}" /> A pie de pista 
		       					</a>
		       				</div>
		       				<div class="oc-item">
		       					<a class="pc-characteristics white center font-s12">
		       						<img src="{{ asset('/img/miramarski/iconos/small/white-parking.png') }}" /> Parking cubierto 
		       					</a>
		       				</div>
		       				<div class="oc-item">
		       					<a class="pc-characteristics white center font-s12">
		       						<img src="{{ asset('/img/miramarski/iconos/small/white-ascensor.png') }}" /> Ascensor 
		       					</a>
		       				</div>
		       				<div class="oc-item">
		       					<a class="pc-characteristics white center font-s12">
		       						<img src="{{ asset('/img/miramarski/iconos/small/white-piscina.png') }}" /> Piscina  
		       					</a>
		       				</div>
		       				<div class="oc-item">
		       					<a class="pc-characteristics white center font-s12">
		       						<img src="{{ asset('/img/miramarski/iconos/small/white-gimnasio.png') }}" /> Gimnasio 
		       					</a>
		       				</div>
		       				<div class="oc-item">
		       					<a class="pc-characteristics white center font-s12">
		       						<img src="{{ asset('/img/miramarski/iconos/small/white-guardaesqui.png') }}" /> Guarda Esqíes 
		       					</a>
		       				</div>
		       				<div class="oc-item">
		       					<a class="pc-characteristics white center font-s12">
		       						<img src="{{ asset('/img/miramarski/iconos/small/white-mascotas.png') }}" /> No mascotas 
		       					</a>
		       				</div>
		       				<div class="oc-item">
		       					<a class="pc-characteristics white center font-s12">
		       						<img src="{{ asset('/img/miramarski/iconos/small/white-ropa-toallas.png') }}" /> Ropa y toallas 
		       					</a>
		       				</div>
		       				<div class="oc-item">
		       					<a class="pc-characteristics white center font-s12">
		       						<img src="{{ asset('/img/miramarski/iconos/small/white-cocina.png') }}" /> Cocina 
		       					</a>
		       				</div>
		       				<div class="oc-item">
		       					<a class="pc-characteristics white center font-s12">
		       						<img src="{{ asset('/img/miramarski/iconos/small/white-ducha.png') }}" /> Baño 
		       					</a>
		       				</div>
		       				<div class="oc-item">
		       					<a class="pc-characteristics white center font-s12">
		       						<img src="{{ asset('/img/miramarski/iconos/small/white-calefaccion.png') }}" /> Calefaccion 
		       					</a>
		       				</div>
		       				<div class="oc-item">
		       					<a class="pc-characteristics white center font-s12">
		       						<img src="{{ asset('/img/miramarski/iconos/small/white-shopping.png') }}" /> Shopping 
		       					</a>
		       				</div>

		       			</div>


   					</div>

   					<div class="col-xs-12">
       					<p class="lead  text-justify font-s13 white ls-5">
       						Se encuentran <b>a 5 minutos andando de la plaza de Andalucía</b>, en Pradollano centro neurálgico de la estación.<br><br>

							<b>Piscina climatizada, gimnasio, parking cubierto, taquilla guardaesquis, acceso directo a las pistas.</b>

       						A pocos metros, tienes varios supermercados, bares y restaurantes, lo que es muy importante para que disfrutes tus vacaciones sin tener que coger el coche ni remontes<br><br>

       						Nuestro edificio Miramar Ski es <b>uno de los edificios más modernos de Sierra Nevada</b> (2006).<br><br>

							Tenemos disponibles <b>apartamentos con dos habitaciones</b> ( ocupación 6 / 8 pers) y también <b>estudios</b> (ocupación 4 / 5 pers).<br><br>

							Dentro de estos dos modelos, podrás  elegir entre los estándar y de lujo, que están recién reformados.<br><br>

							<b>En todas las reservas las sabanas y toallas están incluidas</b><br><br>

							Queremos ofrecerte un servicio especial, por eso incluimos en todas nuestras reservas un obsequio de bienvenida. <br><br>

							<b>Para tu comodidad, te llevamos los fortfaits a tu apartamento para evitarle las largas filas de la temporada alta</b>
       					</p>
   					</div>
					<div class="col-xs-12 push-20">
       					<div class="fslider" data-easing="easeInQuad">
							<div class="flexslider">
								<div class="slider-wrap">

									<?php foreach ($slidesEdificio as $key => $slide): ?>
										<?php $fotos = explode(",", $slide->getFilename()) ?>
										<?php if (isset($fotos[1])): ?>
											<div class="slide" data-thumb="{{ asset('/img/miramarski/edificio/piscina_climatizadalquiler_apartamento_sierra_nevada_miraramarski.jpg') }}">
												<a href="#">
													<img src="{{ asset('/img/miramarski/edificio/')}}/<?php echo $slide->getFilename() ?>" alt="<?php echo $fotos[2] ?>" title="<?php echo $fotos[3] ?>" style="height: 250px;">
													<div class="flex-caption slider-caption-bg">Fotos del edificio</div>
												</a>
											</div>
										<?php else: ?>
											<div class="slide" data-thumb="{{ asset('/img/miramarski/edificio/piscina_climatizadalquiler_apartamento_sierra_nevada_miraramarski.jpg') }}">
												<a href="#">
													<img src="{{ asset('/img/miramarski/edificio/')}}/<?php echo $slide->getFilename() ?>" alt="<?php echo $fotos[2] ?>" title="<?php echo $fotos[3] ?>" style="height: 250px;">
													<div class="flex-caption slider-caption-bg">Fotos del edificio</div>
												</a>
											</div>
										<?php endif ?>
									<?php endforeach ?>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xs-12 push-20">
						<p class="lead  text-justify white ls-15 font-s13 white" >
							En el <span class="menu-booking"><b><u>botón de reserva</u></b></span>  podrás calcular el coste de tu petición y si lo consideras hacer tu solicitud de disponibilidad.
						</p>
					</div>

				</div>
				
			</section>

			<div style="clear: both;"></div>

			<section  class="page-section" style="letter-spacing: 0;line-height: 1; margin: 45px 0;">
				<div class="heading-block center push-20">
					<h3 class="green">GALERÍA DE APARTAMENTOS</h3>
				</div>
				<div class=" row animatable" data-aos="zoom-in">
					<a href="{{url('/apartamentos/apartamento-lujo-sierra-nevada')}}" > 
						<div class="section parallax noborder center" style="background-image: url({{ asset('/img/miramarski/galerias/apto-lujo.jpg') }}); padding: 40px 0; margin: 20px 0;" data-stellar-background-ratio="0.4">
							<h3 class="h2 text-center white text-white font-w800 wst" style="text-shadow: 1px 1px #000;">2 DORMITORIOS<br> DE LUJO</h3>
						</div>
					</a>
				</div>
				<div class=" row animatable" data-aos="zoom-in">
					<a href="{{url('/apartamentos/apartamento-standard-sierra-nevada')}}" > 
						<div class="section parallax noborder center" style="background-image: url({{ asset('/img/miramarski/galerias/apto-standard.jpg') }}); padding: 40px 0; margin: 20px 0;" data-stellar-background-ratio="0.4">
							<h3 class="h2 text-center white text-white font-w800 wst" style="text-shadow: 1px 1px #000;">2 DORMITORIOS STANDARD</h3>
						</div>
					</a>
				</div>
				<div class=" row animatable" data-aos="zoom-in">
					<a href="{{url('/apartamentos/estudio-lujo-sierra-nevada')}}"> 
						<div class="section parallax noborder center" style="background-image: url({{ asset('/img/miramarski/galerias/estudio-lujo.jpg') }}); padding: 40px 0; margin: 20px 0;" data-stellar-background-ratio="0.4">
							<h3 class="h2 text-center white text-white font-w800 wst" style="text-shadow: 1px 1px #000;">ESTUDIO DE LUJO</h3>
						</div>
					</a>
				</div>
				<div class=" row animatable" data-aos="zoom-in">
					<a href="{{url('/apartamentos/estudio-standard-sierra-nevada')}}"> 
						<div class="section parallax noborder center" style="background-image: url({{ asset('/img/miramarski/galerias/estudio-standard.jpg') }}); padding: 40px 0; margin: 20px 0;" data-stellar-background-ratio="0.4">
							<h3 class="h2 text-center white text-white font-w800 wst" style="text-shadow: 1px 1px #000;">ESTUDIO STANDARD</h3>
						</div>
					</a>
				</div>
			</section>

			<section  class="page-section" style="letter-spacing: 0;line-height: 1; margin: 45px 0;">
				
				<div class="row">
					<h2 class="text-center black font-w300">
	   					OTROS <span class="font-w800 green ">SERVICIOS</span>
	   				</h2>
	   				<div class="col-md-12 col-xs-12">

	   					<div class="col-md-4 col-xs-12 push-mobile-20 hover-effect">
	   						<a href="{{ url('/forfait')}}" target="_blank">
		   						<div class="col-xs-12 not-padding  container-image-box">
		   							<div class="col-xs-12 not-padding push-0">
	   									<img class="img-responsive imga" src="{{ asset('/img/miramarski/descuento-forfait.jpg')}}" alt="Apartamento de lujo sierra nevada"/>
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
		   					<a href="{{ url('/actividades')}}">
		   						<div class="col-xs-12 not-padding  container-image-box">
		   							<div class="col-xs-12 not-padding push-0">
		   								
	   									<img class="img-responsive" src="{{ asset('/img/miramarski/ski-con-niños.jpg')}}"  alt="Estudio de lujo sierra nevada"/>
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

			<section class="page-section" style="margin-top: 60px;">
				<div class="col-xs-12">
					<p class="text-justify black font-s14 font-w300" style="line-height: 1.3">
						<span class="font-w600 font-s16">Sabemos lo importante que son tus vacaciones de esqui, por eso cuidamos cada detalle al máximo, intentando siempre conseguir que tu estancia sea lo más agradable posible.</span>
						<br><br>
						<b>En nuestra web encontraras una oferta de calidad</b>, en Sierra Nevada la oferta de alojamiento es muy dispar puediendo encontrarse apartamentos muy viejos y en mal estado,<b> no es nuestro caso, <u>todos nuestros apartamentos se revisan y actualizan cada temporada</u></b>
						<br><br>
						Nuestros apartamentos ( de dos dormitorios y estudios) están ubicados en la mejor zona, Tu experiencia será de máximo confort:<br> <b> Piscina climatizada, gimnasio, ascensor , taquilla guarda esquíes, Parking cubierto, ropa de cama incluida</b>.
						<br><br>
						<b>A pie de pista... Sin coger remontes. El acceso a las pistas es directo, todo pensado para que disfrutes.</b>
						<br><br>
						Tambien <b>te ofrecemos nuestros descuentos para la compra de Forfaits, ofertas en alquiler de material y packs cobinados con cursillos de Ski.</b>
						<br><br>
						Si quieres saber más información sobre la estación de Sierra Nevada <b><a href="{{url('/actividades')}}">consulta nuestro blog</a></b>,  encontraras información sobre la estación, cosas que hacer,actividades de esqui y de a preski,  que visitar, como divertirte con los niños, bares y restaurantes de la estación y de Padrollano….etc
					</p>
				</div>

			</section>


		<!-- END MOBILE -->
		<?php endif; ?>

			
    </div>
    
</section>
@endsection

