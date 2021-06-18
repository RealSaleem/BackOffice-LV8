<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loyalty extends Model{
    protected $table = 'loyalty_program';

    public function store(){
        return $this->belongsTo('App\Models\Store');
    }
}