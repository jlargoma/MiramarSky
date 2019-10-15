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
 
  public function recalculate() {
    $TotalItems = ForfaitsOrderItem::where('order_id',$this->id)
            ->where('status','!=', 'cancel')->sum('total');
    $this->total = $TotalItems;
    $this->save();
  }
}
