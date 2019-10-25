<?php use \Carbon\Carbon;  setlocale(LC_TIME, "ES"); setlocale(LC_TIME, "es_ES"); ?>
<div style="max-width: 767px; float:left;margin: 0 auto">
	<div style="float:left;margin: 0 auto; width: 100%; margin-bottom: 20px;">
		<h2 style="text-align: justify;">
			Hola Maria, tienes un nuevo pedido.
		</h2>
		<p style="text-align: justify;">
			Te dejo aquí los datos del cliente:<br><br>

			<b>Nombre:</b> <?php echo $ordersProducts[0]->order->book->customer->name ?><br><br>
			<b>Email:</b> <?php echo $ordersProducts[0]->order->book->customer->email ?><br><br>
			<b>Fecha entrada:</b> <?php echo Carbon::CreateFromFormat('Y-m-d',$ordersProducts[0]->order->book->start)->formatLocalized('%d %b %Y') ?><br><br>
		</p>
	</div>
	<div class="col-xs-12 push-20" style="float:left;margin: 0 auto; width: 100%; margin-bottom: 20px;">
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
	<div class="col-xs-12 push-20" style="float:left;margin: 0 auto; width: 100%; margin-bottom: 20px;">
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
</div>
