<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncomeLedger extends Model{
    protected $table = 'account_ledger';

    public function account()
    {
    	return $this->belongsTo('App\Models\Accounthead');
    }

    public function outlet()
    {
    	return $this->belongsTo('App\Models\Outlet');
    }   

    public function store()
    {
    	return $this->belongsTo('App\Models\Store');
    }     
}