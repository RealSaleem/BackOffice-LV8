<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreApp extends Model{

    protected $table = 'store_app';
    protected $fillable = ['active'];


    public function store()
    {
        return $this->belongsTo('App\Models\Store');
    }
    public function app()
    {
        return $this->belongsTo('App\Models\Apps','app_id');
    }
}
