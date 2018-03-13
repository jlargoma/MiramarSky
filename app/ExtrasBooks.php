<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExtrasBooks extends Model
{
    public function book()
    {
        return $this->hasOne('\App\Book', 'id', 'book_id');
    }

    public function extra()
    {
        return $this->hasOne('\App\Extras', 'id', 'extra_id');
    }
}
