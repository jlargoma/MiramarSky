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
class PrepareMinStay {
  
  private $startDate;
  private $endDate;
  private $dailyMinStay;
  private $zData;
  private $wData;
  private $ogData;
  private $roomsMinStay;
  public $error;

  public function __construct($start,$end){
    
    $this->error = null;
    $today = date('Y-m-d');
    if ($today>$start) $start = $today;
    if ($end<=$start){
      $this->error = 'Las fecha de inicio debe se mayor a la de final.';
      return false;
    }
    $this->startDate = $start;
    $this->endDate = date('Y-m-d', strtotime($end . ' +1 day'));
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
    $this->getSpecialSegments();
    
    $otaGateway = new \App\Services\OtaGateway\Config();
    $this->ogData = $otaGateway->getRooms();
  }

  public function process() {
    
    
    //obtengo todos los aptos  - ZODOMUS / WUBOOK
    if (false){
      foreach ($this->zData as $chGroup=>$v){
        $this->dailyMinStay = [];
        $oRoom = \App\Rooms::where('channel_group',$chGroup)->first();
       
        if ($oRoom){
          $this->dailyMinStay($oRoom);
          $this->generateQueriesToSendZodomus($chGroup);
          $this->prepareQueriesToSendWubook($chGroup);
        } 
      }
      $this->saveQueriesToSendWubook();
    }
    //obtengo todos los aptos - OTA-GATEWAY
    if (true){
      $this->process_OtaGateway();
    }
  }
  
  public function process_OtaGateway() {
    foreach ($this->ogData as $chGroup=>$v){
      $this->dailyMinStay = [];
      $oRoom = \App\Rooms::where('channel_group',$chGroup)->first();

      if ($oRoom){
        $this->dailyMinStay($oRoom);
        $this->prepareQueriesToSendOtaGateWay($chGroup);
      } 
    }
  }
  
  public function process_justWubook() {
    
    //obtengo todos los aptos
      foreach ($this->wData as $chGroup=>$v){
        $this->dailyPrices = [];
        $oRoom = \App\Rooms::where('channel_group',$chGroup)->first();
        if ($oRoom){
          $this->dailyMinStay($oRoom);
          $this->prepareQueriesToSendWubook($chGroup);
        } 
      }
      $this->saveQueriesToSendWubook();
  }
  
  private function dailyMinStay($oRoom){
 
    $startTime = strtotime($this->startDate);
    $endTime = strtotime($this->endDate);
    $aDays = [];
    $day = 24*60*60;
    while ($startTime<$endTime){
      $aux = date('Y-m-d',$startTime);
      $md = isset($this->specialSegment[$aux]) ? $this->specialSegment[$aux] : 0;
      $aDays[$aux] = $md;
      $startTime += $day;
    }
      
    $oPrice = \App\DailyPrices::where('channel_group',$oRoom->channel_group)
                ->where('date','>=',$this->startDate)
                ->where('date','<=',$this->endDate)
                ->get();
   
    if ($oPrice) {
        foreach ($oPrice as $p) {
          if (isset($aDays[$p->date]) && $p->min_estancia)
            $aDays[$p->date] = $p->min_estancia;
        }
      }

    $this->dailyMinStay = $aDays;
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
  /*******************************************/
  function generateQueriesToSendZodomus($chGroup){
    $d1 = $this->startDate;
    $d2 = $this->endDate;
    $to_send = [];
    $min_stay = null;
   
    if (!isset($this->zData[$chGroup])) return null;
    $zAptos = $this->zData[$chGroup];
    
    
    foreach ($this->dailyMinStay as $d=>$v){
      $d2 = $d;
      if (is_null($min_stay)) $min_stay = $v;
      if (is_null($d1))  $d1 = $d;


      if ($v!=$min_stay){
        $to_send[] =  [
            "dateFrom" => $d1,
            "dateTo" => $d2,
            "minimumStay" =>  $min_stay,

        ];
        $d1 = $d;
        $min_stay = $v;
      }


    }
    if ($d2!=$d1){
       $to_send[] =  [
           "dateFrom" => $d1,
           "dateTo" => $d2,
           "minimumStay" =>  $min_stay,
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
                  "closed" =>  0,
                  "minimumStay" => $v['minimumStay'],
                  "minimumStayArrival" => $v['minimumStay'],
                ];

          $datas[] = [
            'key'=>'SendToZoodomus_minStay',
            'name'=>$nameProcess,
            'content'=> json_encode([$param,$chGroup])
          ];
        }
      }

    }
    \App\ProcessedData::where('key','SendToZoodomus_minStay')
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
    $MinStay = [];
    foreach ($this->dailyMinStay as $v) {
      $MinStay[] = ['min_stay'=>$v,'min_stay_arrival'=>$v];
    }
    
    $this->roomsMinStay['_int_'.$rid] = $MinStay;

  }
  
  function saveQueriesToSendWubook(){
    $nameProcess = $this->startDate.'_'.$this->endDate;
    $datas[] = [
      'key'=>'SendToWubook_minStay',
      'name'=>$nameProcess,
      'content'=> json_encode([
          'start'=>$this->startDate,
          'min_stay'=>$this->roomsMinStay,
              ])
    ];
    \App\ProcessedData::where('key','SendToWubook_minStay')
            ->where('name',$nameProcess)->delete();
    
    \App\ProcessedData::insert($datas);
  }
  
  /**************************************************************/
  
  function prepareQueriesToSendOtaGateWay($chGroup){
    
    $d1 = $this->startDate;
    $d2 = $this->endDate;
    $to_send = [];
    $precio = null;
    
    if (!isset($this->ogData[$chGroup])) return null;
    $aApto = $this->ogData[$chGroup];
    
    $MinStay = [];
    foreach ($this->dailyMinStay as $day=>$v) {
      $MinStay[$day] = ['min_stay'=>$v];
    }
    
    $nameProcess = $this->startDate.'_'.$this->endDate.'_'.$chGroup;
    $key = 'SendToOtaGateway_minStay';

    $data = [
      'key'=>$key,
      'name'=>$nameProcess,
      'content'=> json_encode(['room'=>$aApto,'MinStay'=>$MinStay])
    ];
    \App\ProcessedData::where('key',$key)
            ->where('name',$nameProcess)->delete();
    
    \App\ProcessedData::insert($data);
    

  }
}
