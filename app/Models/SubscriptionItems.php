<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionItems extends Model{
    protected $table = 'subscription_items';

   public function module(){
        return $this->belongsTo('App\Models\Modules');
    }   
    public function subscription(){
        return $this->belongsTo('App\Models\Subscription');
    }
    public function subscriptionhistory(){
        return $this->belongsTo('App\Models\Subscription' , 'subscription_id', 'id');
    }
    
}