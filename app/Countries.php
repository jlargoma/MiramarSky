<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Countries extends Model
{
    protected $table = 'countries';
    
    public function cities()
    {
        return $this->hasMany('\App\Cities', 'code', 'code_country');
    }
}
