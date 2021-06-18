<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Industry extends Model{
    protected $table = 'industries';

	/**
     * Get the stores for the industry.
     */
    public function stores(){
        return $this->hasMany('App\Models\Store');
    }

    public function user(){
        return $this->hasOne('App\Models\User','id','created_by');
    }
}