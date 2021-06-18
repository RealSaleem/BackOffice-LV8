<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockControl extends Model{
    protected $table = 'stockcontrol';
    
    public function products(){
    	return $this->hasMany('App\Models\Product');
    }

    public function transalation()
    {
        return $this->hasMany('App\Models\LanguageTranslation');
    }     
}