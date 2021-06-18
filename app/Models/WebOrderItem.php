<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;

class WebOrderItem extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */    
    protected $table = 'web_order_items';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    //public $timestamps = false;    

    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    //protected $dateFormat = 'U';    

    // const CREATED_AT = 'creation_date';
    // const UPDATED_AT = 'last_update';

    /**
     * The connection name for the model.
     *
     * @var string
     */
    //protected $connection = 'connection-name';        

    //use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    //protected $dates = ['deleted_at'];    

    public function order()
    {
        return $this->belongsTo('App\Models\WebOrder');
    }

    public function variant()
    {
        return $this->belongsTo('App\Models\ProductVariant');
    }    
}
