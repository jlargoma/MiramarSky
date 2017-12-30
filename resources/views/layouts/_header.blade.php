<?php if (!$mobile->isMobile()): ?>
	<div id="top-bar" class="transparent-topbar hidden-sm hidden-xs">

		<div class="container-fluid clearfix">

			<div class="row center nobottommargin">
				
				<div class="col-lg-10 col-md-8 col-xs-12">

				</div>
				<div class="col-lg-2 col-md-4 col-xs-12 hiddens-sm hidden-xs">
					<ul class="header-extras-2 divcenter pull-right">
						<li>
							<a class="facebook" href="https://www.facebook.com/alquilerlujosierranevada/?ref=bookmarks" ><i class="fa fa-facebook-official fa-2x"></i></a>
						</li>
						<li>
							<a class="instagram" href="#" ><i class="fa fa-instagram fa-2x"></i></a>
						</li>
						<li>
							<a class="whatsapp"  href="whatsapp://send?text=Descubre%20www.evolutio.fit!!%20disfruta%20de%20sus%20ofertas%20exclusivas" data-action="share/whatsapp/share" ><i class="fa fa-whatsapp fa-2x"></i></a>
						</li>
						<li>
							<a class="email" href="mailto:reservas@apartamentosierranevada.net" ><i class="fa fa-envelope fa-2x"></i></a>
						</li>

						<li>
							<a class="map" href="https://www.google.com/maps?ll=37.093311,-3.396972&z=17&t=m&hl=es-ES&gl=ES&mapclient=embed&cid=335969053959651753" target="_blank"><i class="fa fa-map-marker fa-2x"></i></a>
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
									<a  href="{{ url('/') }}"><div style="text-align: center; font-size: 18px;"><i class="fa fa-home fa-2x" style="margin-right: 0;font-size: 20px!important"></i> </div></a></li>
								</li>
							<?php endif ?>
							<li class="mega-menu"><a href="#"><div>Apartamentos</div></a>
								<div class="mega-menu-content style-2 clearfix">
									<ul class="mega-menu-column col-md-6">
										<li class="mega-menu-title">

											<ul>
												
												<li>
													<a class="font-w600" href="{{ url('/apartamentos/apartamento-lujo-sierra-nevada')}}"><div>Apartamento 2 DORM de lujo</div></a>
												</li>

												<li>
													<a class="font-w600" href="{{ url('/apartamentos/apartamento-lujo-gran-capacidad-sierra-nevada')}}"><div>Apartamento 3 DORM GRAN OCUPACION</div></a>
												</li>

												<li>
													<a class="font-w300" href="{{ url('/apartamentos/apartamento-standard-sierra-nevada')}}"><div>Apartamento 2 DORM Standard</div></a>
												</li>
											</ul>
										</li>
									</ul>
									<ul class="mega-menu-column col-md-6">
										<li class="mega-menu-title">
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

			</div>
			
		</div>

	</div>

</header>
