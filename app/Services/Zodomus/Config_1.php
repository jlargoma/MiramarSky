<?php

namespace App\Services\Zodomus;

class Config_1{
  
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