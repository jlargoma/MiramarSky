<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Rooms;
class RoomsPhotos extends Model
{
  
  public function migratePhotos() {
    $rooms = Rooms::all();
    foreach ($rooms as $room){
        $directory = public_path() . "/img/miramarski/apartamentos/" . $room->nameRoom;
        if (!file_exists($directory))
        {
                mkdir($directory, 0777, true);
        }
        $directorio = dir($directory);
        
         while ($archivo = $directorio->read()){
           if ($archivo != '.' && $archivo != '..' ){
             
           }
         }


    }
  }
  public function room()
  {
          return $this->belongsTo(Rooms::class)->first();
  }
}
