<?php 
	use \Carbon\Carbon; 
	setlocale(LC_TIME, "ES");
	setlocale(LC_TIME, "es_ES");
?>

<div class="col-xs-12">
	<div class="col-md-3" style="width: 22%;">
		<h2 class="text-center font-w800">
			Resumen liquidación
		</h2>
	</div>
	<div class="col-md-9">
		<table class="table">
			<tr>
				<th class ="text-center bg-complete text-white" style="width: 5%">PVP</th>
	    		<th class ="text-center bg-complete text-white" style="width: 5%">Banc. Jorg</th>
	    		<th class ="text-center bg-complete text-white" style="width: 5%">Banc. Jaime</th>
	    		<th class ="text-center bg-complete text-white" style="width: 5%">Cash Jorge</th>
	    		<th class ="text-center bg-complete text-white" style="width: 5%">Cash Jaime</th>
	    		<th class ="text-center bg-complete text-white" style="width: 4%">Pend</th>
	    		<th class ="text-center bg-complete text-white" style="width: 5%">Ing Neto</th>
	    		<th class ="text-center bg-complete text-white" style="width: 5%">%Benef</th>
	    		<th class ="text-center bg-complete text-white" style="width: 4%">Cost. Total</th>
	    		<th class ="text-center bg-complete text-white" style="width: 5%">Cost. Apto</th>
	    		<th class ="text-center bg-complete text-white" style="width: 5%">Park</th>
	    		<th class ="text-center bg-complete text-white" style="width: 5%">Sup. Lujo</th>
	    		<th class ="text-center bg-complete text-white" style="width: 5%">Limp</th>
	    		<th class ="text-center bg-complete text-white" style="width: 5%">Agencia</th>
	    		<th class ="text-center bg-complete text-white" style="width: 1%">Benef  Jorg</th>
	    		<th class ="text-center bg-complete text-white" style="width: 1%">Benef  Jaim</th>

			<tr>
				<td class="text-center" style="border-left:1px solid black;">
					<b><?php echo number_format($totales["total"],2,',','.') ?> €</b>		
				</td>
				<td class ="text-center" style="border-left:1px solid black;">
					<?php echo number_format($totales["bancoJorge"],2,',','.') ?> €		
				</td>
				<td class ="text-center" style="border-left:1px solid black;">
					<?php echo number_format($totales["bancoJaime"],2,',','.') ?> €	
				</td>
				<td class ="text-center" style="border-left:1px solid black;">
					<?php echo number_format($totales["jorge"],2,',','.') ?> €	
				</td>
				<td class ="text-center" style="border-left:1px solid black;">
					<?php echo number_format($totales["jaime"],2,',','.') ?> €	
				</td>
				<td class ="text-center" style="border-left:1px solid black;">
					<span class="text-danger"><b><?php echo number_format($totales["pendiente"],2,',','.') ?> €</b></span>
				</td>
				<td class ="text-center beneficio" style="border-left:1px solid black;">
					<b><?php echo number_format($totales["beneficio"],2,',','.') ?>€</b>
				</td>
				<td class ="text-center beneficio" style="border-left:1px solid black;">
					<?php echo number_format(($totales["beneficio"]/$totales["total"])*100,2,',','.') ?>%
				</td>
				<td class ="text-center coste" style="border-left:1px solid black;">
					<b><?php echo number_format($totales["coste"],2,',','.') ?>€</b>
				</td>
				<td class ="text-center coste" style="border-left:1px solid black;">
					<?php echo number_format($totales["costeApto"],2,',','.') ?>€
				</td>
				<td class ="text-center coste" style="border-left:1px solid black;">
					<?php echo number_format($totales["costePark"],2,',','.') ?>€
				</td>
				<td class ="text-center coste" style="border-left:1px solid black;">
					<?php echo number_format($totales["costeLujo"],2,',','.') ?>€
				</td>
				<td class ="text-center coste" style="border-left:1px solid black;">
					<?php echo number_format($totales["costeLimp"],2,',','.') ?>€
				</td>
				<td class ="text-center coste" style="border-left:1px solid black;">
					<?php echo number_format($totales["costeAgencia"],2,',','.') ?>€
				</td>
				<td class ="text-center" style="border-left:1px solid black;">
					<?php echo number_format($totales["benJorge"],2,',','.') ?>€
				</td>
				<td class ="text-center" style="border-left:1px solid black;">
					<?php echo number_format($totales["benJaime"],2,',','.') ?>€
				</td>
			</tr>
		</table>
	</div>
    <table class="table table-hover table-responsive">
    	<thead >
    		<th class ="text-center bg-complete text-white" style="width: 7%">Nombre</th>
    		<th class ="text-center bg-complete text-white" style="width: 3%">Pax</th>
    		<th class ="text-center bg-complete text-white" style="width: 5%">Apto</th>
    		<th class ="text-center bg-complete text-white" style="width: 7%">IN - OUT</th>
    		<th class ="text-center bg-complete text-white" style="width: 2%"><i class="fa fa-moon-o"></i></th>
    		<th class ="text-center bg-complete text-white" style="width: 5%">PVP</th>
    		<th class ="text-center bg-complete text-white" style="width: 5%">Banco <br> Jorg</th>
    		<th class ="text-center bg-complete text-white" style="width: 5%">Banco <br> Jaime</th>
    		<th class ="text-center bg-complete text-white" style="width: 5%">Cash <br> Jorge</th>
    		<th class ="text-center bg-complete text-white" style="width: 5%">Cash <br> Jaime</th>
    		<th class ="text-center bg-complete text-white" style="width: 5%">Pend</th>
    		<th class ="text-center bg-complete text-white" style="width: 5%">Ingreso <br> Neto</th>
    		<th class ="text-center bg-complete text-white" style="width: 5%">%Benef</th>
    		<th class ="text-center bg-complete text-white" style="width: 5%">Coste <br> Total</th>
    		<th class ="text-center bg-complete text-white" style="width: 5%">Coste <br> Apto</th>
    		<th class ="text-center bg-complete text-white" style="width: 5%">Park</th>
    		<th class ="text-center bg-complete text-white" style="width: 5%">Sup. Lujo</th>
    		<th class ="text-center bg-complete text-white" style="width: 5%">Limp</th>
    		<th class ="text-center bg-complete text-white" style="width: 5%">Agencia</th>
    		<th class ="text-center bg-complete text-white" style="width: 5%">Ben <br> Jorge</th>
    		<th class ="text-center bg-complete text-white" style="width: 5%">% <br> Jorge</th>
    		<th class ="text-center bg-complete text-white" style="width: 5%">Ben <br> Jaime</th>
    		<th class ="text-center bg-complete text-white" style="width: 5%">% <br> Jaime</th>
    	</thead>
    	<tbody >
    		<!-- Totales -->
    		
    		<?php foreach ($books as $book): ?>
    			<tr >
        			<td class="text-center">
						<a class="update-book" data-id="<?php echo $book->id ?>"  title="Editar Reserva"  href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>">
							<?php  echo $book->customer['name'] ?>		
						</a>
					</td>
        			<td class="text-center">
    					<?php echo $book->pax ?>		
    				</td>
        			<td class="text-center">
    					<?php echo $book->room->name ?>		
    				</td>
        			<td class="text-center">
        				<?php 
							$start = Carbon::createFromFormat('Y-m-d',$book->start);
							echo $start->formatLocalized('%d %b');
						?> - 
        				<?php 
							$finish = Carbon::createFromFormat('Y-m-d',$book->finish);
							echo $finish->formatLocalized('%d %b');
						?>
        			</td>
        			<td class="text-center">
    					<?php echo $book->nigths ?>		
    				</td>
        			<td class="text-center">
    					<b><?php echo number_format($book->total_price,2,',','.') ?> €</b>		
    				</td>

        			<td class="text-center pagos bi">
        				<?php echo number_format($book->getPayment(2),2,',','.'); ?> €
        			</td>
        			<td class="text-center pagos bi">
        				<?php echo number_format($book->getPayment(3),2,',','.'); ?> €
        			</td>
        			<td class="text-center pagos">
        				<?php echo number_format($book->getPayment(0),2,',','.'); ?> €
        			</td>
        			<td class="text-center pagos">
        				<?php echo number_format($book->getPayment(1),2,',','.'); ?> €
        			</td>
					<td class="text-center pagos pendiente">
						<?php echo number_format(($book->total_price - $book->getPayment(4)),2,',','.')." €"; ?>
					</td>
        			<td class="text-center beneficio bi" style="border-left: 1px solid black;"><b>
        				<?php echo number_format($book->total_ben,2,',','.') ?> €</b>
        			</td>
        			<td class="text-center beneficio bf">
        				<?php echo number_format($book->inc_percent,0)." %" ?>
        			</td>
        			<td class="text-center coste bi ">
        				<b><?php echo number_format($book->cost_total,2,',','.')?> €</b>
        			</td>
        			<td class="text-center coste">
        				<?php echo number_format($book->cost_apto,2,',','.')?> €
        			</td>
        			<td class="text-center coste">
        				<?php echo number_format($book->cost_park,2,',','.')?> €
        			</td>
        			<td class="text-center coste" >
        				<?php echo number_format($book->cost_lujo,2,',','.')?> €
        			</td>
        			<td class="text-center coste">		
        				<?php echo number_format($book->sup_limp,2,',','.') ?>€
        			</td>
        			<td class="text-center coste bf">	
        				<?php echo number_format($book->agency,2,',','.') ?>€
        			</td>
        			<td class="text-center">
        				<?php echo number_format($book->ben_jorge,2,',','.') ?>
        			</td>
        			<?php if ($book->total_ben > 0 && $book->ben_jorge > 0): ?>
    				<td class="text-center">
    					<?php echo number_format(($book->total_ben/$book->ben_jorge)*100)."%" ?>
    				</td>
        			<?php else: ?>
    				<td class="text-center"> 0%</td>
        			<?php endif ?>
        			
        			<td class="text-center">
        				<?php echo number_format($book->ben_jaime,2,',','.') ?>
        			</td>
        			<?php if ($book->total_ben > 0 && $book->ben_jaime > 0): ?>
    				<td class="text-center">
						<?php echo number_format(($book->total_ben/$book->ben_jaime)*100)."%" ?>
    				</td>
        			<?php else: ?>
    				<td class="text-center"> 0%</td>
        			<?php endif ?>
        		</tr>
    		<?php endforeach ?>
    		
    	</tbody>
    </table>

</div>