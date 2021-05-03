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
          'desayuno' => 'DESAYUNOS',
          'minibar' => 'MINIBAR',
          'excursiones' => 'EXCURSIONES',
          'tickets_parking' => 'TICKETS PARKING',
          'others' => 'OTROS',
          'limp_prop' => 'LIMPIEZA PROP.',
      ];
    }
    
    
  static function setPropLimpieza($book_id,$room, $date,$amount=null) {
    //UPDATE 
    if ($book_id>0){
      $obj = Incomes::where('book_id',$book_id)->where('type','limp_prop')->first();
      if ($obj){
        $obj->date = $date;
        $obj->save();
      } else {
        //create
        $obj = new Incomes();
        $obj->comment = "LIMPIEZA RESERVA PROPIETARIO " . $room->nameRoom;
        $obj->concept = "LIMPIEZA RESERVA PROPIETARIO";
        $obj->month = date('m', strtotime($date));
        $obj->year  = date('Y', strtotime($date));
        $obj->import = $amount;
        $obj->date = $date;
        $obj->type = 'limp_prop';
        $obj->book_id = $book_id;
        $obj->save();
        
      }
    }
  }
}
