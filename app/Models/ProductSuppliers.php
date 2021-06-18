<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSuppliers extends Model{
    protected $table = 'product_suppliers';

    public function product(){
        return $this->belongsTo('App\Models\Product');
    }
    public function supplier(){
        return $this->belongsTo('App\Models\Supplier');
    }
}