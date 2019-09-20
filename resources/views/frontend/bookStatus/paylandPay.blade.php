<!DOCTYPE html>
<html>
  <head>
    <title>Formulario de Pago</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    color: #fff;
    font-size: 2em;
      }
      .logo{
            width: 320px;
    display: block;
    margin: auto;
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
            <?php else:?>
                <a class="logo" href="/">
                    <img src="{{ assetV('img/miramarski/logo_miramar.png') }}" alt="miramarSki">
                </a>
            <?php endif; ?>
      <div class="form">
       <iframe src="{{ $urlPayland  }}" frameborder="0" style="width: 100%; min-height: 550px;"></iframe>
    </div>
    </div>

  </body>
</html>