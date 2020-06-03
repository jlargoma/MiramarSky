<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;

/**
 * Description of prepareYearPricesAndMinStay
 *
 * @author cremonapg
 */
class prepareDefaultPrices {
  
  private $startDate;
  private $endDate;
  private $dailyPrices;
  private $roomsPrices;
  private $specialSegment;
  private $zData;
  private $wData;
  public $error;

  public function __construct($start,$end){
    
    $this->error = null;
    $today = date('Y-m-d');
    if ($today>$start) $start = $today;
    if ($end<=$start){
      $this->error = 'Las fechas deben se mayor';
      return false;
    }
    $this->startDate = $start;
    $this->endDate = $end;
    $aux = configZodomusAptos();
    $aptoOtas = [];
    $channels = [];
    foreach ($aux as $k=>$data){
      $aptoOtas[$k] = $data->rooms;
      $channels[] = $k;
    }
    $this->zData = $aptoOtas;
    $WuBook = new \App\Services\Wubook\WuBook();
    $this->wData  = $WuBook->getRoomsEquivalent($channels);
  }

  public function process() {
    
//    $this->getSpecialSegments();
    //obtengo todos los aptos
    if (true){
      foreach ($this->zData as $chGroup=>$v){
        $this->dailyPrices = [];
        $oRoom = \App\Rooms::where('channel_group',$chGroup)->first();
       
        if ($oRoom){
          $this->dailyPrice($oRoom);
          $this->generateQueriesToSendZodomus($chGroup);
          $this->prepareQueriesToSendWubook($chGroup);
        } 
//        $this->prepareSpecialSegments();
      }
      $this->saveQueriesToSendWubook();
    }
  }
  
   public function process_justWubook() {
    
    //obtengo todos los aptos
      foreach ($this->wData as $chGroup=>$v){
        $this->dailyPrices = [];
        $oRoom = \App\Rooms::where('channel_group',$chGroup)->first();
        if ($oRoom){
          $this->dailyPrice($oRoom);
          $this->prepareQueriesToSendWubook($chGroup);
        } 
      }
      $this->saveQueriesToSendWubook();
  }
  
  private function dailyPrice($oRoom){
    
    $defaults = $oRoom->defaultCostPrice( $this->startDate, $this->endDate,$oRoom->minOcu);
    $priceDay = $defaults['priceDay'];
    $oPrice = \App\DailyPrices::where('channel_group',$oRoom->channel_group)
                ->where('date','>=',$this->startDate)
                ->where('date','<=',$this->endDate)
                ->get();
   
    
    if ($oPrice) {
        foreach ($oPrice as $p) {
          if (isset($priceDay[$p->date]) && $p->price)
            $priceDay[$p->date] = $p->price;
        }
      }
      
    $this->dailyPrices = $priceDay;
  }
  /*******************************************/
  
  function getSpecialSegments(){
    $oSS = \App\SpecialSegment::where('start','>=',$this->startDate)
                ->where('finish','<=',$this->endDate)
                ->get();
    $ssDays = [];
    $day = 24*60*60;
    
    if ($oSS){
      foreach ($oSS as $item){
        
        $startTime = strtotime($item->start);
        $endTime = strtotime($item->finish);
        
        while ($startTime<=$endTime){
          $ssDays[date('Y-m-d',$startTime)] = $item->minDays;
          $startTime += $day;
        }
      }
    }
    $this->specialSegment = $ssDays;
  }
  function prepareSpecialSegments(){
    
    
//    $this->dailyPrices
//    $this->specialSegment = $ssDays;
  }
  /*******************************************/
  function generateQueriesToSendZodomus($chGroup){
    $d1 = $this->startDate;
    $d2 = $this->endDate;
    $to_send = [];
    $precio = null;
   
    if (!isset($this->zData[$chGroup])) return null;
    $zAptos = $this->zData[$chGroup];
    
    
    foreach ($this->dailyPrices as $d=>$p){
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
        
    $datas = [];
    
 
    $nameProcess = $this->startDate.'_'.$this->endDate.'_'.$chGroup;
    
    foreach ($to_send as $v){
      foreach ($zAptos as $room){
        if ($room->roomID>0){
          $param = [
                  "channelId" =>  $room->channel,
                  "propertyId" => $room->propID,
                  "roomId" =>  $room->roomID,
                  "dateFrom" => $v['dateFrom'],
                  "dateTo" => $v['dateTo'],
                  "currencyCode" =>  "EUR",
                  "rateId" =>  $room->rateID,
                  "weekDays" => $weekDays,
                  "prices" =>  $v['prices'],
                  "closed" =>  0,
                  "minimumStay" => 1,
                  "minimumStayArrival" => 1,
                ];

          $datas[] = [
            'key'=>'SendToZoodomus',
            'name'=>$nameProcess,
            'content'=> json_encode([$param,$chGroup])
          ];
        }
      }

    }
    \App\ProcessedData::where('key','SendToZoodomus')
            ->where('name',$nameProcess)->delete();
    
    \App\ProcessedData::insert($datas);
  }
  
  
  function prepareQueriesToSendWubook($chGroup){
    
    $d1 = $this->startDate;
    $d2 = $this->endDate;
    $to_send = [];
    $precio = null;
   
    
    if (!isset($this->wData[$chGroup])) return null;
    $rid = $this->wData[$chGroup];
    $prices = [];
    foreach ($this->dailyPrices as $v)  $prices[] = $v;
    
    $this->roomsPrices['_int_'.$rid] = $prices;

  }
  
  function saveQueriesToSendWubook(){
    
    $nameProcess = $this->startDate.'_'.$this->endDate;
    $datas[] = [
      'key'=>'SendToWubook',
      'name'=>$nameProcess,
      'content'=> json_encode([
          'start'=>$this->startDate,
          'prices'=>$this->roomsPrices,
              ])
    ];
    \App\ProcessedData::where('key','SendToWubook')
            ->where('name',$nameProcess)->delete();
    
    \App\ProcessedData::insert($datas);
  }
}
