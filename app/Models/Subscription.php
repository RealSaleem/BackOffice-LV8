<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model{
    protected $table = 'subscriptions';

   public function store(){
        return $this->belongsTo('App\Models\Store');
    }   
    public function package(){
        return $this->belongsTo('App\Models\Packages', 'package_id', 'id');
    }
     public function subscription_items(){
        return $this->hasMany('App\Models\SubscriptionItems');
    }
    
}