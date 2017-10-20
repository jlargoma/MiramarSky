<?php use \Carbon\Carbon;  setlocale(LC_TIME, "ES"); setlocale(LC_TIME, "es_ES");
use Illuminate\Support\Facades\Crypt; ?>

Hola <?php echo $user->name ?><br><br>


Tus datos de Acceso son:<br>
Usuario: <?php echo $user->email ?>.<br>
Password: Ingresa <a href="https://www.apartamentosierranevada.net/password/reset">Aqui</a> para generarte una contraseña.

Gestiona tu seccion desde <a href="https://www.apartamentosierranevada.net/admin">Aqui</a>.