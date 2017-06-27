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
						<span class="before-heading color">CEO &amp; Co-Founder</span>
						<h3>GALERIA APARTAMENTO DE LUJO 2 DORMITORIOS</h3>
					</div>

					<div class="row clearfix">

						<div class="push-10">
							<h3 class="green">LOREM IPSUM DOLOR SIT AMET</h3>
						</div>

						<p class="font-s18 ls-15 miramar font-w300 text-justify">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Vero quod consequuntur quibusdam, enim expedita sed quia nesciunt incidunt accusamus necessitatibus modi adipisci officia libero accusantium esse hic, obcaecati, ullam, laboriosam!</p>

						<p class="font-s18 ls-15 miramar font-w300 push-0 text-justify">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti vero, animi suscipit id facere officia. Aspernatur, quo, quos nisi dolorum aperiam fugiat deserunt velit rerum laudantium cum magnam excepturi quod, fuga architecto provident, cupiditate delectus voluptate eaque! Sit neque ut eum, voluptatibus odit cum dolorum ipsa voluptates inventore cumque a.</p>

					</div>

				</div>
			</div>

		</div>

		<div class="row bg-bluesky col-padding" style="background-image: url({{asset('/img/miramarski/esquiadores.png')}}); background-repeat: no-repeat;background-size: contain; background-position: right bottom;  ">
			<div class="container-fluid">
				<div class="col-xs-12 col-md-6">
					<form id="form-book-apto-lujo">
	                    <div class="row">
	                        <div class="block-header text-center push-20">
	                            <h3 class="white text-white" style="margin-bottom:0px">HAZ TU RESERVA</h3>
	                        </div>
	                        <div class="block-content">
	                            <div class="form-group col-sm-12 col-xs-12 col-md-4 col-lg-4 white">
	                                <label>*Nombre</label>
	                                <input type="text" class="sm-form-control" id="nombre" placeholder="Nombre..." maxlength="40" required="">
	                            </div>
	                            <div class="form-group col-sm-12 col-xs-12 col-md-4 col-lg-4 white">
	                                <label>*Email</label>
	                                <input type="email" class="sm-form-control" id="email" placeholder="Email..." maxlength="40" required="">
	                            </div>
	                            <div class="form-group col-sm-12 col-xs-12 col-md-4 col-lg-4 white">
	                                <label>*Telefono</label>
	                                <input type="text" class="sm-form-control only-numbers" id="telefono" placeholder="Teléfono..." maxlength="9" required="">
	                            </div>

	                            <div class="form-group col-sm-10 col-xs-10 col-md-4 white">
	                                <label>*Entrada - Salida</label>
	                                <div class="input-group">
	                                    <input type="text" class="sm-form-control daterange1" required>
	                                </div>
	                                <p class="help-block white" style="line-height:1.2">La entrada es a partir de la 16:00 pm
	                                    <br><b>* ESTANCIA MÍNIMA: 2 NOCHES</b>
	                                </p>
	                            </div>
	                            <div class="form-group col-sm-6 col-xs-6 col-md-3 white">
	                           		<label>*Ocupantes</label>
	                            	<div class="quantity center clearfix divcenter">
										<input type="button" value="-" class="minus black" style=" color: black;">
										<input id="quantity" type="text" name="quantity" value="4" class="qty" style="background: white; color: black;">
										<input type="button" value="+" class="plus black" style=" color: black;">
									</div>
	                                
	                                <p class="help-block white" style="line-height:1.2">Máximo 8 personas</p>
	                                <p class="help-block white" style="color: white; line-height:1.2;">    
	                                    <b>* SOLICITAR APTO DOS DORM 6 PAX</b>
	                                </p>
	                            </div>
	                            <div class="form-group col-sm-3 col-xs-6 col-md-3">
	                                <label class="parking white">*Parking</label>
	                                <div>
										<input id="parking-yes" class="radio-style" name="parking" type="radio" checked="" value="true">
										<label for="parking-yes" class="radio-style-3-label">Si</label>
									</div>
									<div>
										<input id="parking-no" class="radio-style" name="parking" type="radio" value="false">
										<label for="parking-no" class="radio-style-3-label">No</label>
									</div>
	                            </div>
                                <input type="hidden" name="apto-lujo" value="true">
	                            <div class="form-group col-sm-12 col-xs-12 col-md-12 col-lg-12">
	                                <div class="input-group col-sm-12 col-xs-12 col-md-12 col-lg-12">
	                                    <textarea class="sm-form-control" rows="3" placeholder="Comentanos aqui tus dudas o inquietudes." id="coment" maxlength="200"></textarea>
	                                </div>
	                            </div>
	                            <div class="form-group col-sm-12 col-xs-12 col-md-12 col-lg-12 text-center">
	                                <button type="button" class="button button-3d button-xlarge button-rounded button-white button-light" id="confirm-reserva">Solicitar reserva</button>
	                            </div>
	                        </div>
	                    </div>
	                </form>
                </div>
			</div>
		</div>
	</section>
	
@endsection
@section('scripts')
<script type="text/javascript" src="{{asset('/frontend/js/components/moment.js')}}"></script>
<script type="text/javascript" src="{{asset('/frontend/js/components/daterangepicker.js')}}"></script>
<script type="text/javascript">
	$(function() {
		// .daterange1
		$(".daterange1").daterangepicker({
			"buttonClasses": "button button-rounded button-mini nomargin",
			"applyClass": "button-color",
			"cancelClass": "button-light"
		});
	});

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
	$('.minus').click(function(event) {
		var aux = parseInt($('#quantity').val());
		if (aux  > 4) {
			aux = aux-1;
			$('#quantity').val(aux);
		}
	});
	$('.plus').click(function(event) {
		var aux = parseInt($('#quantity').val());
		if (aux  < 8) {
			aux = aux+1;

			$('#quantity').val(aux);
		}
		
	});

</script>	
@endsection