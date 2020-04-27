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

function getKeyControl($id){
  $aux = md5($id);
  return strtoupper(preg_replace('/[0-9]/','', $aux)).intval(preg_replace('/[a-z]/','', $aux));
}

function assetV($uri){
  $uri_asset = asset($uri);
  $v = env('VERSION','v1.0.1');
  return $uri_asset.'?'.$v;
}
function assetNew($uri){
  $uri_asset = asset('/new-asset/'.$uri);
  $v = env('VERSION','v1.1');
  return $uri_asset.'?'.$v;
}



function lstMonths($startYear,$endYear,$format='ym',$name=false){
  $diff = $startYear->diffInMonths($endYear) + 1;
  $lstMonths = [];
  if (is_numeric($diff) && $diff>0){
    $aux = strtotime($startYear);
    while ($diff>0){
      if ($name)
        $lstMonths[date($format,$aux)] =['m' => date('n',$aux), 'y' => date('y',$aux),'name'=> getMonthsSpanish(date('n',$aux))];
      else
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
  $m = intval($m);
  return isset($arrayMonth[$m]) ? $arrayMonth[$m] : '';
}

function listDaysSpanish($min = false) {
    if ($min) {
      $array = [
          1 => 'Lun', 
          2 => 'Mar', 
          3 => 'Mié', 
          4 => 'Jue', 
          5 => 'Vie',
          6 => 'Sáb',
          0 => 'Dom', 
          ];
    } else {
      $array = [
          1 => 'Lunes', 
          2 => 'Martes', 
          3 => 'Miércoles', 
          4 => 'Jueves', 
          5 => 'Viernes',
          6 => 'Sábado',
          0 => 'Domingo', 
          ];
    }
    return $array;
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

function getUrlToPay($token){
  if (env('APP_APPLICATION') == "riad" || env('APP_APPLICATION') == "miramarLocal"){
    $urlPay = route('front.payments',$token);
  } else {
    $urlPay = 'https://miramarski.com/payments-forms?t='.$token;
  }
  
  return $urlPay;
}

function getCloudfl($url){
  
  global $CDN_URL;
  
  if (!$CDN_URL){
    $CDN_URL = env('CDN_URL');
  }
  if (strpos($url, 'apartamentosierranevada.net')>0){
    $aux = parse_url($url);
    if (is_array($aux)){
      $return = $CDN_URL;
      if (isset($aux['path'])) $return .= $aux['path'];
      if (isset($aux['query'])) $return .= '?'.$aux['query'];
      return $return;
    }
  }

  return $CDN_URL.$url;
}

  function convertDateToShow($date){
    $date= trim($date);
    if ($date){
      
      $aux = explode('-',$date);
      if (is_array($aux) && count($aux)==3){
        return $aux[2].'/'.$aux[1].'/'.($aux[0]-2000);
      }
    }
    return null;
  }
  
  function convertDateToShow_text($date,$year = false){
    $date= trim($date);
    if ($date){
      
      $aux = explode('-',$date);
      if (is_array($aux) && count($aux)==3){
        if ($year)  return $aux[2].' '.getMonthsSpanish($aux[1]).', '.($aux[0]-2000);
        return $aux[2].' '.getMonthsSpanish($aux[1]);
      }
    }
    return null;
  }
  
  function convertDateToDB($date){
    $date= trim($date);
    if ($date){
      $aux = explode('/',$date);
      if (is_array($aux) && count($aux)==3){
        return $aux[2].'-'.$aux[1].'-'.$aux[0];
      }
    }
    return null;
  }
  function dateMin($date){
    $date= trim($date);
    if ($date){
      
      $aux = explode('-',$date);
      if (is_array($aux) && count($aux)==3){
        return $aux[2].' '.getMonthsSpanish(intval($aux[1]));
      }
    }
    return null;
  }
  
function paylandCost($val){
  if ($val>0)
    return round((1.2 * $val) / 100,2);
  
  return 0;
}

function urlBase(){
  return sprintf(
    "%s://%s%s",
    isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
    $_SERVER['SERVER_NAME'],
    $_SERVER['REQUEST_URI']
  );
}

function date_policies($date){
  $time = strtotime($date);
  
  return  date('d',$time).' '.
          getMonthsSpanish(date('n',$time)).
          ' del '.date('Y',$time).
          ' a las '.date('H:i',$time).' hrs.';
}

function ob_html_compress($buffer){
  
   $search = array(
        '/\>[^\S ]+/s',     // strip whitespaces after tags, except space
        '/[^\S ]+\</s',     // strip whitespaces before tags, except space
        '/(\s)+/s',         // shorten multiple whitespace sequences
        '/<!--(.|\s)*?-->/' // Remove HTML comments
    );

    $replace = array(
        '>',
        '<',
        '\\1',
        ''
    );

    $buffer = preg_replace($search, $replace, $buffer);

    return $buffer;
  
  return preg_replace(array('/<!--(.*)-->/Uis',"/[[:blank:]]+/"),array('',' '),str_replace(array("\n","\r","\t"),'',$buf));
    return str_replace(array("\n","\r","\t"),'',$buf);
}

function getArrayMonth($startYear,$endYear,$index=false){

  $diff = $startYear->diffInMonths($endYear) + 1;
  $thisMonth = date('m');
  
  $result = [];
  $aux = $startYear->format('n');
  $auxY = $startYear->format('y');
  $c_month = null;
  for ($i = 0; $i < $diff; $i++) {
      $c_month = $aux + $i;
      if ($c_month == 13) {
        $auxY++;
      }
      if ($c_month > 12) {
        $c_month -= 12;
      }
      $tMonth = $c_month;
      if ($tMonth<10) $tMonth = '0'.$tMonth;
      if($index) $result[$auxY.$tMonth] = ['y' => $auxY,'m'=> $tMonth];
        else $result[] = ['y' => $auxY,'m'=> $tMonth];
    }
  return $result;
}

function configZodomusAptos(){
//  $confFile = [
//    'TEST' => [
//        'name' => 'Apto Test 1',
//        'rooms' => [
//            [
//                'channel' => 1, //booking.com
//                'propID' => 123456789,
//                'roomID' => 12345678901,
//                'rateID' => 123456789992,
//                'name' => 'Single Room'
//            ],
//            [
//                'channel' => 1, //booking.com
//                'propID' => 123456789,
//                'roomID' => 12345678902,
//                'rateID' => 123456789992,
//                'name' => 'Suite'
//            ]
//        ],
//    ],
//      ];
//  
//  return json_decode(json_encode($confFile));
//  
//  
  
  $confFile = Illuminate\Support\Facades\File::get(storage_path('app/config/zodomus'));
  return json_decode($confFile);
}

function calcNights($start,$end) {
  return intval(ceil((strtotime($end)-strtotime($start))/(24*60*60)));
}
function moneda($mount,$cero=true,$decimals=0){
  if ($cero)  return number_format($mount, $decimals, ',', '.' ).' €';
  
  if ($mount != 0) return number_format($mount, $decimals, ',', '.' ).' €';
  return '--';
  
}
function getUsrRole(){
  global $uRole;
  
  if (isset($uRole) && !empty($uRole)) {
    return $uRole;
  }
  
  $uRole = Auth::user()->role;
  return $uRole;
}

 function convertDateTimeToShow_text($datetime,$year = false){
    $datetime= trim($datetime);
    if ($datetime){
      $time = strtotime($datetime);
      return date('d',$time).' '.getMonthsSpanish(date('n',$time)).', '.date('y H:i',$time)."hrs";
    }
    return null;
  }
  
  function noIndex(){
    $haystack = ['politica-privacidad','aviso-legal','politica-cookies','condiciones-contratacion'];
//    $haystack = ['fotos','contacto','politica-privacidad','aviso-legal','politica-cookies','condiciones-contratacion'];
    $pathRequest = Request::path(); 
    if (in_array($pathRequest, $haystack)){
      ?>
      <meta name="robots" content="noindex">
      <?php
    }

  }