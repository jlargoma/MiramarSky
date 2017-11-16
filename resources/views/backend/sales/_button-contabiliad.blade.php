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
 	};?>
 	

	<div class="col-md-12 col-xs-12 push-20">
		<div class="col-md-1 col-xs-2">
			<?php if ($url == "contabilidad"): ?>
				<button class="btn btn-md" style="width: 100%; background-color: #6600ff;pointer-events: none" disabled>
					<a class="text-white" >Contabilidad</a>
				</button>
			<?php else: ?>
				<a class="text-white" href="{{url('/admin/contabilidad/')}}">
					<button class="btn btn-md btn-primary" style="width: 100%;">
						Contabilidad
					</button>
				</a>
			<?php endif ?>	
		</div>

		<div class="col-md-1 col-xs-2">
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

		<div class="col-md-1 col-xs-2">
	    	<?php if ($url == "pending"): ?>
	    		<button class="btn btn-md" style="width: 100%; background-color: #6600ff;pointer-events: none" disabled>
	    			<a class="text-white" >Banco</a>
	    		</button>
	    	<?php else: ?>
	    		<a class="text-white" href="{{url('/admin/pending/')}}">
		    		<button class="btn btn-md btn-primary" style="width: 100%;">
			        	Banco
		    		</button>
	    		</a>
	    	<?php endif ?>
		</div>

		<div class="col-md-1 col-xs-2">
	    	<?php if ($url == "cashbox"): ?>
	    		<button class="btn btn-md" style="width: 100%; background-color: #6600ff;pointer-events: none" disabled>
	    			<a class="text-white" >Caja</a>
	    		</button>
	    	<?php else: ?>
	    		<a class="text-white" href="{{url('/admin/cashbox/')}}" >
	    			<button class="btn btn-md btn-primary" style="width: 100%;">
			        	Caja
	    			</button>
	    		</a>
	    	<?php endif ?>
		</div>

		<div class="col-md-1 col-xs-2">
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
		<div class="col-md-1 col-xs-2">
	    	<?php if ($url == "cuenta-socios"): ?>
	    		<button class="btn btn-md" style="width: 100%; background-color: #6600ff;pointer-events: none" disabled>
	    			<a class="text-white" >CTA SOCIOS</a>
	    		</button>
	    	<?php else: ?>
	    		<a class="text-white" href="{{url('/admin/cuenta-socios/')}}">
		    		<button class="btn btn-md btn-primary" style="width: 100%;">
			        	CTA SOCIOS
		    		</button>
	    		</a>
	    	<?php endif ?>
		</div>
			<div class="col-md-1 col-xs-2">
		    	<?php if ($url == "estadisticas"): ?>
		    		<button class="btn btn-md" style="width: 100%; background-color: #6600ff;pointer-events: none" disabled>
		    			<a class="text-white" >Estadisticas</a>
		    		</button>
		    	<?php else: ?>
		    		<a class="text-white" href="{{url('/admin/estadisticas/')}}">
			    		<button class="btn btn-md btn-primary" style="width: 100%;">
				        	Estadisticas
			    		</button>
		    		</a>
		    	<?php endif ?>
			</div>
<!-- 		<div class="col-md-1 col-xs-2">
			<button class="btn btn-md btn-primary" style="width: 100%;">
			    <a class="text-white" href="{{url('/admin/perdidas-ganancias/')}}">INFORMES</a>
			</button>
		</div> -->
		
	</div>
