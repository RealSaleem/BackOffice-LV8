<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modules extends Model{

    protected $table = 'modules';

    public function packages(){
    	return $this->hasMany(Packages::class, 'module_id', 'id');
    } 	
     public function subscription_items(){
        return $this->hasMany('App\Models\SubscriptionItems');
    }

 }