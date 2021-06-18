<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';

    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }

    public function variant()
    {
        return $this->hasMany('App\Models\ProductVariant', 'id', 'variant_id');
    }
}
