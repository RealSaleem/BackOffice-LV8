<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutletZoneCities extends Model{
    protected $table = 'outlet_zone_cities';
        
   public function zones(){
    	return $this->belongsTo('App\Models\OutletZone');
    }
}