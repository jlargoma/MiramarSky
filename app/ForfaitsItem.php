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
  public function getCategory(){
    
    $list = self::getCategories();
    if (isset($list[$this->cat])){
      return $list[$this->cat];
    } else {
      return '';
    }
     
    
  }
}
