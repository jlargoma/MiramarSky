@extends('layouts.master_withoutslider')
@section('metadescription') El tiempo en Sierra Nevada @endsection
@section('title') El tiempo en Sierra Nevada @endsection

<link rel="stylesheet" href="{{ asset('/frontend/css/components/daterangepicker.css')}}" type="text/css" />
<link rel="stylesheet" href="{{ asset('/frontend/css/components/radio-checkbox.css')}}" type="text/css" />

<style type="text/css">
	#primary-menu ul li  a{
		color: #3F51B5!important;
	}
	#primary-menu ul li  a div{
		text-align: left!important;
	}
	label{
		color: white!important
	}
	#content-form-book {
    	padding: 40px 15px;
	}

	.content-widget, .content-widget iframe{
		width: 100%!important;
	}

	.new32{
		width: 100%!important;
	}
	.nomP{
		display: none;
	}
	table.fondo{
		width: 100%!important;

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
			position: fixed!important;
		    top: 15%!important;
		}
		.img{
			max-height: 530px;
		}
		.button.button-desc.button-3d{
			background-color: #4cb53f!important;
		}
	}
	
</style>


@section('content')
	
	<section id="content">

		<div class="container container-mobile clearfix push-0">
			<div class="row">
				<h1 class="center green push-20">EL TIEMPO EN SIERRA NEVADA</h2>
			</div>
		</div>
		
		<div class="row clearfix  push-30">
			<div class="col-xs-12 col-md-5">
				<iframe src="https://www.meteoblue.com/en/weather/widget/three/sierra-nevada_spain_2513236?geoloc=fixed&nocurrent=0&noforecast=0&days=4&tempunit=CELSIUS&windunit=KILOMETER_PER_HOUR&layout=image"  frameborder="0" scrolling="NO" allowtransparency="true" sandbox="allow-same-origin allow-scripts allow-popups" style="width: 100%;height: 595px"></iframe><div><!-- DO NOT REMOVE THIS LINK --><a href="https://www.meteoblue.com/en/weather/forecast/week/sierra-nevada_spain_2513236?utm_source=weather_widget&utm_medium=linkus&utm_content=three&utm_campaign=Weather%2BWidget" target="_blank">meteoblue</a></div>
			</div>

			<div class="col-xs-12 col-md-7 clearfix center">

				<div class="col-md-12 push-0 not-padding-mobile">
					<p class="text-justify font-s16 ls-15">
						<b>Te mostramos la Previsión del tiempo para la Estación de Esquí de Sierra Nevada.</b><br><br>
						En este grafico podrás comprobar el parte meteorológico en Sierra Nevada, la temperatura para hoy, mañana y próximos días.<br>
						<u>Durante la temporada de esquí en zonas de montaña conviene también revisar el estado en el que se encuentran las carreteras</u>, además de boletines de información nivológica y peligro de aludes.<br><br>
						Para acceder a pronósticos de tiempo para la cumbre, la mitad y la base de <b>Sierra Nevada</b> u otras alturas, <a href="#">Pinche aquí</a> y le mostraremos modelos meteorológicos más sofisticados.<br><br>

						<b>Si lo necesitas, tenemos una amplia oferta en alquiler de apartamentos, todos ellos situados en la zona baja de la estación, en el edificio más moderno de Sierra Nevada.</b><br><br>
						El edificio Miramarski se encuentran a 5 minutos de la plaza de Andalucía, (Pradollano).<br><br>

						<b>Te ofrecemos apartamentos de dos dormitorios o estudios, con Piscina climatizada, gimnasio, Parking cubierto, taquilla guarda esquíes, acceso directo a las pistas.</b><br>
						Tenemos apartamentos de dos habitaciones ( ocupación 6 / 8 personas) y también estudios (ocupación 4 / 5 personas).<br><br>
						En todas las reservas incluimos las sabanas y toallas y un obsequio de bienvenida. <br><br>

						<b>Pincha en el <span style="color:blue; cursor: pointer"> botón de reserva</span> para calcular el coste de tu petición y si lo consideras hacer tu solicitud.</b>

					</p>
				</div>
				
				<?php if (!$mobile->isMobile()): ?>
					<button id="showFormBook" class="button button-desc button-3d button-rounded bg-bluesky center white" >¡Reserva YA!</button>

				<?php endif; ?>
			</div>
		</div>
		<?php if (!$mobile->isMobile()): ?>
		<div id="content-form-book" class="row bg-bluesky push-30" style="display: none; background-image: url({{asset('/img/miramarski/esquiadores.png')}}); background-position: left bottom; background-repeat: no-repeat; background-size: 45%;">

			<span style="padding: 0 5px; cursor: pointer; opacity: 1" class="close pull-right white text-white sm-m-r-20 sm-m-t-10">
				<i class="fa fa-times"></i>
			</span>

			<div class="container clearfix">
				<div class="col-md-6 col-md-offset-3">
					<div class="row" id="content-book-response">
						<div class="front" style="max-height: 520px!important;">
							<div class="col-xs-12">
								<h3 class="text-center white">FORMULARIO DE RESERVA</h3>
							</div>
							<div id="form-content">
								@include('frontend._formBook')
							</div>
						</div>
						<div class="back" style="background-color: #3F51B5; max-height: 520px!important;">
							
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php else: ?>
			<div id="content-form-book" class="col-xs-12 bg-bluesky push-30" style="display: none;">

				<span style="padding: 0 5px; cursor: pointer; opacity: 1" class="close pull-right white text-white sm-m-r-20 sm-m-t-10">
					<i class="fa fa-times"></i>
				</span>
				
				<div class="container-mobile clearfix" style="margin-top: 10px;">
					<div class="col-md-6 col-md-offset-3">
						<div class="row" id="content-book-response">
							<div class="front" style="max-height: 520px!important;">
								<div class="col-xs-12">
									<h3 class="text-center white">FORMULARIO DE RESERVA</h3>
								</div>
								<div id="form-content">
									@include('frontend._formBook')
								</div>
							</div>
							<div class="back" style="background-color: #3F51B5; max-height: 520px!important;">
								
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<div class="col-xs-12">
			<h3 class="text-center font-w300">
				OTROS <span class="green font-w800">APARTAMENTOS</span>
			</h3>
		</div>
		<div class="col-xs-12">
			<?php foreach ($aptos as $key => $apartamento): ?>
				<div class="col-md-3 not-padding-mobile hover-effect">
					<a href="{{url('/apartamentos')}}/<?php echo $apartamento ?>">
						<div class="col-xs-12 not-padding  container-image-box push-mobile-20">
							<div class="col-xs-12 not-padding push-0">
								
								<img class="img-responsive" src="/img/miramarski/small/<?php echo $apartamento ?>.jpg"  alt="<?php echo str_replace('-', ' ', $apartamento) ?>"/>
							</div>
							<div class="col-xs-12 not-padding text-right overlay-text">
								<h2 class="font-w600 center push-10 text-center text white font-s24 hvr-reveal" >
									<?php 
										$title    = str_replace('-', ' ', $apartamento);
										$title    = str_replace(' sierra nevada', '', $title);
									?>
									<?php echo strtoupper($title ) ?>
								</h2>
							</div>
						</div>
					</a>
				</div>
			<?php endforeach ?>
		</div>


		<div id="fixed-book" class="col-xs-12 text-center center hidden-lg hidden-md bg-white" style="position: fixed; bottom: 0px; width: 100%; background-color: #FFF!important; padding: 15px;">
			<button id="showFormBook" class="button button-desc button-3d button-rounded bg-bluesky center white" style="background-color: #4cb53f!important; width: 60%; margin: 0px auto;z-index: 90">¡Reserva YA!</button>

		</div>
	</section>
	
@endsection