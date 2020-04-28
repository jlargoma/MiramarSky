<?php

namespace App\Services\Wubook;

class Config {

  public function roomsEquivalent() {

    return [
        432614 => 'DDE', //DDS
        432360 => 'DDL', 
        432630 => 'EstS',
        433845 => 'EstL',
        432611 => 'ESTG',
        431916 => '7J',
        432606 => '9R',
        432612 => '9F',
        432610 => '10I',
        432613 => 'CHLT',
    ];
  }
  
   public function getPropID($site = null) {

    return 1578438122;
    
  }

  public function getAgency($id_chanel) {
    // airbnb => 4,
    //booking => 1

    $chanels = [
        1 => 4,
        2 => 1,
        3 => 1,
    ];

    return isset($chanels[$id_chanel]) ? $chanels[$id_chanel] : -1;
  }

}
