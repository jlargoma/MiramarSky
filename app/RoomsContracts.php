<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Rooms;

class RoomsContracts extends Model
{
  
  static function getMain($yearID){
    $obj = self::where('year_id', $yearID)
            ->whereNull('room_id')->first();
    if (!$obj){
      $obj = new RoomsContracts();
      $obj->year_id =  $yearID;
//      $obj->room_id = null;
      $obj->save();
    }
    return $obj;
  }
  
  static function getContract($yearID,$roomID){
    $obj = self::where('year_id', $yearID)
            ->where('room_id',$roomID)->first();
    if (!$obj){
      $obj = new RoomsContracts();
      $obj->year_id =  $yearID;
      $obj->room_id = $roomID;
      $obj->content = self::getMain($yearID)->content;
      $obj->save();
    }
    return $obj;
  }
  
  function getText($oRoom,$oUser,$sRates, $calendar){
    $text = $this->content;
    if ($oUser){
      $text = str_replace('{usuario_nombre}', $oUser->name, $text);
      $text = str_replace('{usuario_dni}', $oUser->nif, $text);
      if ($oUser->nif_business){
        $text = str_replace('{usuario_representacion}', 
                ' en nombre y representación de '.$oUser->name_business
                .' on CIF: '.$oUser->nif_business
                .' y domicilio en '.$oUser->address_business.', ',
                $text);

      }
    }
    if($oRoom) $text = str_replace('{room_name}', $oRoom->nameRoom, $text);
    
    $text = str_replace('{temporada_rango}', $sRates->seasson, $text);
    $text = str_replace('{temporada_calendario}', $calendar, $text);
    $text = str_replace('{temporada_costos}', $sRates->printTarifas($oRoom), $text);
    $text = str_replace('{break}', '<div class="break"></div>', $text);
    $text = preg_replace('/\{(\w+)\}/i', '', $text);
    
    return $text;
  }
}
