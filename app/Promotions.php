<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Promotions extends Model {

  public $timestamps = false;

  function getDiscount($startDate, $endDate, $ch_group) {
    $oPromotions = \App\Promotions::where('start', '<=', $startDate)
                    ->where('finish', '>=', $endDate)->get();

    if ($oPromotions) {
      $oneDay = 24 * 60 * 60;
      $startAux = strtotime($startDate);
      $endAux = strtotime($endDate);
      $days = [];
      while ($startAux < $endAux) {
        $days[] = date('Y-m-d', $startAux);
        $startAux += $oneDay;
      }

      $maxDiscunt = 15;
      foreach ($oPromotions as $promo) {
        $rooms = unserialize($promo->rooms);
        
        if (!is_array($rooms) || count($rooms) == 0)
          continue;

        if (!in_array($ch_group, $rooms))
          continue;
        if( !$promo->inDays($days) ) continue;
        
        if ($promo->value>$maxDiscunt)
          $maxDiscunt = $promo->value;
      }
      
      return $maxDiscunt;
      
    } else {
      return 15;
    }
  }

  private function inDays($days) {
    $promoDays = unserialize($this->days);
    if (!is_array($promoDays) || count($promoDays) == 0)
      return false;
    if ($days){
      foreach ($days as $day){
        if (!isset($promoDays[$day])) 
          return false;
        if ($promoDays[$day] == 0)
          return false;
      }
    }
    return true;
  }

}
