<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DineIn extends Model{

    protected $table = 'dinein_banners';

    public function DineInBanners(){
        return $this->hasMany('App\Models\DineInBanners','store_id','store_id');
    }

} 