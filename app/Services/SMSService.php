<?php

namespace App\Services;

class SMSService
{
    public $response;
    public $code;
    protected   $SMS_URL;
    protected   $ENDPOINT;

    public function __construct()
    {
      $this->SMS_URL = env('SMS_BASE_URI');
      $this->ENDPOINT = $this->SMS_URL.'/sms/push/sendPush';
    }
    public function sendSMS( $msg,$phone){

      $phone = preg_replace('/[^0-9]+/', '', $phone); //just numbers
      
      if (empty(trim($phone))){
        $this->response = 'Phone number required';
        return FALSE;
      }
      // Post variables to add to the request.
      $push_simple = array(
          'idCliente' => env('SMS_ID'),
          'clave' => env('SMS_PSW'),
          'ruta' => env('SMS_RUTA'),
          'alfabeto' => 0, //GSM
          'remitente' => env('SMS_NAME'),
          'destinatarios' => $phone,
          'texto' => $msg,
          'wappush' => '0',
//          'internacional' => '',
      );
 
      //https://ws1.premiumnumbers.es/sms/push/sendPush?idCliente=99&-clave=kkk&remitente=test&destinatarios=600000000&texto=mensaje%20de%20texto&ruta=
      // CURL REQUEST FOR SIMPLE PUSH
      $url = $this->ENDPOINT;
      $param = [];
      foreach ($push_simple as $k => $d) {
        $param[] = "$k=".urlencode($d);
      }
      $url .= '?' . implode('&', $param);
      dd($url);
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url); 
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
      curl_setopt($ch, CURLOPT_HEADER, 0); 
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 7); //Timeout after 7 seconds
      curl_setopt($ch, CURLOPT_TIMEOUT, 10); //  CURLOPT_TIMEOUT => 10,
      $result = curl_exec($ch);
      $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      curl_close($ch);
            
      // CHECK THE REQUEST
      $this->response = 'No hay repuesta del servidor';
      if ($result){
        $aResult = explode('#',$result);
        if (is_array($aResult) && count($aResult)>1){
          $this->response = $aResult[1];
          if ($aResult[0] == 0){
            return true;
          }
        }
      }
      return FALSE;
    }
 
}