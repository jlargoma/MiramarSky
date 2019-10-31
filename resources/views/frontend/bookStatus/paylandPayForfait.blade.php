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
        left: 0;
        width: 100%;
      }
      .title{
          width: 100%;
          text-align: center;
          color: #fff;
          font-size: 3em;
          font-family: sans-serif;
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
      <h1 class="title">PAGO FORFAIT</h1>
      <div class="form">
       <iframe src="{{ $urlPayland  }}" frameborder="0" style="width: 100%; min-height: 550px;"></iframe>
    </div>
    </div>

  </body>
</html>