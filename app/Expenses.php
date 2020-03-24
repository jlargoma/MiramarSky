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
  
  //Para poner nombre al tipo de cobro//
  static function getTypeCobro($typePayment=NULL) {
    $array = [
        0 => "Tarjeta visa",//"Metalico Jorge",
        2 => "CASH",// "Metalico Jaime",
        3 => "Banco",//"Banco Jorge",
    ];

    if (!is_null($typePayment)) return $typePayment = $array[$typePayment];
    
    return $array;
  }
  
  static function getListByRoom($start,$end,$roomID){
    return self::where('date', '>=', $start)
            ->Where('date', '<=', $end)
            ->Where('PayFor', 'LIKE', '%' . $roomID. '%')       
            ->orderBy('date', 'DESC')
            ->get();
            
//    return self::where('date', '>=', $start)
//            ->Where('date', '<=', $end)
//            ->Where(function ($query2) use ($roomID) {
//              $query2->WhereNull('PayFor')->orWhere('PayFor', 'LIKE', '%' . $roomID. '%');
//            })          
//            ->orderBy('date', 'DESC')
//            ->get();
  }
}
