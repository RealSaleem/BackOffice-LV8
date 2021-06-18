<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'cities';

    public function currency(){
        return $this->belongsTo('App\Models\Currency');
    }
    public function city_areas(){
        return $this->hasMany('App\Models\CityAreas');
    }
    public function cities()
    {
        return $this->hasMany('App\Models\OutletZoneCities','city_id');
    }
}
