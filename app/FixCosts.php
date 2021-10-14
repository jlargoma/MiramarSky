<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FixCosts extends Model
{
    protected $table = 'fix_costs';
//    public $timestamps = false;
    
  static function getLst(){
    return [
        'alq'=>'ALQUILER',
        'serv'=>'LUZ / AGUA /INTERNET',
        'seg'=>'SEGURO',
        'manten'=>'MANTENIMIENTO Y REPARACIONES',
        'repos'=>'REPOSICIÓN MUEBLES / MÁQUINAS',
        'limp'=>'PRODUCTOS LIMPIEZA Y AMENITIES',
        'pers'=>'PERSONAL ESPECÍFICO',
        'imput'=>'IMPUTACIÓN CTE FIJO ESTRUCTURADA',
        'varios'=>'VARIOS',
    ];
  }
  
  static function getByRang($start,$end){
    return \App\FixCosts::where('date','>=',$start)
            ->where('date','<=',$end)->get();
  }
  
  static function deleteByRang($start,$end){
    return \App\FixCosts::where('date','>=',$start)
            ->where('date','<=',$end)->delete();
  }
}
