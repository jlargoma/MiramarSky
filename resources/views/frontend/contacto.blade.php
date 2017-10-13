@extends('layouts.master_withoutslider')

@section('title') Apartamentosierranevada.net - contacto @endsection

<meta name="description" content="Datos de contacto, alquiler apartamento sierra nevada, donde estamos,edificio mirarmarski, como llegar a Sierra Nevada a traves de google maps" />


@section('content')

<!-- Google Code for conversiones_lead Conversion Page -->
<script type="text/javascript">
	/* <![CDATA[ */
	var google_conversion_id = 834109020;
	var google_conversion_language = "en";
	var google_conversion_format = "3";
	var google_conversion_color = "ffffff";
	var google_conversion_label = "DHhZCN3wy3UQ3PzdjQM";
	var google_remarketing_only = false;
	/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
	<div style="display:inline;">
		<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/834109020/?label=DHhZCN3wy3UQ3PzdjQM&amp;guid=ON&amp;script=0"/>
	</div>
</noscript>


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




<?php if (!$mobile->isMobile()): ?>
	<section id="slider" class="slider-parallax full-screen dark error404-wrap" style="background: url(/img/miramarski/contacto.jpg) center;">
		<div class="slider-parallax-inner">

			<div class="container container-mobile vertical-middle center clearfix">

				<div class="col-md-12 col-xs-12 not-padding-mobile">
					<div class="col-md-4 col-md-offset-2 col-xs-12" style="margin-bottom: 25px;">
						<div class="col-xs-12  black-cover">
							<div class="heading-block center">
								
								<h4>APARTAMENTOS DE LUJO SIERRA NEVADA</h4>
								<span>Alquiler Apartamento de Lujo - Edif Miramar Ski</span>
							</div>
							<div class="col-xs-12 not-padding-mobile">
								<div class="col-xs-12 not-padding-mobile text-center">
									<p class="text-justify font-s18 font-w300">
										Calle Cauchiles Escalera 1<br>
										Edificio Miramar Ski<br>
										18916 Monachil Sierra Nevada, Granada<br>
									</p>							
								</div>
								<h4 class="text-center" >
									<a href="#" data-toggle="modal" data-target=".mapa" style="cursor: pointer;color: white">
										<i class="fa fa-map-marker "></i> COMO LLEGAR
									</a>
								</h4>
							</div>
						</div>

					</div>
					
					<div class="col-md-4 col-xs-12" style="margin-bottom: 25px;">
						<?php if (!isset($contacted)): ?>
							<div class="col-xs-12  black-cover" id="content-result-contact-form">
								<div class="heading-block center ">
									<h4 class="white">ENVIANOS TUS DUDAS</h4>
									<!-- <span>Alquiler Apartamento de Lujo - Edif Miramar Ski</span> -->
								</div>
								<div class="col-xs-12 not-padding-mobile">
									<div class="col-xs-12 not-padding-mobile text-center">
										<div class="col-xs-12 not-padding" >

											<div class="col-xs-12" >
												<!-- action="{{url('/contacto')}}" method="post" -->
												<form id="contact-form" method="post" action="{{url('/contacto-form')}}" class="form-horizontal">
													{{ csrf_field() }}
													<div class="col-md-6 col-xs-12 push-20">
														<input type="text" id="name" name="name" class="sm-form-control" required placeholder="Nombre">
													</div>

													<div class="col-md-6 col-xs-12 push-20">
														<input type="email" id="email" name="email"  class="email sm-form-control" required placeholder="Email">
													</div>

													<div class="col-md-6 col-xs-12 push-20">
														<input type="text" id="subject" name="subject"  class="sm-form-control" required placeholder="Asunto">
													</div>

													<div class="col-md-6 col-xs-12 push-20">
														<input type="text" id="phone" name="phone" maxlength="9" class="sm-form-control only-numbers" required placeholder="Teléfono">
													</div>

													<div class="clear"></div>

													<div class="col-xs-12 push-20">
														<textarea required class="required sm-form-control" id="message" name="message" rows="3" cols="30" aria-required="true" placeholder="Mensaje"></textarea>
													</div>

													<div class="col-xs-12 center">
														<button class="button button-3d nomargin" type="submit">Enviar</button>
													</div>

												</form>
											</div>
										</div>						
									</div>
								</div>
							</div>
						<?php else: ?>
							<?php if ($contacted == 1): ?>
								<div class="col-padding black-cover">

									<div class="heading-block center nobottomborder nobottommargin">
										<div class="col-xs-12 center white">
											<i class="white fa fa-check-circle-o fa-5x"></i>
										</div>
										<h2 class="white">Muchas gracias!</h2>
										<span class="white">Nos pondremos en contacto con la mayor brevedad posible.</span>
									</div>
								</div>
							<?php else: ?>
								<div class="col-padding black-cover">
									<div class="heading-block center nobottomborder nobottommargin">
										<div class="col-xs-12 center white">
											<i class="white fa fa-exclamation-circle fa-5x"></i>
										</div>
										<h2 class="white">Lo sentimos!</h2>
										<span class="white">Ha ocurrido algo inesperado, por favor intentalo de nuevo más tarde.</span>
									</div>
								</div>
							<?php endif ?>
						<?php endif ?>
						

					</div>

				</div>
			</div>

		</div>
	</section>
