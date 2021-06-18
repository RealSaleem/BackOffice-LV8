<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model{

    protected $table = 'section';

    public function theme(){
        return $this->belongsToMany('App\Models\Themes','theme_section', 'section_id', 'themes_id');
    }
}