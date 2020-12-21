<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Promotions extends Model {

  public $timestamps = false;

  function getDiscount($startDate, $endDate, $ch_group) {
    $oPromotions = \App\Promotions::where('type', 'perc')->get();
          
    $maxDiscunt = 0;
    $name = '';
   
    if ($oPromotions) {
      $oneDay = 24 * 60 * 60;
      $startAux = strtotime($startDate);
      $endAux = strtotime($endDate);
      $days = [];
      while ($startAux < $endAux) {
        $days[] = date('Y-m-d', $startAux);
        $startAux += $oneDay;
      }


      foreach ($oPromotions as $promo) {
        $rooms = unserialize($promo->rooms);
        
        if (!is_array($rooms) || count($rooms) == 0)
          continue;

        if (!in_array($ch_group, $rooms))
          continue;
        if( !$promo->inDays($days) ) continue;
        if ($promo->value>$maxDiscunt)
          $maxDiscunt = $promo->value;
          $name = $promo->name;
      }
    }
    
    return ['n'=>$name,'v'=>$maxDiscunt];
  }

  
  function getPromo($startDate, $endDate, $ch_group) {
    $oPromotions = \App\Promotions::where('type', 'nights')->get();
    if ($oPromotions) {
      $oneDay = 24 * 60 * 60;
      $startAux = strtotime($startDate);
      $endAux = strtotime($endDate);
      $days = [];
      while ($startAux < $endAux) {
        $days[] = date('Y-m-d', $startAux);
        $startAux += $oneDay;
      }

      foreach ($oPromotions as $promo) {
        $rooms = unserialize($promo->rooms);
        
        if (!is_array($rooms) || count($rooms) == 0)
          continue;

        if (!in_array($ch_group, $rooms))
          continue;
       
        if( !$promo->inDays($days) ) continue;
        
        return [
            'name' => $promo->name,
            'night' => $promo->nights,
            'night_apply' => $promo->night_apply
            ];
        
      }
    }

    return null;
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
