@extends('layouts.master_withoutslider')
<link rel="stylesheet" href="{{ asset('/frontend/css/components/daterangepicker.css')}}" type="text/css" />
<link rel="stylesheet" href="{{ asset('/frontend/css/components/radio-checkbox.css')}}" type="text/css" />

<style type="text/css">
	#primary-menu ul li  a{
		color: #3F51B5!important
	}
	label{
		color: white!important
	}
</style>
@section('metadescription') {{ $aptoHeading }} en Sierra Nevada @endsection
@section('title') {{ $aptoHeading }} en Sierra Nevada @endsection

@section('content')
	
	<section id="content">

		<div class="container clearfix push-20">
			<div class="row" style="margin-top: 40px;">
				<h1 class="center hidden-sm hidden-xs psuh-20"><?php echo strtoupper($aptoHeading); ?></h2>
				<h1 class="center hidden-lg hidden-md push-10"><?php echo strtoupper($aptoHeadingMobile); ?></h2>
				
			</div>
		</div>
		
		<div class="row clearfix  push-30">
			<div class="col-xs-12 col-md-6">
				<div class="fslider" data-easing="easeInQuad">
					<div class="flexslider">
						<div class="slider-wrap">
							<?php foreach ($slides as $key => $slide): ?>
								<div class="slide" data-thumb="{{ asset('/img/miramarski/galerias/')}}/<?php echo $url ?>/<?php echo $slide->getFilename() ?>">
									<a href="#">
										<img src="{{ asset('/img/miramarski/galerias/')}}/<?php echo $url ?>/<?php echo $slide->getFilename() ?>" alt="<?php echo $slide->getFilename() ?>" style="max-height: 500px;">
										<div class="flex-caption slider-caption-bg"><?php echo $slide->getFilename() ?></div>
									</a>
								</div>
							<?php endforeach ?>
						</div>
					</div>
				</div>
			</div>

			<div class="col-xs-12 col-md-6 clearfix center">

					<div class="col-md-12 push-20">
						<?php if ($typeApto == 1): ?>
							@include('frontend.pages._infoAptoLujo')
						<?php elseif($typeApto == 2): ?>
							@include('frontend.pages._infoAptoStandard')
						<?php elseif($typeApto == 3): ?>
							@include('frontend.pages._infoEstudioLujo')
						<?php elseif($typeApto == 4): ?>
							@include('frontend.pages._infoEstudioStandard')
						<?php endif ?>
					</div>
					
					<button id="showFromBook" class="button button-desc button-3d button-rounded bg-bluesky center white" style="background-color: #4cb53f!important">¡Reserva YA!</button>

			</div>
		</div>
		
		<div id="content-form-book" class="row bg-bluesky push-30" style="display: none; padding: 40px 0 0">
			<span style="padding: 0 5px; cursor: pointer; opacity: 1; margin-right: 20px; margin-top: 10px;" class="close pull-right white text-white"><i class="fa fa-times"></i></span>
			<div class="col-xs-12 col-md-4 hidden-sm hidden-xs" style=" min-height: 535px">
				<img src="{{asset('/img/miramarski/esquiadores.png')}}" class="img-responsive" style="position: absolute; bottom: 0">
			</div>
			<div  class="col-xs-12 col-md-4" >
				@include('frontend._formBook')
            </div>
            <div id="content-book-response" class="col-xs-12 col-md-4" >
            	
            </div>
		</div>

		<div class="container container-mobile clearfix">
			<div class="col-xs-12">
				<h3 class="text-center font-w300">
					OTROS <span class="green font-w800">APARTAMENTOS</span>
				</h3>
			</div>
			<div class="col-xs-12">
				<?php foreach ($aptos as $key => $apartamento): ?>
					<?php if ($apartamento != $url): ?>
						<div class="col-md-4 ">
							<a href="{{url('/apartamentos')}}/<?php echo $apartamento ?>">
								<div class="col-xs-12 not-padding  container-image-box push-mobile-20">
									<div class="col-xs-12 not-padding push-0">
										
											<img class="img-responsive" src="/img/miramarski/small/<?php echo $apartamento ?>.jpg"  alt="<?php echo str_replace('-', ' ', $apartamento) ?>"/>
										</a>
									</div>
									<div class="col-xs-12 not-padding text-right overlay-text">
										<h2 class="font-w600 center push-10 text-center text white font-s24" >
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
					<?php endif ?>
				<?php endforeach ?>
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