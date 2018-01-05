<?php $mobile = new \App\Classes\Mobile(); ?>
<?php if (!$mobile->isMobile()): ?>
	<section id="slider" class="section nomargin noborder" style="padding: 0; max-height: 700px">

		<div class="force-full-screen parallax full-screen dark" style="background-image: url({{asset('/img/miramarski/salon-miramar-apartamento-sierra-nevada.jpg')}});background-position: 50% 0; background-size: contain; background-repeat: no-repeat; max-height: 700px">

			<div class="row container-mobile clearfix">
				<div class="slider-caption slider-caption-center" style="max-width: 85%;">
					<h2 class="text-white white font-w800" data-animate="fadeInDown" style="text-align:center;text-shadow: 1px 1px #000;letter-spacing: -2px">Apartamentos de lujo en Sierra Nevada</h2>

					<h3 class="text-white white push-0" data-animate="fadeInUp" data-delay="400" style="text-align:center;text-shadow: 1px 1px #000;font-size: 56px;letter-spacing: -2px">SERVICIO EXCLUSIVO</h3>

					<h4 class="text-white white font-w300 push-40" data-animate="fadeInUp" data-delay="600" style="text-align:center;text-shadow: 1px 1px #000;font-size: 42px;">Piscina, gimnasio, parking, guarda esquis, salida directa a las pistas</h4>
					<p class="text-center">
						<button class="tp-caption rev-btn  tp-static-layer button button-rounded button-large button-green tright  center hvr-grow-shadow menu-booking" data-animate="fadeInUp" data-delay="1000"
						style="z-index: 8; ">SOLICITA TU RESERVA
						</button>
					</p>
				</div>
			</div>

		</div>

	</section>
<?php else: ?>
<section id="slider" class="section nomargin noborder" style="padding: 0; max-height: 520px">

	<div class="full-screen" style="background-image: url({{asset('/img/miramarski/mobile-slide.jpg')}});background-position: 50% 100%; background-size: cover; background-repeat: no-repeat; max-height: 520px">
		<div class="overlay"></div>
		<div class="row" style="z-index: 5">
			<div class="col-xs-12 push-0" style="padding: 100px 20px 65px 20px; z-index: 10;">
				<h2 class="text-white white font-w800" data-aos="fade-up" style="text-align:center;text-shadow: 1px 1px #000;font-size: 38px;letter-spacing: -2px;line-height: 1; margin: 60px 10px;">APARTAMENTOS DE LUJO EN SIERRA NEVADA</h2>

				
				<p class="text-center push-30">
					<button class="btn btn-lg white menu-booking heart font-w300" style="z-index: 8; background-color: #59BA41; border-radius: 0; padding: 20px; font-size: 21px;letter-spacing: -1px;text-shadow: 1px 1px #000;">SOLICITA TU <span class="font-w800">RESERVA</span>
					</button>
				</p>
			</div>
		</div>

	</div>

</section>
<?php endif; ?>
