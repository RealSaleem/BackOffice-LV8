<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutletZone extends Model{
    protected $table = 'outlet_zone';
    
    public function counties(){
        return $this->hasMany('App\Models\OutletZoneCountries');
    }
     public function cities(){
        return $this->hasMany('App\Models\OutletZoneCities');
    }
    public function areas(){
    	return $this->hasMany('App\Models\OutletZoneAreas');
    }
    
    public function store(){
        return $this->belongsTo('App\Models\Store');
    } 
    public function zone_countries()
    {
        return $this->belongsToMany('App\Models\OutletZone','outlet_zone_countries','outlet_zone_id','country_id');
    }
    public function zone_cities()
    {
        return $this->belongsToMany('App\Models\OutletZone','outlet_zone_cities','outlet_zone_id','city_id');
    }
    public function zone_areas()
    {
        return $this->belongsToMany('App\Models\OutletZone','outlet_zone_areas','outlet_zone_id','area');
    } 
}