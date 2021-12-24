<?php

namespace App\Models\Forfaits;

use Illuminate\Database\Eloquent\Model;
use App\Models\Forfaits\ForfaitsOrderItem;

class ForfaitsOrders extends Model
{
  
/**
 * ORDER STATUS
 * status==1 => pend
 * status==2 => pay
 * status==3 => cancel
 * 
 */
          
//  static function getByBook($bookID) {
//    $order = self::where('book_id',$bookID)->first();
//    if ($order){
//      return $order;
//    } else {
//      $order = new ForfaitsOrders();
//      $order->book_id = $bookID;
//      $order->save();
//      return $order;
//    }
//  }
  
  static function getByKey($key) {
    $order = null;
     if ($key){
      $aKey = explode('-',$key);
      $orderID = isset($aKey[0]) ? ($aKey[0]) : null;
      $control = isset($aKey[1]) ? ($aKey[1]) : null;
      $orderID = desencriptID($orderID);
      
      if ($orderID>0 && $control == getKeyControl($orderID)){
        $order = ForfaitsOrders::find($orderID);
      }
     }
     return $order;
  }
 
  public function recalculate() {
    $TotalItems = ForfaitsOrderItem::where('order_id',$this->id)
            ->WhereNull('cancel')->sum('total');
    
    if ($TotalItems){
      /** @FF-ToDo */
//      if ($this->total < $TotalItems){
//        $this->status = 2;
//        if ($this->book_id){
//          \App\Book::where('id', $this->book_id)->update(['ff_status' => 2]);
//        }
//      }
      $this->total = $TotalItems;
    }
    else {
      $this->total = 0;
    }
    
    $this->save();
  }
  public function getInsurName($id) {
    $insur = array(
      5 => 'Seguro Básico de Esquí',
      6 => 'Seguro Completo de Esquí'
        );
    
    return isset($insur[$id]) ? $insur[$id] : '';
  }
  
  public function get_ff_status($showAll = true) {
    $result = [
        'name' => '',
        'icon' => null
    ];

    switch ($this->status) {
      case 0:
        if ($showAll) {
          $result = [
              'name' => 'No Gestionada',
              'icon' => asset('/img/miramarski/ski_icon_status_transparent.png')
          ];
        }
        break;
      case 1:
        $result = [
            'name' => 'Cancelada',
            'icon' => asset('/img/miramarski/ski_icon_status_grey.png')
        ];
        break;
      case 2:
        $result = [
            'name' => 'Pendiente',
            'icon' => asset('/img/miramarski/ski_icon_status_red.png')
        ];
        break;
      case 3:
        $result = [
            'name' => 'Cobrada',
            'icon' => asset('/img/miramarski/ski_icon_status_green.png')
        ];
        break;
      case 4:
        $result = [
            'name' => 'Comprometida',
            'icon' => asset('/img/miramarski/ski_icon_status_orange.png')
        ];
        break;
      case 5:
        $result = [
            'name' => 'INTERESADOS',
            'icon' => asset('/img/miramarski/ski_icon_status_rosa.png')
        ];
        break;
    }
    return $result;
  }
  
  
  function forfaits() {
    return $this->belongsTo('App\Models\Forfaits\Forfaits');
  }
}
