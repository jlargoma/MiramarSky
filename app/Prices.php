<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prices extends Model
{
    public function typeSeasons()
    {
        return $this->hasOne('\App\TypeSeasons', 'id', 'season');
    }

}
