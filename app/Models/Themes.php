<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Themes extends Model{

    protected $table = 'themes';

    public function themes_settings(){
        return $this->hasMany('App\Models\Themes_Settings','themes_id');
    }

    public function sections(){
        return $this->belongsToMany('App\Models\Section','theme_section', 'themes_id', 'section_id');
    }
}