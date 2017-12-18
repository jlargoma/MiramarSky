<?php $mobile = new \App\Classes\Mobile(); ?>
<?php if (!$mobile->isMobile()): ?>
	<section id="slider" class="section nomargin noborder" style="padding: 0; max-height: 700px">

		<div class="force-full-screen parallax full-screen dark" style="background-image: url({{asset('/img/miramarski/salon-miramar-apartamento-sierra-nevada.jpg')}});background-position: 50% 0; background-size: contain; background-repeat: no-repeat; max-height: 700px">

			<div class="row container-mobile clearfix">
				<div class="slider-caption slider-caption-center" style="max-width: 85%;">
					<h2 class="text-white white font-w800" data-animate="fadeInDown" style="text-align:center;text-shadow: 1px 1px #000;letter-spacing: -2px">Apartamentos de lujo en Sierra Nevada</h2>

					<h3 class="text-white white push-0" data-animate="fadeInUp" data-delay="400" style="text-align:center;text-shadow: 1px 1px #000;font-size: 56px;letter-spacing: -2px">SERVICIO EXCLUSIVO</h3>

					<h4 class="text-white white font-w300 push-40" data-animate="fadeInUp" data-delay="600" style="text-align:center;text-shadow: 1px 1px #000;font-size: 42px;">Piscina, gimnasio, parking, guarda esquis, salida directa a las pistas</h4>
	<!-- 
					<a  href="#" class="button button-3d button-teal button-large nobottommargin" style="margin: 30px 0 0 10px;">Buy Now</a> -->
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
<section id="slider" class="section nomargin noborder" style="padding: 0; max-height: 450px">

	<div class="full-screen" style="background-image: url({{asset('/img/miramarski/mobile-slide.jpg')}});background-position: 50% 100%; background-size: cover; background-repeat: no-repeat; max-height: 450px">

		<div class="row">
			<div class="col-xs-12" style="padding: 100px 20px 0 20px">
				<h2 class="text-white white font-w800" data-animate="fadeInDown" style="text-align:center;text-shadow: 1px 1px #000;letter-spacing: -1px ;line-height:1.3;font-size: 26px;">APARTAMENOS DE LUJO EN SIERRA NEVADA</h2>

				<h3 class="text-white white push-0" data-animate="fadeInUp" data-delay="400" style="text-align:center;text-shadow: 1px 1px #000;font-size: 24px;letter-spacing: -2px">SERVICIO EXCLUSIVO</h3>

				<h4 class="text-white white font-w300 push-40" data-animate="fadeInUp" data-delay="600" style="text-align:center;text-shadow: 1px 1px #000;font-size: 22px;">Piscina, gimnasio, parking, guarda esquis, salida directa a las pistas</h4>
<!-- 
				<a  href="#" class="button button-3d button-teal button-large nobottommargin" style="margin: 30px 0 0 10px;">Buy Now</a> -->
				<p class="text-center push-30">
					<button class="btn btn-lg white menu-booking" data-animate="fadeInUp" data-delay="1000" style="z-index: 8; padding: 15px 10px; background-color: #59BA41; border-radius: 0; padding: 15px 10px; font-size: 14px">SOLICITA TU RESERVA
					</button>
				</p>
			</div>
		</div>

	</div>

</section>
<?php endif; ?>
