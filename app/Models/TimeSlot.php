<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeSlot extends Model{
    protected $table = 'time_slot';

    public function store(){
        return $this->belongsTo('App\Models\Store');
    }
}