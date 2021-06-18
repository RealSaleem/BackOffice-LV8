<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebSettings extends Model{

    protected $table = 'web_settings';

    public function banners(){
        return $this->hasMany('App\Models\Banner','store_id','store_id');
    }

    public function links(){
        return $this->hasMany('App\Models\WebSocialLink','store_id','store_id');
    }    
} 