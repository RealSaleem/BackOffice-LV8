<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StorePlugin extends Model{

    protected $table = 'store_plugin';

    
    public function store()
    {
        return $this->belongsTo('App\Models\Store');
    } 
    public function plugin()
    {
        return $this->belongsTo('App\Models\Plugin');
    } 
}