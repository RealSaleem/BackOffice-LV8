<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductTag extends Model{
    protected $table = 'product_tags';
    
    public function tag(){
    	return $this->belongsTo('App\Models\Tag');
    }

}