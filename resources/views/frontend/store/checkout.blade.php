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
		.StripeElement {
			background-color: white;
			padding: 8px 12px;
			border-radius: 4px;
			border: 1px solid transparent;
			box-shadow: 0 1px 3px 0 #e6ebf1;
			-webkit-transition: box-shadow 150ms ease;
			transition: box-shadow 150ms ease;
		}

		.StripeElement--focus {
			box-shadow: 0 1px 3px 0 #cfd7df;
		}

		.StripeElement--invalid {
			border-color: #fa755a;
		}

		.StripeElement--webkit-autofill {
			background-color: #fefde5 !important;
		}
		.stripe-price{
			background-color: white!important;
			padding: 8px 12px!important;
			border-radius: 4px!important;
			border: 1px solid transparent!important;
			box-shadow: 0 1px 3px 0 #e6ebf1!important;
			-webkit-transition: box-shadow 150ms ease!important;
			transition: box-shadow 150ms ease!important;
		}
	</style>
@endsection

@section('title')Compra en casa - Apartamentos de lujo en Sierra Nevada a pie de pista @endsection

@section('content')
<script src="//js.stripe.com/v3/"></script>
<?php if (!$mobile->isMobile()): ?>
	<section class="section page" style="min-height: 420px; padding-top: 0;">
		<div class="slider-parallax-inner">
			<div class="row text-center push-20" style="background-image: url({{ asset('/img/miramarski/supermercado.jpg')}}); background-size: cover; background-position: 50% 35%; padding: 40px 0 0;">
				<div class="heading-block center text-white">
					<h1 style="color:white; text-shadow: 1px 1px #000">CHECKOUT</h1>
					<span style="color:white; text-shadow: 1px 1px #000">Estas a un paso de confirmar tu pedido</span>
				</div>
			</div>
			<div class="container">
			<?php if ($payment == 0): ?>
				
				<div class="row" style="background-color: white;">
					<div class="col-md-12">
						
						<div class="row">
							<div class="col-md-6">
								<div class="table-responsive clearfix">
									<h4>Tu pedido</h4>
									<?php $total = 0; ?>
									<?php $subTotal = 0; ?>
									<?php $iva = 0; ?>
									<table class="table cart">
										<thead>
											<tr>
												<th class="cart-product-thumbnail">&nbsp;</th>
												<th class="cart-product-name" style="width: 30%;">Producto</th>
												<th class="cart-product-quantity">Cantidad</th>
												<th class="cart-product-price">Precio</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($ordersProducts as $key => $orderProduct): ?>
											
												<tr class="cart_item">

													<td class="cart-product-thumbnail">
														<img width="64" height="64" src="<?php echo $orderProduct->product->image; ?>" alt="<?php echo $orderProduct->product->name; ?>">
													</td>

													<td class="cart-product-name">
														<?php echo $orderProduct->product->name; ?>
														
													</td>

													<td class="cart-product-quantity">
														<?php echo $orderProduct->quantity; ?>
													</td>
													<td class="cart-product-price">
														<span class="amount"><?php echo number_format($orderProduct->product->price, 2,',','.') ?>€</span>
													</td>
												</tr>
												<?php $total    +=  ($orderProduct->product->price * $orderProduct->quantity); ?>
												<?php $subTotal +=  ($orderProduct->product->unity * $orderProduct->quantity); ?>
												<?php $iva      +=  (($orderProduct->product->price - $orderProduct->product->unity)* $orderProduct->quantity); ?>
											<?php endforeach ?>
										</tbody>
									</table>

								</div>
							</div>
							

							<div class="col-md-6">
								<div class="table-responsive">
									<h4>Totales</h4>
										<table class="table cart">
											<tbody>
												<tr class="cart_item">
													<td class="cart-product-name">
														<strong>Subtotal</strong>
													</td>

													<td class="cart-product-name">
														<span class="amount"><?php echo number_format($subTotal, 2,',','.') ?>€</span>
													</td>
												</tr>
												<tr class="cart_item">
													<td class="cart-product-name">
														<strong>Impuestos</strong>
													</td>

													<td class="cart-product-name">
														<span class="amount"><?php echo number_format($iva, 2,',','.') ?>€</span>
													</td>
												</tr>
												<tr class="cart_item">
													<td class="cart-product-name">
														<strong>Total</strong>
													</td>

													<td class="cart-product-name">
														<span class="amount color lead"><strong><?php echo number_format($total, 2,',','.') ?>€</strong></span>
													</td>
												</tr>
											</tbody>

										</table>

								</div>
								<div class="row alert alert-info fade in alert-dismissable" style="margin-top: 30px; background-color: #daeffd!important;">
									<form action="{{ url('/supermercado/stripe/payment') }}" method="post" id="payment-form">
            							<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

										<input type="hidden" name="id_order" value="<?php echo $order->id; ?>">
										<div class="col-md-6 col-xs-12 text-left push-20">
											<label for="email">Email</label>
											<input type="email" class="form-control stripe-price" name="email" value="<?php echo $order->book->customer->email ?>" />
										</div>
										<div class="col-md-6 col-xs-12 text-left push-20">
											<label for="importe">Importe a cobrar</label>
											<input type="number" class="form-control stripe-price" name="importe" value="<?php echo  $total ?>" />
										</div>
										<div class="form-row col-xs-12 push-20">
											<label for="card-element">
												Datos de la tarjeta
											</label>
											<div id="card-element">
												<!-- a Stripe Element will be inserted here. -->
											</div>

											<!-- Used to display form errors -->
											<div id="card-errors" role="alert"></div>
										</div>
										<div class="col-xs-12 text-center">
											<button class="button button-3d fright">Cobrar</button>
										</div>

									</form>
								</div>
							</div>
						</div>
						
						
					</div>
				</div>
			<?php else: ?>
				
				
				<div class="col-md-8 col-md-offset-2">
					<div class="col-md-12">
						<h2 class=" text-center font-w300 ls1 shadow" style="line-height: 1; font-size: 42px;">
							<?php echo $message[0] ?><br>
							<span class="font-w800 black shadow" style="font-size: 56px;letter-spacing: -3px;">
								<?php echo $message[1] ?><br>
							</span>
							
						</h2>
					</div>							
				</div>

			<?php endif ?>
			</div>
		</div>
	</section>
