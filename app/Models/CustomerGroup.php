<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerGroup extends Model{
    protected $table = 'customer_groups';
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

    public function customer(){
    	return $this->hasMany('App\Models\Customer');
    }

}
