<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<style type="text/css">
			body {margin: 0; padding: 0; min-width: 100%!important;}
			img {height: auto;}
			.content {width: 100%; max-width: 600px;}
			.header {padding: 40px 30px 20px 30px;}
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
			    <!--[if (gte mso 9)|(IE)]>
		      		<table width="600" align="center" cellpadding="0" cellspacing="0" border="0">
			        	<tr>
			          		<td>
	          	<![endif]-->    
	          	<?php if ($admin == 0): /* Cliente */ ?>
  	 	          	<table bgcolor="#ffffff" class="content" align="center" cellpadding="0" cellspacing="0" border="0">
  	 	          		<tr>
  	 	          			<td bgcolor="#3F51B5" class="header">
  	 	                    	<table class="col425" align="left" border="0" cellpadding="0" cellspacing="0" style="width: 100%; max-width: 425px;">  
  	 	                    		<tr>
  	 	                    			<td height="70">
  	 	                    				<table width="100%" border="0" cellspacing="0" cellpadding="0">
  	 	                    					<tr>
  	 	                    						<td class="subhead" style="padding: 0 0 0 3px; color: white;">
  	 	                    							apartamentosierranevada.net
  	 	                    						</td>
  	 	                    					</tr>
  	 	                    					<tr>
  	 	                    						<td class="h1" style="padding: 5px 0 0 0;">
  	 	                    							Solicitud de reserva
  	 	                    						</td>
  	 	                    					</tr>
  	 	                    				</table>
  	 	                    			</td>
  	 	                    		</tr>
  	 	                    	</table>
  	 	          				<!--[if (gte mso 9)|(IE)]>
  	 				                	</td>
  	 				              	</tr>
  	 				          	</table>
  	 				          	<![endif]-->
  	 	      				</td>
  	 	  				</tr>
  	 	  				<tr>
  	 	  					<td class="innerpadding borderbottom">
  	 	  						<table width="100%" border="0" cellspacing="0" cellpadding="0">
  	 	  							<tr>
  	 	  								<td class="h2" style="color: #3F51B5">
  	 	  									Hola <b><?php echo $data->customer->name ?></b>, hemos recibido tu solicitud  con los siguientes datos<br><br>
  	 	  									<b>Nos pondremos en contacto contigo lo antes posible, para informarte sobre la disponibilidad para tus fechas elegidas.</b>
  	 	  								</td>
  	 	  							</tr>
  	 	  							<tr>
  	 	  								<td class="bodycopy" style="color: #000">
											Nombre: 			<b><?php echo $data->customer->name ?></b> <br>
											Teléfono:  			<b><a href="tel:<?php echo $data->customer->phone ?>"><?php echo $data->customer->phone ?></a></b> <br>
											Apartamento: <b><?php echo $data->room->sizeRooms->name ?> // <?php echo ($data->type_luxury == 1)? "Lujo" : "Estandar" ?></b><br>
											Email: 				<b><?php echo $data->customer->email ?></b> <br>
											Fecha Entrada: 		<b><?php echo date('d-m-Y', strtotime($data->start)) ?></b> <br>
											Fecha Salida: 		<b><?php echo date('d-m-Y', strtotime($data->finish)) ?></b> <br>
											Noches:  			<b><?php echo $data->nigths ?></b> <br>
											Ocupantes: 			<b><?php echo $data->pax ?></b> <br>
											Suplemento parking: <b><?php echo number_format($data->sup_park,0,'','.') ?> €</b> <br>
											Suplemento lujo: 	<b><?php echo number_format($data->sup_lujo,0,'','.') ?> €</b> <br>
											Comentarios: 		<b><?php echo $data->comment ?> € </b><br>

  	 	  									<h2 style="text-align: left">
  	 	  										<b>Precio total: </b>  <?php echo number_format($data->total_price,0,'','.') ?> €
  	 	  									</h2> <br>

  	 	  								</td>
  	 	  							</tr>
  	 	  						</table>
  	 	  					</td>
  	 	  				</tr>
  	 			  		<tr>
  	 					  	<td class="innerpadding borderbottom">
  	 		                	<table class="col380" align="left" border="0" cellpadding="0" cellspacing="0" style="width: 100%;">  
  	 		                		<tr>
  	 		                			<td>
  	 		                				<table width="100%" border="0" cellspacing="0" cellpadding="0">
  	 		                					<tr>
  	 		                						<td class="bodycopy" style="color: #000">
  	 		                							Gracias por confiarnos tus vacaciones, haremos todo lo posible para que pases unos días agradables. <br><br>

  	 		                							<a href="//apartamentosierranevada.net">apartamentosierranevada.net</a>
  	 		                						</td>
  	 		                					</tr>

  	 		                				</table>
  	 		                			</td>
  	 		                		</tr>
  	 		                	</table>
  	 					      </td>
  	 				  	</tr>
  	 					<tr>
  	 						<td class="innerpadding bodycopy" style="color: #000">
  	 							Cualquier duda, por favor no dude en ponerse en contacto con nosotros, estaremos encantados de poder ayudarle.
  	 						</td>
  	 					</tr>
  	 					<tr>
  	 						<td class="footer" bgcolor="#3F51B5">
  	 							<table width="100%" border="0" cellspacing="0" cellpadding="0">
  	 								<tr>
  	 									<td align="center" class="footercopy" style="color: #FFF">
  	 										&reg; Apartamentosierranevada.net, Copyright <?php echo  date('Y'); ?><br/>
  	 									</td>
  	 								</tr>
  	 							</table>
  	 						</td>
  	 					</tr>
 					</table>
          	 	<?php else: /* Admin */?>
	          	 	
		          	<table bgcolor="#ffffff" class="content" align="center" cellpadding="0" cellspacing="0" border="0">
		          		<tr>
		          			<td bgcolor="#3F51B5" class="header">
		                    	<table class="col425" align="left" border="0" cellpadding="0" cellspacing="0" style="width: 100%; max-width: 425px;">  
		                    		<tr>
		                    			<td height="70">
		                    				<table width="100%" border="0" cellspacing="0" cellpadding="0">
		                    					<tr>
		                    						<td class="subhead" style="padding: 0 0 0 3px; color: white;">
		                    							apartamentosierranevada.net
		                    						</td>
		                    					</tr>
		                    					<tr>
		                    						<td class="h1" style="padding: 5px 0 0 0;">
		                    							Solicitud de reserva
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
		  									Detalles de la reserva:<br><br>
		  									<b>Nombre: </b> <?php echo $data->customer->name ?> <br>
  	 	  									Teléfono:  			<b><a href="tel:<?php echo $data->customer->phone ?>"><?php echo $data->customer->phone ?></a></b> <br>
											Apartamento: <b><?php echo $data->room->sizeRooms->name ?> // <?php echo ($data->type_luxury == 1)? "Lujo" : "Estandar" ?></b><br>
  	 	  									<b>Email:</b> <?php echo $data->customer->email ?> <br>
  	 	  									<b>Fecha Entrada:</b> <?php echo date('d-m-Y', strtotime($data->start)) ?> <br>
  	 	  									<b>Fecha Salida:</b> <?php echo date('d-m-Y', strtotime($data->finish)) ?> <br>
  	 	  									<b>Noches: </b> <?php echo $data->nigths ?> <br>
  	 	  									<b>Ocupantes: </b> <?php echo $data->pax ?> <br>
  	 	  									<b>Suplemento parking: </b> <?php echo $data->sup_parking ?> €<br>
  	 	  									<b>Suplemento lujo:  <?php echo $data->sup_lujo ?> €</b><br>
  	 	  									<b>Comentarios: </b>  <?php echo $data->comment ?> € <br>

		  									<h2 style="text-align: left;"><b>Precio total: </b> <?php echo $data->total_price ?> €</h2> <br>

		  								</td>
		  							</tr>
		  						</table>
		  					</td>
		  				</tr>
				  		<tr>
						  	<td class="innerpadding borderbottom">
					          	<!--[if (gte mso 9)|(IE)]>
						            <table width="380" align="left" cellpadding="0" cellspacing="0" border="0">
						              <tr>
						                <td>
			                	<![endif]-->
			                	<table align="left" border="0" cellpadding="0" cellspacing="0" style="width: 100%;">  
			                		<tr>
			                			<td>
			                				<table width="100%" border="0" cellspacing="0" cellpadding="0">
			                					<tr>
			                						<td class="bodycopy" style="color: #000">
			                							Asigna y gestiona esta reserva desde la zona de administración haciendo clic en el siguiente enlace<br><br>

			                							<a href="https://apartamentosierranevada.net/admin" target="_blank">apartamentosierranevada.net/admin</a>
			                						</td>
			                					</tr>

			                				</table>
			                			</td>
			                		</tr>
			                	</table>
				          		<!--[if (gte mso 9)|(IE)]>
						                </td>
				              		</tr>
				          		</table>
					          	<![endif]-->
						      </td>
					  	</tr>
						<tr>
							<td class="footer" bgcolor="#3F51B5">
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td align="center" class="footercopy" style="color: #FFF">
											&reg; Apartamentosierranevada.net, Copyright <?php echo date('Y') ?><br/>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>

				<?php endif ?> 
	    		<!--[if (gte mso 9)|(IE)]>
	          			</td>
	        		</tr>
	    		</table>
	    		<![endif]-->
				</td>
			</tr>
		</table>
	</body>
</html>