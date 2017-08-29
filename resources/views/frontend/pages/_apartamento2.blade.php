@extends('layouts.master_withoutslider')
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
@section('metadescription') {{ $aptoHeading }} en Sierra Nevada @endsection
@section('title') {{ $aptoHeading }} en Sierra Nevada @endsection

@section('content')
	
	<section id="content">

		<div class="container container-mobile clearfix push-0">
			<div class="row">
				<h1 class="center hidden-sm hidden-xs psuh-20"><?php echo strtoupper($aptoHeading); ?></h2>
				<h1 class="center hidden-lg hidden-md green push-10"><?php echo strtoupper($aptoHeadingMobile); ?></h2>
				
			</div>
		</div>
		
		<div class="row clearfix  push-30">
			<div class="col-xs-12 col-md-6">
				<div class="fslider" data-easing="easeInQuad">
					<div class="flexslider">
						<div class="slider-wrap">
							<?php foreach ($slides as $key => $slide): ?>
								<?php $fotos = explode(",", $slide->getFilename()) ?>
								<?php if (isset($fotos[1])): ?>
									<div class="slide" data-thumb="{{ asset('/img/miramarski/galerias/')}}/<?php echo $url ?>/<?php echo $fotos[1] ?>">
										<a>
											<img class="img" src="{{ asset('/img/miramarski/galerias/')}}/<?php echo $url ?>/<?php echo $slide->getFilename() ?>" alt="<?php echo $fotos[2] ?>" title="<?php echo $fotos[3] ?>" >
											<!-- <div class="flex-caption slider-caption-bg"><?php echo $slide->getFilename() ?></div> -->
										</a>
									</div>
								<?php else: ?>
									<div class="slide" data-thumb="{{ asset('/img/miramarski/galerias/')}}/<?php echo $url ?>/<?php echo $slide->getFilename()  ?>">
										<a>
											<img class="img" src="{{ asset('/img/miramarski/galerias/')}}/<?php echo $url ?>/<?php echo $slide->getFilename() ?>" alt="<?php echo $slide->getFilename()  ?>" title="<?php echo $slide->getFilename()  ?>" >
											<!-- <div class="flex-caption slider-caption-bg"><?php echo $slide->getFilename() ?></div> -->
										</a>
									</div>
								<?php endif ?>
								
							<?php endforeach ?>
						</div>
					</div>
				</div>
			</div>

			<div class="col-xs-12 col-md-6 clearfix center">

				<div class="col-md-12 push-0 not-padding-mobile">
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
				
				<?php if (!$mobile->isMobile()): ?>
					<button id="showFormBook" class="button button-desc button-3d button-rounded bg-bluesky center white" >¡Reserva YA!</button>

				<?php endif; ?>
			</div>¡
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
				<?php if ($apartamento != $url): ?>
					<div class="col-md-4 not-padding-mobile hover-effect">
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
				<?php endif ?>
			<?php endforeach ?>
		</div>


		<div id="fixed-book" class="col-xs-12 text-center center hidden-lg hidden-md bg-white" style="position: fixed; bottom: 0px; width: 100%; background-color: #FFF!important; padding: 15px;">
			<button id="showFormBook" class="button button-desc button-3d button-rounded bg-bluesky center white" style="background-color: #4cb53f!important; width: 60%; margin: 0px auto;z-index: 90">¡Reserva YA!</button>

		</div>
	</section>
	
@endsection
@section('scripts')
<script type="text/javascript" src="{{asset('/frontend/js/components/moment.js')}}"></script>
<script type="text/javascript" src="{{asset('/frontend/js/components/daterangepicker.js')}}"></script>
<script type="text/javascript">
	<?php if (!$mobile->isMobile()): ?>

		$('#showFormBook').click(function(event) {
			$('#content-form-book').slideDown('400');
			$('html,body').animate({
			        scrollTop: $("#content-form-book").offset().top - 80},
	        'slow');
		});
	<?php else: ?>

		$('#showFormBook').click(function(event) {
			$('#content-form-book').slideDown('400');
			$('html,body').animate({
			        scrollTop: $("#content-form-book").offset().top},
	        'slow');
	        setTimeout(function(){
	        	$('#fixed-book').fadeOut();
	        }, 1000);
        	

		});

		$(window).scroll(function(event) {
			if (!$('content-form-book').is(':visible')) {
			  	$('#fixed-book').fadeIn();
			}
			
		});
	<?php endif; ?>

	$('span.close').click(function(event) {
		$('#content-form-book').hide('400');
		unflip();
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

	$("#content-book-response").flip({
	  trigger: 'manual'
	});

	function unflip() {

		$("#content-book-response").flip(false);
		$('#content-book-response .back').empty();
	}


	$('#form-book-apto-lujo').submit(function(event) {

		event.preventDefault();


		var _token   = $('input[name="_token"]').val();
		var name     = $('input[name="name"]').val();
		var email    = $('input[name="email"]').val();
		var phone    = $('input[name="telefono"]').val();
		var date     = $('input[name="date"]').val();
		var quantity = $('select[name="quantity"]').val();
		var apto     = $('input:radio[name="apto"]:checked').val();
		var luxury   = $('input:radio[name="luxury"]:checked').val();
		var parking  = $('input:radio[name="parking"]:checked').val();
		var comment  = $('textarea[name="comment"]').val();

		var url = $(this).attr('action');

		$.post( url , {_token : _token,  name : name,    email : email,   phone : phone,   date : date,    quantity : quantity, apto : apto, luxury : luxury,  parking : parking, comment : comment}, function(data) {
			
			$('#content-book-response .back').empty();
			$('#content-book-response .back').append(data);
			$("#content-book-response").flip(true);

		});

	});
</script>	
@endsection