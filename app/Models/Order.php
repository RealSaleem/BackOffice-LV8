<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    
    public $cash;
    public $credit;
    public $voucher;

    public function orderitems(){
    	return $this->hasMany('App\Models\OrderItem');
    }

    public function customer(){
    	return $this->belongsTo('App\Models\Customer');
    }

    public function user(){
    	return $this->belongsTo('App\Models\User');
    }

    public function register(){
    	return $this->belongsTo('App\Models\Register');
    }

    public function register_history(){
        return $this->belongsTo('App\Models\RegisterHistory');
    }

    public function outlet(){
        return $this->belongsTo('App\Models\Outlet');
    }
}