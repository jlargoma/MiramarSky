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


}
