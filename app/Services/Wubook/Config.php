<?php

namespace App\Services\Wubook;

class Config{
  public function roomsEquivalent() {
      
      
      
      return [
          433742 => 164,//"TES1"
          433743 => 165,//"TES2"
          433744 => 166,//"TES3"
          431916 => 166,//"TES3" BORRAR
          ];
      
      
      
      
      
      
      $lst = [
        432364 => 142,//"5D",
        432365 => 155,//"5F",
        432366 => 119,//"8T",
        432360 => 0,//"DDL"
        432361 => 115,//"8D",
        431916 => 153,//"7J",
        432606 => 165,//"9R",
        432610 => 122,//"10I",
        432611 => 160,//"GORB",
        432612 => 149,//"9F",
        432613 => 144,//"CHLT",
        432614 => 0,//"DDS",
        432615 => 129,//"4P",
        432616 => 161,//"7P",
        432617 => 117,//"4N",
        432618 => 159,//"G8",
        432619 => 139,//"G6",
        432620 => 140,//"J6",
        432621 => 124,//"J5",
        432622 => 164,//"J8",
        432623 => 158,//"V10",
        432624 => 0,//"ST-L",
        432625 => 154,//"5E",
        432626 => 138,//"9J",
        432627 => 145,//"9S",
        432628 => 157,//"S7",
        432629 => 163,//"O5",
        432630 => 0,//"ST-S",
        432631 => 125,//"U7",
        432632 => 110,//"H7",
        432633 => 152,//"5K",
        432634 => 162,//"6K",
        432635 => 116,//"V8",
        432636 => 141,//"B5",
        432362 => 123,//"8F",
      ];
      
      //BEGIN: Rooms by Size
      $sizes = [
        1 => 432630,//'ST-S',
        5 => 432624,//'ST-L',
        2 => 432614,//'DDS',
        6 => 432360,//'DDL',
      ];
      
      foreach ($sizes as $k=>$v){
        $rooms = Rooms::select('id')
                ->where('sizeApto',$k)
                ->where('state',1)
                ->whereNotIn('id',$lst)->get();
        if ($rooms){
          $newRId = array();
          foreach ($rooms as $r){
            $newRId[] = $r->id;
          }
          
          $lst[$v] = $newRId;
        }
        
      }
      //END: Rooms by Size
      return $lst;
      
    }
    
    public function getAgency($id_chanel) {
      // airbnb => 4,
      //booking => 1
      
      $chanels = [
        1=>4,  
        2=>1,  
        3=>1,  
      ];
  
      return isset($chanels[$id_chanel]) ? $chanels[$id_chanel] : -1;
    }
}