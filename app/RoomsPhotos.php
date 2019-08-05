<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Rooms;

class RoomsPhotos extends Model
{
  
  
  public function room()
  {
    return $this->belongsTo(Rooms::class)->first();
  }
  
  static function getGalleries(){
    return [
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
  }
  

  public function existsGal($gallery) {
    
    $all = self::getGalleries();
    
    return isset($all[$gallery]);
    
  }
  
  
  
}
