<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAttributeValue extends Model{

    protected $table = 'product_attribute_values';

    public function websetting(){
        return $this->belongsTo('App\Models\ProductAttribute');
    }
    public function attribute_values(){
        return $this->belongsTo('App\Models\ProductAttribute');
    }
     public function product_attribute_values(){
        return $this->belongsTo('App\Models\ProductVariantValue');
    }	
}	