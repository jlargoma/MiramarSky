<?php
namespace App\Services\Wubook;
use App\Services\Wubook\XML_RPC;
use App\Services\Wubook\Config as WBConfig;
use Illuminate\Support\Facades\DB;
use DateTime;
use DateInterval;
use App\DailyPrices;

class WuBook_1{
 
    public $response;
    public $responseCode;
    public $rooms;
    protected   $token;
    protected   $iCode;
    protected   $WBConfig;
    
    public function __construct()
    {
      $this->iCode = 1578438122;
      $this->token = "1685833581.4962";
      $this->WBConfig = new WBConfig();
    }
    
    public function conect(){
      
      $params = array(
          'JL136',
          'umi2f7nh',
          '6e2104d9f0855c934ba5dcbe867c7ebe427d1cb3569e8d9f'
          );
      
      $obj = XML_RPC::CallMethod("https://wired.wubook.net", 'acquire_token', $params);
      
      $aResponse = $obj->params->param->value->array->data;
      $this->token = strval($aResponse->value[1]->string);
      
      return true; 
      
    }
    
    public function disconect(){
      if ($this->token){
        var_dump($this->token);
      $obj = XML_RPC::CallMethod("https://wired.wubook.net", 'release_token', array($this->token));
      
      $aResponse = $obj->params->param->value->array->data;
      dd($aResponse);
//      1769239951.5388
    
      $this->token = null;
      }
      return FALSE; 
    }
    
    
    
    
    
