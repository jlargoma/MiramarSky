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
    
    /**
     * Apply math function to the price based on the Channel
     * @param type $params
     * @return type
     */
    public function processPriceRates($params,$channel_group) {
      
      $channelId = $params['channelId'];
      $price = $params['prices']['price'];
      $priceSingle = isset($params['prices']['priceSingle']) ? $params['prices']['priceSingle'] : 0;
      
      $price = $this->priceByChannel($price,$channelId,$channel_group);
      $priceSingle = $this->priceByChannel($priceSingle,$channelId,$channel_group);
              
      $params['prices']['price'] = ceil($price);
      if (isset($params['prices']['priceSingle']))
        $params['prices']['priceSingle'] = ceil($priceSingle);
      
      return $params;
      
    }
    
    
    public function priceByChannel($price,$channelId=null,$room=null) {
      
      switch ($channelId){
        case 1:
        case "1": //"Booking.com",
          if ($room == 'DDL')
            $price = ($price>0) ? $price+($price*0.24) : 24;
          else 
            $price = ($price>0) ? $price+($price*0.20) : 20;
          break;
        case 2:
        case "2": //Expedia,
          $price = ($price>0) ? $price+($price*0.20) : 20;
          break;
        case 3:
        case "3": //airbnb,
          $price = ($price>0) ? $price+($price*0.15) : 15;
          break;
      }
      
      
      
      return $price;
    }
    
    
    function get_detailRate($rateID){
      
      $text = '';
      switch ($rateID){
        case 17086560:
        case "17086560": //Booking.com - RIAD
          $text = 'NO REEMBOLSABLE';
          break;
        case 6224390:
        case "6224390":  //Booking.com - ROSA
          $text = 'NO REEMBOLSABLE';
          break;
        case 17086950:
        case "17086950":  //Booking.com - ROSA
          $text = 'DESAYUNO INCLUIDO';
          break;
        case 49136:
        case "49136":  
          $text = 'CANCELACIÓN  GRATUITA HASTA 3 DÍAS ANTES';
          break;
      }
      return $text;
 
        
    }
    
    function get_comision($price,$channelId=null) {
      $comision = 0;
      switch ($channelId){
        case 1:
        case "1": //"Booking.com",
          $comision = ($price*0.17);
          break;
//        case 2:
//        case "2": //Expedia,
//          $price = $price+($price*0.20);
//          break;
//        case 3:
//        case "3": //airbnb,
//          $price = $price+($price*0.15);
//          break;
      }
      
      
      
      return round($comision,2);
    }
    
    
    
    
    
    
    
    /////////////////////////////////////////////////////////////////////////////
    
    
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