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

    $dw = listDaysSpanish();
    
    return view('backend/zodomus/cal-room', [
        'rooms' => $rooms,
        'room' => $room,
        'dw' => $dw,
    ]);
  }

  public function calendRoomUPD(Request $request, $apto) {
    $start = $request->input('date_start', null);
    $end = $request->input('date_end', null);
    $price = $request->input('price', null);
    $min_estancia = $request->input('min_estancia', null);
    if (!$start)
      return response()->json(['status'=>'error','msg'=>'Debe seleccionar al menos una fecha de inicio']);
    if (!$end)
      return response()->json(['status'=>'error','msg'=>'Debe seleccionar al menos una fecha de fin']);
    if (!$price || $price<0)
      return response()->json(['status'=>'error','msg'=>'Debe seleccionar al menos una fecha de inicio']);

    $dw = listDaysSpanish();
    $dayWeek = [];
    foreach ($dw as $k => $v) {
      if ($request->input('dw_' . $k, null))
        $dayWeek[] = $k;
    }
    
    if (count($dayWeek) == 0)
      return response()->json(['status'=>'error','msg'=>'Debe seleccionar al menos un día de la semana']);
    
    
    $uID = Auth::user()->id;
    
    
    $startTime = strtotime(convertDateToDB($start));
    $endTime = strtotime(convertDateToDB($end));
    
    $day = 24*60*60;
    while ($startTime<=$endTime){
      $dWeek = date('w',$startTime);
      if (in_array($dWeek,$dayWeek)){
        $oPrice = DailyPrices::where('channel_group', $apto)
              ->where('date', '=', date('Y-m-d',$startTime))
              ->first();
        if (!$oPrice){
          $oPrice = new DailyPrices();
          $oPrice->channel_group = $apto;
          $oPrice->date = date('Y-m-d',$startTime);
        }
        $oPrice->price = $price;
        $oPrice->min_estancia = $min_estancia;
        $oPrice->user_id = $uID;
        $oPrice->save();
      }
      $startTime += $day;
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

      $startTime = strtotime($start);
      $endTime = strtotime($end);
      $startDate = date('Y-m-d', $startTime);
      $endDate = date('Y-m-d', $endTime);

      $defaults = $room->defaultCostPrice($start, $end, $pax);
      $priceDay = $defaults['priceDay'];
      $oPrice = DailyPrices::where('channel_group', $apto)
              ->where('date', '>=', $startDate)
              ->where('date', '<=', $endDate)
              ->get();
      if ($oPrice) {
        foreach ($oPrice as $p) {
          $priceDay[$p->date] = $p->price;
        }
      }
      $priceLst = [];
      $start = $end = $price = null;
      foreach ($priceDay as $d => $p) {
        if (!$price) {
          $price = $p;
          $start = $d;
        }
        if ($price != $p) {
          $priceLst[] = [
              "title" => $price . ' €',
              "start" => $start,
              "end" => $d,
          ];

          $price = $p;
          $start = $d;
        }

        $end = $d;
      }

      $priceLst[] = [
          "title" => $price . ' €',
          "start" => $start,
          "end" => $end,
      ];





      return response()->json($priceLst);
    }




    return response()->json($prices);
  }

  function generate_config() {
    ///admin/channel-manager/config
//    $condif = configZodomusAptos(); dd($condif); die;
    $confFile = \Illuminate\Support\Facades\File::get(storage_path('app/config/zodomus.php'));
//  eval($confFile);
  }

}
