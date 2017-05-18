<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TypeRooms extends Model
{
    protected $table = 'typerooms';

    public function rooms()
    {
        return $this->hasMany('\App\Rooms', 'id', 'sizeRoom');
    }
}
