<style type="text/css">
	.bordered{
		padding: 15px;
		border:1px solid #e8e8e8;
		background: white;
	}
</style>
<!-- <pre>
	<?php print_r($data) ?>
</pre> -->
<div class="row">
	<div class="col-md-3">
		<div class="col-md-6 bordered">
			<div class="card-title text-black hint-text">
				Dias ocupados
			</div>
			<div class="p-l-20">
				<h3 class="text-black font-w400 text-center"><?php echo $data['days-ocupation'] ?></h3>
			</div>
		</div>
		<div class="col-md-6 bordered">
			<div class="card-title text-black hint-text">
				Dias propios
			</div>
			<div class="p-l-20">
				<h3 class="text-black font-w400 text-center"><?php echo $data['dias-propios']; ?></h3>
			</div>
		</div>
	</div>
	<div class="col-md-5">
		<div class="col-md-4 bordered">
			<div class="card-title text-black hint-text">
				Total noches
			</div>
			<div class="p-l-20">
				<h3 class="text-black font-w400 text-center"><?php echo $data['days-ocupation'] + $data['dias-propios']  ?></h3>
			</div>
		</div>
		<div class="col-md-4 bordered">
			<div class="card-title text-black hint-text">
				Días totales temp.
			</div>
			<div class="p-l-20">
				<input class="form-control text-black font-w400 text-center seasonDays" value="<?php echo $data['total-days-season'] ?>" style="border: none; font-size: 32px;margin: 10px 0;color:red!important"/>
			</div>
		</div>
		<div class="col-md-4 bordered">
			<div class="card-title text-black hint-text">
				% ocupación
			</div>
			<div class="p-l-20">
				<h3 class="text-black font-w400 text-center"><?php echo round($data['pax-media']) ?>%</h3>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="col-md-3 bordered">
			<div class="card-title text-black hint-text">
				Nº Inquilinos
			</div>
			<div class="p-l-20">
				<h3 class="text-black font-w400 text-center"><?php echo $data['num-pax'] ?></h3>
			</div>
		</div>
		<div class="col-md-3 bordered">
			<div class="card-title text-black hint-text">
				Estan. media (días)
			</div>
			<div class="p-l-20">
				<h3 class="text-black font-w400 text-center"><?php echo round($data['estancia-media']) ?></h3>
			</div>
		</div>
		<div class="col-md-3 bordered">
			<div class="card-title text-black hint-text">
				Venta propia
			</div>
			<div class="p-l-20">
				<h3 class="text-black font-w400 text-center"><?php echo round($data['propios']) ?>%</h3>
			</div>
		</div>
		<div class="col-md-3 bordered">
			<div class="card-title text-black hint-text">
				Venta agencia
			</div>
			<div class="p-l-20">
				<h3 class="text-black font-w400 text-center"><?php echo round($data['agencia']) ?>%</h3>
			</div>
		</div>
		
	</div>
</div>
