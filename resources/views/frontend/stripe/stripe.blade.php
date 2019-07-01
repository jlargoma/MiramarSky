<?php 
	setlocale(LC_TIME, "ES");
	setlocale(LC_TIME, "es_ES");
	use \Carbon\Carbon;
?>
@extends('layouts.master_onlybody')

@section('title')Graicas por tu reserva @endsection

@section('content')


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
<style type="text/css" media="screen">
    .shadow{
        text-shadow: 1px 1px #000;
    }
    .stripe-button-el{
        background-image: linear-gradient(#28a0e5,#015e94);
        color: #FFF;
        width: 100%;
        padding: 30px 15px;
        font-size: 24px;

    }
    .stripe-button-el span{
        background: none;
        color: #FFF;
        height: auto!important;
        padding: 0;
        font-size: 24px;
        font-family: 'Evolutio',sans-serif;
        letter-spacing: -2px;
        line-height: inherit;
        text-shadow: none;
        -webkit-box-shadow: none; 
        box-shadow: none;
    }
    footer#footer{
    	margin-top: 0!important
    }
</style>

<?php if (!$mobile->isMobile()): ?>
	<section class="section full-screen nobottommargin" style="background-image: url({{ asset('/img/mountain.png')}});background-size: cover;background-position: 0; margin: 0" >
		<div class="container container-mobile clearfix" style="width: 85%;">
            <div class="col-md-12" style="padding: 100px 15px 300px 15px;">
                <h2 class="text-center font-w300 ls1 " style="line-height: 1; font-size: 42px; color: black">
                    <span class="font-w800 " style="font-size: 48px;letter-spacing: -3px; color: black">
                        ¡Muchas gracias por confiar en nosotros!
                    </span><br>
                    Te enviaremos un email con la confirmación de tu reserva y los pasos a seguir.<br><br>
                    Un saludo
                </h2>
            </div>
		</div>
	</section>
<?php else:?>
	<section class="section full-screen nobottommargin" style="background-color: white; padding-top: 0; margin-top: 35px;">
		<div class="container container-mobile clearfix" style="width: 85%;">
            <div class="col-md-12" style="padding: 100px 15px 300px 15px;">
                <h2 class="text-center font-w300 ls1 " style="line-height: 1; font-size: 26px; color: black">
                    <span class="font-w800 " style="font-size: 36px;letter-spacing: -3px; color: black">
                        ¡Muchas gracias por confiar en nosotros!
                    </span><br>
                    Te enviaremos un email con la confirmación de tu reserva y los pasos a seguir.<br><br>
                    Un saludo
                </h2>
            </div>
		</div>
	</section>
<?php endif ;?>

@endsection