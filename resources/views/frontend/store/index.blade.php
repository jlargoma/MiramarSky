@extends('layouts.master_withoutslider')
@section('css')
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
<section class="section page" style="min-height: 420px">
	<div class="slider-parallax-inner">
		<div class="container">

			<div class="row">
				<div class="heading-block center">
					<h1>Cesta de la compra</h1>
					<span>Compra ONLINE y te lo llevamos al edificio el dia de tu entrada</span>
				</div>
				<div class="col-md-12">

					<div class="form-group col-sm-12 col-xs-12 col-md-6 col-lg-6">
					    <label for="email">*Email</label>
					    <input type="email" class="sm-form-control"  name="email" id="email" placeholder="Email..." maxlength="40" required="">
					</div>
					<div class="form-group col-sm-12 col-xs-12 col-md-3">
					    <label for="date" style="display: inherit!important;">*Entrada - Salida</label>
					    <div class="input-group">
					        <input type="text" class="sm-form-control daterange1" id="date"   name="date" required style="cursor: pointer;text-align: center;" readonly="">
					    </div>
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
						    <label for="date" style="display: inherit!important;">*Entrada - Salida</label>
						    <div class="input-group">
						        <input type="text" class="sm-form-control daterange1" id="date"   name="date" required style="cursor: pointer;text-align: center;" readonly="">
						    </div>
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
<script type="text/javascript">
	$(document).ready(function() {
		
		$('#seachBook').click(function(event) {
					
			var email    = $('#email').val();
			var date     = $('#date').val();

			$.get('/searchBook', {email: email, date: date,}, function(data) {
				$('#resultBooksSearch').empty().append(data);
				$('#resultBooksSearch').show();
			});

		});

	});
</script>
@endsection