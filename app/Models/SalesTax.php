<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesTax extends Model{

    protected $table = 'sales_tax';

 	public function store(){
        return $this->belongsTo('App\Models\Store');
    }
}