<?php else: ?>
	<style type="text/css">
		
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
		
			#header{
				    height: 70px!important;
			}
		}
	</style>
	<section id="content" style="background: url(/img/miramarski/contacto.jpg)">


		<div class="container container-mobile center clearfix">

			<div class="col-md-12 col-xs-12 not-padding-mobile">
				<div class="col-lg-4 col-lg-offset-2 col-md-6 col-xs-12" style="margin: 25px  0 0 0;">
					<div class="col-xs-12  black-cover">
						<div class="heading-block center">
							
							<h4 class="white">APARTAMENTOS DE LUJO SIERRA NEVADA</h4>
							<span class="white">Alquiler Apartamento de Lujo - Edif Miramar Ski</span>
						</div>
						<div class="col-xs-12 not-padding-mobile">
							<div class="col-xs-12 not-padding-mobile text-center white">
								<p class="text-justify font-s18 font-w300 white">
									Calle Cauchiles Escalera 1<br>
									Edificio Miramar Ski<br>
									18916 Monachil Sierra Nevada, Granada<br>
								</p>							
							</div>
							<h4 class="text-center" >
								<a href="#" data-toggle="modal" data-target=".mapa" style="cursor: pointer;color: white">
									<i class="fa fa-map-marker "></i> COMO LLEGAR
								</a>
							</h4>
						</div>
					</div>

				</div>
				
				<div class="col-lg-6 col-md-6 col-xs-12" style="margin-bottom: 25px;">
					<div class="col-xs-12  black-cover" id="content-result-contact-form">

						<div class="col-xs-12 not-padding-mobile">
							<?php if (!isset($contacted)): ?>
								<div class="col-xs-12  black-cover" id="content-result-contact-form">
									<div class="heading-block center ">
										<h4 class="white">ENVIANOS TUS DUDAS</h4>
										<!-- <span>Alquiler Apartamento de Lujo - Edif Miramar Ski</span> -->
									</div>
									<div class="col-xs-12 not-padding-mobile">
										<div class="col-xs-12 not-padding-mobile text-center">
											<div class="col-xs-12 not-padding" >

												<div class="col-xs-12" >
													<!-- action="{{url('/contacto')}}" method="post" -->
													<form id="contact-form" method="post" action="{{url('/contacto-form')}}" class="form-horizontal">
														{{ csrf_field() }}
														<div class="col-md-6 col-xs-12 push-20">
															<input type="text" id="name" name="name" class="sm-form-control" required placeholder="Nombre">
														</div>

														<div class="col-md-6 col-xs-12 push-20">
															<input type="email" id="email" name="email"  class="email sm-form-control" required placeholder="Email">
														</div>

														<div class="col-md-6 col-xs-12 push-20">
															<input type="text" id="subject" name="subject"  class="sm-form-control" required placeholder="Asunto">
														</div>

														<div class="col-md-6 col-xs-12 push-20">
															<input type="text" id="phone" name="phone" maxlength="9" class="sm-form-control only-numbers" required placeholder="Teléfono">
														</div>

														<div class="clear"></div>

														<div class="col-xs-12 push-20">
															<textarea required class="required sm-form-control" id="message" name="message" rows="3" cols="30" aria-required="true" placeholder="Mensaje"></textarea>
														</div>

														<div class="col-xs-12 center">
															<button class="button button-3d nomargin" type="submit">Enviar</button>
														</div>

													</form>
												</div>
											</div>						
										</div>
									</div>
								</div>
							<?php else: ?>
								<?php if ($contacted == 1): ?>
									<div class="col-ppadding">

										<div class="heading-block center nobottomborder nobottommargin">
											<div class="col-xs-12 center white">
												<i class="white fa fa-check-circle-o fa-5x"></i>
											</div>
											<h2 class="white">Muchas gracias!</h2>
											<span class="white">Nos pondremos en contacto con la mayor brevedad posible.</span>
										</div>
									</div>
								<?php else: ?>
									<div class="col-ppadding">
										<div class="heading-block center nobottomborder nobottommargin">
											<div class="col-xs-12 center white">
												<i class="white fa fa-exclamation-circle fa-5x"></i>
											</div>
											<h2 class="white">Lo sentimos!</h2>
											<span class="white">Ha ocurrido algo inesperado, por favor intentalo de nuevo más tarde.</span>
										</div>
									</div>
								<?php endif ?>
							<?php endif ?>
						</div>
					</div>

				</div>

			</div>
		</div>

	</section>
<?php endif; ?>


<div class="modal fade mapa" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-body">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">COMO LLEGAR</h4>
				</div>
				<div class="modal-body">
					<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3182.495919630369!2d-3.3991606847018305!3d37.09331097988919!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd71dd38d505f85f%3A0x4a99a1314ca01a9!2sAlquiler+Apartamento+de+Lujo+Sierra+Nevada+-+Edif+Miramar+Ski!5e0!3m2!1ses!2ses!4v1499417977280" width="100%" height="600" frameborder="0" style="border:0" allowfullscreen></iframe>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('scripts')
		
	<script type="text/javascript">
		$(document).ready(function() {
		    $(".only-numbers").keydown(function (e) {
		        // Allow: backspace, delete, tab, escape, enter and .
		        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
		             // Allow: Ctrl+A, Command+A
		            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
		             // Allow: home, end, left, right, down, up
		            (e.keyCode >= 35 && e.keyCode <= 40)) {
		                 // let it happen, don't do anything
		                 return;
		        }
		        // Ensure that it is a number and stop the keypress
		        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
		            e.preventDefault();
		        }
		    });


		    
		});
	</script>	

@endsection