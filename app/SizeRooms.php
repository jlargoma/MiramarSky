<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SizeRooms extends Model
{
    protected $table = 'sizerooms';

    public function rooms()
    {
        return $this->hasMany('\App\Rooms', 'id', 'sizeApto');
    }
}
