<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model{

    protected $table = 'stores';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $guarded = [];
    //protected $fillable = ['name'];

	/**
     * Get the products for the store.
     */
    public function products(){
        return $this->hasMany('App\Models\Product');
    }

    public function brands(){
        return $this->hasMany('App\Models\Brand');
    }

    public function categories(){
        return $this->hasMany('App\Models\Category');
    }

    public function suppliers(){
        return $this->hasMany('App\Models\Supplier');
    }

    public function product_variants()
    {
        return $this->hasManyThrough('App\Models\ProductVariant', 'App\Models\Product','store_id','product_id','id','id');
    }
    public function variants(){
        return $this->hasMany('App\Models\ProductVariant');
    }

    public function outlets(){
        return $this->hasMany('App\Models\Outlet');
    }

    public function registers(){
		return $this->hasManyThrough('App\Models\Register','App\Models\Outlet','store_id','outlet_id','id');
	}

    public function users(){
        return $this->hasMany('App\Models\User');
    }

    public function customers(){
        return $this->hasMany('App\Models\Customer');
    }

    public function industry(){
        return $this->belongsTo('App\Models\Industry');
    }

    public function webstore(){
        return $this->hasOne('App\Models\WebStore');
    }

    public function websettings(){
        return $this->hasOne('App\Models\WebSettings');
    }

    public function roles(){
        return $this->hasOne('App\Models\Role');
    }
     public function subscription(){
        return $this->hasOne('App\Models\Subscription');
    }
    public function subscriptionhistory(){
        return $this->hasMany('App\Models\SubscriptionHistory');
    }

    public function languages(){
        return $this->belongsToMany('App\Models\Language','store_language')->orderBy('id');
    }
    public function timeslot(){
        return $this->hasMany('App\Models\TimeSlot');
    }
    public function holidays(){
        return $this->hasMany('App\Models\Holidays');
    }
    public function loyalty(){
        return $this->hasMany('App\Models\Loyalty');
    }
    public function customer_rewards(){
        return $this->hasMany('App\Models\CustomerRewards');
    }
    public function userstores(){
        return $this->hasMany('App\Models\UsersStore');
    }
    public function salestax(){
        return $this->hasOne('App\Models\SalesTax');
    }
    public function plugins(){
        return $this->belongsToMany('App\Models\Plugin','store_plugin')->withPivot('active');
    }

    public function addons(){
        return $this->hasMany('App\Models\AddOn');
    }


    public function app(){
        return $this->hasMany('App\Models\Apps','id');
    }
}
