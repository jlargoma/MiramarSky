@extends('layouts.master_withoutslider')

@section('metadescription') Grupos - apartamentosierranevada.net @endsection
@section('title')  Grupos - apartamentosierranevada.net @endsection


@section('content')
<meta name="description" content="Contacto para la Cotizacion de grupos esquí en Sierra nevada, te ofrecemos precios especiales, ofertas descuento de forfaits para grupos" />

<meta name="keywords" content="cotizacion grupos esquí sierra nevada;precios especiales grupos">
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
				<h1 class="center psuh-20">GRUPOS</h1>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 center" >
				
				<p class="texto-aparamento " style="text-align: justify;color:black;font-size:16px;padding:0px 30px;" >
					
					Hola, si sois un grupo de más de 20 personas y estáis pensando en ir a esquiar a Sierra Nevada,desde aquí puedes encontrar los apartamentos para todo el grupo que se encuentran en el mismo edificio, contiguos o muy cerca unos de otros. <br><br>

					Si estás pensando en visitar Sierra nevada, creemos que el edificio Miramarski es  una  opción muy interesante para grupos de amigos, familias numerosas, reuniones de empresa, etc. <br><br>

					Recuerda que puedes ponerte en contacto con nosotros mediante los teléfonos especificados o enviándonos un correo electrónico a <a href="mailto:reservas@apartamentosierranevada.net">reservas@apartamentosierranevada.net</a>


				</p>
			<?php if (!isset($contacted)): ?>
				<form id="contact-form" method="post" action="{{url('/contacto-grupos')}}" class="form-horizontal">
					{{ csrf_field() }}
					<div class="col-md-6 col-xs-12 push-20">
						<input type="text" id="name" name="name" class="sm-form-control" required placeholder="Nombre">
					</div>
					
					<div class="col-md-6 col-xs-12 push-20">
						<input type="text" id="phone" name="phone" maxlength="9" class="sm-form-control only-numbers" required placeholder="Teléfono">
					</div>

					<div class="col-md-4 col-xs-12 push-20">
						<input type="email" id="email" name="email"  class="email sm-form-control" required placeholder="Email">
					</div>

					<div class="col-md-5 col-xs-12 push-20">
						<div class="col-md-3" style="margin-top: 10px"><label for="destino">Destino</label></div>
						<div class="col-md-9">
							<select class="destino sm-form-control" name="destino" id="destino" required>
								<option>---</option>
								<option value="Baqueria">Baqueira</option>
								<option value="San Sebastián">San Sebastián</option>
							</select>
						</div>						
					</div>
					<div class="col-md-3 col-xs-12 push-20">
						<div class="col-md-5" style="margin-top: 10px"><label for="personas">Personas</label></div>
						<div class="col-md-7">
							<input type="number" name="personas" class="sm-form-control" > 
						</div>						
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
							<span class="">Nos pondremos en contacto contigo con la mayor brevedad posible</span>
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
						</div>
					</div>
				<?php endif ?>
			<?php endif ?>
			</div>
	</section>
	
@endsection
@section('scripts')

@endsection