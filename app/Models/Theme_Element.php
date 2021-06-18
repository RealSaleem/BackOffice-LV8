<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Theme_Element extends Model{

    protected $table = 'theme_element';

    public function themes_settings(){
        return $this->hasMany('App\Models\Themes_Settings','theme_element_id');
    }
}