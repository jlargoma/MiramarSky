<?php use \Carbon\Carbon;  setlocale(LC_TIME, "ES"); setlocale(LC_TIME, "es_ES");
use Illuminate\Support\Facades\Crypt; ?>

Hola <?php echo $user->name ?><br><br>


Tus datos de Acceso son:<br>
Usuario: <?php echo $user->email ?>.<br>

Gestiona la reserva desde : <a href="www.apartamentosierranevada.net/admin">www.apartamentosierranevada.net/admin</a>