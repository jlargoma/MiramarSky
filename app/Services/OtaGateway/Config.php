<?php

namespace App\Services\OtaGateway;

class Config {

  /**
   * restriction_plan_id
   */
  public function restriction_plan() {
    return 1592050;
  }
  /**
   * Rate Plan ID
   */
  public function Plans() {
    return 17633;
//    return [
//        1 => 764, //"Booking.com",
//        2 => "Expedia",
//        3 => "Airbnb",
//        4 => "Agoda",
//        99 => "Wubook",
//    ];
  }

  /**
   * Apply math function to the price based on the Channel
   * @param type $params
   * @return type
   */
  public function processPriceRates($params, $channel_group) {

    if (isset($params['prices'])) {
      $channelId = $params['channelId'];
      $price = $params['prices']['price'];
      $priceSingle = isset($params['prices']['priceSingle']) ? $params['prices']['priceSingle'] : 0;

      $price = $this->priceByChannel($price, $channelId, $channel_group);
      $priceSingle = $this->priceByChannel($priceSingle, $channelId, $channel_group);

      $params['prices']['price'] = ceil($price);
      if (isset($params['prices']['priceSingle']))
        $params['prices']['priceSingle'] = ceil($priceSingle);
    }

    return $params;
  }

  public function priceByChannel($price, $channelId = null, $room = null, $text = false) {
    if (!$price || !is_numeric($price)) {
      if ($text)
        $price = 1;
      else
        return null;
    }
    $priceText = '';

    switch ($channelId) {
      case 1:
      case "1": //"Booking.com",
        $price = ($price > 0) ? $price + ($price * 0.20) : 20;
        $priceText .= '+20%';
        break;
      case 2:
      case "2": //Expedia,
        $price = ($price > 0) ? $price + ($price * 0.175) : 17.5;
        $priceText .= '+17,5%';
        break;
      case 3:
      case "3": //airbnb,
        $price = ($price > 0) ? $price + ($price * 0.05) : 5;
        $priceText .= '+5%';
        break;
      case 99:
      case "99": //google,
        $price = ($price > 0) ? $price + ($price * 0.12) : 12;
        $priceText .= '+12%';
        break;
    }


    if ($text)
      return $priceText;
    return $price;
  }

  function get_detailRate($rateID) {

    $text = '';
    switch ($rateID) {
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

  function get_comision($price, $channelId = null) {
    $comision = 0;
    switch ($channelId) {
      case 1:
      case "1": //"Booking.com",
        $comision = ($price * 0.17);
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



    return round($comision, 2);
  }

  /////////////////////////////////////////////////////////////////////////////


  function setRooms() {
    
    include_once dirname(__FILE__).'/rooms.php';
    foreach ($rooms['roomtypes'] as $roomtypes){
      echo "'{$roomtypes["name"]}' => [<br/>"
      . "&nbsp;&nbsp; 'name' => '{$roomtypes["description"]}',<br/>"
      . "&nbsp;&nbsp;  'roomID'=> {$roomtypes["id"]} <br/>],<br/>";
    }
      dd($rooms);
  }
    
  function getRooms() {
    return [
      'CHLT' => [
        'name' => 'CHALET LOS PINOS',
        'roomID'=> 47078 
      ],
      'DDE' => [
        'name' => '2 Dor. Est',
        'roomID'=> 47067 
      ],
      'ESTG' => [
        'name' => 'EST.G',
        'roomID'=> 47073 
      ],
      'DDL' => [
        'name' => '2 Dor. Lujo',
        'roomID'=> 47068 
      ],
      'EstS' => [
        'name' => 'ESTUDIOS ESTÁNDAR',
        'roomID'=> 47069 
      ],
      'EstL' => [
        'name' => 'ESTUDIOS LUJO',
        'roomID'=> 47070 
      ],
      '7J' => [
        'name' => '7J',
        'roomID'=> 47074 
      ],
      '9R' => [
        'name' => '9R',
        'roomID'=> 47075 
      ],
      '9F' => [
        'name' => '9F',
        'roomID'=> 47076 
      ],
      '10I' => [
        'name' => '10I',
        'roomID'=> 47077 
      ]
    ];
  }

}
