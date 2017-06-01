<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rooms extends Model
{

	public function book()
    {
        return $this->hasOne('\App\Book', 'id', 'room_id');
    }

    public function typeRoom()
    {
        return $this->hasOne('\App\TypeRooms', 'id', 'typeApto');
    }

    public function user()
    {
        return $this->hasOne('\App\User', 'id', 'owned');
    }

    public static function getPaxRooms($pax,$room)
        {
            $room = \App\Rooms::where('id', $room)->first();
            
            return $room->sizeRooms->minOcu;    
        }
}
