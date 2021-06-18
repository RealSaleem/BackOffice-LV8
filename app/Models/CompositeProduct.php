<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompositeProduct extends Model{
    protected $table = 'composite_products';
 	
	public function product_variant(){
        return $this->belongsTo('App\Models\ProductVariant');
    }

}