<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrandImage extends Model{
    protected $table = 'brand_images';

    public function brand(){
        return $this->belongsTo('App\Models\Brand');
    }
}