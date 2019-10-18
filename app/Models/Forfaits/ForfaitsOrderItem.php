<?php

namespace App\Models\Forfaits;

use Illuminate\Database\Eloquent\Model;

class ForfaitsOrderItem extends Model
{

  static function getLastItem($orderID){
    $item = self::where('order_id',$orderID)->orderBy('id','DESC')->first();
    if ($item){
      return $item->id;
    }
    else 'null';
  }
}
