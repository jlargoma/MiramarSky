<?php

function desencriptID($text){

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
    
    return $id/217;
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
    return $result;
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