    public function fetch_new_bookings() {
      
      if ($this->token){
     
        $param = [
            $this->token,
            $this->iCode,
            1,//ancillary
            1 //Mark
        ];
//        var_dump($this->token);
        $obj = XML_RPC::CallMethod("https://wired.wubook.net", 'fetch_new_bookings',$param);
        dd($obj);
        $aResponse = $obj->params->param->value;//->array->data;
        dd($aResponse);
  //      1769239951.5388

        $this->token = null;
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
      
      $roomsID = $this->WBConfig->roomsEquivalent();
      if ($this->token){
     
        $param = [
            $this->token,
            $this->iCode,
            $dfrom,
            $dto,
//            1,//ancillary
        ];

//        $obj = XML_RPC::CallMethod("https://wired.wubook.net", 'fetch_bookings',$param);
        
      include_once dirname(dirname(dirname(dirname(__FILE__)))).'/public/test-wubook/MoreBook.php';
        $obj = new \SimpleXMLElement(xmlTest());
        $aResponse = $obj->params->param->value->array->data;
        $reservs = $this->processData($aResponse);
        if (count($reservs)){
          
          $this->rooms = $this->WBConfig->roomsEquivalent();
          foreach ($reservs as $data){
            $this->addBook($data);
          }
        }
        
        dd($data);
      }
      return FALSE; 
      
      
      
    }
    
  
    
    /**
     * Booking by ID-book
     * 
     * @param type $rCode
     * @return boolean
     */
    public function fetch_booking($rCode='1578515999') {
      
      if ($this->token){
     
        $param = [
            $this->token,
            $this->iCode,
            $rCode,
//            1,//ancillary
        ];

//        $obj = XML_RPC::CallMethod("https://wired.wubook.net", 'fetch_booking',$param);
        $obj = new \SimpleXMLElement($this->xmlTest());
        $aResponse = $obj->params->param->value->array->data;
        
        $this->processData($aResponse);
      }
      return FALSE; 
      
      
      
    }
    
    
    /**
     * Get prices to all Rooms: from $dfrom to $dto
     * @param type $dfrom
     * @param type $dto
     * @return boolean
     */
     public function fetch_plan_prices($dfrom='2020-06-01',$dto='2020-07-01') {
      
      if ($this->token){
     
        $dfromTime = strtotime($dfrom);
        $dtoTime = strtotime($dto);
        
//        $rooms = null; //array Rooms ID
        
        $rooms = [433742,433743,433744]; //array Rooms ID
        $pricePlan = 153130;//pid Miramar Ski
        $param = [
            $this->token,
            $this->iCode,
            $pricePlan,
            date('d/m/Y',$dfromTime),
            date('d/m/Y',$dtoTime),
            $rooms
        ];

        $obj = XML_RPC::CallMethod("https://wired.wubook.net", 'fetch_plan_prices',$param);
//         include_once dirname(dirname(dirname(__FILE__))).'/public/test-wubook/Prices.php';
//        $obj = new \SimpleXMLElement(xmlTest());
        $aResponse = $obj->params->param->value->array->data;
        
        $oneDay = 24*60*60;
        $pricesRooms = array();
        if ($aResponse->value[0] == 0){
          
          
          //iMPORTANT: REMOVE OLD VALUES ..!!!!
           DailyPrices::where('date','>=',$dfrom)
                  ->where('date','<=',$dto)
                  ->delete();
          
//          die('asdfad');
          $roomsID = $this->WBConfig->roomsEquivalent();

          $members = $aResponse->value[1]->struct;
          foreach ($members as $member){
            foreach ($member as $data){
              $start = $dfromTime;
              $pId = $data->name->__toString();
              if (isset($roomsID[$pId])){
                $id_room = $roomsID[$pId];
                $aux = $data->value->array->data;
                foreach ($aux as $value){
                   foreach ($value as $v){
                     if ($dtoTime<$start) break;
                    $datePrice = date('Y-m-d',$start);
                    $price     = $v->double->__toString();
                           
                    if (is_numeric($id_room)){
                      $pricesRooms[] = [
                         'id_room'    => $id_room,
                         'rId_wubook' => $pId,
                         'date'       => $datePrice,
                         'price'      => $price
                      ];
                    } else {
                      if (is_array($id_room) && count($id_room)>0){
                        
                        foreach ($id_room as $id){
                          $pricesRooms[] = [
                            'id_room'    => $id,
                            'rId_wubook' => $pId,
                            'date'       => $datePrice,
                            'price'      => $price
                          ];
                          
                        }
                      }
                    }
                    $start += $oneDay;
                   }
                   if (count($pricesRooms)>15){
                     DailyPrices::insert($pricesRooms);
                     $pricesRooms = array();
                   }
                }
              }
               
              if (count($pricesRooms)>0){
                DailyPrices::insert($pricesRooms);
                $pricesRooms = array();
              }
                
            }
          }
          

           return true; 
        }
      }
      return FALSE; 
    }
    
    
    /**
     * Get all Rooms
     * @return boolean
     */
     public function fetch_rooms() {
      
      if ($this->token){
        $param = [
            $this->token,
            $this->iCode,
        ];
//        var_dump($this->token);
//        $obj = XML_RPC::CallMethod("https://wired.wubook.net", 'fetch_rooms',$param);
//         include_once dirname(dirname(dirname(__FILE__))).'/public/test-wubook/Rooms.php';
//        $obj = new \SimpleXMLElement(xmlTest());
        $aResponse = $obj->params->param->value->array->data;
        $aRooms = array();
        if ($aResponse->value[0] == 0){
          $rooms = $aResponse->value[1]->array->data;
          foreach ($rooms as $values){
            foreach ($values as $value){
              $rID = $name = null;
              foreach ($value->struct->member as $v){
                if ($v->name == 'id') $rID = $v->value->int->__toString();
                if ($v->name == 'shortname') $name = $v->value->string->__toString();
              }
              $aRooms[$rID] = $name;
            }
          }
          dd($aRooms);
           return true; 
        }
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
      if ($aResponse->value[0] == 0){
        $structs = $aResponse->value[1]->array->data->value;
        foreach ($structs as $struct){
          $member = $struct->struct->member;
          $aData = array();
          foreach ($member as $data){
            $k = $data->name->__toString();

            $content = null;
            switch ($k){
              case 'dayprices':
                $aux = $data->value->struct->member->value->array->data->value;
                $content = array();
                foreach ($aux as $item){
                  foreach ($item as $item2){
                    $content[] = $item2->__toString();
                  }
                }
                break;
              case 'rooms_occupancies':
                $aux = $data->value->array->data->value->struct->member;
                $content = array();
                foreach ($aux as $item){
                  $content[$item->name->__toString()] = $item->value[0]->int->__toString();
                }
                break;
              case 'booked_rooms':
                $aux = $data->value->array->data->value->struct->member;
                $aux2 = $aux->value->struct->member;
                $content = array();
                foreach ($aux as $item){
                  $content[$item->name->__toString()] = $item->value[0]->int->__toString();
                }
                break;

              default :

                $aux = $data->value[0];
                foreach ($aux as $item){
                  $content =$item->__toString();
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
    public function get_Closes($dfrom='2020-06-01',$dto='2020-06-01') {
      
      if ($this->token){
      
       $dfromTime = strtotime($dfrom);
       $dtoTime = strtotime($dto);
        
//        $rooms = null; //array Rooms ID
        
        $rooms = [433742,433743,433744]; //array Rooms ID

        $param = [
            $this->token,
            $this->iCode,
            date('d/m/Y',$dfromTime),
            date('d/m/Y',$dtoTime),
            $rooms
        ];
      
//        $obj = XML_RPC::CallMethod("https://wired.wubook.net", 'fetch_rooms_values',$param);
        
      include_once dirname(dirname(dirname(__FILE__))).'/public/test-wubook/Availability.php';
        $obj = new \SimpleXMLElement(xmlTest());
        $aResponse = $obj->params->param->value->array->data;
        dd($aResponse->value[1]->struct->member);
        $data = $this->processData($aResponse);
      }
      return FALSE; 
      
      
      
    }
    
      /**
     * check and apply Closes by dates
     * 
     * @param type $rCode
     * @return boolean
     */
    public function set_Closes($dfrom='2020-01-28',$roomdays) {
      
      if ($this->token){
      
       $dfromTime = strtotime($dfrom);
        
        $rooms = [433742,433743,433744]; //array Rooms ID

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
      
        $obj = XML_RPC::CallMethod("https://wired.wubook.net", 'update_avail',$param);
        
        $aResponse = $obj->params->param->value->array->data;
        if ($aResponse->value[0]->int == 0){
          if ($aResponse->value[1]->string == 'Ok'){
            echo 'Disponibilidad Actualizada';
            return true;
          }
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
      //BEGIN: Room ID
        $room_id = null;
        if (isset($data["rooms"])){
          $rWubook = $data["rooms"];
          
          if (isset($this->rooms[$rWubook])) $room_id = $this->rooms[$rWubook];
          
          $type_book = 11;
          if (false){//Booking.com Virtual
            $type_book = 100; //gost reserv
            $room_id = -1; //by fastPayment 
          }
        }
        
        if(!$room_id) return false;
      //END: Room ID
        
        
        $start = new DateTime(convertDateToDB($data['date_arrival']));
        $finish = new DateTime(convertDateToDB($data['date_departure']));
        $interval = $start->diff($finish);
        $nights = $interval->format("%a");
       

        $book = new \App\Book();

        $customer           = new \App\Customers();
        $customer->user_id  = 23;
        $customer->name     = $data['customer_name'];
        $customer->email    = $data['customer_mail'];
        $customer->surname  = $data['customer_surname'];
        $customer->phone    = $data['customer_phone'];
        $customer->comments = $data['customer_surname'];
        $customer->address  = $data['customer_address'];
        $customer->country  = $data['customer_country'];
        $customer->city     = $data['customer_city'];
        $customer->language = $data['customer_language_iso'];
        $customer->comments = $data['customer_notes'];
        $customer->save();

        $pax    = $data['men'] + $data['children'];
        $amount = $data['amount'];
        $agency = $this->WBConfig->getAgency($data['id_channel']);
        
        $book_comments = 'id OTA: '.$data['id_channel'].'\n'
              .'id Reserva - OTA: '.$data['channel_reservation_code'].'<br>'
              .'Moneda: '.$data['currency'].'|'
              .'rooms: '.$data['rooms'].'|'
              .'rooms: '.$data['rooms'].'|'
              .'Adultos: '.$data['men'].'|'
              .'Niños: '.$data['children'].'|'
              .'Fecha de reserva: '.$data['date_received_time'].'|';
        
       

        //Create Book
        $book->user_id       = 23;
        $book->customer_id   = $customer->id;//$this->customer_id;// user to book imports / user to airbnb imports
        $book->room_id       = $room_id;
        $book->start         = $start->format("Y-m-d");
        $book->finish        = $finish->format("Y-m-d");
        $book->comment       = $data['status_reason'];
        $book->book_comments = $book_comments;
        $book->type_book     = $type_book;
        $book->nigths        = $nights;
        $book->agency        = $agency;
        $book->pax           = $pax;
        $book->total_price   = $data['amount'];
        $book->rId_wubook    = $data['reservation_code'];
       
        return $book->save();
    }
    
   
    
    
    
    
    function create() {
      if ($this->token){
        
        $customer= [
          'lname'=> 'Blissett',
          'email'=> 'my@email.com',
          'fname'=> 'Luther',
          'city' => 'Fano',
          'street' => 'via giammaria 33',
          'country' => 'IT',
          'lang' => 'IT',
          'phone' => '+3964329779',
          'arrival_hour'=> '14:00',
          'notes' => 'some notes!!!'
            ];


        $dfrom = '2020-06-01';
        $dto    = '2020-06-07';
         $dfromTime = strtotime($dfrom);
         $dtoTime = strtotime($dto);
        $rooms = ["temp-dataid-433743"=>[1, 'nb']]; //array Rooms ID

        $param = [
            $this->token,
            $this->iCode,
            date('d/m/Y',$dfromTime),
            date('d/m/Y',$dtoTime),
            $rooms,
            $customer,
            '100'
        ];
//        var_dump($this->token);
        $obj = XML_RPC::CallMethod("https://wired.wubook.net", 'new_reservation',$param);
        dd($obj);
        $aResponse = $obj->params->param->value;//->array->data;
        dd($aResponse);
  //      1769239951.5388

        $this->token = null;
      }
      return FALSE; 
      
    }
    
    
    
    
        /**
     * Get prices to all Rooms: from $dfrom to $dto
     * @param type $dfrom
     * @param type $dto
     * @return boolean
     */
     public function fetch_plan_prices($dfrom='2020-06-01',$dto='2020-07-01') {
      
      if ($this->token){
     
        $dfromTime = strtotime($dfrom);
        $dtoTime = strtotime($dto);
        $rooms = [433742,433743,433744]; //array Rooms ID
        $param = [
            $this->token,
            $this->iCode,
            $this->price_plan,
            date('d/m/Y',$dfromTime),
            date('d/m/Y',$dtoTime),
            $rooms
        ];

        $aResponse = $this->call('fetch_plan_prices',$param);
       
        $oneDay = 24*60*60;
        $pricesRooms = array();
        if ($aResponse->value[0] == 0){
          
          
          //iMPORTANT: REMOVE OLD VALUES ..!!!!
           DailyPrices::where('date','>=',$dfrom)
                  ->where('date','<=',$dto)
                  ->delete();
          
//          die('asdfad');
          $roomsID = $this->WBConfig->roomsEquivalent();

          $members = $aResponse->value[1]->struct;
          foreach ($members as $member){
            foreach ($member as $data){
              $start = $dfromTime;
              $pId = $data->name->__toString();
              if (isset($roomsID[$pId])){
                $id_room = $roomsID[$pId];
                $aux = $data->value->array->data;
                foreach ($aux as $value){
                   foreach ($value as $v){
                     if ($dtoTime<$start) break;
                    $datePrice = date('Y-m-d',$start);
                    $price     = $v->double->__toString();
                           
                    if (is_numeric($id_room)){
                      $pricesRooms[] = [
                         'channel_group'    => $id_room,
                         'rId_wubook' => $pId,
                         'date'       => $datePrice,
                         'price'      => $price
                      ];
                    } else {
                      if (is_array($id_room) && count($id_room)>0){
                        
                        foreach ($id_room as $id){
                          $pricesRooms[] = [
                            'channel_group'    => $id,
                            'rId_wubook' => $pId,
                            'date'       => $datePrice,
                            'price'      => $price
                          ];
                          
                        }
                      }
                    }
                    $start += $oneDay;
                   }
                   if (count($pricesRooms)>15){
                     DailyPrices::insert($pricesRooms);
                     $pricesRooms = array();
                   }
                }
              }
               
              if (count($pricesRooms)>0){
                DailyPrices::insert($pricesRooms);
                $pricesRooms = array();
              }
                
            }
          }
          

           return true; 
        }
      }
      return FALSE; 
    }
    
    
    
    
}