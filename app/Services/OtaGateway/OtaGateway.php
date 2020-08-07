<?php

namespace App\Services\OtaGateway;
use App\Services\OtaGateway\Config as oConfig;
use Illuminate\Support\Facades\DB;
use DateTime;
use DateInterval;
use App\DailyPrices;


class OtaGateway{
   
  public $response;
  public $responseCode;
  public $rooms;
  protected   $token;
  protected   $URL;
  protected   $oConfig;
  protected   $account_id;
  
  
   public function __construct()
    {
      $this->URL = env('OTA_GATEWAY_URL');
      $this->account_id = env('OTA_GATEWAY_USR_ID');
      $this->oConfig = new oConfig();
    }
    
    /**
     * 
     * @param type $endpoint
     * @param type $method
     * @param type $data
     * @return boolean
     */
    public function call( $endpoint,$method = "POST", $data = [],$fixParam='')
    {
       
      if ($method == "POST" || $method == "PUT"  ){
        
        $data_string = json_encode($data);   
        $ch = curl_init($this->URL.$endpoint);
        
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);                                                                     
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);     
        curl_setopt($ch, CURLOPT_TIMEOUT , 10); //  CURLOPT_TIMEOUT => 10,
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
            'Content-Type: application/json',   
            )                                                                       
        );          
      } else {
        $url = $this->URL.$endpoint;
        if (count($data)){
          $param=[];
          foreach ($data as $k=>$d){
            $param[] = "$k=$d";
          }
          $url .='?'. implode('&',$param);
        }
        $url .= $fixParam;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);                                                                     
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
//        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , 7); //Timeout after 7 seconds
        curl_setopt($ch, CURLOPT_TIMEOUT , 10); //  CURLOPT_TIMEOUT => 10,
        curl_setopt($ch, CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
            'Content-Type: application/json',        
        ));     
      }
      
      $result = curl_exec($ch);
      $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
