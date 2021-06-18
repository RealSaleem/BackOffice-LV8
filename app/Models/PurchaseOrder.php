<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model{

    protected $table = 'purchase_order';

    public function outlet(){
        return $this->hasOne('App\Models\Outlet','id','dest_outlet_id');
    }

    public function src_outlet(){
        return $this->hasOne('App\Models\Outlet','id','src_outlet_id');
    }

    public function supplier(){
        return $this->hasOne('App\Models\Supplier','id','supplier_id');
    }

    public function order_quantity(){
        return $this->hasMany('App\Models\PurchaseOrderItem','purchase_order_id','id');
    }

    public function purchase_order_item(){
        return $this->hasMany('App\Models\PurchaseOrderItem','purchase_order_id','id');
    }    

 //    public function registers(){
	// 	return $this->belongsTo('App\Models\Register','App\Models\Outlet','store_id','outlet_id','id');
	// }

 //    public function store(){
 //        return $this->belongsTo('App\Models\Store');
 //    }
}