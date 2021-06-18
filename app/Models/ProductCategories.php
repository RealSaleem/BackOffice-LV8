<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategories extends Model{
    protected $table = 'product_categories';

    public function category(){
        return $this->belongsTo('App\Models\Category');
    }
    public function product(){
        return $this->belongsTo('App\Models\Product');
    }
    public function parent()
    {
        return $this->belongsTo('App\Models\Category', 'parent_id');
    }
}