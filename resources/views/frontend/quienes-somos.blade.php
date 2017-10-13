@extends('layouts.master_withoutslider')

@section('metadescription') Quienes Somos - apartamentosierranevada.net @endsection
@section('title')  Quienes Somos - apartamentosierranevada.net @endsection

@section('content')
<style type="text/css">
	#primary-menu ul li  a{
		color: #3F51B5!important;
	}
	#primary-menu ul li  a div{
		text-align: left!important;
	}
	#content p {
	    line-height: 1.2;
	}
	.fa-circle{
		font-size: 10px!important;
	}
	#contact-form input{
			color: black!important;
		}
		*::-webkit-input-placeholder {
	    /* Google Chrome y Safari */
	    color: rgba(0,0,0,0.85) !important;
		}
		*:-moz-placeholder {
		    /* Firefox anterior a 19 */
		    color: rgba(0,0,0,0.85) !important;
		}
		*::-moz-placeholder {
		    /* Firefox 19 y superior */
		    color: rgba(0,0,0,0.85) !important;
		}
		*:-ms-input-placeholder {
		    /* Internet Explorer 10 y superior */
		    color: rgba(0,0,0,0.85) !important;
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

	}

	</style>

<section id="content" style="margin-top: 15px">

	<div class="container container-mobile clearfix push-0">
		<div class="row">
			<h1 class="center psuh-20">Quienes Somos</h1>
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 center" >
			
			<p class="texto-aparamento " style="text-align: justify;color:black;font-size:16px;padding:0px 30px;" >
				
				Hola, como estás. <br><br>

				Somos una empresa que solamente gestiona el alquiler de apartamentos y estudios en el edificio MiramarSki, en Sierra Nevada. <br><br>

				<b>Nuestra especialidad son los apartamentos y estudios de calidad,</b> siempre tratando de daros  una atención personalizada a ti y a tu familia. <br><br>

				Los apartamentos y estudios  que gestionamos en www.apartamentosierranevada.net  han sido revisados y seleccionados por nosotros por su calidad, diseño, decoración, ubicación y equipamiento. <br><br>

				<b>El  objetivo por el que trabajamos es lograr que nuestros clientes puedan sentirse como en casa durante sus vacaciones en Sierra Nevada.</b><br><br>

				Si hemos conseguido que nos des tu confianza no vamos a defraudarte, sabemos lo importante que son tus días de ocio, aceptamos la responsabilidad. <br><br>

				Además de tu apartamento, ponemos a tu disposición una serie de servicios opcionales para intentar hacer tu estancia más agradable: <b>Descuentos en Forfait, cursillos de esquí, alquiler de material... puedes contratarlos en este link:</b> <a href="{{ url('/forfait') }}">Forfaits</a><br><br>

				Gracias por confiarnos tus vacaciones,  haremos todo lo posible para que pases unos días agradables. <br><br>

				Un saludo<br><br>

				Jorge Largo <br><br>

				<a href="{{ url('/') }}">www.apartamentosierranevada.net</a><br><br>




			</p>

		</div>
</section>
	
@endsection
@section('scripts')

@endsection