<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class AddOnTranslation extends Model{
    protected $table = 'add_on_translations';

    public function add_on(){
        return $this->belongsTo('App\Models\AddOn');
    }
}