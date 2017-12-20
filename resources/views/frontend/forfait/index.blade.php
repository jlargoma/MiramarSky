@extends('layouts.master_withoutslider')
@section('css')
	<link rel="stylesheet" href="/frontend/demos/travel/css/datepicker.css" type="text/css" />

	<link rel="stylesheet" href="/frontend/css/components/timepicker.css" type="text/css" />
	<link rel="stylesheet" href="/frontend/css/components/daterangepicker.css" type="text/css" />
	<style type="text/css">
		#top-bar, #header{
			display: none;
		}
		#primary-menu ul li  a{
			color: #3F51B5!important;
		}
		#primary-menu ul li  a div{
			text-align: left!important;
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
			.daterangepicker {
			     left: 12%!important;
			     top: 3%!important; 
			 }
		}
	</style>
@endsection

@section('title')Forfaits - Apartamentos de lujo en Sierra Nevada a pie de pista @endsection

@section('content')
<?php if (!$mobile->isMobile()): ?>
<section class="section page" style="min-height: 420px; padding-top: 0; margin-top: 0">
	<div class="slider-parallax-inner" style="background-color: white;">
		<div class="row text-center push-20" style="background-image: url({{ asset('/img/miramarski/supermercado.jpg')}}); background-size: cover; background-position: 50% 35%; padding: 40px 0 0;">
			<div class="heading-block center text-white">
				<h1 style="color:white; text-shadow: 1px 1px #000">SOLICITUD DE FORFAITS</h1>
				<span style="color:white; text-shadow: 1px 1px #000">Te los llevamos a casa, olvidate de colas y esperas</span>
			</div>
		</div>
		<div class="col-md-6">

			<div class="row" >
				
				<div class="col-md-12">
					<h3 class="text-center">
						DATOS DE TU ESTANCIA
					</h3>
					
					<div class="form-group col-sm-12 col-xs-12 col-md-6 col-lg-6">
					    <label for="email">*Nombre</label>
					    <input type="text" class="sm-form-control"  name="name" id="name" placeholder="Nombre..." required="">
					</div>
					<div class="form-group col-sm-12 col-xs-12 col-md-6 col-lg-6">
					    <label for="email">*Email</label>
					    <input type="email" class="sm-form-control"  name="email" id="email" placeholder="Email..." required="">
					</div>
					<div class="form-group col-sm-12 col-xs-12 col-md-6 col-lg-6">
					    <label for="email">*Teléfono</label>
					    <input type="text" class="sm-form-control"  name="phone" id="email" placeholder="Teléfono..." maxlength="9" required="">
					</div>
					<div class="form-group col-sm-12 col-xs-12 col-md-6">
					    <label for="date" style="display: inherit!important;">*Fecha Entrada</label>
					    <input style="cursor:pointer; max-width: 100%;" type="text" value="<?php echo date('d-m-Y') ?>" class="sm-form-control tleft date" placeholder="DD-MM-YYYY">
					</div>
					
					<div class="col-xs-12">
						<?php foreach ($products['fortfaits'] as $key => $fortfait): ?>
							<div class="col-md-2 col-xs-6 left fortfait">
								
							</div>
						<?php endforeach ?>
					</div>

	                <div class="form-group col-sm-10">
	                	
	                	<!-- Juvenil -->
	                        <div id="Forfait0" class="desc" style="display: none;border-left: solid;border-right: solid">
	                            <span><h2 align="center">Reserva Forfait Juvenil</h2></span>
								
								Ahora, al alquilar cualquier tu equipo puedes reservar tu Forfait Express, para que lo tengas preparado cuando entres en tu apartamento .<br /><br />

								El precio varía según la temporada Baja/Alta/Primavera o en Promoción, por lo que tan solo tienes que hacer la reserva y ya efectuaras el pago una vez que se pongan en contacto contigo .<br /><br />

								<strong>NOTA: Para reservar tu Forfait, es obligatorio enviar este formulario con  3-4 dias de antelación, de lo contrario, no se tramitará el pedido.</strong>
								<br /><br />
								<div class="form-group col-sm-4">
	                                <span>*Cantidad</span>
	                                    <div class="input-group">
	                                    	<span class="input-group-btn">
	                                    		<button class="btn btn-default rest-clients" type="button" >-</button>
	                                    	</span> 
	                                        <input type="text" name="forfait-Juvenil-cant" class="form-control text-center count-clients" id='forfait-Juvenil-cant' readonly="readonly" required>
	                                        <span class="input-group-btn">
	                                        	<button class="btn btn-default add-client" type="button">+</button>
	                                        </span>

	                                    </div>

	                        	</div>
	                        	<div class="form-group col-sm-5">
	                                <span>*Dias</span>
	                                    <div class="input-group" >
	                                    	<select type="text" class="form-control" id="JuvenilDias">
	                                    		<option>Elige una opcion</option>
	                                    		<option value="2">2 Dias</option>
	                                    		<option value="3">3 Dias</option>
	                                    		<option value="4">4 Dias</option>
	                                    		<option value="5">5 Dias</option>
	                                    		<option value="6">6 Dias</option>
	                                    		<option value="7">7 Dias</option>
	                                    	</select>

	                                    </div>

	                        	</div>
	                        	<div class="form-group col-sm-3">
	                                <span>*Carrito</span>
	                        			<button name="boton" id="botonjuv" class="form-control" type="button">Solicitar</button>
	                        	</div>
	                        </div>
	                    
	                    <!-- Junior -->
	                        <div id="Forfait1" class="desc" style="display: none;border-left: solid;border-right: solid">
	                            <span><h2 align="center">Reserva Forfait Junior / Discapacitado Adulto Senior</h2></span>

								Ahora, al alquilar cualquier tu equipo puedes reservar tu Forfait Express, para que lo tengas preparado cuando entres en tu apartamento .<br /><br />

								El precio varía según la temporada Baja/Alta/Primavera o en Promoción, por lo que tan solo tienes que hacer la reserva y ya efectuaras el pago una vez que se pongan en contacto contigo .<br /><br />

								<strong>NOTA: Para reservar tu Forfait, es obligatorio enviar este formulario con  3-4 dias de antelación, de lo contrario, no se tramitará el pedido.</strong>
								<br /><br />
								<div class="form-group col-sm-4">
	                                <span>*Cantidad</span>
	                                    <div class="input-group">
	                                    	<span class="input-group-btn">
	                                    		<button class="btn btn-default rest-clients" type="button" >-</button>
	                                    	</span> 
	                                        <input type="text" name="forfait-Junior-cant" class="form-control text-center count-clients" id='forfait-Junior-cant' readonly="readonly" required>
	                                        <span class="input-group-btn">
	                                        	<button class="btn btn-default add-client" type="button">+</button>
	                                        </span>

	                                    </div>

	                        	</div>
	                        	<div class="form-group col-sm-5">
	                                <span>*Dias</span>
	                                    <div class="input-group" >
	                                    	<select type="text" class="form-control" id="JuniorDias">
	                                    		<option>Elige una opcion</option>
	                                    		<option value="2">2 Dias</option>
	                                    		<option value="3">3 Dias</option>
	                                    		<option value="4">4 Dias</option>
	                                    		<option value="5">5 Dias</option>
	                                    		<option value="6">6 Dias</option>
	                                    		<option value="7">7 Dias</option>
	                                    	</select>

	                                    </div>

	                        	</div>
	                        	<div class="form-group col-sm-3">
	                                <span>*Carrito</span>
	                        			<button name="boton" id="botonjun" class="form-control" type="button">Solicitar</button>
	                        	</div>
	                        </div>
	                    
	                    <!-- Adulto -->
	                        <div id="Forfait2" class="desc" style="display: none;border-left: solid;border-right: solid">
	                            <span><h2 align="center">Reserva Forfait Adulto</h2></span>

								Ahora, al alquilar cualquier tu equipo puedes reservar tu Forfait Express, para que lo tengas preparado cuando entres en tu apartamento .<br /><br />

								El precio varía según la temporada Baja/Alta/Primavera o en Promoción, por lo que tan solo tienes que hacer la reserva y ya efectuaras el pago una vez que se pongan en contacto contigo .<br /><br />

								<strong>NOTA: Para reservar tu Forfait, es obligatorio enviar este formulario con  3-4 dias de antelación, de lo contrario, no se tramitará el pedido.</strong>
								<br /><br />
								<div class="form-group col-sm-4">
	                                <span>*Cantidad</span>
	                                    <div class="input-group">
	                                    	<span class="input-group-btn">
	                                    		<button class="btn btn-default rest-clients" type="button" >-</button>
	                                    	</span> 
	                                        <input type="text" name="forfait-Adultos-cant" class="form-control text-center count-clients" id='forfait-Adultos-cant' readonly="readonly" required>
	                                        <span class="input-group-btn">
	                                        	<button class="btn btn-default add-client" type="button">+</button>
	                                        </span>

	                                    </div>

	                        	</div>
	                        	<div class="form-group col-sm-5">
	                                <span>*Dias</span>
	                                    <div class="input-group" >
	                                    	<select type="text" class="form-control" id="AdultosDias">
	                                    		<option>Elige una opcion</option>
	                                    		<option value="2">2 Dias</option>
	                                    		<option value="3">3 Dias</option>
	                                    		<option value="4">4 Dias</option>
	                                    		<option value="5">5 Dias</option>
	                                    		<option value="6">6 Dias</option>
	                                    		<option value="7">7 Dias</option>
	                                    	</select>

	                                    </div>

	                        	</div>
	                        	<div class="form-group col-sm-3">
	                                <span>*Carrito</span>
	                        			<button name="boton" id="botonadult" class="form-control" type="button">Solicitar</button>
	                        	</div>
	                        </div>
	                    
	                    <!-- Senior -->
	                        <div id="Forfait3" class="desc" style="display: none;border-left: solid;border-right: solid">
	                            <span><h2 align="center">Reserva Forfait Senior</h2></span>

								Ahora, al alquilar cualquier tu equipo puedes reservar tu Forfait Express, para que lo tengas preparado cuando entres en tu apartamento .<br /><br />

								El precio varía según la temporada Baja/Alta/Primavera o en Promoción, por lo que tan solo tienes que hacer la reserva y ya efectuaras el pago una vez que se pongan en contacto contigo .<br /><br />

								<strong>NOTA: Para reservar tu Forfait, es obligatorio enviar este formulario con  3-4 dias de antelación, de lo contrario, no se tramitará el pedido.</strong>
								<br /><br />
								<div class="form-group col-sm-4">
	                                <span>*Cantidad</span>
	                                    <div class="input-group">
	                                    	<span class="input-group-btn">
	                                    		<button class="btn btn-default rest-clients" type="button" >-</button>
	                                    	</span> 
	                                        <input type="text" name="forfait-Senior-cant" class="form-control text-center count-clients" id='forfait-Senior-cant' readonly="readonly" required>
	                                        <span class="input-group-btn">
	                                        	<button class="btn btn-default add-client" type="button">+</button>
	                                        </span>

	                                    </div>

	                        	</div>
	                        	<div class="form-group col-sm-5">
	                                <span>*Dias</span>
	                                    <div class="input-group" >
	                                    	<select type="text" class="form-control" id="SeniorDias">
	                                    		<option>Elige una opcion</option>
	                                    		<option value="2">2 Dias</option>
	                                    		<option value="3">3 Dias</option>
	                                    		<option value="4">4 Dias</option>
	                                    		<option value="5">5 Dias</option>
	                                    		<option value="6">6 Dias</option>
	                                    		<option value="7">7 Dias</option>
	                                    	</select>

	                                    </div>

	                        	</div>
	                        	<div class="form-group col-sm-3">
	                                <span>*Carrito</span>
	                        			<button name="boton" id="botonsenior" class="form-control" type="button">Solicitar</button>
	                        	</div>
	                        </div>
	                    
	                    <!-- Juvenil Familiar -->
	                        <div id="Forfait4" class="desc" style="display: none;border-left: solid;border-right: solid">
	                            <span><h2 align="center">Reserva Forfait Junior Formula Familiar</h2></span>

								Ahora, al alquilar cualquier tu equipo puedes reservar tu Forfait Express, para que lo tengas preparado cuando entres en tu apartamento .<br /><br />

								El precio varía según la temporada Baja/Alta/Primavera o en Promoción, por lo que tan solo tienes que hacer la reserva y ya efectuaras el pago una vez que se pongan en contacto contigo .<br /><br />

								<strong>NOTA: Para reservar tu Forfait, es obligatorio enviar este formulario con  3-4 dias de antelación, de lo contrario, no se tramitará el pedido.</strong>
								<br /><br />
	                        	<div class="form-group col-sm-4">
	                                <span>*Cantidad</span>
	                                    <div class="input-group">
	                                    	<span class="input-group-btn">
	                                    		<button class="btn btn-default rest-clients" type="button" >-</button>
	                                    	</span> 
	                                        <input type="text" name="forfait-juvenil-familiar-cant" class="form-control text-center count-clients" id='forfait-juvenil-familiar-cant' readonly="readonly" required>
	                                        <span class="input-group-btn">
	                                        	<button class="btn btn-default add-client" type="button">+</button>
	                                        </span>

	                                    </div>

	                        	</div>
	                        	<div class="form-group col-sm-5">
	                                <span>*Dias</span>
	                                    <div class="input-group" >
	                                    	<select type="text" class="form-control" id="juvfaDias">
	                                    		<option>Elige una opcion</option>
	                                    		<option value="2">2 Dias</option>
	                                    		<option value="3">3 Dias</option>
	                                    		<option value="4">4 Dias</option>
	                                    		<option value="5">5 Dias</option>
	                                    		<option value="6">6 Dias</option>
	                                    		<option value="7">7 Dias</option>
	                                    	</select>

	                                    </div>

	                        	</div>
	                        	<div class="form-group col-sm-3">
	                                <span>*Carrito</span>
	                        			<button name="boton" id="botonjuvfam" class="form-control" type="button">Solicitar</button>
	                        	</div>
	                        </div>
	                        
	                    <!-- Junio Familiar -->
	                        <div id="Forfait5" class="desc" style="display: none;border-left: solid;border-right: solid">
	                            <span><h2 align="center">Reserva Forfait Juvenil Formula Familiar</h2></span>

	                            Ahora, al alquilar cualquier tu equipo puedes reservar tu Forfait Express, para que lo tengas preparado cuando entres en tu apartamento .<br /><br />

								El precio varía según la temporada Baja/Alta/Primavera o en Promoción, por lo que tan solo tienes que hacer la reserva y ya efectuaras el pago una vez que se pongan en contacto contigo .<br /><br />

								<strong>NOTA: Para reservar tu Forfait, es obligatorio enviar este formulario con  3-4 dias de antelación, de lo contrario, no se tramitará el pedido.</strong>
	                            <br /><br />
	                        	<div class="form-group col-sm-4">
	                                <span>*Cantidad</span>
	                                    <div class="input-group">
	                                    	<span class="input-group-btn">
	                                    		<button class="btn btn-default rest-clients" type="button" >-</button>
	                                    	</span> 
	                                        <input type="text" name="forfait-junior-familiar-cant" class="form-control text-center count-clients" id='forfait-junior-familiar-cant' readonly="readonly" required>
	                                        <span class="input-group-btn">
	                                        	<button class="btn btn-default add-client" type="button">+</button>
	                                        </span>

	                                    </div>

	                        	</div>
	                        	<div class="form-group col-sm-5">
	                                <span>*Dias</span>
	                                    <div class="input-group" >
	                                    	<select type="text" class="form-control" id="junfaDias">
	                                    		<option>Elige una opcion</option>
	                                    		<option value="2">2 Dias</option>
	                                    		<option value="3">3 Dias</option>
	                                    		<option value="4">4 Dias</option>
	                                    		<option value="5">5 Dias</option>
	                                    		<option value="6">6 Dias</option>
	                                    		<option value="7">7 Dias</option>
	                                    	</select>

	                                    </div>

	                        	</div>
	                        	<div class="form-group col-sm-3">
	                                <span>*Carrito</span>
	                        			<button name="boton" id="botonjunfam" class="form-control" type="button">Solicitar</button>
	                        	</div>
	                        </div>
	                </div>
				</div>
			</div>

		</div>
	</div>
