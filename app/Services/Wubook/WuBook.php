<?php
namespace App\Services\Wubook;
use App\Services\Wubook\XML_RPC;
use App\Services\Wubook\Config as WBConfig;
use Illuminate\Support\Facades\DB;
use DateTime;
use DateInterval;
use App\Models\DailyPrices;

class WuBook{
 
    public $response;
    public $responseCode;
    public $channels;
    private $price_plan;
    private $rplan;
    protected   $token;
    protected   $iCode;
    protected   $WBConfig;
    
    public function __construct()
    {
      $this->iCode = 1578438122;
      $this->token = "5808490848.6474";
      $this->price_plan = 153130;
      $this->rplan = 76427;
      $this->WBConfig = new WBConfig();
    }
    
    private function call($method,$param) {
      $response = XML_RPC::CallMethod("https://wired.wubook.net/", $method, $param);
      if ($response){
        
        if (isset($response->params->param->value->array->data)){
          $response = $response->params->param->value->array->data;
          if (isset($response->value)){
            if (isset($response->value[0]) && $response->value[0]->int != 0){
              echo $response->value[1]->string;
              
              return null;
            }
            return $response->value[1];
          }
        }
      }
      return null;
    }
    
    public function conect(){
      $params = array(
          env('WUBOOK_USR'),
          env('WUBOOK_PSW'),
          env('WUBOOK_KEY')
          );
//      dd($params);
      $aResponse = $this->call('acquire_token', $params);
      if ($aResponse){
        $this->token = strval($aResponse->string);
//        dd($this->token);
        return true; 
      } 
      return false;
    }
    
    public function disconect(){
//      return FALSE; 
      if ($this->token){
        $aResponse = $this->call('release_token', array($this->token));
        $this->token = null;
      }
      return FALSE; 
    }
    
    public function pushURL($url, $test=0){
      // tdocs.wubook.net/wired/fetch.html#setting-up-the-push-notification
      if ($this->token){
        $aResponse = $this->call('push_activation', array($this->token,$this->iCode,$url, $test));
        if ($aResponse){
          if ($aResponse[0]->string == 'Ok'){
            return true;
          }
        }
      }
      return FALSE; 
    }
    
    public function get_pushURL(){
      // tdocs.wubook.net/wired/fetch.html#setting-up-the-push-notification
      if ($this->token){
        $aResponse = $this->call('push_url', array($this->token,$this->iCode));
        dd($aResponse);
      }
      return FALSE; 
    }

    
    
    /**
     * 
     * @param type $aResponse
     * @return type
     */
    private function processData($aResponse) {
      $result = array();
      if ($aResponse){
        $structs = $aResponse->array->data->value;
        foreach ($structs as $struct){
          
          if (isset($struct->struct))
            $member = $struct->struct->member;
          else 
            $member = $struct->member;
          $aData = array();
//          var_dump($member);die;
          foreach ($member as $data){
//            $k = $data->name->__toString();
            $k = $data->name;

            $content = null;
            switch ($k){
              case 'dayprices':
                $aux = $data->value->struct->member->value->array->data->value;
                $content = array();
                foreach ($aux as $item){
                  if (is_array($item)){
                    foreach ($item as $item2){
                      $content[] = $item2;//->__toString();
                    }
                  } else {
                    $content[] = $item;
                  }
                }
                break;
              case 'rooms_occupancies':
                if (is_array($data->value->array->data->value)){
                  $data->value->array->data->value = $data->value->array->data->value[0];
                }
                if (is_object($data->value->array->data->value)){
                  $aux = $data->value->array->data->value->struct->member;
                  $content = array();
                  foreach ($aux as $item){
  //                 
                    $content[$item->name] = $item->value->int;
  //                  $content[$item->name->__toString()] = $item->value[0]->int->__toString();
                  }
                }
                break;
              case 'booked_rooms':
//                $content = array();
//                $aux = $data->value->array->data->value->struct->member;
//                $aux2 = $aux[1]->value->array->data->value;
//                foreach ($aux2 as $item){
//                  $content[] = $item->struct->member[3]->value->string;
//                }
                
                break;

              default :
                $aux = $data->value;
                foreach ($aux as $item){
                  $content =$item;
                }
                break;
            }


            $aData[$k] = $content;

          }
          $result[] = $aData;
        }
      }
      return $result;
    }
    
    
    /**
     * check and apply Closes by dates
     * 
     * @param type $rCode
     * @return boolean
     */
    public function set_Closes($roomdays) {
      if ($this->token){
//        $roomdays= [
//          ['id'=> 433743, 'days'=> [['avail'=> 1,'date'=>'27/11/2016']],
//          ['id'=> 433742, 'days'=> [['avail'=> 2,'date'=>'07/11/2016'], [], ['avail'=> 2,'date'=>'17/11/2016']]],
//        ];
        $param = [
            $this->token,
            $this->iCode,
            $roomdays
        ];
     
        $aResponse = $this->call('update_sparse_avail',$param);
        if ($aResponse){
          if ($aResponse[0]->string == 'Ok'){
//            echo 'Disponibilidad Actualizada';
            return true;
          }
        }
      }
      return FALSE; 
    }
    /**
     * check and apply Closes by dates
     * 
     * @param type $rCode
     * @return boolean
     */
    public function set_ClosesRange($dfrom='2020-01-28',$roomdays) {
      
      if ($this->token){
       $dfromTime = strtotime($dfrom);
//        $roomdays= [
//          ['id'=> 433743, 'days'=> [['avail'=> 1], ['avail'=> 1], ['avail'=> 1]]],
//          ['id'=> 433742, 'days'=> [['avail'=> 2], [], ['avail'=> 2]]],
//        ];
          
        $param = [
            $this->token,
            $this->iCode,
            date('d/m/Y',$dfromTime),
            $roomdays
        ];
      
        $aResponse = $this->call('update_avail',$param);
        if ($aResponse){
          if ($aResponse[0]->string == 'Ok'){
//            echo 'Disponibilidad Actualizada';
            return true;
          }
        }
      }
      return FALSE; 
    }
    
    
     /**
     * check and apply Closes by dates
     * 
     * @param type $rCode
     * @return boolean
     */
    public function set_Prices($dfrom='2020-01-28',$prices) {
      
      if ($this->token){
       $dfromTime = strtotime($dfrom);
       
       
      
//        $prices= [
//          "_int_433743" => [100, 101, 102],
//          "_int_433742" => [200, 201, 202],
//        ];
 
          
        $param = [
            $this->token,
            $this->iCode,
            $this->price_plan,
            date('d/m/Y',$dfromTime),
            $prices
        ];
//        var_dump($param);
//       dd($param);
      
        $aResponse = $this->call('update_plan_prices',$param);
        if ($aResponse){
          if ($aResponse->string == 'Ok'){
//            echo 'Precios Actualizados';
            return true;
          }
        }
      }
      return FALSE; 
    }
    
