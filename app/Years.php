<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Years extends Model
{
    protected $table = 'years';
    
    static function getActive(){
      $activeYear = null;
      $idYear = getYearActive();
      if (is_numeric($idYear) && $idYear>0)
        $activeYear = Years::find($idYear);
      if (!$activeYear){
        $activeYear = Years::where('active', 1)->first();
      }
      return $activeYear;

//      return self::where('active', 1)->first();
    }
}
