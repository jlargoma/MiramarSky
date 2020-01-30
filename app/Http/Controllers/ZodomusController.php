<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use \Carbon\Carbon;
use Auth;
use App\Services\Zodomus\Zodomus;
use App\Services\Zodomus\Config as ZConfig;
use App\Rooms;
use App\DailyPrices;
use App\ChannelManagerQueues;

class ZodomusController extends Controller {

  function index($apto = null) {
//      $Zodomus =  new \App\Services\Zodomus\Zodomus();
    $zConfig = new ZConfig();
//      $aptos = $zConfig->propertyList();
    $aptos = configZodomusAptos();
    $channels = $zConfig->Channels();
//      dd(reset($aptos));
    if (!$apto) {
      $aux = reset($aptos);
      $apto = null;
    }

    return view('backend/zodomus/index', [
        'aptos' => $aptos,
        'apto' => $apto,
        'channels' => $channels,
//          'roomsName' => $rooms,
    ]);
  }

  /**
   * 
   * @param Request $request
   * @param type $apto
   * @return type
   */
  public function listBy_apto(Request $request, $apto) {


//    /      $oZodomus = new Zodomus();
//      $rates = $oZodomus->getRates($apto);
//      $colors = $zConfig->colors();
//      if (isset($rates->status) && $rates->status->returnCode == 200){
//        $roomsObj = $rates->rooms;
//        $rooms = [];
////        $roomsColor = [];
//        foreach ($roomsObj as $k=>$r){
//          $rooms[$r->id] = $r->name;
////          $roomsColor[$r->id] = $colors[$k];
//        }
//      }

    $response = [];

    $zConfig = new ZConfig();
    $colors = $zConfig->colors();

    $oZodomus = new Zodomus();

    $start = $request->input('start', null);
    $end = $request->input('end', null);
    $now = strtotime(date('Y-m-d'));
    if (!$start || !$end)
      return null;

    $endTime = strtotime($end);
    if ($endTime < time())
      return null;

    $startTime = strtotime($start);
    if ($startTime < time())
      $startTime = $now;

    $oneDay = 24 * 60 * 60;
    $step_1 = $startTime + (31 * $oneDay); //limit Zodomus

    $startDate = date('Y-m-d', $startTime);
    $endDate = date('Y-m-d', $endTime);



    /** BEGIN: Rooms Availability */
    if ($step_1 > $endTime) {
      $availability = $oZodomus->getRoomsAvailability($apto, $startDate, $endDate);
      $response = $this->getEventsAvail($availability);
    } else {
      $availability = $oZodomus->getRoomsAvailability($apto, $startDate, date('Y-m-d', $step_1));
      $response = $this->getEventsAvail($availability);
      $availability = $oZodomus->getRoomsAvailability($apto, date('Y-m-d', ($step_1 - $oneDay)), $endDate);
      $response = $this->getEventsAvail($availability, $response);
//          dd($response);
    }

    $render = [];
    foreach ($response as $roomID => $data) {
      $start = $end = $text = null;
      foreach ($data as $time => $tit) {
        if (!$text) {
          $text = $tit;
          $start = $time;
        }
        if ($text != $tit) {
          $render[] = [
              "title" => $tit,
              "start" => $start,
              "end" => $time,
              "color" => isset($colors[$roomID]) ? $colors[$roomID] : '#c1c1c1'
          ];

          $text = $tit;
          $start = $time;
        }

        $end = $time;
      }

      $render[] = [
          "title" => $text,
          "start" => $start,
          "end" => $end,
          "color" => isset($colors[$roomID]) ? $colors[$roomID] : '#c1c1c1'
      ];
    }
//      dd($render,$response); die;

    /** BEGIN: Rooms Availability */
    return $render;
  }

