<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Paymentspro extends Model
{
    
    //
    protected $table = 'paymentspro';
    protected $typePayment = 0;
    public function paymentRoom()
    {
        return $this->hasOne('\App\Rooms', 'room_id', 'id');
    }

    //Para poner nombre al dia del calendario//
       static function getPaymentType($type)
            {
                $array = [1=> "Metalico Jorge",2 =>"Metalico Jaime",3=> "Banco"];

                return $typePayment = $array[$type];
            }
}
