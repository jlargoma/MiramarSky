<?php

function desencriptID($text){

    $text = trim($text);
    $char_list = "GHIJKLMNOPQRSTUVWXYZ";
    $char_salt = "ABCDEFabcdef";
    $text_len = strlen($text);
    $result = "";

    for($i = 0; $i < $text_len; $i++)
    {
      if (strpos($char_salt, $text[$i]) !== FALSE){
        $result = $text[$i].$result;
      } else {
        $aux = strpos($char_list, $text[$i]);
        if ($aux > 9){
          $result = ($aux-10).$result;
        } else {
          $result = $aux.$result;
        }
      }
    }
    $id = hexdec($result);
    $cantControl = strlen($result);
    if (substr($text,-1) == $cantControl) return $id/217;
    if (substr($text,-2) == $cantControl) return $id/217;
    return 'null';
}

function encriptID($data){
    $text = strtoupper(dechex($data*217));
    $char_list = "GHIJKLMNOPQRSTUVWXYZ";
    $char_salt = "ABCDEFabcdef";
    $text_len = strlen($text);
    $result = "";

    for($i = 0; $i < $text_len; $i++)
    {
      if (strpos($char_salt, $text[$i]) !== FALSE){
        $result = $text[$i].$result;
      } else {
        if (($i%2) == 0){
          $result = $char_list[$text[$i]+10].$result;
        } else {
          $result = $char_list[$text[$i]].$result;
        }
      }
    }
    
    $length = strlen($result);
    $newVal = '';
    for ($i=0; $i<$length; $i++) {
      $newVal .= (rand(0, 117)). $result[$i];
    }
    return ($newVal).$length;
}

function assetV($uri){
  $uri_asset = asset($uri);
  $v = env('VERSION','v1.0.1');
  return $uri_asset.'?'.$v;
}

function lstMonths($startYear,$endYear,$format='ym'){
  $diff = $startYear->diffInMonths($endYear) + 1;
  $lstMonths = [];
  if (is_numeric($diff) && $diff>0){
    $aux = strtotime($startYear);
    while ($diff>0){
      $lstMonths[date($format,$aux)] =['m' => date('n',$aux), 'y' => date('y',$aux)];
      $aux = strtotime("+1 month", $aux);
      $diff--;
    }
  }
  
  return $lstMonths;
}

function getMonthsSpanish($m,$min=true){
  if ($min){
    $arrayMonth = ['','Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sept', 'Oct', 'Nov', 'Dic'];
  } else {
    $arrayMonth = ['','Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
  }
  return isset($arrayMonth[$m]) ? $arrayMonth[$m] : '';
}

function getUserIpAddr(){
    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
        //ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        //ip pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function translateSubject($text,$lng='es'){
  if (!$lng || strtolower($lng) == 'es'){
    return $text;
  }
  
  $texts = [
  'Bloqueo de reserva y datos de pago',
  'Correo de Reserva de Propietario',
  'Reserva denegada',
  'Confirmación de reserva (pago parcial)',
  'Solicitud disponibilidad',
  'Recordatorio Pago',
  'Confirmación de Pago',
  'Recordatorio para Completado de Partee'
  ];
  
  $trasl = [
    'Booking Request and payment details',
    'Correo de Reserva de Propietario',
    'unavailable request',
    'Booking confirmation (partial payment. done)',
    'Solicitud disponibilidad',
    'Second Payment Reminder',
    'Payment confirmation',
    'Reminder to Complete Police information'
  ];
  
  $text = trim($text);
  foreach ($texts as $k=>$t){
    if ($t == $text){
      return isset($trasl[$k]) ? $trasl[$k] : $text;
    }
  }
  
  return $text;
}