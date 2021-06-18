<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem extends Model
{
    protected $table = 'purchase_order_items';
    
    public function purchase_order(){
    	return $this->belongsTo('App\Models\PurchaseOrder');
    }

    public function product(){
        return $this->hasOne('App\Models\Product','id','product_id');
    }

}