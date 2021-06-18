<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerRewards extends Model{
    protected $table = 'customer_rewards';

    public function store(){
        return $this->belongsTo('App\Models\Store');
    }
    public function customer(){
    	return $this->belongsTo('App\Models\Customer');
    }
}