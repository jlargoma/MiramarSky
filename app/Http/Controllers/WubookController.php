<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Rooms;
use App\DailyPrices;
use App\WobookAvails;

use App\Services\Wubook\WuBook;

class WubookController extends AppController {

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index() {
    
    $WuBook = new WuBook();
//    $WuBook->conect();
//    $WuBook->fetch_rooms();
//    $rooms = $WuBook->getRoomsEquivalent(null);
//    dd($rooms);
//    $url = 'https://apartamentosierranevada.net/wubook-Webhook';
//    $WuBook->pushURL($url, 1);
//    foreach ($rooms as $ch=>$lcode){
//      $WuBook->pushURL($lcode, $url, 1);
//      dd($lcode);
//    }
    
//    $WuBook->pushURL($lcode, $url, $test=0);

    //'2020-04-01','2020-04-08'
     /*************************************************/
//    $roomdays= [
//          ['id'=> 433743, 'days'=> [['avail'=> 9,'date'=>'27/04/2020'],['avail'=> 9,'date'=>'28/04/2020']]],
//          ['id'=> 433742, 'days'=> [['avail'=> 2,'date'=>'20/04/2020'],['avail'=> 4,'date'=>'24/04/2020']]]
//        ];
//    $WuBook->set_Closes($roomdays);
    /*************************************************/
//    $prices= [
//          433743 => [990, 991, 992],
//          433742 => [880, 881, 882],
//        ];
        
    //$WuBook->set_Prices('2020-04-05',$prices);
    
    /*************************************************/
    //all bookings
//    $WuBook->fetch_bookings();
    /*************************************************/
    // get booking
//    $WuBook->fetch_booking();
    
    
    $WuBook->disconect();
  }
  
  /**
   * 
   * @param Request $request
   * @return type
   */
  public function sendPricesGroup(Request $request) {
    $sentUPD_wubook = \App\ProcessedData::findOrCreate('sentUPD_wubook');
    $dates = json_decode($sentUPD_wubook->content);
    if (!$dates){
      return redirect()->back()->withErrors(['No hay registros que enviar']);
    }
    $start    = $dates->start;
    $today    = date('Y-m-d');
    $end      = $dates->finish;
   
    
    if ($today>$end) 
      return redirect()->back()->withErrors(['No se pueden enviar registros anteriores a la fecha actual']);
    
    if ($start<$today) $start = $today;
    
    $WuBook = new WuBook();
    //get the channels from the Site
    $channels = Rooms::groupBy('channel_group')->pluck('channel_group')->toArray();
  
    //Get the Channel -> Wubook rooms ID
    $items = $WuBook->getRoomsEquivalent($channels);
    
    $prices= [];
    
    foreach ($items as $ch=>$rid){
      $pricesCh = $this->getPriceDay_group($ch,$start,$end);
      $aux = [];
      if ($pricesCh){
        foreach ($pricesCh as $d=>$p){
          $aux[] = intval($p);
        }
        $prices['_int_'.$rid] = $aux;
      }
    
    }
        
    if (count($prices)>0){
      
      $WuBook->conect();
      
      $response = $WuBook->set_Prices($start,$prices);

      $WuBook->disconect();
      
      $sentUPD_wubook->content = null;
      $sentUPD_wubook->save();
      
    } else {
      return redirect()->back()->withErrors(['Los precios no están asociados a WuBook']);
    }
    
    if($response)
      return redirect()->back()->with(['success'=>'Precios enviados a WuBook']);
      
    return redirect()->back()->withErrors(['Ocurrió un error al enviar los precios a WuBook']);
     
  }
  
  
  /**
   * Get the list of prices to send to Wubook
   * @param type $ch
   * @param type $start
   * @param type $end
   * @return type
   */
  function getPriceDay_group($ch,$start,$end){
    
    $prices = [];
    $room = Rooms::where('channel_group',$ch)->first();
    if (!$room)   return null;
    $defaults = $room->defaultCostPrice($start, $end, $room->pax);
    $priceDay = $defaults['priceDay'];
    
    $oPrice = DailyPrices::where('channel_group', $ch)
            ->where('date', '>=', $start)
            ->where('date', '<=', $end)
            ->get();
    if ($oPrice) {
      foreach ($oPrice as $p) {
        if ($p->price && isset($priceDay[$p->date])) $priceDay[$p->date] = $p->price;
      }
    }
   
    return $priceDay;
      
  }
  
