<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prices extends Model
{
    public function typeSeasons()
    {
        return $this->hasOne('\App\TypeSeasons', 'id', 'season');
    }
    
    public static function getCostsFromSeason($season, $pax){
        return Prices::select(['cost', 'price'])->where('season', $season)
                ->where('occupation', $pax)->get()->toArray();
    }

}
