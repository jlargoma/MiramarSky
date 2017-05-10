<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Seasons extends Model
{
    public function typeSeasons()
    {
        return $this->hasOne('\App\TypeSeasons', 'id', 'type');
    }}
