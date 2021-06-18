<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model{
    protected $table = 'email_template';

    public function transalation()
    {
        return $this->hasMany('App\Models\LanguageTranslation','email_template_id');
    }

    public function store(){
        return $this->belongsTo('App\Models\Store');
    }  
}
