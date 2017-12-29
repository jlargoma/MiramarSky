@extends('layouts.master_withoutslider')
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
			position: fixed!important;
		    top: 15%!important;
		}
		.img{
			max-height: 530px;
		}
		.button.button-desc.button-3d{
			background-color: #4cb53f!important;
		}

		.img.img-slider-apto{
			height: 250px!important;
		}
	}
	
</style>
@section('metadescription')Restaurantes en Sierra Nevada @endsection
@section('title') Restaurantes en Sierra Nevada @endsection

@section('content')
	<section id="content">

		<div class="content-wrap">

			<div class="container container-mobile clearfix">

				<div class="single-post nobottommargin">

					<!-- Single Post
					============================================= -->
					<div class="entry clearfix">

						<!-- Entry Title
						============================================= -->
						<div class="entry-title">
							<h1 class="text-center push-0" style="text-transform: uppercase;line-height: 1; letter-spacing: -2px;">Restaurantes</h1>
							<h2 class="text-center font-w300 font-s18 push-20" style="text-transform: uppercase;line-height: 1; letter-spacing: -2px;font-size: 16px!important;">¿Que comprar o donde Comer en Sierra Nevada?<br>Listado de Comercios, Bares y Restaurantes de la Estación de Esquí</h2>
						</div><!-- .entry-title end -->
						<div class="entry-image bottommargin">
							<a href="{{ url('/restaurantes')}}">
								<img src="{{ asset('/img/posts/restaurante-sierra-nevada.jpg')}}" alt="Restaurantes">
							</a>
						</div><!-- .entry-image end -->

						<!-- Entry Content
						============================================= -->
						<div class="entry-content notopmargin">
							
							<div class="col-xs-12 push-20 ">
								<p class="text-justify font-s18 font-w300">
									Sierra Nevada cuenta con muchos bares y restaurantes de todo tipo. Abundan en varias zonas, desde la que podemos denominar la zona principal de la estación, Pradollano, como en zonas más altas como Borreguiles e incluso los tan exitosos a pie de pistas a los que muchos acuden por su cercanía. Existen muchos restaurantes en Sierra Nevada y te vamos a mostrar  los que mejor relación calidad precio tienen.

									Estamos seguros de que entre tantos establecimientos para desayunar, comer, cenar o simplemente para hacer un descanso, vas a encontrar el que más encaje con tus gustos. Para que puedas dar con ellos, vamos a mostrarte los más destacados para que a la hora de elegir tengas las menores dudas posibles.

									Algo con lo que debes contar es que los precios a nivel de restauración en Sierra Nevada son un poco más elevados. En esto coincide con todas las estaciones de esquí, pero no por eso debemos dejar de disfrutar de una buena comida mientras estamos de vacaciones ¿no? solamente hay que saber elegir bien y seguro que quedas bien satisfecho.  Sin más dilación, vamos con los restaurantes… ¡Comenzamos!

									Lo mejor es situarse, para ello os indicamos un plano de Sierra Nevada donde aparecen algunos de los restaurantes indicados:
								</p>
							</div>
							<div class="col-xs-12 push-20 text-center">
								<img class="img-responsive" src="{{ asset('/img/posts/mapa-restaurante-sierra-nevada.png')}}" alt="Mapa" style="margin: 0 auto;">
							</div>

							<div class="col-xs-12 push-20">
								<h3 class="text-justify">Restaurantes en Sierra nevada, los imprescindibles</h3>
								<p class="text-justify font-s18 font-w300">
									
									Vamos a dividir los restaurantes según los gustos, de esta forma puedes ver lo que tienes a tu alcance para degustar en Sierra Nevada. Encontramos principalmente 6 grandes grupos de los que te contaremos los principales establecimientos:<br><br>

									- Especialidad en carne y comida casera<br>
									- Comida típica de Granadina o andaluza<br>
									- Cocina de estilo mediterráneo o variada<br>
									- Pizzerías<br>
									- Comida rápida y en pista<br>
									- Bares<br>
								</p>
							</div>

							<div class="col-xs-12 push-20">
								<h2 class="text-justify">Especialidad en carne y comida casera</h3>
								<img class="img-responsive push-20" src="http://www.apartamentosierranevada.net/actividades/wp-content/uploads/2017/12/especialidad-carne.png" alt="especialidad carne" style="margin: 0 auto;"/>
								<p class="text-justify font-s18 font-w300">
									En Sierra Nevada a la hora de buscar restaurantes en Sierra Nevada buscamos en no pocas ocasiones buenos platos y en especial buena carne. Vamos a recomendaros algunos sitios donde seguro que vuestro apetito quedará bien saciado.
								</p>
							</div>

							<div class="col-xs-12 push-20 local-post">
								<h3 class="text-justify">Little Morgan</h3>
								<div class="col-md-3 col-xs-12">
									<img class="img-responsive push-20" src="http://www.apartamentosierranevada.net/actividades/wp-content/uploads/2017/12/little-morgan-300x169.jpg" alt="little morgan" style="margin: 0 auto;">
								</div>
								<div class="col-md-9 col-xs-12">

									<p class="text-justify font-s18 font-w300">
										Afamado restaurante que cuenta con magnífica carne a la piedra. Además también cuentan con un variado menú para que no solo los carnívoros disfruten. Magnífico ambiente en un restaurante que está entre los más destacados de la estación.<br><br>

										<b>Contacto</b><br>
										Plaza Andalucía, Edificio Monachil, s/n 18196 Sierra Nevada España<br>
										<a href="tel:958481286">958 481 286</a>

									</p>

								</div>
							</div>
							
							<div class="col-xs-12 push-20 local-post">
								<h3 class="text-justify">Horno La Cabaña</h3>
								<div class="col-md-3 col-xs-12">
									<img class="img-responsive push-20" src="http://www.apartamentosierranevada.net/actividades/wp-content/uploads/2017/12/horno-la-cabaña-300x231.png"  style="margin: 0 auto;">
								</div>
								<div class="col-md-9 col-xs-12">

									<p class="text-justify font-s18 font-w300">
										En este horno-restaurante puedes degustar una magnífica carne a la brasa ( su especialidad), destaca por un trato casero que le da ese toque especial.<br><br>

										<b>Contacto</b><br>
										Edif. Saporo, nº 2 bajo<br>
										<a href="tel:958480013">958 480 013</a>

									</p>

								</div>
							</div>


							<div class="col-xs-12 push-20 local-post">
								<h3 class="text-justify">Restaurante Andalusí</h3>
								<div class="col-md-3 col-xs-12">
									<img class="img-responsive push-20" src="https://www.apartamentosierranevada.net/actividades/wp-content/uploads/2017/12/restaurante-andalus%C3%AD.png" alt="Restaurante Andalusí" style="margin: 0 auto;">
								</div>
								<div class="col-md-9 col-xs-12">

									<p class="text-justify font-s18 font-w300">
										En él podemos probar carne de buena calidad, pero también toca otros tipos de comida, por lo que ante todo, la variedad forma parte del menú.<br><br>

										<b>Contacto</b><br>
										Plaza Andalucia | Edificio Montebajo 7, 18196, Sierra Nevada<br>
										<a href="tel:958480206">958 480 206</a>

									</p>

								</div>
							</div>


							<div class="col-xs-12 push-20 local-post">
								<h3 class="text-justify">Mesón La Alcazaba</h3>
								<div class="col-md-3 col-xs-12">
									<img class="img-responsive push-20" src="https://www.apartamentosierranevada.net/actividades/wp-content/uploads/2017/12/restaurante_alcazaba.jpg" alt="Mesón La Alcazaba" style="margin: 0 auto;">
								</div>
								<div class="col-md-9 col-xs-12">

									<p class="text-justify font-s18 font-w300">
										Está situado en una localización privilegiada, donde podemos almorzar a la carta. En ella destaca su carne al carbón, pero tampoco hay que olvidar sus arroces entre otros manjares. Cuenta con preciosas vistas al Veleta y a las pistas de los Borreguiles.<br><br>

										<b>Contacto</b><br>
										Junto Edf. Borreguiles, planta Baja<br>
										<a href="tel:687073058">687073058</a>

									</p>

								</div>
							</div>


							<div class="col-xs-12 push-20 local-post">
								<h3 class="text-justify">Restaurante La Carreta</h3>
								<div class="col-md-3 col-xs-12">
									<img class="img-responsive push-20" src="https://www.apartamentosierranevada.net/actividades/wp-content/uploads/2017/12/restaurante-la-carreta-300x169.jpg" alt="Restaurante La Carreta" style="margin: 0 auto;">
								</div>
								<div class="col-md-9 col-xs-12">

									<p class="text-justify font-s18 font-w300">
										<b>Contacto</b><br>
										Plaza Pradollano s/n, Edificio Mont Blanc<br>
										<a href="tel:958480554">958 480 554</a>

									</p>

								</div>
							</div>

							<div class="col-xs-12 push-20">
								<h2 class="text-justify">Comida granadina y andaluza</h2>
								<img class="img-responsive push-20 aligncenter" src="http://www.apartamentosierranevada.net/actividades/wp-content/uploads/2017/12/comida-andaluza.jpg" alt="comida andaluza" width="640" height="427" style="margin: 0 auto;"/>
								<p class="text-justify font-s18 font-w300" >
									Estamos en Granada ¿no?, Andalucía ¿no? pues que mejor que probar los ricos platos de la gastronomía de la zona. Si buscamos restaurantes en Granada y queremos descubrirlos o bien volver a probarlos te decimos algunos de los mejores lugares para disfrutar. ¡Buen provecho!
								</p>
							</div>

							<div class="col-xs-12 push-20 local-post">
								<h3>Restaurante La Visera</h3>
								<div class="col-md-3 col-xs-12">
									<img class="wp-image-6181 size-medium alignnone" src="http://www.apartamentosierranevada.net/actividades/wp-content/uploads/2017/11/Restaurante-la-Visera-300x165.png" alt="Restaurante la Visera" width="300" height="165" />
								</div>
								<div class="col-md-9 col-xs-12">
									Un buen restaurante en el que se puede disfrutar de una magnífica comida mientras tenemos increíbles vistas. Excelente ubicación. A no perderse "La picaña de Angus" y los archifamosos fingers de pollo. Cuenta con grandes opiniones de los comensales que destacan el buen trato y la calidad de sus productos.<br><br>
									<strong>Contacto</strong><br>
									Edf. Montebajo, local 1 Avenida Andalucia, 18494 Sierra Nevada, España<br>
									638 58 83 73
								</div>
							</div>


							<div class="col-xs-12 push-20 local-post">
								<h3>La Tinaja de la Sierra</h3>
								<div class="col-md-3 col-xs-12">
									<img class="alignnone wp-image-6215" src="http://www.apartamentosierranevada.net/actividades/wp-content/uploads/2017/12/la-tinaja-300x252.png" alt="la tinaja" width="276" height="232" />
								</div>
								<div class="col-md-9 col-xs-12">
									Se encuentra situado en pleno corazón de la Estación de Esquí de Sierra Nevada, en el Hotel Meliá Sierra Nevada, un lugar donde disfrutar la mejor comida regional y como no, algunos de los mejores platos internacionales. Resaltar su decoración típica de alta montaña.<br><br>
									<strong>Contacto</strong><br>
									Calle de la Virgen de las Nieves, 18196 Monachil, Granada<br>
									958 480 400
								</div>
							</div>

							<div class="col-xs-12 push-20 local-post">
								<h3>Restaurante La Higuera</h3>
								<div class="col-md-3 col-xs-12">
									<img class="alignnone size-medium wp-image-6216" src="http://www.apartamentosierranevada.net/actividades/wp-content/uploads/2017/12/la-higuera-300x252.png" alt="la higuera" width="300" height="252" />
								</div>
								<div class="col-md-9 col-xs-12">
									Otro restaurante de comida típica regional, donde destaca el típico plato alpujarreño y otras especialidades, como el rico choto, habas con jamón o chuletas de cordero.<br><br>
									<strong>Contacto</strong><br>
									Carretera Sierra Nevada, Km 16<br>
									958 340 417
								</div>
							</div>
							
							<div class="col-xs-12 push-20 local-post">
								<h3>Restaurante El Guerra</h3>
								<div class="col-md-3 col-xs-12">
									<img class="" src="http://hotelelguerra.com/wp-content/uploads/2015/11/parking-gratuito-guerra-en-sierra-nevada.png" alt="parking gratuito guerra en sierra nevada" width="301" height="226" />
								</div>
								<div class="col-md-9 col-xs-12">
									Aprovechan el poder contar con un entorno que hace posible contar con un entorno donde pueden tener acceso a buena materia prima de la zona. Los mejores platos tradicionales de Granada a tu alcance, desde las típicas habas con jamón al cochinillo.<br><br>
									<strong>Contacto</strong><br>
									Ctra. Sierra Nevada, Km. 21,3 – 18160 Sierra Nevada, Granada<br>
									958 484 836
								</div>
							</div>

							<div class="col-xs-12 push-20">
								<h2 class="text-justify">Cocina de estilo mediterráneo o variada</h2>
								<img class="wp-image-6223 aligncenter" src="http://www.apartamentosierranevada.net/actividades/wp-content/uploads/2017/12/comida-mediterranea.jpg" alt="comida mediterranea" width="694" height="399" />
								<p class="text-justify font-s18 font-w300" >
									La comida mediterránea es valorada en todo el mundo y también hay sitio para ella en muchos restaurantes en Sierra Nevada. Si estás preparado para probar una de las mejores gastronomías del mundo, aquí tienes unos buenos lugares para quedar satisfecho.
								</p>
							</div>

							<div class="col-xs-12 push-20 local-post">
								<h3>Restaurante La Bodega</h3>
								<div class="col-md-3 col-xs-12">
									<img class="wp-image-6185 size-medium alignnone" src="http://www.apartamentosierranevada.net/actividades/wp-content/uploads/2017/11/restaurante-la-bodega-300x138.png" alt="restaurante la bodega" width="300" height="138" />
								</div>
								<div class="col-md-9 col-xs-12">
									Su excelente ubicación tiene el atractivo de sus grandes platos y unas tapas que hacen de él una de las referencias de la zona. Un restaurante para cualquier hora del día y con especial encanto para las cenas.<br><br>
									<strong>Contacto</strong><br>
									Plaza Andalucía, 0 S/N<br>
									958 24 91 33
								</div>
							</div>

							<div class="col-xs-12 push-20 local-post">
								<h3>Restaurante Pourquoi Pas</h3>
								<div class="col-md-3 col-xs-12">
									<img class="alignnone size-medium wp-image-6219" src="http://www.apartamentosierranevada.net/actividades/wp-content/uploads/2017/12/restaurante-pourquoi-pas-300x169.jpg" alt="restaurante pourquoi pas" width="300" height="169" />
								</div>
								<div class="col-md-9 col-xs-12">
									Este restaurante de nombre galo, tiene un estilo encantador y goza de una decoración bastante montañera. Puedes disfrutar de platos característicos de la cocina francesa. Además se pueden degustar platos de lo más variado.<br><br>
									<strong>Contacto</strong><br>
									Plaza Pradollano s/n<br>
									958 48 20 26
								</div>
							</div>

							<div class="col-xs-12 push-20 local-post">
								<h3>Restaurante Nevasol</h3>
								<div class="col-md-3 col-xs-12">
									<img class="alignnone size-medium wp-image-6220" src="http://www.apartamentosierranevada.net/actividades/wp-content/uploads/2017/12/nevasol-300x214.png" alt="nevasol" width="300" height="214" />
								</div>
								<div class="col-md-9 col-xs-12">
									Autoservicio y servicio de barbacoa los fines de semana como grandes atractivos . Cuenta con una gran terraza solarium.<br><br>
									<strong>Contacto</strong><br>
									Estación de Esquí de Sierra Nevada, Monachil<br>
									958 340 936
								</div>
							</div>

							<div class="col-xs-12 push-20 local-post">
								<h3>El Bistro</h3>
								<div class="col-md-3 col-xs-12">
									<img class="alignnone size-medium wp-image-6221" src="http://www.apartamentosierranevada.net/actividades/wp-content/uploads/2017/12/el-bistro-300x127.jpg" alt="el bistro" width="300" height="127" />
								</div>
								<div class="col-md-9 col-xs-12">
									Comida variada y donde los buenos ingredientes y un servicio adecuado son algunos de sus atractivos.<br><br>
									<strong>Contacto</strong><br>
									Edf. Constelacion, Plaza Pradollano, s/n<br>
									958 481 340
								</div>
							</div>

							<div class="col-xs-12 push-20 local-post">
								<h3>Il Nuovo Little Morgan</h3>
								<div class="col-md-3 col-xs-12">
									<img class="alignnone wp-image-6222" src="http://www.apartamentosierranevada.net/actividades/wp-content/uploads/2017/12/il-nuovo-300x200.jpg" alt="il nuovo" width="300" height="200" />
								</div>
								<div class="col-md-9 col-xs-12">
									Un restaurante que apuesta por la buena comida italiana, donde abarca, desde la mejor pasta y pizza hasta platos más elaborados.<br><br>
									<strong>Contacto</strong><br>
									Ed. Monachil, Plaza de andalucia<br>
									958 481 286
								</div>
							</div>

							<div class="col-xs-12 push-20 local-post">
								<h3>La Carihuela</h3>
								<div class="col-md-3 col-xs-12">
									<img class="alignnone size-medium wp-image-6224" src="http://www.apartamentosierranevada.net/actividades/wp-content/uploads/2017/12/la-carihuela-300x204.png" alt="la carihuela" width="300" height="204" />
								</div>
								<div class="col-md-9 col-xs-12">
									Este restaurante te ofrece una gran variedad de tapas y platos procedentes de la mejor cocina tradicional granadina.<br><br>
									<strong>Contacto</strong><br>
									Calle de la Virgen de las Nieves, 16<br>
									958 480 010
								</div>
							</div>
							
							<div class="col-xs-12 push-20 local-post">
								<h3>La Carihuela</h3>
								<div class="col-md-3 col-xs-12">
									<img class="alignnone size-medium wp-image-6224" src="http://www.apartamentosierranevada.net/actividades/wp-content/uploads/2017/12/la-carihuela-300x204.png" alt="la carihuela" width="300" height="204" />
								</div>
								<div class="col-md-9 col-xs-12">
									Este restaurante te ofrece una gran variedad de tapas y platos procedentes de la mejor cocina tradicional granadina.<br><br>
									<strong>Contacto</strong><br>
									Calle de la Virgen de las Nieves, 16<br>
									958 480 010
								</div>
							</div>
							
							
							<div class="col-xs-12 push-20">
								<h2>Pizzerias</h2>
								<img class="aligncenter wp-image-6230" src="http://www.apartamentosierranevada.net/actividades/wp-content/uploads/2017/12/pizza-1024x683.jpg" alt="pizza" width="641" height="428" />
								<p class="text-justify font-s18 font-w300" >
									En los restaurantes en Sierra Nevada hay sitio para la famosa pizza. Si hay un plato en todo el mundo conocido y consumido por antonomasia son las pizzas. Si quieres probar las mejores de la zona y además, en muchos casos, disfrutar de otros platos típicos de la gastronomía italiana como la pasta, aquí tienes una buena selección.
								</p>
							</div>

							<div class="col-xs-12 push-20 local-post">
								<h3>Tito Luigi</h3>
								<div class="col-md-3 col-xs-12">
									<img class="alignnone size-medium wp-image-6231" src="http://www.apartamentosierranevada.net/actividades/wp-content/uploads/2017/12/tito-luigi-300x224.jpg" alt="tito luigi" width="300" height="224" />
								</div>
								<div class="col-md-9 col-xs-12">
									Un clásico de la estación que tiene una relación calidad precio bastante buena y donde destacan sus buenos ingredientes. Ideal para cualquier momento del día que se quiera comer unas buenas pizzas a la manera italiana.<br><br>
									<strong>Contacto</strong><br>
									958 480 882
								</div>
							</div>

							<div class="col-xs-12 push-20 local-post">
								<h3>Ci vediamo</h3>
								<div class="col-md-3 col-xs-12">
									<img class="alignnone size-medium wp-image-6232" src="http://www.apartamentosierranevada.net/actividades/wp-content/uploads/2017/12/Ci-Vediamo-300x175.jpg" alt="Ci vediamo" width="300" height="175" />
								</div>
								<div class="col-md-9 col-xs-12">
									Una pizzería/restaurante italo-argentino donde se dan cita no solo la pizza, también la pasta y una mezcla interesante con la aportación de algunos platos y toques típicamente argentinos.<br><br>
									<strong>Contacto</strong><br>
									Plaza de Andalucía s/n<br>
									958 480 856
								</div>
							</div>
							
							<div class="col-xs-12 push-20 local-post">
								<h3>Pizzería Alpino</h3>
								
								<div class="col-md-12 col-xs-12">
									Buenas pizzas hechas a modo casero en un establecimiento que es un clásico ya en la zona.<br><br>
									<strong>Contacto</strong><br>
									C/Pradollano s/n<br>
									958 481 112
								</div>
							</div>

							<div class="col-xs-12 push-20 local-post">
								<h3>Pizzería Floren</h3>
								<div class="col-md-12 col-xs-12">
									Destaca por sus pizzas frescas. Una buena alternativa si quieres degustar las típicas pizzas italianas con un toque local. Además, antipasti, ensaladas, pasta tradicional o pasta fresca, pizzas, rissotti, canelones, etc. y platos de pescado o carne. Destacan también sus vinos italianos y españoles.<br><br>
									<strong>Contacto</strong><br>
									Edificio primavera 1<br>
									633 740 001
								</div>
							</div>

							
							<div class="col-xs-12 push-20">
								<h2>Comida rápida y en pista</h2>
								<img class="aligncenter wp-image-6236" src="http://www.apartamentosierranevada.net/actividades/wp-content/uploads/2017/12/restaurante-en-pista-1024x768.jpg" alt="restaurante en pista" width="690" height="517" />
								<p class="text-justify font-s18 font-w300" >
									Cuando vamos buscando restaurantes en Sierra Nevada, no siempre vamos con la misma disposición, en ocasiones hay ganas de comer a pie de pista sin tener que desplazarse de la misma. ¿Las vistas bien lo merecen no?
								</p>
							</div>
							
							
							<div class="col-xs-12 push-20 local-post">
								<h3>Campobase</h3>
								<div class="col-md-3 col-xs-12">
									<img class="alignnone size-medium wp-image-6237" src="http://www.apartamentosierranevada.net/actividades/wp-content/uploads/2017/12/campo-base-300x169.jpg" alt="campo base" width="300" height="169" />
								</div>
								<div class="col-md-9 col-xs-12">
									Un buen restaurante para comer variado o también merece la pena para tomar algo tranquilamente. Muy bien valorado por la clientela.<br><br>
									<strong>Contacto</strong><br>
									Edificio Mont Blac, Calle de la Virgen de las Nieves<br>
									958 480 714
								</div>
							</div>
							
							<div class="col-xs-12 push-20 local-post">
								<h3>Restaurante Borreguiles</h3>
								<div class="col-md-3 col-xs-12">
									<img class="alignnone size-medium wp-image-6238" src="http://www.apartamentosierranevada.net/actividades/wp-content/uploads/2017/12/restaurante-borreguiles-300x200.jpg" alt="restaurante borreguiles" width="300" height="200" />
								</div>
								<div class="col-md-9 col-xs-12">
									Un buen lugar donde comer bien o disfrutar de su servicio de autoservicio en Borreguiles.<br><br>
									<strong>Contacto</strong><br>
									Borreguiles s/n
								</div>
							</div>
							

							<div class="col-xs-12 push-20 local-post">
								<h3>Restaurante Genil</h3>
								<div class="col-md-3 col-xs-12">
									<img class="alignnone size-medium wp-image-6239" src="http://www.apartamentosierranevada.net/actividades/wp-content/uploads/2017/12/genil-300x200.jpg" alt="genil" width="300" height="200" />
								</div>
								<div class="col-md-9 col-xs-12">
									El sitio adecuado para comer o tomar unos buenos bocatas rápidos o las estupendas tapas típicas de la zona.<br><br>
									<strong>Contacto</strong><br>
									Está al lado a la Estación superior del Telesilla Genil.<br>
									902708090
								</div>
							</div>

							<div class="col-xs-12 push-20">
								<h2>Bares</h2>
								<img class="alignnone size-medium wp-image-6233" src="http://www.apartamentosierranevada.net/actividades/wp-content/uploads/2017/12/cartujano-300x225.jpg" alt="cartujano" width="300" height="225" />
								<p class="text-justify font-s18 font-w300" >
									Algo tan español como los bares no podíamos dejar de tocarlo a la hora de hablar de restaurantes en Sierra Nevada. Desde los típicos bares para tomar una buena cerveza con su tapa reglamentaria hasta sitios de ambiente más nocturno. Te dejamos algunos para que los descubras.
								</p>
							</div>

							<div class="col-xs-12 push-20 local-post">
								<h3>Taberna El Cartujano</h3>
								<div class="col-md-3 col-xs-12">
									<img class="alignnone size-medium wp-image-6239" src="http://www.apartamentosierranevada.net/actividades/wp-content/uploads/2017/12/cartujano-300x225.jpg" alt="genil" width="300" height="200" />
								</div>
								<div class="col-md-9 col-xs-12">
									Un bar de reducido tamaño, pero con mucho ambiente en la plaza céntrica de borreguiles, <em>sierra nevada</em>. Perfecto para tomarse unas cañas y tapas después de esquiar.<br><br>

								</div>
							</div>

							<div class="col-xs-12 push-20 local-post">
								<h3>Pub Hipódromo</h3>
								<div class="col-md-3 col-xs-12">
									<img class="size-medium wp-image-6234 alignnone" src="http://www.apartamentosierranevada.net/actividades/wp-content/uploads/2017/12/hipodromo-300x200.jpg" alt="hipodromo" width="300" height="200" />
								</div>
								<div class="col-md-9 col-xs-12">
									Aquí se pueden tomar buenas copas en el mejor ambiente de la zona. En calle Virgen de las Nieves. Monachil.
									
								</div>
							</div>
							
							<div class="col-xs-12 push-20 local-post">
								<h3>Pub Soho</h3>
								<div class="col-md-3 col-xs-12">
									<img class="alignnone wp-image-6235" src="http://www.apartamentosierranevada.net/actividades/wp-content/uploads/2017/12/soho-225x300.gif" alt="soho" width="176" height="235" />
								</div>
								<div class="col-md-9 col-xs-12">
									Un clásico de la zona de bares. Se sitúa también en la calle Virgen de las Nieves. Monachil.
								</div>
							</div>
							
							<div class="col-xs-12">
								<p class="text-justify font-s18">
									Esperamos que ahora sí tengas más claro cuáles son <strong>los mejores restaurantes en Sierra Nevada</strong> y también conozcas algunos bares donde tomar una copa tranquilo o con el mejor ambiente. Cuando uno viene a Sierra Nevada se da cuenta de la gran oferta en existente y es que estamos en una de las principales estaciones del país. Al haber una oferta tan grande de restaurantes en Sierra Nevada, no es siempre fácil tomar una decisión, por lo que esperamos que este artículo os sirva de una pequeña guía sobre lo que estáis buscando. En <a href="https://www.apartamentosierranevada.net" target="_blank" rel="noopener">apartamentosierranevada.net</a> estaremos encantados de resolver las dudas sobre restaurantes en Sierra Nevada que tengas al respecto o en cuanto al alojamiento en la zona.
								</p>
							</div>

							<div class="col-xs-12">
								<p class="text-justify font-s18">
									Para ver el listado completo de bares y restaurantes de Sierra Nevada haz clic <a href="https://www.apartamentosierranevada.net/actividades/sin-categoria/conoce-los-mejores-restaurantes-en-sierra-nevada.html?preview_id=6178&preview_nonce=3e7ad1b84e&post_format=standard&_thumbnail_id=6179&preview=true" target="_blank" rel="noopener">aquí</a>
								</p>
							</div>

						</div>
					</div><!-- .entry end -->
				</div>

			</div>
		</div>
	</section>
	
@endsection