<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddOnItems extends Model{
    protected $table = 'add_on_items';

    protected $fillable = ['language_key', 'add_on_id', 'price','name'];

    public function products(){
    	return $this->belongsTo('App\Models\AddOn');
    }

    public function item_transalation()
    {
        return $this->hasMany('App\Models\AddOnItemsTranslation');
    }
    public function store(){
        return $this->belongsTo('App\Models\Store');
    }
    public function add_on()
    {
        return $this->belongsToMany('App\Models\AddOnItems','add_on','name','name');
    }
}
