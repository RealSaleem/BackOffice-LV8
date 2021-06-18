<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class SyncParkedSales extends Model
{
    protected $table = 'park_sales_sync';

    public function store()
    {
    	return $this->belongsTo('App\Models\Store');
    }

    public function register()
    {
    	return $this->belongsTo('App\Models\Register');
    }

    public function user()
    {
    	return $this->belongsTo('App\Models\User','user_id');
    }    

    public function order()
    {
        return $this->hasOne('App\Models\Order', 'identity', 'park_identity');
    }    
}
