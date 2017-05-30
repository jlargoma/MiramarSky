<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rooms extends Model
{

	public function book()
    {
        return $this->hasOne('\App\Book', 'id', 'room_id');
    }

    public function sizeRooms()
    {
        return $this->hasOne('\App\SizeRooms', 'id', 'sizeRoom');
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