  /**
   * Auxiliar function to send Avails to date range
   */
  public function createAvails() {
    // ver la función sendAvailibility book
    
    $start = date('Y-m-d');
////    $start = '2020-04-16';
    $end = '2020-12-30';
//    $site = 1;
     
    $WuBook = new WuBook();
    //get the channels from the Site
    $channels = Rooms::groupBy('channel_group')->pluck('channel_group')->toArray();
  
    $book = new \App\Book();
    //Get the Channel -> Wubook rooms ID
    $items = $WuBook->getRoomsEquivalent($channels);
    
    $result= [];
    
    foreach ($items as $ch=>$rid){
      $availibility = $book->getAvailibilityBy_channel($ch, $start, $end);
      $aux = [];
      if ($availibility){
        foreach ($availibility as $d=>$v){
          $result[] = [
              'channel_group' => $ch,
              'date'          => $d,
              'avail'         => $v
          ];
        }
        
        WobookAvails::insert($result);
        $result = [];
      }
    }
  }
  
  /**
   * Send Avails groups to wubook
   */
  public function sendAvails() {
    
    //clear before dates
    WobookAvails::where('date','<',date('Y-m-d'))->delete();
    
    $list = WobookAvails::orderBy('id','desc')->get();
    $items = [];
    $delIDs = [];
    $already = [];
    if ($list){
      foreach ($list as $v){
        if (!in_array($v->channel_group.$v->date,$already)){
          $already[] = $v->channel_group.$v->date;
          if (!isset($items[$v->channel_group])) $items[$v->channel_group] = [];
          $items[$v->channel_group][] = [
            'avail'=> $v->avail,
            'date'=> convertDateToShow($v->date,true)
            ];
        }
        if (!isset($delIDs[$v->channel_group])) $delIDs[$v->channel_group] = [];
        $delIDs[$v->channel_group][] = $v->id;
      }
      
    }

    $WuBook = new WuBook();
    
    $lstChannels = $this->getChannels();
    $roomdays = [];
    $delDay = [];
    if (count($lstChannels)>0){ //the Site has channels

      $rIDs = $WuBook->getRoomsEquivalent($lstChannels);
      if (count($rIDs)){ //the channels has a WuBook's room
        foreach ($rIDs as $ch=>$rid){
          if (isset($items[$ch]))
            $roomdays[] = ['id'=> $rid, 'days'=> $items[$ch]];
          if (isset($delIDs[$ch])){
            foreach ($delIDs[$ch] as $d) $delDay[] = $d;
          }
            
        }
      }
    }
    if (count($roomdays)>0){
    
      $WuBook->conect();
      if ($WuBook->set_Closes($roomdays)){
        //delete the aux table data
        WobookAvails::whereIn('id',$delDay)->delete();
      }
      $WuBook->disconect();
      
    }
    /*************************************************/
    
    
    
  }
  
  /**
   * 
   * @param Request $request
   * rcode (the reservation code) 
   * lcode (the property identifier, 
   */
  public function webHook(Request $request) {
    
    $rcode = $request->input('rcode');
    $lcode = $request->input('lcode');
    
         
    //save the params to response quikly the HTTP 200
    $oData = \App\ProcessedData::findOrCreate('wubook_webhook');
    $content = json_decode($oData->content,true);
    if (!$content || !is_array($content)) $content = [];
      
    $content[] = [
          'date' =>time(),
          'rcode'=>$rcode,
          'lcode'=>$lcode,
      ];
    
    $oData->content = json_encode($content);
    $oData->save();
    
    return response('',200);
  }
  
  /**
   * Process the bookings by WebHooks
   * @param Request $request
   */
  public function processHook(Request $request) {
      
    $oData = \App\ProcessedData::findOrCreate('wubook_webhook');
    $content = json_decode($oData->content);
    if (count($content)){
      
      $WuBook = new WuBook();
      $WuBook->conect();
      foreach ($content as $c){
        $WuBook->fetch_booking($c->lcode,$c->rcode);
      }
      $WuBook->disconect();
      
      //Clear the auxiliar datatable
      $oData->content = null;
      $oData->save();
    }
     
    
    
  }
  

  private function getChannels() {
    return \App\Rooms::where('state',1)->groupBy('channel_group')->pluck('channel_group')->toArray();
  }

}
