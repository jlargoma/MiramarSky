@extends('layouts.master_pages')
<link rel="stylesheet" href="{{ asset('/frontend/css/components/daterangepicker.css')}}" type="text/css" />
<link rel="stylesheet" href="{{ asset('/frontend/css/components/radio-checkbox.css')}}" type="text/css" />

<style type="text/css">
	label{
		color: white!important
	}
</style>
@section('title') {{ $aptoHeading }} en Sierra Nevada @endsection


@section('slider')

<?php if ($typeApto == 1): ?>

	<meta name="description" content="Alquiler de apartamento de lujo dos dormitorios en Sierra nevada, el edificio miramarski esá a pie de pista, los apartamentos tienen capacidad 6 / 8 personas, con piscina climatizada, gimansio y parking cubierto; el edificio esta en zona baja, a 5 minutos de la plaza de Andalucía" />

	<meta name="keywords" content="alquiler apartamento de lujo;dos dormitorios;sierra nevada;recien reformado;a pie de pista;capacidad 8 personas;piscina climatizada;gimansio;zona baja">


<?php elseif($typeApto == 2): ?>

	<meta name="description" content="Alquiler apartamento dos dormitorios en Sierra nevada, los apartamentos tienen capacidad 6 / 8 personas, con piscina climatizada, gimansio y parking cubierto; el edificio esta en zona baja, a 5 minutos de la plaza de Andalucía" />

	<meta name="keywords" content="alquiler apartamento dos dormitorios;sierra nevada;edificio miramarski;a pie de pista;capacidad 6 / 8 personas;piscina climatizada;gimansio;zona baja">


<?php elseif($typeApto == 3): ?>

	<meta name="description" content="Alquiler apartamentos y estudios;sierra nevada;edificio miramarski;los apartamentos tienen capacidad 4 / 5 personas, con piscina climatizada, gimansio y parking cubierto; el edificio esta en zona baja, a 5 minutos de la plaza de Andalucía" />

	<meta name="keywords" content="alquiler apartamentos y estudios;sierra nevada;edificio miramarski;a pie de pista;capacidad 4 / 5 personas;piscina climatizada;gimansio;zona baja">


<?php elseif($typeApto == 4): ?>

	<meta name="description" content="Alquiler apartamento y estudio de lujo, sierra nevada en el edificio miramarski, los apartamentos tienen capacidad 4 /5 personas, con piscina climatizada, gimansio y parking cubierto; el edificio esta en zona baja, a 5 minutos de la plaza de Andalucía" />

	<meta name="keywords" content="alquiler apartamento y estudio;de lujo; sierra nevada;edificio miramarski;a pie de pista;capacidad 4 / 5 personas;piscina climatizada;gimansio;zona baja">


<?php endif ?>

<section id="slider" class="revslider-wrap clearfix">

	<div class="rev_slider_wrapper" style="background-color:#eef0f1;padding:0px;">
		<!-- START REVOLUTION SLIDER 5.3.1.6 fullscreen mode -->
		<div id="rev_slider_15_1" class="rev_slider fullscreenbanner" style="display:none;" data-version="5.3.1.6">
			<ul>
				<?php foreach ($slides as $key => $slide): ?>
					<!-- SLIDE  -->
					<li class="dark" data-index="rs-3<?php echo $key+1?>" data-transition="slidehorizontal" data-slotamount="default" data-hideafterloop="0" data-hideslideonmobile="off"  data-easein="default" data-easeout="default" data-masterspeed="2000"  data-thumb="{{ asset('/img/miramarski/galerias/')}}/<?php echo $url ?>/<?php echo $slide->getFilename() ?>"  data-rotate="0"  data-fstransition="slidehorizontal" data-fsmasterspeed="1000" data-fsslotamount="7" data-saveperformance="off"  data-title="One" data-param1="" data-param2="" data-param3="" data-param4="" data-param5="" data-param6="" data-param7="" data-param8="" data-param9="" data-param10="" data-description="">
						<!-- MAIN IMAGE -->
						<img src="{{ asset('/img/miramarski/galerias/')}}/<?php echo $url ?>/<?php echo $slide->getFilename() ?>"  alt="<?php echo $slide->getFilename() ?>"  data-bgposition="<?php if($mobile->isMobile()){ echo 'cover'; }else{ echo 'center center'; }?>" data-kenburns="on" data-duration="20000" data-ease="Linear.easeNone" data-scalestart="130" data-scaleend="100" data-rotatestart="0" data-rotateend="0" data-offsetstart="0 0" data-offsetend="0 0" data-bgparallax="13" class="rev-slidebg" data-no-retina>
	                    <div class="tp-caption tp-shape tp-shapewrapper   tp-resizeme"
							id="slide-3<?php echo $key+1?>-layer-<?php echo $key+1?>"
							data-x="['center','center','center','center']" data-hoffset="['0','0','0','0']"
							data-y="['middle','middle','middle','middle']" data-voffset="['0','0','0','0']"
							data-width="full"
							data-height="full"
							data-whitespace="nowrap"
							data-transform_idle="o:1;"

							data-transform_in="z:0;rX:0deg;rY:0;rZ:0;sX:1.5;sY:1.5;skX:0;skY:0;opacity:0;s:1500;e:Power3.easeOut;"
							data-transform_out="auto:auto;s:1000;"
							data-mask_in="x:0px;y:0px;s:inherit;e:inherit;"
							data-start="0"
							data-basealign="slide"
							data-responsive_offset="on"

							style="z-index: 5;background-color:rgba(0, 0, 0, 0.35);border-color:rgba(0, 0, 0, 1.00);">
						</div>
					</li>
				<?php endforeach ?>
			</ul>
			<div class="tp-bannertimer" style="height: 10px; background-color: rgba(255, 255, 255, 0.25);"></div>	</div>
		</div><!-- END REVOLUTION SLIDER -->
	</div><!-- END REVOLUTION SLIDER WRAPPER -->

</section>

@endsection

@section('content')
	
	<section id="content">

		<div class="container clearfix push-20">
			<div class="row" style="margin-top: 40px;">
				<h2 class="center hidden-sm hidden-xs psuh-20"><?php echo strtoupper($aptoHeading); ?></h2>
				<h2 class="center hidden-lg hidden-md push-10"><?php echo strtoupper($aptoHeadingMobile); ?></h2>
				
				

				<div class="row clearfix center push-30">

					<?php if ($typeApto == 1): ?>
						@include('frontend.pages._infoAptoLujo')
					<?php elseif($typeApto == 2): ?>
						@include('frontend.pages._infoAptoStandard')
					<?php elseif($typeApto == 3): ?>
						@include('frontend.pages._infoEstudioLujo')
					<?php elseif($typeApto == 4): ?>
						@include('frontend.pages._infoEstudioStandard')
					<?php endif ?>
					
					<button id="showFromBook" class="button button-desc button-3d button-rounded bg-bluesky center white">¡Reserva YA!</button>

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
				<?php foreach ($aptos as $key => $apartamento): ?>
					<?php if ($apartamento != $url): ?>
						<div class="col-md-4 hover-effect">
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