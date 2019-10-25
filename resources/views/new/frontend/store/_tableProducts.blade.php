
	<?php if (!$mobile->isMobile()): ?>
		<div class="table-responsive bottommargin" style="max-height: 800px; overflow-y: auto;">
			<table class="table cart">
				<thead>
					<tr>
						<th class="cart-product-thumbnail">&nbsp;</th>
						<th class="cart-product-name" style="width: 30%;">Producto</th>
						<th class="cart-product-price">Precio</th>
						<th class="cart-product-quantity">Cantidad</th>
						<th class="cart-product-quantity">Añadir</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($products as $key => $product): ?>
						<tr class="cart_item">

							<td class="cart-product-thumbnail">
								<img width="64" height="64" src="<?php echo $product->image; ?>" alt="<?php echo $product->name; ?>">
							</td>

							<td class="cart-product-name">
								<?php echo $product->name; ?>
								<input type="hidden" name="product-<?php echo $product->id; ?>" value="<?php echo $product->id; ?>">
							</td>

							<td class="cart-product-price">
								<span class="amount"><?php echo number_format($product->price, 2,',','.') ?>€</span>
							</td>

							<td class="cart-product-quantity">
								<div class="quantity clearfix">
									<input type="button" value="-" class="minus" data-target="<?php echo $product->id; ?>" data-opt="minus">
									<input type="number" name="quantity-<?php echo $product->id; ?>" value="0" id="qty-<?php echo $product->id; ?>" class="qty"  min="0" max="10">
									<input type="button" value="+" class="plus" data-target="<?php echo $product->id; ?>" data-opt="plus">
								</div>
							</td>
							<td class="cart-product-quantity">
								<button class="btn btn-success addCart" data-order="<?php echo $order->id ?>" data-product="<?php echo $product->id; ?>">
									<i class="icon-shopping-cart"></i>
								</button>
							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
	<?php else: ?>
		<div class="col-xs-12" style="max-height: 460px; overflow-y: auto;">
			<?php foreach ($products as $key => $product): ?>
				<div class="col-xs-12 push-20">
					<div class="col-xs-2 not-padding">
						<img class="img-responsive" src="<?php echo $product->image; ?>" alt="<?php echo $product->name; ?>">
					</div>
					<div class="col-xs-6 text-left">
						<?php echo $product->name; ?>
						<input type="hidden" name="product-<?php echo $product->id; ?>" value="<?php echo $product->id; ?>">
						<div class="quantity text-center clearfix" style="margin: 0 auto;  width: 170px;">
							<input type="button" value="-" class="minus" data-target="<?php echo $product->id; ?>" data-opt="minus" style="width: 26px">
							<input type="number" name="quantity-<?php echo $product->id; ?>" value="0" id="qty-<?php echo $product->id; ?>" class="qty"  min="0" max="10">
							<input type="button" value="+" class="plus" data-target="<?php echo $product->id; ?>" data-opt="plus" style="width: 26px">
						</div>
					</div>
					<div class="col-xs-2">
						<span class="amount"><?php echo number_format($product->price, 2,',','.') ?>€</span>
					</div>
					<div class="col-xs-2">
						<button class="btn btn-primary btn-xs addCart" data-order="<?php echo $order->id ?>" data-product="<?php echo $product->id; ?>">
							<i class="icon-shopping-cart"></i>
						</button>
					</div>
				</div>
			<?php endforeach ?>
		
		</div>
	<?php endif; ?>
<script type="text/javascript">
	$('.minus, .plus').click(function(event) {
		var target = "qty-"+$(this).attr('data-target');
		if ( $(this).attr('data-opt') == "plus" ) {
			if (parseInt( $("#"+target).val()) < 10) {
				var sum =  parseInt( $("#"+target).val()) + 1;
				$("#"+target).val(sum);
			}
		} else {
			if (parseInt( $("#"+target).val()) > 0) {
				var res = parseInt( $("#"+target).val()) - 1;
				$("#"+target).val(res);
			}
			

		}
	});

	$('.addCart').click(function(event) {
		var product = $(this).attr('data-product');
		var order   = $(this).attr('data-order');
		var qty     = $('#qty-'+product).val();

		if ($('#qty-'+product).val() == 0) {
			alert('Añade un numero de unidades');
		} else {
			$.get('/supermercado/addCart',{product: product,order: order,qty: qty}, function(data) {
				$('.summaryCart').empty();
				$('.summaryCart').load('/supermercado/getSummaryCart?order='+order);

				$.notify({
	                title: '<strong>AÑADIDO</strong>, ',
	                icon: 'glyphicon glyphicon-check',
	                message: 'Articulo añadido correctamente'
	            },{
	                type: 'success',
	                animate: {
	                    enter: 'animated fadeInUp',
	                    exit: 'animated fadeOutRight'
	                },
	                placement: {
	                    from: "top",
	                    align: "right"
	                },
	                allow_dismiss: false,
	                <?php if (!$mobile->isMobile()): ?>
			        	offset: 120,
			        <?php else: ?>
			        	offset: 10,
			        <?php endif ?>
	                spacing: 10,
	                z_index: 1031,
	                delay: 5000,
	                timer: 1500,
	            }); 

			});
		}

		
	});
</script>