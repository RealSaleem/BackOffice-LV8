<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutletZoneCountries extends Model{
    protected $table = 'outlet_zone_countries';
   
   public function zones(){
    	return $this->belongsTo('App\Models\OutletZone');
    }     
}