<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookSafetyBox extends Model
{
  static $keys = [
     'caja_1' => '0110', 
     'caja_2' => '0220', 
     'caja_3' => '0330', 
     'caja_4' => '0440', 
     'caja_5' => '0550', 
     'caja_6' => '0660', 
     'caja_7' => '0770', 
     'caja_8' => '0880', 
     'caja_9' => '0990', 
     'caja_10' => '0000', 
  ];
  static $keys_name = [
     'caja_1' => 'BUZON ROJO - CAJA N1', 
     'caja_2' => 'BUZON ROJO - CAJA N2', 
     'caja_3' => 'BUZON AMARILLO - CAJA N3', 
     'caja_4' => 'BUZON AMARILLO - CAJA N4', 
     'caja_5' => 'BUZON VERDE - CAJA N5', 
     'caja_6' => 'BUZON VERDE - CAJA N6', 
     'caja_7' => 'BUZON AZUL - CAJA N7', 
     'caja_8' => 'BUZON AZUL - CAJA N8', 
     'caja_9' => 'BUZON NARANJA - CAJA N9', 
     'caja_10' => 'BUZON NARANJA - CAJA N10', 
  ];
  static $keys_color = [
     'caja_1' => 'ROJO',
     'caja_2' => 'ROJO',
     'caja_3' => 'AMARILLO',
     'caja_4' => 'AMARILLO',
     'caja_5' => 'VERDE',
     'caja_6' => 'VERDE',
     'caja_7' => 'AZUL',
     'caja_8' => 'AZUL',
     'caja_9' => 'NARANJA',
     'caja_10' => 'NARANJA',
  ];
  
  static $keys_caja = [
     'caja_1' => 'N1', 
     'caja_2' => 'N2', 
     'caja_3' => 'N3', 
     'caja_4' => 'N4', 
     'caja_5' => 'N5', 
     'caja_6' => 'N6', 
     'caja_7' => 'N7', 
     'caja_8' => 'N8', 
     'caja_9' => 'N9', 
     'caja_10' => 'N10', 
  ];
  
  function getKey(){
    $aux = self::$keys;
    if (isset($aux[$this->key])) return $aux[$this->key];
    return '--';
  }
  
  function getBuzon(){
    $aux = self::$keys_name;
    if (isset($aux[$this->key])) return $aux[$this->key];
    return '--';
  }
  function getBuzonColor(){
    $aux = self::$keys_color;
    if (isset($aux[$this->key])) return $aux[$this->key];
    return '--';
  }
  function getBuzonCaja(){
    $aux = self::$keys_caja;
    if (isset($aux[$this->key])) return $aux[$this->key];
    return '--';
  }
}
