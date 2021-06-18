<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model{
    protected $table = 'tags';

    public function products(){
    	return $this->belongsToMany('App\Models\Product','product_tags');
    }
    
}