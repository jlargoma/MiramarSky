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
  /**
   * 
   * @return (object) [giftCost,giftPVP,luzCost,luzPVP]
   */
  static function loadFixed(){
    
    $aObj = [
        'giftCost'=>0,
        'giftPVP'=>0,
        'luzCost'=>0,
        'luzPVP'=>0,
        ];
  
    $oObj = \App\Extras::whereIn('id',[4,7])->get();
    foreach ($oObj  as $item) {
      switch ($item->id){
        case 4: //Gift
          $aObj['giftCost'] = floatval($item->cost);
          $aObj['giftPVP'] = floatval($item->price);
          break;
        case 7: //Luz
          $aObj['luzCost'] = floatval($item->cost);
          $aObj['luzPVP'] = floatval($item->price);
          break;
      }
    }
    return (object)$aObj;
  }


  static function giftCost(){
    $extraPrice = \App\Extras::find(4);
    return floatval($extraPrice->cost);
  }
  
  static function luzCost(){
    $extraPrice = \App\Extras::find(7);
    return floatval($extraPrice->cost);
  }
}