     /**
     * check and apply Closes by dates
     * 
     * @param type $rCode
     * @return boolean
     */
    public function set_Restrictions($dfrom='2029-01-28',$min_stay) {
      
      if ($this->token){
       $dfromTime = strtotime($dfrom);
       
//        values= {
//          '1': [ {'min_stay': 3}, {}, {'max_stay': 4}],
//          '2': [ {'closed': 1}, {}, {'max_stay': 2}],
//        }
 
          
        $param = [
            $this->token,
            $this->iCode,
            $this->rplan,
            date('d/m/Y',$dfromTime),
            $min_stay
        ];
//        var_dump($param);
      
        $aResponse = $this->call('rplan_update_rplan_values',$param);
        if ($aResponse){
          if ($aResponse->string == 'Ok'){
            return true;
          }
        }
      }
      return FALSE; 
    }
    
     /**
     * fetch_bookings by dates
     * 
     * @param type $rCode
     * @return boolean
     */
    public function fetch_bookings($dfrom='01/01/2020',$dto='21/12/2020') {
      
      $result = [];
      if ($this->token){
     
        $param = [
            $this->token,
            $this->iCode,
            $dfrom,
            $dto,
//            1,//ancillary
        ];
        
        $aResponse = $this->call('fetch_bookings',$param);
        $aux = json_encode($aResponse);
        $aResponse = json_decode($aux);
//        echo json_encode($aResponse);
//        include_once public_path('MoreBook.php');
//        $aResponse = json_decode($bookings);


        $reservs = $this->processData($aResponse);
        
        if (count($reservs)){
          
          $this->channels = $this->WBConfig->roomsEquivalent();
          foreach ($reservs as $data){
            $result[] = $this->addBook($data);
          }
        }
        
        dd($result);
      }
      return FALSE; 
    }
    
    
     /**
     * Booking by ID-book
     * 
     * @param type $rCode
     * @return boolean
     */
    public function fetch_booking($iCode,$rCode=null) {
      
      if ($iCode != $this->iCode) return false;
      
      if ($this->token && $rCode){
        $param = [
            $this->token,
            $this->iCode,
            $rCode,
        ];
        $aResponse = $this->call('fetch_booking',$param);
        $aux = json_encode($aResponse);
        $aResponse = json_decode($aux);
        
//        include_once public_path('/tests/WuBook-booking.php');
//        $aResponse = json_decode($bookings);
//        
        $reserv = $this->processData($aResponse);
        if ($reserv){
          $this->channels = $this->WBConfig->roomsEquivalent();
          return $this->addBook($reserv[0]);
        }
      }
      
      return FALSE; 
    }
    
    
     /**
     * Add event to calendar
     * 
     * @param $event ICal\Event
     * @param $agency integer Agency from where come the book
     * @param $room_id Room belong the book
     */
    private function addBook($data)
    {
   
      $alreadyExist = \App\Book::where('external_id',$data['reservation_code'])->first();
      if ($alreadyExist) return null;
      
      
      foreach ($data as $k=>$v){
        if ($k != 'rooms_occupancies')
          $data[$k] = is_object($v) ? null : $v;
      }
      
      foreach ($data as $k=>$v){
        if (!$v){
          switch ($k){
            case 'customer_mail': $data[$k] = ''; break;
          }
        }
      }
     
      $start  = convertDateToDB($data['date_arrival']);
      $finish = convertDateToDB($data['date_departure']);
      
      $roomGroup = isset($this->channels[$data['rooms']]) ? $this->channels[$data['rooms']] : 'DDE';
      $room = new \App\Rooms();
      $roomID = $room->calculateRoomToFastPayment($roomGroup, $start, $finish);
      if ($roomID<0){
        $roomID = 33;
      }
            
      $nights = calcNights($start, $finish);
      $book = new \App\Book();

      // Customer
      $customer             = new \App\Customers();
      $customer->user_id    = 23;
      $customer->name       = $data['customer_name'].' '.$data['customer_surname'];
      $customer->email      = $data['customer_mail'];
      $customer->phone      = $data['customer_phone'];
      $customer->comments   = $data['customer_surname'];
      $customer->address    = $data['customer_address'];
      $customer->country    = $data['customer_country'];
      $customer->city       = $data['customer_city'];
      $customer->zipCode    = $data['customer_zip'];
      $customer->language   = $data['customer_language_iso'];
      $customer->comments   = $data['customer_notes'];
      $customer->email_notif= $data['customer_mail'];
      $customer->send_notif = 1;
      $customer->DNI        = '';

      if ($customer->zipCode>0){
        $customer->province = substr($customer->zipCode, 0,2);
      } else {
        if ($customer->country == 'es' || $customer->country == 'ES'){
          if (trim($customer->city) != ''){
            $obj = \App\Provinces::where('province','LIKE', '%'.trim($customer->city).'%')->first();
            if ($obj) {
              $customer->province = $obj->code;
            }
          }
        }
      }
      $customer->save();
    
      //Create Book
      $pax    = isset($data['rooms_occupancies']) ? $data['rooms_occupancies']['occupancy'] : 0;
      if ($pax <1)  $pax = $data['men'] + $data['children'];
      $amount = floatval($data['amount']);
//      $agency = 999999; //wubook
      $agency = $this->WBConfig->getAgency(intval($data['id_channel'])); //wubook
      
      $PVPAgencia = 0;
      if ($agency == 999999)  $PVPAgencia = $amount * 0.12;
      

      $book_comments = 'id Reserva - OTA: '.$data['channel_reservation_code'].'<br>'
            .'Moneda: '.$data['currency'].'|'
            .'Adultos: '.$data['men'].'|'
            .'Niños: '.$data['children'];
                
      
      $book->user_id = 39;
      $book->customer_id = $customer->id;
      $book->room_id = $roomID;
      $book->start = $start;
      $book->finish = $finish;
      $book->comment = $book_comments;
      $book->type_book = 11;
      $book->nigths = $nights;
      $book->agency = $agency;
      $book->pax = $pax;
      $book->real_pax = $pax;
      $book->PVPAgencia = $PVPAgencia;
      $book->total_price = $data['amount'];
      $book->external_id = $data['reservation_code'];
      $book->propertyId = $data['rooms'];
      $book->external_roomId = null;
      $book->priceOTA = $data['amount'];

      $book->save();


       //costes
      $room = \App\Rooms::find($roomID);
      $costes = $room->priceLimpieza($room->sizeApto);
      $book->cost_limp = isset($costes['cost_limp']) ? $costes['cost_limp'] : 0;


      $book->cost_lujo = 0;
      if ($room->luxury == 1){
        $book->type_luxury = 1;
        $book->cost_lujo = \App\Settings::priceLujo();
      }


      $book->cost_park = (\App\Settings::priceParking()*$nights) * $room->num_garage;
      $book->type_park = 1;

      $book->cost_apto = $room->getCostRoom($book->start,$book->finish,$book->pax);
      $book->extraCost  = \App\Extras::find(4)->cost;

      $book->cost_total = $book->get_costeTotal();

      $book->save();
      
      $book->sendAvailibility($roomID,$start,$finish);
      
      return $book->id;
      
     
    }
    
   
    public function getRoomsEquivalent($channels) {
      $result = [];
      $lst = $this->WBConfig->roomsEquivalent();
      
      if ($channels === null){
        foreach ($lst as $rid => $ch)  $result[$ch] = $rid;
        return $result;
      }
      
      foreach ($lst as $rid => $ch){
        if(in_array($ch, $channels))
        $result[$ch] = $rid;
      }
      return $result;
    }
    
    function fetch_rooms() {
      $param = [
            $this->token,
            1578949667,
          160538,
           '21/04/2020',
           '24/04/2020',
        ];
      
      
                
        $aResponse = $this->call('fetch_plan_prices',$param);
        var_dump($aResponse);
    }
    
}