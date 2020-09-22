<?php

namespace App\Traits;

use App\Settings;
use Carbon\Carbon;
use App\Repositories\CachedRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\DailyPrices;
use App\Rooms;

trait OtasTraits
{
 /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function calendRoom($room = null) {

    $oConfig = $this->oConfig;
    
    $agencies = $oConfig->getAllAgency();
    $roomsLst = $oConfig->getRoomsName();
    
    $aptos = configZodomusAptos();
    $rooms = [];
    foreach ($roomsLst as $k => $name) {
      if (!$room) $room = $k;
      $rooms[$k] = $name;
    }

    $dw = listDaysSpanish(true);
    $price_booking = $price_expedia = $price_airbnb = $price_google = 0;
    
    $data = [
        'rooms' => $rooms,
        'room' => $room,
        'dw' => $dw,
        'price_booking' => 0,
        'price_expedia' => 0,
        'price_airbnb' => 0,
        'price_google' => 0,
    ];
    foreach ($agencies as $ag=>$k){
      if ($ag == 'google-hotel') $ag = 'google';
      $data['price_'.$ag] = $oConfig->priceByChannel(0,$k,$room,true);
    }
    
    return view('backend/zodomus/cal-room', $data);
  }

  /**
   * 
   * @param Request $request
   * @param type $apto
   * @return type
   */
  public function calendRoomUPD(Request $request, $apto) {
    if ($apto == 'ALL'){
      $roomsLst = $this->oConfig->getRoomsName();
      $count = 0;
      foreach ($roomsLst as $k=>$v){
        $this->calendRoomUPD_byRoom($request, $k);
        $count++;
      }
      /*******************************/
      // save log data
      $lData = new \App\LogsData();
          
      $date_range = $request->input('date_range', null);
      $date = explode(' - ', $date_range);
      $startTime = convertDateToDB($date[0]);
      $endTime = convertDateToDB($date[1]);
      
      $weekDays = [];
      $diaSemana = listDaysSpanish(true); 
      for($i=0;$i<7;$i++){
        if ($request->input('dw_' . $i, null)){
          $weekDays[] = $diaSemana[$i];
        }
      }
      $min_estancia = $request->input('min_estancia', null);
      $dataLog = [
          'min_estancia' => $min_estancia,
          'startDate'=> $startTime,
          'endDate'  => $endTime,
          'weekDays' => implode(', ', $weekDays),
          'userID'   => Auth::user()->id
      ];
      $lData->key  = "min_stay_group";
      $lData->data =  $count.' Registros cargados';
      $lData->long_info = json_encode($dataLog);
      $lData->save();
      /*******************************/
      return response()->json(['status'=>'OK','msg'=>'datos cargados']);
    } else {
      return $this->calendRoomUPD_byRoom($request, $apto);
    }
  }
  
  /**
   * 
   * @param Request $request
   * @param type $apto
   * @return type
   */
  public function calendRoomUPD_byRoom(Request $request, $apto) {
    
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

    if($price && $price>0){
      \App\ProcessedData::savePriceUPD_toOtaGateway(date('Y-m-d',$startTime),date('Y-m-d',$endTime));
    } else {
      if ($min_estancia)
      \App\ProcessedData::saveMinDayUPD_toOtaGateway(date('Y-m-d',$startTime),date('Y-m-d',$endTime));
    }

    return response()->json(['status'=>'OK','msg'=>'datos cargados']);
  }
  
  
    /**
   * Para el calendation por habitación
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
      $agencies = $this->oConfig->getAllAgency();
      foreach ($priceDay as $d => $p) {
        $data = [
            'price_booking' => 0,
            'price_expedia' => 0,
            'price_airbnb' => 0,
            'price_google' => 0,
        ];
        foreach ($agencies as $ag=>$k){
          if ($ag == 'google-hotel') $ag = 'google';
          $data['price_'.$ag] = $this->oConfig->priceByChannel($p,$k,$apto);
        }
    
        $min_estancia = isset($min[$d]) ? $min[$d] : 0;
        
        
        $priceLst[] = [
            "title" => '<table>'
            . '<tr><td colspan="2" class="main">'.$p.' €</td></tr>'
            . '<tr><td colspan="2" class="min-estanc">'.$min_estancia.' dias</td></tr>'
            . '<tr><td><span class="price-booking">'.$data['price_booking'].'</span></td><td><span class="price-airbnb">'.$data['price_airbnb'].'</span></td></tr>'
            . '<tr><td><span class="price-expedia">'.$data['price_expedia'].'</span></td><td><span class="price-google">'.$data['price_google'].'</span></td></tr>'
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
  
  
  
}
