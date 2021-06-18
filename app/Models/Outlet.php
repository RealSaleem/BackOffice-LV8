<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Outlet extends Model{
    protected $table = 'outlets';


    public function registers(){
        return $this->hasMany('App\Models\Register');
    }

	public function orders()
	{
		return $this->hasManyThrough('App\Models\Order', 'App\Models\Register','outlet_id', 'register_id', 'id');
	}

	public function register_history()
	{
		return $this->hasManyThrough('App\Models\RegisterHistory','App\Models\Register','outlet_id','register_id','id');
	}

	public function users()
	{
		return $this->belongsToMany('App\Models\User');
	}
	public function product_stock()
    {
        return $this->hasMany('App\Models\ProductStock');
    }
    public function zones(){
    	return $this->hasMany('App\Models\OutletZone');
    }

    public function images()
    {
        return $this->hasMany('App\Models\OutletImages');
    }

}
