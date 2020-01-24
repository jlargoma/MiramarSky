<?php

namespace App\Services\Zodomus;

class Config{
  
  public function propertyList() {
    return [
      321000 => 'Apartamento de Prueba',  
    ];
  }
  public function colors() {
    return [
      32100001 => "#b9e4c8",
      32100002 => "#d2a0d1",
      32100003 => "#c1bee0",
    ];
  }
  
  public function roomsEquivalents() {
      
      
      
      return [
          433742 => 164,//"TES1"
          433743 => 165,//"TES2"
          433744 => 166,//"TES3"
          431916 => 166,//"TES3" BORRAR
          ];
      
      
      
      
      
      
      $lst = [
        432364 => 142,//"5D",
        432365 => 155,//"5F",
        432366 => 119,//"8T",
        432360 => 0,//"DDL"
        432361 => 115,//"8D",
        431916 => 153,//"7J",
        432606 => 165,//"9R",
        432610 => 122,//"10I",
        432611 => 160,//"GORB",
        432612 => 149,//"9F",
        432613 => 144,//"CHLT",
        432614 => 0,//"DDS",
        432615 => 129,//"4P",
        432616 => 161,//"7P",
        432617 => 117,//"4N",
        432618 => 159,//"G8",
        432619 => 139,//"G6",
        432620 => 140,//"J6",
        432621 => 124,//"J5",
        432622 => 164,//"J8",
        432623 => 158,//"V10",
        432624 => 0,//"ST-L",
        432625 => 154,//"5E",
        432626 => 138,//"9J",
        432627 => 145,//"9S",
        432628 => 157,//"S7",
        432629 => 163,//"O5",
        432630 => 0,//"ST-S",
        432631 => 125,//"U7",
        432632 => 110,//"H7",
        432633 => 152,//"5K",
        432634 => 162,//"6K",
        432635 => 116,//"V8",
        432636 => 141,//"B5",
        432362 => 123,//"8F",
      ];
      
      //BEGIN: Rooms by Size
      $sizes = [
        1 => 432630,//'ST-S',
        5 => 432624,//'ST-L',
        2 => 432614,//'DDS',
        6 => 432360,//'DDL',
      ];
      
      foreach ($sizes as $k=>$v){
        $rooms = Rooms::select('id')
                ->where('sizeApto',$k)
                ->where('state',1)
                ->whereNotIn('id',$lst)->get();
        if ($rooms){
          $newRId = array();
          foreach ($rooms as $r){
            $newRId[] = $r->id;
          }
          
          $lst[$v] = $newRId;
        }
        
      }
      //END: Rooms by Size
      return $lst;
      
    }
    
    
    public function getAgency($id_chanel) {
      // airbnb => 4,
      //booking => 1
      
      $chanels = [
        1=>1,  //"Booking.com",
        2=>0,  //Expedia
        3=>4,  //airbnb
      ];
  
      return isset($chanels[$id_chanel]) ? $chanels[$id_chanel] : -1;
    }
    
    /**
     * Channels in API
     * GET channels
     * [id=>name]
     * @return type
     */
    public function Channels() {
      return [
        1 => "Booking.com",
        2 => "Expedia",
        3 => "Airbnb",
        4 => "Agoda",
      ];
    }
    
    /**
     * Get price-model
     */
    public function priceModels($param) {
      
      return [
          1 => "Maximum / Single occupancy",
          2 => "Derived pricing",
          3 => "Occupancy",
          4 => "Per day",
          5 => "Per Day Length of stay",
      ];
    }
    
    
    public function getExampleRates() {
      return [
        "channelId" =>  1,
        "propertyId" =>  "999999",
        "roomId" =>  "99999901",
        "rateId" =>  "999999991",
        "dateFrom" =>  "2020-07-01",
        "dateTo" =>  "2020-07-23",
        "currencyCode" =>  "EUR",
        "prices" =>   [
          "price" =>  "850.00",
          "priceSingle" => "850.00"
        ],
        "weekDays" =>  [
          "sun" => FALSE,
          "mon" => TRUE,
          "tue" =>  TRUE,
          "wed" =>  TRUE,
          "thu" =>  TRUE,
          "fri" =>  TRUE,
          "sat" =>  FALSE
        ],
        "closed" =>  0,//"0=false , 1=true, (optional restrition)",
        "minAdvanceRes" => '2D',// "4D = four days; 4D4H = four days and four hours, (optional restrition)",
        "maxAdvanceRes" => '8D',// "4D = four days; 4D4H = four days and four hours, (optional restrition)",
//        "minimumStay" =>  "Between 0 and 31, (optional restrition)",
//        "maximumStay" =>  "Between 0 and 31, (optional restrition)",
//        "minimumStayArrival" =>  "Between 0 and 31, (optional restrition)",
//        "maximumStayArrival" =>  "Between 0 and 31, (optional restrition)",
//        "exactStayArrival" =>  "Between 0 and 31, (optional restrition)",
//        "closedOnArrival" =>  "0=false , 1=true, (optional restrition)",
//        "closedOnDeparture" =>  "0=false , 1=true, (optional restrition)"
      ];
    }
    
    function getExampleRoom(){
      return [
        "channelId" =>  1,
        "propertyId" =>  "321000",
        "rooms" =>  [
            [
            "roomId" =>  "32100001",
            "roomName" =>  "Single room",
            "status" =>  1,
            "quantity" =>1,
            "rates" =>  ["321000991","321000992","321000993"]
            ],
            [
            "roomId" =>  32100002,
            "status" =>  1,
            "roomName" =>  "Single room 2",
            "quantity" =>1,
            "rates" =>  [321000992]
            ]
        ]
      ];
      
    }
}