<?php else: ?>

	<section class="section page" style="min-height: 420px;  margin-top: 20px; padding-top: 0">
		<div class="row text-center push-20" style="background-image: url({{ asset('/img/miramarski/supermercado.jpg')}}); background-size: cover; background-position: 50% 35%; padding: 40px 0 0;">
			<div class="heading-block center text-white">
				<h1 style="color:white; text-shadow: 1px 1px #000">CHECKOUT</h1>
				<span style="color:white; text-shadow: 1px 1px #000">Estas a un paso de confirmar tu pedido</span>
			</div>
		</div>
		<div class="slider-parallax-inner">
			<div class="container-mobile ">
			<?php if ($payment == 0): ?>
				
				<div class="row" style="background-color: white;">
					<div class="col-md-12">
						
						<div class="row">
							<div class="col-md-6 col-xs-12">
								<h4 class="text-center push-10">Tu pedido</h4>
								<div class="table-responsive clearfix">
									<?php $total = 0; ?>
									<?php $subTotal = 0; ?>
									<?php $iva = 0; ?>
									<table class="table">
										<thead>
											<tr>
												<th class="cart-product-thumbnail">&nbsp;</th>
												<th class="cart-product-name" style="width: 30%;">Producto</th>
												<th class="cart-product-quantity">Cantidad</th>
												<th class="cart-product-price">Precio</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($ordersProducts as $key => $orderProduct): ?>
											
												<tr class="">

													<td class="">
														<img width="32" height="32" src="<?php echo $orderProduct->product->image; ?>" alt="<?php echo $orderProduct->product->name; ?>">
													</td>

													<td class="cart-product-name">
														<?php echo $orderProduct->product->name; ?>
														
													</td>

													<td class="cart-product-quantity">
														<?php echo $orderProduct->quantity; ?>
													</td>
													<td class="cart-product-price">
														<span class="amount"><?php echo number_format($orderProduct->product->price, 2,',','.') ?>€</span>
													</td>
												</tr>
												<?php $total    +=  ($orderProduct->product->price * $orderProduct->quantity); ?>
												<?php $subTotal +=  ($orderProduct->product->unity * $orderProduct->quantity); ?>
												<?php $iva      +=  (($orderProduct->product->price - $orderProduct->product->unity)* $orderProduct->quantity); ?>
											<?php endforeach ?>
										</tbody>
									</table>

								</div>
							</div>
							

							<div class="col-md-6 col-xs-12">
								<div class="table-responsive">
									<h4 class="text-center push-10">Totales</h4>
										<table class="table cart">
											<tbody>
												<tr class="cart_item">
													<td class="cart-product-name">
														<strong>Subtotal</strong>
													</td>
													
													<td class="cart-product-name text-right">
														<span class="amount"><?php echo number_format($subTotal, 2,',','.') ?>€</span>
													</td>
												</tr>
												<tr class="cart_item">
													<td class="cart-product-name">
														<strong>Impuestos</strong>
													</td>

													<td class="cart-product-name text-right">
														<span class="amount"><?php echo number_format($iva, 2,',','.') ?>€</span>
													</td>
												</tr>
												<tr class="cart_item">
													<td class="cart-product-name">
														<strong>Total</strong>
													</td>

													<td class="cart-product-name text-right">
														<span class="amount color lead"><strong><?php echo number_format($total, 2,',','.') ?>€</strong></span>
													</td>
												</tr>
											</tbody>

										</table>

								</div>
								<div class="row alert alert-info fade in alert-dismissable" style="margin-top: 30px; background-color: #daeffd!important;">
									<form action="{{ url('/supermercado/stripe/payment') }}" method="post" id="payment-form">
            							<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

										<input type="hidden" name="id_order" value="<?php echo $order->id; ?>">
										<div class="col-md-6 col-xs-12 text-left push-20">
											<label for="email">Email</label>
											<input type="email" class="form-control stripe-price" name="email" value="<?php echo $order->book->customer->email ?>" />
										</div>
										<div class="col-md-6 col-xs-12 text-left push-20">
											<label for="importe">Importe a cobrar</label>
											<input type="number" class="form-control stripe-price" name="importe" value="<?php echo  $total ?>" />
										</div>
										<div class="form-row col-xs-12 push-20">
											<label for="card-element">
												Datos de la tarjeta
											</label>
											<div id="card-element">
												<!-- a Stripe Element will be inserted here. -->
											</div>

											<!-- Used to display form errors -->
											<div id="card-errors" role="alert"></div>
										</div>
										<div class="col-xs-12 text-center">
											<button class="button button-3d fright">PAGAR</button>
										</div>

									</form>
								</div>
							</div>
						</div>
						
						
					</div>
				</div>
			<?php else: ?>
				
				
				<div class="col-md-8 col-md-offset-2 col-xs-12">
					<div class="col-md-12">
						<h2 class=" text-center font-w300 ls1 shadow" style="line-height: 1; font-size: 42px;">
							<?php echo $message[0] ?><br>
							<span class="font-w800 black shadow" style="font-size: 48px;letter-spacing: -3px;">
								<?php echo $message[1] ?><br>
							</span>
							
						</h2>
					</div>							
				</div>

			<?php endif ?>
			</div>
		</div>
	</section>
