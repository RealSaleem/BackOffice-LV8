<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pages extends Model{

    protected $table = 'web_pages';

 //    public function outlet(){
 //        return $this->belongsTo('App\Modles\Outlet');
 //    }

 //    public function registers(){
	// 	return $this->belongsTo('App\Models\Register','App\Models\Outlet','store_id','outlet_id','id');
	// }

 //    public function store(){
 //        return $this->belongsTo('App\Models\Store');
 //    }
    public function transalation()
    {
        return $this->hasMany('App\Models\LanguageTranslation', 'page_id', 'id');
    }
}