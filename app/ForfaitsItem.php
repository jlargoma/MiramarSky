<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ForfaitsItem extends Model
{
  static function getCategories(){
    return [
        "packs_clases" => 'Pack Clases',
        "esqui" => 'Esquí',
        "snowblade" => 'Snowblade',
        "snowboard" => 'Snowboard',
        "cascos" => 'Cascos',
    ];
  }
  static function getClasses(){
    return [
        "indiv_class" => 'CLASES PARTICULARES',
        "grupal_class" => 'Clase Colectivas',
    ];
  }
  public function getCategory(){
    
    $list = self::getCategories();
    if (isset($list[$this->cat])){
      return $list[$this->cat];
    } else {
      $list = self::getClasses();
      if (isset($list[$this->cat])){
        return $list[$this->cat];
      }
    }
     
    return '';
    
  }
}
