<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model{
    protected $table = 'product_variants';
	protected $fillable = ['product_id', 'supplier_price', 'sku', 'attribute_value_1', 'attribute_value_2', 'attribute_value_3', 'markup', 'retail_price', 'image','is_active'];

	public function product(){
		return $this->belongsTo('App\Models\Product');
	}
	public function store(){
		return $this->belongsTo('App\Models\Store');
	}

	public function product_stock(){
		return $this->hasMany('App\Models\ProductStock','variant_id');
	}
	
	// public function product_stock(){
	// 	return $this->hasMany('App\Models\ProductStock','variant_id')->selectRaw('product_stock.variant_id,SUM(product_stock.quantity) quantity,outlets.name,outlets.id outlet_id')->join('outlets', 'product_stock.outlet_id', '=', 'outlets.id')->groupBy('product_stock.product_id')->groupBy('product_stock.variant_id')->groupBy('outlets.name');
	// }

	public function product_add_on()
    {
        return $this->belongsToMany('App\Models\Product','products_add_on','product_id','add_on_id', 'product_id');
    } 

	/*public function stock_product(){
		return $this->hasOne('App\Models\ProductStock','variant_id');
	}*/
}