<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;


class Product extends Model{

    use SoftDeletes;

    protected $table = 'products';
    protected $dates = ['deleted_at'];

    use SoftDeletes;


    public function delete(array $options = [])
    {
        $this->deleted_by = Auth::user()->id;
        $this->deleted_at = time();
        return $this->save();
    }

    public function save(array $options = [])
    {
        if ($this->created_at == null) {
            $this->created = Auth::user()->id;
        } else {
            $this->updated_by = Auth::user()->id;
        }
        return parent::save();
    }



    public function category(){
        return $this->belongsTo('App\Models\Category');
    }

    public function brand(){
        return $this->belongsTo('App\Models\Brand');
    }

    public function supplier(){
        return $this->belongsTo('App\Models\Supplier');
    }

    public function store(){
        return $this->belongsTo('App\Models\Store');
    }

    public function tags(){
        return $this->belongsToMany('App\Models\Tag','product_tags');
  //       return $this->hasMany('App\Models\ProductTag');
		// return $this->hasManyThrough('App\Models\Tag','App\Models\ProductTag','tag_id','id', 'product_id');
    }

    public function product_variants(){
        return $this->hasMany('App\Models\ProductVariant');
    }

    public function product_images(){
        return $this->hasMany('App\Models\ProductImage');
    }

    public function composite_products(){
        return $this->hasMany('App\Models\CompositeProduct');
    }

    public function user(){
        return $this->hasOne('App\Models\User','id','created');
    }

    // public function parent()
    // {
    //     return $this->belongsTo('App\Models\Category', 'parent_id');
    // }

    public function related()
    {
        return $this->belongsToMany('App\Models\Product','products_related','product_id','related_product_id');
    }
    public function categories()
    {
        return $this->belongsToMany('App\Models\Product','product_categories','product_id','category_id');
    }
    public function product_supplires()
    {
        return $this->belongsToMany('App\Models\Product','product_suppliers','product_id','supplier_id');
    }

    public function images()
    {
        return $this->hasMany('App\Models\ProductImage');
    }

    public function language()
    {
        return $this->hasMany('App\Models\ProductLanguage');
    }

    public function transalation()
    {
        return $this->hasMany('App\Models\LanguageTranslation');
    }
    public function product_categories()
    {
        return $this->hasMany('App\Models\ProductCategories');
    }
    public function product_ralated()
    {
        return $this->hasMany('App\Models\ProductRelated');
    }
    public function product_suppliers()
    {
        return $this->hasMany('App\Models\ProductSuppliers');
    }
    public function product_verients()
    {
        return $this->hasMany('App\Models\ProductVariant');
    }
    public function product_stock()
    {
        return $this->hasMany('App\Models\ProductStock');
    }
    public function product_attributes()
    {
        return $this->hasMany('App\Models\ProductAttribute');
    }
    public function product_add_on()
    {
        return $this->belongsToMany('App\Models\Product','products_add_on','product_id','add_on_id');
    }
    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }
}
