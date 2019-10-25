@extends('layouts.master_withoutslider')
@section('css')
	<!-- <link rel="stylesheet" href="/frontend/css/components/radio-checkbox.css" type="text/css" /> -->
	<style type="text/css">
		#primary-menu ul li  a{
			color: #3F51B5!important;
		}
		#primary-menu ul li  a div{
			text-align: left!important;
		}
		#content-form-book {
	    	padding: 40px 15px;
		}
		.votes{
			width: 20%;
			float: left;
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
		}
	</style>
@endsection

@section('title')Encuesta de satisfacción - Apartamentos de lujo en Sierra Nevada a pie de pista @endsection

@section('content')
<?php if (!$mobile->isMobile()): ?>
<section class="section page" style="min-height: 420px; padding-top: 0">
	<div class="slider-parallax-inner" style="background-color: white;">
		<div class="row text-center push-20" style="background-image: url({{ asset('/img/rating.jpg')}}); background-size: cover; background-position: 50% 35%; padding: 40px 0 0;">
			<div class="heading-block center text-white">
				<h1 style="color:white; text-shadow: 1px 1px #000">Encuesta de satisfacción</h1>
			</div>
		</div>
		<div class="container">

			<?php if ($vote == 0): ?>
				<div class="row">
					<div class="col-xs-12">
						<h2 class="text-center push-10 font-w300">
							ESTIMADO <span class="font-w800">CLIENTE</span>
						</h2>
						<p class="text-justify">
							Con el fin de mejorar la calidad de nuestros servicios y asegurar la satisfacción de todos nuestros visitantes, agradeceríamos respondiera a este cuestionario. Puntué del 1 (Muy mal) al 5 (muy bien) los siguientes aspectos de este establecimiento.
						</p>
					</div>
					<form id="form-votes" class="form-horizontal" action="{{ url('questions/vote')}}" method="post">
	    				<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
	    				<input type="hidden" name="room_id" value="<?php echo $book->room_id; ?>">
						<div class="col-xs-12 push-20">
							<?php foreach ($questions as $key => $question): ?>
								<div class="col-md-6 col-xs-12 push-20">
									<div class="col-xs-12">
										<h3 class="text-left font-w800 push-0" style="line-height: 1; letter-spacing: -1px;">
											<?php echo $question->question; ?>
										</h3>
									</div>
									<div class="col-xs-12 text-center">
										<?php for ($i=1; $i <= 5 ; $i++) :?>
											<div class="votes">
												<input id="radio-<?php echo $i?>" class="radio-style" name="question[<?php echo $question->id ?>]" type="radio" <?php if( $i == 5){echo 'checked="checked" '; } ?> value="<?php echo $i ?>">
												<label for="radio-<?php echo $i?>" class="radio-style-3-label font-w300" style="font-size: 18px;">
													<?php echo $i ?> <i class="fa fa-star fa-2x" style="color: #fde16d"></i>
												</label>
											</div>
										<?php endfor; ?>
									</div>
								</div>
							<?php endforeach ?>
						</div>
						<div class="col-xs-12 text-center push-20">
							<label>Comentarios o sugerencias sobre el apartamento?</label>
							<textarea class="form-control" rows="10"  name="question[7]" placeholder="Comentarios y sugerencias...."></textarea>
						</div>
						<div class="col-xs-12 text-center">
							<button type="submit" class="button button-3d button-rounded button-green">Votar</button>
						</div>
					</form>
					
				</div>
			<?php else: ?>
				<div class="row">
					<div class="col-padding">
						<div class="heading-block center nobottomborder push-20">
							<h2 class="black">Muchas gracias por tus valoraciones!</h2>
							<span class="black">Las tendremos muy en cuenta para seguir mejorando tus vacaciones.</span>
						</div>
						<div class="heading-block center nobottomborder nobottommargin">
							Nos encantaria contar con tu opinión tambien en Google, por favor rellena este formulario y ayudanos a crecer en Google.<br>
							<a href="https://www.google.es/maps/place/Alquiler+Apartamento+de+Lujo+Sierra+Nevada+-+Edif+Miramar+Ski/@37.093311,-3.3991607,17z/data=!3m1!4b1!4m7!3m6!1s0xd71dd38d505f85f:0x4a99a1314ca01a9!8m2!3d37.093311!4d-3.396972!9m1!1b1">
								Escribir reseña
							</a>
						</div>
					</div>
				</div>
			<?php endif ?>

		</div>
	</div>
