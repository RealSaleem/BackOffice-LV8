<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreEmailTemplate extends Model{
    protected $table = 'store_email_template';

    public function transalation()
    {
        return $this->hasMany('App\Models\LanguageTranslation','email_template_id');
    }    
}