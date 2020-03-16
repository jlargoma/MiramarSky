<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Incomes extends Model
{
    protected $table = 'incomes';
     static function getTypes(){
      return [
          'extr' => 'EXTRAORDINARIOS',
          'rappel_closes' => 'RAPPEL CLOSES',
          'others' => 'OTROS',
      ];
    }
}
