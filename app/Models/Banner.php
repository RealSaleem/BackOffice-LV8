<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model{

    protected $table = 'web_banners';

    public function websetting(){
        return $this->belongsTo('App\Models\WebSettings','store_id','store_id');
    }	

    public function category(){
        return $this->belongsTo('App\Models\Category');
    }

    public function product(){
        return $this->belongsTo('App\Models\Product');
    }
    public function transalation()
    {
        return $this->hasMany('App\Models\LanguageTranslation','banner_id');
    }
}