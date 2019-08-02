<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Rooms;
class RoomsPhotos extends Model
{
  
  public function migratePhotos() {
    $rooms = Rooms::all();
    $path = public_path();
    foreach ($rooms as $room){
        $rute = "/img/miramarski/apartamentos/" . $room->nameRoom.'/';
        $directory = "$path/img/miramarski/apartamentos/" . $room->nameRoom;
        if (!file_exists($directory))
        {
                mkdir($directory, 0777, true);
        }
        $directorio = dir($directory);
        $i=0;
         while ($archivo = $directorio->read()){
           if ($archivo != '.' && $archivo != '..' ){
            $obj = new RoomsPhotos();
            $obj->room_id = $room->id;
            $obj->file_rute = $rute.$archivo;
            $obj->file_name = $archivo;
            $obj->status = 'public';
            $obj->position = $i;
            $obj->main = ($i==0) ? 1 : 0;
            $obj->save();
            
            echo $rute.$archivo."\n";
            $i++;
           }
         }
    }
  }
  public function room()
  {
          return $this->belongsTo(Rooms::class)->first();
  }
}