</section>
<?php else: ?>

	<section class="section page" style="min-height: 420px; padding-top: 0;margin-top: 25px;">
		<div class="slider-parallax-inner">
			<div class="row text-center push-20" style="background-image: url({{ asset('/img/rating.jpg')}}); background-size: cover; background-position: 50% 35%; padding: 40px 0 0;">
				<div class="col-xs-12 heading-block center text-white">
					<h1 style="color:white; text-shadow: 1px 1px #000">Encuesta de satisfacción</h1>
				</div>
			</div>
			<div class="container-mobile">
				<?php if ($vote == 0): ?>
					<div class="row">
						<div class="col-xs-12">
							<h2 class="text-center push-10 font-w300">
								ESTIMADO <span class="font-w800">CLIENTE</span>
							</h2>
							<div class="col-xs-12">
								<p class="text-justify">
									Con el fin de mejorar la calidad de nuestros servicios y asegurar la satisfacción de todos nuestros visitantes, agradeceríamos respondiera a este cuestionario. Puntué del 1 (Muy mal) al 5 (muy bien) los siguientes aspectos de este establecimiento.
								</p>
							</div>
						</div>
						<form id="form-votes" class="form-horizontal" action="{{ url('questions/vote')}}" method="post">
		    				<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
		    				<input type="hidden" name="room_id" value="<?php echo $book->room_id; ?>">
							<div class="col-xs-12 push-30">
								<?php foreach ($questions as $key => $question): ?>
									<div class="col-md-6 col-xs-12 push-20">
										<div class="col-xs-12">
											<h3 class="text-left font-w800 push-10" style="line-height: 1; letter-spacing: -1px;font-size: 18px;">
												<?php echo $question->question; ?>
											</h3>
										</div>
										<div class="col-xs-12 text-center">
											<?php for ($i=1; $i <= 5 ; $i++) :?>
												<div class="votes">
													<input id="radio-<?php echo $i?>" class="radio-style" name="question[<?php echo $question->id ?>]" type="radio" <?php if( $i == 5){echo 'checked="checked" '; } ?> value="<?php echo $i ?>">
													<label for="radio-<?php echo $i?>" class="radio-style-3-label font-w300" style="font-size: 18px;">
														<?php echo $i ?> <i class="fa fa-star fa-2x" style="color: #fde16d"></i>
													</label>
												</div>
											<?php endfor; ?>
										</div>
									</div>
								<?php endforeach ?>
							</div>
							<div class="col-xs-12 text-center push-20">
								<div class="col-xs-12 text-center push-20">

									<label>Comentarios o sugerencias sobre el apartamento?</label>
									<textarea class="form-control" rows="5"  name="question[7]" placeholder="Comentarios y sugerencias...."></textarea>
								</div>
							</div>
							<div class="col-xs-12 text-center">
								<button type="submit" class="button button-3d button-rounded button-green">Votar</button>
							</div>
						</form>
						
					</div>
				<?php else: ?>
					<div class="row">
						<div class="col-padding">
							<div class="heading-block center nobottomborder push-20">
								<h2 class="black">Muchas gracias por tus valoraciones!</h2>
								<span class="black">Las tendremos muy en cuenta para seguir mejorando tus vacaciones.</span>
							</div>
							<div class="heading-block center nobottomborder nobottommargin">
								Nos encantaria contar con tu opinión tambien en Google, por favor rellena este formulario y ayudanos a crecer en Google.<br>
								<a href="https://www.google.es/maps/place/Alquiler+Apartamento+de+Lujo+Sierra+Nevada+-+Edif+Miramar+Ski/@37.093311,-3.3991607,17z/data=!3m1!4b1!4m7!3m6!1s0xd71dd38d505f85f:0x4a99a1314ca01a9!8m2!3d37.093311!4d-3.396972!9m1!1b1">
									Escribir reseña
								</a>
							</div>
						</div>
					</div>
				<?php endif ?>

			</div>
		</div>
	</section>
<?php endif; ?>

@endsection

@section('scripts')
	<script type="text/javascript" src="/frontend/js/components/star-rating.js"></script>
@endsection