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
    
    $rooms = [];
    foreach ($roomsLst as $k => $name) {
      if (!$room) $room = $k;
      $rooms[$k] = $name;
    }

    $dw = listDaysSpanish(true);
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
      $startTime = $request->input('date_start', null);
      $endTime = $request->input('date_end', null);
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

    $OtaGateway = new \App\Services\OtaGateway\OtaGateway();
    if (!$OtaGateway->conect() )
      return response()->json(['status'=>'error','msg'=>'Ota no conectada']);

        
//    $date = explode(' - ', $date_range);
    
    $startTime = strtotime($request->input('date_start', null));
    $endTime   = strtotime($request->input('date_end', null));
    
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
    
    $startTimeAux = $startTime;
    $aPrices = $aMinStay = [];
    while ($startTimeAux<=$endTime){
      $dWeek = date('w',$startTimeAux);
      if (in_array($dWeek,$dayWeek)){
        $dateItem = date('Y-m-d',$startTimeAux);
        $oPrice = DailyPrices::where('channel_group', $apto)
              ->where('date', '=', $dateItem)
              ->first();
        if (!$oPrice){
          $oPrice = new DailyPrices();
          $oPrice->channel_group = $apto;
            $oPrice->date = $dateItem;
        }
        if($price && $price>0){
          $oPrice->price = $price;
          $aPrices[$dateItem] = $price;
        }
        if($min_estancia && $min_estancia>0){
          $oPrice->min_estancia = $min_estancia;
          $aMinStay[$dateItem] = ['min_stay'=> intval($min_estancia)];
        }
        $oPrice->user_id = $uID;
        $oPrice->save();
      }
      
      $startTimeAux = strtotime('+1 day', $startTimeAux);
    }
    
    
    $roomTypeID = $this->oConfig->getRooms($apto);

    if (count($aPrices)){
      $resp = $OtaGateway->setRates(["price"=>[$roomTypeID=>$aPrices]]);
    }
    
    if (count($aMinStay)){
      $OtaGateway->setMinStay(['restrictions'=>[$roomTypeID=>$aMinStay]]);
    }
    
//
//    if($price && $price>0){
//      \App\ProcessedData::savePriceUPD_toOtaGateway(date('Y-m-d',$startTime),date('Y-m-d',$endTime));
//    } else {
//      if ($min_estancia)
//      \App\ProcessedData::saveMinDayUPD_toOtaGateway(date('Y-m-d',$startTime),date('Y-m-d',$endTime));
//    }

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
          if (isset($priceDay[$p->date]) && $p->price)
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
            "title" => ''.$p.' €<p class="min-estanc">'.$min_estancia.' dias</p>'
            . '<table class="t-otas">'
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
  
  
    /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  function calendSite($month=null,$year=null) {

    
    
    $oConfig = $this->oConfig;
    
    $agencies = $oConfig->getAllAgency();
    $roomsLst = $oConfig->getRoomsName();
    $months = getMonthsSpanish(null,false,true);
    
    /**************************************************************************************/
    
    //Armo el calendario
    if(!$month) $month = date('m');
    if(!$year) $year = date('Y');
    
    $days = [];
    $dateTime = strtotime("$year-$month-01");
    $current = getMonthsSpanish($month,false).' '.$year;
    $start = date('Y-m-d',$dateTime);
    
    
    $dateTime = strtotime('-1 day', $dateTime);
    for($i=0;$i<35;$i++){
      $dateTime = strtotime('+1 day', $dateTime);
      
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
    foreach ($roomsLst as $room => $roomNAme) {
        $dataAux =[
            'tit' => $roomNAme,
            'price_booking' => 0,
            'price_expedia' => 0,
            'price_airbnb'  => 0,
            'price_google'  => 0,
            'price_agoda'  => 0,
        ];
        
        
        foreach ($agencies as $ag=>$agencID){
          if ($ag == 'google-hotel') $ag = 'google';
          $dataAux['price_'.$ag] = $oConfig->priceByChannel(0,$agencID,$room,true);
        }
        $rooms[$room] = $dataAux;
    }
    //END: listo los Channels Group del sitio
    
    
    //Cargo la disponibilidad y precios por día
    foreach ($rooms as $k=>$v){
      $rooms[$k]['data'] = $this->getPriceDay_group($k,$start,$finish);
    }
    $dw = listDaysSpanish(true);

    return view('backend/zodomus/cal-sites', [
        'rooms' => $rooms,
        'dw' => $dw,
        'days' => $days,
        'month' => $month,
        'monthsLst' => $months,
        'year' => $year,
        'aMonth' => $aMonth,
        'current' => $current,
        'otaAvail' => $this->getOtaAvail($start,$finish),
        'prev' => date('m/Y',strtotime('-1 month'.$start)),
        'next' => date('m/Y',strtotime('+1 month'.$start)),
    ]);
  
  }
  
  function getPriceDay_group($ch,$start,$end){
    // public function listBy_room(Request $request, $apto) {
    $prices = [];
    $oConfig = $this->oConfig;
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
          'booking'=>ceil($oConfig->priceByChannel($p,1,$ch)),
          'expedia'=>ceil($oConfig->priceByChannel($p,28,$ch)),
          'airbnb'=>ceil($oConfig->priceByChannel($p,4,$ch)),
          'google'=>ceil($oConfig->priceByChannel($p,99,$ch)),
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
  if ($type == 'price')
    \App\ProcessedData::savePriceUPD_toOtaGateway($min_day,$max_day);
  if ($type == 'minDay')
    \App\ProcessedData::saveMinDayUPD_toOtaGateway($min_day,$max_day);
  
  return response()->json(['status'=>'OK','msg'=>'datos cargados']);
 
  }
  
  function getOtaAvail($start,$end){
  
    $cKey = md5('OA'.$start.$end);
    $sCache = new \App\Services\CacheData($cKey);
    $cache = $sCache->get();
    if ($cache) return $cache;
    
    $oConfig = $this->oConfig;
    $OtaGateway = new \App\Services\OtaGateway\OtaGateway();
    $aRoomIDs = $this->oConfig->getRooms();
    $auxDay = arrayDays($start,$end,'Y-m-d',0);
    $result = [];
    foreach ($aRoomIDs as $ch=>$r){
      $result[$ch] = $auxDay;
    }
    
    if (!$OtaGateway->conect())   return 'Ota no conectada';
    $avail = $OtaGateway->getAvailability($start,$end);
    if (isset($avail->availability)){
      foreach ($avail->availability as $rID => $a){
        $ch = array_search($rID, $aRoomIDs);
        if($ch){
          foreach ($a as $date => $av)
            $result[$ch][$date]=$av;
        }
      }
    }
    
    $sCache->set($result);
    return $result;
  }
  
}
