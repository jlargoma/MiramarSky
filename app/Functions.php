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
    $aux = strtotime('first day of '.$startYear);
    while ($diff>0){
        switch ($name){
            case 'short':
                $lstMonths[date($format,$aux)] =['m' => date('n',$aux), 'y' => date('y',$aux),'name'=> getMonthsSpanish(date('n',$aux))];
                break;
            case 'long':
                $lstMonths[date($format,$aux)] =['m' => date('n',$aux), 'y' => date('y',$aux),'name'=> getMonthsSpanish(date('n',$aux),false)];
                break;
            default :
                $lstMonths[date($format,$aux)] =['m' => date('n',$aux), 'y' => date('y',$aux)];
                break;
                
        }
      $aux = strtotime('next month', $aux);
      $diff--;
    }
  }
  
  return $lstMonths;
}

function getMonthsSpanish($m,$min=true,$list=false){
  if ($min){
    $arrayMonth = ['','Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sept', 'Oct', 'Nov', 'Dic'];
  } else {
    $arrayMonth = ['','Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
  }
  if ($list)return $arrayMonth;
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
  $lng = App\Settings::getLenguaje(strtoupper($lng));
  
  if ($lng == 'es'){
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
  if (env('APP_APPLICATION') == "miramarLocal"){
    $urlPay = route('front.payments',$token);
  } else {
    $urlPay = 'https://miramarski.com/payments-forms?t='.$token;
  }
  
  return $urlPay;
}

function getCloudfl($url){
  
  return $url;
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

  function convertDateToShow($date,$yearsComplete=false){
    $date= trim($date);
    if ($date){
      
      $aux = explode('-',$date);
      if (is_array($aux) && count($aux)==3){
        if ($yearsComplete) return $aux[2].'/'.$aux[1].'/'.$aux[0];
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
/*  $confFile = [
    'TEST' => [
        'name' => 'Apto Test 1',
        'roomtype_id' => 46964,
        'rooms' => [
            [
                'channel' => 1, //booking.com
                'propID' => 123456789,
                'roomID' => 12345678901,
                'rateID' => 123456789992,
                'name' => 'booking'
            ],
            [
                'channel' => 2, //Expedia
                'propID' => 123456789,
                'roomID' => 12345678902,
                'rateID' => 123456789992,
                'name' => 'Expedia'
            ]
        ],
    ],
    'TEST_2' => [
        'name' => 'Apto Test 2',
        'roomtype_id' => 46965,
        'rooms' => [
            [
                'channel' => 1, //booking.com
                'propID' => 1234567,
                'roomID' => 123456701,
                'rateID' => 123456789992,
                'name' => 'booking'
            ],
            [
                'channel' => 2, //Expedia
                'propID' => 12345,
                'roomID' => 1234501,
                'rateID' => 123456789992,
                'name' => 'Expedia'
            ]
        ],
    ],
  ];
  
  return json_decode(json_encode($confFile));
*/
  
  $confFile = Illuminate\Support\Facades\File::get(storage_path('app/config/zodomus'));
  return json_decode($confFile);
}

function calcNights($start,$end) {
  $date1 = date_create_from_format('Y-m-d', date('Y-m-d', strtotime($start)));
  $date2 = date_create_from_format('Y-m-d', date('Y-m-d', strtotime($end)));
  $diff = date_diff($date1, $date2);
  return $diff->days;
  
  return intval(ceil((strtotime($end)-strtotime($start))/(24*60*60)));
}
function moneda($mount,$cero=true,$decimals=0){
  if ($cero)  return number_format($mount, $decimals, ',', '.' ).' €';
  
  if ($mount != 0) return number_format($mount, $decimals, ',', '.' ).' €';
  return '--';
  
}
function numero($mount,$cero=true,$decimals=0){
  if ($cero)  return number_format($mount, $decimals, ',', '.' );
  
  if ($mount != 0) return number_format($mount, $decimals, ',', '.' );
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
  
  /**
 * Format a String to Slug
 * @param type $string
 * @return type
 */
function clearTitle($string){
  $string = str_replace(
      array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
      array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
      $string
  );
  $string = str_replace(
      array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
      array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
      $string );
  $string = str_replace(
      array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
      array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
      $string );
  $string = str_replace(
      array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
      array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
      $string );
  $string = str_replace(
      array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
      array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
      $string );
  $string = str_replace(
      array('ñ', 'Ñ', 'ç', 'Ç'),
      array('n', 'N', 'c', 'C'),
      $string
  );
  return str_replace(' ','-', strtolower($string));
}

function show_isset($array,$index){
  if(isset($array[$index])){
    echo $array[$index];
  }
}

function value_isset($array,$index){
  if(isset($array[$index])){
    echo 'value="'.$array[$index].'"';
  } else {
    echo 'value=""';
  }
}
function colors(){
  return ['#9b59ff','#295d9b','#10cfbd','red','#871282','#066572','#a7dae7','#1fa7c0','#b2d33d','#3aaa49'];
}

function printColor($id){
  $lst = colors();
  $count = count($lst);
  if ($id<$count) return $lst[$id];
  
  $id = $id/$count;
  return $lst[$id];
}


function convertBold($detail){
  $detail = trim(nl2br($detail));
  if (!$detail || trim($detail) == '') return '';
  $start = false;
  $aDetail = explode('*', $detail);
  $result = '';
  if ($detail[0] == '*'){
    $result = '<b>';
    $start =  true;
  }
  if (count($aDetail)>0){
    foreach ($aDetail as $v){
      if ($v == "") continue;
      $result .= $v;
      if ($start)  $result .= '</b>';
      else  $result .= '<b>';
      
      $start = !$start;
    }
  }
  
  if ($start)  $result .= '</b>';
  return $result;
    
}

function removeIVA($price,$iva){
  if (!$price || !$iva) return 0;
  return round($price / (1 + $iva/100),2);
}

function whatsappFormat($texto){
      $texto = nl2br($texto);
      $whatsapp = str_replace('&nbsp;', ' ', $texto);
      $whatsapp = str_replace('<strong>', '*', $whatsapp);
      $whatsapp = str_replace('</strong>', '*', $whatsapp);
      $whatsapp = str_replace('<br />', '%0D%0A', $whatsapp);
      $whatsapp = str_replace('</p>', '%0D%0A', $whatsapp);
      $whatsapp = strip_tags($whatsapp);
      return $whatsapp;
}

function whatsappUnFormat($text){
      $string = htmlentities($text, null, 'utf-8');
      $content = str_replace("&nbsp;", " ", $string);
      $text = html_entity_decode($content);
      $text = str_replace(' *', ' <b>', $text);
      $text = str_replace("* ", '</b> ', $text);
      $text = nl2br($text);
      $text = str_replace('*', ' <b>', $text);
      return $text;
}
function firstDayMonth($year,$month){
  // First day of a specific month
  $d = new \DateTime($year . '-' . $month . '-01');
  $d->modify('first day of this month');
  return $d->format('Y-m-d');
}
function lastDayMonth($year,$month){
      // last day of a specific month
      $d = new \DateTime($year . '-' . $month . '-01');
      $d->modify('last day of this month');
      return $d->format('Y-m-d');
}
function configOtasAptosName(){
  $otaConfig = new \App\Services\OtaGateway\Config();
  return  $otaConfig->getRoomsName();
}
function get_shortlink($url){
  $sS_urls = new \App\Services\ShortUrlService();
  return $sS_urls->create($url);
}