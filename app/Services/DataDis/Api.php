<?php

namespace App\Services\DataDis;

use App\Services\DataDis\Config as oConfig;
use Illuminate\Support\Facades\DB;
use DateTime;
use DateInterval;
use App\Services\LogsService;

class Api {

  public $response;
  public $responseCode;
  public $rooms;
  protected $token;
  protected $authorizedNif;
  protected $URL;
  protected $oConfig;
  private $sLog;

  public function __construct() {
    $this->URL = 'https://datadis.es/api-private/';
    $this->authorizedNif = "miramarSK1";
    $this->oConfig = new oConfig();
    $this->token = null;
    $this->sLog = new LogsService('API','DataDis');
  }

  /**
   * 
   * @param type $endpoint
   * @param type $method
   * @param type $data
   * @return boolean
   */
  public function call($endpoint, $method = "POST", $data = [], $fixParam = '') {

    $HTTPHEADER = [
        'Content-Type: application/json',
        'authorizedNif: '.$this->authorizedNif,
    ];
    if ($this->token) $HTTPHEADER[] = 'Authorization: Bearer'.$this->token;
    
    if ($method == "POST" || $method == "PUT") {

      $data_string = json_encode($data);
      $ch = curl_init($this->URL . $endpoint);

      curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_TIMEOUT, 30); //  CURLOPT_TIMEOUT => 10,
      curl_setopt($ch, CURLOPT_HTTPHEADER, $HTTPHEADER);
      
    } else {
      $url = $this->URL . $endpoint;
      if (count($data)) {
        $param = [];
        foreach ($data as $k => $d) {
          $param[] = "$k=$d";
        }
        $url .= '?' . implode('&', $param);
      }
      $url .= $fixParam;
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 7); //Timeout after 7 seconds
      curl_setopt($ch, CURLOPT_TIMEOUT, 10); //  CURLOPT_TIMEOUT => 10,
      curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
      curl_setopt($ch, CURLOPT_HTTPHEADER,$HTTPHEADER);
    }

    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//    var_dump(\json_decode($result),$result,$httpCode);
    $this->response = null;
    $this->responseCode = $httpCode;
    
    curl_close($ch);
    if ($httpCode!=200){
      $data['endpoint'] = $this->URL . $endpoint;
      $this->sLog->error($result,$data);
      return false;
    }
    
    $this->response = \json_decode($result);
    
     if (!$this->response){
      $data['endpoint'] = $this->URL . $endpoint;
      $this->sLog->error($result,$data);
      return false;
    }
    
    return TRUE;
   
  }

  public function conect() {

//    $this->token = "eyJhbGciOiJSUzI1NiJ9.eyJzdWIiOiJCOTI1NDk4ODAiLCJhdXRob3JpdGllcyI6WyJQX0FVVE9SSVpBQ0lPTkVTIiwiUF9CVVNRX0lOVEVSIiwiUF9DT05TVUxfSU5GT19BR1JFR0FEIiwiUF9DT05TVU1fRElBX1NFTUFOQV9NRVMiLCJQX0RBVE9TX0NPTlRSQVRPIiwiUF9EQVRPU19WSUFfQVBJIiwiUF9JTkZPUk1fUFJFREVGSU4iLCJQX01JU19TVU1JTklTVFJPUyIsIlBfTk9USUZJQ0FDIiwiUF9QT1RFTkNJQVMiLCJQX1NVTUlOSVNUUk9TX0FVVE9SIiwiUF9VU0VSIiwiUF9aT05BX1BSSVZBRF9HRVNUSU9OX0VTVEFEIiwiUk9MRV9BUEkiXSwicHVibGljVXNlciI6eyJpZCI6MTY5MTIsIm5hbWUiOiJJTlNUSVRVVE8gU1VQRVJJT1IgREVTU0FSUk9MTE8gRU1QUkVTQVJJQUwgU0wiLCJzdXJuYW1lIjpudWxsLCJlbWFpbCI6InJlc2VydmFzQGFwYXJ0YW1lbnRvc2llcnJhbmV2YWRhLm5ldCIsImxvZ2luIjoiQjkyNTQ5ODgwIiwibGFuZyI6ImVzIiwiY29tcGxldGVOYW1lIjoiSU5TVElUVVRPIFNVUEVSSU9SIERFU1NBUlJPTExPIEVNUFJFU0FSSUFMIFNMIG51bGwifSwiZW52aXJvbm1lbnQiOiJQUk8iLCJpYXQiOjE2MzYwNTgyNTUsImV4cCI6MTYzNjE0NDY1NX0.MLg0L4-QN-5GLua-WZC8StwrcLh9v1bkVYCFMkODKUIjGmV56p7ATpdNrNrREzQzsgBKlsHB8jmF0vaCtzXOFqtKJZbcBo7dIFfsDKsHXFZj7CIodY3T3ynJ3bMFkPLKq4ND3o0tlnxP1ltLbJZFecveuBpv5mdTlx7Hc83e6bX6MFQTFqD-tNENxOmHB7nXMRJVPrwYrv-e3zHMqSK-M-ptf0FiKXeVwS6H0gBxOph3jNlTGWMAS3AD86UwazJMdhxSnFCLYLAxfM0rOzquWHdY9WOK1cLQCBkzv7T9KTQbVU3uYKPuDTxEcoVo8dAcun3NeFA0hhSO8vVnUX7qpw";
//    return true;
    
    
    
    
    global $DATADIS_TOKEN; //to console
    
    if (isset($_COOKIE["DATADIS_TOKEN"])) {
      $this->token = $_COOKIE["DATADIS_TOKEN"];
      return true;
    }
    if ($DATADIS_TOKEN) {
      $this->token = $DATADIS_TOKEN;
      return true;
    }
    
    $username = env('DATADIS_USR');
    $password = env('DATADIS_PSW');
    $webKit = "----WebKitFormBoundary7MA4YWxkTrZu0gW";
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://datadis.es/nikola-auth/tokens/login",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "--$webKit\r\nContent-Disposition: form-data; name=\"username\"\r\n\r\n$username\r\n--$webKit\r\nContent-Disposition: form-data; name=\"password\"\r\n\r\n$password\r\n--$webKit--",
      CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "content-type: multipart/form-data; boundary=$webKit",
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      $this->sLog->error($err,$response);
      return false;
    } else {
//      echo $response."\n";
      $this->token = $response;
      setcookie("DATADIS_TOKEN", $this->token, time() + 3000);
      $DATADIS_TOKEN = $this->token;
      return true;
    }
  }

  public function disconect() {
    //to console
    global $DATADIS_TOKEN;
    $DATADIS_TOKEN = null;
    
    if (isset($_COOKIE["DATADIS_TOKEN"])) {
      $this->token = $_COOKIE["DATADIS_TOKEN"];
      setcookie("DATADIS_TOKEN", $this->token, time() - 3000);
    }
  }

  public function getSupplies() {
    $this->call('api/get-supplies', 'GET');
    return ($this->response);
  }
  
  public function getTimeCurveData($params) {

    //{"cups":["ES0031104624626027NF0F"],"distributor":"2","nifAutorizado":null,"fechaInicial":"2021/10/10","fechaFinal":"2021/10/27","fraccion":0,"tipoPuntoMedida":5}
    $params['fraccion'] = 0;
    $params['nifAutorizado'] = null;
    $this->call('supply-data/time-curve-data/week', 'POST', $params);
    return ($this->response);
  }
  public function getTimeCurveDataDays($params) {

    $params['fraccion'] = 0;
    $params['nifAutorizado'] = null;
    $this->call('supply-data/time-curve-data/day', 'POST', $params);
    return ($this->response);
  }

 
}
