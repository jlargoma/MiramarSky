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
    $price_booking = $zConfig->priceByChannel(0,1,$room,true);
    $price_expedia = $zConfig->priceByChannel(0,2,$room,true);
    $price_airbnb = $zConfig->priceByChannel(0,3,$room,true);
    $price_google = $zConfig->priceByChannel(0,99,$room,true);
    return view('backend/zodomus/cal-room', [
        'rooms' => $rooms,
        'room' => $room,
        'dw' => $dw,
        'price_booking' => $price_booking,
        'price_expedia' => $price_expedia,
        'price_airbnb' => $price_airbnb,
        'price_google' => $price_google,
    ]);
  }

  public function calendRoomUPD(Request $request, $apto) {
    
    $date_range = $request->input('date_range', null);
    
    $price = $request->input('price', null);
    $min_estancia = $request->input('min_estancia', null);
    if (!$date_range)
      return response()->json(['status'=>'error','msg'=>'Debe seleccionar al menos una fecha de inicio']);
    
    if ((!$price || $price<0) && (!$min_estancia || $min_estancia<0) )
      return response()->json(['status'=>'error','msg'=>'Debe seleccionar el precio o estancia mínima']);

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
        if($price && $price>0) $oPrice->price = $price;
        if($min_estancia && $min_estancia>0) $oPrice->min_estancia = $min_estancia;
        $oPrice->user_id = $uID;
        $oPrice->save();
      }
      $startTimeAux += $day;
    }

    if($price && $price<0){
      \App\ProcessedData::savePriceUPD_toWubook(date('Y-m-d',$startTime),date('Y-m-d',$endTime));
    } else {
      /**
       * @ToDo enviar Min. estancia a WuBook*/
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

    $zConfig = new ZConfig();
    $room = Rooms::where('channel_group',$apto)->first();
    if (!$room)
      return null;

    $pax = $room->minOcu;
    if ($start && $end) {
      
      $start = (convertDateToDB($start));
      $end = (convertDateToDB($end));


      $defaults = $room->defaultCostPrice($start, $end, $pax);
      $priceDay = $defaults['priceDay'];
      $min = [];
      $oPrice = DailyPrices::where('channel_group', $apto)
              ->where('date', '>=', $start)
              ->where('date', '<=', $end)
              ->get();
      if ($oPrice) {
        foreach ($oPrice as $p) {
          $priceDay[$p->date] = $p->price;
          $min[$p->date] = $p->min_estancia;
        }
      }
      $priceLst = [];
      $redDays = [];
      foreach ($priceDay as $d => $p) {

        $priceBooking = ceil($zConfig->priceByChannel($p,1,$apto));
        $priceExpedia = ceil($zConfig->priceByChannel($p,2));
        $priceAirbnb = ceil($zConfig->priceByChannel($p,3));
        $priceGoogle = ceil($zConfig->priceByChannel($p,99));
        $min_estancia = isset($min[$d]) ? $min[$d] : 0;
        
        
        $priceLst[] = [
            "title" => '<table>'
            . '<tr><td colspan="2" class="main">'.$p.' €</td></tr>'
            . '<tr><td colspan="2" class="min-estanc">'.$min_estancia.' dias</td></tr>'
            . '<tr><td><span class="price-booking">'.$priceBooking.'</span></td><td><span class="price-airbnb">'.$priceAirbnb.'</span></td></tr>'
            . '<tr><td><span class="price-expedia">'.$priceExpedia.'</span></td><td><span class="price-google">'.$priceGoogle.'</span></td></tr>'
            . '</table>',
            "start" => $d,
            'classNames' => 'prices',
        ];

      }

      $book = new \App\Book();
      $availibility = $book->getAvailibilityBy_channel($apto, $start, $end);
      foreach ($availibility as $d => $p) {
        $class = ($p>0) ? 'yes' : 'no';
        
        if ($p<=0) $redDays[] = $d;
       
        $priceLst[] = [
            "title" => $p,
            "start" => $d.' 01:00',
            'classNames' =>  'availibility '.$class
        ];

      }

      return response()->json(['priceLst' => $priceLst,'redDays'=>$redDays]);
    }




    return response()->json($prices);
  }

  function generate_config() {
    ///admin/channel-manager/config
//    $condif = configZodomusAptos(); dd($condif); die;
    $confFile = \Illuminate\Support\Facades\File::get(storage_path('app/config/zodomus.php'));
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
    
    //min Advance Res
    $price = null;
    if ($data['price']>0) $price = $data['price'];
    
    
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
                "closed" =>  0,//"0=false , 1=true, (optional restrition)",
              ];

              if ($price){
                $param['prices'] = ["price" => $data['price'].""];
              }
              if ($minimumStay){
      //          "minimumStay" => '2D',// "4D = four days; 4D4H = four days and four hours, (optional restrition)",
                $param['minimumStay'] = $minimumStay;
                $param['minimumStayArrival'] = $minimumStay;
              }
              
              $errorMsg = $oZodomus->setRates($param,$channel_group);
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
    
     return null;
      $oZodomus =  new \App\Services\Zodomus\Zodomus();
