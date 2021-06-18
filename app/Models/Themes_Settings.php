<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Themes_Settings extends Model{

    protected $table = 'themes_settings';

    public function themes(){
        return $this->belongsTo('App\Models\Themes');
    }
    public function theme_element(){
        return $this->belongsTo('App\Models\Theme_Element');
    }
}