<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariantValue extends Model{

    protected $table = 'product_variant_values';

    public function variant(){
        return $this->belongsTo('App\Models\ProductVariant');
    }
    public function values(){
        return $this->belongsTo('App\Models\ProductAttributeValue');
    }	
}	