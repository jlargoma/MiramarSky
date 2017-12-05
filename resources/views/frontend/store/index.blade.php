@extends('layouts.master_withoutslider')
@section('css')
	<link rel="stylesheet" href="/frontend/demos/travel/css/datepicker.css" type="text/css" />

	<link rel="stylesheet" href="/frontend/css/components/timepicker.css" type="text/css" />
	<link rel="stylesheet" href="/frontend/css/components/daterangepicker.css" type="text/css" />
	<style type="text/css">
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

@section('title')Compra en casa - Apartamentos de lujo en Sierra Nevada a pie de pista @endsection

@section('content')
<?php if (!$mobile->isMobile()): ?>
<section class="section page" style="min-height: 420px; padding-top: 0">
	<div class="slider-parallax-inner" style="background-color: white;">
		<div class="row text-center push-20" style="background-image: url({{ asset('/img/miramarski/supermercado.jpg')}}); background-size: cover; background-position: 50% 35%; padding: 40px 0 0;">
			<div class="heading-block center text-white">
				<h1 style="color:white; text-shadow: 1px 1px #000">Cesta de la compra</h1>
				<span style="color:white; text-shadow: 1px 1px #000">Compra ONLINE y te lo llevamos al edificio el dia de tu entrada</span>
			</div>
		</div>
		<div class="container">

			<div class="row" >
				
				<div class="col-md-12">
					<h3 class="text-center">
						Introduce los datos de tu reserva para entrar.
					</h3>
					<div class="form-group col-sm-12 col-xs-12 col-md-6 col-lg-6">
					    <label for="email">*Email</label>
					    <input type="email" class="sm-form-control"  name="email" id="email" placeholder="Email..." maxlength="40" required="">
					</div>
					<div class="form-group col-sm-12 col-xs-12 col-md-3">
					    <label for="date" style="display: inherit!important;">*Fecha Entrada</label>
					    <input type="text" value="<?php echo date('d-m-Y') ?>" class="sm-form-control tleft date" placeholder="DD-MM-YYYY">
					</div>
					<div class="form-group col-sm-12 col-xs-12 col-md-3" style="padding: 20px 0;">
						<button class="button button-3d button-large button-rounded button-green font-w300" id="seachBook">
							<i class="fa fa-sign-in"></i> Entrar
						</button>
					</div>
				</div>
			</div>

		</div>
	</div>
	<div class="slider-parallax-inner container" id="resultBooksSearch" style="background-color: white;display: none;">
		
	</div>
</section>
<?php else: ?>

	<section class="section page" style="min-height: 420px">
		<div class="slider-parallax-inner">
			<div class="container-mobile">

				<div class="row">
					<div class="col-xs-12 heading-block center">
						<h1>Cesta de la compra</h1>
						<span>Compra ONLINE y te lo llevamos al edificio el dia de tu entrada</span>
					</div>
					<div class="col-md-12">

						<div class="form-group col-sm-12 col-xs-12 col-md-6 col-lg-6">
						    <label for="email">*Email</label>
						    <input type="email" class="sm-form-control"  name="email" id="email" placeholder="Email..." maxlength="40" required="">
						</div>
						<div class="form-group col-sm-12 col-xs-12 col-md-3">
						    <label for="date" style="display: inherit!important;">*Fecha Entrada</label>
					    	<input type="text" value="<?php echo date('d-m-Y') ?>" class="sm-form-control tleft date" placeholder="DD-MM-YYYY">
						</div>
						<div class="form-group col-sm-12 col-xs-12 col-md-3" style="padding: 20px 0;">
							<button class="button button-3d button-large button-rounded button-green font-w300" id="seachBook">
								<i class="fa fa-search"></i> BUSCAR
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

		$('#seachBook').click(function(event) {
					
			var email    = $('#email').val();
			var date     = $('.date').val();

			$.get('/searchBook', {email: email, date: date,}, function(data) {

				if (data.status == 'danger') {
				    $.notify({
				        title: '<strong>'+data.title+'</strong>, ',
				        icon: 'glyphicons-remove-circle',
				        message: data.response
				    },{
				        type: data.status,
				        animate: {
				            enter: 'animated fadeInUp',
				            exit: 'animated fadeOutRight'
				        },
				        placement: {
				            from: "top",
				            align: "right"
				        },
				        offset: 120,
				        spacing: 10,
				        z_index: 1031,
				        allow_dismiss: true,
				        delay: 5000,
				        timer: 1500,
				    }); 
				}else {
	                $.notify({
	                    title: '<strong>'+data.title+'</strong>, ',
	                    icon: 'glyphicons-ok-circle',
	                    message: data.response
	                },{
	                    type: data.status,
	                    animate: {
	                        enter: 'animated fadeInUp',
	                        exit: 'animated fadeOutRight'
	                    },
	                    placement: {
	                        from: "top",
	                        align: "right"
	                    },
	                    allow_dismiss: false,
	                    offset: 120,
	                    spacing: 10,
	                    z_index: 1031,
	                    delay: 5000,
	                    timer: 1500,
	                }); 
	            }
	            if (data.status == 'warning' || data.status == 'success') {
	            	setTimeout(function(){
	            	 	window.location.replace("/supermercado/reserva/"+data.data);
            		}, 3000);
	            }


			});

		});

	});
</script>
@endsection