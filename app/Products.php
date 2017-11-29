<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    public function tax()
    {
        return $this->hasOne('\App\Taxes', 'id', 'tax_id');
    }

    public function orders()
    {
        return $this->hasMany('\App\Products_orders', 'id', 'product_id');
    }
}