//      $apto = 2092950; 
//      $return = $Zodomus->sendRatesGroup($apto,10084311,209295006,'EstL',1);
      
        $param = [
                "channelId" =>  1,
                "propertyId" => 1542253,
                "reservationId" =>  3537637317,
              ];
    
      $reservation = $oZodomus->getBooking($param);
      dd($reservation);    
      
       $rooms = [
        [ "roomId" => 209295006,
        "roomName" => "ESTUDIOS LUJO",
        "status" => 1,
        "quantity" =>10,
        "rates" => [10084311]
        ],
        ];
      $roomToAt = [
        "channelId" => 1,
        "propertyId" => $apto,
        "rooms" =>  $rooms
      ];
//      $return = $Zodomus->activateRoom($roomToAt);
//      
      
  dd($return);
      
      
      $rooms = [];
      $aptosLst = configZodomusAptos();
      foreach ($aptosLst as $k=>$v){
        foreach ($v->rooms as $j){
          if($j->channel == 2){
//            $rooms[] =  ["roomId" => $j->roomID,"roomName" => $j->name,"rates" => [$j->rateID],"quantity" => 10,"status" => 1];
//          echo'['.$j->roomID.','.$j->rateID.','.$k.']<br>';
          }
          
        }
      }
      die;
      
      //sendRatesGroup($rateId,$roomID,$channel_group)
//       $return = $Zodomus->activateRoom($roomToAt);
//    $return = $Zodomus->checkProperty($apto);
      
      
      $ratesRooms = [
          [231940162,'286355412A','DDE'],
          [231940164,'286355419A','DDL'],
          [231940097,'286355357A','EstS'],
          [231940160,'286355407A','EstL'],
          [231940170,'286355432A','7J'],
          [231940174,'286355437A','9F'],
          [231940167,'286355427A','10I'],
      ];
      foreach ($ratesRooms as $r){
        $return = $Zodomus->sendRatesGroup($apto,$r[1],$r[0],$r[2]);
      }
      
