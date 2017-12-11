<?php 
	setlocale(LC_TIME, "ES");
	setlocale(LC_TIME, "es_ES");
	use \Carbon\Carbon;
?>
@extends('layouts.master_withoutslider')

@section('title')Pagos apartamentosierranevada.net @endsection

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
<?php 
	if ( count($payments) == 0) {
		$partialPay = ($book->total_price * 0.25);

	}elseif(count($payments) == 1){

		$partialPay = ($book->total_price * 0.25);

	}elseif(count($payments) > 1){

		$partialPay = ($book->total_price * 0.5);
	}

?>
<?php if (!$mobile->isMobile()): ?>
	<section class="section nobottommargin" style="background-image: url({{ asset('/img/mountain.png')}});background-size: cover;background-position: 0; min-height: 564px;" >
		<div class="container container-mobile clearfix" style="width: 85%;">
			<?php if ($payment == 1): ?>
				<div class="col-md-6 nobottommargin">
					
					<div class="row">
						<div class="col-xs-12">
							
							<?php if ( count($payments) == 0): ?>
								<h2 class="text-justify font-w300 ls1" style="line-height: 1; font-size: 20px;">
									Hola <b><?php echo $book->customer->name ?></b>,Para confirmar tu reserva tienes que abonar el 25% de el importe total de tu reserva  mediante nuestra pasarela de pago stripe.
								</h2>
								<p class="text-justify" style="font-size: 20px;font-family: miramar!important">
									Te dejamos un resumen de tu reserva:
								</p>
							<?php elseif(count($payments) == 1): ?>
								<h2 class="text-justify font-w300 ls1" style="line-height: 1; font-size: 20px;">
									Hola <b><?php echo $book->customer->name ?></b>,Aquí puedes abonar el siguiente 25% del importe total de tu reserva  mediante nuestra pasarela de pago stripe.
								</h2>
								<p class="text-justify" style="font-size: 20px;font-family: miramar!important">
									Te recordamos los datos de tu reserva:
								</p>
							<?php elseif(count($payments) > 1): ?>
								<h2 class="text-justify font-w300 ls1" style="line-height: 1; font-size: 20px;">
									Hola <b><?php echo $book->customer->name ?></b>,Aquí puedes abonar el último pago del 50% del importe total de tu reserva mediante nuestra pasarela de pago stripe.
								</h2>
								<p class="text-justify" style="font-size: 20px;font-family: miramar!important">
									Te recordamos los datos de tu reserva:
								</p>
							<?php endif ?>
						</div>
						<div class="col-md-12">
							<p class="font-w300 text-justify" style="font-size: 20px; font-family: miramar!important">
								<?php 
									$start = Carbon::createFromFormat('Y-m-d', $book->start);
									$finish = Carbon::createFromFormat('Y-m-d', $book->finish);
								?>

								Nombre: <b><?php echo $book->customer->name ?></b><br>
								Apartamento: <b><?php echo $book->room->sizeRooms->name ?> // <?php echo ($book->type_luxury == 1)? "Lujo" : "Estandar" ?></b><br>
								Nº Ocupantes: <b><?php echo $book->pax ?> Pers </b><br>
								Fechas: <b><?php echo $start->formatLocalized('%d %B %Y') ?> - <?php echo $finish->formatLocalized('%d %B %Y') ?> </b><br>
								Noches: <b><?php echo $book->nigths ?></b>  <br>
								Precio total: <b><?php echo number_format($book->total_price,2,',','.') ?> € </b><br>
							</p>
						</div>
							
					</div>
					
				</div>
				<div class="col-md-4 col-md-offset-2 col-xs-12">
					<div class="col-md-12 text-center push-20">
						<img class="img-responsive" src="{{asset('/img/stripe.png')}}" style="    width: 15%; float: right;">
					</div>
					<div class="col-md-12">
						
						<h2 class="text-center font-w300 ls1" style="line-height: 1; font-size: 24px;">
							<span class="font-w800" style="font-size: 32px; letter-spacing: -3px;">
								El importe que debes abonar para tu reserva es de:
							</span>
						</h2>
						<p class=" text-center font-w800" style="font-size: 116px;letter-spacing: -15px; line-height: 1;">
							<?php echo $partialPay ?> € <span>*</span>
						</p>
					</div>
					<form action="{{ url('/reservas/stripe/payment') }}" method="POST">
						<input type="hidden" name="price" value="<?php echo $partialPay *100 ?>">
						<input type="hidden" name="id_book" value="<?php echo $book->id ?>">
			  			<script
						    src="https://checkout.stripe.com/checkout.js" class="stripe-button"
						    data-key="<?php echo $stripe['publishable_key']; ?>"
						    data-amount="<?php echo $partialPay *100 ?>"
						    data-name="Apartamentosierranevada"
						    data-description="Pago parcial 25% reserva"
						    data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
						    data-locale="auto"
						    data-zip-code="true"
						    data-currency="eur">
					  	</script>
					</form>
					<div class="col-md-12">
						<p class="font-s12 text-center"><span class="text-danger">*</span> <i>Tienes un plazo de 5 horas para abonar este importe</i></p>
					</div>
						
				</div>
			<?php else: ?>
				<div class="col-md-12" style="padding: 100px 15px 300px 15px;">
					<h2 class="text-center font-w300 ls1 " style="line-height: 1; font-size: 42px; color: black">
						<span class="font-w800 " style="font-size: 48px;letter-spacing: -3px; color: black">
							¡Muchas gracias por confiar en nosotros!
						</span><br>
						Te enviaremos un email con la confirmación de tu reserva y los pasos a seguir.<br><br>
						Un saludo
					</h2>
				</div>
			<?php endif ?>
		</div>
	</section>
