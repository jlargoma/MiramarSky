<?php

namespace App\Services\OtaGateway;

class Config {

  /**
   * restriction_plan_id
   */
  public function restriction_plan($ota=null) {
    
    switch ($ota){
      case 1:
      case "1": //"Booking.com",
      return 1592050;
      case 2:
      case "2": //Expedia,
      return 1592548;
      case 3:
      case "3": //"Plan Miramar - AirBnb"
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
  public function Plans($ota=null) {
    switch ($ota){
      case 1:
      case "1": //"Booking.com",
      return 17633;
      case 2:
      case "2": //Expedia,
      return 18126;
      case 3:
      case "3": //"Plan Miramar - AirBnb"
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

  public function priceByChannel($price,$channelId=null,$room=null,$text=false,$nights=1) {
      
      if (!$price || !is_numeric($price)){
        if ($text) $price = 1;
        else return null;
      }
      
      if (is_numeric($room)){
        $aux = array_search($room, $this->getRooms());
        if ($aux) $room = $aux;
      }
      
      
      
      
      $priceText = '';
      //Parking
      if ($channelId <3){
        if ( in_array($room, ['7J','9F',47074,47076]) ){ //name o ID equivalent
          $price += 40*$nights;  
          $priceText = '(PVP+40€)';
        } else {
          $price += 20*$nights;  
          $priceText = '(PVP+20€)';
        }
      }
      
      switch ($channelId){
        case 1:
        case "1": //"Booking.com",
          if ($room == 'DDL' || $room == 'EstL' || $room == '9F'){
            $price = $price+($price*0.24);
            $priceText .= '+24%';
          }
          else{
            $price = $price+($price*0.20);
            $priceText .= '+20%';
          }
          break;
        case 2:
        case "2": //Expedia,
          $price = $price+($price*0.20);
          $priceText .= '+20%';
          break;
        case 3:
        case "3": //airbnb,
          $price = $price+($price*0.05);
          $priceText = '+5%';
          break;
        case 99:
        case "99": //google,
          $price = $price+($price*0.12);
          $priceText = '+12%';
          break;
      }
      
      if ($text) return $priceText;
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

  public function getAgency($id_chanel) {
    // airbnb => 4,
    //booking => 1
    $chanels = [
        'airbnb' => 4,
        'booking' => 1,
        'expedia' => 28,
        'google-hotel' => 999999,
    ];

    return isset($chanels[$id_chanel]) ? $chanels[$id_chanel] : -1;
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
      'CHLT' => 47078,
      'DDE'  => 47067,
      'ESTG' => 47073,
      'DDL'  => 47068,
      'EstS' => 47069,
      'EstL' => 47070,
      '7J'   => 47074,
      '9R'   => 47075,
      '9F'   => 47076,
      '10I'  => 47077
    ];
  }

  function getRoomsName() {
    return [
      'CHLT' =>'CHALET LOS PINOS',
      'DDE' => '2 Dor. Est',
      'ESTG' => 'EST.G',
      'DDL' => '2 Dor. Lujo',
      'EstS' => 'ESTUDIOS ESTÁNDAR',
      'EstL' => 'ESTUDIOS LUJO',
      '7J' => '7J',
      '9R' => '9R',
      '9F' => '9F',
      '10I' =>  '10I',
    ];
  }

  function getChannelByRoom($roomtype_id){
    $ch = array_search($roomtype_id, $this->getRooms());
    return  $ch === FALSE ? 'ROOMDD' : $ch;
  }
}
