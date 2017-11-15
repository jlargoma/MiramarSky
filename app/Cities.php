<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cities extends Model
{
    protected $table = 'cities';

    public function country()
    {
        return $this->hasOne('\App\Countries', 'code', 'code_country');
    }

    
}
