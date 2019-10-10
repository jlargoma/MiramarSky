<?php 
	setlocale(LC_TIME, "ES");
	setlocale(LC_TIME, "es_ES");
	use \Carbon\Carbon;
?>
@extends('layouts.master_onlybody')

@section('title')Gracias por tu reserva @endsection

@section('content')
	<style>
		html {
			overflow: hidden;
		}
	</style>
<?php if (!$mobile->isMobile()): ?>
	<section class="section full-screen nobottommargin" style="background-color: #3f51b5;margin: 0; padding: 0;" >
		<div class="container container-mobile clearfix" style="width: 85%;">
            <div class="col-md-12" style="padding: 100px 15px 300px 15px;">
				<div class="row" style="margin-bottom: 15px;">
					<?php if (env('APP_APPLICATION') == "riad"): ?>
						<div class="col-md-2 col-md-offset-5">
							<img src="{{ asset('img/riad/logo_riad.png') }}" alt="" style="width: 100%">
						</div>
					<?php endif; ?>
				</div>
                <div class="row">
					<h2 class="text-center font-w300 ls1 " style="line-height: 1; font-size: 16px; color: white">
                    <span class="font-w800 " style="font-size: 32px;letter-spacing: -3px; color: white">
                        ¡Muchas gracias por confiar en nosotros!
                    </span><br>
						Te enviaremos un email con la confirmación de tu reserva y los pasos a seguir.<br><br>
						Un saludo
					</h2>
				</div>
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