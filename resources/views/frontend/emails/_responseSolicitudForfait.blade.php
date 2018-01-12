<?php 
	use \Carbon\Carbon;
	setlocale(LC_TIME, "ES"); 
    setlocale(LC_TIME, "es_ES"); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<style type="text/css">
			body {margin: 0; padding: 0; min-width: 100%!important;}
			img {height: auto;}
			.content {width: 100%; max-width: 600px;}
			.header {padding: 20px 30px 20px 30px;}
			.innerpadding {padding: 30px 30px 30px 30px;}
			.borderbottom {border-bottom: 1px solid #f2eeed;}
			.subhead {font-size: 15px; color: #ffffff; font-family: sans-serif; letter-spacing: 0px;}
			.h1, .h2, .bodycopy {color: #FFF; font-family: sans-serif;}
			.h1 {font-size: 33px; line-height: 38px; font-weight: bold;}
			.h2 {padding: 0 0 15px 0; font-size: 24px; line-height: 28px; font-weight: bold;}
			.bodycopy {font-size: 16px; line-height: 22px;}
			.button {text-align: center; font-size: 18px; font-family: sans-serif; font-weight: bold; padding: 0 30px 0 30px;}
			.button a {color: #ffffff; text-decoration: none;}
			.footer {padding: 20px 30px 15px 30px;}
			.footercopy {font-family: sans-serif; font-size: 14px; color: #ffffff;}
			.footercopy a {color: #ffffff; text-decoration: underline;}

			@media only screen and (max-width: 550px), screen and (max-device-width: 550px) {
				body[yahoo] .hide {display: none!important;}
				body[yahoo] .buttonwrapper {background-color: transparent!important;}
				body[yahoo] .button {padding: 0px!important;}
				body[yahoo] .button a {background-color: #e05443; padding: 15px 15px 13px!important;}
				body[yahoo] .unsubscribe {display: block; margin-top: 20px; padding: 10px 50px; background: #2f3942; border-radius: 5px; text-decoration: none!important; font-weight: bold;}
			}

			@media only screen and (min-device-width: 601px) {
				.content {width: 600px !important;}
				.col425 {width: 425px!important;}
				.col380 {width: 380px!important;}
			}	

		</style>
	</head>

	<body yahoo bgcolor="#ffffff">
		<table width="100%" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td>
		          	<table bgcolor="#ffffff" class="content" align="center" cellpadding="0" cellspacing="0" border="0">
		          		<tr>
		          			<td bgcolor="#3F51B5" class="header">
		                    	<table class="" align="left" border="0" cellpadding="0" cellspacing="0" style="width: 100%; ">  
		                    		<tr>
		                    			<td height="70">
		                    				<table width="100%" border="0" cellspacing="0" cellpadding="0">
		                    					<tr>
		                    						<td class="h1" style="padding: 5px 0 0 0; color: white; font-size: 32px;">
		                    							Solicitud Forfaits & Clases Ski – Miramar ski
		                    						</td>
		                    					</tr>
		                    				</table>
		                    			</td>
		                    		</tr>
		                    	</table>
		      				</td>
		  				</tr>
		  				<tr>
		  					<td class="innerpadding borderbottom">
		  						<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  							<tr>
		  								<td class="bodycopy" style="color: #000">
		  									Hola, has recibido una nueva solicitud:<br><br>
											<span style="font-weight: 300; font-size: 18px;">
												<?php $date = Carbon::createFromFormat('Y-m-d H:i:s', $solicitud->created_at); ?>
												Fecha solicitud : <?php echo $date->formatLocalized('%d %B %Y') ?>
											</span><br><br>

											<span style="font-size: 18px"><b><?php echo ucfirst($solicitud->name) ?></b></span><br>
											<a href="mailto:<?php echo $solicitud->email ?>"><?php echo $solicitud->email ?></a><br>
											<a href="tel:<?php echo $solicitud->phone ?>"><?php echo $solicitud->phone ?></a>
			  							</td>
		  							</tr>
		  						</table>
		  					</td>
		  				</tr>
				  		<tr>
						  	<td class="innerpadding borderbottom">
			                	<table  align="left" border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin: 0 auto;">  
			                		<tr>
			                			<td>
			                				<table class="col425" width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 20px; margin: 0 auto;">
			                					<tr>
			                						<td class="bodycopy" style="color: #000">
			                							<table style="width: 100%; border: 1px double #e8e8e8;">
			                								<tr>
			                									<th style="text-align: center ;border: 1px double #e8e8e8; padding: 15px 10px">Producto</th>
			                									<th style="text-align: center ;border: 1px double #e8e8e8; padding: 15px 10px">Cantidad</th>
			                									<th style="text-align: center ;border: 1px double #e8e8e8; padding: 15px 10px">Precio</th>
			                								</tr>
			                								<?php $total = 0; ?>
			                								<?php foreach ($productos as $key => $producto): ?>
																<?php $dataProduct = explode(' ', $producto->name); ?>								
			                									<tr>
			                										<td style="text-align: left;border: 1px double #e8e8e8; padding: 10px">
			                											<b><?php if ($dataProduct[0] != " "): ?>
			                												<?php echo $dataProduct[0] ?>
			                											<?php endif ?>
																		<?php echo $dataProduct[1] ?> <?php echo $dataProduct[2] ?></b>
																		<br>
																		<?php echo $dataProduct[3] ?> <?php echo $dataProduct[4] ?>
																		<?php if (isset($dataProduct[5]) ): ?>
																			<?php echo $dataProduct[5] ?>
																		<?php endif ?>
																		<?php if (isset($dataProduct[6]) ): ?>
																			<?php echo $dataProduct[6] ?>
																		<?php endif ?>
																		<?php if (isset($dataProduct[7]) ): ?>
																			<?php echo $dataProduct[7] ?>
																		<?php endif ?>
																		<?php if (isset($dataProduct[8]) ): ?>
																			<?php echo $dataProduct[8] ?>
																		<?php endif ?>
																		<?php if (isset($dataProduct[9]) ): ?>
																			<?php echo $dataProduct[9] ?>
																		<?php endif ?>
																		<?php if (isset($dataProduct[10]) ): ?>
																			<?php echo $dataProduct[10] ?>
																		<?php endif ?>
																		<?php if (isset($dataProduct[11]) ): ?>
																			<?php echo $dataProduct[11] ?>
																		<?php endif ?>
																		<?php if (isset($dataProduct[12]) ): ?>
																			<?php echo $dataProduct[12] ?>
																		<?php endif ?>
																		<?php if (isset($dataProduct[13]) ): ?>
																			<?php echo $dataProduct[13] ?>
																		<?php endif ?>
																		<?php if (isset($dataProduct[14]) ): ?>
																			<?php echo $dataProduct[14] ?>
																		<?php endif ?>
																		<br>
																		<b style="font-size: 18px;">Inicio: <?php echo Carbon::createFromFormat('Y-m-d', $solicitud->start)->formatLocalized('%d-%B-%Y') ?></b>
																		<br>
																		<b style="font-size: 18px;">Fin: <?php echo Carbon::createFromFormat('Y-m-d', $solicitud->finish)->formatLocalized('%d-%B-%Y') ?></b>
			                										</td>
			                										<td style="text-align: center;border: 1px double #e8e8e8; padding: 10px">
			                											
			                												<b><?php echo $dataProduct[0] ?></b>
			                											
			                										</td>
			                										<td style="text-align: center;border: 1px double #e8e8e8; padding: 10px">
			                											<?php if( $producto->price == 0 ){ $producto->price = 0; }?>
			                											<b><?php echo number_format($producto->price, 2,',','.'); ?>€ <span style="color: red">*</span></b>
			                										</td>
			                									</tr>
			                									<?php $total += $producto->price; ?>
			                								<?php endforeach ?>
			                								<?php $totalSinIva = $total - (0.21 * $total); ?>
															<tr>
			                									<td style="text-align: left ;border: 1px double #e8e8e8; padding: 15px 10px" colspan="2">
			                										Subtotal:
			                									</td>
			                									<td style="text-align: center ;border: 1px double #e8e8e8; padding: 15px 10px">
																	<?php echo number_format($totalSinIva, 2,',','.'); ?>€
			                									</td>
			                								</tr>
			                								<tr>
			                									<td style="text-align: left ;border: 1px double #e8e8e8; padding: 15px 10px" colspan="2">
			                										Impuestos:
			                									</td>
			                									<td style="text-align: center ;border: 1px double #e8e8e8; padding: 15px 10px">
																	<?php echo number_format((0.21 * $total), 2,',','.'); ?>€
			                									</td>
			                								</tr>
			                								<tr>
			                									<td style="text-align: left ;border: 1px double #e8e8e8; padding: 15px 10px" colspan="2">
			                										Total:
			                									</td>
			                									<td style="text-align: center ;border: 1px double #e8e8e8; padding: 15px 10px">

																	<?php echo number_format($total, 2,',','.'); ?>€
			                									</td>
			                								</tr>
			                							</table>			                							
			                						</td>
			                					</tr>

			                				</table>
			                			</td>
			                		</tr>
			                	</table>
						      </td>
					  	</tr>
					  	<tr>
							<td class="footer">
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td align="center" style="color: #000">
											* En breve te enviaremos el precio de los servicios que has solicitado, también puedes llamarnos al <a href="tel:958480168">958480168</a>. Gracias!
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td class="footer" bgcolor="#3F51B5">
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td align="center" class="footercopy" style="color: #FFF">
											© Copyright <?php echo date('Y') ?> <span style="color: white;">apartamentosierranevada.net</span>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</body>
</html>