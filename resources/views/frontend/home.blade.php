@extends('layouts.master')

@section('content')

<section id="content">

    <div class="content-wrap notoppadding" style="padding-bottom: 0;">
       	<!-- METODO DESKTOP -->

       	<section class="page-section">

   			<div id="banner-offert" class="button button-full center tright footer-stick line-promo" style="padding: 0;background-color: #fff;margin-bottom: 0px!important;"  data-animate="bounceIn">
   				<div class="row" style="padding: 0 15px;">
   					<div class="col-xs-12 center  font-w300 text-center" style="padding: 20px 0">
   						<span class="font-w800">SOLICITA TU RESERVA</span> ¡COMIENZA TUS VACACIONES YA!
   						<div id="btn-hover-banner" class="button button-desc button-border button-rounded center">RESERVAR YA!</div>
   					</div>
   				</div>
   			</div>
       		<div id="content-book" class="container clearfix push-10" style="display: none;">
       			<div class="tabs advanced-real-estate-tabs clearfix">

       				<div class="tab-container">
       					<div class="container clearfix">
       						<div class="tab-content clearfix" id="tab-properties">
       							<form action="#" method="post" class="nobottommargin">
       								<div class="row">
       									<div class="col-md-2 col-sm-12 bottommargin-sm">
       										<label for="" style="display:block;">Type</label>
       										<input class="bt-switch" type="checkbox" checked data-on-text="Buy" data-off-text="Rent" data-on-color="themecolor" data-off-color="themecolor">
       									</div>
       									<div class="col-md-3 col-sm-6 col-xs-12 bottommargin-sm">
       										<label for="">Choose Locations</label>
       										<select class="selectpicker form-control" multiple data-live-search="true" data-size="6" style="width:100%;">
       											<optgroup label="Alaskan/Hawaiian Time Zone">
       												<option value="AK">Alaska</option>
       												<option value="HI">Hawaii</option>
       											</optgroup>
       											<optgroup label="Pacific Time Zone">
       												<option value="CA">California</option>
       												<option value="NV">Nevada</option>
       												<option value="OR">Oregon</option>
       												<option value="WA">Washington</option>
       											</optgroup>
       											<optgroup label="Mountain Time Zone">
       												<option value="AZ">Arizona</option>
       												<option value="CO">Colorado</option>
       												<option value="ID">Idaho</option>
       												<option value="MT">Montana</option>
       												<option value="NE">Nebraska</option>
       												<option value="NM">New Mexico</option>
       												<option value="ND">North Dakota</option>
       												<option value="UT">Utah</option>
       												<option value="WY">Wyoming</option>
       											</optgroup>
       											<optgroup label="Central Time Zone">
       												<option value="AL">Alabama</option>
       												<option value="AR">Arkansas</option>
       												<option value="IL">Illinois</option>
       												<option value="IA">Iowa</option>
       												<option value="KS">Kansas</option>
       												<option value="KY">Kentucky</option>
       												<option value="LA">Louisiana</option>
       												<option value="MN">Minnesota</option>
       												<option value="MS">Mississippi</option>
       												<option value="MO">Missouri</option>
       												<option value="OK">Oklahoma</option>
       												<option value="SD">South Dakota</option>
       												<option value="TX">Texas</option>
       												<option value="TN">Tennessee</option>
       												<option value="WI">Wisconsin</option>
       											</optgroup>
       											<optgroup label="Eastern Time Zone">
       												<option value="CT">Connecticut</option>
       												<option value="DE">Delaware</option>
       												<option value="FL">Florida</option>
       												<option value="GA">Georgia</option>
       												<option value="IN">Indiana</option>
       												<option value="ME">Maine</option>
       												<option value="MD">Maryland</option>
       												<option value="MA">Massachusetts</option>
       												<option value="MI">Michigan</option>
       												<option value="NH">New Hampshire</option>
       												<option value="NJ">New Jersey</option>
       												<option value="NY">New York</option>
       												<option value="NC">North Carolina</option>
       												<option value="OH">Ohio</option>
       												<option value="PA">Pennsylvania</option>
       												<option value="RI">Rhode Island</option>
       												<option value="SC">South Carolina</option>
       												<option value="VT">Vermont</option>
       												<option value="VA">Virginia</option>
       												<option value="WV">West Virginia</option>
       											</optgroup>
       										</select>
       									</div>
       									<div class="col-md-3 col-sm-6 col-xs-12 bottommargin-sm">
       										<label for="">Property Type</label>
       										<select class="selectpicker form-control" data-size="6" style="width:100%; line-height: 30px;">
       											<option value="Any">Any</option>
       											<optgroup label="Residential">
       												<option value="Apartment">Apartment</option>
       												<option value="Condo">Condo</option>
       												<option value="Villa">Villa</option>
       												<option value="Building">Building</option>
       											</optgroup>
       											<optgroup label="Commercial">
       												<option value="Shop">Shop</option>
       												<option value="Office">Office</option>
       												<option value="Warehouse">Warehouse</option>
       											</optgroup>
       										</select>
       									</div>
       									<div class="col-md-2 col-sm-6 col-xs-6 bottommargin-sm">
       										<label for="">Beds</label>
       										<select class="selectpicker form-control" multiple data-size="6" data-placeholder="Any" style="width:100%; line-height: 30px;">
       											<option value="1">1</option>
       											<option value="2">2</option>
       											<option value="3">3</option>
       											<option value="4">4</option>
       											<option value="5+">5+</option>
       										</select>
       									</div>
       									<div class="col-md-2 col-sm-6 col-xs-6 bottommargin-sm">
       										<label for="">Baths</label>
       										<select class="selectpicker form-control" multiple data-size="6" data-placeholder="Any" style="width:100%; line-height: 30px;">
       											<option value="1">1</option>
       											<option value="2">2</option>
       											<option value="3">3</option>
       											<option value="4">4</option>
       											<option value="5+">5+</option>
       										</select>
       									</div>
       									<div class="clear"></div>
       									<div class="col-md-4 col-sm-6 col-xs-12">
       										<label for="" style="margin-bottom: 20px !important;">Price Range</label>
       										<input class="price-range-slider" />
       									</div>
       									<div class="clear visible-xs bottommargin-sm"></div>
       									<div class="col-md-4 col-md-offset-1 col-sm-6 col-xs-12">
       										<label for="" style="margin-bottom: 20px !important;">Property Area</label>
       										<input class="area-range-slider" />
       									</div>
       									<div class="col-md-offset-1 col-md-2 col-sm-12 clearfix">
       										<button class="button button-3d button-rounded btn-block nomargin" style="margin-top: 35px !important;">Search</button>
       									</div>
       								</div>
       							</form>
       						</div>
       					</div>
       				</div>

       			</div>
       		</div>
       		<div class="container container-mobile clearfix push-20">
       			<?php if (!$mobile->isMobile()): ?>
       			<div class="col-md-12 col-xs-12 push-20 hidden-xs hidden-sm">
       				<a href="{{ url('/contacto') }}">
       					<img class="img-responsive" alt="plane en forma 10 semanas" src="{{ asset('/assets/images/gym/EVOLUTIO-BANNER-PLAN-EN-FORMA.jpg') }}"/>
       				</a>
       			</div>
       			<?php else: ?>
       			<div class="col-xs-12 push-20 hidden-lg hidden-md">
       				<a href="{{ url('/contacto') }}">
       					<img class="img-responsive" alt="plane en forma 10 semanas" src="{{ asset('/assets/images/gym/EVOLUTIO-BANNER-PLAN-EN-FORMA-mobile.png') }}"/>
       				</a>
       			</div>
       			<?php endif; ?>
       		</div>
   			<div class="row push-20">
   				<h2 class="text-center black font-w300">
   					NUESTROS <span class="font-w800 green ">APARTAMENTOS</span>
   				</h2>
   				<div class="col-md-12 col-xs-12">

   					<div class="col-xs-12 col-md-3 push-mobile-20">
   						<div class="col-xs-12 not-padding  container-image-box">
   							<div class="col-xs-12 not-padding push-0">
   								<a href="{{url('/entrenamiento-funcional-villaviciosa-de-odon')}}">
   									<img class="img-responsive imga" src="{{ asset('/assets/images/gym/personal-trainer-villaviciosa-de-odon.jpg')}}" alt="personal trainer villaviciosa"/>
   								</a>
   							</div>
   							<div class="col-xs-12 not-padding text-right overlay-text">
   								<h2 class="font-w600 center push-10 text-center text font-s24" >
   									<a class="white text-white" href="{{url('/entrenamiento-funcional-villaviciosa-de-odon')}}">ENTRENAMIENTO FUNCIONAL</a>
   								</h2>
   							</div>
   						</div>
   					</div>

   					<div class="col-xs-12 col-md-3 push-mobile-20">
   						<div class="col-xs-12 not-padding  container-image-box">
   							<div class="col-xs-12 not-padding push-0">
   								<a href="{{url('/entrenamiento-funcional-villaviciosa-de-odon')}}">
   									<img class="img-responsive" src="{{ asset('/assets/images/gym/entrenador-personal.jpg')}}"  alt="entrenador personal villaviciosa"/>
   								</a>
   							</div>
   							<div class="col-xs-12 not-padding text-right overlay-text">
   								<h2 class="font-w600 center push-10 text-center text font-s24" >
   									<a class="white text-white" href="{{url('/entrenador-personal-villaviciosa-de-odon')}}">PERSONAL TRAINER</a>
   								</h2>
   							</div>
   						</div>
   					</div>

   					<div class="col-xs-12 col-md-3 push-mobile-20">
   						<div class="col-xs-12 not-padding  container-image-box">
   							<div class="col-xs-12 not-padding push-0">
   								<a href="{{url('/hipopresivos-villaviciosa-de-odon')}}">
   									<img class="img-responsive imga" src="{{ asset('/assets/images/gym/gimansia-hipopresivos-villaviosa-de-odon-evolutio255x210.jpg')}}"  alt="hipopresivos villaviciosa"/>
   								</a>
   							</div>
   							<div class="col-xs-12 not-padding text-right overlay-text">
   								<h2 class="font-w600 center push-10 text-center text font-s24" >
   									<a class="white text-white" href="{{url('/hipopresivos-villaviciosa-de-odon')}}">HIPOPRESIVOS</a>
   								</h2>
   							</div>
   						</div>
   					</div>

   					<div class="col-xs-12 col-md-3 push-mobile-20">
   						<div class="col-xs-12 not-padding  container-image-box">
   							<div class="col-xs-12 not-padding push-0">
   								<a href="{{url('/cardio-fight-villaviciosa-de-odon')}}">
   									<img class="img-responsive" src="{{ asset('/assets/images/gym/cardio-boxing-villaviciosa-de-odon-boxeo-chicas-2.jpg')}}"  alt="cardio boxing villaviciosa"/>
   								</a>
   							</div>
   							<div class="col-xs-12 not-padding text-right overlay-text">
   								<h2 class="font-w600 center push-10 text-center text font-s24" >
   									<a class="white text-white" href="{{url('/cardio-fight-villaviciosa-de-odon')}}">CARDIO BOXING</a>
   								</h2>
   							</div>
   						</div>
   					</div>
   					
   				</div>
   			</div>
       			

       		<div class="row clearfix common-height">

       			<div class="col-md-6 center col-padding hidden-sm hidden-xs" style="background: url({{ asset('/assets/images/gym/entrenamiento-funcional-villaviciosa.jpg') }}) center center / cover no-repeat; ">
       				<div>&nbsp;</div>
       			</div>

       			<div class="col-md-6 center col-padding" style="background-color: rgb(255, 255, 255); height: 674px;">
       				<div>
       					<div class="heading-block nobottomborder">
       						<h3>¿QUE HACEMOS?</h3>
       					</div>
       					<p class="lead  text-justify black">
       						Porque todos tenemos ciertas necesidades especiales a la hora de hacer ejercicio, os presentamos nuestra propuesta con la que tratamos de cubrir tus necesidades de individualización en un formato en el que se podrá recibir una atención personalizada gracias a estar en un grupo reducido de personas con unas demandas similares y a nuestros entrenadores personales que especificarán el trabajo de cada miembro.<br><br>
       						<span class="green font-w600">TE AYUDAREMOS A RECUPERAR TU BIENESTAR</span>

       					</p>
       					<h2 class="green font-w600 text-left nobottommargin">Evolutio</h2>
       					<h4 class="text-left">Tu centro de entrenamiento en Villaviciosa de Odón</h4>
       					<p class="lead  text-justify black">
       						Nuestro método deportivo es un sistema ideado por expertos que ofrece grandes beneficios y excelentes resultados en nuestro centro de entrenamiento en Villaviciosa de Odón, muy cerca de Móstoles. Con él cada día es diferente, mejoras tu fuerza, resistencia, velocidad, flexibilidad... todo lo que necesita un deportista.

       						Nuestro objetivo como entrenadores personales, es encontrar un equilibrio saludable entre la parte física, mental y emocional, mejorando los cuatro pilares fundamentales: Nutrición, Actividad Física, Mentalidad y Descanso. Por eso, en nuestro centro de Villaviciosa de Odón, contamos con nutricionista deportivo, fisioterapeuta y un personal trainer que te dará atención personalizada.

       						Todos los entrenamientos son siempre guiados por un especialista, uno de nuestros entrenadores personales. 

       						Tenemos clases grupales de pilates, cardio boxing, etc. y sesiones individuales, readaptadores, dietistas, coaches, fisioterapeutas, activadores musculares… Trabajamos juntos por y para tu salud.

       					</p>
       				</div>
       			</div>

       		</div>
       	</section>

    </div>
    
</section>
@endsection