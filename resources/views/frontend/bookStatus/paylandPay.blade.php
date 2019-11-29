<!DOCTYPE html>
<html>
  <head>
    <title>Formulario de Pago</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=100%, initial-scale=1.0">
    <style>
      .background{
        height: 100%;
        width: 100%;
        opacity: 0.75;
        position: fixed;
        left: 0;
        top: 0;
        background-image: url("{{ assetV('img/miramarski/lockscreen.jpg')}}");
        background-repeat: no-repeat;
        background-size: cover;
      }
      .contenedor{
        z-index: 99;
        position: absolute;
        top: 4em;
        left: 0;
        width: 100%;
      }
      .title{
        width: 100%;
        text-align: center;
        color: #fff;
        font-size: 3em;
      }
      .form{
        width: 100%;
        text-align: center;
        color: #828282;
        font-size: 2em;
        font-family: "Helvetica Neue", "Helvetica", Arial, sans-serif;
        background: rgba(255, 255, 255, 0.6) !important;
        color: #000;
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 0px;
        padding: 3em 0 6em 0;
        
      }

      .logo{
        width: 320px;
        display: block;
        margin: auto;
      }
      .msg-error{

      }
      ul{
        width: 100%;
        text-align: center;
        margin: 1em auto;
        padding: 0;
      }
      li{
        display: inline-block;
      }
      li.step {
        background-color: #d9e0e2;
        color: #496893;
        text-align: center;
        padding: 13px;
        border-radius: 48%;
        margin: 0;
        width: 20px;
      }
      li.step.active {
        background-color: #496893;
        color: #fff;
      }


      li.line{
        height: 4px;
        width: 7em;
        padding: 0;
        border-radius: inherit;
        padding-bottom: 4px;
        margin: 1em 0 0 0;
        border-bottom: 3px solid #496893;
      }
      li.line span{
        background-color: #b3cfe2;
        padding: 6px;
        color: #496893;
      }
      input#dni {
        padding: 6px;
        border: 1px solid #3f88b8;
        font-size: 1em;
        border-radius: 4px;
      }
      .m1{
        margin: 1em auto;
      }
      span.required {
        color: red;
      }
      button{
        padding: 12px;
        color: #eff2f3;
        background-color: #42648e;
        font-size: 1em;
        border: none;
        border-radius: 7px;
      }
      .alert-warning{
        display: none; 
        width: 320px;
        margin: 1em auto;
        padding: 10px;
        background-color: #e2d9aa;
        color: #b56700;
        font-weight: 400;
        border: 1px solid #cac090;
        border-radius: 7px;
      }
      input[type="checkbox" i] {
        width: 22px;
        height: 21px;
        position: absolute;
        border: 1px solid #3f88b8;
      }
      span.check{
        position: relative;
        width: 25px;
        display: inline-flex;
        height: 1em;
      }
      
      label.checkbox input[type="checkbox"] {display:none;}
