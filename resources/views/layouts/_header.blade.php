<?php if (!$mobile->isMobile()): ?>
	<div id="top-bar" class="transparent-topbar hidden-sm hidden-xs">

		<div class="container-fluid clearfix">

			<div class="row center nobottommargin">
				
				<div class="col-lg-10 col-md-8 col-xs-12">
					<div class="top-links">
						<ul>
							<li >
							
							</li>
							<li class="hidden-md hiddens-sm hidden-xs">
								
							</li>
							<li class="hidden-md hiddens-sm hidden-xs">
								
							</li>
						</ul>

					</div>
				</div>
				<div class="col-lg-2 col-md-4 col-xs-12 hiddens-sm hidden-xs">
					<ul class="header-extras-2 divcenter pull-right">
						<li>
							<a class="facebook" href="https://www.facebook.com/Evolutio.fit" ><i class="fa fa-facebook-official fa-2x"></i></a>
						</li>
						<li>
							<a class="instagram" href="#" ><i class="fa fa-instagram fa-2x"></i></a>
						</li>
						<li>
							<a class="whatsapp"  href="whatsapp://send?text=Descubre%20www.evolutio.fit!!%20disfruta%20de%20sus%20ofertas%20exclusivas" data-action="share/whatsapp/share" ><i class="fa fa-whatsapp fa-2x"></i></a>
						</li>
						<li>
							<a class="email" href="mailto:info@evolutio.fit" ><i class="fa fa-envelope fa-2x"></i></a>
						</li>

						<li>
							<a class="map" href="https://www.google.es/maps/place/Av.+Quitapesares,+20,+28670+Villaviciosa+de+Od%C3%B3n,+Madrid/@40.3500423,-3.9011227,17z/data=!3m1!4b1!4m5!3m4!1s0xd418e238472183f:0x5217e4e5e7d47fd3!8m2!3d40.3500423!4d-3.898934" ><i class="fa fa-map-marker fa-2x"></i></a>
						</li>
					</ul>
				</div>
			</div>

		</div>

	</div>
<?php endif; ?>
<!-- Header
============================================= -->	
<header id="header" class="static-sticky transparent-header  not-dark ">

	<div id="header-wrap">

		<div class="container container-mobile clearfix">

			<div id="primary-menu-trigger"><i class="icon-reorder"></i></div>
			<div>
				<div>
					<!-- Logo
					============================================= -->
					
					<!-- <div id="logo">
						<a href="{{url('/')}}" class="standard-logo" data-dark-logo="{{ asset ('frontend/images/logo-dark.png')}}" data-sticky-logo="{{ asset ('frontend/images/logo-dark.png')}}" data-mobile-logo="{{ asset ('frontend/images/logo.png')}}"><img src="{{ asset ('frontend/images/logo.png')}}" alt="Logo"></a>
						<a href="{{url('/')}}" class="retina-logo" data-dark-logo="{{ asset ('frontend/images/logo-dark.png')}}" data-sticky-logo="{{ asset ('frontend/images/logo-dark.png')}}" data-mobile-logo="{{ asset ('frontend/images/logo.png')}}"><img src="{{ asset ('frontend/images/logo.png')}}" alt="Logo"></a>
					</div> -->
				</div>
				<div>
					
					<!-- Primary Navigation
					============================================= -->
					<nav id="primary-menu" class="with-arrows style-2 center">

						<ul>
							<?php if (Request::path() != '/'): ?>
								<li>
									<a  href="{{ url('/') }}"><div style="text-align: center; font-size: 18px;"><i class="fa fa-home fa-2x" style="margin-right: 0;"></i> </div></a></li>
								</li>
							<?php endif ?>
							<li class="mega-menu"><a href="#"><div>Apartamentos</div></a>
								<div class="mega-menu-content style-2 clearfix">
									<ul class="mega-menu-column col-md-6">
										<li class="mega-menu-title"><a href="#" class="font-w600 green"><div>Apartamentos</div></a>
											<ul>
												
												<li>
													<a class="font-w600" href="{{ url('/apartamentos/apartamento-lujo-sierra-nevada')}}"><div>Apartamento de lujo</div></a>
												</li>

												<li>
													<a class="font-w300" href="{{ url('/apartamentos/apartamento-standard-sierra-nevada')}}"><div>Apartamento Standard</div></a>
												</li>
											</ul>
										</li>
									</ul>
									<ul class="mega-menu-column col-md-6">
										<li class="mega-menu-title"><a href="#" class="font-w600 green"><div>Estudios</div></a>
											<ul>
												<li>
													<a class="font-w300" href="{{ url('/apartamentos/estudio-lujo-sierra-nevada')}}"><div>Estudio de lujo</div></a>
												</li>
												
												<li>
													<a class="font-w300" href="{{ url('/apartamentos/estudio-standard-sierra-nevada')}}"><div>Estudio Standard</div></a>
												</li>
											</ul>
										</li>
									</ul>
								</div>
							</li>
							<!-- <li>
								<a href="{{ url('/reserva') }}"><div>Reserva</div></a></li>
							</li> -->
							<li>
								<a href="{{ url('/actividades') }}"><div>¿Qué hacer en sierra nevada?</div></a></li>
							</li>
							
							<li >
								<!-- <a href="#"><div>Reservar</div></a> -->
								<a class="menu-booking" href="#" data-href="#wrapper"><div>Reservar</div></a>
							</li>
							<li>
								<a href="{{ url('/contacto') }}"><div>Contacto</div></a></li>
							</li>
						</ul>

					</nav><!-- #primary-menu end -->

				</div>
				<div></div>

			</div>
			
		</div>

	</div>

</header>
