<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashFlow extends Model{
    protected $table = 'cash_flow';

	public function cash_flow()
	{
		return $this->belongsTo('App\Models\RegisterHistory');
	}

	public function user()
	{
		return $this->belongsTo('App\Models\User','created','id');
	}

	public function register_history(){
        return $this->belongsTo('App\Models\RegisterHistory');
    }
}