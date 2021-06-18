<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DineInBanners extends Model{

    protected $table = 'dinein_banners';

    public function DineIn(){
        return $this->belongsTo('App\Models\DineIn','store_id','store_id');
    }	

    
}