//      dd(\json_decode($result),$result,$httpCode);
      curl_close($ch);
      $this->response = null;
      $this->responseCode = $httpCode;
       $this->response = \json_decode($result); return TRUE; 
      switch ($httpCode){
        case 200:
          if(!$result) 
          { 
            $this->response = null;
            return FALSE; 
          } 
          $this->response = \json_decode($result);
          return TRUE; 
          break;
        case 400:
          $this->response = 'Wrong data - Bad Request';
          break;
        case 401:
          $this->response = $result;
          break;
        case 404:
          $this->response = $result;//'NotFound';
          break;
        default :
          $this->response = 'Server error';
          break;
      }

      return FALSE; 
      
    }
    
    
    public function conect(){
      
      if (isset($_COOKIE["OTA_GATE_TOKEN"])){
        $this->token = $_COOKIE["OTA_GATE_TOKEN"];
        return true; 
      }
      $params = array(
          'username' => env('OTA_GATEWAY_USR'),
          'password' => env('OTA_GATEWAY_PSW')
          );
      $Response = $this->call('auth',"POST", $params);
//      dd($Response,$this->response);
      if ($Response){
        $this->token = strval($this->response->token);
        setcookie("OTA_GATE_TOKEN",  $this->token, time()+3000); 
        return true; 
      } 
      return false;
    }
    
    public function disconect(){
      if (isset($_COOKIE["OTA_GATE_TOKEN"])){
        $this->token = $_COOKIE["OTA_GATE_TOKEN"];
        $Response = $this->call('auth',"DELETE", []);
        setcookie("OTA_GATE_TOKEN",  $this->token, time()-3000); 
      }
    }
    
    public function createRoom($params) {
     
      $params['token'] = $this->token;
      $params['account_id'] = $this->account_id;
      $this->call('roomtypes','POST',$params);
      return  ($this->response);
    }
    public function newRestrictionPlan($params) {
     
      $params['token'] = $this->token;
      $params['account_id'] = $this->account_id;
      $this->call('restriction_plans','POST',$params);
      return  ($this->response);
    }
    public function createWebhook($params) {
     
      $params['token'] = $this->token;
      $params['account_id'] = $this->account_id;
      $this->call('webhook','POST',$params);
      return  ($this->response);
    }
    public function newRatesPlan($params) {
     
      $params['token'] = $this->token;
      $params['account_id'] = $this->account_id;
      $this->call('restriction_plans','POST',$params);
      return  ($this->response);
    }
    
    public function setRates($params) {
      $params['token'] = $this->token;
      $params['account_id'] = $this->account_id;
      $params['plan_id'] = $this->oConfig->Plans();
      $this->call('prices','POST',$params);
      return  ($this->responseCode);
    }
    public function setMinStay($params) {
      $params['token'] = $this->token;
      $params['account_id'] = $this->account_id;
      $params['restriction_plan_id'] = $this->oConfig->restriction_plan();
      $this->call('restrictions','POST',$params);
      return  ($this->responseCode);
    }
    public function createOTA($params) {
      $params['token'] = $this->token;
      $params['account_id'] = $this->account_id;
//      dd($params);
      $this->call('ota_settings','POST',$params);
      return  ($this->response);
    }
    public function sendAvailability($params) {
      $params['token'] = $this->token;
      $params['account_id'] = $this->account_id;
      
      $this->call('availability','POST',$params);
      return  ($this->responseCode);
    }
    function getBooking($booking_numbers) {
      $params = [];
      $fixParam = "";
      if (count($booking_numbers) == 1){
        $params['booking_number'] = $booking_numbers;
      } else {
        $paranName = strval('booking_numbers[]');
        foreach ($booking_numbers as $b){
          $fixParam .= '&'.$paranName.'='.$b;
        }
      }
      $params['token'] = $this->token;
      $params['account_id'] = $this->account_id;
      
              
      $this->call('bookings','GET',$params,$fixParam);
      return $this->response;
      
    }
    
    
    
  public function calculateRoomToFastPayment($apto, $start, $finish,$roomID = null) {

    $room = new \App\Rooms();
    return $room->calculateRoomToFastPayment($apto, $start, $finish,$roomID);
    
  }
   
  
  function reservations_cc($channelId,$propertyId,$reservationId){
      $params = [
      "channelId" => $channelId,
      "propertyId" => $propertyId,
      "reservationId" => $reservationId,
      ];
    
      $this->call('reservations-cc','GET',$params,true);
      return $this->response;
  }
  
  
    /**
     * Add/Edit Booking
     * 
     */
    public function addBook($cg,$reserv)
    {
      $update = null;
      /***********************************************************/
      /** CANCEL THE BOOKING **/
      //Booking Status. 1 - new, 2 - canceled, 3 - pending
        $alreadyExist = \App\Book::where('bkg_number', $reserv['bkg_number'])->first();
        if ($alreadyExist){
           if ($reserv['status'] == 2){//Cancelada
            $response = $alreadyExist->changeBook(98, "", $alreadyExist);
            if ($response['status'] == 'success' || $response['status'] ==  'warning'){
              //Ya esta disponible
              $alreadyExist->sendAvailibilityBy_status();
            }
            return $alreadyExist->id;
          } else {
            $update = $alreadyExist->id;
          }
        }
      /***********************************************************/
     
      $book = new \App\Book();
      $start  = $reserv['start'];
      $finish = $reserv['end'];
      $nights = calcNights($start, $finish);
      $reserv['start_date'] = $start;
      $reserv['end_date'] = $finish;
      $reserv['nights'] = $nights;
      
      /** UPDATE THE BOOKING **/
      if ($update){
        // Customer
        $customer = \App\Customers::find($alreadyExist->customer_id);
        if ($customer && $customer->id == $alreadyExist->customer_id){
          $customer->name = $reserv['customer_name'];
          $customer->email = $reserv['customer_email'];
          $customer->phone = $reserv['customer_phone'];
          $customer->DNI = "";
          $customer->email_notif = $reserv['customer_email'];
          $customer->send_notif = 1;
          $customer->country	 = '';
          $customer->city	 = '';
          $customer->zipCode	 = '';
          $customer->save();
        }
        
        $this->updBooking($alreadyExist, $reserv);
        return $update;
      }
      
      /** CREATE THE BOOKING **/
      $roomID = $this->calculateRoomToFastPayment($cg, $start, $finish);
      if ($roomID<0){
        $roomID = 142;
      }
      $book = new \App\Book();

      // Customer
      $customer = new \App\Customers();
      $customer->user_id = 23;
      $customer->name = $reserv['customer_name'];
      $customer->email = $reserv['customer_email'];
      $customer->phone = $reserv['customer_phone'];
      $customer->DNI = "";
      $customer->email_notif = $reserv['customer_email'];
      $customer->send_notif = 1;
      $customer->country = null;
      $customer->city	 = -1;
      $customer->zipCode = '';
      $customer->save();
    
      //Create Book
      $book->user_id = 39;
      $book->customer_id = $customer->id;
      $book->room_id = $roomID;
      $book->external_id = $reserv['reser_id'];
      $book->bkg_number = $reserv['bkg_number'];
      $book->external_roomId = $reserv['external_roomId'];
      $book->save();

      $this->updBooking($book, $reserv);
      return $book->id;
    }
    
    /**
     * 
     * @param type $book
     * @param type $reserv
     */
    private function updBooking($book, $reserv){
      
      $pax    = $reserv['adults'] + $reserv['children'];
      $book_comments = $book->comment . "\n"
            .'Adultos: '.$reserv['adults'].' - '
            .'Niños: '.$reserv['children'];
      
      $agency = $reserv['agency'];
      $amount = floatval($reserv['totalPrice']);
      $PVPAgencia = 0;
      if ($agency == 999999)  $PVPAgencia = intval($reserv['totalPrice']) * 0.12;
      $comment = $reserv['customer_comment'];
      
      $book->start = $reserv['start'];
      $book->finish = $reserv['end'];
      $book->comment = $comment;
      $book->book_comments = $book_comments;
      $book->type_book = 11;
      $book->agency = $reserv['agency'];
      $book->pax = $pax;
      $book->real_pax = $pax;
      $book->PVPAgencia = $reserv['comision'];
      $book->total_price = $reserv['totalPrice'];
      $book->priceOTA = $reserv['totalPrice'];
      $book->total_price = $reserv['totalPrice'];

      $book->save();
            
      $totales = $book->getPriceBook($book->start,$book->finish,$book->room_id);
      
      $book->sup_limp    = $totales['price_limp'];
      $book->cost_limp   = $totales['cost_limp'];
      $book->sup_park    = $totales['parking'];
      $book->cost_park   = $totales['cost_parking'];
      $book->sup_lujo    = $totales['price_lux'];
      $book->cost_lujo   = $totales['cost_lux'];
      $book->cost_apto   = $totales['cost_apto'];
      $book->extraCost   = $totales['cost_extr'];
      $book->extraPrice  = $totales['price_extr'];
                        
      $book->cost_total  = $book->get_costeTotal();
      $book->real_price  = $totales['price_total'];
      $book->total_ben   = $book->total_price - $book->cost_total;
      $book->save();

      $book->sendAvailibility($book->room_id,$reserv['start_date'],$reserv['end_date']);
      
    }
 
  /*************************************************************/
  /*************    AUX FUNCTIONS             ******************/
  /*************************************************************/
 
}