label.checkbox span {
  display:inline-block;
  border:2px solid #BBB;
  border-radius:10px;
  width:25px;
  height:25px;
  background:#FF5E5E;
  vertical-align:middle;
  margin:3px;
  position: relative;
  transition:width 0.1s, height 0.1s, margin 0.1s;
}
label.checkbox :checked + span {
  background:#ACEAAC;
  width:27px;
  height:27px;
  margin: 2px;
}
label.checkbox :checked + span:after {
  content: '\2714';
  font-size: 20px;
  position: absolute;
  top: 2px;
  left: 5px;
  color: #4087b7;
}
.fs-2{
  font-size: 1.3em;
}
.loader {
    border: 10px solid #e0e0e0;
    border-top: 10px solid #3498db;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    margin: 15px auto;
    animation: spin 2s linear infinite;
    display: none;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
    </style>
  </head>
  <body>
    <div class="background"></div>
    <div class="contenedor">
      <?php if (env('APP_APPLICATION') == "riad"): ?>
        <a class="logo" href="/">
          <img src="{{ assetV('img/riad/logo_riad_b.png') }}" alt="Riad">
        </a>
      <?php else: ?>
        <a class="logo" href="/">
          <img src="{{ assetV('img/miramarski/logo_miramar.png') }}" alt="miramarSki">
        </a>
      <?php endif; ?>

      @if($request_dni)
      <div class="form black">
        <h3>TPV</h3>
        <ul>
          <li id="step_1" class="active step">1</li>
          <li class="line"><span>Pasos</span></li>
          <li id="step_2" class="step">2</li>
        </ul>
        <div class="">
          
          <div class=" fs-2">{{$dates}}</div>
          <h2>{{$room}}</h2>
          <div class="m1 fs-2">
            <label>Nombre:</label>
            {{$name}}
          </div>
        </div>
        <div id="form_step_1">


          <div class="m1">
            <label><span class="required">*</span>DNI:</label>
            <input type="text" id="dni" class="form-control required">
          </div>
          <div class="m1">
            <label class="checkbox">
              <input type="checkbox" id="tyc_1" >
              <span></span>
              Acepta las <a href="{{route('cond.contratacion')}}" title="Ir a políticas de contratación" target="_black">
                políticas de contratación
              </a>
            </label>
          </div>
          <div class="m1">
            <label class="checkbox">
              <input type="checkbox" id="tyc_2" >
              <span></span>
              Acepta las 
              <a href="{{route('cond.fianza')}}" title="Ir a condiciones de fianza" target="_black">
                condiciones de fianza
              </a>
            </label>
          </div>
          <div class="text-center">
            <input type="hidden" name="recaptcha_response" id="recaptchaResponse">
            <button class="btn btn-primary" title="Ir al paso 2" id="siguiente">Siguente</button>
          </div>
          <p class="alert alert-warning msg-error" ></p>
          <p class="loader"></p>
        </div>
        <div id="form_step_2" style="display:none">
          <iframe src="{{ $urlPayland  }}" frameborder="0" style="width: 100%; min-height: 550px;"></iframe>
        </div>
        <div id="recaptcha" class="g-recaptcha" data-sitekey="6Ld4Jh8TAAAAAD2tURa21kTFwMkKoyJCqaXb0uoK"></div>
      </div>
      @else
      <iframe src="{{ $urlPayland  }}" frameborder="0" style="width: 100%; min-height: 550px;"></iframe>
      @endif



    </div>
    @if($request_dni)
    <script  src="https://code.jquery.com/jquery-2.1.4.min.js"
             integrity="sha256-8WqyJLuWKRBVhxXIL1jBDD7SDxU936oZkCnxQbWwJVw="
    crossorigin="anonymous"></script>
    <script src="https://www.google.com/recaptcha/api.js?render=6LdOoYYUAAAAAPKBszrHm6BWXPE8Gfm3ywnoOEUV"></script>
    <script>
    $(function () {

      $('#siguiente').on('click', function () {
        var public_key = '6LdOoYYUAAAAAPKBszrHm6BWXPE8Gfm3ywnoOEUV';
        if (!$('#tyc_1').is(':checked')) {
          showError('Por favor, acepte las políticas de contratación para continuar');
          return;
        }
        if (!$('#tyc_2').is(':checked')) {
          showError('Por favor, acepte las condiciones de fianza para continuar');
          return;
        }

        var dni = $('#dni').val();
        if (isBlank(dni) || isEmpty(dni)) {
          showError('Por favor, complete su CIF, NIF ó DNI para continuar');
          return;
        }
        
        grecaptcha.ready(function () {
        grecaptcha.execute(public_key, {action: 'launch_form_submit'})
            .then(function (token) {
              // Verify the token on the server.

              var recaptchaResponse = document.getElementById('recaptchaResponse');
              recaptchaResponse.value = token;
              $('#siguiente').hide(500, function () {
                                $('.loader').show();
                                });
              

              $.ajax({
                type: "POST",
                url: "/ajax/checkRecaptcha",
                data: {token: token, public_key: public_key},
                dataType: 'json',
                success: function (response) {
                  if (response.status == 'true') {
                    
                    var token = '{{csrf_token()}}';
                    var data = {dni: dni, _token: token};
                    $.ajax({
                          url: '{{$urlSend}}',
                          data: data,
                          type: 'POST',
                          crossDomain: true,
//                          dataType: 'jsonp'
                        }).done( function(result) { 
                              if (result == 'ok') {
                                $('.loader').hide();
                                $('#step_1').removeClass('active');
                                $('#step_2').addClass('active');
                                $('#form_step_1').hide(500, function () {
                                  $('#form_step_2').show();
                                });

                              } else {
                                showError(result);
                                return;
                              }
                          }).fail(function(response) {
                            showError('Error de sistema'); 
                          });
                  }
                },
                error: function (response) {
                 showError('Error: por favor refresque la pantalla');
                }
              });
            });
          });  
        });  



        var showError = function (text) {
          $('.msg-error').text(text).fadeIn();
          $('.loader').hide(500, function () {
                                $('#siguiente').show();
                                });
          setTimeout(function () {
            $('.msg-error').text('').fadeOut();
          }, 3500);
        }
        function isBlank(str) {
          return (!str || /^\s*$/.test(str));
        }
        function isEmpty(str) {
          return (!str || 0 === str.length);
        }
    });
    </script>
    @endif
  </body>
</html>