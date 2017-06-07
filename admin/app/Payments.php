<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    public function book()
            {
                return $this->hasOne('\App\Book', 'book_id', 'id');
            }
}
