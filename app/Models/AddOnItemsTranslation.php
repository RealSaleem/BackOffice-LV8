<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class AddOnItemsTranslation extends Model{
    protected $table = 'add_on_items_translations';

    public function add_on_items(){
        return $this->belongsTo('App\Models\AddOnItems');
    }
}