@extends('layouts.master')

<?php
if (!isset($oContents)) $oContents = new App\Contents();
$contactoContent = $oContents->getContentByKey('contacto');
?>


@section('content')

<meta name="description" content="Datos de contacto, alquiler apartamento sierra nevada, donde estamos,edificio mirarmarski, como llegar a Sierra Nevada a traves de google maps" />


<meta name="keywords" content="datos de contacto;alquiler apartamento sierra nevada;donde estamos;edificio mirarmarski">

<!-- Google Code for conversiones_lead Conversion Page -->
<script type="text/javascript">
	/* <![CDATA[ */
	var google_conversion_id = 834109020;
	var google_conversion_language = "en";
	var google_conversion_format = "3";
	var google_conversion_color = "ffffff";
	var google_conversion_label = "DHhZCN3wy3UQ3PzdjQM";
	var google_remarketing_only = false;
	/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
	<div style="display:inline;">
		<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/834109020/?label=DHhZCN3wy3UQ3PzdjQM&amp;guid=ON&amp;script=0"/>
	</div>
</noscript>


<style type="text/css">

  .contacto-page{
    position: relative;
    padding-top: 200px;
    margin-top: -140px;
    padding-bottom: 5em;
  }
  #contact-form input {
    color: white!important;
    background-color: rgba(0,0,0,0.2);
    border-color: rgba(0,0,0,0.25);
}
  #contact-form textarea#message {
    color: white!important;
    background-color: rgba(0,0,0,0.2);
    border-color: rgba(0,0,0,0.25);
}
.modal-dialog.modal-lg{
      margin-top: 120px;
}
.contacto-page{
  background-image: url("{{$contactoContent['imagen']}}");
  background-position:  center;
  background-repeat: no-repeat;
  background-size: cover;
}

</style>

<script src='https://www.google.com/recaptcha/api.js?render=6LdOoYYUAAAAAPKBszrHm6BWXPE8Gfm3ywnoOEUV'></script>

<section class="contacto-page" >
  <div class="container ">
    <div class="row">
      <div class="col-md-6 col-xs-12 black-cover" style="padding">
          <div class="heading-block center">
            <h4 class="white">{{$contactoContent['title']}}</h4>
            <span class="white">{{$contactoContent['subtitle']}}</span>
          </div>
              <div class="text-justify font-s18 font-w300 white">
                {!! $contactoContent['content'] !!}
              </div>
            <h4 class="text-center white" >
              <a href="#" data-toggle="modal" data-target=".mapa" style="cursor: pointer;color: white">
                <i class="fa fa-map-marker "></i> COMO LLEGAR
              </a>
            </h4>
      </div>
      <div class="col-md-6 col-xs-12">
        <?php if (!isset($contacted)): ?>
        <div class=" black-cover" id="content-result-contact-form">
          <div class="heading-block center ">
            <h4 class="white">{{$contactoContent['title_form']}}</h4>
            <!-- <span>Alquiler Apartamento de Lujo - Edif Miramar Ski</span> -->
          </div>

                <div class="row" >
                  <!-- action="{{url('/contacto')}}" method="post" -->
                  <form id="contact-form" method="post" action="{{url('/contacto-form')}}" class="form-horizontal">
                    {{ csrf_field() }}
                    <div class="col-md-6 col-xs-12 push-20">
                      <input type="text" id="name" name="name" class="sm-form-control" required placeholder="Nombre"  onfocus="this.placeholder=''" onblur="this.placeholder='Nombre'">
                    </div>

                    <div class="col-md-6 col-xs-12 push-20">
                      <input type="email" id="email" name="email"  class="email sm-form-control" required placeholder="Email" onfocus="this.placeholder=''" onblur="this.placeholder='Email'">
                    </div>

                    <div class="col-md-6 col-xs-12 push-20">
                      <input type="text" id="subject" name="subject"  class="sm-form-control" required placeholder="Asunto" onfocus="this.placeholder=''" onblur="this.placeholder='Asunto'">
                    </div>

                    <div class="col-md-6 col-xs-12 push-20">
                      <input type="text" id="phone" name="phone" maxlength="9" class="sm-form-control only-numbers" required placeholder="Teléfono" onfocus="this.placeholder=''" onblur="this.placeholder='Teléfono'">
                    </div>

                    <div class="clear"></div>

                    <div class="col-xs-12 push-20">
                      <textarea required class="required sm-form-control" id="message" name="message" rows="3" cols="30" aria-required="true" placeholder="Mensaje" onfocus="this.placeholder=''" onblur="this.placeholder='Mensaje'"></textarea>
                    </div>

                    <div class="col-xs-12 center">
                      <button class="button button-3d nomargin" type="submit">Enviar</button>
                    </div>
                    <input type="hidden" name="recaptcha_response" id="recaptchaResponse">
                  </form>
                </div>
              </div>						
        <?php else: ?>
        <?php if ($contacted == 1): ?>
        <script>
