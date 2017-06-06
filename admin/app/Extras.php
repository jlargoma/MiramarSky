<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Extras extends Model
{
    public function extrasBooks()
            {
                return $this->hasMany('\App\ExtrasBooks', 'id', 'extra_id');
            }
}
