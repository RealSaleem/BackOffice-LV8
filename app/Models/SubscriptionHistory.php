<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionHistory extends Model{
    protected $table = 'subscriptions_history';

   public function store(){
        return $this->belongsTo('App\Models\Store');
    }   
    public function package(){
        return $this->belongsTo('App\Models\Packages', 'package_id', 'id');
    }
     public function subscription_items(){
        return $this->hasMany('App\Models\SubscriptionItems' , 'subscription_id', 'subscription_id');
    }
    
}