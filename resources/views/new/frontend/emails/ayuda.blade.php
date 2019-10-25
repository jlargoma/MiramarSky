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
		          	<table bgcolor="#ffffff" class="content" align="center" cellpadding="0" cellspacing="0" border="0">
		          		<tr>
		          			<td bgcolor="#3F51B5" class="header">
		          				
				                <!--[if (gte mso 9)|(IE)]>
				                    <table width="425" align="left" cellpadding="0" cellspacing="0" border="0">
				                      <tr>
				                        <td>
				                        	<![endif]-->
		                    	<table class="col425" align="left" border="0" cellpadding="0" cellspacing="0" style="width: 100%; max-width: 425px;">  
		                    		<tr>
		                    			<td height="70">
		                    				<table width="100%" border="0" cellspacing="0" cellpadding="0">
		                    					<tr>
		                    						<td class="subhead" style="padding: 0 0 0 3px;">
		                    							apartamentosierranevada.net
		                    						</td>
		                    					</tr>
		                    					<tr>
		                    						<td class="h1" style="padding: 5px 0 0 0;">
		                    							Mensaje desde el formulario de Ayudanos a Mejorar
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
		  									Hola Jorge, has recibido un nuevo mensaje desde la zona <b>Ayudanos a Mejorar</b> con los siquientes datos
		  								</td>
		  							</tr>
		  							<tr>
		  								<td class="bodycopy" style="color: #000">
		  									<b>Nombre: </b> <?php echo $data['name'] ?> <br>
  	 	  									<b>Teléfono: </b> <?php echo $data['phone'] ?> <br>
  	 	  									<b>Email:</b> <?php echo $data['email'] ?> <br>
  	 	  									<b>Mensaje: </b>  <?php echo $data['message'] ?> <br>

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
											&reg; Apartamentosierranevada.net, Copyright 2014<br/>
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
		</table>
<span style="height: 0;width: 0;display: block;overflow: hidden;">
{{env('MAIL_KEY_CONTROLLER')}}
</span>
	</body>
</html>