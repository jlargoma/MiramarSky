<div class="col-xs-12 push-20">
	<?php $total = 0; ?>
	<?php $subTotal = 0; ?>
	<?php $iva = 0; ?>
	<?php foreach ($ordersProducts as $key => $orderProduct): ?>
		<div class="col-xs-12 push-10" style="border-bottom: 1px solid black;padding: 10px 15px 10px 0;">
			<div class="col-xs-2">
				<a href="{{ url ('/supermercado/pedidos/delete')}}/<?php echo $orderProduct->id ?>" class="remove" title="Eliminar <?php echo $orderProduct->product->name; ?> "><i class="icon-trash2"></i></a>
			</div>
			<div class="col-xs-6" style="font-size: 14px; line-height: 1">
				<?php echo $orderProduct->product->name; ?> 
			</div>
			<div class="col-xs-2 not-padding">
				X <?php echo $orderProduct->quantity; ?> 
			</div>
			<div class="col-xs-2">
				<?php echo number_format($orderProduct->product->price, 2,',','.') ?>€
			</div>
		</div>
		<?php $total    +=  ($orderProduct->product->price * $orderProduct->quantity); ?>
		<?php $subTotal +=  ($orderProduct->product->unity * $orderProduct->quantity); ?>
		<?php $iva      +=  (($orderProduct->product->price - $orderProduct->product->unity)* $orderProduct->quantity); ?>

	<?php endforeach ?>
</div>
<div class="col-xs-12 push-20">
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
<div class="col-xs-12">
	<?php if ( count($ordersProducts) > 0  && $total > 10): ?>
		<a href="{{ url('/supermercado/checkout') }}/<?php echo base64_encode($order->id) ?>" class="button button-desc button-3d button-rounded button-green center">
			PASAR POR CAJA
		</a>
	<?php else: ?>
		<button class="button button-desc button-3d button-rounded button-leaf center" disabled>
			PASAR POR CAJA
		</button>
	<?php endif ?>
	

</div>