<?php

namespace App\Models\Forfaits;

use Illuminate\Database\Eloquent\Model;
use App\Models\Forfaits\ForfaitsOrderItem;

class ForfaitsOrders extends Model
{
  
  static function getByBook($bookID) {
    $order = self::where('book_id',$bookID)->first();
    if ($order){
      return $order;
    } else {
      $order = new ForfaitsOrders();
      $order->book_id = $bookID;
      $order->save();
      return $order;
    }
  }
  
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
    
    if ($TotalItems)
      $this->total = $TotalItems;
    else $this->total = 0;
    
    $this->save();
  }
  public function getInsurName($id) {
    $insur = array(
      5 => 'Seguro Básico de Esquí',
      6 => 'Seguro Completo de Esquí'
        );
    
    return isset($insur[$id]) ? $insur[$id] : '';
  }
}
