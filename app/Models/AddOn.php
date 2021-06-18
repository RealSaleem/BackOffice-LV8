<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class AddOn extends Model{
    protected $table = 'add_on';

    protected $fillable = ['store_id', 'type', 'is_active','identifier','name','language_key', 'code', 'min', 'max'];

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

    public function items(){
    	return $this->hasMany('App\Models\AddOnItems');
    }

    public function transalation()
    {
        return $this->hasMany('App\Models\AddOnTranslation');
    }
    public function store(){
        return $this->belongsTo('App\Models\Store');
    }


    public function item_Product(){
       return $this->belongsToMany('App\Models\Product');
    }


    public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }


}


