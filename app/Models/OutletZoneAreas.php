<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutletZoneAreas extends Model{
    protected $table = 'outlet_zone_areas';
    
    public function zones(){
    	return $this->belongsTo('App\Models\OutletZone');
    }
        
}