<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */    
    protected $table = 'language';

    public function stores()
    {
        return $this->hasMany('App\Models\Store');
    }
}