  private function getEventsAvail($availability, $response = []) {
    if (isset($availability->status) && $availability->status->returnCode == 200) {
      $roomsAvail = $availability->rooms;
      foreach ($roomsAvail as $r) {
        $roomID = $r->id;
        $dates = $r->dates;
        foreach ($dates as $d) {
          if ($d->booked)
            $title = 'Bloqueado';
          else {
            if ($d->cancelled)
              $title = 'Cancelado';
            else
              $title = $d->availability . ' Disp';
          }
          $response[$roomID][$d->date] = $title;
//          $response[$roomID][strtotime($d->date)] = [
//              "title" => $title,
//              "start" => $d->date,
//              "color"=> isset($roomsColor[$roomID]) ? $roomsColor[$roomID] : '#c1c1c1'
//          ];
        }
      }
    }

    return $response;
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function calendRoom($room = null) {

    $zConfig = new ZConfig();
    $aptos = configZodomusAptos();
//    $channels = $zConfig->Channels();

    $rooms = [];
    foreach ($aptos as $k => $item) {
      if (!$room) $room = $k;
      $rooms[$k] = $item->name;
    }

    $dw = listDaysSpanish(true);
    
    return view('backend/zodomus/cal-room', [
        'rooms' => $rooms,
        'room' => $room,
        'dw' => $dw,
    ]);
  }

  public function calendRoomUPD(Request $request, $apto) {
    
    $date_range = $request->input('date_range', null);
    
    $price = $request->input('price', null);
    $min_estancia = $request->input('min_estancia', null);
    if (!$date_range)
      return response()->json(['status'=>'error','msg'=>'Debe seleccionar al menos una fecha de inicio']);
    
    if (!$price || $price<0)
      return response()->json(['status'=>'error','msg'=>'Debe seleccionar al menos una fecha de inicio']);

    $date = explode(' - ', $date_range);
    
    
    $startTime = strtotime(convertDateToDB($date[0]));
    $endTime = strtotime(convertDateToDB($date[1]));
    
   
    
    $dw = listDaysSpanish(true);
    $dayWeek = [];
    
    
    $weekDaysID =  [
          0=>"sun",
          1=>"mon",
          2=>"tue",
          3=>"wed",
          4=>"thu",
          5=>"fri",
          6=>"sat",
        ];
            
    $weekDays = [];
    foreach ($dw as $k => $v) {
      if ($request->input('dw_' . $k, null)){
        $dayWeek[] = $k;
        $weekDays[] = $weekDaysID[$k];
      }
    }
    $weekDays = implode('|', $weekDays);
    
    
    
    if (count($dayWeek) == 0)
      return response()->json(['status'=>'error','msg'=>'Debe seleccionar al menos un día de la semana']);
    
    
    $uID = Auth::user()->id;
    
    $day = 24*60*60;
    $startTimeAux = $startTime;
    while ($startTimeAux<=$endTime){
      $dWeek = date('w',$startTimeAux);
      if (in_array($dWeek,$dayWeek)){
        $oPrice = DailyPrices::where('channel_group', $apto)
              ->where('date', '=', date('Y-m-d',$startTimeAux))
              ->first();
        if (!$oPrice){
          $oPrice = new DailyPrices();
          $oPrice->channel_group = $apto;
          $oPrice->date = date('Y-m-d',$startTimeAux);
        }
        $oPrice->price = $price;
        $oPrice->min_estancia = $min_estancia;
        $oPrice->user_id = $uID;
        $oPrice->save();
      }
      $startTimeAux += $day;
    }

    
    
    
    $insert = [
        'price'=>$price,
        'minimumStay'=>$min_estancia,
        'weekDays'=>$weekDays,
        'channel_group'=>$apto,
        'date_start'=>date('Y-m-d',$startTime),
        'date_end'=>date('Y-m-d',$endTime),
        'sent'=>0,
    ];
    ChannelManagerQueues::insert($insert);
    
    
    $response = $this->sendPricesZodomus($insert);
    if ($response) {
      return response()->json(['status'=>'error','msg'=>'Channel Manager: '.$response]);
    }
    return response()->json(['status'=>'OK','msg'=>'datos cargados']);
  }

  /**
   * 
   * @param Request $request
   * @param type $apto
   * @return type
   */
  public function listBy_room(Request $request, $apto) {

    $prices = [];
    $start = $request->input('start', null);
    $end = $request->input('end', null);

    $room = Rooms::where('channel_group',$apto)->first();
    if (!$room)
      return null;

    $pax = $room->minOcu;
    if ($start && $end) {
      
      $start = (convertDateToDB($start));
      $end = (convertDateToDB($end));


      $defaults = $room->defaultCostPrice($start, $end, $pax);
      $priceDay = $defaults['priceDay'];
      $oPrice = DailyPrices::where('channel_group', $apto)
              ->where('date', '>=', $start)
              ->where('date', '<=', $end)
              ->get();
      if ($oPrice) {
        foreach ($oPrice as $p) {
          $priceDay[$p->date] = $p->price;
        }
      }
      $priceLst = [];
      foreach ($priceDay as $d => $p) {
       
          $priceLst[] = [
              "title" => $p . ' €',
              "start" => $d,
          ];

      }



      return response()->json($priceLst);
    }




    return response()->json($prices);
  }

  function generate_config() {
    ///admin/channel-manager/config
//    $condif = configZodomusAptos(); dd($condif); die;
//    $confFile = \Illuminate\Support\Facades\File::get(storage_path('app/config/zodomus.php'));
//  eval($confFile);
  }
  
  
  
  /**
   * Send the new rates to Zodomus
   * 
   * @param type $data
   * @return type
   */
  private function sendPricesZodomus($data) {
    
    //week Days 
    $weekDays =  [
          "sun" =>  "false",
          "mon" =>  "false",
          "tue" =>  "false",
          "wed" =>  "false",
          "thu" =>  "false",
          "fri" =>  "false",
          "sat" =>  "false"
        ];
    
    $weekDaysLst = explode('|', $data['weekDays']);
    foreach ($weekDaysLst as $wd){
      if (isset($weekDays[$wd])) $weekDays[$wd] = "true";
      $weekDays[$wd] = "true";
    }
    
    //min Advance Res
    $minimumStay = null;
    if ($data['minimumStay']>0) $minimumStay = $data['minimumStay'];
    
    
    $oZodomus = new Zodomus();
    $channel_group = $data['channel_group'];
    $aptos = configZodomusAptos();
    
    $data['date_end'] = date('Y-m-d', strtotime($data['date_end'])+(24*60*60));
    foreach ($aptos as $cg => $apto){
      if ($cg == $channel_group){
        //send all channels
        foreach ($apto->rooms as $room){
              $param = [
                "channelId" =>  $room->channel,
                "propertyId" => $room->propID,
                "roomId" =>  $room->roomID,
                "dateFrom" => $data['date_start'],
                "dateTo" => $data['date_end'],
                "currencyCode" =>  "EUR",
                "rateId" =>  $room->rateID,
                "weekDays" => $weekDays,
                "prices" =>   [
                  "price" => $data['price'].""
                ],
                "closed" =>  0,//"0=false , 1=true, (optional restrition)",
              ];

              if ($minimumStay){
      //          "minimumStay" => '2D',// "4D = four days; 4D4H = four days and four hours, (optional restrition)",
                $param['minimumStay'] = $minimumStay;
                $param['minimumStayArrival'] = $minimumStay;
              }
              
              $errorMsg = $oZodomus->setRates($param);
              if ($errorMsg){
                return $errorMsg;
              }
        }
      }
    }
    
    return null;
    
   
  }
  
  /**
   * /admin/channel-manager/ZODOMUS
   */
  function zodomusTest(){
    
    /*2092950
4284223
6037501
2942955
5813366
2798863
     * */
     
      $Zodomus =  new \App\Services\Zodomus\Zodomus();
      $apto = 1542253; 
      $roomID = 294200801;
      $rateId = 6280183;
      $return = null;
//      $return = $Zodomus->getInfo();
//      
//      $return = $Zodomus->activateChannels($apto);
//      $return = $Zodomus->getRates($apto);
//      $return = $Zodomus->getRoomsAvailability($apto,'2020-02-05','2020-02-11');

      
      $aptos = configZodomusAptos();
      $rooms = [];
      foreach ($aptos as $k => $item) {
        foreach ($item->rooms as $room) {
          if ($room->propID == $apto && $room->channel == 1)
            $rooms[] = [
                "roomId" =>  $room->roomID,
                "roomName" =>   $room->name,
                "status" =>  1,
                "quantity" =>1, //max availability
                "rates" =>  [$rateId]
                ];
        }
      }
    
      $roomToAt = [
        "channelId" =>  1,
        "propertyId" => $apto,
        "rooms" =>  $rooms
      ];
//      dd($roomToAt);
//      
//      $return = $Zodomus->activateRoom($roomToAt);
      
        $return = $Zodomus->checkProperty($apto);
       
      
//      $return = $Zodomus->getBookings(1,$apto); //1234567894827  1234567898746
//      $return = $Zodomus->createTestReserv($apto);
      
//      $Zodomus->createRoom();
       $paramRates = [
                "channelId" =>  1,
                "propertyId" => $apto,
                "roomId" =>  $roomID,
                "dateFrom" => "2020-07-01",
                "dateTo" => "2020-07-05",
                "currencyCode" =>  "EUR",
                "rateId" =>  $rateId,
                "baseOccupancy" =>  "1",
//                "weekDays" => $weekDays,
                "prices" => ['price'=>450 ],
//                "closed" =>  0,//"0=false , 1=true, (optional restrition)",
              ];
//      $return = $Zodomus->setRates($paramRates);
//      $return = $Zodomus->setRatesDerived($apto);
        $paramAvail = [
                "channelId" =>  1,
                "propertyId" => $apto,
                "roomId" =>  $roomID,
                "dateFrom" => "2020-07-01",
                "dateTo" => "2020-07-05",
                "availability" =>  1,
              ];
       
       
//      $return = $Zodomus->setRoomsAvailability($paramAvail);
//      $return = $Zodomus->getRates($apto);
       
//      $return = $Zodomus->getSummary($apto);
      if ($return)   dd($return);
      
  }
  
  
  function sendAvail(Request $request, $apto) {
    
    $date_range = $request->input('date_range', null);
    
    if (!$date_range)
      return back()->withErrors(['Debe seleccionar al menos una fecha de inicio']);
    
    $date = explode(' - ', $date_range);
    $startTime = (convertDateToDB($date[0]));
    $endTime = (convertDateToDB($date[1]));
    
    
    $room = Rooms::where('channel_group',$apto)->first();
    if ($room){
      $book = new \App\Book();
      $book->sendAvailibility($room->id,$startTime,$endTime);
      return back()->with(['success'=>'Disponibilidad enviada']);
    } else {
      return back()->withErrors(['No posee apartamentos asignados']);
    }
    
  }
  
  function webHook(Request $request) {
    
    //save a copy
    $json = json_encode($request->all());
    $dir = storage_path().'/zodomus';
    if (!file_exists($dir)) {
        mkdir($dir, 0775, true);
    }
    file_put_contents($dir."/".time(),$json);
    
    
    $webhookKey = $request->input('webhookKey');// (Your webhook key, you should validate if a call to your webhook has the correct key)
    $channelId = $request->input('channelId');//channelId (Integer)
    $propertyId = $request->input('propertyId');//propertyId (String)
    $reservationId = $request->input('reservationId');//reservationId (String, sent from the channel to identify the reservation)
    $reservationStatus = $request->input('reservationStatus');//(Integer 1=new, 2=modified, 3=cancelled)
    
    if ($webhookKey == env('ZODOMUS_WEBHOOK')){
      if ($reservationStatus == 1){
        
        
        
        $oZodomus = new Zodomus();
        $zConfig = new ZConfig();

        $param = [
                "channelId" =>  $channelId,
                "propertyId" => $propertyId,
                "reservationId" =>  $reservationId,
              ];
        $reservation = $oZodomus->getBooking($param);
        
        if ($reservation && $reservation->status->returnCode == 200){
          $booking = $reservation->reservations;
          //check if exists
          $alreadyExist = \App\Book::where('external_id', $reservationId)->first();
          if ($alreadyExist){
            if ($booking->reservation->status == 3){ //Cancelada
              
              $response = $alreadyExist->changeBook(3, "", $alreadyExist);
              if ($response['status'] == 'success' || $response['status'] ==  'warning'){
                //Ya esta disponible
                $alreadyExist->sendAvailibilityBy_status();
              }
            }
            
            return 'ok';
          }
          
          if ($booking->reservation->status != 1) return 'ok';
        
          
          if ($booking) {
            $roomId = $booking->rooms[0]->id;
            
            $cg = $oZodomus->getChannelManager($channelId,$propertyId,$roomId);
            if (!$cg) return false;

            $reserv = [
                'channel' => $channelId,
                'channel_group' => $cg,
                'agency' => $zConfig->getAgency($channelId),
                'reser_id' => $reservationId,
                'status' => $booking->reservation->status,
                'customer' => $booking->customer,
                'totalPrice' => $booking->rooms[0]->totalPrice,
                'numberOfGuests' => $booking->rooms[0]->numberOfGuests,
                'mealPlan' => $booking->rooms[0]->mealPlan,
                'start' => $booking->rooms[0]->arrivalDate,
                'end' => $booking->rooms[0]->departureDate,
            ];
            
            
            
          $roomID = $oZodomus->calculateRoomToFastPayment($cg, $reserv['start'], $reserv['end']);
          if ($roomID<0){
           return 'false';
          }
          $nights = calcNights($reserv['start'], $reserv['end']);
          $book = new \App\Book();

          $rCustomer = $reserv['customer'];
          $customer = new \App\Customers();
          $customer->user_id = 23;
          $customer->name = $rCustomer->firstName . ' ' . $rCustomer->lastName . ' - ZodomusWH ';
          $customer->email = $rCustomer->email;
          $customer->phone = $rCustomer->phoneCountryCode . ' ' . $rCustomer->phoneCityArea . ' ' . $rCustomer->phone;
          $customer->DNI = "";
          $customer->save();

          //Create Book
          $book->user_id = 39;
          $book->customer_id = $customer->id;
          $book->room_id = $roomID;
          $book->start = $reserv['start'];
          $book->finish = $reserv['end'];
          $book->comment = $reserv['mealPlan'];
          $book->type_book = 11;
          $book->nigths = $nights;
          $book->agency = $reserv['agency'];
          $book->pax = $reserv['numberOfGuests'];
          $book->PVPAgencia = $reserv['totalPrice'];
          $book->total_price = $reserv['totalPrice'];
          $book->external_id = $reserv['reser_id'];

          $book->save();
          return 'ok';
          }
          
          
     
        }
      } else {
        echo $reservationStatus.' error';
      }
        
    } else {
      echo 'token error';
    }
  }

}
