<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebStore extends Model{

    protected $table = 'web_stores';

    public function links(){
        return $this->hasMany('App\Models\WebSocialLink','store_id','store_id');
    }
}