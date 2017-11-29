<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Products_orders extends Model
{
    protected $table = 'products_orders';
    
    public function order()
    {
        return $this->hasMany('\App\Orders', 'id', 'order_id');
    }

    public function product()
    {
        return $this->hasOne('\App\Products', 'id', 'product_id');
    }
}
