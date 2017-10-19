<?php use \Carbon\Carbon;  setlocale(LC_TIME, "ES"); setlocale(LC_TIME, "es_ES");Use \Crypt\decrypt; ?>

Hola <?php echo $user->name ?><br><br>


Tus datos de Acceso son:<br>
Usuario: <?php echo $user->email ?>.<br>
Password: <?php echo Crypt::decrypt($user->password); ?>
Fechas: <b><?php echo Carbon::createFromFormat('Y-m-d h:i:s',$book->start)->format('d-M') ?> - <?php echo Carbon::createFromFormat('Y-m-d h:i:s',$book->finish)->format('d-M') ?></b> <br><br>

Gestiona la reserva desde : <a href="www.apartamentosierranevada.net/admin">www.apartamentosierranevada.net/admin</a>