<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TypeSeasons extends Model
{
	protected $table = 'typeseasons';

   public function seasons()
    {
        return $this->hasMany('\App\Seasons', 'id', 'type');
    }
}
