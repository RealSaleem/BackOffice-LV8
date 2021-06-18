<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAddOn extends Model{
    protected $table = 'products_add_on';

    public function product(){
        return $this->belongsTo('App\Models\Product');
    }
     public function add_on(){
        return $this->belongsTo('App\Models\AddOn');
    }
}