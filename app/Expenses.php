<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expenses extends Model
{
    protected $table = 'expenses';
     
  static function getTypes(){
    return [
      'CAPSULAS' => 'CAPSULAS',
      'DECORACION' => 'DECORACION',
      'EQUIPAMIENTO_VIVIENDA' => 'EQUIPAMIENTO VIVIENDA',
      'LIMPIEZA' => 'LIMPIEZA',
      'MENAJE' => 'MENAJE',
      'PAGO_PROPIETARIO' => 'PAGO PROPIETARIO',
      'REPARACION_CONSERVACION' => 'REPARACION Y CONSERVACION',
      'SABANAS_TOALLAS' => 'SABANAS Y TOALLAS',
      'VARIOS' => 'VARIOS',
    ];
  }
}
