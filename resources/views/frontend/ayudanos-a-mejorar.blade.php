@extends('layouts.master_withoutslider')

@section('metadescription') Ayudanos a Mejorar - apartamentosierranevada.net @endsection
@section('title')  Ayudanos a Mejorar - apartamentosierranevada.net @endsection

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
				<h1 class="center psuh-20">Ayudanos a Mejorar</h1>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 center" >
				
				<p class="texto-aparamento " style="text-align: justify;color:black;font-size:16px;padding:0px 30px;" >
					
					Nuestro objetivo es conseguir tu máxima satisfacción, en lo que respecta a la reserva de tu apartamento en Miramarski Sierra Nevada, como en el momento de disfrutar del apartamento contratado para pasar tus vacaciones.<br><br>

					<b>Si crees que podemos mejorar en cualquier ámbito, si tienes alguna sugerencia, por favor envíanos un correo y haremos todo lo posible para conseguirlo.</b><br><br>

					Tú opinión es fundamental para nosotros. Muchas Gracias<br><br>

				</p>
				<?php if (!isset($contacted)): ?>
					<form id="contact-form" method="post" action="{{url('/contacto-ayuda')}}" class="form-horizontal">
						{{ csrf_field() }}
						<div class="col-md-12 col-xs-12 push-20">
							<input type="text" id="name" name="name" class="sm-form-control" required placeholder="Nombre">
						</div>
						
						<div class="col-md-6 col-xs-12 push-20">
							<input type="text" id="phone" name="phone" maxlength="9" class="sm-form-control only-numbers" required placeholder="Teléfono">
						</div>

						<div class="col-md-6 col-xs-12 push-20">
							<input type="email" id="email" name="email"  class="email sm-form-control" required placeholder="Email">
						</div>

						<div class="clear"></div>

						<div class="col-xs-12 push-20">
							<textarea required class="required sm-form-control" id="message" name="message" rows="3" cols="30" aria-required="true" placeholder="Mensaje"></textarea>
							<p style="float: left;margin-bottom: 0px;margin-top: 5px"><input type="checkbox" name="aceptacion" required> He leido y entendido las Condiciones de Uso y acepto la Politica de Privacidad </p>
						</div>
						
						<div class="col-xs-12 center">
							<button class="button button-3d nomargin" type="submit">Enviar</button>
						</div>

					</form>
				<?php else: ?>
					<?php if ($contacted == 1): ?>
						<div class="col-ppadding" style="padding-bottom: 20px">

							<div class="heading-block center nobottomborder nobottommargin">
								<div class="col-xs-12 center ">
									<i class=" fa fa-check-circle-o fa-5x"></i>
								</div>
								<h2 class="">Muchas gracias!</h2>
								<span class="">Por ayudarnos a mejorar nuestros servicios</span>
							</div>
						</div>
					<?php else: ?>
						<div class="col-ppadding" style="padding-bottom: 20px">
							<div class="heading-block center nobottomborder nobottommargin">
								<div class="col-xs-12 center ">
									<i class=" fa fa-exclamation-circle fa-5x"></i>
								</div>
								<h2 class="">Lo sentimos!</h2>
								<span class="">Ha ocurrido algo inesperado, por favor intentalo de nuevo más tarde.</span>
								<span class="">Deseamos conocer tus opiniones</span>
							</div>
						</div>
					<?php endif ?>
				<?php endif ?>
			</div>
	</section>
	
@endsection
@section('scripts')

@endsection