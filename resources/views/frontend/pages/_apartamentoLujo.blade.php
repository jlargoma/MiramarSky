@extends('layouts.master_pages')

@section('title') Apartamento de lujo en Sierra Nevada @endsection

@section('content')
	<link rel="stylesheet" href="{{ asset('/frontend/css/components/daterangepicker.css')}}" type="text/css" />
	<link rel="stylesheet" href="{{ asset('/frontend/css/components/radio-checkbox.css')}}" type="text/css" />

	<style type="text/css">
		#logo a.standard-logo, #logo img{
			display: none;
		}
		#logo a.retina-logo, #logo a.retina-logo img{
			display: block;
		}

		#logo a.retina-logo img{
			content:url("{{ asset ('frontend/images/logo-dark.png')}}");
		}
		label{
			color: white!important
		}
	</style>
	<section id="content">

		<div class="row clearfix push-20">

			<div class="col-sm-6 col-padding">
				<div class="fslider" data-easing="easeInQuad">
					<div class="flexslider">
						<div class="slider-wrap">
							<?php foreach ($slides as $slide): ?>
								
							
							<div class="slide" data-thumb="{{ asset('/img/miramarski/galerias/apartamento-lujo')}}/<?php echo $slide->getFilename() ?>">
								<a href="#">
									<img src="{{ asset('/img/miramarski/galerias/apartamento-lujo')}}/<?php echo $slide->getFilename() ?>" alt="<?php echo $slide->getFilename () ?>">
									<!-- <div class="flex-caption slider-caption-bg"><?php echo $slide->getFilename () ?></div> -->
								</a>
							</div>
							<?php endforeach ?>
						</div>
					</div>
				</div>
			</div>

			<div class="col-sm-6 col-padding">
				<div>
					<div class="heading-block">
						<h3>APARTAMENTO DE LUJO 2 DORMITORIOS</h3>
						<h5>
							<div class="product-rating">
								<i class="text-warning icon-star3"></i>
								<i class="text-warning icon-star3"></i>
								<i class="text-warning icon-star3"></i>
								<i class="text-warning icon-star3"></i>
								<i class="text-warning icon-star3"></i>
							</div>
						</h5>
					</div>

					<div class="row clearfix center push-30">

						<p class="font-s18 ls-15 miramar font-w300 text-justify">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Vero quod consequuntur quibusdam, enim expedita sed quia nesciunt incidunt accusamus necessitatibus modi adipisci officia libero accusantium esse hic, obcaecati, ullam, laboriosam!</p>

						<p class="font-s18 ls-15 miramar font-w300 text-justify">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti vero, animi suscipit id facere officia. Aspernatur, quo, quos nisi dolorum aperiam fugiat deserunt velit rerum laudantium cum magnam excepturi quod, fuga architecto provident, cupiditate delectus voluptate eaque! Sit neque ut eum, voluptatibus odit cum dolorum ipsa voluptates inventore cumque a.</p>

						<p class="font-s18 ls-15 miramar font-w300 text-justify">
							<span class="text-black font-w300">
								<i class="fa fa-wifi"></i> Wifi
							</span> &nbsp;&nbsp;
							<span class="text-black font-w300">
								<i class="fa fa-tv"></i> 43"
							</span> &nbsp;&nbsp;
							<span class="text-black font-w300">
								<i class="fa fa-paw"></i> Mascotas
							</span> &nbsp;&nbsp;
							<span class="text-black font-w300">
								<i class="fa fa-key"></i> Llaves
							</span> &nbsp;&nbsp;
							<span class="text-black font-w300">
								<i class="fa fa-bed"></i> Camas
							</span> &nbsp;&nbsp;
							<span class="text-black font-w300">
								<i class="fa fa-car"></i> Parking
							</span> &nbsp;&nbsp;
						</p>
						
						<button id="showFromBook" class="button button-desc button-3d button-rounded bg-bluesky center">¡Reserva YA!</button>

					</div>

					

				</div>
			</div>

		</div>
		
		<div id="content-form-book" class="row bg-bluesky push-30" style="display: none;">
			<span style="padding: 0 5px; cursor: pointer; opacity: 1; margin-right: 20px; margin-top: 10px;" class="close pull-right white text-white"><i class="fa fa-times"></i></span>
			<div class="col-xs-12 col-md-6 hidden-sm hidden-xs" style=" min-height: 535px">
				<img src="{{asset('/img/miramarski/esquiadores.png')}}" class="img-responsive" style="position: absolute; bottom: 0">
			</div>
			<div id="content-book-response" class="col-xs-12 col-md-6" style="">
				@include('frontend._formBook')
            </div>
		</div>

		<div class="container container-mobile clearfix">
			<div class="col-xs-12">
				<h3 class="text-center font-w300">
					OTROS <span class="green font-w800">APARTAMENTOS</span>
				</h3>
			</div>
			<div class="col-xs-12">
				<div class="col-md-4 ">
					<div class="col-xs-12 not-padding  container-image-box push-mobile-20">
						<div class="col-xs-12 not-padding push-0">
							<a href="{{url('/apartamentos/estudio-lujo-sierra-nevada')}}">
								<img class="img-responsive" src="{{ asset('/img/miramarski/small/estudio-lujo-sierra-nevada.jpg')}}"  alt="Estudio de lujo sierra nevada"/>
							</a>
						</div>
						<div class="col-xs-12 not-padding text-right overlay-text">
							<h2 class="font-w600 center push-10 text-center text font-s24" >
								<a class="white text-white" href="{{url('/apartamentos/estudio-lujo-sierra-nevada')}}">ESTUDIO DE LUJO</a>
							</h2>
						</div>
					</div>
				</div>

				<div class="col-md-4 push-mobile-20">
					<div class="col-xs-12 not-padding  container-image-box push-mobile-20">
						<div class="col-xs-12 not-padding push-0">
							<a href="{{url('/apartamentos/apartamento-standard-sierra-nevada')}}">
								<img class="img-responsive imga" src="{{ asset('/img/miramarski/small/apartamento-standar-sierra-nevada.jpg')}}"  alt="Apartamento standard sierra nevada"/>
							</a>
						</div>
						<div class="col-xs-12 not-padding text-right overlay-text">
							<h2 class="font-w600 center push-10 text-center text font-s24" >
								<a class="white text-white" href="{{url('/apartamentos/apartamento-standard-sierra-nevada')}}">APARTAMENTO STANDARD</a>
							</h2>
						</div>
					</div>
				</div>

				<div class="col-md-4 push-mobile-20">
					<div class="col-xs-12 not-padding  container-image-box push-mobile-20">
						<div class="col-xs-12 not-padding push-0">
							<a href="{{url('/apartamentos/estudio-standard-sierra-nevada')}}">
								<img class="img-responsive" src="{{ asset('/img/miramarski/small/estudio-standard-sierra-nevada.jpg')}}"  alt="Estudio standard sierra nevada"/>
							</a>
						</div>
						<div class="col-xs-12 not-padding text-right overlay-text">
							<h2 class="font-w600 center push-10 text-center text font-s24" >
								<a class="white text-white" href="{{url('/apartamentos/estudio-standard-sierra-nevada')}}">ESTUDIO STANDARD</a>
							</h2>
						</div>
					</div>
				</div>

			</div>
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
		      format: 'DD/MM/YYYY'
		    },
		    
		});
	});


	$('#showFromBook').click(function(event) {
		$('#content-form-book').slideDown('400');
		$('html,body').animate({
		        scrollTop: $("#content-form-book").offset().top - 80},
        'slow');
	});

	$('span.close').click(function(event) {
		$('#content-form-book').hide('400');
		$('html,body').animate({
		        scrollTop: $("body").offset().top},
        'slow');
	});


		$(".only-numbers").keydown(function (e) {	    
	       	if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||	         
	           	(e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 	         
	           	(e.keyCode >= 35 && e.keyCode <= 40)) {	             
	                return;
	       	}	     
	       	if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
	           e.preventDefault();
	       	}
	   	});

		$('#quantity').change(function(event) {
			var val = parseInt($(this).val());
			if ( val >= 5) {
				$('#apto-estudio').attr('disabled',true);
				$('#apto-estudio').hide();	

			}else{
				
				$('#apto-estudio').attr('disabled',false);
				$('#apto-estudio').show();
			}
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

				$('#content-book-response').empty().append(data);
			});

		});
</script>	
@endsection