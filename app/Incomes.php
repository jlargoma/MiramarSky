<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Incomes extends Model
{
    protected $table = 'incomes';
     static function getTypes(){
      return [
          'extr' => 'EXTRAORDINARIOS',
          'rappel_clases' => 'RAPPEL CLASES',
          'rappel_forfaits' => 'RAPPEL FORFAITS',
          'rappel_alq_material' => 'RAPPEL ALQ MATERIAL',
          'others' => 'OTROS',
      ];
    }
}
