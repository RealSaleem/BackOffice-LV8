<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CityAreas extends Model
{
    protected $table = 'city_areas';

    public function currency(){
        return $this->belongsTo('App\Models\City');
    }
    // public function areas_count(){
    //     return $this->hasMany('App\Models\OutletZoneAreas');
    // }
    public function areas()
    {
        return $this->hasMany('App\Models\OutletZoneAreas','area');
    } 
}