<?php else:?>
	<section class="section full-screen nobottommargin" style="background-color: white; padding-top: 0; margin-top: 35px;">
		<div class="container container-mobile clearfix" style="width: 85%;">
			<?php if ($payment == 1): ?>
				<div class="col-md-6 nobottommargin">
					
					<div class="row">
						<div class="col-xs-12">
							<h2 class="text-justify font-w300 push-10 ls1" style="line-height: 1; font-size: 18px;">
								Hola <b><?php echo $book->customer->name ?></b>, Para confirmar tu reserva tienes que abonar el 25% de el importe total de tu reserva  mediante nuestra pasarela de pago stripe
							</h2>
							<p class="text-justify push-0" style="font-size: 18px;font-family: miramar!important">Te dejamos un resumen de tu reserva:</p>
						</div>
						<div class="col-md-12">
							<p class="font-w300 text-justify" style="font-size: 18px; font-family: miramar!important">
								<?php 
									$start = Carbon::createFromFormat('Y-m-d', $book->start);
									$finish = Carbon::createFromFormat('Y-m-d', $book->finish);
								?>

								Nombre: <b><?php echo $book->customer->name ?></b><br>
								Apto: <b><?php echo $book->room->sizeRooms->name ?> // <?php echo ($book->type_luxury == 1)? "Lujo" : "Estandar" ?></b><br>
								Nº Pers: <b><?php echo $book->pax ?> Pers </b><br>
								Fechas: <b><?php echo $start->formatLocalized('%d %B') ?> - <?php echo $finish->formatLocalized('%d %B ') ?> </b><br>
								Noches: <b><?php echo $book->nigths ?></b>  <br>
								Precio total: <b><?php echo number_format($book->total_price,2,',','.') ?> € </b><br>
							</p>
						</div>
							
					</div>
					
				</div>
				<div class="col-md-4 col-md-offset-2 col-xs-12">
					
					<div class="col-md-12">
						
						<h2 class="text-center push-10 font-w300 ls1" style="line-height: 1; font-size: 22px;">
							<span class="font-w800" style="font-size: 22px; letter-spacing: -3px;">
								El importe que debes abonar para tu reserva es de:
							</span>
						</h2>
						<p class=" text-center font-w800 push-10" style="font-size: 72px;letter-spacing: -15px; line-height: 1;">
							<?php echo round($partialPay) ?> € <span>*</span>
						</p>
					</div>
					<form action="{{ url('/reservas/stripe/payment') }}" method="POST">
						<input type="hidden" name="price" value="<?php echo round($partialPay) ?>00">
						<input type="hidden" name="id_book" value="<?php echo $book->id ?>">
			  			<script
						    src="https://checkout.stripe.com/checkout.js" class="stripe-button"
						    data-key="<?php echo $stripe['publishable_key']; ?>"
						    data-amount="<?php echo round($partialPay) ?>00"
						    data-name="Apartamentosierranevada"
						    data-description="Pago parcial 25% reserva"
						    data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
						    data-locale="auto"
						    data-zip-code="true"
						    data-currency="eur">
					  	</script>
					</form>
					<div class="col-md-12">
						<p class="font-s12 text-center"><span class="text-danger">*</span> <i>Tienes un plazo de 5 horas para abonar este importe</i></p>
					</div>
					<div class="col-md-12 text-center push-20">
						<img class="img-responsive" src="{{asset('/img/stripe.png')}}" style="width: 50%; margin: 0 auto;">
					</div>
				</div>
			<?php else: ?>
				<div class="col-md-12" style="padding: 100px 15px 300px 15px;">
					<h2 class="text-center font-w300 ls1 " style="line-height: 1; font-size: 26px; color: black">
						<span class="font-w800 " style="font-size: 36px;letter-spacing: -3px; color: black">
							¡Muchas gracias por confiar en nosotros!
						</span><br>
						Te enviaremos un email con la confirmación de tu reserva y los pasos a seguir.<br><br>
						Un saludo
					</h2>
				</div>
			<?php endif ?>
		</div>
	</section>
<?php endif ;?>

@endsection