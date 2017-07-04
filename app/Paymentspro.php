<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Paymentspro extends Model
{
    
    //
    protected $table = 'paymentspro';
    
    public function paymentRoom()
    {
        return $this->hasOne('\App\Rooms', 'room_id', 'id');
    }
}
