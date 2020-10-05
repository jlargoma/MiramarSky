<?php

namespace App\Services\Zodomus;
use App\Services\Zodomus\Config as ZConfig;
use Illuminate\Support\Facades\DB;
use DateTime;
use DateInterval;
use App\DailyPrices;


class Zodomus{
   
  public $response;
  public $responseCode;
  public $rooms;
  protected   $token;
  protected   $URL;
  protected   $usr;
  protected   $psw;
  protected   $psw_card;
  protected   $ZConfig;
  
  
   public function __construct(){return null;}
   public function call(){return null;}
   public function getInfo(){return null;}
   public function activateChannels(){return null;}
   public function checkProperty(){return null;}
   public function createRoom(){return null;}
   public function activateRoom(){return null;}
   public function getRoomsAvailability(){return null;}
   public function setRoomsAvailability(){return null;}
   public function getSummary(){return null;}
   public function getRates(){return null;}
   public function setRates(){return null;}
   public function getBookings(){return null;}
   public function getBookingsQueue(){return null;}
   public function createTestReserv(){return null;}
   public function setRatesDerived(){return null;}
   public function getBooking(){return null;}
   public function getChannelManager(){return null;}
   public function calculateRoomToFastPayment(){return null;}
   public function cancelRates(){return null;}
   public function reservations_cc(){return null;}
   public function saveBooking(){return null;}
   public function updBooking( $endpoint,$method = "POST", $data = [],$cc=false){return null;}
  
  /*************************************************************/
  /*************    AUX FUNCTIONS             ******************/
  /*************************************************************/
  
  function createNewProperties(){
     $rooms = [];
      $aptosLst = configZodomusAptos();
      foreach ($aptosLst as $k=>$v){
        foreach ($v->rooms as $j){
          if($j->channel == 2){
            $rooms[] =  ["roomId" => $j->roomID,"roomName" => $j->name,"rates" => [$j->rateID],"quantity" => 10,"status" => 1];
          }
          
        }
      }
//      dd($rooms);
      
      
      $roomToAt = [
             "channelId" => 2,
             "propertyId" => $apto,
             "rooms" => $rooms
           ];
//    $return = $Zodomus->activateRoom($roomToAt);
  }
  
  function sendRatesGroup($apto,$rateId,$roomID,$channel_group,$channelId){
//      $channel_group = 'ROSASJ';
  
      $priceDay = $minDay = [];
      $startDate = '2020-12-01';
      $endDate = '2021-07-01';
      $oRoom = \App\Rooms::where('channel_group',$channel_group)->first();
      $defaults = $oRoom->defaultCostPrice( $startDate, $endDate,$oRoom->minOcu);
      $priceDay = $defaults['priceDay'];
      $oPrice = \App\DailyPrices::where('channel_group',$channel_group)
                ->where('date','>=',$startDate)
                ->where('date','<=',$endDate)
                ->get();
    
      foreach ($oPrice as $p){
        if (isset($priceDay[$p->date])){
          $priceDay[$p->date] = $p->price;
        }
      }
     
      $d1 = $startDate;
      $d2 = null;
      $to_send = [];
      $precio = null;
      foreach ($priceDay as $d=>$p){
        $d2 = $d;
        if (is_null($precio)) $precio = $p;
        if (is_null($d1))  $d1 = $d;
        
        
        if ($p!=$precio){
          $to_send[] =  [
              "dateFrom" => $d1,
              "dateTo" => $d2,
              "prices" =>   [ "price" => $precio ],
              
          ];
          $d1 = $d;
          $precio = $p;
        }
        
        
      }
      
      if ($d2!=$d1){
          $to_send[] =  [
              "dateFrom" => $d1,
              "dateTo" => $d2,
              "prices" =>   [ "price" => $precio ],
              
          ];
        }
      
       
     $weekDays = [ 
      "sun"=>true,
      "mon"=>true,
      "tue"=>true,
      "wed"=>true,
      "thu"=>true,
      "fri"=>true,
      "sat"=>true];
        
      foreach ($to_send as $v){
        if ($v['prices']['price'] == 999) continue;
         $param = [
                "channelId" =>  $channelId,
                "propertyId" => $apto,
                "roomId" =>  $roomID,
                "dateFrom" => $v['dateFrom'],
                "dateTo" => $v['dateTo'],
                "currencyCode" =>  "EUR",
                "rateId" =>  $rateId,
                "weekDays" => $weekDays,
                "prices" =>  $v['prices'],
                "closed" =>  0,
                "minimumStay" => 1,
                "minimumStayArrival" => 1,
              ];
         
              $errorMsg = $this->setRates($param,$channel_group);
              var_dump($this->response,$param);
              if ($errorMsg){
                return $errorMsg;
              }
      }
  
      dd($apto,$rateId,$roomID,$channel_group,$to_send);
      
  }
}
