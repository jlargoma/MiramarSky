<div class="table-responsive bottommargin">
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
						<button class="btn btn-primary addCart" data-order="<?php echo $order->id ?>" data-product="<?php echo $product->id; ?>">
							<i class="icon-shopping-cart"></i>
						</button>
					</td>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>
</div>
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
			});
		}

		
	});
</script>