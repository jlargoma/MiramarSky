<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    public function getProducts()
    {

        return \App\Products_orders::where('order_id', $this->id)->get();
    }

    public function book()
    {
        return $this->hasOne('\App\Book', 'id', 'book_id');
    }
}
