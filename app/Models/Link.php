<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Link extends Model{

    protected $table = 'web_links';

    public function websetting(){
        return $this->belongsTo('App\Models\WebSettings');
    }
} 