//     $return = $Zodomus->getRates($apto);
//    $return = $Zodomus->createTestReserv($apto);
    if ($return){
        var_dump($return); die;
        dd($return);
      }
      
  }
  
  
  function sendAvail(Request $request, $apto) {
    
    $date_range = $request->input('date_range', null);
    
    if (!$date_range)
      return back()->withErrors(['Debe seleccionar al menos una fecha de inicio']);
    
    $date = explode(' - ', $date_range);
    $startTime = (convertDateToDB($date[0]));
    $endTime = (convertDateToDB($date[1]));
    
    if ($apto == 'all'){
      $aptos = configZodomusAptos();
      $book = new \App\Book();
      foreach ($aptos as $ch=>$v){
        $room = Rooms::where('channel_group',$ch)->first();
        if ($room){
            $book->sendAvailibility($room->id,$startTime,$endTime);
        }
      }
      return back()->with(['success'=>'Disponibilidad enviada']);
    } else {
      $room = Rooms::where('channel_group',$apto)->first();
      if ($room){
        $book = new \App\Book();
        $book->sendAvailibility($room->id,$startTime,$endTime);
        return back()->with(['success'=>'Disponibilidad enviada']);
      } else {
        return back()->withErrors(['No posee apartamentos asignados']);
      }
    }
    
  }
  
  
    
  /**
   * /admin/channel-manager/forceImport
   */
  function forceImport() {
    $cannels = configZodomusAptos();
    $Zodomus = new Zodomus();
    $alreadySent = [];
    $reservas = [];
    foreach ($cannels as $cg => $apto) {
     
      //get all channels
      foreach ($apto->rooms as $room) {
        if (true) {
          $keyIteration = $room->channel . '-' . $room->propID;
          if (in_array($keyIteration, $alreadySent))
            continue;

          $channelId = $room->channel;
          $propertyId = $room->propID;
          $bookings = $Zodomus->getBookingsQueue($room->channel, $room->propID);
       
          if ($bookings && $bookings->status->returnCode == 200) {
            $alreadySent[] = $keyIteration;
            
            foreach ($bookings->reservations as $book) {
              $reservas[] = [
                  $room->channel, $room->propID,$book->id
                ];
            }
          }
        }
      }
    }
    $response = null;
    foreach ($reservas as $r){
      $alreadyExist = \App\Book::where('external_id', $r[2])->first();
      if (!$alreadyExist){
        $response = $this->importAnReserv($r[0],$r[1],$r[2]);
      }
    }
    dd($response);
  }
  
  function webHook(Request $request) {
    
    
    
    $webhookKey = $request->input('webhookKey');// (Your webhook key, you should validate if a call to your webhook has the correct key)
    $channelId = $request->input('channelId');//channelId (Integer)
    $propertyId = $request->input('propertyId');//propertyId (String)
    $reservationId = $request->input('reservationId');//reservationId (String, sent from the channel to identify the reservation)
    $reservationStatus = $request->input('reservationStatus');//(Integer 1=new, 2=modified, 3=cancelled)
    //
    //save a copy
    $json = json_encode($request->all());
    $dir = storage_path().'/zodomus';
    if (!file_exists($dir)) {
        mkdir($dir, 0775, true);
    }
    file_put_contents($dir."/".time().'-'.$reservationId.'-'.$propertyId,$json);
    
    if ($webhookKey == env('ZODOMUS_WEBHOOK')){
      if ($reservationStatus == 1 || $reservationStatus == 2){
        $this->importAnReserv($channelId,$propertyId,$reservationId);
      }else {
        echo $reservationStatus.' error';
      }
        
    } else {
      echo 'token error';
    }
  }

  function importAnReserv($channelId,$propertyId,$reservationId,$force=false){
    
        $oZodomus = new Zodomus();
        $zConfig = new ZConfig();

        $param = [
                "channelId" =>  $channelId,
                "propertyId" => $propertyId,
                "reservationId" =>  $reservationId,
              ];
        if ($force){
          $reservation = json_decode('');
        } else {
          $reservation = $oZodomus->getBooking($param);
//          echo json_encode($reservation);
        }
//        
        
        if ($reservation && isset($reservation->status) && $reservation->status->returnCode == 200){
          $booking = $reservation->reservations;
          if (!isset($booking->rooms)) {
            if ($booking->reservation->status == 3) { //Cancelada
              $alreadyExist = \App\Book::where('external_id', $reservationId)->get();
              if ($alreadyExist) {
                foreach ($alreadyExist as $item){
                  $response = $item->changeBook(3, "", $item);
                  echo $item->id.' cancelado - ';
                  if ($response['status'] == 'success' || $response['status'] == 'warning') {
                    //Ya esta disponible
                    $item->sendAvailibilityBy_status();
                  }
                }
              }
            }
            return;
          }
          //Una reserva puede tener multiples habitaciones
          $rooms = $booking->rooms;
          foreach ($rooms as $room){
            $update = false;
            $roomId = $room->id;
            $roomReservationId = $room->roomReservationId;
             //check if exists
            $alreadyExist = \App\Book::where('external_id', $reservationId)
                    ->where(function ($query) use ($roomReservationId) {
                                            $query->where('external_roomId', $roomReservationId)
                                                  ->orWhereNull('external_roomId');
                                        })->first();
                                       
            if ($alreadyExist){
              if ($booking->reservation->status == 3){ //Cancelada

                $response = $alreadyExist->changeBook(98, "", $alreadyExist);
                if ($response['status'] == 'success' || $response['status'] ==  'warning'){
                  //Ya esta disponible
                  $alreadyExist->sendAvailibilityBy_status();
                }
                continue;
              } else {
                $update = $alreadyExist->id;
              }
              
            }              
            
            $cg = $oZodomus->getChannelManager($channelId,$propertyId,$roomId);
            if (!$cg){//'no se encontro channel'
              continue;
            }
            
            $totalPrice = $room->totalPrice;
            if (isset($room->priceDetails)){
              foreach ($room->priceDetails as $priceDetails){
                if ($totalPrice < $priceDetails->total){
                  $totalPrice = $priceDetails->total;
                }
              }
            }
            if (isset($room->priceDetailsExtra)){
              foreach ($room->priceDetailsExtra as $pExtr){
                if ($pExtr->type == "guest" && $pExtr->included == 'no'){
                  $totalPrice += round($pExtr->amount,2);
                }
              }
            }
            
            $rateId   = isset($room->prices[0]) ? $room->prices[0]->rateId : 0;
            $comision = $zConfig->get_comision($totalPrice,$channelId);
            $reserv = [
                'channel' => $channelId,
                'propertyId' => $propertyId,
                'rate_id' => $rateId,
                'comision'=>$comision,
                'external_roomId' => $roomReservationId,
                'channel_group' => $cg,
                'agency' => $zConfig->getAgency($channelId),
                'reser_id' => $reservationId,
                'status' => $booking->reservation->status,
                'customer' => $booking->customer,
                'totalPrice' => $totalPrice,
                'numberOfGuests' => $room->numberOfGuests,
                'mealPlan' => $room->mealPlan,
                'start' => $room->arrivalDate,
                'end' => $room->departureDate,
            ];
            
            if ($update){
              $bookID = $oZodomus->updBooking($cg,$reserv,$update);
            } else {
              $bookID = $oZodomus->saveBooking($cg,$reserv);
            }
            if ($force){
              var_dump($cg,$reserv,$bookID);
            }
            
          }
        }
        
    }
        
  /**
   * Just to test
   * @param type $cg
   * @param type $start
   * @param type $finish
   */  
  function listDisponibilidad($cg, $start, $finish) {
//    /channel-manager/disponibilidad/ROSADC/2020-02-15/2020-02-19
    if (Auth::user()->role != "admin") return;
    $typeBooks = [
        0 => 'ELIMINADA',
        1 => 'Reservado - stripe',
        2 => 'Pagada-la-señal',
        3 => 'SIN RESPONDER',
        4 => 'Bloqueado',
        5 => 'Contestado(EMAIL)',
        6 => 'Denegada',
        7 => 'Reserva Propietario',
        8 => 'ATIPICAS',
        //'SubComunidad',
        9 => 'Booking',
        10 => 'Overbooking',
        11 => 'blocked-ical',
        12 => 'ICAL - INVISIBLE',
        99 => 'FASTPAYMENT - SOLICITUD',
    ];

    $oRooms = \App\Rooms::where('channel_group', $cg)->pluck('id')->toArray();
    $match1 = [['start', '>=', $start], ['start', '<=', $finish]];
    $match2 = [['finish', '>=', $start], ['finish', '<=', $finish]];
    $match3 = [['start', '<', $start], ['finish', '>', $finish]];

    $books = \App\Book::whereIn('type_book', [1, 2, 4, 7, 8, 9, 11, 5])->whereIn('room_id', $oRooms)
                    ->where(function ($query) use ($match1, $match2, $match3) {
                      $query->where($match1)
                      ->orWhere($match2)
                      ->orWhere($match3);
                    })->get();
    foreach ($books as $b) {
      echo $b->id . ' start: ' . $b->start . ' finish: ' . $b->finish . ' Estado: ' . $typeBooks[$b->type_book] . ' -- ';
      echo '<a href="http://admin.riadpuertasdelalbaicin.com/admin/reservas/update/' . $b->id . '">IR</a><br>';
    }
    dd($books);
  }


      /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  function calendSite($site = 1,$month=null,$year=null) {

    $zConfig = new ZConfig();
    $aptos = configZodomusAptos();
//    $aptosBySite = getAptosBySite($site);

    //Armo el calendario
    if(!$month) $month = date('m');
    if(!$year) $year = date('Y');
    
    $days = [];
    $dateTime = strtotime("$year-$month-01");
    $current = getMonthsSpanish($month,false).' '.$year;
    $start = date('Y-m-d',$dateTime);
    
    
    $day = 24*60*60;
    $dateTime -= $day;
    for($i=0;$i<35;$i++){
      $dateTime += $day;
      
      $days[date('Y-m-d',$dateTime)] = [
          'day' => date('d',$dateTime),
          'w' => date('w',$dateTime),
          'month' => date('n',$dateTime),
          'monthText' => getMonthsSpanish(date('n',$dateTime)),
          'rooms' => []
      ];
    }
    $finish = date('Y-m-d',$dateTime);
    
    $aMonth = [];
    $count = 0;
    $aux = true;
    foreach ($days as $k=>$day){
      $count++;
      if ($month != $day['month'] && $aux){
        $aux = false;
        $aMonth[] = ['colspan'=>$count-1,'text'=>getMonthsSpanish($month)];
        $count = 1;
      } elseif($count>6){
        $aMonth[] = ['colspan'=>$count,'text'=>getMonthsSpanish($month)];
        $count = 0;
      }
    }
    if ($count>0)   $aMonth[] = ['colspan'=>$count,'text'=>getMonthsSpanish($day['month'])];
    //END: Armo el calendario
    
    
    // listo los Channels Group del sitio
    $rooms = [];
    foreach ($aptos as $k => $item) {
//      if (in_array($k,$aptosBySite)){
      if (true){
        $rooms[$k] = [
            'tit' => $item->name,
            'price_booking' => $zConfig->priceByChannel(0,1,$k,1),
            'price_expedia' => $zConfig->priceByChannel(0,2,$k,1),
            'price_airbnb'  => $zConfig->priceByChannel(0,3,$k,1),
            'price_google'  => $zConfig->priceByChannel(0,99,$k,1),
        ];
      }
    }
    //END: listo los Channels Group del sitio
    
    

    //Cargo la disponibilidad y precios por día
    foreach ($rooms as $k=>$v){
      $rooms[$k]['data'] = $this->getPriceDay_group($k,$start,$finish);
    }
    $dw = listDaysSpanish(true);
//    $price_booking = $zConfig->priceByChannel(0,1,$room);
//    $price_expedia = $zConfig->priceByChannel(0,2,$room);
//    $price_airbnb = $zConfig->priceByChannel(0,3,$room);
    return view('backend/zodomus/cal-sites', [
        'rooms' => $rooms,
        'site' => $site,
        'dw' => $dw,
        'days' => $days,
        'month' => $month,
        'year' => $year,
        'aMonth' => $aMonth,
        'current' => $current,
        'prev' => date('m/Y',strtotime('-1 month'.$start)),
        'next' => date('m/Y',strtotime('+1 month'.$start)),
    ]);
  
  }
  
  function getPriceDay_group($ch,$start,$end){
    // public function listBy_room(Request $request, $apto) {
    $prices = [];
    $zConfig = new ZConfig();
    $room = Rooms::where('channel_group',$ch)->first();
    if (!$room)   return null;
    $defaults = $room->defaultCostPrice($start, $end, $room->pax);
    $priceDay = $defaults['priceDay'];
    $min = [];
    $oPrice = DailyPrices::where('channel_group', $ch)
            ->where('date', '>=', $start)
            ->where('date', '<=', $end)
            ->get();
    if ($oPrice) {
      foreach ($oPrice as $p) {
        if ($p->price) $priceDay[$p->date] = $p->price;
        $min[$p->date] = $p->min_estancia;
      }
    }
    $priceLst = [];
    $redDays = [];
    foreach ($priceDay as $d => $p) {
      $min_estancia = isset($min[$d]) ? $min[$d] : 0;
      $priceLst[$d] = [
          $p,
          $min_estancia,
          ceil($zConfig->priceByChannel($p,1,$ch)),
          ceil($zConfig->priceByChannel($p,2,$ch)),
          ceil($zConfig->priceByChannel($p,3,$ch)),
        ];
    }
    $book = new \App\Book();
    $availibility = $book->getAvailibilityBy_channel($ch, $start, $end,true);
    
    return [
        'priceLst' => $priceLst,
        'avail'=>$availibility[0],
        't_rooms'=>$availibility[1]
    ];
      
  }
  
  /**
   * Update Price or Min by cal-2
   * @param Request $request
   * @return type
   */
  function calendSiteUpd(Request $request){
  
  
  $items = $request->input('items');
  $val = $request->input('val');
  $type = $request->input('type');
  $lst = array();
  $lstAllDays = array();
  
  if (!$val || $val<0)
    return response()->json(['status'=>'error','msg'=>'Debe seleccionar el valor a modificar']);
      
  if (!$type)
    return response()->json(['status'=>'error','msg'=>'Error de tipo de datos']);
      
  if (!$items || count($items)<1)
    return response()->json(['status'=>'error','msg'=>'Error de datos']);
      
      
  if ($items){
    foreach ($items as $v){
      $aux = explode('@',$v);
      if (!isset($lst[$aux[0]])) $lst[$aux[0]] = array();
      if (!isset($lstAllDays[$aux[0]])) $lstAllDays[$aux[0]] = array();
       $lst[$aux[0]][] = strtotime($aux[1]);
       $lstAllDays[$aux[0]][] = $aux[1];
       
    }
  }
  
  $day = 24*60*60;
  $lstRangeDay = [];
  if ($lst){
    foreach ($lst as $k=>$v){
      $lstRangeDay[$k] = [];    
      $start = $startDate = $endDate = null;
      $aux = [];
      sort($v);
      foreach ($v as $k2=>$v2){
        
        if ($start){
          if ($v2 == $start+$day){
            $start = $v2;
            $endDate = date('Y-m-d',$v2);
           
          } else {
            $aux[] = [$startDate,$endDate];
            $start = $v2;
            $startDate = date('Y-m-d',$v2);
            $endDate = date('Y-m-d',$v2);
          }
        } else {
          $start = $v2;
          $startDate = date('Y-m-d',$v2);
          $endDate = date('Y-m-d',$v2);
        }
        
      }
      if ($endDate){
        $aux[] = [$startDate,$endDate];
      }
      
      
      $lstRangeDay[$k] = $aux;
    }
  }
  
  
  
  //save registers
  $uID = Auth::user()->id;
    
  foreach ($lstAllDays as $k=>$v){
    $oPrice = DailyPrices::where('channel_group', $k)
              ->whereIn('date', $v)
              ->get();
    if ($oPrice){
      foreach ($oPrice as $p){
        if (($key = array_search($p->date, $v)) !== false) {
          unset($v[$key]);
          
          if ($type == 'price') $p->price = floatval ($val);
          if ($type == 'minDay') $p->min_estancia = intval ($val);
          $p->user_id = $uID;
          $p->save();
        }
      }
    }
    
    if (count($v)>0){
      foreach ($v as $d){
        $p = new DailyPrices();
        $p->date = $d;
        $p->channel_group = $k;
        if ($type == 'price') $p->price = floatval ($val);
        if ($type == 'minDay') $p->min_estancia = intval ($val);
        $p->user_id = $uID;
        $p->save();
      }
    }
    
  }

  //BEGIN: imforma los precios cambiados para Wubook
  $min_day = $max_day = null;
  foreach ($lstAllDays as $k=>$v){
    foreach ($v as $day){
      if (!$min_day){
        $min_day = $max_day = $day;
      } else {
        if ($min_day>$day) $min_day = $day;
        if ($max_day<$day) $max_day = $day;
      }
    }
  }
  \App\ProcessedData::savePriceUPD_toWubook($min_day,$max_day);
  //END: imforma los precios cambiados para Wubook
  
  $weekDays = "sun|mon|tue|wed|thu|fri|sat";
  $min_estancia = $price = null;
  if ($type == 'price') $price = floatval ($val);
  if ($type == 'minDay') $min_estancia = intval ($val);
   
  foreach ($lstRangeDay as $k=>$v){
    foreach ($v as $k1=>$v1){
       $insert = [
           'price'=>$price,
           'minimumStay'=>$min_estancia,
           'weekDays'=>$weekDays,
           'channel_group'=>$k,
           'date_start'=>$v1[0],
           'date_end'=>$v1[1],
           'sent'=>0,
       ];
       $response = $this->sendPricesZodomus($insert);
       if ($response) {
         return response()->json(['status'=>'error','msg'=>'Channel Manager: '.$response]);
       }
    }
  }
 
  return response()->json(['status'=>'OK','msg'=>'datos cargados']);
 
  }
  
  
  
  
}