<?php endif; ?>


@endsection

@section('scripts')
	<script type="text/javascript">
		function stripeTokenHandler(token) {
            // Insert the token ID into the form so it gets submitted to the server
            var form = document.getElementById('payment-form');
            var hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'stripeToken');
            hiddenInput.setAttribute('value', token.id);
            form.appendChild(hiddenInput);

            // Submit the form
            form.submit();
        }
        // Create a Stripe client
        var stripe = Stripe('<?php echo $stripe['publishable_key'] ?>');

        // Create an instance of Elements
        var elements = stripe.elements();

        // Custom styling can be passed to options when creating an Element.
        // (Note that this demo uses a wider set of styles than the guide below.)
        var style = {
        	base: {
        		color: '#32325d',
        		lineHeight: '24px',
        		fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
        		fontSmoothing: 'antialiased',
        		fontSize: '16px',
        		'::placeholder': {
        			color: '#aab7c4'
        		}
        	},
        	invalid: {
        		color: '#fa755a',
        		iconColor: '#fa755a'
        	}
        };

        // Create an instance of the card Element
        var card = elements.create('card', {style: style});

        // Add an instance of the card Element into the `card-element` <div>
        card.mount('#card-element');

        // Handle real-time validation errors from the card Element.
        card.addEventListener('change', function(event) {
        	var displayError = document.getElementById('card-errors');
        	if (event.error) {
        		displayError.textContent = event.error.message;
        	} else {
        		displayError.textContent = '';
        	}
        });

        // Handle form submission
        var form = document.getElementById('payment-form');
        form.addEventListener('submit', function(event) {
        	event.preventDefault();

        	stripe.createToken(card).then(function(result) {
        		if (result.error) {
              // Inform the user if there was an error
              var errorElement = document.getElementById('card-errors');
              errorElement.textContent = result.error.message;
          } else {
              // Send the token to your server
              stripeTokenHandler(result.token);
          }
      });
        })
    </script>
@endsection