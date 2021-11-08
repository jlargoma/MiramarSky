<?php

namespace App\Services\DataDis;

class Config {

  function getRooms($room=null) {
    $lst = [
        115 => [
          "address" => "MIRAMAR SKI   8 D", //8D
          "cups"  =>  "ES0031104624626027NF0F",
          "pointType"  =>  5,
          "distributorCode"  =>  "2"
        ],
        142 => [
          "address" => "MIRAMAR SKI 0  5 D", //5D
          "cups"  =>  "ES0031104624626045JW0F",
          "pointType"  =>  4,
          "distributorCode"  =>  "2"
        ],
        163 => [
          "address" => "MIRAMAR SKI 5 O", // o5
          "cups"  =>  "ES0031104624631018EF0F",
          "pointType"  =>  5,
          "distributorCode"  =>  "2"
        ],
        155=> [
          "address" => "C/ HERMANOS MACHADO, 2 3A ESC. A, 3ºA 28660-BOADILLA DEL MONTE", //5F
          "cups"  =>  "ES0021000011610871HP",
          "pointType"  =>  5,
          "distributorCode"  =>  "8"
        ],
    ];
    if ($room){
      return isset($lst[$room]) ? $lst[$room] : -1;
    }
    return $lst;
  }

 

}
