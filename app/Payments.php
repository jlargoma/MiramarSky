<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
	protected $type = 0;

    public function book()
            {
                return $this->hasOne('\App\Book', 'id', 'book_id');
            }

    //Para poner nombre al tipo de pago//
	   static function getType($type)
            {
            	$array = [1 =>"Metalico Jorge", 2 =>"Metalico Jaime",3 =>"Banco"];

            	return $type = $array[$type];
            }
}
