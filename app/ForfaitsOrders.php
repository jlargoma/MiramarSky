<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ForfaitsOrderItem;

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
      $bookingKey = isset($aKey[0]) ? ($aKey[0]) : null;
      $clientKey = isset($aKey[1]) ? ($aKey[1]) : null;
      $bookingID = desencriptID($bookingKey);
      $clientID = desencriptID($clientKey);
      if ($bookingID>0 && $clientID>0){
        $order = ForfaitsOrders::getByBook($bookingID);
      }
     }
     return $order;
  }
 
  public function recalculate() {
    $TotalItems = ForfaitsOrderItem::where('order_id',$this->id)
            ->WhereNull('cancel')->sum('total');
    $this->total = $TotalItems;
    $this->save();
  }
}
