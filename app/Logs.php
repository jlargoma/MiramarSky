<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Logs extends Model
{
    
  public function infoProceess($tipe,$msg,$content=null) {
    Logs::insert([
        'type' => $tipe,
        'msg' => $msg,
        'content' => $content,
    ]);
  }
  
    
  static function getLastInfo($type,$limit = 5) {
    $oLst = Logs::where('type',$type)
            ->orderBy('date','DESC')->limit($limit)
            ->get();
  
    $lst = '<ul>';
    if ($oLst){
      foreach ($oLst as $l)
        $lst .= '<li><b>'. convertDateTimeToShow_text($l->date).':</b> '.$l->msg.'</li>';
      
    } else {
      $lst .= '<li>Aún no hay registros cargados para el mes en curso</li>';
    }
    $lst .= '</ul>';
    return $lst;
  }
}