</section>
<?php else: ?>

	<section class="section page" style="min-height: 420px; padding-top: 0;margin-top: 25px;">
		<div class="slider-parallax-inner">
			<div class="row text-center push-20" style="background-image: url({{ asset('/img/miramarski/supermercado.jpg')}}); background-size: cover; background-position: 50% 35%; padding: 40px 0 0;">
				<div class="col-xs-12 heading-block center text-white">
					<h1 style="color:white; text-shadow: 1px 1px #000; line-height: 1; letter-spacing: -2px;">Supermercado online</h1>
					<span style="color:white; text-shadow: 1px 1px #000">Te llevamos la compra al edificio el dia de tu entrada</span>
				</div>
			</div>
			<div class="container-mobile">

				<div class="row">
					<div class="col-md-12">
						<h4 class="text-center" style="line-height: 1; letter-spacing: -1px;">
							Introduce los datos de tu reserva para entrar.
						</h4>
						<div class="form-group col-sm-12 col-xs-8 col-md-6 col-lg-6 push-10">
						    <label for="email">*Email</label>
						    <input type="email" class="sm-form-control"  name="email" id="email" placeholder="Email..." maxlength="40" required="">
						</div>
						<div class="form-group col-sm-12 col-xs-4 push-10" style="padding-left: 0">
						    <label for="date" style="display: inherit!important;">*Entrada</label>
					    	<input style="cursor:pointer;" type="text" value="<?php echo date('d-m-Y') ?>" class="sm-form-control tleft date" placeholder="DD-MM-YYYY">
						</div>
						<div class="form-group text-center col-sm-12 col-xs-12 col-md-3" style="padding: 20px 0;">
							<button class="button button-3d button-large button-rounded button-green font-w300" id="seachBook">
								<i class="fa fa-sign-in"></i> ENTRAR
							</button>
						</div>
					</div>
				</div>

			</div>
		</div>
		<div class="slider-parallax-inner container" id="resultBooksSearch" style="display: none;">
			
		</div>
	</section>
<?php endif; ?>

@endsection

@section('scripts')
<script type="text/javascript" src="/frontend/js/components/moment.js"></script>
<script type="text/javascript" src="/frontend/demos/travel/js/datepicker.js"></script>
<script type="text/javascript" src="/frontend/js/components/timepicker.js"></script>
<script type="text/javascript" src="/frontend/js/components/daterangepicker.js"></script>

<script type="text/javascript">
	$(document).ready(function() {
		$('.date').datepicker({
			autoclose: true,
			format: "dd-mm-yyyy",
		});
	});
</script>
@endsection