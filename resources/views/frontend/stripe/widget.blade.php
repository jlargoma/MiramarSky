<html>
    <head>
        <title>Confirmación de Pago</title>
    <?php if (config('app.env') == 'production'): ?>
      <meta name="description" content="Pago desde widget WEB">
    <?php else: // PRODUCTION ?>
      <meta name="description" content="Pago desde widget WEB - test">
    <?php endif; // PRODUCTION ?>
    </head>  
    <body style="text-align: center;">
      <h2 style="line-height: 1;padding-top: 2em; ">
        ¡Muchas gracias por confiar en nosotros!
      </h2>
      <p >
        Te enviaremos un email con la confirmación de tu reserva <br/>y los pasos a seguir.
      </p>
      <p style="margin-top: 4em;">
        Un saludo
      </p>
    </body>
        
</html>
