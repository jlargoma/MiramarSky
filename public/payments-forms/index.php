<?php
//https://miramarski.com/payments-forms?t=1
$param = isset($_GET['t']) ? $_GET['t'] : 'null';
$url = 'https://apartamentosierranevada.net/payments-forms/'.$param;
echo file_get_contents($url);
?>