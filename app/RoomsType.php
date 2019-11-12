<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoomsType extends Model
{
  
  public function createItems() {
    $lst = [
        'estudio-standard-sierra-nevada'      => 'ESTUDIO',
        'estudio-lujo-sierra-nevada'          => 'ESTUDIO LUJO',
        'apartamento-un-dormitorio'           => 'APTO UN DORMITORIO',
        'apartamento-standard-sierra-nevada'  => 'APTO DOS DORM',
        'apartamento-lujo-sierra-nevada'      => 'APTO DOS DORM LUJO',
        'apartamento-lujo-gran-capacidad-sierra-nevada' => 'APTO GRAN OCUPACION',
        'chalet-los-pinos-sierra-nevada'      => 'CHALET LOS PINOS',
        'monte-gorbea'                        => 'MONTE GORBEA',
        'el-edificio'                         => 'EL EDIFICIO'
        ];
    
        foreach ($lst as $k=>$v){
          $obj = new RoomsType();
          $obj->name  = $k;
          $obj->title = $v;
          $obj->gallery_key = $k;
          $obj->status = 1;
          $obj->save();
          
        }
    
  }
  
  static function getRoomType($sizeRoom) {
    
    $typeApto = '';
    
    switch ($sizeRoom){
      case 2:
      case 6:
         $typeApto = "2 DORM";
         break;
      case 5:
      case 1:
         $typeApto = "Estudio";
         break;
      case 3:
      case 4:
      case 7:
      case 8:
         $typeApto = "3 DORM";
         break;
      case 9:
         $typeApto = "CHALET";
         break;
       
      
    }
    
    return $typeApto;
    
  }
}
