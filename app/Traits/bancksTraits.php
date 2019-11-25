<?php

namespace App\Traits;

use App\Settings;
use Carbon\Carbon;
use App\Book;
use App\Repositories\CachedRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


trait bancksTraits {

 function conectTestBank(){
   $data = array("username" =>  env('PARTEE_USR'), "password" => env('PARTEE_PSW'),"rememberMe"=> false);                                                                    
      $data_string = json_encode($data);                                                                                   
                                                                                                                     
      $ch = curl_init($this->PARTEE_URL."authenticate");
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
      
      
      if(!$result) 
      { 
        $this->response = 'Server error - empty response';
        $this->responseCode = 400;
        return FALSE; 
      } 
 }
}
