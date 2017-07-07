@extends('layouts.master_withoutslider')

@section('title') Apartamentosierranevada.net - contacto @endsection

@section('content')

<section id="slider" class="slider-parallax full-screen dark error404-wrap" style="background: url(/img/miramarski/contacto.jpg) center;">
	<div class="slider-parallax-inner">

		<div class="container container-mobile vertical-middle center clearfix">

			<div class="col-md-12 col-xs-12 not-padding-mobile">
				<div class="col-md-4 col-md-offset-2 col-xs-12 hidden-xs hidden-sm" style="margin-bottom: 25px;">
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
									<i class="icon-map"></i> COMO LLEGAR
								</a>
							</h4>
						</div>
					</div>

				</div>
				
				<div class="col-md-4 col-xs-12 hidden-xs hidden-sm" style="margin-bottom: 25px;">
					<div class="col-xs-12  black-cover" id="content-result-contact-form">
						<div class="heading-block center ">
							<h4>ENVIANOS TUS DUDAS</h4>
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

				</div>

			</div>
		</div>

	</div>
</section>

<div class="modal fade mapa" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-body">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">COMO LLEGAR</h4>
				</div>
				<div class="modal-body">
					<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3182.495919630369!2d-3.3991606847018305!3d37.09331097988919!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd71dd38d505f85f%3A0x4a99a1314ca01a9!2sAlquiler+Apartamento+de+Lujo+Sierra+Nevada+-+Edif+Miramar+Ski!5e0!3m2!1ses!2ses!4v1499417977280" width="800" height="600" frameborder="0" style="border:0" allowfullscreen></iframe>
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


		    $('#contact-form').submit(function(event) {

		    	event.preventDefault();

				var _token  = $('input[name="_token"]').val();
				var name    = $('input[name="name"]').val();
				var email   = $('input[name="email"]').val();
				var subject = $('input[name="subject"]').val();
				var phone   = $('input[name="phone"]').val();
				var message = $('textarea[name="message"]').val();

		    	var url = $(this).attr('action');

		    	$.post( url , {
		    					_token : _token,
		    					name : name,
		    					email : email,
		    					subject : subject,
		    					phone : phone,
		    					message : message,
		    					}, function(data) {

    				$('#content-result-contact-form').empty().append(data);
		    	});

		    });

		});
	</script>	

@endsection