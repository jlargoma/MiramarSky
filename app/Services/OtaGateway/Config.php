<?php

namespace App\Services\OtaGateway;

class Config {

  /**
   * restriction_plan_id
   */
  public function restriction_plan($ota = null) {

    switch ($ota) {
      case 1:
      case "1": //"Booking.com",
        return 1592050;
      case 28:
      case "28": //Expedia,
        return 1592548;
      case 4:
      case "4": //"Plan Miramar - AirBnb"
        return 1592531;
      case 99:
      case "99": //google GHotels
        return 1592532;
    }
    return 1592050;
  }

  /**
   * Rate Plan ID
   */
  public function Plans($ota = null) {
    switch ($ota) {
      case 1:
      case "1": //"Booking.com",
        return 17633;
      case 28:
      case "28": //Expedia,
        return 18126;
      case 4:
      case "4": //"Plan Miramar - AirBnb"
        return 18111;
      case 99:
      case "99": //google GHotels
        return 18112;
      default :
        return 17633;
    }
    return 17633;
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

  public function priceByChannel($price, $channelId = null, $room = null, $text = false, $nights = 1) {

   
    if (!$price || !is_numeric($price)) {
      if ($text)
        $price = 1;
      else
        return null;
    }

    $roomsLst = $this->getRooms();
    $agencyLst = $this->getAllAgency();

    if (is_numeric($room)) {
      $aux = array_search($room, $roomsLst);
      if ($aux)
        $room = $aux;
    }

    $priceText = '';
    $prices_ota = \App\Settings::getContent('prices_ota');
    if ($prices_ota) {
      $prices_ota = unserialize($prices_ota);
      if (is_array($prices_ota) && isset($prices_ota[$room . $channelId])){
        $priceData = $prices_ota[$room . $channelId];

        //incremento el valor fijo por noche
        if ($priceData['f']){
          $price += $priceData['f'] * $nights;
          $priceText = '(PVP+'.$priceData['f'].'€)';
        }
        
        //incremento el valo por porcentaje
        if ($priceData['p']){
          $price = $price * (1+ ($priceData['p'] / 100));
          $priceText .= '+'.$priceData['p'].'%';
        }
        

        if ($text)  return $priceText;
        return $price;
      }
      
      //if the price is not load
      if ($text)  return '--';
        return null;
      
    } else {
       return null;
    }
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

  public function getAllAgency() {
    return [
        'airbnb' => 4,
        'booking' => 1,
        'expedia' => 28,
        'google-hotel' => 99,
        'agoda' => 98,
    ];
  }

  public function getAgency($id_chanel) {
    // airbnb => 4,
    //booking => 1
    $chanels = $this->getAllAgency();

    return isset($chanels[$id_chanel]) ? $chanels[$id_chanel] : -1;
  }

  /////////////////////////////////////////////////////////////////////////////


  function setRooms() {

    include_once dirname(__FILE__) . '/rooms.php';
    foreach ($rooms['roomtypes'] as $roomtypes) {
      echo "'{$roomtypes["name"]}' => [<br/>"
      . "&nbsp;&nbsp; 'name' => '{$roomtypes["description"]}',<br/>"
      . "&nbsp;&nbsp;  'roomID'=> {$roomtypes["id"]} <br/>],<br/>";
    }
    dd($rooms);
  }

  function getRooms() {
    return [
        'CHLT' => 47078,
        'DDE' => 47067,
        'ESTG' => 47073,
        'DDL' => 47068,
        'EstS' => 47069,
        'EstL' => 47070,
        '7J' => 47074,
        '9R' => 47075,
        '9F' => 47076,
        '10I' => 47077
    ];
  }

  function getRoomsName() {
    return [
        'DDE' => '2 Dor. Est',
        'DDL' => '2 Dor. Lujo',
        'EstS' => 'ESTUDIOS ESTÁNDAR',
        'EstL' => 'ESTUDIOS LUJO',
        '7J' => '7J',
        '9R' => '9R',
        '9F' => '9F',
        '10I' => '10I',
        'ESTG' => 'EST.G',
        'CHLT' => 'CHALET LOS PINOS',
    ];
  }

  function getChannelByRoom($roomtype_id) {
    $ch = array_search($roomtype_id, $this->getRooms());
    return $ch === FALSE ? 'ROOMDD' : $ch;
  }

}
