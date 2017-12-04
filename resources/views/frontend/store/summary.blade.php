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

		}
	</style>
@endsection

@section('title')Compra en casa - Apartamentos de lujo en Sierra Nevada a pie de pista @endsection

@section('content')
<?php if (!$mobile->isMobile()): ?>
	<section class="section page" style="min-height: 420px">
		<div class="slider-parallax-inner">
			<div class="container ">

				<div class="row" style="background-color: white;">
					<div class="heading-block center">
						<h1>PRODUCTOS</h1>
						<span>Haz tu compra entre nuestra gran variedad de productos</span>
					</div>
					<div class="col-md-12">
						
						<div class="row">

							<div class="col-md-9 col-xs-12">
								<div class="row">
									<div class="col-md-4 pull-right">
										<input type="text" class="form-control" placeholder="Buscar..." id="searchProduct">
									</div>
								</div>
								<div class="row content-products">
									@include('frontend.store._tableProducts')
								</div>
								
							</div>
							<div class="col-md-3 col-xs-12 ">
								<div class="row summaryCart">
									<?php $ordersProducts = \App\Products_orders::where('order_id', $order->id)->get(); ?>
									@include('frontend.store._summaryCart', ['ordersProducts', $ordersProducts])
								</div>
								
							</div>
						</div>
						
						
					</div>
				</div>

			</div>
		</div>
	</section>
<?php else: ?>


<?php endif; ?>


@endsection

@section('scripts')
	

	<script type="text/javascript">
		$('#searchProduct').keyup(function(event) {
            var searchString = $(this).val();
            var order = <?php echo $order->id ?>;

            $.get('/supermercado/search/searchByName', { searchString: searchString, order: order}, function(data) {

                $('.content-products').empty().append(data);
                
            });
        });

		
	</script>
@endsection