<?php

namespace App\Services\Wubook;

class Config
{


  public function getPropID($site = null)
  {
    return 1578438122; //s'olo vamos a usa una
  }

  function getRooms($room = null)
  {

    $lst = [
      'CHLT' => 432613,
      'DDE' => 432614,
      'ESTG' => 432611,
      'DDL' => 432360,
      'EstS' => 432630,
      'EstL' => 433845,
      '7J' => 431916,
      '9R' => 432606,
      '9F' => 432612,
      '10I' => 432610
    ];
    if ($room) {
      return isset($lst[$room]) ? $lst[$room] : -1;
    }
    return $lst;
  }

  function getChannelByRoom($roomID)
  {
    $all = $this->getRooms();
    foreach ($all as $chn => $rid) {
      if ($rid == $roomID) return $chn;
    }
    return null;
  }

  public function pricePlan($site)
  {
    return 153130; 
  }

  public function restricPlan($site)
  {
    return 0;  // solo vamos a usar una propiedad
    return 76427;  // solo vamos a usar una propiedad
  }
}
