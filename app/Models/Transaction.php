<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */    
    protected $table = 'transaction';

    public function web_order()
    {
        return $this->belongsTo('App\Models\WebOrder', 'weborder_id');
    }
}
