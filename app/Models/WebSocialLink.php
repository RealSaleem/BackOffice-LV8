<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebSocialLink extends Model{

    protected $table = 'web_social_links';

    public function store(){
        return $this->belongsTo('App\Models\Store');
    }
}