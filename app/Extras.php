<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Extras extends Model
{
  
  /*
    1 Estudio = 0
    2 Dos Dorm = 0
    3 Grande(10 px) = 0
    4 Grande (12 pax) = 0
    5 Estudio Lujo = 0
    6 Dos Dorm Lujo = 0
    7 Grande(10px) Lujo = 0
    8 Grande (12 pax) Lujo = 0
    9 Chalet = 0
   * 
   */
  static function luzBySize($sizeApt){
    switch ($sizeApt) {
      case 1: // Estudio = 20
      case 5: // Estudio Lujo = 20 
        return 7;
      case 2: //Dos Dorm = 25
      case 6: //Dos Dorm Lujo = 25
        return 8;
      case 9: //Chalet = 25
        return 9;
      case 3: //Grande(10 px) = 40
      case 4: //Grande (12 pax) = 40
      case 7: //Grande(10px) Lujo = 40
      case 8: //Grande (12 pax) Lujo = 40
        return 10;
      default:
        return 7;
          break;
    }
  }
  /**
   * 
   * @return (object) [giftCost,giftPVP,luzCost,luzPVP]
   */
  static function loadFixed($sizeApt){
    
    $aObj = [
        'giftCost'=>0,
        'giftPVP'=>0,
        'luzCost'=>0,
        'luzPVP'=>0
        ];
    $oObj = \App\Extras::where('id',4)
            ->orWhere('id', Extras::luzBySize($sizeApt))->get();
    foreach ($oObj  as $item) {
      if ($item->id == 4){ //Gift
          $aObj['giftCost'] = floatval($item->cost);
          $aObj['giftPVP'] = floatval($item->price);
          
      } else {
          $aObj['luzCost'] = floatval($item->cost);
          $aObj['luzPVP'] = floatval($item->price);
      }
    }
    return (object)$aObj;
  }


  static function giftCost(){
    $extraPrice = \App\Extras::find(4);
    return floatval($extraPrice->cost);
  }
  
//  static function luzCost($sizeApt){
//    $extraPrice = \App\Extras::find(Extras::luzBySize($sizeApt));
//    return floatval($extraPrice->cost);
//  }
}
