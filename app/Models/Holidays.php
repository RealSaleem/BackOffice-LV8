<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Holidays extends Model{
    protected $table = 'holidays';

    public function store(){
        return $this->belongsTo('App\Models\Store');
    }
}