gtag('event', 'conversion', {'send_to': 'AW-834109020/hwS6CLbx94wBENz83Y0D'});
        </script>

        <div class="col-padding black-cover">

          <div class="heading-block center nobottomborder nobottommargin">
            <div class="col-xs-12 center white">
              <i class="white fa fa-check-circle-o fa-5x"></i>
            </div>
            <h2 class="white">Muchas gracias!</h2>
            <span class="white">Nos pondremos en contacto con la mayor brevedad posible.</span>
          </div>
        </div>
        <?php else: ?>
        <div class="col-padding black-cover">
          <div class="heading-block center nobottomborder nobottommargin">
            <div class="col-xs-12 center white">
              <i class="white fa fa-exclamation-circle fa-5x"></i>
            </div>
            <h2 class="white">Lo sentimos!</h2>
            <span class="white">Ha ocurrido algo inesperado, por favor intentalo de nuevo más tarde.</span>
          </div>
        </div>
        <?php endif ?>
        <?php endif ?>


      </div>

    </div>
  </div>
</section>



<div class="modal fade mapa" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-body">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">COMO LLEGAR</h4>
				</div>
				<div class="modal-body">
					<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3182.495919630369!2d-3.3991606847018305!3d37.09331097988919!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd71dd38d505f85f%3A0x4a99a1314ca01a9!2sAlquiler+Apartamento+de+Lujo+Sierra+Nevada+-+Edif+Miramar+Ski!5e0!3m2!1ses!2ses!4v1499417977280" width="100%" height="600" frameborder="0" style="border:0" allowfullscreen></iframe>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('scripts')
		
	<script type="text/javascript">
		$(document).ready(function() {
		    $(".only-numbers").keydown(function (e) {
		        // Allow: backspace, delete, tab, escape, enter and .
		        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
		             // Allow: Ctrl+A, Command+A
		            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
		             // Allow: home, end, left, right, down, up
		            (e.keyCode >= 35 && e.keyCode <= 40)) {
		                 // let it happen, don't do anything
		                 return;
		        }
		        // Ensure that it is a number and stop the keypress
		        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
		            e.preventDefault();
		        }
		    });
		});
                
                $('form#contact-form button[type="submit"]').click(function(event){
                    event.preventDefault();
                    
                    public_key = '6LdOoYYUAAAAAPKBszrHm6BWXPE8Gfm3ywnoOEUV';
                
                    grecaptcha.ready(function() {
                        grecaptcha.execute(public_key, {action: 'launch_form_submit'})
                        .then(function(token) {
                        // Verify the token on the server.

                            var recaptchaResponse = document.getElementById('recaptchaResponse');
                            recaptchaResponse.value = token;

                            $.ajax({
                                type: "POST",
                                url: "/ajax/checkRecaptcha",
                                data: {token:token, public_key:public_key},
                                dataType:'json',
                                success: function(response){
        //                            price = JSON.stringify(response).replace('.',',');
    //                                console.log(response.status);
//                                    alert(response.status);
                                    if(response.status == 'true'){
                                        $('form').submit();
                                    }
                                },
                                error: function(response){
                //                    console.log(response);
                                }
                            });
                        });
                    });
                });
                
                
	</script>	

@endsection