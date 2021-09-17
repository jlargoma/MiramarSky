<?php
namespace App\Services;

      
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ShortUrlService{
  
  private $domain;
  
  public function __construct() {
    $this->domain =  env('URL_HELP_SERVICE');
  }
  
  public function create($url) {
//    return $url;
    
    $data_string = json_encode(['url'=>$url,'tkn'=>'dfsinr3r2rtlsfedagç43gnñbfx8']);
    

    $ch = curl_init($this->domain."shortUrl");
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
        'Content-Type: application/json',                                                                                
        'Content-Length: ' . strlen($data_string))                                                                       
    );                                                                                                                   
    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
    curl_exec($ch);
    curl_close($ch);
    return  $result;
  }
  
}


