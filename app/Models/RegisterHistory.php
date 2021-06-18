<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegisterHistory extends Model{
    protected $table = 'register_history';

    public $cash;
    public $credit;
    public $voucher;

	public function register()
	{
		return $this->belongsTo('App\Models\Register');
	}

	public function cash_flow()
	{
		return $this->hasMany('App\Models\CashFlow');
	}

	public function orders()
	{
		return $this->hasMany('App\Models\Order');
	}
}