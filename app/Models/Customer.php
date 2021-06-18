<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Customer extends Model{
    protected $table = 'customers';
    use SoftDeletes;

    protected $dates = ['deleted_at'];

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

    public function order(){
    	return $this->hasMany('App\Models\Order');
    }

    public function customer_group(){
    	return $this->belongsTo('App\Models\CustomerGroup');
    }

    public function store(){
        return $this->belongsTo('App\Models\Store');
    }

    public function user(){
    	return $this->hasOne('App\Models\User','id','created');
    }
    public function customer_rewards(){
        return $this->hasMany('App\Models\CustomerRewards');
    }
}
