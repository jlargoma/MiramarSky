 <style>
 	.btn-primary{
 		background-color: #295d9b!important;
 	}
 </style>
 <?php 
 	$url = substr(Request::path(), 6);
 	$posicion =strpos($url, '/');
 	if ($posicion > 0) {
 		 $url = substr($url,0,$posicion);
 	}else{
 		 $url;
 	};
?>
 	

<div class="col-md-12 col-xs-12 push-20">
	<div class="col-md-2 col-xs-2">
		<?php if ($url == "contabilidad"): ?>
			<button class="btn btn-md" style="width: 100%; background-color: #6600ff;pointer-events: none" disabled>
				<a class="text-white" >Estadisticas</a>
			</button>
		<?php else: ?>
			<a class="text-white" href="{{url('/admin/contabilidad/')}}">
				<button class="btn btn-md btn-primary" style="width: 100%;">
					Estadisticas
				</button>
			</a>
		<?php endif ?>	
	</div>

	<div class="col-md-2 col-xs-2">
    	<?php if ($url == "gastos"): ?>
    		<button class="btn btn-md" style="width: 100%; background-color: #6600ff;pointer-events: none" disabled>
    			<a class="text-white" >Gastos</a>
    		</button>
    	<?php else: ?>
    		<a class="text-white" href="{{url('/admin/gastos/')}}">
    			<button class="btn btn-md btn-primary" style="width: 100%;">
		        	Gastos
    			</button>
    		</a>
    	<?php endif ?>	
	</div>

	<div class="col-md-2 col-xs-2">
    	<?php if ($url == "ingresos"): ?>
    		<button class="btn btn-md" style="width: 100%; background-color: #6600ff;pointer-events: none" disabled>
    			<a class="text-white" >Ingresos</a>
    		</button>
    	<?php else: ?>
    		<a class="text-white" href="{{url('/admin/ingresos/')}}">
	    		<button class="btn btn-md btn-primary" style="width: 100%;">
		        	Ingresos
	    		</button>
    		</a>
    	<?php endif ?>
	</div>


	<div class="col-md-2 col-xs-2">
    	<?php if ($url == "perdidas-ganancias"): ?>
    		<button class="btn btn-md" style="width: 100%; background-color: #6600ff;pointer-events: none" disabled>
    			<a class="text-white" >CTA P &amp; G</a>
    		</button>
    	<?php else: ?>
    		<a class="text-white" href="{{url('/admin/perdidas-ganancias/')}}">
	    		<button class="btn btn-md btn-primary" style="width: 100%;">
		        	CTA P &amp; G
	    		</button>
    		</a>
    	<?php endif ?>
	</div>
</div>
