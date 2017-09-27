@extends('layouts.master_withoutslider')

@section('metadescription') Aviso Legal - apartamentosierranevada.net @endsection
@section('title')  Aviso Legal - apartamentosierranevada.net @endsection

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
				<h1 class="center psuh-20">Términos y condiciones generales de Alquiler</h2>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 center" >
				<p class="texto-aparamento " style="text-align: justify;color:black;font-size:16px;padding:0px 30px" >

					La página web <a href="{{ url('/') }}">www.apartamentosierranevada.net</a>  es propiedad de ISDE S.L., con CIF: B-B92549880 y domicilio social en Avda Quitapesares nº20 28670 Villaviciosa de Odón Madrid. <br><br>

					Para cualquier aclaración nos pueden contactar a través de la siguiente dirección de correo electrónico: <a href="mailto:reservas@apartamentosierranevada.net">reservas@apartamentosierranevada.net</a> <br><br>

					Todo el contenido gráfico y la información de la página <a href="{{ url('/') }}">www.apartamentosierranevada.net</a>  , así como el diseño gráfico, las imágenes, las bases de datos y los programas son propiedad exclusiva de ISDE S.L., la cual se reserva todos los derechos de explotación. <br><br>

					ISDE S.L. recogerá los datos de carácter personal de manera adecuada, pertinente según las finalidades, utilidades, servicios y/o prestaciones incluidas en su sede web, de forma, además, determinada, explícita y legítima, por lo que en ningún caso se emplearán medios fraudulentos, desleales o, naturalmente, ilícitos o que en alguna forma pongan en peligro los legítimos derechos de los visitantes.

				</p>
			</div>
	</section>
	
@endsection
@section('scripts')

@endsection