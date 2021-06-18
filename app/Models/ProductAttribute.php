<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model{

    protected $table = 'product_attributes';

    public function values(){
        return $this->hasMany('App\Models\ProductAttributeValue','product_attribute_id');
    }
    public function attributes(){
        return $this->belongsTo('App\Models\Product');
    }	
}	