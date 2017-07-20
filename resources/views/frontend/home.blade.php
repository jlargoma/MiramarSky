@extends('layouts.master')
@section('title')Alquiler de apartamentos de lujo en Sierra Nievada a pie de pista @endsection

@section('content')
<link rel="stylesheet" href="{{ asset('/frontend/css/components/daterangepicker.css')}}" type="text/css" />
<link href="{{ asset('/frontend/hover.css')}}" rel="stylesheet" media="all">
<link rel="stylesheet" href="{{ asset('/frontend/css/components/radio-checkbox.css')}}" type="text/css" />
<style type="text/css">
	.hvr-reveal:before{
		border-color: white!important;
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
	       	<section class="page-section">

	   			<div id="line-banner-offert"  class=" center tright footer-stick line-promo" style="padding: 0;margin-bottom: 0px!important;">
	   				<div class="row" style="padding: 0 15px;">
	   					
	   					<div class="col-xs-12 center  font-w300 text-center" style="padding: 20px 0">
			                <!-- center hvr-grow-shadow button-reveal -->
			                <button id="banner-offert" class="button button-rounded button-reveal button-large button-red tright  center hvr-grow-shadow ">
				               <i class="icon-angle-right"></i><span>SOLICITA TU RESERVA</span>
			                </button>
		              	</div>
	               		
					</div>
	   			</div>
	   			<div class="row" style="background-color: #3F51B5;">
	   				
	   				<div id="content-book" class="container clearfix push-10" style="display: none;">
	   					<div class="tabs advanced-real-estate-tabs clearfix">

	   						<div class="tab-container" style="padding: 20px 0; background-color: #3F51B5;">
	   							<div id="close-form-book" style="position: absolute; top: 20px; right: 10px; z-index: 50;  cursor: pointer;">
	   								<span class="white text-white"><i class="fa fa-times fa-2x"></i></span>
	   							</div>
	   							<div class="container clearfix">
	   								<div class="tab-content clearfix">
	   									<div class="col-md-6">
											<div class="col-xs-12">
												<h3 class="text-center white">FORMULARIO DE RESERVA</h3>
											</div>

		   									@include('frontend._formBook')
	   									</div>
	   									<div class="col-md-6"  id="content-book-response" style="display: none;">
	   										
	   									</div>

										
	   								</div>
	   							</div>
	   						</div>

	   					</div>
	   				</div>
	   			</div>

				
				<div class="row clearfix common-height">

	       			

	       			<div class="col-md-6 center col-padding" style="background-color: rgb(255, 255, 255); height: 674px;">
	       				<div>
	       					<div class="heading-block nobottomborder" style="margin-bottom: 20px">
	       						<h3 class="green">¿QUE HACEMOS?</h3>
	       					</div>
	       					<p class="lead  text-justify black ls-15">
	       						Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.<br><br>
	       						<span class="green font-w600 text-center">Lorem ipsum dolor sit amet</span>

	       					</p>
	       					<h2 class="green font-w600 text-left nobottommargin">Lorem ipsum dolor sit amet</h2>
	       					<h4 class="text-left">consectetur adipiscing elit</h4>
	       					<p class="lead  text-justify black ls-15">
	       						Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.
	       					</p>
	       				</div>
	       			</div>

	       			<div class="col-md-6 center col-padding hidden-sm hidden-xs" >
       					<div id="oc-slider" class="owl-carousel carousel-widget" data-margin="0" data-items="1" data-animate-in="zoomIn" data-speed="450" data-animate-out="fadeOut">

       						<a href="#"><img src="{{ asset('/img/miramarski/exteriores.jpg') }}" alt="Slide 1"></a>
       						<a href="#"><img src="{{ asset('/img/miramarski/cama-principal-apartamento-sierra-nevada.jpg') }}" alt="Slide 2"></a>
       						<a href="#"><img src="{{ asset('/img/miramarski/salon-miramar-apartamento-sierra-nevada.jpg') }}" alt="Slide 3"></a>
       						<a href="#"><img src="{{ asset('/img/miramarski/television-chimenea-apartamento-sierra-nevada.jpg') }}" alt="Slide 4"></a>

       					</div>
	       			</div>

	       		</div>


	   			<div class="row push-30" style="margin-top: 20px;">
	   				<h2 class="text-center black font-w300">
	   					NUESTROS <span class="font-w800 green ">APARTAMENTOS</span>
	   				</h2>
	   				<div class="col-md-12 col-xs-12">

	   					<div class="col-md-3 col-xs-12 push-mobile-20 ">
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
					
						<div class="col-md-3 col-xs-12 push-mobile-20 ">
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

	   					<div class="col-md-3 col-xs-12 push-mobile-20 ">
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

	   					
	   					<div class="col-md-3 col-xs-12 push-mobile-20 ">
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
	       			
				<div class="row push-20" style="margin-top: 20px;">
	   				<h2 class="text-center black font-w300">
	   					OTROS <span class="font-w800 green ">SERVICIOS</span>
	   				</h2>
	   				<div class="col-md-12 col-xs-12">

	   					<div class="image-box col-xs-12 push-mobile-20 ">
	   						<a href="#">
		   						<div class="col-xs-12 not-padding  container-image-box">
		   							<div class="col-xs-12 not-padding push-0">
	   									<img class="img-responsive imga" src="{{ asset('/img/miramarski/estacion.jpg')}}" alt="Apartamento de lujo sierra nevada"/>
		   							</div>
		   							<div class="col-xs-12 not-padding text-right overlay-text">
		   								<h2 class="font-w200 center push-10 text-center text font-s24 white hvr-reveal" style="padding: 90px 105px;">LA ESTACIÓN
		   								</h2>
		   							</div>
		   						</div>
	   						</a>
	   					</div>
					
						<div class="image-box col-xs-12 push-mobile-20 ">
							<a href="#">
		   						<div class="col-xs-12 not-padding  container-image-box">
		   							<div class="col-xs-12 not-padding push-0">
	   									<img class="img-responsive imga" src="{{ asset('/img/miramarski/fortfait.jpg')}}"  alt="Apartamento standard sierra nevada"/>
		   							</div>
		   							<div class="col-xs-12 not-padding text-right overlay-text">
		   								<h2 class="font-w200 center push-10 text-center text font-s24 white hvr-reveal" style="padding: 100px 105px;">
		   									FORTFAIT
		   								</h2>
		   							</div>
		   						</div>
							</a>
	   					</div>

	   					<div class="image-box col-xs-12 push-mobile-20 ">
		   					<a href="#">
		   						<div class="col-xs-12 not-padding  container-image-box">
		   							<div class="col-xs-12 not-padding push-0">
		   								
	   									<img class="img-responsive" src="{{ asset('/img/miramarski/small/estudio-lujo-sierra-nevada.jpg')}}"  alt="Estudio de lujo sierra nevada"/>
		   							</div>
		   							<div class="col-xs-12 not-padding text-right overlay-text">
		   								<h2 class="font-w200 center push-10 text-center text font-s24 white hvr-reveal" style="padding: 100px 105px;">
		   									RESERVAS
		   								</h2>
		   							</div>
		   						</div>
		   					</a>
	   					</div>

	   					
	   					<div class="image-box col-xs-12 push-mobile-20 ">
	   						<a href="#">
		   						<div class="col-xs-12 not-padding  container-image-box">
		   							<div class="col-xs-12 not-padding push-0">
		   								
	   									<img class="img-responsive" src="{{ asset('/img/miramarski/actividadessn.jpg')}}"  alt="Estudio standard sierra nevada"/>
		   							</div>
		   							<div class="col-xs-12 not-padding text-right overlay-text">
		   								<h2 class="font-w200 center push-10 text-center text font-s24 white hvr-reveal" style="padding: 90px 85px;">
		   									ACTIVIDADES SN
		   								</h2>
		   							</div>
		   						</div>
		   					</a>
	   					</div>
	   					
	   					<div class="image-box col-xs-12 push-mobile-20 ">
	   						<a href="#">
		   						<div class="col-xs-12 not-padding  container-image-box">
		   							<div class="col-xs-12 not-padding push-0">
		   								
	   									<img class="img-responsive" src="{{ asset('/img/miramarski/eventos.jpg')}}"  alt="Estudio standard sierra nevada"/>
		   							</div>
		   							<div class="col-xs-12 not-padding text-right overlay-text">
		   								<h2 class="font-w200 center push-10 text-center text font-s24 white hvr-reveal" style="padding: 100px 105px;">
		   									Eventos
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
	       	<section class="page-section push-30" style="letter-spacing: 0;line-height: 1;background: #3f51b5!important;color: #fff!important;">
	   			<div id="line-banner-offert"  class=" center tright footer-stick line-promo" style="padding: 0;margin-bottom: 0px!important;">
	   				<div class="row" style="padding: 0 15px;">
	   					
	   					<div class="col-xs-12 center  font-w300 text-center" style="padding: 20px 0">
			                
			                <div id="banner-offert" class="button button-desc button-border button-rounded center">
				               SOLICITA TU RESERVA
			                </div>
		              	</div>
	               		
					</div>
	   			</div>
	   			<div class="row" style="background-color: #3F51B5;">
	   				
	   				<div id="content-book" class="container container-mobile clearfix push-10" style="display: none;">
	   					<div class="tabs advanced-real-estate-tabs clearfix">

	   						<div class="tab-container" style="padding: 20px 0; background-color: #3F51B5;">
	   							<div id="close-form-book" style="position: absolute; top: 20px; right: 10px; z-index: 50;  cursor: pointer;">
	   								<span class="white text-white"><i class="fa fa-times fa-2x"></i></span>
	   							</div>
	   								<div class="tab-content clearfix" id="content-book-response">
										<div class="col-xs-12">
											<h3 class="text-center white">FORMULARIO DE RESERVA</h3>
										</div>

	   									@include('frontend._formBook')
	   								</div>
	   						</div>

	   					</div>
	   				</div>
	   			</div>

	       	</section>
			
			<section  class="page-section" style="letter-spacing: 0;line-height: 1;">
				<div class="col-xs-12">
					<div class="col-xs-12">
						<div class="heading-block center nobottomborder" style="margin-bottom: 20px">
							<h3 class="green">EL edificio y los aptos</h3>
						</div>
						<p class="lead  text-justify black ls-15">
							Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
						</p>
						<p class="lead  text-center black ls-15">
							<span class="green font-w600 text-center">Lorem ipsum dolor sit amet</span>
						</p>
						<div class="col-xs-12 push-30">
							<div id="oc-slider" class="owl-carousel carousel-widget" data-margin="0" data-items="1" data-animate-in="zoomIn" data-speed="450" data-animate-out="fadeOut">

								<a href="#"><img src="{{ asset('/img/miramarski/exteriores.jpg') }}" alt="Slide 1"></a>
								<a href="#"><img src="{{ asset('/img/miramarski/cama-principal-apartamento-sierra-nevada.jpg') }}" alt="Slide 2"></a>
								<a href="#"><img src="{{ asset('/img/miramarski/salon-miramar-apartamento-sierra-nevada.jpg') }}" alt="Slide 3"></a>
								<a href="#"><img src="{{ asset('/img/miramarski/television-chimenea-apartamento-sierra-nevada.jpg') }}" alt="Slide 4"></a>

							</div>
						</div>

						
					</div>


				</div>
				<div style="clear: both;"></div>


				<a href="{{url('/apartamentos/apartamento-lujo-sierra-nevada')}}">
					<div class="section parallax noborder center" style="background-image: url({{ asset('/img/miramarski/galerias/apartamento-lujo-sierra-nevada/a-panoramica.jpg') }}); padding: 40px 0; margin: 20px 0;" data-stellar-background-ratio="0.4">
						<h3 class="h2 text-center white text-white font-w800" style="text-shadow: 1px 1px #000;">APARTAMENTO DE LUJO</h3>
						<span  class="text-center white text-white" style="text-shadow: 1px 1px #000;">Lorem ipsum dolor sit amet</span>
					</div>
				</a>
				<a href="{{url('/apartamentos/apartamento-standard-sierra-nevada')}}">
					<div class="section parallax noborder center" style="background-image: url({{ asset('/img/miramarski/galerias/apartamento-standard-sierra-nevada/salon5-min.jpg') }}); padding: 40px 0; margin: 20px 0;" data-stellar-background-ratio="0.4">
						<h3 class="h2 text-center white text-white font-w800" style="text-shadow: 1px 1px #000;">APARTAMENTO STANDARD</h3>
						<span  class="text-center white text-white" style="text-shadow: 1px 1px #000;">Lorem ipsum dolor sit amet</span>
					</div>
				</a>
				<a href="{{url('/apartamentos/estudio-lujo-sierra-nevada')}}">
					<div class="section parallax noborder center" style="background-image: url({{ asset('/img/miramarski/galerias/apartamento-lujo-sierra-nevada/a-panoramica.jpg') }}); padding: 40px 0; margin: 20px 0;" data-stellar-background-ratio="0.4">
						<h3 class="h2 text-center white text-white font-w800" style="text-shadow: 1px 1px #000;">ESTUDIO DE LUJO</h3>
						<span  class="text-center white text-white" style="text-shadow: 1px 1px #000;">Lorem ipsum dolor sit amet</span>
					</div>
				</a>
				<a href="{{url('/apartamentos/estudio-standard-sierra-nevada')}}">
					<div class="section parallax noborder center" style="background-image: url({{ asset('/img/miramarski/galerias/apartamento-lujo-sierra-nevada/salon.jpg') }}); padding: 40px 0; margin: 20px 0;" data-stellar-background-ratio="0.4">
						<h3 class="h2 text-center white text-white font-w800" style="text-shadow: 1px 1px #000;">ESTUDIO STANDARD</h3>
						<span  class="text-center white text-white" style="text-shadow: 1px 1px #000;">Lorem ipsum dolor sit amet</span>
					</div>
				</a>
				<div style="clear: both;"></div>
			</section>
			<section  class="page-section" style="letter-spacing: 0;line-height: 1; margin: 45px 0;">
				
				<div class="row">
					<div class="heading-block center">
						<h3 class="green">OTROS SERVICIOS</h3>
					</div>

					

					<a href="{{url('/actividades')}}" >
						<div class="section parallax-effect nomargin noborder center" style="background-image: url({{ asset('/img/miramarski/banners/actividades.jpg') }}); padding: 80px 0; height: 265px;" data-stellar-background-ratio="0.4">
								<h3 class="h1 text-center white text-white font-w800" style="text-shadow: 1px 1px #000;">ACTIVIDADES</h3>
								<span  class="text-center white text-white" style="text-shadow: 1px 1px #000;">Lorem ipsum dolor sit amet</span>
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
<script type="text/javascript" src="{{asset('/frontend/js/components/moment.js')}}"></script>
<script type="text/javascript" src="{{asset('/frontend/js/components/daterangepicker.js')}}"></script>
<script type="text/javascript">
	$(function() {
		// .daterange1
		$(".daterange1").daterangepicker({
			"buttonClasses": "button button-rounded button-mini nomargin",
			"applyClass": "button-color",
			"cancelClass": "button-light",
		 	locale: {
		      format: 'DD/MM/YYYY',
		      "applyLabel": "Aplicar",
		        "cancelLabel": "Cancelar",
		        "fromLabel": "From",
		        "toLabel": "To",
		        "customRangeLabel": "Custom",
		        "daysOfWeek": [
		            "Do",
		            "Lu",
		            "Mar",
		            "Mi",
		            "Ju",
		            "Vi",
		            "Sa"
		        ],
		        "monthNames": [
		            "Enero",
		            "Febrero",
		            "Marzo",
		            "Abril",
		            "Mayo",
		            "Junio",
		            "Julio",
		            "Agosto",
		            "Septiembre",
		            "Octubre",
		            "Noviembre",
		            "Diciembre"
		        ],
		        "firstDay": 1,
		    },
		    
		});
	});

	$('#date').click(function(event) {
		event.preventDefault();
		$('html, body').animate({
		       scrollTop: $("#date").offset().top - 40
		   }, 2000);
	});

	$('#form-book-apto-lujo').submit(function(event) {

		event.preventDefault();

		var _token   = $('input[name="_token"]').val();
		var name     = $('input[name="name"]').val();
		var email    = $('input[name="email"]').val();
		var phone    = $('input[name="telefono"]').val();
		var date     = $('input[name="date"]').val();
		var quantity = $('select[name="quantity"]').val();
		var apto     = $('input[name="apto"]').val();
		var luxury   = $('input[name="luxury"]').val();
		var parking  = $('input[name="parking"]').val();
		var comment  = $('textarea[name="comment"]').val();

		var url = $(this).attr('action');

		$.post( url , {_token : _token,  name : name,    email : email,   phone : phone,   date : date,    quantity : quantity, apto : apto, luxury : luxury,  parking : parking, comment : comment}, function(data) {

			$('#content-book-response').empty().append(data).fadeIn();
		});

	});

	$('#banner-offert, .menu-booking').click(function(event) {
		$('#content-book').show('400');
		$('#banner-offert').hide();
		$('#line-banner-offert').hide();

		$('html, body').animate({
		       scrollTop: $("#content-book").offset().top - 60
		   }, 2000);
	});

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