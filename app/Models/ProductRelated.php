<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductRelated extends Model{
    protected $table = 'products_related';

    public function product(){
        return $this->belongsTo('App\Models\Product');
    }
}