
@extends('layouts.master-landings')

@section('title') Evolutio - contacto @endsection

@section('content')

<style type="text/css">
	#tel:hover{
		color: green !important;
	}
	*::-webkit-input-placeholder {
    /* Google Chrome y Safari */
    color: rgba(255,255,255,0.5) !important;
	}
	*:-moz-placeholder {
	    /* Firefox anterior a 19 */
	    color: rgba(255,255,255,0.5) !important;
	}
	*::-moz-placeholder {
	    /* Firefox 19 y superior */
	    color: rgba(255,255,255,0.5) !important;
	}
	*:-ms-input-placeholder {
	    /* Internet Explorer 10 y superior */
	    color: rgba(255,255,255,0.5) !important;
	}
</style>

	<section id="section-contacto" class="page-section section parallax dark " style="background-image: url({{asset('/assets/images/gym/centro-entrenamiento-funcional-sin-maquina-villaviciosa-de-odon.jpg')}}); padding: 200px 0; margin-bottom:0;  margin-top: 0" data-stellar-background-ratio="0.3">

		<div class="container clearfix container-mobile">

			<div class="col-md-12 col-xs-12 not-padding-mobile">
				<div class="col-md-6 col-xs-12 hidden-xs hidden-sm" style="margin-bottom: 25px;">
					<div class="col-xs-12  black-cover">
						<div class="heading-block center push-5">
							<h4 class="text-center">
								<a href="#" data-toggle="modal" data-target=".mapa" style="cursor: pointer;color: white">
									<i class="icon-map"></i> Como llegar
								</a>
							</h4>
							<h4>Evolutio-Centro de entrenamiento </h4>
							<!-- <span>Some of our Clients love us &amp; so we do!</span> -->
						</div>
						<div class="col-xs-12 not-padding-mobile">
							<div class="col-xs-12 not-padding-mobile text-center">
								<p class="text-justify font-s18 font-w300">
									Avda Quitapesares nº20 nave 42ª<br>
									Pol. Ind. Villapark<br>
									28670 Villaviciosa de Odón<br>
									Teléfono: <a id="tel" href="tel:911723217" style="color:white" >911723217</a><br>
								</p>							
							</div>
						</div>
					</div>

				</div>

				<div class="col-md-6 col-xs-12 col_last black-cover" style="margin-bottom: 25px;" id="section-contacto-mobile">
					<?php if ($validator == 0): ?>
						<div class="col-xs-12 not-padding hidden-lg hidden-md">
							<div class="heading-block center push-5">
								<h4>Evolutio Human Training System</h4>
								<!-- <span>Some of our Clients love us &amp; so we do!</span> -->

							</div>
							<h3 class="text-center">
								<a href="#" data-toggle="modal" data-target=".mapa" style="cursor: pointer;color: white">
									<i class="icon-map"></i> Como llegar
								</a>
							</h3>
						</div>
						<div class="col-xs-12 not-padding" id="content-result-contact-form">
							<div class="heading-block center push-5">
								<h4>Contactanos</h4>
								<!-- <span>Some of our Clients love us &amp; so we do!</span> -->
							</div>
							<div class="col-xs-12" >
								<!-- action="{{url('/contacto')}}" method="post" -->
								<form class="nobottommargin" id="formulario" method="post" action="{{url('/contacto-form')}}" >
									{{ csrf_field() }}
									<div class="col_full">
										<input type="text" id="name" name="name" class="sm-form-control" required placeholder="Nombre">
									</div>

									<div class="col_full">
										<input type="email" id="email" name="email"  class="email sm-form-control" required placeholder="Email">
									</div>

									<div class="clear"></div>

									<div class="col_full">
										<input type="number" id="phone" name="phone" maxlength="9" class="sm-form-control" required placeholder="Teléfono">
									</div>

									<div class="col_full">
										<textarea required class="required sm-form-control" id="message" name="message" rows="3" cols="30" aria-required="true" placeholder="Mensaje"></textarea>
									</div>

									<div class="col_full">
										<button class="button button-3d nomargin" type="submit">Enviar</button>
									</div>

								</form>
							</div>
						</div>
					<?php elseif ($validator == 1): ?>
						<div class="col-xs-12 not-padding hidden-lg hidden-md">
							<div class="heading-block center push-5">
								<h4>Evolutio Human Training System</h4>
								<!-- <span>Some of our Clients love us &amp; so we do!</span> -->

							</div>
							<h3 class="text-center">
								<a href="#" data-toggle="modal" data-target=".mapa" style="cursor: pointer;color: white">
									<i class="icon-map"></i> Como llegar
								</a>
							</h3>
						</div>
						<div class="col-xs-12 not-padding" id="content-result-contact-form">
							<div class="heading-block center push-5">
								<h4>Contacto mandado</h4>
								<!-- <span>Some of our Clients love us &amp; so we do!</span> -->
							</div>
							<div class="col-xs-12" >
								<!-- action="{{url('/contacto')}}" method="post" -->
								<div class="col-xs-12 not-padding-mobile text-center">
								<p class="text-justify font-s18 font-w300">
									Muchas gracias por contactar con nosotros.<br>
									Nos pondremos en contacto con la mayor brevedad posible.</br>
								</p>							
							</div>
							</div>
						</div>
					<?php elseif ($validator == 2): ?>
						<div class="col-xs-12 not-padding hidden-lg hidden-md">
							<div class="heading-block center push-5">
								<h4>Evolutio Human Training System</h4>
								<!-- <span>Some of our Clients love us &amp; so we do!</span> -->

							</div>
							<h3 class="text-center">
								<a href="#" data-toggle="modal" data-target=".mapa" style="cursor: pointer">
									<i class="icon-map"></i> Como llegar
								</a>
							</h3>
						</div>
						<div class="col-xs-12 not-padding" id="content-result-contact-form">
							<div class="heading-block center push-5">
								<h4>Contacto no mandado</h4>
								<!-- <span>Some of our Clients love us &amp; so we do!</span> -->
							</div>
							<div class="col-xs-12" >
								<!-- action="{{url('/contacto')}}" method="post" -->
								<div class="col-xs-12 not-padding-mobile text-center">
								<p class="text-justify font-s18 font-w300">
									Ha habido un error.<br>
									Intentelo de nuevo mas tarde o Puede llamarnos al <br>
									<a id="tel" href="tel:911723217" style="color:white" >911723217</a>
								</p>							
							</div>
							</div>
						</div>
					<?php endif ?>
					
				</div>

			</div>
		</div>
	</section>

@endsection

@include('pop-up')
