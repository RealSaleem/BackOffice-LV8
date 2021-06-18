<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Register extends Model{
    protected $table = 'registers';

	public function orders()
	{
		return $this->hasMany('App\Models\Order');
	}

	public function register_history()
	{
		return $this->hasMany('App\Models\RegisterHistory');
	}

	public function outlet()
	{
		return $this->belongsTo('App\Models\Outlet');
	}
}