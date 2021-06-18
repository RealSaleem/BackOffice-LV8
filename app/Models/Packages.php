<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Packages extends Model{

    protected $table = 'package_list';

   public function module(){
        return $this->belongsTo('App\Models\Modules');
    }
    public function package_name(){
        return $this->belongsTo('App\Models\PackagesName', 'package_name_id', 'id');
    }
    public function subscription(){
        return $this->belongsTo('App\Models\Subscription', 'id', 'package_id');
    }
     public function subscription_items(){
        return $this->hasMany('App\Models\SubscriptionItems');
    } 
    public function subscriptions_history(){
        return $this->hasMany('App\Models\SubscriptionHistory');
    } 

 }