<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rooms extends Model
{
    public function sizeRooms()
    {
        return $this->hasOne('\App\SizeRooms', 'id', 'sizeRoom');
    }

    public function user()
    {
        return $this->hasOne('\App\User', 'id', 'user_id');
    }
}
