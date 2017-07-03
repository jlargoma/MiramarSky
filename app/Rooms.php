<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rooms extends Model
{

	public function book()
    {
        return $this->hasMany('\App\Book', 'id', 'room_id');
    }

    public function sizeRooms()
    {
        return $this->hasOne('\App\SizeRooms', 'id', 'sizeApto');
    }

    public function typeAptos()
    {
        return $this->hasOne('\App\TypeApto', 'id', 'typeApto');
    }

    public function user()
    {
        return $this->hasOne('\App\User', 'id', 'owned');
    }

    public static function getPaxRooms($pax,$room)
        {
            $room = \App\Rooms::where('id', $room)->first();
            
            return $room->minOcu;    
        }


}
