<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $table = 'currencies';

    public function cities(){
        return $this->hasMany('App\Models\City','country_id');
    }
    public function countries()
    {
        return $this->hasMany('App\Models\OutletZoneCountries','country_id');
    }
}
